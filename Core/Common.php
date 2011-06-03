<?php
/**
 * Core - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html Gpl v3 or later
 * @version $Id: Common.php 1818 2010-01-31 04:23:06Z vipsoft $
 *
 * @category Core
 * @package Core
 */

/**
 * Static class providing functions used by both the CORE of Core and the visitor Tracking engine.
 *
 * This is the only external class loaded by the /piwik.php file.
 * This class should contain only the functions that are used in
 * both the CORE and the piwik.php statistics logging engine.
 *
 * @package Core
 */
class Core_Common
{
	/**
	 * Const used to map the referer type to an integer in the log_visit table
	 */
	const REFERER_TYPE_DIRECT_ENTRY		= 1;
	const REFERER_TYPE_SEARCH_ENGINE	= 2;
	const REFERER_TYPE_WEBSITE			= 3;
	const REFERER_TYPE_CAMPAIGN			= 6;

    const CLASSES_PREFIX = 'Core_';

	/**
	 * Flag used with htmlspecialchar
	 * See php.net/htmlspecialchars
	 */
	const HTML_ENCODING_QUOTE_STYLE		= ENT_COMPAT;

	/**
	 * Returns the path and query part from a URL.
	 * Eg. http://piwik.org/test/index.php?module=CoreHome will return /test/index.php?module=CoreHome
	 *
	 * @param string $url either http://piwik.org/test or /
	 * @return string
	 */
	static function getPathAndQueryFromUrl($url)
	{
		$parsedUrl = parse_url( $url );
		$result = '';
		if(isset($parsedUrl['path']))
		{
			$result .= substr($parsedUrl['path'], 1);
		}
		if(isset($parsedUrl['query']))
		{
			$result .= '?'.$parsedUrl['query'];
		}
		return $result;
	}

	/**
	 * ending WITHOUT slash
	 * @return string
	 */
	static public function getPathToRoot()
	{
		return realpath( dirname(__FILE__). "/.." );
	}

	/**
	 * Returns the value of a GET parameter $parameter in an URL query $urlQuery
	 *
	 * @param string $urlQuery result of parse_url()['query'] and htmlentitied (& is &amp;) eg. module=test&amp;action=toto or ?page=test
	 * @param string $param
	 *
	 * @return string|bool Parameter value if found (can be the empty string!), false if not found
	 */
	static public function getParameterFromQueryString( $urlQuery, $parameter)
	{
		$nameToValue = self::getArrayFromQueryString($urlQuery);
		if(isset($nameToValue[$parameter]))
		{
			return $nameToValue[$parameter];
		}
		return false;
	}

	/**
	 * Returns an URL query string in an array format
	 * The input query string should be htmlspecialchar'ed
	 *
	 * @param string urlQuery
	 * @return array array( param1=> value1, param2=>value2)
	 */
	static public function getArrayFromQueryString( $urlQuery )
	{
		if(strlen($urlQuery) == 0)
		{
			return array();
		}
		if($urlQuery[0] == '?')
		{
			$urlQuery = substr($urlQuery, 1);
		}

		$separator = '&';

		$urlQuery = $separator . $urlQuery;
		//		$urlQuery = str_replace(array('%20'), ' ', $urlQuery);
		$refererQuery = trim($urlQuery);

		$values = explode($separator, $refererQuery);

		$nameToValue = array();

		foreach($values as $value)
		{
			if( false !== strpos($value, '='))
			{
				$exploded = explode('=',$value);
				$name = $exploded[0];

				// if array without indexes
				if( substr($name,-2,2) == '[]' )
				{
					$name = substr($name, 0, -2);
					if( isset($nameToValue[$name]) == false || is_array($nameToValue[$name]) == false )
					{
						$nameToValue[$name] = array();
					}
					array_push($nameToValue[$name],$exploded[1]);
				}
				else
				{
					$nameToValue[$name] = $exploded[1];
				}
			}
		}
		return $nameToValue;
	}

