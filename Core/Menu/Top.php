<?php
/**
 * Piwik - Open source web analytics
 * 
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @version $Id: Top.php 3270 2010-10-28 18:21:55Z vipsoft $
 * 
 * @category Piwik
 * @package Core_Menu
 */

/**
 * @package Core_Menu
 */
class Core_Menu_Top extends Core_Menu_Abstract
{
	static private $instance = null;
	/**
	 * @return Core_Menu_Top
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
	 * Directly adds a menu entry containing html.
	 *
	 * @param string $menuName
	 * @param string $data
	 * @param boolean $displayedForCurrentUser
	 * @param int $order
	 */
	public function addHtml($menuName, $data, $displayedForCurrentUser, $order) {
		if($displayedForCurrentUser) {
			if(!isset($this->menu[$menuName])) {
				$this->menu[$menuName]['_html'] = $data;
				$this->menu[$menuName]['_order'] = $order;
				$this->menu[$menuName]['_hasSubmenu'] = false;
			}
		}
	}

	/**
	 * Triggers the TopMenu.add hook and returns the menu.
	 *
	 * @return Array
	 */
	public function get()
	{
		if(!$this->menu) {
			Core_PostEvent('TopMenu.add');
		}
		return parent::get();
	}
}

/**
 * Returns the TopMenu as an array.
 *
 * @return array
 */
function Core_GetTopMenu()
{
	return Core_Menu_Top::getInstance()->get();
}

/**
 * Adds a new entry to the TopMenu.
 *
 * @param string $topMenuName
 * @param string $subTopName
 * @param string $url
 * @param boolean $displayedForCurrentUser
 * @param int $order
 */
function Core_AddTopMenu( $topMenuName, $data, $displayedForCurrentUser = true, $order = 10, $isHTML = false)
{
	if($isHTML)
	{
		Core_Menu_Top::getInstance()->addHtml($topMenuName, $data, $displayedForCurrentUser, $order);
	}
	else
	{
		Core_Menu_Top::getInstance()->add($topMenuName, null, $data, $displayedForCurrentUser, $order);
	}
}

/**
 * Renames a entry of the TopMenu
 *
 * @param string $topMenuOriginal
 * @param string $topMenuRenamed
 */
function Core_RenameTopMenuEntry($topMenuOriginal, $topMenuRenamed)
{
	Core_Menu_Top::getInstance()->rename($topMenuOriginal, null, $topMenuRenamed, null);
}
