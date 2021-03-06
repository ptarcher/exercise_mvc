<?php
/**
 * Setup for the bike website
 *
 * PHP version 5
 *  
 * @author    Paul Archer <ptarcher@gmail.com>
 * @copyright 2009 Paul Archer
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.

 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/* Set up the include path */
@ini_set('magic_quotes_runtime', 0);
define('DOCUMENT_ROOT', dirname(__FILE__)=='/'?'':dirname(__FILE__));
if (!defined('USER_PATH')) {
    define('USER_PATH', DOCUMENT_ROOT);
}
if (!defined('INCLUDE_PATH')) {
    define('INCLUDE_PATH', DOCUMENT_ROOT);
}

$incPath = get_include_path();
set_include_path('libraries' . PATH_SEPARATOR . $incPath);

require_once INCLUDE_PATH.'/Core/testMinimumPhpVersion.php';

/* Zend Autoloader */
require_once INCLUDE_PATH.'/libraries/Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('Core_');
$autoloader->registerNamespace('Module_');
$autoloader->registerNamespace('HTML_');
$autoloader->registerNamespace('PEAR_');

/* Zend Session */
Zend_Session::start();

/* Start the front controller */
$controller = Core_FrontController::getInstance();
$controller->init();
$controller->dispatch();

?>
