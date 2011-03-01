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

class Module_Sessions_Module extends Core_Module 
{
    public function getInformation()
    {
        return array(
                'name'        => 'Session',
                'description' => 'View, create and edit exercise sessions',
                'version'     => '0.1',
                'author'      => 'Paul Archer',
                );
    }

    function getListHooksRegistered()
    {
        $hooks = array(
                'Menu.add' => 'addMenu',
        );
        return $hooks;
    }

    function addMenu()
    {
        Core_Menu_AddMenu('Sessions', 'View Sessions', 
                array('module' => 'Sessions', 
                      'action' => 'view'));
        Core_Menu_AddMenu('Sessions', 'Climbs', 
                array('module' => 'Sessions', 
                      'action' => 'viewClimbs'));

        if (isset($_SESSION['athlete']) && $_SESSION['athlete']) {
            Core_Menu_AddMenu('Sessions', 'File Upload', 
                    array('module' => 'Sessions', 
                          'action' => 'viewUpload'));
        }
    }
}

?>
