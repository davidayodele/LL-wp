<?php

	if( ! class_exists( 'ssl_zen_cloudflare_fix' ) ) {
		
		/**
		* Class to manage cloudflare infinite loop fix for ssl zen
		*/
		class ssl_zen_cloudflare_fix
		{
			/**
		     * Add hooks and filters for admin pages
		     *
		     * @since 1.0
		     * @static
		     */
			public static function init(){

				$sslActivated = get_option( 'ssl_zen_ssl_activated', '' );
				$cloudflareFix = get_option( 'ssl_zen_fix_cloudflare', '' );

				/* Check if ssl is activated and cloudflare fix is enabled */
				if( $sslActivated == '1' && $cloudflareFix == '1' ) {
					
					$serverOptions = array( 'HTTP_CF_VISITOR', 'HTTP_X_FORWARDED_PROTO' );

					foreach ($serverOptions as $option) {
						if ( isset( $_SERVER[ $option ] ) && ( strpos( $_SERVER[ $option ], 'https' ) !== false ) ) {
							$_SERVER[ 'HTTPS' ] = 'on';
							break;
						}
					}
				}

				add_action( 'admin_init', __CLASS__ . '::admin_init' );
			}

			/**
		     * Hook to be called when 'admin_init' action is called by wordpress.
		     * Sets the plugin to load first
		     *
		     * @since 1.0
		     * @static
		     */
			public static function admin_init() {
				$loadPosition = self::getActivePluginLoadPosition( SSL_ZEN_BASEFILE );
				if ( $loadPosition >= 1 ) {
					self::setActivePluginLoadPosition( SSL_ZEN_BASEFILE, 0 );
				}
			}

			/**
		     * Function to get plugin's load position
		     *
		     * @since 1.0
		     * @static
		     * @return INT Position of the plugin
		     */
			private static function getActivePluginLoadPosition( $pluginFile ) {
				$optionKey = is_multisite() ? 'active_sitewide_plugins' : 'active_plugins';
				$activePlugins = get_option( $optionKey );
				$position = -1;
				if ( is_array( $activePlugins ) ) {
					$position = array_search( $pluginFile, $activePlugins );
					if ( $position === false ) {
						$position = -1;
					}
				}
				return $position;
			}

			/**
		     * Function to set plugin's load position
		     *
		     * @since 1.0
		     * @static
		     */
			private static function setActivePluginLoadPosition( $pluginFile, $desiredPosition = 0 ) {

				$activePlugins = self::setArrayValueToPosition( get_option( 'active_plugins' ), $pluginFile, $desiredPosition );
				update_option( 'active_plugins', $activePlugins );

				if ( is_multisite() ) {
					$activePlugins = self::setArrayValueToPosition( get_option( 'active_sitewide_plugins' ), $pluginFile, $desiredPosition );
					update_option( 'active_sitewide_plugins', $activePlugins );
				}
			}

			/**
		     * Function to set array value to the position
		     *
		     * @since 1.0
		     * @static
		     * @return ARRAY Positions of the active plugins
		     */
			private static function setArrayValueToPosition( $subjectArray, $value, $desiredPosition ) {

				if ( $desiredPosition < 0 || !is_array( $subjectArray ) ) {
					return $subjectArray;
				}

				$maxPossiblePosition = count( $subjectArray ) - 1;
				if ( $desiredPosition > $maxPossiblePosition ) {
					$desiredPosition = $maxPossiblePosition;
				}

				$position = array_search( $value, $subjectArray );
				if ( $position !== false && $position != $desiredPosition ) {

					/* remove existing and reset index*/
					unset( $subjectArray[ $position ] );
					$subjectArray = array_values( $subjectArray );

					/* insert and update*/
					array_splice( $subjectArray, $desiredPosition, 0, $value );
				}

				return $subjectArray;
			}
		}

		/**
		* Calling init function and activate hooks and filters.
		*/
		ssl_zen_cloudflare_fix::init();
	}