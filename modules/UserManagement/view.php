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

class ModuleUserManagementView extends CoreModuleView {

    function renderUsers($sessions) {
        global $exercisePage, $html;

        $header_fields   = array('User ID');
        $fields = array('userid');
        $table_viewer = new TableViewer($sessions);

        $exercisePage->drawHeader("UserManagement");
        CoreNavigator::getInstance()->viewNavigatorBar();

        $html->draw('<center>');
        $html->draw('<h1>UserManagement</h1>');
    
        $table_viewer->selectHeaders($header_fields);
        $table_viewer->selectFields($fields);
        $table_viewer->renderTable();

        $html->draw('</center>');

        $exercisePage->drawFooter();

        $html->render();
    }

    function renderCreate($user_types) 
    {
        global $exercisePage, $html, $navigator;

        $exercisePage->drawHeader("Exercise UserManagement");
        $navigator->viewNavigatorBar();

        $html->draw('<center>');
        $html->draw('<h1>Add a new User</h1>');
        $html->draw('</center>');

        $items = array(array("name" => "User ID", "type" => "input",
                       "attributes" => array("name" => "userid",
                                             "type" => "text")),
                       array("name" => "Type", "type" => "select", 
                             "options" => $user_types,
                       "attributes" => array("name" => "type")),
                       array("name" => "Password", "type" => "input",
                       "attributes" => array("name" => "password",
                                             "type" => "password")),
                       array(                "type" => "input",
                       "attributes" => array("type"    => "button",
                                             "value"   => "Create",
                                             "onClick" => "this.submit()"))
                      );
        $form = new HtmlForm($items, "UserManagement", "doCreate", "POST");

        $exercisePage->drawFooter();

        $html->render();
    }
}

?>
