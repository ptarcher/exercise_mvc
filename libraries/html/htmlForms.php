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

class HtmlForm {
    function __construct($items, $module, $action, $method = "GET") {
        global $html;

        $html->draw('<form method="'.$this->method.'" action="?'.$module.'&'.$action.'">');
        $html->draw('<table>');
        foreach ($items as $item) {
            $html->draw('<tr>');
            
            $html->draw('<th>');
            if (strlen($item['name']) != 0) {
                $html->draw($item['name'] . ':');
            }
            $html->draw('</th>');

            $html->draw('<td>');
            if ($item['type'] == 'input') {
                $input = '<input';
                foreach ($item['attributes'] as $field => $value) {
                    $input .= " $field=\"$value\"";
                }
                $input .= '>';
                $html->draw($input);
            } else if ($item['type'] == 'select') {
                $html->draw('<select name="'.$item['attributes']['name'].'">');
                foreach ($item['options'] as $key => $option) {
                    $html->draw('<option value="'.$key.'">'
                                               .$option.'</option>');
                }
                $html->draw('</select>');
            } else if ($item['type'] == 'submit') {
            }
            $html->draw('</td>');
            
            $html->draw('</tr>');
        }
        $html->draw('</table>');
    }

    function __destruct() {
    }
}

?>
