<?php
/*
 *  Description: 
 *  Date:        18/02/2011
 *  
 *  Author:      Paul Archer <ptarcher@gmail.com>
 *
 * Copyright (C) 2011  Paul Archer
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

class Core_User {
	static private $instance = null;
    var $user;

	/**
     */
    function __construct() {
        $this->user = new Zend_Session_Namespace('user');
    }

	/**
     */
	static public function getInstance()
    {
		if (self::$instance == null)
        {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
    }

	/**
     * Get the current users username
     */
	static public function getUserId()
    {
        $me = self::getInstance();
        return $me->user->userid;
    }

    /**
     * Checks if the current user is a super user
     */
    static public function isSuperUser()
    {
        $me = self::getInstance();
        return $me->user->superuser;
    }

    static public function getUserToken()
    {
        $me = self::getInstance();
        return $me->user->token;
    }
}

?>
