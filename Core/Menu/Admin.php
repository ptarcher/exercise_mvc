<?php
/**
 * Piwik - Open source web analytics
 * 
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @version $Id: Admin.php 3270 2010-10-28 18:21:55Z vipsoft $
 * 
 * @category Piwik
 * @package Core_Menu
 */

/**
 * @package Core_Menu
 */
class Core_Menu_Admin extends Core_Menu_Abstract
{
	static private $instance = null;
	/**
	 * @return Core_Menu_Admin
	 */
	static public function getInstance()
	{
		if (self::$instance == null)
		{
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Triggers the AdminMenu.add hook and returns the menu.
	 *
	 * @return Array
	 */
	public function get()
	{
		if(!$this->menu) {
			Core_PostEvent('AdminMenu.add');
		}
		return parent::get();
	}
}

/**
 * Returns the current AdminMenu name
 * @return boolean
 */
function Core_GetCurrentAdminMenuName()
{
	$menu = Core_GetAdminMenu();
	$currentModule = Piwik::getModule();
	$currentAction = Piwik::getAction();
	foreach($menu as $name => $parameters)
	{
		if($parameters['_url']['module'] == $currentModule
			&& $parameters['_url']['action'] == $currentAction)
		{
			return $name;
		}
	}
	return false;
}


function Core_GetAdminMenu()
{
	return Core_Menu_Admin::getInstance()->get();
}

/**
 * Adds a new AdminMenu entry.
 *
 * @param string $adminMenuName
 * @param string $url
 * @param boolean $displayedForCurrentUser
 * @param int $order
 */
function Core_AddAdminMenu( $adminMenuName, $url, $displayedForCurrentUser = true, $order = 10 )
{
	Core_Menu_Admin::getInstance()->add($adminMenuName, null, $url, $displayedForCurrentUser, $order);
}

/**
 * Renames an AdminMenu entry.
 *
 * @param string $adminMenuOriginal
 * @param string $adminMenuRenamed
 */
function Core_RenameAdminMenuEntry($adminMenuOriginal, $adminMenuRenamed)
{
	Core_Menu_Admin::getInstance()->rename($adminMenuOriginal, null, $adminMenuRenamed, null);
}
