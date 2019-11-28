<?php

	if( ! class_exists( 'ssl_zen' ) ) {
		/**
		* Base class of the plugin
		*/
		class ssl_zen
		{
			/**
		     * @var ssl_zen the single instance of the class
		     * @since 1.0
		     */
			protected static $instance = null;

			/**
		     * Instantiates the plugin and include all the files needed for the plugin.
		     */
			function __construct() {
				self::include_plugin_files();
			}

			/**
		     * Main SSL Zen Plugin instance
		     *
		     * Ensures only one instance of SSL Zen is loaded or can be loaded.
		     *
		     * @since 1.0
		     * @static
		     * @return SSL Zen - Main instance
		     */
			public static function instance() {
				if ( is_null( self::$instance ) ) {
		            self::$instance = new self();
		        }

		        return self::$instance;
			}

			/**
		     * Include all the files needed for the plugin.
		     */
			private static function include_plugin_files() {
				require_once(SSL_ZEN_DIR . 'classes/class.ssl_zen_cloudflare_fix.php');
				require_once(SSL_ZEN_DIR . 'classes/class.ssl_zen_certificate.php');
				require_once(SSL_ZEN_DIR . 'classes/class.ssl_zen_admin.php');
				require_once(SSL_ZEN_DIR . 'classes/class.ssl_zen_scripts.php');
				require_once(SSL_ZEN_DIR . 'classes/class.ssl_zen_https.php');
				require_once(SSL_ZEN_DIR . 'classes/class.ssl_zen_scheduled.php');
			}
		}
	}