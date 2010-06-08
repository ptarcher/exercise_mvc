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

class ModulePlans extends CoreModule {
    var $module_description = array(
        'name'        => 'Plans',
        'description' => 'View, create and edit exercise plans',
        'version'     => '0.1',
        'author'      => 'Paul Archer',
    );

    static function _getHooks() {
        $hooks = array(
            array("hook"     => "navigator",
                  "category" => "Plans", 
                  "name"     => "View Plans", 
                  "module"   => "Plans", 
                  "action"   => "view"),
            array("hook"     => "navigator",
                  "category" => "Plans", 
                  "name"     => "Create a new Daily Plan", 
                  "module"   => "Plans", 
                  "action"   => "createDaily"),
            array("hook"     => "navigator",
                  "category" => "Plans", 
                  "name"     => "Create a new Weekly Plan", 
                  "module"   => "Plans", 
                  "action"   => "createWeekly"),

        );

        return $hooks;
    }

    function index() {
        $this->view();
    }
    
    function view() {
        $weekly_plans = $this->api->getWeeklyPlans();
        $view = CoreView::factory('plans');
        $view->weekly_plans = $weekly_plans;

        echo $view->render();
    }

    function createDaily() {
    }

    function weeklyDaily() {
    }
}

?>
