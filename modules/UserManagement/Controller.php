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
require_once('modules/UserManagement/AddUserFrom.php');

class ModuleUserManagement extends CoreModule {
    var $module_description = array(
        'name'        => 'Session',
        'description' => 'View, create and edit exercise sessions',
        'version'     => '0.1',
        'author'      => 'Paul Archer',
    );

    static function _getHooks() {
        $hooks   = array();

        $hooks[] = array("hook"     => "navigator",
                         "category" => "User", 
                         "name"     => "Settings", 
                         "module"   => "UserManagement", 
                         "action"   => "settings");
        $hooks[] = array("hook"     => "navigator",
                         "category" => "User", 
                         "name"     => "Bikes", 
                         "module"   => "UserManagement", 
                         "action"   => "bikes");

        if (isset($_SESSION['superuser']) && $_SESSION['superuser']) {
            $hooks[] = array("hook"     => "navigator",
                             "category" => "UserManagement", 
                             "name"     => "View Users", 
                             "module"   => "UserManagement", 
                             "action"   => "view");
            $hooks[] = array("hook"     => "navigator",
                             "category" => "UserManagement", 
                             "name"     => "New User", 
                             "module"   => "UserManagement", 
                             "action"   => "create");
        }

        return $hooks;
    }

    function index() {
        return $this->view();
    }
    
    function view() {
        /* TODO: Check if administrator */
        $users = $this->api->getUsers();

        $view = CoreView::factory('users');
        $view->users = $users;
        echo $view->render();
    }

    function create() {
        $user_types = $this->api->getExerciseTypes();

        $form = new AddUserForm();
        if ($form->validate()) {
            $userid   = $form->getSubmitValue('adduserform_login');
            $password = $form->getSubmitValue('adduserform_password');
            $coach    = $form->getSubmitValue('adduserform_coach');
            $athlete  = $form->getSubmitValue('adduserform_athlete');
            $usertype = $form->getSubmitValue('adduserform_usertype');

            $success = $this->api->createUser($userid, $password, $coach, $athlete, $usertype);
            if ($success) {
                /* We have sucessfully logged in, now lets 
                 * display the next page */
                 /*
                if (!isset($redirect_module) || !isset($redirect_action)) {
                    $redirect_module = 'Sessions';
                }

                Url::redirectToUrl($urlToRedirect);
                */
            }
        }

        $view = CoreView::factory('adduser');
        //$view->users = $users;
        $view->addForm($form);
        $view->subTemplate = 'genericForm.tpl';

        /* Coach radio buttons */
        $view->assign('coach_types', array('y' => 'Yes',
                                           'n' => 'No'));
        $view->assign('coach_selected', 'n');

        /* Athlete radio buttons */
        $view->assign('athlete_types', array('y' => 'Yes',
                                             'n' => 'No'));
        $view->assign('athlete_selected', 'n');

        /* Athlete radio buttons */
        $view->assign('usertype_types', array('user'      => 'User',
                                              'superuser' => 'SuperUser'));
        $view->assign('usertype_selected', 'user');

        echo $view->render();
    }

    function settings() {
        $view = CoreView::factory('usersettings');

        $user = $this->api->getUser();

        $settings   = array();
        $settings[] = array("name"     => 'UserID',
                            "id"       => 'userid', 
                            "value"    => $user['userid'], 
                            "editable" => false);

        $settings[] = array("name"     => 'Password',
                            "id"       => 'password', 
                            "value"    => '********', 
                            "editable" => true);

        $settings[] = array("name"     => 'Maximum Heart Rate',
                            "id"       => 'max_heartrate', 
                            "value"    => $user['max_heartrate'], 
                            "editable" => true);

        $settings[] = array("name"     => 'Resting Heart Rate',
                            "id"       => 'resting_heartrate', 
                            "value"    => $user['resting_heartrate'], 
                            "editable" => true);

        $settings[] = array("name"     => 'Date of Birth',
                            "id"       => 'dob', 
                            "value"    => $user['dob'], 
                            "editable" => true);

        $settings[] = array("name"     => 'Rider Weight',
                            "id"       => 'rider_weight', 
                            "value"    => $user['rider_weight'], 
                            "editable" => true);

        $settings[] = array("name"     => 'Bike Weight',
                            "id"       => 'bike_weight', 
                            "value"    => $user['bike_weight'], 
                            "editable" => true);

        $settings[] = array("name"     => 'Athlete',
                            "id"       => 'athlete', 
                            "value"    => $user['athlete'] ? 'Yes' : 'No', 
                            "editable" => true);

        $settings[] = array("name"     => 'Coach',
                            "id"       => 'coach', 
                            "value"    => $user['coach'] ? 'Yes' : 'No', 
                            "editable" => true);

        $view->settings = $settings;

        echo $view->render();
    }

    function bikes() {
        $view = CoreView::factory('bikes');

        echo $view->render();
    }

}

?>
