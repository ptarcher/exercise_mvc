<?php
/**
 * Piwik - Open source web analytics
 * 
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html Gpl v3 or later
 * @version $Id: Controller.php 1420 2009-08-22 13:23:16Z vipsoft $
 * 
 * @category Piwik_Plugins
 * @package Piwik_API
 */

require_once('core/Module.php');
require_once('core/API/Request.php');

/**
 * 
 * @package Piwik_API
 */
class ModuleAPIAccess extends CoreModule
{
	function index()
	{
		$request = new API_Request();
		echo $request->process();
	}

    /*
	public function listAllMethods()
	{
		$ApiDocumentation = new Piwik_API_DocumentationGenerator();
		echo $ApiDocumentation->getAllInterfaceString( $outputExampleUrls = true, $prefixUrls = Piwik_Common::getRequestVar('prefixUrl', '') );
	}
	
	public function listAllAPI()
	{
		$view = Piwik_View::factory("listAllAPI");
		$this->setGeneralVariablesView($view);
		
		$ApiDocumentation = new Piwik_API_DocumentationGenerator();
		$view->countLoadedAPI = Piwik_API_Proxy::getInstance()->getCountRegisteredClasses();
		$view->list_api_methods_with_links = $ApiDocumentation->getAllInterfaceString();
		echo $view->render();
	}*/
}
