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


class exercisePage {
    var $html;
    function __construct() {
        global $html;

        $this->html = $html;
    }

    function drawHeader($title, $extras = "") {
        $this->html->draw('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">');
        $this->html->draw('<html>');
        $this->html->draw('<head>');
        $this->html->draw('<title>'.$title.'</title>');
        $this->html->draw('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">');
        $this->html->draw('<link href="include/layout.css" rel="stylesheet" type="text/css"></link>');
        $this->html->draw('<script language="javascript" type="text/javascript" src="include/jquery.js"></script>');
        $this->html->draw('<script language="javascript" type="text/javascript" src="include/jquery.flot.js"></script>');
        $this->html->draw('<script language="javascript" type="text/javascript" src="include/jquery.flot.navigate.js"></script>');
        if (strlen($extras)) {
                $this->html->draw($extras);
        }
        $this->html->draw('</head>');
        $this->html->draw('<body>');
    }

    function drawFooter() {
        $this->html->draw('</body>');
        $this->html->draw('</html>');
    }

}

$exercisePage = new exercisePage();

?>
