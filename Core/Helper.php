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
require_once('Core/Url.php');

class Core_Helper
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

/*
 * Current module, action, plugin
 */

	/**
	 * Returns the name of the Login plugin currently being used.
	 * Must be used since it is not allowed to hardcode 'Login' in URLs
	 * in case another Login plugin is being used.
	 *
	 * @return string
	 */
	static public function getLoginModuleName()
	{
		return Zend_Registry::get('auth')->getName();
	}

	static public function getDefaultModuleName()
	{
		return 'DashBoard';
	}

	/**
	 * Returns the plugin currently being used to display the page
	 *
	 * @return Core_Module
	 */
	static public function getCurrentModule()
	{
		return Core_ModuleManager::getInstance()->getLoadedModule(Core_Helper::getModule());
	}



    /**
     * Returns the current module read from the URL (eg. 'API', 'UserSettings', etc.)
     *
     * @return string
     */
    static public function getModule()
    {
        return Core_Common::getRequestVar('module', '', 'string');
    }

    /**
     * Returns the current action read from the URL
     *
     * @return string
     */
    static public function getAction()
    {
        return Core_Common::getRequestVar('action', '', 'string');
    }
/**
     * Redirect to module (and action)
     *
     * @param string $newModule
     * @param string $newAction
     * @return bool false if the URL to redirect to is already this URL
     */
    static public function redirectToModule( $newModule, $newAction = '' )
    {
        $currentModule = self::getModule();
        $currentAction = self::getAction();

        if($currentModule != $newModule
                ||  $currentAction != $newAction )
        {

            $newUrl = 'index.php' . Core_Url::                                 getCurrentQueryStringWithParametersModified(
                    array('module' => $newModule, 'action' => $newAction)
                    );

            Core_Url::redirectToUrl($newUrl);
        }
        return false;
    }


    function __destruct() 
    {
    }
}

?>
