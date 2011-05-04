<?php
/**
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
			array('text',     'login'),
			array('password', 'password'),
			array('radio',    'coach'),
			array('radio',    'athlete'),
		);
		$this->addElements( $formElements );

		$formRules = array(
			array('login',    'Login Required',           'required'),
			array('password', 'Password Required',        'required'),
			array('coach',    'Select a coach setting',   'required'),
			array('athlete',  'Select a athlete setting', 'required'),
		);
		$this->addRules( $formRules );

		$this->addElement('submit', 'submit');
	}
}

