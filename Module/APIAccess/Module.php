<?php
/**
 * Core - Open source web analytics
 * 
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html Gpl v3 or later
 * @version $Id: Module.php 1420 2009-08-22 13:23:16Z vipsoft $
 * 
 * @category Core_Plugins
 * @package Core_API
 */

/**
 * 
 * @package Core_API
 */
class Module_APIAccess_Module extends Core_Module
{
    public function getInformation()
    {
        return array(
                'name'        => 'APIAccess',
                'description' => 'Allows direct access to API functions',
                'author'      => 'Paul Archer',
                'version'     => '0.1',
                );
    }
}
