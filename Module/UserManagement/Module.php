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

class Module_UserManagement_Module extends Core_Module
{
    /**
     * Get the Modules details.
     *
     * @return Array of the description fields
     */
    public function getInformation()
    {
        return array(
                'name'        => 'Session',
                'description' => 'View, create and edit exercise sessions',
                'version'     => '0.1',
                'author'      => 'Paul Archer',
                );
    }

    /**
     * Get the callback hooks.
     *
     * @return Array of callbacks
     */
    function getListHooksRegistered()
    {
        $hooks = array(
                'Menu.add' => 'addMenu',
        );
        return $hooks;
    }

    /**
     * Call back hook to add menu entries.
     *
     * @return null
     */
    function addMenu()
    {
        Core_Menu_AddMenu('User', 'Bikes', 
                array('module' => 'UserManagement', 
                      'action' => 'bikes'));

        Core_Menu_AddMenu('User', 'Settings', 
                array('module' => 'UserManagement', 
                      'action' => 'settings'));

        if (isset($_SESSION['superuser']) && $_SESSION['superuser']) {
            Core_Menu_AddMenu('UserManagement', 'View Users', 
                    array('module' => 'UserManagement', 
                          'action' => 'view'));

            Core_Menu_AddMenu('UserManagement', 'New User', 
                    array('module' => 'UserManagement', 
                          'action' => 'create'));
        }
    }
}

?>
