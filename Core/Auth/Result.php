<?php
/**
 * Authentication result
 *
 * @package Core
 * @subpackage Core_Auth
 * @see Zend_Auth_Result, libs/Zend/Auth/Result.php
 * @link http://framework.zend.com/manual/en/zend.auth.html
 */
class Core_Auth_Result extends Zend_Auth_Result
{
	/**
	 * token_auth parameter used to authenticate in the API
	 *
	 * @var string
	 */
	protected $_token_auth = null;
	
	const SUCCESS_SUPERUSER_AUTH_CODE = 42;

	/**
	 * Constructor for Core_Auth_Result
	 *
	 * @param int $code
	 * @param string $login identity
	 * @param string $token_auth
	 * @param array $messages
	 */
	public function __construct($code, $login, $token_auth, array $messages = array())
	{
		// Core_Auth_Result::SUCCESS_SUPERUSER_AUTH_CODE, Core_Auth_Result::SUCCESS, Core_Auth_Result::FAILURE  
		$this->_code		= (int)$code;
		$this->_identity	= $login;
		$this->_messages	= $messages;
		$this->_token_auth	= $token_auth;
	}
	
    /**
     * Returns the token_auth to authenticate the current user in the API
     *
     * @return string
     */
    public function getTokenAuth()
    {
    	return $this->_token_auth;
    }
}

?>
