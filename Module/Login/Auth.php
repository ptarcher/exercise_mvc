<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @version $Id: Auth.php 3565 2011-01-03 05:49:45Z matt $
 *
 * @category Core_Plugins
 * @package Core_Login
 */

/**
 *
 * @package Core_Login
 */
class Module_Login_Auth implements Core_Auth
{
	protected $login = null;
	protected $token_auth = null;

	/**
	 * Authentication module's name, e.g., "Login"
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'Login';
	}

	/**
	 * Authenticates user
	 *
	 * @return Core_Auth_Result
	 */
	public function authenticate()
	{
		$rootLogin    = Zend_Registry::get('config')->superuser->login;
		$rootPassword = Zend_Registry::get('config')->superuser->password;
		$rootToken    = Module_UserManagement_API::getInstance()->getTokenAuth($rootLogin, $rootPassword);

		if(is_null($this->login))
		{
            $db = Zend_Registry::get('db');

			if($this->token_auth === $rootToken)
			{
				return new Core_Auth_Result(Core_Auth_Result::SUCCESS_SUPERUSER_AUTH_CODE, $rootLogin, $this->token_auth );
			}

            $select = $db->select()
                         ->from('t_users',
                                 array('userid'))
                         ->where('token = ?', $token_auth);
            $stmt = $db->query($select);
            $login_details = $stmt->fetch();
            $login - $login_details['userid'];

			if(!empty($login))
			{
				return new Core_Auth_Result(Core_Auth_Result::SUCCESS, $login, $this->token_auth );
			}
		}
		else if(!empty($this->login))
		{
            $db = Zend_Registry::get('db');

			if($this->login === $rootLogin
				&& ($this->getHashTokenAuth($rootLogin, $rootToken) === $this->token_auth)
				|| $rootToken === $this->token_auth)
			{
				$this->setTokenAuth($rootToken);
				return new Core_Auth_Result(Core_Auth_Result::SUCCESS_SUPERUSER_AUTH_CODE, $rootLogin, $this->token_auth );
			}

			$login = $this->login;
            $select = $db->select()
                         ->from('t_users',
                                 array('token'))
                         ->where('userid = ?', $login);
            $stmt = $db->query($select);
            $user_details = $stmt->fetch();
            $userToken = $user_details['token'];

			if(!empty($userToken)
				&& (($this->getHashTokenAuth($login, $userToken) === $this->token_auth)
				|| $userToken === $this->token_auth))
			{
				$this->setTokenAuth($userToken);
				return new Core_Auth_Result(Core_Auth_Result::SUCCESS, $login, $userToken );
			}
		}

		return new Core_Auth_Result( Core_Auth_Result::FAILURE, $this->login, $this->token_auth );
	}

	/**
	 * Accessor to set login name
	 *
	 * @param string $login user login
	 */
	public function setLogin($login)
	{
		$this->login = $login;
	}

	/**
	 * Accessor to set authentication token
	 *
	 * @param string $token_auth authentication token
	 */
	public function setTokenAuth($token_auth)
	{
		$this->token_auth = $token_auth;
	}

	/**
	 * Accessor to compute the hashed authentication token
	 *
	 * @param string $login user login
	 * @param string $token_auth authentication token
	 * @return string hashed authentication token
	 */
	public function getHashTokenAuth($login, $token_auth)
	{
		return md5($login . $token_auth);
	}
}

?>
