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

include('include/session.php');

echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
echo '<html>';
echo '<head>';
echo '<title>AJAX Graphs</title>';
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
echo '<link href="include/layout.css" rel="stylesheet" type="text/css"></link>';
echo '<script language="javascript" type="text/javascript" src="include/flot/jquery.js"></script>';
echo '<script language="javascript" type="text/javascript" src="include/flot/jquery.flot.js"></script>';
echo '<script language="javascript" type="text/javascript" src="include/jquery.flot.ajax.js"></script>';
echo '</head>';
echo '<body>';

echo '<div id="placeholder" style="width:600px;height:300px;"></div>';
?>

    <p>
      <input class="fetchSeries" type="button" value="Distance">
        <input type="hidden" value="getdata.php?source=distance&axis=1"></input>
      </input>
    </p>

    <p>
      <input class="fetchSeries" type="button" value="Heart Rate">
        <input type="hidden" value="getdata.php?source=heartrate&axis=2"></input>
      </input>
    </p>

    <p>
      <input class="fetchSeries" type="button" value="Speed">
        <input type="hidden" value="getdata.php?source=speed&axis=3"></input>
      </input>
    </p>

<?php

echo '</body>';
echo '</html>';

?>
