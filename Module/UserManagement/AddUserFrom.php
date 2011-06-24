<?php
/**
 * Form for adding new users.
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
 * Form for adding new users.
 *
 * @category Bike
 * @package  UserManagement
 * @author   Paul Archer <ptarcher@gmail.com>
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt AGPL-3 .0
 * @link     http://paul.archer.tw
 */
class AddUserForm extends Core_Form
{
    /**
     * Create a form for adding new users
     *
     * @param string $id          id of the form
     * @param string $method      method of submission
     * @param array  $attributes  extra form attributes
     * @param bool   $trackSubmit trackSubmit
     *
     * @return Core_Form
     */
    function __construct($id = 'adduserform', $method = 'post', 
                         $attributes = null, $trackSubmit = false)
    {
        parent::__construct($id, $method, $attributes, $trackSubmit);
    }

    /**
     * Initialise the form fields
     *
     * @return null
     */
    function init()
    {
        $login = $this->addElement('text', 'login');
        $login->addRule('required', 'Login Required');

        $pw = $this->addElement('password', 'password');
        $pw->addRule('required', 'Password Required');

        $coach = $this->addElement('radio', 'coach');
        $coach->addRule('required', 'Select a coach setting');

        $athlete = $this->addElement('radio', 'athlete');
        $athlete->addRule('required', 'Select an athlete setting');

        $this->addElement('submit', 'submit');
    }
}

