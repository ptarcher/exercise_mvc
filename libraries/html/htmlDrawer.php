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

class htmlDrawer {
    var $error_output;
    var $buf_output;
    var $indent;

    function __construct() {
        $indent = 0;
        $output = "";
    }

    function indent($num) {
        $this->indent += $num;
        if ($this->indent < 0) {
            $this->indent = 0;
            echo 'ERROR: Negative indent';
        }
    }

    function draw($line) {
        for ($i = 0; $i < $num; $i++) {
            $this->output .= ' ';
        }
       $this->output .= $line . "\n"; 
    }

    function drawCol($col, $colspan = 1) {
        $this->draw('<td' . ($colspan != 1 ? (' colspan="'.$colspan.'"') : "") . '>'.$col.'</td>');
    }

    function drawHeaderCol($col, $colspan = 1) {
        $this->draw('<th' . ($colspan != 1 ? (' colspan="'.$colspan.'"') : "") . '>'.$col.'</th>');
    }

    function getInput($name, $type = "text", $value = "", $extras = "") {
        return '<input name="'.$name.'" type="'.$type.'" value="'.$value.'" '.$extras.'>';
    }

    function render() {
        echo $this->output;
    }

    function __destruct() {
        $output = null;
    }
}

$GLOBALS['html'] = new htmlDrawer();

?>