	/**
	 * Create directory if permitted
	 *
	 * @param string $path
	 * @param int $mode (in octal)
	 * @param bool $denyAccess
	 */
	static public function mkdir( $path, $mode = 0755, $denyAccess = true )
	{
		if(!is_dir($path))
		{
			$directoryParent = self::realpath(dirname($path));
			if( is_writable($directoryParent) )
			{
				mkdir($path, $mode, true);
			}
		}

		if($denyAccess)
		{
			self::createHtAccess($path);
		}
	}

	/**
	 * Create .htaccess file in specified directory
	 *
	 * Apache-specific; for IIS @see web.config
	 *
	 * @param string $path without trailing slash
	 */
	static public function createHtAccess( $path )
	{
		@file_put_contents($path . '/.htaccess', 'Deny from all');
	}

	/**
	 * Get canonicalized absolute path
	 * See http://php.net/realpath
	 *
	 * @param string $path
	 * @return string canonicalized absolute path
	 */
	static public function realpath($path)
	{
		if (file_exists($path))
		{
		    return realpath($path);
		}
	    return $path;
	}

	/**
	 * Returns true if the string is a valid filename
	 * File names that start with a-Z or 0-9 and contain a-Z, 0-9, underscore(_), dash(-), and dot(.) will be accepted.
	 * File names beginning with anything but a-Z or 0-9 will be rejected (including .htaccess for example).
	 * File names containing anything other than above mentioned will also be rejected (file names with spaces won't be accepted).
	 *
	 * @param string filename
	 * @return bool
	 *
	 */
	static public function isValidFilename($filename)
	{
		return (0 !== preg_match('/(^[a-zA-Z0-9]+([a-zA-Z_0-9.-]*))$/', $filename));
	}

	/**
	 * Returns true if the string passed may be a URL.
	 * We don't need a precise test here because the value comes from the website
	 * tracked source code and the URLs may look very strange.
	 *
	 * @param string $url
	 * @return bool
	 */
	static function isLookLikeUrl( $url )
	{
		return preg_match('~^(ftp|news|http|https)?://(.*)$~', $url, $matches) !== 0
				&& strlen($matches[2]) > 0;
	}

	/**
	 * Returns the variable after cleaning operations.
	 * NB: The variable still has to be escaped before going into a SQL Query!
	 *
	 * If an array is passed the cleaning is done recursively on all the sub-arrays.
	 * The array's keys are filtered as well!
	 *
	 * How this method works:
	 * - The variable returned has been htmlspecialchars to avoid the XSS security problem.
	 * - The single quotes are not protected so "Core's amazing" will still be "Core's amazing".
	 *
	 * - Transformations are:
	 * 		- '&' (ampersand) becomes '&amp;'
	 *  	- '"'(double quote) becomes '&quot;'
	 * 		- '<' (less than) becomes '&lt;'
	 * 		- '>' (greater than) becomes '&gt;'
	 * - It handles the magic_quotes setting.
	 * - A non string value is returned without modification
	 *
	 * @param mixed The variable to be cleaned
	 * @return mixed The variable after cleaning
	 */
	static public function sanitizeInputValues($value)
	{
		if(is_numeric($value))
		{
			return $value;
		}
		elseif(is_string($value))
		{
			$value = self::sanitizeInputValue($value);

			// Undo the damage caused by magic_quotes; deprecated in php 5.3 but not removed until php 6
			if ( version_compare(phpversion(), '6') === -1
				&& get_magic_quotes_gpc())
			{
				$value = stripslashes($value);
			}
		}
		elseif (is_array($value))
		{
			foreach (array_keys($value) as $key)
			{
				$newKey = $key;
				$newKey = self::sanitizeInputValues($newKey);
				if ($key != $newKey)
				{
					$value[$newKey] = $value[$key];
					unset($value[$key]);
				}

				$value[$newKey] = self::sanitizeInputValues($value[$newKey]);
			}
		}
		elseif( !is_null($value)
			&& !is_bool($value))
		{
			throw new Exception("The value to escape has not a supported type. Value = ".var_export($value, true));
		}
		return $value;
	}

