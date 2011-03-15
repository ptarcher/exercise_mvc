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
        if(Core_Common::getRequestVar('token_auth', false) != Core_User::getCurrentUserToken()) {
            throw new Core_Access_NoAccessException('Invalid Auth Token');
        }
    }
}

?>
