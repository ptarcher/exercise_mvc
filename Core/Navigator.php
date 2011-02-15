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

require_once('Core/Url.php');

class Core_Navigator 
{
    private $categories = array();
	static private $instance = null;

	/**
	 * Returns the singleton CoreNavigator
	 *
	 * @return Core_ModuleManager
	 */
	static public function getInstance()
	{
		if (self::$instance == null)
		{			
			$c = __CLASS__;
			self::$instance = new $c();
		}
		return self::$instance;
	}
	
    function __construct() 
    {
        $dp = opendir("modules");

        while (($ent = readdir($dp)) !== false) {
            if ($ent{0} == '.') {
                continue;
            }

            $module_dir  = "modules" . DIRECTORY_SEPARATOR . $ent;
            $module_file = $module_dir . DIRECTORY_SEPARATOR . "Controller.php";
            $class_name  = "Module_".$ent;

            // Open up the module
            if (is_dir($module_dir) && is_file($module_file)) {
                require_once($module_file);
                // Call the hook function 
                if (!method_exists($class_name, "_getHooks")) {
                    continue;
                }
                $hooks = call_user_func_array(array($class_name, "_getHooks"), array());
                if (is_array($hooks)) {
                    foreach ($hooks as $hook) {
                        if ($hook["hook"]   == 'navigator') {
                            $link["name"]   = $hook["name"];
                            $link["module"] = $hook["module"];
                            $link["action"] = $hook["action"];

                            $link["url"] = 'index.php?' . Core_Url::getQueryStringFromParameters(array('module' => $hook['module'], 
                                       'action' => $hook['action']));

                            $this->categories[$hook["category"]][] = $link;
                        }
                    }
                }
            }
        }

        uksort($this->categories, 'strcasecmp');
    }

    function getMenu() 
    {
        return $this->categories;
    }
}

?>
