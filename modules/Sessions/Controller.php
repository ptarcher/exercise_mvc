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

require_once('core/Module.php');
require_once('core/View.php');
require_once('modules/Sessions/UploadForm.php');
require_once('modules/Sessions/FITLap.php');
require_once('modules/Sessions/FITSession.php');
require_once('modules/Sessions/FITRecords.php');

class ModuleSessions extends CoreModule {
    var $module_description = array(
        'name'        => 'Session',
        'description' => 'View, create and edit exercise sessions',
        'version'     => '0.1',
        'author'      => 'Paul Archer',
    );

    static function _getHooks() {
        $hooks   = array();
        $hooks[] = array("hook"     => "navigator",
                         "category" => "Sessions", 
                         "name"     => "View Sessions", 
                         "module"   => "Sessions", 
                         "action"   => "view");
        /*
        $hooks[] = array("hook"     => "navigator",
                         "category" => "Sessions", 
                         "name"     => "New Session", 
                         "module"   => "Sessions", 
                         "action"   => "create");
        */

        if (isset($_SESSION['athlete']) && $_SESSION['athlete']) {
            $hooks[] = array("hook"     => "navigator",
                             "category" => "Sessions", 
                             "name"     => "File Upload", 
                             "module"   => "Sessions", 
                             "action"   => "viewUpload");
        }

        return $hooks;
    }
    
    function index() {
        $this->view();
    }

    function view() {
        $sessions = $this->api->getSessions();
        $view = CoreView::factory('sessions');
        $view->sessions = $sessions;
        $view->coach    = $_SESSION['coach'];
        echo $view->render();
    }

    function viewUpload() {
        $form = new SessionUploadForm();
        if ($form->validate()) {
            $upload = $form->getSubmitValue('form_upload');

            exec('/usr/bin/fitdecode -s '.$upload['tmp_name'], $xml_session);
            $xml_session = implode("\n", $xml_session);
            $sessions    = parseSessions($xml_session);
    
            /* There should only be one session */
            if (is_array($sessions)) {
                $session = $sessions[0];
                unset($sessions);
            }

            /* Insert the session data into the database */
            $this->api->createSessionFull($session->timestamp,
                                          'E1',
                                          'Untitled',
                                          $session->total_timer_time,
                                          $session->total_distance,
                                          $session->total_calories,
                                          $session->avg_heart_rate,
                                          $session->max_heart_rate,
                                          $session->avg_speed,
                                          $session->max_speed,
                                          $session->total_ascent,
                                          $session->total_descent,
                                          '');

            /* Find the seconds since epoch so we can do simple maths */
            $ftime = strptime($session->start_time, '%FT%T%z');
            $session_epoch = mktime($ftime['tm_hour'],
                                    $ftime['tm_min'],
                                    $ftime['tm_sec'],
                                    1 ,
                                    $ftime['tm_yday'] + 1,
                                    $ftime['tm_year'] + 1900); 
            $session_timestamp = $session->timestamp;



            $last_interval = -1;

            unset($session);
            unset($sessions);

            exec('/usr/bin/fitdecode -r '.$upload['tmp_name'], $xml_records);
            $xml_records = implode("\n", $xml_records);
            $records     = parseRecords($xml_records);

            if (is_array($records)) {
                $record_prev = $records[0];
            }
            $gradient_avg = 0;


            /* TODO: Get these from either the record OR
             * From the user settings */
            $rider_weight = 75;
            $bike_weight  = 9;

            /* Add the matching data points */
            foreach($records as $record) {
                /* Convert the timestamp into an interval */
                $ftime = strptime($record->timestamp, '%FT%T%z');
                $record_epoch = mktime($ftime['tm_hour'],
                                       $ftime['tm_min'],
                                       $ftime['tm_sec'],
                                       1 ,
                                       $ftime['tm_yday'] + 1,
                                       $ftime['tm_year'] + 1900);
                $record_interval = $record_epoch - $session_epoch;

                /* calculate the gradient */
                $gradient = 0;
                $rise = $record->altitude - $record_prev->altitude;             /* m */
                $run  = ($record->distance - $record_prev->distance) * 1000;    /* Convert to m */
                if ($run) {
                    $gradient = $rise / $run;
                }


                /* Skip duplicates, they will cause issues in graphs */
                if ($last_interval != $record_interval) {
                    $gradient_avg = $gradient_avg * 0.7 + 0.3 * $gradient;
                    echo "gradient (".$record->timestamp.") = $gradient_avg<br>";

                    if (!isset($record->power)) {
                        $power = $this->api->getPower($gradient_avg,
                                                      $record->temperature,
                                                      $record->altitude,
                                                      $record->speed,
                                                      $rider_weight,
                                                      $bike_weight);
                        echo "Power = $power<br>";
                    }

                    $this->api->insertSessionData($session_timestamp,
                                                  $record_interval,
                                                  $record->distance,
                                                  $record->heart_rate,
                                                  $record->speed,
                                                  $record->position_lat,
                                                  $record->position_long,
                                                  $record->altitude,
                                                  $record->cadence,
                                                  $record->temperature,
                                                  $record->power,
                                                  $gradient_avg);
                    $record_prev = $record;
                }
                $last_interval = $record_interval;
            }

            unset($records);
            exec('/usr/bin/fitdecode -l '.$upload['tmp_name'], $xml_laps);
            $xml_laps = implode("\n", $xml_laps);
            $laps     = parseLaps($xml_laps);
            $lap_num = 1;
            foreach($laps as $lap) {
                $ftime = strptime($lap->start_time, '%FT%T%z');
                $start_epoch = mktime($ftime['tm_hour'],
                                      $ftime['tm_min'],
                                      $ftime['tm_sec'],
                                      1 ,
                                      $ftime['tm_yday'] + 1,
                                      $ftime['tm_year'] + 1900);

                $lap_start    = $start_epoch    - $session_epoch;
                $lap_duration = $lap->total_timer_time;

                $this->api->insertLap($session_timestamp,
                                      $lap_num,
                                      $lap_start,
                                      $lap->start_position_lat,
                                      $lap->start_position_long,
                                      $lap_duration,
                                      $lap->total_calories,
                                      $lap->avg_heart_rate,
                                      $lap->max_heart_rate,
                                      $lap->avg_speed,
                                      $lap->max_speed,
                                      $lap->total_ascent,
                                      $lap->total_descent,
                                      $lap->total_distance);
                $lap_num++;
            }
        }

        $view = CoreView::factory('sessionsfileupload');
        $view->addForm($form);
        $view->subTemplate = 'genericForm.tpl';
        echo $view->render();
    }
}

?>
