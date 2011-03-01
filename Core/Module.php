<?php
/*
 *  Description: Simple plugin class
 *  Date:        01/03/2011
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

abstract class Core_Module 
{
    function __construct() 
    {
        return;
    }

    /**
     * Returns the plugin details
     * - 'description' => string        // 1-2 sentence description of the      plugin
     * - 'author' => string             // plugin author 
     * - 'author_homepage' => string    // author homepage URL (or email        "mailto:youremail@example.org")
     * - 'homepage' => string           // plugin homepage URL
     * - 'license' => string            // plugin license
     * - 'license_homepage' => string   // license homepage URL
     * - 'version' => string            // plugin version number; examples and  3rd party plugins must not use Core_Version::VERSION; 3rd party plugins must   increment the version number with each plugin release
     * - 'translationAvailable' => bool // is there a translation file in       plugins/your-plugin/lang/* ?
     */
    abstract function getInformation();

    /**
     * Returns the plugin version number
     *
     * @return string
     */
    public function getVersion()
    {
        $info = $this->getInformation();
        return $info['version'];
    }


    /**
     * Install the plugin
     * - create tables
     * - update existing tables
     * - etc.
     */
    public function install()
    {
        return;
    }

    /**
     * Remove the created resources during the install
     */
    public function uninstall()
    {
        return;
    }

    /**
     * Returns the list of hooks registered with the methods names
     * @var array
     */
    function getListHooksRegistered() 
    {
        return array();
    }

    /**
     * Executed after loading plugin and registering translations
     * Useful for code that uses translated strings from the plugin.
     */
    public function postLoad()
    {
        return;
    }


    /**
     * Returns the plugin's base name without the "Core_" prefix,
     * e.g., "UserCountry" when the plugin class is "Core_UserCountry"
     *
     * @return string
     */
    final public function getClassName()
    {
        return Core_Common::unprefixClass(get_class($this));
    }


    function __destruct() 
    {
        return;
    }
}

?>
