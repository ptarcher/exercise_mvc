<?php
/**
 * Core - Open source web analytics
 * 
 * @link http://core.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @version $Id: testMinimumPhpVersion.php 3565 2011-01-03 05:49:45Z matt $
 * 
 * @category Core
 * @package Core
 */

/**
 * This file is executed before anything else. 
 * It checks the minimum PHP version required to run Core.
 * This file must be compatible PHP4.
 */

$core_errorMessage = '';

$core_minimumPHPVersion = '5.1.3';
$core_currentPHPVersion = PHP_VERSION;
if( version_compare($core_minimumPHPVersion , $core_currentPHPVersion ) > 0 )
{
	$core_errorMessage .= "<p><b>To run Core you need at least PHP version $core_minimumPHPVersion</b></p> 
				<p>Unfortunately it seems your webserver is using PHP version $core_currentPHPVersion. </p>
				<p>Please try to update your PHP version, Core is really worth it! Nowadays most web hosts 
				support PHP $core_minimumPHPVersion.</p>";
}					

$core_zend_compatibility_mode = ini_get("zend.ze1_compatibility_mode");
if($core_zend_compatibility_mode == 1)
{
	$core_errorMessage .= "<p><b>Core is not compatible with the directive <code>zend.ze1_compatibility_mode = On</code></b></p> 
				<p>It seems your php.ini file has <pre>zend.ze1_compatibility_mode = On</pre>It makes PHP5 behave like PHP4.
				If you want to use Core you need to set <pre>zend.ze1_compatibility_mode = Off</pre> in your php.ini configuration file. You may have to ask your system administrator.</p>";
}

if(!class_exists('ArrayObject', false))
{
	$core_errorMessage .= "<p><b>Core and Zend Framework require the SPL extension</p> 
				<p>It appears your PHP was compiled with --disable-spl.
				To enjoy Core, you need PHP compiled without that configure option.</p>";
}

if(!function_exists('session_cache_limiter'))
{
	$core_errorMessage .= "<p><b>Core and Zend_Session require the session extension</p> 
				<p>It appears your PHP was compiled with --disable-session.
				To enjoy Core, you need PHP compiled without that configure option.</p>";
}

/**
 * Displays info/warning/error message in a friendly UI and exits.
 *
 * @param string $message Main message
 * @param string|false $optionalTrace Backtrace; will be displayed in lighter color
 * @param bool $optionalLinks If true, will show links to the Core website for help
 */
function Core_ExitWithMessage($message, $optionalTrace = false, $optionalLinks = false)
{
	@header('Content-Type: text/html; charset=utf-8');
	if($optionalTrace)
	{
		$optionalTrace = '<font color="#888888">Backtrace:<br /><pre>'.$optionalTrace.'</pre></font>';
	}
	if($optionalLinks)
	{
		$optionalLinks = '<ul>
						<li><a target="_blank" href="?module=Proxy&action=redirect&url=http://core.org">Core homepage</a></li>
						<li><a target="_blank" href="?module=Proxy&action=redirect&url=http://core.org/faq/">Core Frequently Asked Questions</a></li>
						<li><a target="_blank" href="?module=Proxy&action=redirect&url=http://core.org/docs/">Core Documentation</a></li>
						<li><a target="_blank" href="?module=Proxy&action=redirect&url=http://forum.core.org/">Core Forums</a></li>
						<li><a target="_blank" href="?module=Proxy&action=redirect&url=http://demo.core.org">Core Online Demo</a></li>
						</ul>';
	}
	$headerPage = file_get_contents(INCLUDE_PATH . '/themes/default/templates/simple_structure_header.tpl');
	$footerPage = file_get_contents(INCLUDE_PATH . '/themes/default/templates/simple_structure_footer.tpl');
	$headerPage = str_replace('{$HTML_TITLE}', 'Core &rsaquo; Error', $headerPage);
	$content = '<p>'.$message.'</p>'. $optionalTrace .' '. $optionalLinks;
	
	echo $headerPage . $content . $footerPage;
	exit;
}

if (!function_exists('file_get_contents'))
{
	/**
	 * Reads entire file into a string.
	 * This function is not 100% compatible with the native function.
	 *
	 * @see http://php.net/file_get_contents
	 * @since PHP 4.3.0
	 *
	 * @param string $filename Name of the file to read.
	 * @return string The read data or false on failure.
	 */
	function file_get_contents($filename)
	{
		$fhandle = fopen($filename, "r");
		$fcontents = fread($fhandle, filesize($filename));
		fclose($fhandle);
		return $fcontents;
	}
}

if(!empty($core_errorMessage))
{
	Core_ExitWithMessage($core_errorMessage, false, true);
}

/**
 * We now include the upgradephp package to define some functions used in Core
 * that may not be defined in the current PHP version.
 *
 * @see libs/upgradephp/upgrade.php
 * @link http://upgradephp.berlios.de/
 */
//require_once INCLUDE_PATH . '/libs/upgradephp/upgrade.php';
