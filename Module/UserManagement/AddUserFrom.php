<?php
/**
 */

/**
 *
 * @package Core_Login
 */
class AddUserForm extends Core_Form
{
	function __construct( $id = 'adduserform', $method = 'post', $attributes = null, $trackSubmit = false)
	{
		parent::__construct($id, $method, $attributes, $trackSubmit);
	}

	function init()
	{
        $login   = $this->addElement('text',     'login');
        $login->addRule('required', 'Login Required');

        $pw      = $this->addElement('password', 'password');
        $pw->addRule('required', 'Password Required');

        $coach   = $this->addElement('radio',    'coach');
        $coach->addRule('required', 'Select a coach setting');

        $athlete = $this->addElement('radio',    'athlete');
        $athlete->addRule('required', 'Select an athlete setting');

		$this->addElement('submit', 'submit');
	}
}

