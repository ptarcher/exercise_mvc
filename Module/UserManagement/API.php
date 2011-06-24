<?php
/**
 * API Access functions for UserManagement.
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

/**
 * API Access functions for UserManagement.
 *
 * @category Bike
 * @package  UserManagement
 * @author   Paul Archer <ptarcher@gmail.com>
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt AGPL-3 .0
 * @link     http://paul.archer.tw
 */
class Module_UserManagement_API extends Core_ModuleAPI
{
    static private $_instance = null;
    /**
     * Returns the singleton ModuleUserManagementAPI
     *
     * @return ModuleUserManagementAPI
     */
    static public function getInstance()
    {
        if (self::$_instance == null) {
            $c               = __CLASS__;
            self::$_instance = new $c();
        }
        return self::$_instance;
    }

    /**
     * Get the given username configuration
     *
     * @return Array of the user fields
     */
    function getUser($userid = "") 
    {
        if ($userid === "") {
            $userid = Core_Common::getCurrentUserLogin();
        }
        Core_Common::checkUserIsSuperUserOrTheUser($userid);

        var_dump($userid);

        $db     = Zend_Registry::get('db');
        $select = $db->select()
                     ->from('t_users',
                             array('userid',
                                   'coach',
                                   'athlete',
                                   'superuser',
                                   'dob'=>'TO_CHAR(dob, \'DD/MM/YYYY\')',
                                   'max_heartrate',
                                   'resting_heartrate',
                                   'rider_weight',
                                   'bike_weight'))
                     ->where('userid = ?', $userid);

        $stmt  = $db->query($select);
        $users = $stmt->fetchAll();

        if (count($users) == 0) {
            throw new Exception('Invalid username');
        }

        return $users[0];
    }

    /**
     * Update a users setting
     *
     * @return null
     */
    function updateSetting($id, $value)
    {
        $db = Zend_Registry::get('db');

        $valid_fields = array('coach',
                              'athlete',
                              'max_heartrate',
                              'resting_heartrate',
                              'rider_weight',
                              'bike_weight',
                              'password',
                              'dob');
        if (!in_array($id, $valid_fields)) {
            return;
        }

        if ($id == 'password') {
            /* Need to combine with salt etc */
            $password_salt = Core_Common::getRandomString(64);
            $password_hash = sha1($value . $password_salt);

            $db->update('t_users',
                    array('password_hash' => $password_hash,
                          'password_salt' => $password_salt),
                    'userid = \''.Core_Common::getCurrentUserLogin().'\'');
        } else {
            $db->update('t_users',
                    array($id => $value),
                    'userid = \''.Core_Common::getCurrentUserLogin().'\'');
        }
    }

    /**
     * Get the list of users
     *
     * @return Array of users
     */
    function getUsers() 
    {
        if (!Core_Access::isSuperUser()) {
            throw exception('You need to be super user to perform this action');
        }

        $db     = Zend_Registry::get('db');
        $select = $db->select()
                     ->from('t_users',
                             array('userid',
                                   'coach',
                                   'athlete',
                                   'superuser'))
                     ->order('userid DESC');
        $stmt   = $db->query($select);

        return $stmt->fetchAll();
    }

    /**
     * Create a new user
     *
     * @return null
     */
    function createUser($userid, $password, $email, $coach = 'f', 
                        $athlete = 't', $superuser = 'f') 
    {
        $password_salt = Core_Common::getRandomString(64);
        $password_hash = sha1($password . $password_salt);

        $db   = Zend_Registry::get('db');
        $auth = Zend_Registry::get('auth');

        $auth_token = $auth->getHashTokenAuth($userid, $password_hash);

        if (!Core_Access::isSuperUser()) {
            $superuser = 'f';
        }

        $db->insert('t_users',
                array('userid'        => $userid,
                      'password_hash' => $password_hash,
                      'password_salt' => $password_salt,
                      'email'         => $email,
                      'coach'         => $coach,
                      'athlete'       => $athlete,
                      'superuser'     => $superuser,
                      'token'         => $auth_token));
    }