	/**
	 * Sanitize a single input value
	 *
	 * @param string $value
	 * @return string sanitized input
	 */
	static public function sanitizeInputValue($value)
	{
		return htmlspecialchars($value, self::HTML_ENCODING_QUOTE_STYLE, 'UTF-8');
	}

	/**
	 * Unsanitize a single input value
	 *
	 * @param string $value
	 * @return string unsanitized input
	 */
	static public function unsanitizeInputValue($value)
	{
		return htmlspecialchars_decode($value, self::HTML_ENCODING_QUOTE_STYLE);
	}

	/**
	 * Returns a sanitized variable value from the $_GET and $_POST superglobal.
	 * If the variable doesn't have a value or an empty value, returns the defaultValue if specified.
	 * If the variable doesn't have neither a value nor a default value provided, an exception is raised.
	 *
	 * @see sanitizeInputValues() for the applied sanitization
	 *
	 * @param string $varName name of the variable
	 * @param string $varDefault default value. If '', and if the type doesn't match, exit() !
	 * @param string $varType Expected type, the value must be one of the following: array, int, integer, string
	 *
	 * @exception if the variable type is not known
	 * @exception if the variable we want to read doesn't have neither a value nor a default value specified
	 *
	 * @return mixed The variable after cleaning
	 */
	static public function getRequestVar($varName, $varDefault = null, $varType = null, $requestArrayToUse = null)
	{
		if(is_null($requestArrayToUse))
		{
			$requestArrayToUse = $_GET + $_POST;
		}
		$varDefault = self::sanitizeInputValues( $varDefault );
		if($varType == 'int')
		{
			// settype accepts only integer
			// 'int' is simply a shortcut for 'integer'
			$varType = 'integer';
		}

		// there is no value $varName in the REQUEST so we try to use the default value
		if(empty($varName)
			|| !isset($requestArrayToUse[$varName])
			|| (	!is_array($requestArrayToUse[$varName])
				&& strlen($requestArrayToUse[$varName]) === 0
				)
		)
		{
			if( is_null($varDefault))
			{
				throw new Exception("The parameter '$varName' isn't set in the Request, and a default value wasn't provided.");
			}
			else
			{
				if( !is_null($varType)
					&& in_array($varType, array('string', 'integer', 'array'))
				)
				{
					settype($varDefault, $varType);
				}
				return $varDefault;
			}
		}

		// Normal case, there is a value available in REQUEST for the requested varName
		$value = self::sanitizeInputValues( $requestArrayToUse[$varName] );

		if( !is_null($varType))
		{
			$ok = false;

			if($varType == 'string')
			{
				if(is_string($value)) $ok = true;
			}
			elseif($varType == 'integer')
			{
				if($value == (string)(int)$value) $ok = true;
			}
			elseif($varType == 'float')
			{
				if($value == (string)(float)$value) $ok = true;
			}
			elseif($varType == 'array')
			{
				if(is_array($value)) $ok = true;
			}
			else
			{
				throw new Exception("\$varType specified is not known. It should be one of the following: array, int, integer, float, string");
			}

			// The type is not correct
			if($ok === false)
			{
				if($varDefault === null)
				{
					throw new Exception("The parameter '$varName' doesn't have a correct type, and a default value wasn't provided.");
				}
				// we return the default value with the good type set
				else
				{
					settype($varDefault, $varType);
					return $varDefault;
				}
			}
		}
		return $value;
	}

