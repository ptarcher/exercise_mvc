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

require_once('Core/Common.php');

class Core_FrontController 
{
    static private $instance = null;

    static public function getInstance()
    {
        if (self::$instance == null) {
            $c = __CLASS__;
            self::$instance = new $c();
        }
        return self::$instance;
    }

    function __construct() 
    {
    }

	function dispatch( $module = null, $action = null, $parameters = null)
    {
		if(is_null($module)) {
			$defaultModule = 'Sessions';
			$module = Core_Common::getRequestVar('module', $defaultModule, 'string');
        }

        if(is_null($action)) {
            $action = Core_Common::getRequestVar('action', false);
        }
		
		if(is_null($parameters)) {
			$parameters = array();
		}

        $this->checkLogin($module, $action);

		if(!ctype_alnum($module))
		{
			throw new Exception("Invalid module name '$module'");
		}

        $controllerClassName = "Module_" . $module . "_Controller";

        if (!class_exists($controllerClassName)) {
            $module_file = "Module".DIRECTORY_SEPARATOR.
                $module.DIRECTORY_SEPARATOR.
                "Controller.php";

            /* Include the module */
            if (!file_exists($module_file)) {
                throw new Exception("Module controller '$controllerClassName' not found!");
                return;
            }

            require_once($module_file);
        }

        // Dynamically create the class
        $controller = new $controllerClassName;
        if ($action === false) {
            $action = $controller->getDefaultAction();
        }

        // Dynamically call the action
        if ( !is_callable(array($controller, $action))) {
			throw new Exception("Action not found in $controllerClassName::$action().");				
        }

        try {
            return call_user_func_array(array($controller, $action), $parameters);
        } catch (Exception $e) {
            echo 'Error: ' . $e;
            return null;
        }

	
    }

    private function checkLogin(&$requested_module, &$requested_action) 
    {
        if (!isset($_SESSION['userid'])) {
            $requested_module = 'Login';
            $requested_action = 'doLogin';
        }
    }

    function __destruct() 
    {
        $this->module = null;
    }
}

?>
