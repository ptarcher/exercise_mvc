<?php
/**
 * Core - Open source web analytics
 * 
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html Gpl v3 or later
 * @version $Id: Controller.php 1420 2009-08-22 13:23:16Z vipsoft $
 * 
 * @category Core_Plugins
 * @package Core_API
 */

require_once('Core/Module.php');
require_once('Core/API/Request.php');

/**
 * 
 * @package Core_API
 */
class ModuleAPIAccess extends Core_Module
{
	function index()
	{
		$request = new API_Request();
		echo $request->process();
	}

    /*
	public function listAllMethods()
	{
		$ApiDocumentation = new Core_API_DocumentationGenerator();
		echo $ApiDocumentation->getAllInterfaceString( $outputExampleUrls = true, $prefixUrls = Core_Common::getRequestVar('prefixUrl', '') );
	}
	
	public function listAllAPI()
	{
		$view = Core_View::factory("listAllAPI");
		$this->setGeneralVariablesView($view);
		
		$ApiDocumentation = new Core_API_DocumentationGenerator();
		$view->countLoadedAPI = Core_API_Proxy::getInstance()->getCountRegisteredClasses();
		$view->list_api_methods_with_links = $ApiDocumentation->getAllInterfaceString();
		echo $view->render();
	}*/
}
