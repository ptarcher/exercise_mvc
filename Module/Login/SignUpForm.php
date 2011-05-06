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
class Module_Login_SignUpForm extends Core_Form
{
	function __construct()
	{
		parent::__construct();
		// reset
		$this->updateAttributes('id="signupform" name="signupform"');
	}

	function init()
	{
		$formElements = array(
			array('text',     'form_login'),
			array('email',    'form_email'),
			array('password', 'form_password'),
			array('password', 'form_passwordconfirm'),
		);
		$this->addElements( $formElements );

		$formRules = array(
			array('form_login',    sprintf('The %s is required', 'Login'), 'required'),
			array('form_login',    sprintf('The username must be alpha numeric'), 'alphanumeric'),
			array('form_email',    sprintf('The %s is required', 'Email'), 'required'),
			array('form_email',    sprintf('Must be a valid email address', ''), 'email'),
			array('form_password', sprintf('The %s is required', 'Password'), 'required'),
			array('form_passwordconfirm', sprintf('The %s is required', 'Confirmation Password'), 'required'),
		);
		$this->addRules( $formRules );

		$this->addElement('submit', 'submit');
	}
}

