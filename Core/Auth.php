<?php
/**
 * Core - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @version $Id: Auth.php 2968 2010-08-20 15:26:33Z vipsoft $
 *
 * @category Core
 * @package Core
 */

/**
 * Interface for authentication modules
 *
 * @package Core
 * @subpackage Core_Auth
 */
interface Core_Auth {
	/**
	 * Authentication module's name, e.g., "Login"
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Authenticates user
	 *
	 * @return Core_Auth_Result
	 */
	public function authenticate();
}

?>
