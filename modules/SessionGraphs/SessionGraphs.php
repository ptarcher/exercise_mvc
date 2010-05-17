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

        $view = CoreView::factory('sessiongraphs');
        $view->session_date = $session_date;
        echo $view->render();
    }
}

?>
