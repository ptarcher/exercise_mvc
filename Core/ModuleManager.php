<?php
/**
 * Core - Open source web analytics
 * 
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html Gpl v3 or later
 * @version $Id: ModuleManager.php 1632 2009-12-08 22:48:44Z matt $
 * 
 * @category Core
 * @package Core
 */

/**
 * @see Core/ModulesFunctions/Menu.php
 * @see Core/ModulesFunctions/AdminMenu.php
 * @see Core/ModulesFunctions/WidgetsList.php
 * @see Core/ModulesFunctions/Sql.php
 */
 /*
require_once CORE_INCLUDE_PATH . '/Core/ModulesFunctions/Menu.php';
require_once CORE_INCLUDE_PATH . '/Core/ModulesFunctions/AdminMenu.php';
require_once CORE_INCLUDE_PATH . '/Core/ModulesFunctions/WidgetsList.php';
require_once CORE_INCLUDE_PATH . '/Core/ModulesFunctions/Sql.php';
*/
require_once ('libraries/Event/Dispatcher.php');
require_once ('libraries/Event/Notification.php');

/**
 * @package Core
 * @subpackage Core_ModuleManager
 */
class Core_ModuleManager
{
	/**
	 * @var Event_Dispatcher
	 */
	public $dispatcher;
	
	protected $modulesToLoad = array();
	protected $languageToLoad = null;

	protected $doLoadModules = true;
	protected $loadedModules = array();
	
	protected $doLoadAlwaysActivatedModules = true;
	protected $moduleToAlwaysActivate = array( 'Core_Home', 'Core_Updater', 'Core_AdminHome', 'Core_ModulesAdmin' );

	static private $instance = null;
	
	/**
	 * Returns the singleton Core_ModuleManager
	 *
	 * @return Core_ModuleManager
	 */
	static public function getInstance()
	{
		if (self::$instance == null)
		{			
			$c = __CLASS__;
			self::$instance = new $c();
		}
		return self::$instance;
	}
	
	private function __construct()
	{
		$this->dispatcher = Event_Dispatcher::getInstance();
	}
	
	public function isModuleAlwaysActivated( $name )
	{
		return in_array( $name, $this->moduleToAlwaysActivate);
	}
	
	public function isModuleActivated( $name )
	{
		return in_array( $name, $this->modulesToLoad)
			|| $this->isModuleAlwaysActivated( $name );		
	}
	
	public function isModuleLoaded( $name )
	{
		return isset($this->loadedModules[$name]);
	}
	
	/**
	 * Reads the directories inside the modules/ directory and returns their names in an array
	 *
	 * @return array
	 */
	public function readModulesDirectory()
	{
		$modulesName = glob( INCLUDE_PATH . '/modules/*', GLOB_ONLYDIR);
		$modulesName = $modulesName === false ? array() : array_map('basename', $modulesName);
		return $modulesName;
	}

	public function deactivateModule($moduleName)
	{
		$modules = $this->modulesToLoad;
		$key = array_search($moduleName,$modules);
		if($key !== false)
		{
			unset($modules[$key]);
		}
	}
	
	public function setModulesToLoad( array $modulesToLoad )
	{
		// case no modules to load
		if(is_null($modulesToLoad))
		{
			$modulesToLoad = array();
		}
		$this->modulesToLoad = $modulesToLoad;
		$this->loadModules();
	}
	
	public function doNotLoadModules()
	{
		$this->doLoadModules = false;
	}

	public function doNotLoadAlwaysActivatedModules()
	{
		$this->doLoadAlwaysActivatedModules = false;
	}
	
	public function postLoadModules()
	{
		$modules = $this->getLoadedModules();
		foreach($modules as $module)
		{
			$this->loadTranslation( $module, $this->languageToLoad );
			$module->postLoad();
		}
	}
	
	/**
	 * Returns an array containing the modules class names (eg. 'Core_UserCountry' and NOT 'UserCountry')
	 *
	 * @return array
	 */
	public function getLoadedModulesName()
	{
		return array_map('get_class', $this->getLoadedModules());
	}
	
	/**
	 * Returns an array of key,value with the following format: array(
	 * 		'UserCountry' => Core_Module $moduleObject,
	 * 		'UserSettings' => Core_Module $moduleObject,
	 * 	);
	 *
	 * @return array 
	 */
	public function getLoadedModules()
	{
		return $this->loadedModules;
	}