	/**
	 * Unserialize (serialized) array
	 *
	 * @param string
	 * @return array or original string if not unserializable
	 */
	public static function unserialize_array( $str )
	{
		// we set the unserialized version only for arrays as you can have set a serialized string on purpose
		if (preg_match('/^a:[0-9]+:{/', $str)
			&& !preg_match('/(^|;|{|})O:[0-9]+:"/', $str)
			&& strpos($str, "\0") === false)
		{
			if( ($arrayValue = @unserialize($str)) !== false
				&& is_array($arrayValue) )
			{
				return $arrayValue;
			}
		}

		// return original string
		return $str;
	}

	/**
	 * Returns a 32 characters long uniq ID
	 *
	 * @return string 32 chars
	 */
	static public function generateUniqId()
	{
		return md5(uniqid(rand(), true));
	}

    /**
     * Get salt from [superuser] section
     *
     * @return string
     */
    static public function getSalt()
    {
        static $salt = null;
        if(is_null($salt))
        {
            $config = Zend_Registry::get('config');
            if($config !== false)
            {
                $salt = @$config->superuser->salt;
            }
        }
        return $salt;
    }


	/**
	 * Generate random string
	 *
	 * @param string $length string length
	 * @param string $alphabet characters allowed in random string
	 * @return string random string with given length
	 */
	public static function getRandomString($length = 16, $alphabet = "abcdefghijklmnoprstuvwxyz0123456789")
	{
		$chars = $alphabet;
		$str = '';

		list($usec, $sec) = explode(" ", microtime());
		$seed = ((float)$sec+(float)$usec)*100000;
		mt_srand($seed);

		for($i = 0; $i < $length; $i++)
		{
			$rand_key = mt_rand(0, strlen($chars)-1);
			$str  .= substr($chars, $rand_key, 1);
		}
		return str_shuffle($str);
	}

/*
 * Prefix/unprefix class name
 */
    /**
     * Prefix class name (if needed)
     *
     * @param string $class
     * @return string
     */
    static public function prefixClass( $class )
    {
        if(!strncmp($class, Core_Common::CLASSES_PREFIX, 
                    strlen(Core_Common::CLASSES_PREFIX)))
        {
            return $class;
        }
        return Core_Common::CLASSES_PREFIX.$class;
    }

    /**
     * Unprefix class name (if needed)
     *
     * @param string $class
     * @return string
     */
    static public function unprefixClass( $class )
    {
        $lenPrefix = strlen(Core_Common::CLASSES_PREFIX);
        if(!strncmp($class, Core_Common::CLASSES_PREFIX, $lenPrefix))
        {
            return substr($class, $lenPrefix);
        }
        return $class;
    }


/*
 * System environment
 */
	/**
	 * Returns true if PHP was invoked from command-line interface (shell)
	 *
	 * @since added in 0.4.4
	 * @return bool true if PHP invoked as a CGI or from CLI
	 */
	static public function isPhpCliMode()
	{
		return	PHP_SAPI == 'cli' ||
				(substr(PHP_SAPI, 0, 3) == 'cgi' && @$_SERVER['REMOTE_ADDR'] == '');
	}

    /**
     * Returns true if running on a Windows operating system
     *
     * @since added in 0.6.5
     * @return bool true if PHP detects it is running on Windows; else false
     */
    static public function isWindows()
    {
        return DIRECTORY_SEPARATOR == '\\';
    }

    /*
     * HTTP headers
     */
    /**
     * Returns true if this appears to be a secure HTTPS connection
     *
     * @return bool
     */
    static public function isHttps()
    {
        return Core_Url::getCurrentScheme() === 'https' || Zend_Registry::     get('config')->General->assume_secure_protocol;
    }

/*
 * Access
 */
    /**
     * Create access object
     */
    static public function createAccessObject()
    {
        Zend_Registry::set('access', new Core_Access());
    }


