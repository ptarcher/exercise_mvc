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

class Module_Login_Controller extends Core_Controller 
{
    function index() 
    {
        $this->doLogin();
    }
    
    function doLogin($error_string = null) 
    {
        /*
        $currentUrl = Core_Helper::getModule() == 'Login' ? 
                              Core_Url::getReferer() : 
                              'index.php' . Core_Url::getCurrentQueryString();
        */

        //self::checkForceSslLogin();

        /* Keep reference to the url, so we can redirect there later */
        $currentUrl = 'index.php' . Core_Url::getCurrentQueryString();
        $urlToRedirect = Core_Common::getRequestVar('form_url', $currentUrl,   'string');
        $urlToRedirect = htmlspecialchars_decode($urlToRedirect);

        $form = new Module_Login_LoginForm();
        if ($form->validate()) {
            $login      = $form->getSubmitValue('form_login');
            $password   = $form->getSubmitValue('form_password');
            $rememberme = $form->getSubmitValue('form_rememberme');

            try {
                $this->authenticateAndRedirect($login, $password, $rememberme);
            } catch (Exception $e) {
                $error_string = $e->getMessage();
            }
        }

        $view = Core_View::factory('login');
        $view->urlToRedirect = $urlToRedirect;
        $view->addForm($form);
        $view->subTemplate = 'genericForm.tpl';
        $view->AccessErrorString = $error_string;
        echo $view->render();
    }

	/**
	 * Authenticate user and password.  Redirect if successful.
	 *
	 * @param string $login user name
	 * @param string $md5Password md5 hash of password
	 * @param bool $rememberMe Remember me?
	 * @param string $urlToRedirect URL to redirect to, if successfully authenticated
	 * @return string failure message if unable to authenticate
	 */
	protected function authenticateAndRedirect($login, $password, $rememberMe, $urlToRedirect = 'index.php')
	{
		$info = array(	'login'       => $login, 
						'password'    => $password,
						'rememberMe'  => $rememberMe,
		);
		//Piwik_Nonce::discardNonce('Piwik_Login.login');
		Core_PostEvent('Login.initSession', $info);

		Core_Url::redirectToUrl($urlToRedirect);
	}

    /**
     * Clear the session information
     */
    static public function clearSession()
    {
        $authCookieName = Zend_Registry::get('config')->General->login_cookie_name;
        $cookie = new Core_Cookie($authCookieName);
        $cookie->delete();

        Zend_Session::expireSessionCookie();
        Zend_Session::regenerateId();
    }

    /**
     * Create a new user
     */
    function signup() 
    {
        $form = new Module_Login_SignUpForm();
        $view = Core_View::factory('signup');
        $view->errorMessage = "";

        if ($form->validate()) {
            $api      = new Module_Login_API();
            $user_api = new Module_UserManagement_API();

            $login    = $form->getSubmitValue('form_login');
            $password  = $form->getSubmitValue('form_password');
            $password2 = $form->getSubmitValue('form_passwordconfirm');
            $email     = $form->getSubmitValue('form_email');

            /* Check the passwords match */
            try {
                /* Check if the username exists */
                if ($api->getUser($login)) {
                    throw new Exception('The username is already taken');
                }

                /* Check the passwords */
                if ($password !== $password2) {
                    throw new Exception('The passwords do not match');
                }

                $user_api->createUser($login, $password, $email);

                Core_Url::redirectToUrl('index.php');

            } catch (Exception $e) {
                $view->errorMessage = $e->getMessage();
            }
        }

        $view->addForm($form);
        $view->subTemplate = 'genericForm.tpl';
        echo $view->render();
    }

    /**
     * Lost Password
     */
    function lostPassword() 
    {
    }


    /**
     * Logout the current user
     */
    function logout() 
    {
        self::clearSession();
        Core_Helper::redirectToModule(Core_Helper::getDefaultModuleName());
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
