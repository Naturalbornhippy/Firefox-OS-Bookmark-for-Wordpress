<?php
/**
 * Firefox OS Bookmark
 *
 *
 * @package   Firefox_OS_Bookmark
 * @author    Mte90 <mte90net@gmail.com>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 Mte90
 *
 * @wordpress-plugin
 * Plugin Name:       Firefox OS Bookmark
 * Plugin URI:        http://mte90.net
 * Description:       This script create the manifest.webapp file used on Firefox OS for install the apps. In this way when an user with Firefox OS open your site it's asked to install an app that is a simple bookmark.
 * Version:           1.0.0
 * Author:            Mte90
 * Author URI:       
 * Text Domain:       firefox-os-bookmark
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

/*
 * @TODO:
 *
 * - eliminare la parte public
 *
 */
require_once( plugin_dir_path( __FILE__ ) . 'public/class-firefox-os-bookmark.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'Firefox_OS_Bookmark', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Firefox_OS_Bookmark', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Firefox_OS_Bookmark', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-firefox-os-bookmark-admin.php' );
	add_action( 'plugins_loaded', array( 'Firefox_OS_Bookmark_Admin', 'get_instance' ) );

}