	/**
	 * Get current user email address
	 *
	 * @return string
	 */
	static public function getCurrentUserEmail()
	{
		if(!Core_Common::isUserIsSuperUser())
		{
			$user = Module_UserManagement_API::getInstance()->getUser(Core_Common::getCurrentUserLogin());
			return $user['email'];
		}
		$superuser = Zend_Registry::get('config')->superuser;
		return $superuser->email;
	}

	/**
	 * Get current user login
	 *
	 * @return string login ID
	 */
	static public function getCurrentUserLogin()
	{
		return Zend_Registry::get('access')->getLogin();
	}

	/**
	 * Get current user's token auth
	 *
	 * @return string Token auth
	 */
	static public function getCurrentUserTokenAuth()
	{
		return Zend_Registry::get('access')->getTokenAuth();
	}

	/**
	 * Returns true if the current user is either the super user, or the user $theUser
	 * Used when modifying user preference: this usually requires super user or being the user itself.
	 *
	 * @param string $theUser
	 * @return bool
	 */
	static public function isUserIsSuperUserOrTheUser( $theUser )
	{
		try{
			self::checkUserIsSuperUserOrTheUser( $theUser );
			return true;
		} catch( Exception $e){
			return false;
		}
	}

	/**
	 * Check that current user is either the specified user or the superuser
	 *
	 * @param string $theUser
	 * @throws exception if the user is neither the super user nor the user $theUser
	 */
	static public function checkUserIsSuperUserOrTheUser( $theUser )
	{
		try{
			if( Core_Common::getCurrentUserLogin() !== $theUser)
			{
				// or to the super user
				Core_Common::checkUserIsSuperUser();
			}
		} catch( Core_Access_NoAccessException $e){
			throw new Core_Common_Access_NoAccessException("The user has to be either the Super User or the user '$theUser' itself.");
		}
	}

	/**
	 * Returns true if the current user is the Super User
	 *
	 * @return bool
	 */
	static public function isUserIsSuperUser()
	{
		try{
			self::checkUserIsSuperUser();
			return true;
		} catch( Exception $e){
			return false;
		}
	}

	/**
	 * Is user the anonymous user?
	 *
	 * @return bool True if anonymouse; false otherwise
	 */
	static public function isUserIsAnonymous()
	{
		return Core_Common::getCurrentUserLogin() == 'anonymous';
	}

	/**
	 * Checks if user is not the anonymous user.
	 *
	 * @throws Exception if user is anonymous.
	 */
	static public function checkUserIsNotAnonymous()
	{
		if(self::isUserIsAnonymous())
		{
			throw new Exception('General_YouMustBeLoggedIn');
		}
	}

	/**
	 * Helper method user to set the current as Super User.
	 * This should be used with great care as this gives the user all permissions.
	 *
	 * @param bool True to set current user as super user
	 */
	static public function setUserIsSuperUser( $bool = true )
	{
		Zend_Registry::get('access')->setSuperUser($bool);
	}

	/**
	 * Check that user is the superuser
	 *
	 * @throws Exception if not the superuser
	 */
	static public function checkUserIsSuperUser()
	{
		Zend_Registry::get('access')->checkUserIsSuperUser();
	}

	/**
	 * Returns true if the user has admin access to the sites
	 *
	 * @param mixed $idSites
	 * @return bool
	 */
	static public function isUserHasAdminAccess( $idSites )
	{
		try{
			self::checkUserHasAdminAccess( $idSites );
			return true;
		} catch( Exception $e){
			return false;
		}
	}

	/**
	 * Check user has admin access to the sites
	 *
	 * @param mixed $idSites
	 * @throws Exception if user doesn't have admin access to the sites
	 */
	static public function checkUserHasAdminAccess( $idSites )
	{
		Zend_Registry::get('access')->checkUserHasAdminAccess( $idSites );
	}

	/**
	 * Returns true if the user has admin access to any sites
	 *
	 * @return bool
	 */
	static public function isUserHasSomeAdminAccess()
	{
		try{
			self::checkUserHasSomeAdminAccess();
			return true;
		} catch( Exception $e){
			return false;
		}
	}

