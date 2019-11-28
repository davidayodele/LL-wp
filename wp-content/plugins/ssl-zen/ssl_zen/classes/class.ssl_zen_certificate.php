<?php

	require_once( SSL_ZEN_DIR . 'lib/LEFunctions.php' );
	require_once( SSL_ZEN_DIR . 'lib/LEConnector.php' );
	require_once( SSL_ZEN_DIR . 'lib/LEAccount.php' );
	require_once( SSL_ZEN_DIR . 'lib/LEAuthorization.php' );
	require_once( SSL_ZEN_DIR . 'lib/LEClient.php' );
	require_once( SSL_ZEN_DIR . 'lib/LEOrder.php' );

	use LEClient\LEClient;
	use LEClient\LEOrder;

	if( ! class_exists( 'ssl_zen_certificate' ) ) {
		/**
		* Class to manage ssl certificates by interacting with LEClient library
		*/
		class ssl_zen_certificate
		{
			/**
		     * Create client on Let's Encrypt
		     *
		     * @since 1.0
		     * @static
		     * @return LEClient Returns the object of the Let's Encrypt Client
		     */
			public static function createClient() {

				try{
					$email = get_option( 'ssl_zen_email', '' );

					$client = new LEClient( array( $email ), LEClient::LE_PRODUCTION, LEClient::LOG_OFF,  SSL_ZEN_DIR . 'keys' );

					return $client;
				} catch(Exception $e) {
					ssl_zen_certificate::redirect_on_error();
				}
			}

			/**
		     * Generates an order on Let's Encrypt
		     *
		     * @since 1.0
		     * @static
		     * @return LEOrder Returns the object of the Let's Encrypt Order
		     */
			public static function generateOrder() {

				try{
					$baseDomainName = get_option( 'ssl_zen_base_domain', '' );
					$domains = get_option( 'ssl_zen_domains', array() );

					$client = ssl_zen_certificate::createClient();
					$order = $client->getOrCreateOrder( $baseDomainName, $domains );

					return $order;
				} catch(Exception $e) {
					ssl_zen_certificate::redirect_on_error();
				}	
			}

			/**
		     * Checks for all the pending authorizations on Let's Encrypt for an order and 
		     * update the authorization status
		     *
		     * @since 1.0
		     * @static
		     */
			public static function updateAuthorizations() {
				
				try{
					$arrPending = self::getPendingAuthorization();

					if( is_array( $arrPending ) && count( $arrPending ) ) {

						$order = ssl_zen_certificate::generateOrder();

						foreach ( $arrPending as $pending ) {
							$order->verifyPendingOrderAuthorization($pending['identifier'], LEOrder::CHALLENGE_TYPE_HTTP);
						}
					}
				} catch(Exception $e) {
					ssl_zen_certificate::redirect_on_error();
				}
			}

			/**
		     * Check if the authorizations are valid for the particular order
		     *
		     * @since 1.0
		     * @static
		     * @return Boolean Returns the status of domain verification
		     */
			public static function validateAuthorization() {

				try{
					$order = ssl_zen_certificate::generateOrder();
					$status = $order->allAuthorizationsValid();
					
					return $status;
				} catch(Exception $e) {
					ssl_zen_certificate::redirect_on_error();
				}
			}

			/**
		     * Fetches all the pending authorizations for the particular order
		     *
		     * @since 1.0
		     * @static
		     * @return Array Returns all the pending authorizations
		     */
			public static function getPendingAuthorization() {

				try{
					$order = ssl_zen_certificate::generateOrder();
					$pendingAuthorizations = $order->getPendingAuthorizations(LEOrder::CHALLENGE_TYPE_HTTP);

					return $pendingAuthorizations;
				} catch(Exception $e) {
					ssl_zen_certificate::redirect_on_error();
				}
			}

			/**
		     * Finalizes the Let's Encrypt order
		     *
		     * @since 1.0
		     * @static
		     */
			public static function finalizeOrder() {

				try{
					$order = ssl_zen_certificate::generateOrder();
					if(!$order->isFinalized()) $order->finalizeOrder();
				} catch(Exception $e) {
					ssl_zen_certificate::redirect_on_error();
				}
			}

			/**
		     * Generates and returns the path in the form of array for the certificates for a particular order
		     *
		     * @since 1.0
		     * @static
		     * @return Array Paths of the certificates generated for a particular order
		     */
			public static function generateCertificate() {

				try{
					$order = ssl_zen_certificate::generateOrder();
					if($order->isFinalized()) $order->getCertificate();
					$arrCertificates = $order->getAllCertificates();

					return $arrCertificates;
				} catch(Exception $e) {
					ssl_zen_certificate::redirect_on_error();
				}
			}

			/**
		     * Verifies if the SSL certificate is successfully installed on the domain or not.
		     *
		     * @since 1.0
		     * @static
		     * @return Bool True if the SSL certificate is installed successfully, false otherwise.
		     */
			public static function verifyssl( $domain ) {
			    $res = false;
			    $stream = @stream_context_create( array( 'ssl' => array( 'capture_peer_cert' => true ) ) );
			    $socket = @stream_socket_client( 'ssl://' . $domain . ':443', $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $stream );

			    /* If we got a ssl certificate we check here, if the certificate domain */
			    /* matches the website domain. */
			    if ( $socket ) {

			        $cont = stream_context_get_params( $socket );

			        $cert_ressource = $cont['options']['ssl']['peer_certificate'];
			        $cert = openssl_x509_parse( $cert_ressource );

			        /* Expected name has format "/CN=*.yourdomain.com" */
			        $namepart = explode( '=', $cert['name'] );

			        /* We want to correctly confirm the certificate even */
			        /* for subdomains like "www.yourdomain.com" */
			        if ( count( $namepart ) == 2 ) {
			            $cert_domain = trim( $namepart[1], '*. ' );
			            $check_domain = substr( $domain, -strlen( $cert_domain ) );
			            $res = ($cert_domain == $check_domain);
			        }
			    }

			    return $res;
			}

			/**
		     * Redirect user to the page when error is raised in the Lets Encrypt API
		     *
		     * @since 1.1
		     * @static
		     */
			private static function redirect_on_error(){
				$currentSettingTab = get_option( 'ssl_zen_settings_stage', '' );

				if( $currentSettingTab == '' )
					$currentSettingTab = 'step1';

				wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=' . $currentSettingTab . '&info=api_error' ) );
				exit;
			}
		}
	}