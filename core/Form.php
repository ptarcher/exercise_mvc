<?php
/**
 * Piwik - Open source web analytics
 * 
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html Gpl v3 or later
 * @version $Id: Form.php 1434 2009-08-23 15:55:35Z vipsoft $
 * 
 * @category Piwik
 * @package Piwik
 */

require_once('libraries/HTML/QuickForm.php');

/**
 * Parent class for forms to be included in Smarty
 * 
 * For an example, @see Login_Form
 * 
 * @package Piwik
 * @see HTML_QuickForm, libs/HTML/QuickForm.php
 * @link http://pear.php.net/package/HTML_QuickForm/
 */
abstract class CoreForm extends HTML_QuickForm
{
	protected $a_formElements = array();
	
	function __construct( $action = '' )
	{
		if(empty($action))
		{
			$action = Url::getCurrentQueryString();
		}
		parent::HTML_QuickForm('form', 'POST', $action);
		
		$this->registerRule( 'checkEmail', 'function', 'CoreForm_isValidEmailString');
		$this->registerRule( 'fieldHaveSameValue', 'function', 'CoreForm_fieldHaveSameValue');
	
		$this->init();
	}
	
	abstract function init();
	
	function getElementList()
	{
		$listElements=array();
		foreach($this->a_formElements as $title =>  $a_parameters)
		{
			foreach($a_parameters as $parameters)
			{
				if($parameters[1] != 'headertext' 
					&& $parameters[1] != 'submit')
				{					
					// case radio : there are two labels but only record once, unique name
					if( !isset($listElements[$title]) 
					|| !in_array($parameters[1], $listElements[$title]))
					{
						$listElements[$title][] = $parameters[1];
					}
				}
			}
		}
		return $listElements;
	}
	
	function addElements( $a_formElements, $sectionTitle = '' )
	{
		foreach($a_formElements as $parameters)
		{
			call_user_func_array(array(&$this , "addElement"), $parameters );
		}
		
		$this->a_formElements = 
					array_merge(
							$this->a_formElements, 
							array( 
								$sectionTitle =>  $a_formElements
								)
						);
	}
	
	function addRules( $a_formRules)
	{
		foreach($a_formRules as $parameters)
		{
			call_user_func_array(array(&$this , "addRule"), $parameters );
		}
		
	}

	function setChecked( $nameElement )
	{
		foreach( $this->_elements as $key => $value)
		{
			if($value->_attributes['name'] == $nameElement)
			{
				$this->_elements[$key]->_attributes['checked'] = 'checked';
			}
		}
	}
}

function CoreForm_fieldHaveSameValue($element, $value, $arg) 
{
	$value2 = Common::getRequestVar( $arg, '', 'string');
	$value2 = Common::unsanitizeInputValue($value2);
	return $value === $value2;
}

function CoreForm_isValidEmailString( $element, $value )
{
	return Helper::isValidEmailString($value);
}
