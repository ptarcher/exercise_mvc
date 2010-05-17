<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html Gpl v3 or later
 * @version $Id: Form.php 1736 2009-12-26 21:48:30Z vipsoft $
 *
 * @category Piwik_Plugins
 * @package Piwik_Login
 */

require_once('core/Form.php');
require_once('core/Translate.php');

/**
 *
 * @package Piwik_Login
 */
class SessionUploadForm extends CoreForm
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

