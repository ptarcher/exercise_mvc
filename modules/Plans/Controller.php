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

require_once('Core/Module.php');
require_once('Core/View.php');

class Module_Plans extends Core_Module {
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
        );

        return $hooks;
    }

    function index() {
        $this->view();
    }
    
    function view() {
        $weekly_plans = $this->api->getWeeklyPlans();
        $view = Core_View::factory('plans');
        $view->plans = $weekly_plans;

        echo $view->render();
    }

    function viewDaily() {
        $week_date   = Core_Common::getRequestVar('week_date', null, 'string');

        $daily_plans = $this->api->getDailyPlans($week_date);

        $view = Core_View::factory('daily');
        $view->plans     = $daily_plans;
        $view->week_date = $week_date;

        echo $view->render();
    }

    function createDaily() {
    }

    function weeklyDaily() {
    }
}

?>
