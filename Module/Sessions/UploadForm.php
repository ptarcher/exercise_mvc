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

/**
 *
 * @package Core_Login
 */
class SessionUploadForm extends Core_Form
{
	function __construct( $id = 'uploadform', $method = 'post', $attributes = null, $trackSubmit = false)
	{
		parent::__construct($id, $method, $attributes = array('autocomplete' => 'off', 'enctype' => 'multipart/form-data'), $trackSubmit);
	}

	function init()
	{
        $file = $this->addElement('file',     'form_upload', array('required' => 'required'));
        //$file->addLabel('Session File');
        $file->addRule('required', 'The upload file is required');

		$this->addElement('submit', 'submit');
	}
}

