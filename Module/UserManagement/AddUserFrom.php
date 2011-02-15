<?php
/**
 * Core - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html Gpl v3 or later
 * @version $Id: Form.php 1736 2009-12-26 21:48:30Z vipsoft $
 *
 * @category Core_Plugins
 * @package Core_UserManagement
 */

/**
 *
 * @package Core_Login
 */
class AddUserForm extends Core_Form
{
	function __construct()
	{
		parent::__construct();
		// reset
		$this->updateAttributes('id="adduserform" name="adduserform"');
	}

	function init()
	{
		$formElements = array(
			array('text',     'adduserform_login'),
			array('password', 'adduserform_password'),
			array('radio',    'adduserform_coach'),
			array('radio',    'adduserform_athlete'),
		);
		$this->addElements( $formElements );

		$formRules = array(
			array('adduserform_login',    'Login Required',           'required'),
			array('adduserform_password', 'Password Required',        'required'),
			array('adduserform_coach',    'Select a coach setting',   'required'),
			array('adduserform_athlete',  'Select a athlete setting', 'required'),
		);
		$this->addRules( $formRules );

		$this->addElement('submit', 'submit');
	}
}

