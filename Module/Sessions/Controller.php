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

require_once('Module/Sessions/UploadForm.php');
require_once('Module/Sessions/FITLap.php');
require_once('Module/Sessions/FITSession.php');
require_once('Module/Sessions/FITRecords.php');

/* PEAR benchmark */
require_once 'Benchmark/Timer.php';

class Module_Sessions_Controller extends Core_Controller 
{
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
        $hooks[] = array("hook"     => "navigator",
                         "category" => "Sessions", 
                         "name"     => "Climbs", 
                         "module"   => "Sessions", 
                         "action"   => "viewClimbs");


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
        $view = Core_View::factory('sessions');
        $view->sessions = $sessions;
        $view->coach    = $_SESSION['coach'];

        echo $view->render();
    }

    function viewClimbs() {
        $climbs = $this->api->getClimbs();
        $view = Core_View::factory('climbs');
        $view->climbs = $climbs;
        $view->coach  = $_SESSION['coach'];

        echo $view->render();
    }

    function viewUpload() {
        $form = new SessionUploadForm();

        $UploadErrorString = "";
        $UploadStatus      = "Error"

        if ($form->validate()) {
            $timer = new Benchmark_Timer();
            $timer->start();
            $upload = $form->getSubmitValue('form_upload');

            $timer->setMarker('Decode Sessions - Start');
            exec('/usr/bin/fitdecode -s '.$upload['tmp_name'], $xml_session);
            $xml_session = implode("\n", $xml_session);
            $sessions    = parseSessions($xml_session);
            $timer->setMarker('Decode Sessions - End');
    
            /* There should only be one session */
            if (is_array($sessions)) {
                $session = $sessions[0];
                unset($sessions);
            }

            $db = Zend_Registry::get('db');
            $db->beginTransaction();

            try {
                /* Insert the session data into the database */
                $this->api->createSessionFull($session->start_time,
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
                $session_timestamp = $session->start_time;


                unset($session);
                unset($sessions);

                $timer->setMarker('Decode Records - Start');
                exec('/usr/bin/fitdecode -r '.$upload['tmp_name'], $xml_records);
                $xml_records = implode("\n", $xml_records);
                $records_input = parseRecords($xml_records, $session_epoch);
                $timer->setMarker('Decode Records - End');

                if (is_array($records_input)) {
                    $record_prev = $records_input[0];
                }

                /* Get the array of records, removing duplicates */
                $records = Array();
                foreach($records_input as $record) {
                    if (!isset($record_last) || 
                            $record_last->interval != $record->interval) {
                        $records[] = $record;
                    }
                    $record_last = $record;
                }
                unset($records_input);
                unset($record_last);

                $UserAPI = Module_UserManagement_API::getInstance();
                $user = $UserAPI->getUser();

                /* Add the matching data points */
                foreach($records as $record) {
                    /* Skip duplicates, they will cause issues in graphs */
                    if (!isset($record->power)) {
                        $record->power = $this->api->getPower($record->gradient,
                                                              $record->temperature,
                                                              $record->altitude,
                                                              $record->speed,
                                                              $record->speed    - $record_prev->speed,
                                                              $record->interval - $record_prev->interval,
                                                              $user['rider_weight'],
                                                              $user['bike_weight']);
                    }
                    $record_prev = $record;
                }
                unset($user);
                unset($UserAPI);

                $timer->setMarker('Record insertion - start');
                $this->api->insertAllSessionData($session_timestamp, $records);

                /* Insert all the data */
                $timer->setMarker('Record insertion - end');

                /* Calculate the climbs */
                $climbs = $this->api->getClimbCategories();

                $timer->setMarker('Climb - Start');
                $min_climb = $climbs[0];

                /* 500m with an average gradient of more than 3% (cat 5)*/
                /* Find the points that have a distance of 500m */
                $window_distance = 0;
                $window_altitude = 0;
                $cat             = -1;
                $climb_num       = 1;

                $num_records = count($records);
                $num_climbs  = count($climbs);

                for ($front = 0, $back = 0; $front < $num_records; $front++) {
                    $window_distance += $records[$front]->delta_distance * 1000;
                    $window_altitude += $records[$front]->delta_altitude;

                    if ($window_distance > $min_climb['min_distance']) {
                        $window_gradient = ($window_altitude/$window_distance)*100;

                        /* Check if we have found the start of a climb */
                        if ($cat == -1 && (($window_gradient >= $climbs[$cat+1]['min_gradient']))) {
                            $cat++;

                            /* Go through and find the minimum height */
                            $min = $back;
                            for ($i = $back; $i < $front; $i++) {
                                if ($records[$i]->altitude <= $records[$min]->altitude) {
                                    $min = $i;
                                }
                            }
                            $climb['bottom']       = $records[$min]->interval;
                            $climb['min_altitude'] = $records[$min]->altitude;
                        }

                        /* Check if we have finished the climb */
                        if ($cat != -1 && ($window_gradient < $climbs[$cat]['min_gradient'])) {
                            /* Need to go back and find the maximum altitude */
                            $max = $back;
                            for ($i = $back; $i < $front; $i++) {
                                if ($records[$i]->altitude > $records[$max]->altitude) {
                                    $max = $i;
                                }
                            }
                            $climb['top']          = $records[$max]->interval;
                            $climb['max_altitude'] = $records[$max]->altitude;

                            /* Get the max gradient */
                            $climb['gradient_max'] = $records[$min]->gradient;
                            for ($i = $min; $i <= $max; $i++) {
                                if ($climb['gradient_max'] < $records[$i]->gradient) {
                                    $climb['gradient_max'] = $records[$i]->gradient;
                                }
                            }

                            /* Tally the totals */
                            $climb['total_climbed'] = 0;
                            for ($i = $min+1; $i <= $max; $i++) {
                                $climb['total_climbed']  += $records[$i]->delta_altitude;
                            }

                            $climb['total_distance'] = round($records[$max]->distance - $records[$min]->distance, 2);
                            $climb['gradient_avg']   = round(($climb['total_climbed'] / ($climb['total_distance'] * 1000)) * 100, 2);

                            /* Find the category of the climb */
                            $cat = -1;
                            while ((($cat+1) < $num_climbs) && 
                                    ($climb['gradient_avg']        >= $climbs[$cat+1]['min_gradient'])  &&
                                    ($climb['total_distance']*1000 >= $climbs[$cat+1]['min_distance']) &&
                                    ($climb['total_climbed']       >= $climbs[$cat+1]['min_height'])) {
                                $cat++;
                            }
                            $climb['cat']            = $cat;

                            if ($cat != -1) {
                                /* Store it into the database */
                                $this->api->insertClimb($session_timestamp,       $climb_num++,
                                                        $climb['bottom'],         $climb['top'],
                                                        $climb['gradient_avg'],   $climb['gradient_max'],
                                                        $climb['total_distance'], $climb['total_climbed'],
                                                        $climb['min_altitude'],   $climb['max_altitude']);

                                /* Start search for the next climb */
                                $front           = $max;
                                $back            = $max;
                                $window_distance = 0;
                                $window_altitude = 0;
                            } else {
                                /* It was a false climb, either not steep enough, 
                                 * too short, and the window just masked this 
                                 * Keep searching for the next climb
                                 */
                            }

                            $cat = -1;
                        }

                        /* Move the back of the window up */
                        while ($window_distance > $min_climb['min_distance'] && $back < $num_records) {
                            $window_distance -= $records[$back]->delta_distance * 1000;
                            $window_altitude -= $records[$back]->delta_altitude;
                            $back++;
                        }
                    }
                }
                $timer->setMarker('Climb - End');

                /*
                 * Bikes
                 * userid
                 * name
                 * description
                 * type, TT or Road
                 * weight
                 * picture?
                 * Assign a bike to an exercise session at creation time?
                 */

                unset($records);

                $timer->setMarker('Laps - Start');
                exec('/usr/bin/fitdecode -l '.$upload['tmp_name'], $xml_laps);
                $xml_laps = implode("\n", $xml_laps);
                $laps     = parseLaps($xml_laps);
                $timer->setMarker('Laps - End');

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
                    $this->api->insertLap($session_timestamp,
                                          $lap_num,
                                          $lap_start,
                                          $lap->start_position_lat,
                                          $lap->start_position_long,
                                          $lap->total_timer_time,
                                          $lap->total_elapsed_time,
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
                //$timer->display();

                $db->commit();
                $UploadErrorString = "Session can be view here";
                $UploadStatus      = "Success";
            } catch (Exception $e) {
                $db->rollback();
                $UploadErrorString = "Failed to upload";
                $e->getMessage();
            }
            //$timer->display();
        }

        $view = Core_View::factory('sessionsfileupload');
        $view->addForm($form);
        $view->UploadErrorString = $UploadErrorString;
        $view->subTemplate = 'genericForm.tpl';
        echo $view->render();
    }
}

?>
