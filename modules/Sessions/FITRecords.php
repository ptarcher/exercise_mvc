<?php
/*
 *  Description: Display simple single digits of the current weather.
 *  Date:        02/06/2009
 *  
 *  Author:      Paul Archer <ptarcher@gmail.com>
 *
 * Copyright (C) 2009  Paul Archer
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.

 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
require_once "modules/Sessions/FITElement.php";

class FITRecord extends FITElement {
    var $timestamp;
    var $position_lat;
    var $position_long;
    var $distance;
    var $speed;
    var $heart_rate;
    var $altitude;
    var $cadence;
    var $temperature;
    var $power;

    /* Calculated */
    var $interval;
    var $gradient;
    var $delta_altitude;
    var $delta_distance;
}

function parseRecords($xml, $session_epoch) {
    $records = array();

    $timer = new Benchmark_Timer();
    $timer->start();

    $timer->setMarker('Parse XML to tags - start');
    /* Parse the XML into tags */
    $parser = xml_parser_create();
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, $xml, $values, $tags);
    xml_parser_free($parser);

    // loop through the structures
    $timer->setMarker('tags to arrays - start');
    foreach ($tags as $key => $value) {
        if ($key == "record") {
            $molranges = $value;
            // each contiguous pair of array entries are the 
            // lower and upper range for each molecule definition
            for ($i = 0; $i < count($molranges); $i += 2) {
                $offset = $molranges[$i] + 1;
                $len    = $molranges[$i + 1] - $offset;
                $records[] = parseRecord($values, $offset, $len);
            }
        } else {
            continue;
        }
    }
    $timer->setMarker('tags to array - done');

    $i = 0;

    $timer->setMarker('gradient calcs - start');
    /* Gradient calc constants */
    $NUM_GRADIENT_SAMPES = 11;
    $LOW_OFFSET          = floor($NUM_GRADIENT_SAMPES/2);
    $HIGH_OFFSET         = floor($NUM_GRADIENT_SAMPES/2);

    /* Create the window function */
    /* Tukey window */
    $alpha = 0.5;
    $window = array();
    for ($i = 0; $i < $NUM_GRADIENT_SAMPES; $i++) {
        if ($i <= ($alpha*$NUM_GRADIENT_SAMPES/2)) {
            $window[$i] = 0.5 * (1 + cos(M_PI * (2*$i/($alpha*$NUM_GRADIENT_SAMPES) - 1)));
        } else if ($i <= $NUM_GRADIENT_SAMPES*(1-$alpha/2)) {
            $window[$i] = 1.0;
        } else {
            $window[$i] = 0.5 * (1 + cos(M_PI * (2*$i/($alpha*$NUM_GRADIENT_SAMPES) - 2/$alpha + 1)));
        }
    }

    $i = 0;
    $num_records = count($records);
    foreach($records as $record) {
        /* Convert the timestamp into an interval */
        $ftime = strptime($record->timestamp, '%FT%T%z');
        $record_epoch = mktime($ftime['tm_hour'],
                $ftime['tm_min'],
                $ftime['tm_sec'],
                1 ,
                $ftime['tm_yday'] + 1,
                $ftime['tm_year'] + 1900);
        $record->interval = $record_epoch - $session_epoch;

        if ($i > 0) {
            $record->delta_distance = $record->distance - $records[$i-1]->distance;
            $record->delta_altitude = round($record->altitude - $records[$i-1]->altitude, 2);
        } else {
            $record->delta_distance = 0;
            $record->delta_altitude = 0;
        }

        /* Calculate the average gradient */
        $total_rise     = 0;
        $total_distance = 0;
        unset($first_distance);
        $last_distance = 0;
        for ($g = $i - $LOW_OFFSET, $j = 0; $g <= $i + $HIGH_OFFSET; $g++, $j++) {
            if ($g >= 0 && $g < $num_records) {
                if (!isset($first_distance)) {
                    $first_distance = $records[$g]->distance;
                }
                $total_rise     += ($records[$g]->altitude - $record->altitude)*$window[$j];
                $last_distance = $records[$g]->distance;
            }
        }
        $avg_rise     = $total_rise     / $NUM_GRADIENT_SAMPES;
        $avg_distance = (($last_distance-$first_distance) / $NUM_GRADIENT_SAMPES) * 1000;;

        if ($avg_distance) {
            $record->gradient = round($avg_rise / $avg_distance * 100, 1);
        } else {
            $record->gradient = 0;
        }


        /* TODO: Calculate the power */

        $i++;
    }
    $timer->setMarker('gradient calcs - done');

    $timer->display();

    return $records;
}

function parseRecord($record_values, $offset, $len) 
{
    for ($i=0; $i < $len; $i++) {
        $record[$record_values[$i+$offset]["tag"]] = $record_values[$i+$offset]["value"];
    }

    return new FITRecord($record);
}


?>
