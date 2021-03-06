<?php
/**
 * Piwik - Open source web analytics
 * 
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @version $Id: Config.php 2968 2010-08-20 15:26:33Z vipsoft $
 * 
 * @category Core
 * @package Core
 */

/**
 * This class is used to access configuration files values.
 * You can also set these values, the updated configuration files will be written at the end of the script execution.
 * 
 * Example reading a value from the configuration file:
 * 	$minValue = Zend_Registry::get('config')->General->minimum_memory_limit;
 * 
 * will read the value minimumMemoryLimit under the [General] section of the config file
 * 
 * @package Core
 * @subpackage Core_Config
 */
class Core_Config
{
	/**
	 * When the user modifies the configuration file and there is one value missing, we suggest the default config file
	 *
	 * @var string
	 */
	protected $urlToCoreHelpMissingValueInConfigurationFile = 
		'http://dev.piwik.org/trac/browser/trunk/config/global.ini.php?format=raw';

	protected $defaultConfig 				= null;
	protected $userConfig 					= null;
	protected $pathIniFileUserConfig 		= null;
	protected $pathIniFileDefaultConfig 	= null;
	protected $configFileUpdated 			= false;
	protected $doWriteFileWhenUpdated		= true;
	protected $cachedConfigArray 			= array();
	protected $isTestEnvironment			= false;
	
	/**
	 * Storing the correct cwd() because the value is not correct in the destructor
	 * "The working directory in the script shutdown phase can be different with some SAPIs (e.g. Apache)."
	 * 
	 * @see http://bugs.php.net/bug.php?id=34206
	 */
	protected $correctCwd;
	
	/**
	 * Returns default relative path for user configuration file
	 *
	 * @return string
	 */
	static public function getDefaultUserConfigPath()
	{
		return USER_PATH .'/config/config.ini.php';
	}

	/**
	 * Returns default relative path for global configuration file
	 *
	 * @return string
	 */
	static public function getDefaultDefaultConfigPath()
	{
		return USER_PATH .'/config/global.ini.php';
	}
	
	/**
	 * Builds the Config object, given the optional path for the user INI file
	 * If not specified, it will use the default path
	 *
	 * @param string $pathIniFileUserConfig
	 */
	function __construct($pathIniFileUserConfig = null, $pathIniFileDefaultConfig = null)
	{
		if(is_null($pathIniFileUserConfig))
		{	
			$pathIniFileUserConfig = self::getDefaultUserConfigPath();
		}
		$this->pathIniFileUserConfig = $pathIniFileUserConfig;
		
		if(is_null($pathIniFileDefaultConfig))
		{	
			$pathIniFileDefaultConfig = self::getDefaultDefaultConfigPath();
		}
		$this->pathIniFileDefaultConfig = $pathIniFileDefaultConfig;
		
		// see http://bugs.php.net/bug.php?id=34206
		$this->correctCwd = getcwd();
	}
	
	/**
	 * By default, when calling setting configuration values using
	 * $config->database = array(...)
	 * Core will automatically save the updated config file in __destruct()
	 * This can be disabled (when setting partial configuration values during the installation process for example)
	 */
	public function disableSavingConfigurationFileUpdates()
	{
		$this->doWriteFileWhenUpdated = false;
	}
	
	public function init()
	{
		if(!is_readable($this->pathIniFileDefaultConfig))
		{
			Core_ExitWithMessage('General_ExceptionConfigurationFileNotFound: '. $this->pathIniFileDefaultConfig);
		}
		$this->defaultConfig = new Core_Config_Ini($this->pathIniFileDefaultConfig, null, true);
		if(is_null($this->defaultConfig) || count($this->defaultConfig->toArray()) == 0)
		{
        Core_ExitWithMessage('General_ExceptionUnreadableFileDisabledMethod' . $this->pathIniFileDefaultConfig);
		}

		if(!is_readable($this->pathIniFileUserConfig))
		{
			throw new Exception('General_ExceptionConfigurationFileNotFound'. $this->pathIniFileUserConfig);
		}
		$this->userConfig = new Core_Config_Ini($this->pathIniFileUserConfig, null, true);
		if(is_null($this->userConfig) || count($this->userConfig->toArray()) == 0)
		{
			Core_ExitWithMessage('General_ExceptionUnreadableFileDisabledMethod'. $this->pathIniFileUserConfig);
		}
	}
	
