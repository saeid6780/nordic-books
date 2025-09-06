<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://linkedin.com/in/saeid-sadigh-zadeh-8861688a
 * @since             1.0.0
 * @package           Nordic_Books
 *
 * @wordpress-plugin
 * Plugin Name:       Nordic Books
 * Plugin URI:        https://linkedin.com/in/saeid-sadigh-zadeh-8861688a
 * Description:       Book Info WordPress Plugin
 * Version:           1.0.0
 * Author:            saeid6780
 * Author URI:        https://linkedin.com/in/saeid-sadigh-zadeh-8861688a/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nordic-books
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
define( 'NORDIC_BOOKS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-nordic-books-activator.php
 */
function activate_nordic_books() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nordic-books-activator.php';
	Nordic_Books_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-nordic-books-deactivator.php
 */
function deactivate_nordic_books() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nordic-books-deactivator.php';
	Nordic_Books_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_nordic_books' );
register_deactivation_hook( __FILE__, 'deactivate_nordic_books' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-nordic-books.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_nordic_books() {

	$plugin = new Nordic_Books();
	$plugin->run();

}
run_nordic_books();
