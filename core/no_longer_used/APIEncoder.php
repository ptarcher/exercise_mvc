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

require_once('core/DataRender.php');
require_once('core/DataRender/json.php');
require_once('core/DataRender/xml.php');
require_once('core/DataRender/gpx.php');

class CoreAPIEncoder {
    function __construct() {
    }

    function encode($requested_module, $requested_action, $requested_format) {
        // TODO
        //$this->checkLogin($requested_module, $requested_action);

        $api_file = "modules".DIRECTORY_SEPARATOR.
                    $requested_module.DIRECTORY_SEPARATOR.
                    "API.php";

        /* Include the module */
        if (!file_exists($api_file)) {
            echo "API not found " . $requested_module;
            return;
        }

        require_once($api_file);

        $api_class = "Module" . $requested_module . "API";
        if (!class_exists($api_class)) {
            // Error
            echo "Error: Unknown class " . $requested_module;
        }

        // Dynamically create the class
        $this->api = new $api_class;

        // Dynamically call the action
        if (!method_exists($this->api, $requested_action)) {
            // Error
            echo "Error: Unknown method " . $requested_action;
            return;
        }


        // TODO: Somehow pass the arguments universally to the api function
        $args = $_GET;
        // Remove the commands
        unset($args['encode']);
        unset($args['module']);
        unset($args['action']);

        $result = call_user_func_array(array($this->api,$requested_action), 
                                 array_merge(array($_SESSION['userid']),$args));
        if ($requested_format == "json") {
            $render = new CoreDataRender_Json($result);
            $render->render();
        } else if ($requested_format == "xml") {
            $render = new CoreDataRender_Xml($result);
            $render->render();
        } else if ($requested_format == "gpx") {
            $render = new CoreDataRender_Gpx($result);
            $render->render();
        } else {
            echo 'Unknown format ' . $requested_format;
        }
    }

    function __destruct() {
        $this->api = null;
    }
}

?>
