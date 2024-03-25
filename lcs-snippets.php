<?php
/**
 * Plugin Name: Louis Code Snippets
 * Plugin URI: https://louis.fatbois.life/
 * Description: A plugin that enables to create and showcase code snippets.
 * Version: 1.0.0
 * Author: <a href="https://louis.fatbois.life/">Code by Louis</a>
 * Author URI: https://louis.fatbois.life/
 * Requires at least: 4.4
 * Tested up to: 4.9
 *
 * Text Domain: lcs-snippets
 */

// LCS_SNIPPETS
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {	exit; }

// Defined constant
define( 'LCS_SNIPPETS_NAME', 'Louis Code Snippets' );
define( 'LCS_SNIPPETS_URL', plugin_dir_url( __FILE__ ) );
define( 'LCS_SNIPPETS_PATH', plugin_dir_path( __FILE__ ) );
define( 'LCS_SNIPPETS_VERSION', '1.0.0' );

// Includes Files
require_once( LCS_SNIPPETS_PATH . 'includes/class-core.php' );
require_once( LCS_SNIPPETS_PATH . 'includes/class-post-type.php' );
require_once( LCS_SNIPPETS_PATH . 'includes/class-scripts.php' );
require_once( LCS_SNIPPETS_PATH . 'includes/functions.php' );


// Load textdomain translation
add_action( 'plugins_loaded', 'lcs_load_textdomain' );
function lcs_load_textdomain() {
	load_plugin_textdomain( 'lcs-snippets', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}

register_activation_hook( __FILE__, 'lcs_set_options' );