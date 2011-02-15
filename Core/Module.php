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

class Core_Module {
    var $api;

    var $module_description = array();

    function __construct() {
        $parts = preg_split('/Module/', get_class($this));
        $module_name = $parts[1];

        $api_file  = 'modules/'.$module_name.'/API.php';

        /* API */
        if (file_exists($api_file)) {
            require_once($api_file);
            $api_class  = 'Module' . $module_name . 'API';

            if (class_exists($api_class)) {
                $this->api  = new $api_class;
            } else {
                echo "Warning, ".$api_file." exists, but no class is avaliable.";
            }
        }
    }

    static function _hook() {
        $hooks = array();
        return $hooks;
    }

    function getDefaultAction() {
        return 'index';
    }

    function __destruct() {
        $this->api  = null;
    }
}

?>
