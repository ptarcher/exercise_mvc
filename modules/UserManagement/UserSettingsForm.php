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

require_once('core/Form.php');
require_once('core/Translate.php');

/**
 *
 * @package Core_Login
 */
class UserSettingsForm extends CoreForm
{
	function __construct()
	{
		parent::__construct();
		// reset
		$this->updateAttributes('id="usersettingsform" name="usersettingsform"');
	}

	function init()
	{
		$formElements = array(
			array('text',     'usersettingsform_login'),
			array('password', 'usersettingsform_password'),
			array('radio',    'usersettingsform_coach'),
			array('radio',    'usersettingsform_athlete'),
		);
		$this->addElements( $formElements );

		$formRules = array(
			array('usersettingsform_login',    'Login Required',           'required'),
		);
		$this->addRules( $formRules );

		$this->addElement('submit', 'submit');
	}
}

