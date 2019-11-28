<?php

if ( ! class_exists( 'ssl_zen_scripts' ) ) {

	/**
	 * Class to manage the scripts and styles for SSL Zen
	 */
	class ssl_zen_scripts {
		/**
		 * Add hooks and filters to enqueue scripts and styles needed for SSL Zen
		 *
		 * @since 1.0
		 * @static
		 */
		public static function init() {
			$page = isset( $_REQUEST['page'] ) ? sanitize_text_field($_REQUEST['page']) : '';
			if ( $page == 'ssl_zen' ) {
				add_action( 'admin_enqueue_scripts', __CLASS__ . '::admin_enqueue_scripts' );
			}
		}

		/**
		 * Hook to add scripts and styles for SSL Zen admin
		 *
		 * @since 1.0
		 * @static
		 */
		public static function admin_enqueue_scripts() {
			wp_enqueue_style( 'ssl-zen-font-css', SSL_ZEN_URL . 'css/fonts.css', array(), SSL_ZEN_PLUGIN_VERSION );
			wp_enqueue_style( 'ssl-zen-bootstrap-css', SSL_ZEN_URL . 'css/bootstrap.min.css', array(),
				SSL_ZEN_PLUGIN_VERSION );
			wp_enqueue_style( 'ssl-zen-fontawesome-css', SSL_ZEN_URL . 'css/font-awesome.min.css', array(),
				SSL_ZEN_PLUGIN_VERSION );
			wp_enqueue_style( 'ssl-zen-build-css', SSL_ZEN_URL . 'css/build.css', array(), SSL_ZEN_PLUGIN_VERSION );
			wp_enqueue_style( 'ssl-zen-bootstrap-toggle-css', SSL_ZEN_URL . 'css/bootstrap-toggle.min.css', array(),
				SSL_ZEN_PLUGIN_VERSION );
			wp_enqueue_style( 'ssl-zen-style-css', SSL_ZEN_URL . 'css/style.css', array(), SSL_ZEN_PLUGIN_VERSION );
			wp_enqueue_style( 'ssl-zen-google-fonts', 'https://fonts.googleapis.com/css?family=Roboto&display=swap',
				false, SSL_ZEN_PLUGIN_VERSION );

			wp_enqueue_script( 'ssl-zen-jquery-validate-js', SSL_ZEN_URL . 'js/jquery.validate.js', array( 'jquery' ),
				SSL_ZEN_PLUGIN_VERSION );
			wp_enqueue_script( 'ssl-zen-bootstrap-js', SSL_ZEN_URL . 'js/bootstrap.min.js', array( 'jquery' ),
				SSL_ZEN_PLUGIN_VERSION );
			wp_enqueue_script( 'ssl-zen-bootstrap-toggle-js', SSL_ZEN_URL . 'js/bootstrap-toggle.min.js',
				array( 'jquery' ), SSL_ZEN_PLUGIN_VERSION );
			wp_enqueue_script( 'ssl-zen-main-js', SSL_ZEN_URL . 'js/main.js', array( 'jquery' ),
				SSL_ZEN_PLUGIN_VERSION );
		}
		
	}

	/**
	 * Calling init function and activate hooks and filters.
	 */
	ssl_zen_scripts::init();
}