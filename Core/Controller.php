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

class Core_Controller {
    function getDefaultAction() 
    {
        return 'index';
    }

    /**
     * Checks that the specified token matches the current logged in user token
     * Protection against CSRF
     * 
     * @return throws exception if token doesn't match
     */
    protected function checkTokenInUrl()
    {
        if(Core_Common::getRequestVar('token_auth', false) != Core_Common::getCurrentUserAuth()) {
            throw new Core_Access_NoAccessException('Invalid Auth Token');
        }
    }

    /**
     *
     */
    function preDispatch()
    {
        $currentLogin  = Core_Common::getCurrentUserLogin();
        $currentModule = Core_Helper::getModule();
        $loginModule   = Core_Helper::getLoginModuleName();


        if($currentModule !== $loginModule && (empty($currentLogin) || $currentLogin === 'anonymous'))
        {
            Core_Helper::redirectToModule($loginModule);
        }
    }

    /*
    function redirectToIndex($moduleToRedirect, $actionToRedirect)
    {
        $currentLogin = Core_Common::getCurrentUserLogin();

        if(!empty($currentLogin)
                && $currentLogin != 'anonymous')
        {
            $errorMessage = sprintf('CoreHome_NoPrivileges %s',   $currentLogin);
            $errorMessage .= "<br /><br />&nbsp;&nbsp;&nbsp;<b><a href='index.  php?module=". Zend_Registry::get('auth')->getName() ."&amp;                     action=logout'>&rsaquo; ". 'General_Logout'. "</a></b><br />";
            //Piwik_ExitWithMessage($errorMessage, false, true);
        }

        Core_FrontController::getInstance()->dispatch(Core_Helper::getLoginModuleName(), false);

        exit;
    }*/
}

?>