	/**
	 * At the script shutdown, we save the new configuration file, if the user has set some values 
	 */
	function __destruct()
	{
		if($this->configFileUpdated === true 
			&& $this->doWriteFileWhenUpdated === true)
		{
			$configFile = "; <?php exit; ?";
			$configFile .= "> DO NOT REMOVE THIS LINE\n";
			$configFile .= "; file automatically generated or modified by Core; you can manually override the default values in global.ini.php by redefining them in this file.\n";
			
			foreach($this->userConfig as $section => $arraySection)
			{
				$arraySection = $arraySection->toArray();
				$configFile .= "[$section]\n";
				foreach($arraySection as $name => $value)
				{
					if(is_numeric($name))
					{
						$name = $section;
						$value = array($value);
					}
					
					if(is_array($value))
					{
						foreach($value as $currentValue)
						{
							$configFile .= $name."[] = $currentValue\n";
						}
					}
					else
					{
						if(!is_numeric($value))
						{
							$value = "\"$value\"";
						}
						$configFile .= $name.' = '.$value."\n";						
					}
				}
				$configFile .= "\n";
			}
			chdir($this->correctCwd);
			@file_put_contents($this->pathIniFileUserConfig, $configFile );
		}
	}
	
	public function isFileWritable()
	{
		return is_writable($this->pathIniFileUserConfig);
	}
	
	/**
	 * If called, we use the database_tests credentials
	 */
	public function setTestEnvironment()
	{
		$this->isTestEnvironment = true;
		$this->database = $this->database_tests->toArray();
		// for unit tests, we set that no plugin is installed. This will force
		// the test initialization to create the plugins tables, execute ALTER queries, etc.
		$this->PluginsInstalled = array();
		
		foreach(Core_Tracker_Config::$toRestoreFromGlobalConfig as $section)
		{
			$this->$section = $this->defaultConfig->$section->toArray();
		}
		$this->disableSavingConfigurationFileUpdates();
	}

	/**
	 * Is the config file set to use the test values?
	 * @return bool
	 */
	public function isTestEnvironment()
	{
		return $this->isTestEnvironment;
	}
	
	/**
	 * Called when setting configuration values eg. 
	 * 	Zend_Registry::get('config')->MyConfigSection = 'foobar';
	 *
	 * The values will be saved in the configuration file at the end of the script @see __destruct()
	 * 
	 * @param string $name
	 * @param mixed $values
	 */
	public function __set($name, $values)
	{
		$this->cachedConfigArray = array();
		$this->checkWritePermissionOnFile();
		if(is_null($this->userConfig))
		{
			$this->userConfig = new Zend_Config(array(), true);
		}
		$values = self::encodeValues($values);
		if(is_array($values) 
			|| $this->userConfig->$name != $values)
		{
			$this->configFileUpdated = true;
		}
		$this->userConfig->$name = $values;
	}
	
	private function encodeValues($values)
	{
		if(is_array($values))
		{
			foreach($values as &$value)
			{
				$value = self::encodeValues($value);
			}
		}
		else
		{
			$values = htmlentities($values, ENT_COMPAT);
		}
		return $values;
	}
	
	private function decodeValues($values)
	{
		if(is_array($values))
		{
			foreach($values as &$value)
			{
				$value = self::decodeValues($value);
			}
		}
		else
		{
			$values = html_entity_decode($values, ENT_COMPAT);
		}
		return $values;
	}
	
	protected function checkWritePermissionOnFile() 
	{
		static $enoughPermission = null;
		if(is_null($enoughPermission))
		{
			if($this->doWriteFileWhenUpdated)
			{
				Core::checkDirectoriesWritableOrDie( array('/config/') );
			}
			$enoughPermission = true;
		}
		return $enoughPermission;
	}
	
	/**
	 * Loop through the Default and the User configuration objects and cache them in arrays.
	 * This slightly helps reducing the Zend overhead when accessing config entries hundreds of times.
	 */
	protected function cacheConfigArray()
	{
		$allSections = array(); 
		foreach($this->defaultConfig as $sectionName => $valueInDefaultConfig)
		{
			$allSections[] = $sectionName;
		}
		if(!is_null($this->userConfig))
		{
			foreach($this->userConfig as $sectionName => $valueInUserConfig)
			{
				$allSections[] = $sectionName;
			}
		}
		$allSections = array_unique($allSections);
		
		foreach($allSections as $sectionName)
		{
			$section = array();
			if(($valueInDefaultConfig = $this->defaultConfig->$sectionName) !== null)
			{
				$valueInDefaultConfig = $valueInDefaultConfig->toArray();
				$section = array_merge($section, $valueInDefaultConfig);
			}
			if( !is_null($this->userConfig)
				&& null !== ($valueInUserConfig = $this->userConfig->$sectionName))
			{
				$valueInUserConfig = $valueInUserConfig->toArray();
				$valueInUserConfig = self::decodeValues($valueInUserConfig);
				$section = array_merge($section, $valueInUserConfig);
			}
			$this->cachedConfigArray[$sectionName] = new Zend_Config($section, true);
		}
	}
	
	/**
	 * Called when getting a configuration value, eg. Zend_Registry::get('config')->superuser->login
	 *
	 * @param string $name
	 * @return mixed value 
	 * 
	 * @throws exception if the value was not found in the configuration file
	 */
	public function __get($name)
	{
		if(empty($this->cachedConfigArray))
		{
			$this->cacheConfigArray();
		}
		return $this->cachedConfigArray[$name];
	}
}


