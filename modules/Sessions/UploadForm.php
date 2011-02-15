<?php
/**
 * Core - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html Gpl v3 or later
 * @version $Id: Form.php 1736 2009-12-26 21:48:30Z vipsoft $
 *
 * @category Core_Plugins
 * @package Core_Sessions
 */

require_once('Core/Form.php');
require_once('Core/Translate.php');

/**
 *
 * @package Core_Login
 */
class SessionUploadForm extends Core_Form
{
	function __construct()
	{
		parent::__construct();
		// reset
		$this->updateAttributes('id="uploadform" name="uploadform"');
	}

	function init()
	{
		$formElements = array(
			array('file',     'form_upload'),
		);
		$this->addElements( $formElements );

		$formRules = array(
			array('form_upload',    sprintf(Translate('General_Required'), Translate('Sessions_Upload')), 'required'),
		);
		$this->addRules( $formRules );

		$this->addElement('submit', 'submit');
	}
}

