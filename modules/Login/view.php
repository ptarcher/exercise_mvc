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

require_once('core/ModuleView.php');

class ModuleLoginView extends CoreModuleView {

    function renderLogin($redirect_module = "", $redirect_action = "", $failed = false, $logged_out = false) {
        global $exercisePage, $html;

        $exercisePage->drawHeader("Login");

        $html->draw('<center>');
        $html->draw('<h1>Login</h1>');
        if ($failed) {
            $html->draw('<b>Loggin Failed</b>');
        } else if ($logged_out) {
            $html->draw('<b>You are now logged out</b>');
        }
        $html->draw('<form method="post" action="index.php?module=Login&action=doLogin">');
        if (strlen($redirect_module) && strlen($redirect_action)) {
            $html->draw('<input type="hidden" name="redirect_module" value="'.$redirect_module.'">');
            $html->draw('<input type="hidden" name="redirect_action" value="'.$redirect_action.'">');
        }
        $html->draw('<table>');
        $html->draw('<tr>');
        $html->drawCol('<b>username: </b>');
        $html->drawCol($html->getInput('userid'));
        $html->draw('<tr>');
        $html->draw('</tr>');
        $html->drawCol('<b>password: </b>');
        $html->drawCol($html->getInput('password', 'password'));
        $html->draw('</tr>');
        $html->draw('<tr>');
        $html->drawCol('');
        $html->drawCol('<center><input type="submit" value="login"></center>');
        $html->draw('</tr>');
        $html->draw('</table>');
        $html->draw('</form>');
        $html->draw('</center>');

        $exercisePage->drawFooter();

        $html->render();
    }
}

?>
