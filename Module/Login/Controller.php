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

require_once('Module/Login/LoginForm.php');

class Module_Login_Controller extends Core_Controller 
{
    var $module_description = array(
        'name'        => 'login',
        'description' => 'Performs login and logout operations',
        'version'     => '0.1',
        'author'      => 'Paul Archer',
    );

    static function _getHooks() {
        $hooks = array(
            array("hook"     => "navigator",
                  "category" => "User", 
                  "name"     => "Logout", 
                  "module"   => "Login", 
                  "action"   => "logout"),
        );

        return $hooks;
    }

    function index() {
        $this->doLogin();
    }
    
    function doLogin() {
        global $allocator;

        /*
        $currentUrl = Helper::getModule() == 'Login' ? 
                              Core_Url::getReferer() : 
                              'index.php' . Core_Url::getCurrentQueryString();
        */
        $error_string = '';

        self::checkForceSslLogin();

        /* Keep reference to the url, so we can redirect there later */
        $currentUrl = 'index.php' . Core_Url::getCurrentQueryString();
        $urlToRedirect = Core_Common::getRequestVar('form_url', $currentUrl,   'string');
        $urlToRedirect = htmlspecialchars_decode($urlToRedirect);

        $form = new LoginForm();
        if ($form->validate()) {
            $userid   = $form->getSubmitValue('form_login');
            $password = $form->getSubmitValue('form_password');

            $success = $this->api->checkLogin($userid, $password);
            if ($success) {
                $user_credentials = $this->api->getUser($userid);

                $user = new Zend_Session_Namespace('user');
                $user->userid    = $user_credentials['userid'];
                $user->coach     = $user_credentials['coach']     == 't';
                $user->athlete   = $user_credentials['athlete']   == 't';
                $user->superuser = $user_credentials['superuser'] == 't';

                $_SESSION['userid']    = $user_credentials['userid'];
                $_SESSION['coach']     = $user_credentials['coach']     == 't';
                $_SESSION['athlete']   = $user_credentials['athlete']   == 't';
                $_SESSION['superuser'] = $user_credentials['superuser'] == 't';

                /* We have sucessfully logged in, now lets 
                 * display the next page */
                if (!isset($redirect_module) || !isset($redirect_action)) {
                    $redirect_module = 'Sessions';
                }

                Core_Url::redirectToUrl($urlToRedirect);
                return;
            } else {
                $error_string = 'Incorrect Login Details';
            }
        }

        $view = Core_View::factory('login');
        $view->urlToRedirect = $urlToRedirect;
        $view->addForm($form);
        $view->subTemplate = 'genericForm.tpl';
        $view->AccessErrorString = $error_string;
        echo $view->render();
    }

    function logout() {
        session_unset();
        session_destroy();

        Core_Helper::redirectToModule('Sessions');
    }

    /**
     * Check force_ssl_login and redirect if connection isn't secure and not    using a reverse proxy
     *
     * @param none
     * @return void
     */
    protected function checkForceSslLogin()
    {
        $forceSslLogin = Zend_Registry::get('config')->General->force_ssl_login;
        if($forceSslLogin)
        {
            $reverseProxy = Zend_Registry::get('config')->General->reverse_proxy;
            if(!(Core_Url::getCurrentScheme() == 'https' || $reverseProxy))
            {
                $url = 'https://'
                    . Core_Url::getCurrentHost()
                    . Core_Url::getCurrentScriptName()
                    . Core_Url::getCurrentQueryString();
                Core_Url::redirectToUrl($url);
            }
        }
    }

}

?>
