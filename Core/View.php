<?php
/**
 * Core - Open source web analytics
 * 
 * @link http://Core.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html Gpl v3 or later
 * @version $Id: View.php 1834 2010-02-10 08:32:03Z vipsoft $
 * 
 * @category Core
 * @package Core
 */

require_once('Core/iView.php');
require_once('libraries/Smarty/Smarty.class.php');

/**
 * View class to render the user interface
 *
 * @package Core
 */
class Core_View implements Core_iView
{
	// view types
	const STANDARD = 0; // REGULAR, FULL, CLASSIC
	const MOBILE = 1;
	const CLI = 2;

	private $template = '';
	private $smarty = false;
	private $variables = array();
	
	public function __construct( $templateFile, $smConf = array(), $filter = true )
	{
		$this->template = $templateFile;
		$this->smarty = new Smarty();

        $template_dir = array('Module', 'themes/default', 'themes');
		$this->smarty->template_dir = $template_dir;
		array_walk($this->smarty->template_dir, array("Core_View","addPath"), INCLUDE_PATH);

		$this->smarty->plugins_dir = array("Core/SmartyPlugins", "libraries/Smarty/plugins/");
		array_walk($this->smarty->plugins_dir, array("Core_View","addPath"), INCLUDE_PATH);

		$this->smarty->compile_dir = "tmp/templates_c";
		Core_View::addPath($this->smarty->compile_dir, null, USER_PATH);

		$this->smarty->cache_dir = "tmp/cache";
		Core_View::addPath($this->smarty->cache_dir, null, USER_PATH);

/*
		$error_reporting = $smConf->error_reporting;
		if($error_reporting != (string)(int)$error_reporting)
		{
			$error_reporting = self::bitwise_eval($error_reporting);
		}
		$this->smarty->error_reporting = $error_reporting;

		$this->smarty->assign('tag', 'Core=' . Version::VERSION);
		if($filter)
		{
			$this->smarty->load_filter('output', 'cachebuster');
			$this->smarty->load_filter('output', 'ajaxcdn');
			$this->smarty->load_filter('output', 'trimwhitespace');
		}
        */
	}
	
	/**
	 * Directly assigns a variable to the view script.
	 * VAR names may not be prefixed with '_'.
	 *
	 *	@param string $key The variable name.
	 *	@param mixed $val The variable value.
	 */
	public function __set($key, $val)
	{
		$this->smarty->assign($key, $val);
	}

	/**
	 * Retrieves an assigned variable.
	 * VAR names may not be prefixed with '_'.
	 *
	 *	@param string $key The variable name.
	 *	@return mixed The variable value.
	 */
	public function __get($key)
	{
		return $this->smarty->get_template_vars($key);
	}

	public function render()
	{
		try {
			$this->currentModule = Core_Helper::getModule();
			//$this->userLogin = Core_Helper::getCurrentUserLogin();
			
			$this->url = Core_Url::getCurrentUrl();
			//$this->token_auth = Core_Core::getCurrentUserTokenAuth();
			//$this->userHasSomeAdminAccess = Core_Core::isUserHasSomeAdminAccess();
			//$this->userIsSuperUser = Core_Core::isUserIsSuperUser();
			//$this->Core_version = Core_Version::VERSION;
			//$this->latest_version_available = UpdateCheck::isNewestVersionAvailable();

			//$this->loginModule = Zend_Registry::get('auth')->getName();


            // global value accessible to all templates: the 
            // Core base URL for the current request
            $this->Core_Url = Core_Url::getCurrentUrlWithoutFileName();

            /* The navigation menu */
            $this->Core_NavigationMenu = Core_Navigator::getInstance()->getMenu();

		} catch(Exception $e) {
			// can fail, for example at installation (no plugin loaded yet)		
		}
		
		//$this->totalTimeGeneration = Zend_Registry::get('timer')->getTime();
        /*
		try {
			$this->totalNumberOfQueries = Core_Common::getQueryCount();
		}
		catch(Exception $e){
			$this->totalNumberOfQueries = 0;
		}*/
 
		@header('Content-Type: text/html; charset=utf-8');
		@header("Pragma: ");
		@header("Cache-Control: no-store, must-revalidate");
		
		return $this->smarty->fetch($this->template);
	}
	
