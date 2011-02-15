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

require_once('Core/Form.php');
require_once('Core/Translate.php');

/**
 *
 * @package Core_Login
 */
class LoginForm extends CoreForm
{
	function __construct()
	{
		parent::__construct();
		// reset
		$this->updateAttributes('id="loginform" name="loginform"');
	}

	function init()
	{
		$formElements = array(
			array('text',     'form_login'),
			array('password', 'form_password'),
		);
		$this->addElements( $formElements );

		$formRules = array(
			array('form_login',    sprintf('The %s is required', 'Login'), 'required'),
			array('form_password', sprintf('The %s is required', 'Password'), 'required'),
		);
		$this->addRules( $formRules );

		$this->addElement('submit', 'submit');
	}
}