	/**
	 * Returns the given Core_Module object 
	 *
	 * @param string $name
	 * @return Core_Core
	 */
	public function getLoadedModule($name)
	{
		if(!isset($this->loadedModules[$name]))
		{
			throw new Exception("The module '$name' has not been loaded.");
		}
		return $this->loadedModules[$name];
	}
	
	/**
	 * Load the modules classes installed.
	 * Register the observers for every module.
	 * 
	 */
	public function loadModules()
	{
		$this->modulesToLoad = array_unique($this->modulesToLoad);

		if($this->doLoadAlwaysActivatedModules)
		{
			$this->modulesToLoad = array_merge($this->modulesToLoad, $this->moduleToAlwaysActivate);
		}
		
		foreach($this->modulesToLoad as $moduleName)
		{
			if(!$this->isModuleLoaded($moduleName))
			{
				$newModule = $this->loadModule($moduleName);	
				if($this->doLoadModules
					&& $this->isModuleActivated($moduleName))
				{
					$this->addModuleObservers( $newModule );
					$this->addLoadedModule( $moduleName, $newModule);
				}
			}
		}
	}
	
	/**
	 * Loads the module filename and instanciates the module with the given name, eg. UserCountry
	 * Do NOT give the class name ie. Core_UserCountry, but give the module name ie. UserCountry 
	 *
	 * @param Core_Module $moduleName
	 */
	public function loadModule( $moduleName )
	{
		if(isset($this->loadedModules[$moduleName]))
		{
			return $this->loadedModules[$moduleName];
		}
		$moduleFileName = $moduleName . '/' . $moduleName . '.php';
		$moduleClassName = $moduleName;
		
		if( !Common::isValidFilename($moduleName))
		{
			throw new Exception("The module filename '$moduleFileName' is not a valid filename");
		}
		
		$path = INCLUDE_PATH . '/modules/' . $moduleFileName;

		if(!file_exists($path))
		{
			throw new Exception("Unable to load module '$moduleName' because '$path' couldn't be found.
			You can manually uninstall the module by removing the line <code>Modules[] = $moduleName</code> from the Core config file.");
		}

		// Don't remove this.
		// Our autoloader can't find modules/ModuleName/ModuleName.php
		require_once $path; // prefixed by CORE_INCLUDE_PATH
		
		if(!class_exists($moduleClassName, false))
		{
			throw new Exception("The class $moduleClassName couldn't be found in the file '$path'");
		}
		$newModule = new $moduleClassName();
		
		if(!($newModule instanceof Core_Module))
		{
			throw new Exception("The module $moduleClassName in the file $path must inherit from Core_Module.");
		}
		return $newModule;
	}
	
	public function setLanguageToLoad( $code )
	{
		$this->languageToLoad = $code;
	}

	/**
	 * @param Core_Module $module
	 */
	public function unloadModule( $module )
	{
		if(!($module instanceof Core_Module ))
		{
			$module = $this->loadModule( $module );
		}
		$hooks = $module->getListHooksRegistered();
			
		foreach($hooks as $hookName => $methodToCall)
		{
			$success = $this->dispatcher->removeObserver( array( $module, $methodToCall), $hookName );
			if($success !== true)
			{
				throw new Exception("Error unloading module = ".$module->getClassName() . ", method = $methodToCall, hook = $hookName ");
			}
		}
		unset($this->loadedModules[$module->getClassName()]);
	}
	
	public function unloadModules()
	{
		$modulesLoaded = $this->getLoadedModules();
		foreach($modulesLoaded as $module)
		{
			$this->unloadModule($module);
		}
	}

	private function installModules()
	{
		foreach($this->getLoadedModules() as $module)
		{		
			$this->installModule($module);
		}
	}
	
	private function installModule( Core_Module $module )
	{
		try{
			$module->install();
		} catch(Exception $e) {
			throw new Core_ModuleManager_ModuleException($module->getName(), $module->getClassName(), $e->getMessage());		}	
	}
	
	
	/**
	 * For the given module, add all the observers of this module.
	 */
	private function addModuleObservers( Core_Module $module )
	{
		$hooks = $module->getListHooksRegistered();
		
		foreach($hooks as $hookName => $methodToCall)
		{
			$this->dispatcher->addObserver( array( $module, $methodToCall), $hookName );
		}
	}
	
	/**
	 * Add a module in the loaded modules array
	 *
	 * @param string module name without prefix (eg. 'UserCountry')
	 * @param Core_Module $newModule
	 */
	private function addLoadedModule( $moduleName, Core_Module $newModule )
	{
		$this->loadedModules[$moduleName] = $newModule;
	}
	
	/**
	 * @param Core_Module $module
	 * @param string $langCode
	 */
	private function loadTranslation( $module, $langCode )
	{
		// we are certainly in Tracker mode, Zend is not loaded
		if(!class_exists('Zend_Loader', false))
		{
			return ;
		}
		
		$infos = $module->getInformation();		
		if(!isset($infos['translationAvailable']))
		{
			$infos['translationAvailable'] = false;
		}
		$translationAvailable = $infos['translationAvailable'];
		
		if(!$translationAvailable)
		{
			return;
		}
		
		$moduleName = $module->getClassName();
		
		$path = CORE_INCLUDE_PATH . '/modules/' . $moduleName .'/lang/%s.php';
		
		$defaultLangPath = sprintf($path, $langCode);
		$defaultEnglishLangPath = sprintf($path, 'en');
		
		$translations = array();
				
		if(file_exists($defaultLangPath))
		{
			require $defaultLangPath;
		}
		elseif(file_exists($defaultEnglishLangPath))
		{
			require $defaultEnglishLangPath;
		}
		else
		{
			throw new Exception("Language file not found for the module '$moduleName'.");
		}
		Core_Translate::getInstance()->mergeTranslationArray($translations);
	}
	
	/**
	 * @return array
	 */
	public function getInstalledModulesName()
	{
		if(!class_exists('Zend_Registry', false))
		{
			throw new Exception("Not possible to list installed modules (case Tracker module)");
		}
		$moduleNames = Zend_Registry::get('config')->ModulesInstalled->ModulesInstalled->toArray();
		return $moduleNames;
	}
	
	public function getInstalledModules()
	{
		$modules = $this->getLoadedModules();
		$installed = $this->getInstalledModulesName();
		return array_intersect_key($modules, array_combine($installed, array_fill(0, count($installed), 1)));
	}

	private function installModuleIfNecessary( Core_Module $module )
	{
		$moduleName = $module->getClassName();
		
		// is the module already installed or is it the first time we activate it?
		$modulesInstalled = $this->getInstalledModulesName();
		if(!in_array($moduleName,$modulesInstalled))
		{
			$this->installModule($module);
			$modulesInstalled[] = $moduleName;
			Zend_Registry::get('config')->ModulesInstalled = array('ModulesInstalled' => $modulesInstalled);	
		}
		
		$information = $module->getInformation();
		
		// if the module is to be loaded during the statistics logging
		if(isset($information['TrackerModule'])
			&& $information['TrackerModule'] === true)
		{
			$modulesTracker = Zend_Registry::get('config')->Modules_Tracker->Modules_Tracker;
			if(is_null($modulesTracker))
			{
				$modulesTracker = array();
			}
			else
			{
				$modulesTracker = $modulesTracker->toArray();
			}
			if(!in_array($moduleName, $modulesTracker))
			{
				$modulesTracker[] = $moduleName;
				Zend_Registry::get('config')->Modules_Tracker = array('Modules_Tracker' => $modulesTracker);
			}
		}
	}
}

/**
 * @package Core
 * @subpackage Core_ModuleManager
 */
class Core_ModuleManager_ModuleException extends Exception 
{
	function __construct($moduleName, $className, $message)
	{
		parent::__construct("There was a problem installing the module ". $moduleName . ": " . $message. "
				If this module has already been installed, and if you want to hide this message</b>, you must add the following line under the 
				[ModulesInstalled] 
				entry in your config/config.ini.php file:
				ModulesInstalled[] = $className" );
	}
}

/**
 * Post an event to the dispatcher which will notice the observers
 */
function PostEvent( $eventName,  &$object = null, $info = array() )
{
	$notification = new Event_Notification($object, $eventName, $info);
	Core_ModuleManager::getInstance()->dispatcher->postNotification( $notification, true, false );
}

/**
 * Register an action to execute for a given event
 */
function AddAction( $hookName, $function )
{
	Core_ModuleManager::getInstance()->dispatcher->addObserver( $function, $hookName );
}