	public function addForm( $form )
	{
        if ($form instanceof Core_Form) 
        {
            static $registered = false;
            if(!$registered)
            {
                HTML_QuickForm2_Renderer::register('smarty',                    'HTML_QuickForm2_Renderer_Smarty');
                $registered = true;
            }

            // Create the renderer object   
            $renderer = HTML_QuickForm2_Renderer::factory('smarty');
            $renderer->setOption('group_errors', true);

            // build the HTML for the form
            $form->render($renderer);

            // assign array with form data
            $this->smarty->assign('form_data', $renderer->toArray());
            $this->smarty->assign('element_list', $form->getElementList());
        }
	}
	
	public function assign($var, $value=null)
	{
		if (is_string($var))
		{
			$this->smarty->assign($var, $value);
		}
		elseif (is_array($var))
		{
			foreach ($var as $key => $value)
			{
				$this->smarty->assign($key, $value);
			}
		}
	}

	public function clearCompiledTemplates()
	{
		$this->smarty->clear_compiled_tpl();
	}

/*
	public function isCached($template)
	{
		if ($this->smarty->is_cached($template))
		{
			return true;
		}
		return false;
	}


	public function setCaching($caching)
	{
		$this->smarty->caching = $caching;
	}
*/

	static public function addPath(&$value, $key, $path)
	{
		if($value[0] != '/' && $value[0] != DIRECTORY_SEPARATOR)
		{
			$value = $path ."/$value";
		}
	}

	/**
	 * Evaluate expression containing only bitwise operators.
	 * Replaces defined constants with corresponding values.
	 * Does not use eval() or create_function().
	 *
	 * @param string $expression Expression.
	 * @return string
	 */
	static public function bitwise_eval($expression)
	{
		// replace defined constants
		$buf = get_defined_constants(true);

		// use only the 'Core' PHP constants, e.g., E_ALL, E_STRICT, ...
		$consts = isset($buf['Core']) ? $buf['Core'] : (isset($buf['mhash']) ? $buf['mhash'] : $buf['internal']);
		$expression = str_replace(' ', '', strtr($expression, $consts));

		// bitwise operators in order of precedence (highest to lowest)
		// @todo: boolean ! (NOT) and parentheses aren't handled
		$expression = preg_replace_callback('/~(-?[0-9]+)/', create_function('$matches', 'return (string)((~(int)$matches[1]));'), $expression);
		$expression = preg_replace_callback('/(-?[0-9]+)&(-?[0-9]+)/', create_function('$matches', 'return (string)((int)$matches[1]&(int)$matches[2]);'), $expression);
		$expression = preg_replace_callback('/(-?[0-9]+)\^(-?[0-9]+)/', create_function('$matches', 'return (string)((int)$matches[1]^(int)$matches[2]);'), $expression);
		$expression = preg_replace_callback('/(-?[0-9]+)\|(-?[0-9]+)/', create_function('$matches', 'return (string)((int)$matches[1]|(int)$matches[2]);'), $expression);

		return (string)((int)$expression & PHP_INT_MAX);
	}

	/**
	 * View factory method
	 *
	 * @param $templateName Template name (e.g., 'index')
	 * @param $viewType     View type (e.g., View::CLI)
	 */
	static public function factory( $templateName, $viewType = null, $path = null)
	{
		//PostEvent('View.getViewType', $viewType);

		// get caller
        if ($path === null) {
            $bt = @debug_backtrace();
            if($bt === null || !isset($bt[0]))
            {
                throw new Exception("View factory cannot be invoked");
            }
            $path = dirname($bt[0]['file']);
        } else {
            $path = USER_PATH. DIRECTORY_SEPARATOR . $path;
        }

		// determine best view type
		if($viewType === null)
		{
			if(Core_Common::isPhpCliMode())
			{
				$viewType = self::CLI;
			}
			else
			{
				$viewType = self::STANDARD;
			}
		}

		// get template filename
		if($viewType == self::CLI)
		{
			$templateFile = $path.'/templates/cli_'.$templateName.'.tpl';
			if(file_exists($templateFile))
			{
				return new View($templateFile, array(), false);
			}

			$viewType = self::STANDARD;
		}

		if($viewType == self::MOBILE)
		{
			$templateFile = $path.'/templates/mobile_'.$templateName.'.tpl';
			if(!file_exists($templateFile))
			{
				$viewType = self::STANDARD;
			}
		}

		if($viewType != self::MOBILE)
		{
			$templateFile = $path.'/templates/'.$templateName.'.tpl';
			if(!file_exists($templateFile))
			{
				throw new Exception('Template not found: '.$templateFile);
			}
		}

		return new Core_View($templateFile);
	}
}
