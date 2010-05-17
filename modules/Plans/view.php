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

class ModulePlansView extends CoreModuleView {

    function renderPlans($sessions) {
        global $exercisePage, $html, $navigator;

        $header_fields   = array('Date', 'Type', 'Description', 'Duration', 'Distance', 'Average Speed', 'Average Heart Rate', 'Comments');
        $fields = array('session_date','type_short', 'description', 'duration', 'distance', 'avg_speed', 'avg_heartrate', 'comment');
        $table_viewer = new TableViewer($sessions);

        $exercisePage->drawHeader("Exercise Plans");
        CoreNavigator::getInstance()->viewNavigatorBar();

        $html->draw('<center>');
        $html->draw('<h1>Exercise Plans</h1>');
    
        $table_viewer->selectHeaders($header_fields);
        $table_viewer->selectFields($fields);
        $table_viewer->renderTable();

        $html->draw('</center>');

        $exercisePage->drawFooter();

        $html->render();
    }
}

?>
