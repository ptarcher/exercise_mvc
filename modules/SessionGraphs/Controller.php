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

class ModuleSessionGraphs extends CoreModule {
    var $module_description = array(
        'name'        => 'Session Graphs',
        'description' => 'View Exercise Session Graphs',
        'version'     => '0.1',
        'author'      => 'Paul Archer',
    );

    function index() {
        $this->view();
    }

    function view() {
        $session_date = Common::getRequestVar('session_date', null, 'string');

        $session = $this->api->getSession($session_date);
        $laps    = $this->api->getLaps($session_date);
        $zones   = $this->api->getZones($session_date);

        $view = CoreView::factory('sessiongraphs');
        $view->session_date = $session_date;
        $view->laps         = $laps;
        $view->zones        = $zones;

        $session_labels = array();
        $session_labels[] = array("label" => 'Date',
                                  "value" => $session['session_date'],
                                  "id"    => 'session_date',
                                  "units" => '');
        $session_labels[] = array("label" => 'Duration',
                                  "value" => $session['duration'],
                                  "id"    => 'duration',
                                  "units" => '');
        $session_labels[] = array("label" => 'Distance',
                                  "value" => $session['distance'],
                                  "id"    => 'distance',
                                  "units" => 'km');
        $session_labels[] = array("label" => 'Avg Speed',
                                  "value" => $session['avg_speed'],
                                  "id"    => 'avg_speed',
                                  "units" => 'km/h');
        $session_labels[] = array("label" => 'Max Speed',
                                  "value" => $session['max_speed'],
                                  "id"    => 'max_speed',
                                  "units" => 'km/h');
        $session_labels[] = array("label" => 'Avg Heart Rate',
                                  "value" => $session['avg_heartrate'],
                                  "id"    => 'avg_heartrate',
                                  "units" => 'bpm');
        $session_labels[] = array("label" => 'Max Heart Rate',
                                  "value" => $session['max_heartrate'],
                                  "id"    => 'max_heartrate',
                                  "units" => 'bpm');
        $session_labels[] = array("label" => 'Avg Heart Percent',
                                  "value" => $session['avg_heartrate_percent'],
                                  "id"    => 'avg_heartrate_percent',
                                  "units" => '%');
        $session_labels[] = array("label" => 'Max Heart Percent',
                                  "value" => $session['max_heartrate_percent'],
                                  "id"    => 'max_heartrate_percent',
                                  "units" => '%');
        $session_labels[] = array("label" => 'Energy',
                                  "value" => round($session['calories']*4.184),
                                  "id"    => 'calories',
                                  "units" => 'kJ');
        $session_labels[] = array("label" => 'Total Ascent',
                                  "value" => $session['total_ascent'],
                                  "id"    => 'total_ascent',
                                  "units" => 'm');
        $session_labels[] = array("label" => 'Total Descent',
                                  "value" => $session['total_descent'],
                                  "id"    => 'total_descent',
                                  "units" => 'm');
        $view->session = $session_labels;

        echo $view->render();
    }
}

?>
