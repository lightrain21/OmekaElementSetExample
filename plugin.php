<?php
/**
 * @version $Id$
 * @copyright Jeremy Boggs, 2011
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package OmekaElementSetExample
 */

add_plugin_hook('install', 'OmekaElementSetExample::install');
add_plugin_hook('uninstall', 'OmekaElementSetExample::uninstall');
add_plugin_hook('admin_append_to_plugin_uninstall_message', 'OmekaElementSetExample::plugin_uninstall_message');

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
     * Define all the elements associated with shiny new element set. Each
     * element can be defined either as a single string for the element set 
     * name, or as array.
     *
     * Element as string:
     *
     * <code>
     *     'Element Name'
     * </code>
     * 
     * Element as array:
     *
     * <code> 
     * array(
     *     'name'           => [(string) name, required], 
     *     'description'    => [(string) description, optional], 
     *     'record_type'    => [(string) record type name, optional], 
     *     'data_type'      => [(string) data type name, optional], 
     *     'record_type_id' => [(int) record type id, optional],
     *     'data_type_id'   => [(int) data type id, optional]
     * ) 
     * </code>
     *
     * Example that adds an element as a string and one as an array:
     *
     * <code> 
     * array(
     *     'name'           => 'Element One Name', 
     *     'description'    => 'A description for Element One', 
     *     'record_type'    => 'Item', 
     *     'data_type'      => 'Tiny Text', 
     * ),
     * 'Element Two Name'
     * </code>
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
        ),
    );
    
    /**
     * Installs the OmekaElementSetExample plugin. Gets data about our element
     * set from our class constants and $element variable to insert our element
     * set.
     *
     * @uses insert_element_set()
     * @uses get_db()
     */
    public static function install()
    {        
        /**
         * Check the ElementSet table in our Omeka database to see whether
         * Omeka already has an element set with the same name as ours. If it
         * does, stop the installation and throw an exception. We don't want to
         * overwrite existing element sets!
         */
        if (get_db()->getTable('ElementSet')->findByName(self::ELEMENT_SET_NAME)) {
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
    
    /**
     * Appends a warning message to the uninstall confirmation page.
     */
    public static function plugin_uninstall_message()
    {
        echo '<p><strong>Warning</strong>: This will permanently delete the "' . self::ELEMENT_SET_NAME . '" element set and all its associated metadata. You may deactivate this plugin if you do not want to lose data.</p>';
    }
}
