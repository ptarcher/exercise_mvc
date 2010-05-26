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

class FITElement {
    function __construct($xml_struct)
    {
        foreach ($xml_struct as $key => $value)
            $this->$key = $xml_struct[$key];
    }
}

class FITRecord extends FITElement {
    var $timestamp;
    var $position_lat;
    var $position_long;
    var $distance;
    var $speed;
    var $heart_rate;
}

function parseRecords($xml) {
    $record_array = array();

    /* Parse the XML into tags */
    $parser = xml_parser_create();
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, $xml, $values, $tags);
    xml_parser_free($parser);

    // loop through the structures
    foreach ($tags as $key => $value) {
        if ($key == "record") {
            $molranges = $value;
            // each contiguous pair of array entries are the 
            // lower and upper range for each molecule definition
            for ($i=0; $i < count($molranges); $i+=2) {
                $offset = $molranges[$i] + 1;
                $len = $molranges[$i + 1] - $offset;
                $record_array[] = parseRecord(array_slice($values, $offset, $len));
            }
        } else {
            continue;
        }
    }
    return $record_array;
}

function parseRecord($record_values) 
{
    for ($i=0; $i < count($record_values); $i++) {
        $record[$record_values[$i]["tag"]] = $record_values[$i]["value"];
    }
    return new FITRecord($record);
}

?>