    /**
     * Get the possible exercise types
     *
     * @return Array of exercise types
     */
    function getExerciseTypes() 
    {
        // TODO: Convert this into user groups
        $db     = Zend_Registry::get('db');
        $select = $db->select()
                     ->from('t_training_types',
                             array('type_short',
                                   'type'))
                     ->order('type_short');
        $stmt   = $db->query($select);
        $types  = $stmt->fetchAll();

        /* Convert into a nice display table */
        $exercise_types = array();
        foreach ($types as $type) {
            $description = $type['type_short'] . ' - ' . $type['type'];

            $exercise_types[$type['type_short']] = $description;
        }

        return $exercise_types;
    }

    /**
     * Get the Authorisation Token
     *
     * @return token
     */
    public function getTokenAuth($login, $password_hash)
    {
        return md5($login . $password_hash);
    }

    /**
     * Get the list of the users bikes
     *
     * @return Array of bikes
     */
    public function getBikes()
    {
        $userid = Core_Common::getCurrentUserLogin();
        $db     = Zend_Registry::get('db');

        $select = $db->select()
                     ->from('t_users_bikes',
                             array('userid',
                                   'id',
                                   'name',
                                   'type',
                                   'description',
                                   'created'))
                     ->where('userid = ?', $userid)
                     ->order(array('type', 'name', 'description'));
        $stmt   = $db->query($select);
        $bikes  = $stmt->fetchAll();

        return $bikes;
    }

    /**
     * Add a bike part to a given $bike_id
     *
     * @return null
     */
    function insertBikeData($bike_id, $category, $part, $description, 
                            $inspection_period_km, $inspection_period_date)
    {
        $db     = Zend_Registry::get('db');
        $userid = Core_Common::getCurrentUserLogin();

        $db->insert('t_users_bikes_parts',
                array('userid'        => $userid,
                      'bike_id'       => $bike_id,
                      'category'      => $category,
                      'part'          => $part,
                      'description'   => $description,
                      'inspection_period_km'   => $inspection_period_km,
                      'inspection_period_date' => $inspection_period_date));
    }

    /**
     * Update a bike parts details
     *
     * @return null
     */
    function updateBikeData($bike_id, $part_id, 
                            $category, $part, $description, 
                            $inspection_period_km, $inspection_period_date,
                            $inspected, $withdrawn)
    {
        $userid = Core_Common::getCurrentUserLogin();
        $db     = Zend_Registry::get('db');

        /* TODO: Add the withdrawn       */
        /* TODO: Add the last inspection */
        if ($inspected) {
        }

        if ($withdrawn) {
        }

        $db->update('t_users',
                array('bike_id'                => $bike_id,
                      'id'                     => $part_id,
                      'category'               => $category,
                      'part'                   => $part,
                      'description'            => $description,
                      'inspection_period_km'   => $inspection_period_km,
                      'inspection_period_date' => $inspection_period_date),
                array('userid' => $userid));
    }

    /**
     * Delete a bike part from the system
     *
     * @return null
     */
    function deleteBikeData($bike_id, $part_id)
    {
        $userid = Core_Common::getCurrentUserLogin();
        $db     = Zend_Registry::get('db');

        $db->delete('t_users_bikes_parts',
                    array('userid'  => $userid,
                          'bike_id' => $bike_id,
                          'id'      => $part_id));
    }

    /**
     * Get the list of bike parts
     *
     * @return Array of bike parts
     */
    function getBikeData($bike_id)
    {
        $userid = Core_Common::getCurrentUserLogin();
        $db     = Zend_Registry::get('db');

        $select = $db->select()
                     ->from('t_users_bikes_parts',
                             array('userid',
                                   'bike_id',
                                   'id',
                                   'category',
                                   'part',
                                   'description',
                                   'inspection_period_km',
                                   'inspection_period_date',
                                   'inspected_km',
                                   'inspected_date',
                                   'replaced_km',
                                   'replaced_date',
                                   'withdrawn_km',
                                   'withdrawn_date'))
                     ->where('userid  = ?', $userid)
                     ->where('bike_id = ?', $bike_id)
                     ->order('category')
                     ->order('part')
                     ->order('id');

        $stmt  = $db->query($select);
        $parts = $stmt->fetchAll();

        return $parts;
    }
}
