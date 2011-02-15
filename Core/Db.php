<?php
/*
 *  Description: 
 *  Date:        
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

require_once('config.php');

class Core_Db {
	static private $instance = null;

	/**
     */
	static public function getInstance()
    {
		if (self::$instance == null)
        {
			$c = __CLASS__;
			self::$instance = $c::factory();
		}
		return self::$instance;
    }

    /**
     */
    public static function factory()
    {
        $adapter = Zend_Db::factory('Pdo_'.DB_TYPE,
                array('host'     => DB_HOST,
                      'port'     => DB_PORT,
                      'username' => DB_USER,
                      'password' => DB_PASSWORD,
                      'dbname'   => DB_NAME));

        return $adapter;
    }
}

?>
