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
                             "name"     => "Upload from File", 
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

    function doCreate() {
        // TODO: Check if it was successful
        $this->api->createSession($_SESSION['userid'], 
                                  $_POST['date'],
                                  $_POST['type'],
                                  $_POST['description'],
                                  $_POST['duration'],
                                  $_POST['distance'],
                                  $_POST['avg_hr'],
                                  $_POST['avg_speed'],
                                  $_POST['comments']);

        // TODO: Display it was successful
        $this->view->renderCreate($exercise_types);
    }

    /*
    function viewUpload() {
        //$exercise_types = $this->api->getExerciseTypes();
        $this->view->renderUpload($exercise_types);
    }*/

    function viewUpload() {
        $form = new SessionUploadForm();
        if ($form->validate()) {
            $upload = $form->getSubmitValue('form_upload');

            exec('/usr/bin/fitdecode -r '.$upload['tmp_name'], $xml_records);
            exec('/usr/bin/fitdecode -l '.$upload['tmp_name'], $xml_laps);
            exec('/usr/bin/fitdecode -s '.$upload['tmp_name'], $xml_session);

            $xml_records = implode("\n", $xml_records);
            $xml_laps    = implode("\n", $xml_laps);
            $xml_session = implode("\n", $xml_session);

            //print_r($xml_session);
            $laps     = parseLaps($xml_laps);
            $sessions = parseSessions($xml_session);
            $records  = parseRecords($xml_records);
    
            /* There should only be one session */
            if (is_array($sessions)) {
                $session = $sessions[0];
            }

            /* Insert the session data into the database */
            $this->api->createSession($session->timestamp,
                                      'E1',
                                      'Untitled',
                                      $session->total_timer_time,
                                      $session->total_distance,
                                      $session->avg_heart_rate,
                                      $session->avg_speed,
                                      '');

            /* Find the seconds since epoch so we can do simple maths */
            $ftime = strptime($session->start_time, '%FT%T%z');
            $session_epoch = mktime($ftime['tm_hour'],
                                    $ftime['tm_min'],
                                    $ftime['tm_sec'],
                                    1 ,
                                    $ftime['tm_yday'] + 1,
                                    $ftime['tm_year'] + 1900); 

            $last_interval = -1;
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

                /* Skip duplicates, they will cause issues in graphs */
                if ($last_interval != $record_interval) {
                    /* TODO: add the other factors */
                    $this->api->insertSessionData($session->timestamp,
                                                  $record_interval,
                                                  $record->distance,
                                                  $record->heart_rate,
                                                  $record->speed,
                                                  $record->position_lat,
                                                  $record->position_long,
                                                  $record->altitude,
                                                  $record->cadence,
                                                  $record->temperature,
                                                  $record->power);
                }
                $last_interval = $record_interval;
            }

            //print_r($laps);
            //print_r($session);
            //print_r($records);
        }

        $view = CoreView::factory('sessionsfileupload');
        $view->addForm($form);
        $view->subTemplate = 'genericForm.tpl';
        echo $view->render();
    }


    function doUpload() {
    }
}

?>