	/**
	 * Check user has admin access to any sites
	 *
	 * @throws Exception if user doesn't have admin access to any sites
	 */
	static public function checkUserHasSomeAdminAccess()
	{
		Zend_Registry::get('access')->checkUserHasSomeAdminAccess();
	}

	/**
	 * Returns true if the user has view access to the sites
	 *
	 * @param mixed $idSites
	 * @return bool
	 */
	static public function isUserHasViewAccess( $idSites )
	{
		try{
			self::checkUserHasViewAccess( $idSites );
			return true;
		} catch( Exception $e){
			return false;
		}
	}

	/**
	 * Check user has view access to the sites
	 *
	 * @param mixed $idSites
	 * @throws Exception if user doesn't have view access to sites
	 */
	static public function checkUserHasViewAccess( $idSites )
	{
		Zend_Registry::get('access')->checkUserHasViewAccess( $idSites );
	}

	/**
	 * Returns true if the user has view access to any sites
	 *
	 * @return bool
	 */
	static public function isUserHasSomeViewAccess()
	{
		try{
			self::checkUserHasSomeViewAccess();
			return true;
		} catch( Exception $e){
			return false;
		}
	}

	/**
	 * Check user has view access to any sites
	 *
	 * @throws Exception if user doesn't have view access to any sites
	 */
	static public function checkUserHasSomeViewAccess()
	{
		Zend_Registry::get('access')->checkUserHasSomeViewAccess();
	}

/*
 * PHP environment settings
 */

    /**
     * Set maximum script execution time.
     *
     * @param int max execution time in seconds (0 = no limit)
     */
    static public function setMaxExecutionTime($executionTime)
    {
        // in the event one or the other is disabled...
        @ini_set('max_execution_time', $executionTime);
        @set_time_limit($executionTime);
    }

    /**
     * Get php memory_limit (in Megabytes)
     *
     * Prior to PHP 5.2.1, or on Windows, --enable-memory-limit is not a
     * compile-time default, so ini_get('memory_limit') may return false.
     *
     * @see http://www.php.net/manual/en/faq.using.php#faq.using.shorthandbytes
     * @return int memory limit in megabytes
     */
    static public function getMemoryLimitValue()
    {
        if($memory = ini_get('memory_limit'))
        {
            // handle shorthand byte options (case-insensitive)
            $shorthandByteOption = substr($memory, -1);
            switch($shorthandByteOption)
            {
                case 'G':
                case 'g':
                    return substr($memory, 0, -1) * 1024;
                case 'M':
                case 'm':
                    return substr($memory, 0, -1);
                case 'K':
                case 'k':
                    return substr($memory, 0, -1) / 1024;
            }
            return $memory / 1048576;
        }
        return false;
    }

    /**
     * Set PHP memory limit
     *
     * Note: system settings may prevent scripts from overriding the master value
     *
     * @param int $minimumMemoryLimit
     * @return bool true if set; false otherwise
     */
    static public function setMemoryLimit($minimumMemoryLimit)
    {
        // in Megabytes
        $currentValue = self::getMemoryLimitValue();
        if( ($currentValue === false
                    || $currentValue < $minimumMemoryLimit )
                && @ini_set('memory_limit', $minimumMemoryLimit.'M'))
        {
            return true;
        }
        return false;
    }

    /**
     * Raise PHP memory limit if below the minimum required
     *
     * @return bool true if set; false otherwise
     */
    static public function raiseMemoryLimitIfNecessary()
    {
        $minimumMemoryLimit = Zend_Registry::get('config')->General->minimum_memory_limit;
        $memoryLimit = self::getMemoryLimitValue();
        if($memoryLimit === false
                || $memoryLimit < $minimumMemoryLimit)
        {
            return self::setMemoryLimit($minimumMemoryLimit);
        }

        return false;
    }
}
