<?php
/**
 * Controller functions for UserManagement.
 *
 * PHP version 5
 *
 * @category  Bike
 * @package   UserManagement
 * @author    Paul Archer <ptarcher@gmail.com>
 * @copyright 2009 Paul Archer
 * @license   http://www.gnu.org/licenses/agpl-3.0.txt AGPL-3 .0
 * @version   Release: 1.0
 * @link      http://paul.archer.tw
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

require_once 'Module/UserManagement/AddUserFrom.php';


/**
 * Controller functions for UserManagement.
 *
 * @category Bike
 * @package  UserManagement
 * @author   Paul Archer <ptarcher@gmail.com>
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt AGPL-3 .0
 * @link     http://paul.archer.tw
 */
class Module_UserManagement_Controller extends Core_Controller
{
    /**
     * The default controller.
     *
     * @return The Webpage Text
     */
    function index() 
    {
        return $this->view();
    }
    
    /**
     * View the list of users.
     *
     * @return The Webpage Text
     */
    function view() 
    {
        $api = new Module_UserManagement_API();

        /* TODO: Check if administrator */
        $users = $api->getUsers();
        $view  = Core_View::factory('users');

        $view->users = $users;
        echo $view->render();
    }

    /**
     * Create a new user form.
     *
     * @return The Webpage Text
     */
    function create() 
    {
        $api = new Module_UserManagement_API();

        $user_types = $api->getExerciseTypes();

        $form = new AddUserForm();
        if ($form->validate()) {
            $userid   = $form->getSubmitValue('login');
            $password = $form->getSubmitValue('password');
            $coach    = $form->getSubmitValue('coach');
            $athlete  = $form->getSubmitValue('athlete');
            $usertype = $form->getSubmitValue('usertype');

            $success = $api->createUser($userid, $password, 
                                        $coach, $athlete, $usertype);
            if ($success) {
                /* We have sucessfully logged in, now lets 
                 * display the next page */
                 /*
                if (!isset($redirect_module) || !isset($redirect_action)) {
                    $redirect_module = 'Sessions';
                }

                Core_Url::redirectToUrl($urlToRedirect);
                */
            }
        }

        $view = Core_View::factory('adduser');
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

    /**
     * Update the current users settings.
     *
     * @return The Webpage Text
     */
    function settings() 
    {
        $api  = new Module_UserManagement_API();
        $view = Core_View::factory('usersettings');

        $user = $api->getUser();

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

    /**
     * Show the list of the users current bikes.
     *
     * @return The Webpage Text
     */
    function bikes() 
    {
        $api  = new Module_UserManagement_API();
        $view = Core_View::factory('bikes');

        $view->bikes = $api->getBikes();

        echo $view->render();
    }

    /**
     * View the parts on a bike.
     *
     * @return The Webpage Text
     */
    function viewBike() 
    {
        $api     = new Module_UserManagement_API();
        $bike_id = Core_Common::getRequestVar('id', null, 'int');
        $view    = Core_View::factory('viewBike');

        $view->bikes = $api->getBikes();
        $view->parts = $api->getBikeData($bike_id);

        echo $view->render();
    }
}
