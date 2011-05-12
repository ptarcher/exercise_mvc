<?php
/**
 * Core - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html Gpl v3 or later
 * @version $Id: Form.php 1736 2009-12-26 21:48:30Z vipsoft $
 *
 * @category Core_Plugins
 * @package Core_Login
 */

/**
 *
 * @package Core_Login
 */
class Module_Login_LoginForm extends Core_Form
{
	function __construct( $id = 'loginform', $method = 'post', $attributes = null, $trackSubmit = false)
	{
		parent::__construct($id, $method, $attributes, $trackSubmit);
	}

	function init()
	{
        $login = $this->addElement('text',     'form_login');
        $login->addRule('required', 'The login is required');

        $pw    = $this->addElement('password', 'form_password');
        $pw->addRule('required', 'The password is required');

		$this->addElement('submit', 'submit');
	}
}

