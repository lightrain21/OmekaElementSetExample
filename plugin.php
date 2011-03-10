<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2011
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package OmekaElementSetExample
 */

add_plugin_hook('install', 'OmekaElementSetExample::install');
add_plugin_hook('uninstall', 'OmekaElementSetExample::uninstall');

/**
 * An example plugin for creating an element set in Omeka. This plugin should
 * not be installed itself. Instead, it should be used as a template for
 * creating other element set plugins. Parts are broken up a bit more than
 * necessary for ease of explanation. For more information on creating element
 * sets for Omeka, see http://omeka.org/codex/Creating_an_Element_Set.
 *
 * Based on the ZoteroImport plugin by Jim Safley.
 * 
 * @package OmekaElementSetExample
 */
class OmekaElementSetExample
{
    /**
     * Constants for the name and description of your element set. Update these
     * with your own values.
     */
    const ELEMENT_SET_NAME = 'Example Element Set';
    const ELEMENT_SET_DESCRIPTION = 'Add your element set description.';
    
    /**
     *
     *
     * @var array
     */
    public static $elements = array(
        array(
            'name'           => 'Name of First Element',
            'description'    => 'Description of First Element',
            'record_type'    => 'Item',
            'data_type'      => 'Tiny Text'
        ),
        array(
            'name'           => 'Name of Second Element',
            'description'    => 'Description of Second Element',
            'record_type'    => 'Item',
            'data_type'      => 'Tiny Text'
        )
    );
    
    /**
     * Installs the OmekaElementSetExample plugin.
     *
     * @uses insert_element_set()
     * @uses get_db()
     */
    public static function install()
    {
        $db = get_db();
        
        if ($db->getTable('ElementSet')->findByName(self::ELEMENT_SET_NAME)) {
            throw new Exception('An element set by the name "' . self::ELEMENT_SET_NAME . '" already exists. You must delete that element set to install this plugin.');
        }
        
        /**
         * Use our class constants to create an array of element set metadata,
         * for use in the insert_elmement_set() function later.
         */
        $elementSetMetadata = array(
            'name'        => self::ELEMENT_SET_NAME, 
            'description' => self::ELEMENT_SET_DESCRIPTION
        );
        
        /**
         * Use the global insert_element_set function to insert our new element
         * set. Inserts element set metadata and elements defined in the 
         * class variable $elements.
         */
        insert_element_set($elementSetMetadata, self::$elements);
    }
    
    /**
     * Uninstalls the Omeka Element Set Example plugin. This will remove the
     * element set created during installation.
     */
    public static function uninstall()
    {        
        if ($elementSet = get_db()->getTable('ElementSet')->findByName(self::ELEMENT_SET_NAME)) {
            $elementSet->delete();
        }
    }
}
