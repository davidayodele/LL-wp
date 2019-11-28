<?php

	if( ! class_exists( 'ssl_zen_scheduled' ) ) {
		
		/**
		* Class to manage scheduled tasks
		*/
		class ssl_zen_scheduled {

			/**
		     * Add hooks and filters for the plugin
		     *
		     * @since 1.0
		     * @static
		     */
			public static function init() {

				$sent60daysEmail = get_option( 'ssl_zen_certificate_60_days_email_sent', '' );
				$sent90daysEmail = get_option( 'ssl_zen_certificate_90_days_email_sent', '' );

				/* Send reminder after 60 days */
				if( get_option( 'ssl_zen_certificate_60_days', '' ) != '' ) {
					if($sent60daysEmail == '1') {
						/* If email already sent, remove from schedule */
						$timestamp = wp_next_scheduled( 'ssl_zen_60_days_email' );
						if ( $timestamp != false ) {
							wp_unschedule_event( $timestamp, 'ssl_zen_60_days_email' );
						}
					} else {
						/* If email not sent schedule */
						if ( ! wp_next_scheduled( 'ssl_zen_60_days_email' ) ) {
							wp_schedule_event( time(), 'daily', 'ssl_zen_60_days_email' );
						}
					}

					add_action( 'ssl_zen_60_days_email', __CLASS__ . '::ssl_zen_60_days_email_hook' );
				}


				/* Send reminder after 90 days */
				if( get_option( 'ssl_zen_certificate_90_days', '' ) != '' ) {
					if($sent90daysEmail == '1') {
						/* If email already sent, remove from schedule */
						$timestamp = wp_next_scheduled( 'ssl_zen_90_days_email' );
						if ( $timestamp != false ) {
							wp_unschedule_event( $timestamp, 'ssl_zen_90_days_email' );
						}
					} else {
						/* If email not sent schedule */
						if ( ! wp_next_scheduled( 'ssl_zen_90_days_email' ) ) {
							wp_schedule_event( time(), 'daily', 'ssl_zen_90_days_email' );
						}
					}

					add_action( 'ssl_zen_90_days_email', __CLASS__ . '::ssl_zen_90_days_email_hook' );
				}
			}

			/**
		     * Function to send the renewal reminder email after 60 days
		     *
		     * @since 1.0
		     * @static
		     */
			public static function ssl_zen_60_days_email_hook() {
				if( date_i18n( 'Y-m-d' ) > get_option( 'ssl_zen_certificate_60_days', '' ) ){

					$headers = array('Content-Type: text/html; charset=UTF-8');

					$message = __( 'Hello,', 'ssl-zen' ) . '<br><br>';
					$message .= __( 'Your SSL Certificate will expire on .', 'ssl-zen' ) . ' ' . get_option( 'ssl_zen_certificate_90_days', '' ) . '<br>';
					$message .= __( 'Please make sure to renew your certificate before then, or visitors to your website will encounter errors.', 'ssl-zen' ) . '<br><br>';
                    $message .= __( 'If you want us to automatically renew your SSL certificates, please upgrade to <a href="https://checkout.freemius.com/mode/dialog/plugin/4586/plan/7397/">SSL Zen Pro.</a>', 'ssl-zen' ) . '<br>';
					$message .= __( 'Regards,', 'ssl-zen' ) . '<br>' . get_bloginfo( 'name' );	
				
					wp_mail( get_option( 'ssl_zen_email', '' ), 'Important: SSL certificate renewal reminder', $message, $headers );

					update_option( 'ssl_zen_certificate_60_days_email_sent', '1' );
				}
			}

			/**
		     * Function to send the renewal reminder email after 90 days
		     *
		     * @since 1.0
		     * @static
		     */
			public static function ssl_zen_90_days_email_hook() {

				if( date_i18n( 'Y-m-d' ) > get_option( 'ssl_zen_certificate_90_days', '' ) ){

					$headers = array('Content-Type: text/html; charset=UTF-8');

					$message = __( 'Hello,', 'ssl-zen' ) . '<br><br>';
					$message .= __( 'Your SSL Certificate expired on .', 'ssl-zen' ) . ' ' . get_option( 'ssl_zen_certificate_90_days', '' ) . '<br>';
					$message .= __( 'Please make sure to renew your certificate immediately.', 'ssl-zen' ) . '<br><br>';
                    $message .= __( 'If you want us to automatically renew your SSL certificates, please upgrade to <a href="https://checkout.freemius.com/mode/dialog/plugin/4586/plan/7397/">SSL Zen Pro.</a>', 'ssl-zen' ) . '<br>';
					$message .= __( 'Regards,', 'ssl-zen' ) . '<br>' . get_bloginfo( 'name' );	
				
					wp_mail( get_option( 'ssl_zen_email', '' ), 'Urgent and Important: SSL certificate expired', $message, $headers );

					update_option( 'ssl_zen_certificate_90_days_email_sent', '1' );
				}
			}
		}

		/**
		* Calling init function and activate hooks and filters.
		*/
		ssl_zen_scheduled::init();
	}