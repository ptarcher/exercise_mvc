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

require_once('core/ModuleView.php');
require_once('core/TableViewer.php');
require_once('core/View.php');

class ModuleSessionsView extends CoreModuleView {
    function renderSessions($sessions) 
    {
    }

    function renderUpload($exercise_types) {
        global $exercisePage, $html, $navigator;

        $exercisePage->drawHeader("Exercise Sessions");
        $navigator->viewNavigatorBar();

        $html->draw('<center>');
        $html->draw('<h1>Upload an Exercise Session</h1>');
        $html->draw('</center>');

                       
       $items = array(array("name" => "Type", "type" => "select", 
                             "options" => $exercise_types,
                      "attributes" => array("name" => "type")),
                      array("name" => "Description", "type" => "input",
                      "attributes" => array("name" => "description",
                                            "type" => "text")),
                      array("name" => "Comments", "type" => "input",
                       "attributes" => array("name" => "comments",
                                             "type" => "text")),
                      array("name" => "File", "type" => "input",
                      "attributes" => array("name" => "file",
                                            "type" => "file")),
                      array(                "type" => "input",
                      "attributes" => array("type"    => "button",
                                             "value"   => "Upload",
                                             "onClick" => "this.submit()"))
                      );
        $form = new HtmlForm($items, "Sessions", "doUpload", "POST");

        $exercisePage->drawFooter();

        $html->render();
    }

    function renderCreate($exercise_types) 
    {
        global $exercisePage, $html, $navigator;

        $exercisePage->drawHeader("Exercise Sessions");
        $navigator->viewNavigatorBar();

        $html->draw('<center>');
        $html->draw('<h1>Add a new Exercise Session</h1>');
        $html->draw('</center>');

        $items = array(array("name" => "Date", "type" => "input",
                       "attributes" => array("name" => "date",
                                             "type" => "text")),
                       array("name" => "Type", "type" => "select", 
                             "options" => $exercise_types,
                       "attributes" => array("name" => "type")),
                       array("name" => "Description", "type" => "input",
                       "attributes" => array("name" => "description",
                                             "type" => "text")),
                       array("name" => "Duration", "type" => "input",
                       "attributes" => array("name" => "duration",
                                             "type" => "text")),
                       array("name" => "Distance", "type" => "input",
                       "attributes" => array("name" => "distance",
                                             "type" => "text")),
                       array("name" => "Average Speed", "type" => "input",
                       "attributes" => array("name" => "avg_speed",
                                             "type" => "text")),
                       array("name" => "Average Heart Rate", "type" => "input",
                       "attributes" => array("name" => "avg_hr",
                                             "type" => "text")),
                       array("name" => "Comments", "type" => "input",
                       "attributes" => array("name" => "comments",
                                             "type" => "text")),
                       array(                "type" => "input",
                       "attributes" => array("type"    => "button",
                                             "value"   => "Create",
                                             "onClick" => "this.submit()"))
                      );
        $form = new HtmlForm($items, "Sessions", "doUpload", "POST");

        $exercisePage->drawFooter();

        $html->render();
    }
}

?>
