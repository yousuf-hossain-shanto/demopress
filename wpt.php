<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://microtriangle.com
 * @since             1.0.0
 * @package           Wpt
 *
 * @wordpress-plugin
 * Plugin Name:       WP Temp
 * Plugin URI:        https://microtriangle.com/product/wp-temp
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Microtriangle
 * Author URI:        https://microtriangle.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WPT_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpt-activator.php
 */
function activate_wpt() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpt-activator.php';
	Wpt_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpt-deactivator.php
 */
function deactivate_wpt() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpt-deactivator.php';
	Wpt_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wpt' );
register_deactivation_hook( __FILE__, 'deactivate_wpt' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpt.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wpt() {

	$plugin = new Wpt();
	$plugin->run();

}
run_wpt();
