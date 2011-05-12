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
	function __construct( $id = 'signupform', $method = 'post', $attributes = null, $trackSubmit = false)
	{
		parent::__construct($id, $method, $attributes = array('autocomplete' => 'off'), $trackSubmit);
	}

	function init()
	{
        $login = $this->addElement('text',     'form_login', array('required' => 'required'));
        $login->setLabel('Login');
        $login->addRule('required',     'The Login is requried');
        //$login->addRule('alphanumeric', 'The username must be alpha numeric');

        $email = $this->addElement('email',      'form_email', array('required' => 'required'));
        $email->setLabel('Email');
        $email->addRule('required', 'The Email is requried');
        //$email->addRule('email',  'Must use a valid email address');

        $pw = $this->addElement('password', 'form_password', array('required' => 'required'));
        $pw->setLabel('Password');
        $pw->addRule('required', 'The Password is requried');

        $pwconf = $this->addElement('password', 'form_passwordconfirm', array('required' => 'required'));
        $pwconf->setLabel('Confirm Password');
        $pwconf->addRule('required', 'The Password confirmation is requried');

        $dob = $this->addElement('text', 'form_dob', array('required' => 'required'));
        $dob->setLabel('Date of Birth');
        $dob->addRule('required', 'The Date of Birth is required');

        $sex = $this->addElement('select', 'form_sex', array('required' => 'required'));
        $sex->setLabel('Sex');
        $sex->loadOptions(array('Male','Female'));

		$this->addElement('submit', 'submit', 
                array('value' => 'Submit',
                      'class' => 'submit'));
	}
}

