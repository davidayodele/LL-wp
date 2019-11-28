<?php

/**
 *
 * Plugin is used to manage SSL for the wordpress site.
 *
 * Plugin Name:       Free SSL Certificate for WordPress - SSL Zen
 * Plugin URI:        https://sslzen.com
 * Description:       Secure your WordPress website with a free SSL certificate from LetsEncrypt.
 * Version:           1.9.3
 * Author:            Sagar Patil
 * Author URI:        https://sagarpatil.com
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       ssl-zen
 * Domain Path:       ssl_zen/languages
 *
 * @author      Sagar Patil
 * @category    Plugin
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 */

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access Denied' );
}

if ( ! function_exists( 'sz_fs' ) ) {
	// Create a helper function for easy SDK access.
	function sz_fs() {
		global $sz_fs;

		if ( ! isset( $sz_fs ) ) {
			// Include Freemius SDK.
			require_once dirname( __FILE__ ) . '/freemius/start.php';
			$sz_fs = fs_dynamic_init( array(
				'id'               => '4586',
				'slug'             => 'ssl-zen',
				'type'             => 'plugin',
				'public_key'       => 'pk_89da8f4d86d21701663c6381a4ab4',
				'is_premium'       => false,
				'premium_suffix'   => 'Pro',
				'has_addons'       => false,
				'has_paid_plans'   => true,
				'is_org_compliant' => false,
				'menu'             => array(
					'slug'       => 'ssl_zen',
					'first-path' => 'admin.php?page=ssl_zen&tab=step1',
				),
				'is_live'          => true,
			) );
		}

		return $sz_fs;
	}

	// Init Freemius.
	sz_fs();
	// Signal that SDK was initiated.
	do_action( 'sz_fs_loaded' );
}

/**
 * Define constants used in the plugin
 */
if ( ! defined( 'SSL_ZEN_PLUGIN_VERSION' ) ) {
	define( 'SSL_ZEN_PLUGIN_VERSION', '1.9.3' );
}
define( 'SSL_ZEN_DIR', plugin_dir_path( __FILE__ ) . 'ssl_zen/' );
define( 'SSL_ZEN_URL', plugin_dir_url( __FILE__ ) . 'ssl_zen/' );
define( 'SSL_ZEN_BASEFILE', plugin_basename( __FILE__ ) );

/**
 * Include the core file of the plugin
 */
require_once( SSL_ZEN_DIR . 'classes/class.ssl_zen.php' );

if ( ! function_exists( 'ssl_zen_init' ) ) {

	/**
	 * Function to initialize the plugin.
	 *
	 * @return class object
	 */
	function ssl_zen_init() {
		/* Initialize the base class of the plugin */
		return ssl_zen::instance();
	}
}

/**
 * Create the main object of the plugin when the plugins are loaded
 */
add_action( 'plugins_loaded', 'ssl_zen_init' );