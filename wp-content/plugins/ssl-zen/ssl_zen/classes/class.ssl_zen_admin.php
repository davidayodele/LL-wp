<?php

if ( ! class_exists( 'ssl_zen_admin' ) ) {
	/**
	 * Class to manage the admin settings of ssl_zen
	 */
	class ssl_zen_admin {

		/**
         * to manage the allowed tabs after each step
         *
		 * @var $allowedTabs
		 * @since 1.0
		 */
		private static $allowedTabs = array(
			'started'      => array( '', 'started', 'faq', 'support' ),
			'incompatible' => array( '', 'incompatible', 'faq', 'support' ),
			'step1'        => array( '', 'step1', 'faq', 'support' ),
			'step2'        => array( '', 'step1', 'step2', 'faq', 'support' ),
			'step3'        => array( '', 'step1', 'step2', 'step3', 'faq', 'support' ),
			'step4'        => array( '', 'step1', 'step2', 'step3', 'step4', 'settings', 'faq', 'support' ),
			'settings'     => array( '', 'step1', 'step2', 'step3', 'step4', 'settings', 'faq', 'support', 'renew' ),
		);

		/**
		 * Add hooks and filters for admin pages
		 *
		 * @since 1.0
		 * @static
		 */
		public static function init() {
			/* Manage admin menu */
			add_action( 'admin_menu', __CLASS__ . '::admin_menu' );

			add_action( 'admin_init', __CLASS__ . '::admin_init' );

			register_deactivation_hook( SSL_ZEN_BASEFILE, __CLASS__ . '::deactivate_plugin' );

			add_action( 'plugin_action_links_' . SSL_ZEN_BASEFILE, __CLASS__ . '::plugin_action_links' );

		}

		/**
		 * Hook to manage the admin menu
		 *
		 * @since 1.0
		 * @static
		 */
		public static function admin_menu() {
			/* Add SSL Zen page to admin menu */
			global $menu;

			add_menu_page( __( 'SSL Zen', 'ssl-zen' ), __( 'SSL Zen', 'ssl-zen' ), 'manage_options', 'ssl_zen',
				__CLASS__ . '::ssl_zen_hook', 'dashicons-lock', 101 );
		}

		/**
		 * Hook to display SSL Zen Settings page
		 *
		 * @since 1.0
		 * @static
		 */
		public static function ssl_zen_hook() {
			$tab = ( isset( $_REQUEST['tab'] ) ? trim( sanitize_text_field( $_REQUEST['tab'] ) ) : '' );

			?>
            <div class="ssl-zen-content-container">
                <div class="ssl-zen-wrap clearfix">
                    <header class="header clearfix">
                        <div class="logo">
                            <a href="#">
                                <img src="<?php echo SSL_ZEN_URL; ?>img/logo.svg" alt="">
                            </a>
                            <span>v<?php echo SSL_ZEN_PLUGIN_VERSION; ?> Free</span>
                        </div>

                        <ul class="right-nav list-unstyled list-inline">
							<?php

							if ( get_option( 'ssl_zen_ssl_activated', '' ) == '1' ) {
								?>
                                <li class="<?php echo( $tab == 'renew' ? 'active' : '' ); ?>"><a
                                            href="<?php echo admin_url( 'admin.php?page=ssl_zen&tab=renew' ); ?>">Renew</a>
                                </li>
                                <li class="<?php echo( $tab == 'settings' ? 'active' : '' ); ?>"><a
                                            href="<?php echo admin_url( 'admin.php?page=ssl_zen&tab=settings' ); ?>">Settings</a>
                                </li>
								<?php
							}
							?>
                            <li class="<?php echo( $tab == 'faq' ? 'active' : '' ); ?>"><a
                                        href="https://sslzen.com/#faq" target="_blank">FAQ</a></li>
                            <li><a href="mailto:support@sslzen.com">Support</a></li>
                        </ul>
                    </header>
                    <div class='ssl-zen-container'>
						<?php
						/* Fetch the current tab from the url and display the page accordingly */
						switch ( $tab ) {
							case 'started':
								self::started();
								break;
							case 'incompatible':
								self::incompatible();
								break;
							case 'step1':
								self::step1();
								break;

							case 'step2':
								self::step2();
								break;

							case 'step3':
								self::step3();
								break;

							case 'step4':
								self::step4();
								break;

							case 'settings':
								self::settings();
								break;

							case 'faq':
								self::faq();
								break;

							case 'renew':
								self::renew();
								break;

							default:
								self::started();
								break;
						}

						?>
                    </div>
                </div>
            </div>
			<?php
		}

		/**
		 * Function to display Get Started page
		 *
		 * @since 1.8
		 * @static
		 */
		private static function started() {
			?>
            <form name="frmgetstarted" id="frmgetstarted" action="" method="post">
				<?php
				wp_nonce_field( 'ssl_zen_get_started', 'ssl_zen_get_started_nonce' );
				?>
                <section class="ssl-zen-get-st-header">
                    <a href="javascript:void(0)"
                       onclick="jQuery('#frmgetstarted').submit()"><?php _e( 'Use Free Version',
							'ssl-zen' ); ?></a>
                    <a href="<?php echo sz_fs()->get_upgrade_url(); ?>"><?php _e( 'EXPLORE PRO',
							'ssl-zen' ); ?></a>
                </section>
                <div class="ssl-zen-steps-container">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <h3 class="ssl-zen-get-st-title"><?php _e( 'Get a Free SSL Certificate',
										'ssl-zen' ); ?></h3>
                                <p class="ssl-zen-get-st-desc">
									<?php _e( 'Let’s Encrypt<sup>TM</sup> is a nonprofit, whose mission is to create a more secure and privacy-respecting web by promoting the widespread adoption of HTTPS. Their services are free and easy to use so that every website can deploy HTTPS. LetsEncrypt<sup>TM</sup> does not charge a fee for their certificates.',
										'ssl-zen' ); ?>
                                </p>
                            </div>
                        </div>
                        <div class="row ssl-zen-get-st-cells">
                            <div class="col-md-4">
                                <div class="ssl-zen-get-st-cell">
                                    <div>
										<?php _e( 'Domain Validation' ) ?>
                                    </div>
                                    <div class="ssl-zen-domain-validation ssl-zen-get-st-cell-bg">
                                        <div>1</div>
                                    </div>
                                    <p>
										<?php _e( 'Prove your domain ownership by uploading files on your website.',
											'ssl-zen' ); ?>
                                    </p>
                                    <div class="row ssl-free-env-row">
                                        <div class="col-xs-4">
                                            <span class="ssl-zen-env ssl-zen-env-free">FREE</span>
                                        </div>
                                        <div class="col-xs-8" style="padding-left: 0">
                                            <span class="ssl-zen-env-feature">Manual file upload</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <span class="ssl-zen-env ssl-zen-env-pro">PRO</span>
                                        </div>
                                        <div class="col-xs-8" style="padding-left: 0">
                                            <span class="ssl-zen-env-feature">Automatic</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ssl-zen-get-st-cell">
                                    <div>
										<?php _e( 'Certificate Issuance' ) ?>
                                    </div>
                                    <div class="ssl-zen-cert-issue ssl-zen-get-st-cell-bg">
                                        <div>2</div>
                                    </div>
                                    <p>
										<?php _e( 'After domain verification, we will generate a free SSL certificate for you.',
											'ssl-zen' ); ?>
                                    </p>
                                    <div class="row ssl-free-env-row">
                                        <div class="col-xs-4">
                                            <span class="ssl-zen-env ssl-zen-env-free">FREE</span>
                                        </div>
                                        <div class="col-xs-8" style="padding-left: 0">
                                            <span class="ssl-zen-env-feature">Manual SSL installation</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <span class="ssl-zen-env ssl-zen-env-pro">PRO</span>
                                        </div>
                                        <div class="col-xs-8" style="padding-left: 0">
                                            <span class="ssl-zen-env-feature">Automatic</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ssl-zen-get-st-cell">
                                    <div>
										<?php _e( 'SSL Renewal' ) ?>
                                    </div>
                                    <div class="ssl-zen-ssl-renuewal ssl-zen-get-st-cell-bg">
                                        <div>3</div>
                                    </div>
                                    <p>
										<?php _e( 'SSL certificates are only valid for 90 days. Renew them at no additional cost.',
											'ssl-zen' ); ?>
                                    </p>
                                    <div class="row ssl-free-env-row">
                                        <div class="col-xs-4">
                                            <span class="ssl-zen-env ssl-zen-env-free">FREE</span>
                                        </div>
                                        <div class="col-xs-8" style="padding-left: 0">
                                            <span class="ssl-zen-env-feature">Renew every 90 days</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <span class="ssl-zen-env ssl-zen-env-pro">PRO</span>
                                        </div>
                                        <div class="col-xs-8" style="padding-left: 0">
                                            <span class="ssl-zen-env-feature">Automatic</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
			<?php
		}

		/**
		 * Function to display Get Started page
		 *
		 * @since 1.8
		 * @static
		 */
		private static function incompatible() {
			?>
            <form name="frmplincomp" id="frmplincomp" action="" method="post">
				<?php
				//				wp_nonce_field( 'ssl_zen_plugin_incompatible', 'ssl_zen_get_incompatible_nonce' );
				?>
                <section class="ssl-zen-get-st-header">
                    <a href="javascript:void(0)"
                       onclick="jQuery('#frmplincomp').submit();"><?php echo _e( 'Uninstall Plugin', 'ssl-zen' ); ?></a>
                    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3TG48U6YNG43S"
                       target="_blank"><?php echo _e( 'PAY NOW', 'ssl-zen' ); ?></a>
                </section>
                <input type="hidden" class="deactivate-plugin-btn" name="ssl_zen_deactivate_plugin"
                       id="ssl_zen_deactivate_plugin" value="1">
                <div class="ssl-zen-steps-container">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <h3 class="ssl-zen-pl-inc-title"><?php _e( 'Uh-oh! Our plugin is incompatible with your website hosting provider.',
										'ssl-zen' ); ?></h3>
                                <p class="ssl-zen-pl-inc-desc">
									<?php _e( 'Do not lose heart. We can still help you. For a one time fee of $49.99, we will :',
										'ssl-zen' ); ?>
                                </p>
                            </div>
                        </div>
                        <div class="row ssl-zen-get-st-cells with-brd">
                            <div class="col-md-4">
                                <div class="ssl-zen-get-st-cell">
                                    <div>
										<?php _e( 'Domain Validation' ) ?>
                                    </div>
                                    <div class="ssl-zen-domain-validation ssl-zen-get-st-cell-bg">
                                        <div>1</div>
                                    </div>
                                    <p>
										<?php _e( 'Verify your domain ownership with LetsEncrypt.',
											'ssl-zen' ); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ssl-zen-get-st-cell">
                                    <div>
										<?php _e( 'Certificate Issuance' ) ?>
                                    </div>
                                    <div class="ssl-zen-cert-issue ssl-zen-get-st-cell-bg">
                                        <div>2</div>
                                    </div>
                                    <p>
										<?php _e( 'Generate SSL certificate and install it on your web server.',
											'ssl-zen' ); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ssl-zen-get-st-cell">
                                    <div>
										<?php _e( 'SSL Renewal' ) ?>
                                    </div>
                                    <div class="ssl-zen-ssl-renuewal ssl-zen-get-st-cell-bg">
                                        <div>3</div>
                                    </div>
                                    <p>
										<?php _e( 'Add a script that will automatically renew your certificate. You never pay for SSL certificate again!',
											'ssl-zen' ); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <h4 class="ssl-zen-pl-inc-title-bottom"><?php _e( 'How does it work?',
										'ssl-zen' ); ?></h4>
                                <p class="ssl-zen-pl-inc-desc-bottom">
									<?php _e( 'We ask you to schedule a web meeting with us. At the decided time, one of our SSL experts will connect remotely to your computer and complete all the necessary steps to install the SSL certificate on your website.',
										'ssl-zen' ); ?>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <h4 class="ssl-zen-pl-inc-title-bottom"><?php _e( 'Pre-requisites:',
										'ssl-zen' ); ?></h4>
                                <p class="ssl-zen-pl-inc-desc-bottom">
									<?php _e( 'Linux hosting, Minimum PHP 5.4, MySQL or Maria DB Database',
										'ssl-zen' ); ?>
                                </p>
                                <p class="ssl-zen-pl-inc-desc-bottom">
									<?php _e( 'Note: If for any reason we are not able to install a SSL certificate on your website, we will refund you the full amount. ',
										'ssl-zen' ); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
			<?php
		}

		/**
		 * Function to display step 1 for SSL Zen.
		 *
		 * @since 1.0
		 * @static
		 */
		private static function step1() {
			if ( isset( $_REQUEST['info'] ) ) {
				$info = sanitize_text_field( $_REQUEST['info'] );
			}
			?>
            <form name="frmstep1" id="frmstep1" action="" method="post">
				<?php
				/* Display success / failure message */
				if ( isset( $info ) ) {
					$msg = '';

					if ( $info == 'api_error' ) {
						$msg = __( 'LetsEncrypt API Error. Please try again later.', 'ssl-zen' );
					}

					if ( $msg != '' ) {
						?>
                        <div class="error notice is-dismissible">
                            <p><strong><?php echo $msg; ?></strong></p>
                            <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span>
                            </button>
                        </div>
						<?php
					}
				}
				?>
                <section class="controls clearfix">
                    <ul class="progress-list list-unstyled list-inline">
                        <li class="active">
                            <a href="<?php echo admin_url( 'admin.php?page=ssl_zen&tab=step1' ); ?>">
                                <span><i class="fa fa-circle" aria-hidden="true"></i></span>
                            </a>
                            <p><?php _e( 'WEBSITE DETAILS', 'ssl-zen' ); ?></p>
                        </li>
                        <li>
                            <a href="<?php echo admin_url( 'admin.php?page=ssl_zen&tab=step2' ); ?>"
                               title="<?php _e( 'VERIFY DOMAIN OWNERSHIP', 'ssl-zen' ); ?>">
                                <span><i class="fa fa-circle" aria-hidden="true"></i></span>
                            </a>
                            <p><?php _e( 'VERIFY DOMAIN OWNERSHIP', 'ssl-zen' ); ?></p>
                        </li>
                        <li>
                            <a href="<?php echo admin_url( 'admin.php?page=ssl_zen&tab=step3' ); ?>"
                               title="<?php _e( 'INSTALL SSL CERTIFICATE', 'ssl-zen' ); ?>">
                                <span><i class="fa fa-circle" aria-hidden="true"></i></span>
                            </a>
                            <p><?php _e( 'INSTALL SSL CERTIFICATE', 'ssl-zen' ); ?></p>
                        </li>
                        <li class="last-child">
                            <a href="<?php echo admin_url( 'admin.php?page=ssl_zen&tab=step4' ); ?>"
                               title="<?php _e( 'ACTIVATE SSL CERTIFICATE', 'ssl-zen' ); ?>">
                                <span><i class="fa fa-circle" aria-hidden="true"></i></span>
                            </a>
                            <p><?php _e( 'ACTIVATE SSL CERTIFICATE', 'ssl-zen' ); ?></p>
                        </li>
                    </ul>

                    <input type='submit' class='btn btn-primary btnNext' name='generate_ssl' id='generate_ssl'
                           value='<?php _e( 'Next', 'ssl-zen' ); ?>'>
                </section>

				<?php
				wp_nonce_field( 'ssl_zen_generate_certificate', 'ssl_zen_generate_certificate_nonce' );
				?>
                <div class="ssl-zen-steps-container">
                    <img id="pointer" src="<?php echo SSL_ZEN_URL; ?>img/pointer.png" alt="">
                    <div class="container-fluid">
                        <p style="font-weight: 500;"><?php _e( 'Secure your website with a free SSL certificate',
								'' ); ?></p>

                        <div class="media" style="line-height: 1.4;">
                            <div class="media-left">
                                <img class="media-object" src="<?php echo SSL_ZEN_URL; ?>img/lock.svg" alt="encrypt">
                            </div>
                            <div class="media-body" style="color: #2c3c69;">
                                <p><?php _e( 'The SSL certificate for your website will be generated by LetsEncrypt.org, an open certificate authority (CA), run for the public\'s benefit.',
										'' ); ?></p>
                            </div>
                        </div>
                        <div class="row">

                            <div class="form-group clearfix">
                                <div class="col-xs-3">
                                    <div class="tab-1-label">
										<?php _e( 'Domain Details', 'ssl-zen' ); ?>
                                    </div>
                                </div>
                                <div class="col-xs-9">
                                    <main>
                                        <label for="domaiAdress"><?php _e( 'Domain Address', 'ssl-zen' ); ?></label>
                                        <br>
                                        <span class='text'>
												<?php
												$arrHosts = array();
												$urlInfo  = parse_url( get_site_url() );
												$host     = ( isset( $urlInfo['host'] ) ? $urlInfo['host'] : '' );

												$arrHosts[] = $host;
												$hasWww     = false;

												if ( strpos( $host, 'www.' ) === 0 ) {
													$host   = substr( $host, 4 );
													$hasWww = true;
												}

												echo $host;
												?>
											</span>
                                        <input type='hidden' name='base_domain_name' id='base_domain_name'
                                               value='<?php echo $host; ?>'>

                                        <div class="checkbox checkbox-success checkbox-circle"
                                             style="color: #999999; font-size: 18px;">
											<?php
											if ( $hasWww ) {
												?>
                                                <input type="hidden" name="include_www" id="include_www" value='1'>
												<?php
											}
											?>

                                            <input type='checkbox' class="styled" name='include_www' id='include_www'
                                                   value='1' <?php echo( $hasWww ? 'checked="checked"' : ( get_option( 'ssl_zen_include_wwww',
												'' ) == '1' ? 'checked="checked"' : '' ) ); ?> <?php echo( $hasWww ? 'disabled="disabled"' : '' ); ?> >
                                            <label for="include_www">
												<?php _e( 'Include www-prefixed version too?', 'ssl-zen' ); ?> &nbsp;
                                                <a href="#" style="position: relative; z-index: 999;"
                                                   data-toggle="tooltip"
                                                   title="<?php _e( 'By default, we generate SSL certificate only for domain.com. If user enters www.domain.com your website will show a not secure warning. Check this box to create a certificate for www.domain.com too. Make sure you have a CNAME record added for www in your domain panel.',
													   'ssl-zen' ); ?>"><img
                                                            src="<?php echo SSL_ZEN_URL; ?>img/question.svg" alt=""></a>
                                            </label>
                                        </div>
                                    </main>
                                </div>
                            </div>

                            <div class="form-group clearfix">
                                <div class="col-xs-3">
                                    <div class="tab-1-label tab-1-label2">
										<?php _e( 'Contact Details', 'ssl-zen' ); ?> &nbsp;
                                        <a href="#" style="position: relative; z-index: 999;" data-toggle="tooltip"
                                           title="<?php _e( 'Your SSL certificates, renewal notifications will be sent to this email. Make sure this is a valid email and you can access it.',
											   'ssl-zen' ); ?>"><img
                                                    src="<?php echo SSL_ZEN_URL; ?>img/question.svg" alt=""></a>
                                    </div>
                                </div>
                                <div class="col-xs-9">
                                    <main>
                                        <label for="email"><?php _e( 'Email Address', 'ssl-zen' ); ?></label> <br>
                                        <input type="email" name="email" id="email"
                                               placeholder="<?php _e( 'Enter your email address', 'ssl-zen' ); ?>"
                                               value='<?php echo get_option( 'ssl_zen_email',
											       get_option( 'admin_email' ) ); ?>'
                                               required>
                                    </main>
                                </div>
                            </div>

                            <div class="form-group clearfix">
                                <div class="col-xs-3">
                                </div>
                                <div class="col-xs-9">
                                    <main>
                                        <div class="checkbox checkbox-success checkbox-circle terms-checkbox">
                                            <input type='checkbox' class="styled" name='terms' id='terms' value='1'
                                                   required>
                                            <label for="terms">
												<?php _e( 'I agree to <a href="https://sslzen.com/terms-of-service/" target="_blank">Terms and Conditions</a>',
													'ssl-zen' ) ?>
                                            </label>
                                        </div>
                                    </main>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
			<?php
		}

		/**
		 * Function to display step 2 for SSL Zen.
		 *
		 * @since 1.0
		 * @static
		 */
		private static function step2() {
			if ( isset( $_REQUEST['info'] ) ) {
				$info = sanitize_text_field( $_REQUEST['info'] );
			}
			?>
            <form name='frmstep2' id='frmstep2' action='' method='post'>

				<?php
				/* Display success / failure message */
				if ( isset( $info ) ) {
					$msg = '';

					switch ( $info ) {
						case 'dlerr':
							$msg = __( 'Error downloading domain verification file. Upgrade to our Pro version to automatically verify domain ownership.',
								'ssl-zen' );
							break;

						case 'invalid':
							$msg = __( 'Verifying domain ownership failed. Please check that the files listed below are uploaded to "web-root/.well-known/acme-challenge/" directory (web-root can be public_html or www or htdocs). Upgrade to our Pro version to automatically verify domain ownership.',
								'ssl-zen' );
							break;
						case 'api_error':
							$msg = __( 'LetsEncrypt API Error. Please try again later.', 'ssl-zen' );
							break;
					}

					if ( $msg != '' ) {
						?>
                        <div class="error notice is-dismissible">
                            <p><strong><?php echo $msg; ?></strong></p>
                            <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span>
                            </button>
                        </div>
						<?php
					}
				}
				?>

				<?php

				/* Fetch all pending authorizations */
				$arrPending       = ssl_zen_certificate::getPendingAuthorization();
				$showVerifyButton = ( ( is_array( $arrPending ) && count( $arrPending ) > 0 ) ? true : false );
				?>

                <section class="controls clearfix">
                    <ul class="progress-list list-unstyled list-inline">
                        <li class="completed">
                            <a href="<?php echo admin_url( 'admin.php?page=ssl_zen&tab=step1' ); ?>"
                               title="<?php _e( 'WEBSITE DETAILS', 'ssl-zen' ); ?>">
                                <span><i class="fa fa-check-circle" aria-hidden="true"></i></span>
                            </a>
                            <p><?php _e( 'WEBSITE DETAILS', 'ssl-zen' ); ?></p>
                        </li>
                        <li class="active">
                            <a href="<?php echo admin_url( 'admin.php?page=ssl_zen&tab=step2' ); ?>">
                                <span><i class="fa fa-circle" aria-hidden="true"></i></span>
                            </a>
                            <p><?php _e( 'VERIFY DOMAIN OWNERSHIP', 'ssl-zen' ); ?></p>
                        </li>
                        <li>
                            <a href="<?php echo admin_url( 'admin.php?page=ssl_zen&tab=step3' ); ?>"
                               title="<?php _e( 'INSTALL SSL CERTIFICATE', 'ssl-zen' ); ?>">
                                <span><i class="fa fa-circle" aria-hidden="true"></i></span>
                            </a>
                            <p><?php _e( 'INSTALL SSL CERTIFICATE', 'ssl-zen' ); ?></p>
                        </li>
                        <li class="last-child">
                            <a href="<?php echo admin_url( 'admin.php?page=ssl_zen&tab=step4' ); ?>"
                               title="<?php _e( 'ACTIVATE SSL CERTIFICATE', 'ssl-zen' ); ?>">
                                <span><i class="fa fa-circle" aria-hidden="true"></i></span>
                            </a>
                            <p><?php _e( 'ACTIVATE SSL CERTIFICATE', 'ssl-zen' ); ?></p>
                        </li>
                    </ul>

					<?php
					if ( $showVerifyButton ) {
						?>
                        <input type='submit' class='btn btn-primary btnNext' name='ssl_zen_verify' id='ssl_zen_verify'
                               value='<?php _e( 'Next', 'ssl-zen' ); ?>'>
						<?php
					} else {
						?>
                        <a href="<?php echo admin_url( 'admin.php?page=ssl_zen&tab=step3' ); ?>"
                           class='btn btn-primary btnNext'><?php _e( 'Next', 'ssl-zen' ); ?></a>
						<?php
					}
					?>

                </section>

				<?php
				wp_nonce_field( 'ssl_zen_verify', 'ssl_zen_verify_nonce' );
				?>
                <div class="ssl-zen-steps-container">
                    <img id="pointer" src="<?php echo SSL_ZEN_URL; ?>img/pointer.png" alt="">
                    <div class="container-fluid">
						<?php
						/* Display the files for download */

						if ( $showVerifyButton ) {

							$urlInfo = parse_url( get_site_url() );
							$host    = ( isset( $urlInfo['scheme'] ) ? $urlInfo['scheme'] . '://' : '' ) . ( isset( $urlInfo['host'] ) ? $urlInfo['host'] : '' );
							?>
                            <p>
                                <b><?php _e( 'Prove that you own the domain for which you are requesting the certificate.',
										'ssl-zen' ); ?></b>
                            </p>

                            <ul class="list-unstyled prove-domain-list">
                                <li>
                                    <span><?php _e( 'Step 1', 'ssl-zen' ); ?></span> <a class="btn video-guide-btn"
                                                                                        href="https://sslzen.fleeq.io/l/z5aoukrmpb-pzasu8e8sq"
                                                                                        target="_blank"><img
                                                src="<?php echo SSL_ZEN_URL; ?>img/video.svg" alt="">
                                        <span><?php _e( 'Video Guide', 'ssl-zen' ); ?></span></a>
                                    <p><?php _e( 'Create an new folder <b>\'.well-known/acme-challenge\'</b> in <b>public_html or htdocs or www',
											'ssl-zen' ); ?></b></p>
                                </li>

                                <li>
                                    <span><?php _e( 'Step 2', 'ssl-zen' ); ?></span> <a class="btn video-guide-btn"
                                                                                        href="https://sslzen.fleeq.io/l/jtmokuzvuf-8fekixz626"
                                                                                        target="_blank"><img
                                                src="<?php echo SSL_ZEN_URL; ?>img/video.svg" alt="">
                                        <span><?php _e( 'Video Guide', 'ssl-zen' ); ?></span></a>
                                    <p><?php _e( 'Download the file(s) below on your local computer and upload them in <b>\'acme-challenge\'</b> folder',
											'ssl-zen' ); ?></p>
                                </li>
                            </ul>

                            <div class="prove-domain-buttons">
								<?php
								$fileCounter = 1;
								foreach ( $arrPending as $index => $pending ) {
									$fileName = ( isset( $pending['filename'] ) ? $pending['filename'] : '' );
									?>
                                    <div class="file-1-download">
                                        <a href="<?php echo admin_url( 'admin.php?page=ssl_zen&tab=step2&download=' . $index ); ?>"
                                           class="btn dl-file-btn">
                                            <img class="normal" src="<?php echo SSL_ZEN_URL; ?>img/download-o.svg"
                                                 alt="">
                                            <img class="hover" src="<?php echo SSL_ZEN_URL; ?>img/download-white.svg"
                                                 alt="">
											<?php echo __( 'Download File', 'ssl-zen' ) . $fileCounter; ?>
                                        </a>
                                        <a href="<?php echo $host . '/.well-known/acme-challenge/' . $fileName; ?>"
                                           class="btn verify-btn" target="_blank">
                                            <img class="normal" src="<?php echo SSL_ZEN_URL; ?>img/verify-o.svg" alt="">
                                            <img class="hover" src="<?php echo SSL_ZEN_URL; ?>img/verify-white.svg"
                                                 alt="">
											<?php _e( 'Verify', 'ssl-zen' ); ?>
                                        </a>
                                    </div>
									<?php
									$fileCounter ++;
								}
								?>
                            </div>
							<?php
						} else {
							?>
                            <p><?php _e( 'You have proved your domain ownership.', 'ssl-zen' ); ?></p>
                            <img src="<?php echo SSL_ZEN_URL; ?>img/already-verified.svg">
							<?php
						}
						?>
                    </div>
                </div>
            </form>
			<?php
		}

		/**
		 * Function to display step 3 for SSL Zen.
		 *
		 * @since 1.0
		 * @static
		 */
		private static function step3() {
			if ( isset( $_REQUEST['info'] ) ) {
				$info = sanitize_text_field( $_REQUEST['info'] );
			}
			?>
            <form name='frmstep3' id='frmstep3' action='' method='post'>

				<?php
				/* Display success / failure message */
				if ( isset( $info ) ) {
					$msg = '';

					switch ( $info ) {
						case 'error':
							$msg = __( 'Error: SSL Certificate is not installed correctly. Please upgrade to Pro version to automatically install SSL certificate.',
								'ssl-zen' );
							break;
						case 'api_error':
							$msg = __( 'LetsEncrypt API Error. Please try again later.', 'ssl-zen' );
							break;
					}

					if ( $msg != '' ) {
						?>
                        <div class="error notice is-dismissible">
                            <p><strong><?php echo $msg; ?></strong></p>
                            <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span>
                            </button>
                        </div>
						<?php
					}
				}
				?>

                <section class="controls clearfix">
                    <ul class="progress-list list-unstyled list-inline">
                        <li class="completed">
                            <a href="<?php echo admin_url( 'admin.php?page=ssl_zen&tab=step1' ); ?>"
                               title="<?php _e( 'WEBSITE DETAILS', 'ssl-zen' ); ?>">
                                <span><i class="fa fa-check-circle" aria-hidden="true"></i></span>
                            </a>
                            <p><?php _e( 'WEBSITE DETAILS', 'ssl-zen' ); ?></p>
                        </li>
                        <li class="completed">
                            <a href="<?php echo admin_url( 'admin.php?page=ssl_zen&tab=step2' ); ?>"
                               title="<?php _e( 'VERIFY DOMAIN OWNERSHIP', 'ssl-zen' ); ?>">
                                <span><i class="fa fa-check-circle" aria-hidden="true"></i></span>
                            </a>
                            <p><?php _e( 'VERIFY DOMAIN OWNERSHIP', 'ssl-zen' ); ?></p>
                        </li>
                        <li class="active">
                            <a href="<?php echo admin_url( 'admin.php?page=ssl_zen&tab=step3' ); ?>">
                                <span><i class="fa fa-circle" aria-hidden="true"></i></span>
                            </a>
                            <p><?php _e( 'INSTALL SSL CERTIFICATE', 'ssl-zen' ); ?></p>
                        </li>
                        <li class="last-child">
                            <a href="<?php echo admin_url( 'admin.php?page=ssl_zen&tab=step4' ); ?>"
                               title="<?php _e( 'ACTIVATE SSL CERTIFICATE', 'ssl-zen' ); ?>">
                                <span><i class="fa fa-circle" aria-hidden="true"></i></span>
                            </a>
                            <p><?php _e( 'ACTIVATE SSL CERTIFICATE', 'ssl-zen' ); ?></p>
                        </li>
                    </ul>

                    <input type='submit' class='btn btn-primary btnNext' name='ssl_zen_install_certificate'
                           id='ssl_zen_install_certificate' value='<?php _e( 'Next', 'ssl-zen' ); ?>'>
                </section>

				<?php
				wp_nonce_field( 'ssl_zen_install_certificate', 'ssl_zen_install_certificate_nonce' );
				?>

                <div class="ssl-zen-steps-container">
                    <img id="pointer" src="<?php echo SSL_ZEN_URL; ?>img/pointer.png" alt="">

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="media success-box">
                                    <div class="media-left">
                                        <img class="media-object" src="<?php echo SSL_ZEN_URL; ?>img/shield.svg"
                                             alt="Shield">
                                    </div>
                                    <div class="media-body">
                                        <p><?php _e( 'Congratulations on receiving your Free SSL Certificate.',
												'ssl-zen' ); ?>
                                            <br>
											<?php _e( 'We have emailed the SSL certificate to your registered email address. Please allow upto 15 mins for the email to arrive.',
												'ssl-zen' ); ?>
                                            <!-- Email sending failed? Ask user to download from website -->
                                            <br>
                                            <span><?php _e( 'Did not receive email? You can download the SSL certificate from your website. They are stored at wp-content/plugins/ssl-zen/ssl_zen/keys/',
													'ssl-zen' ); ?></span>
                                        </p>
                                    </div>
                                </div>  <!-- end success box -->

                                <div class="step-3-top-area">
                                    <h4><?php _e( 'Install SSL Certificate on your web server', 'ssl-zen' ); ?></h4>
                                    <p><?php _e( 'Check out the video guide or alternatively, follow our step by step guide below.',
											'ssl-zen' ); ?></p>

                                    <a href="https://sslzen.fleeq.io/l/nv98k8c8uu-g6xfn3dp9n" target="_blank"
                                       class="btn video-guide-btn"><img src="<?php echo SSL_ZEN_URL; ?>img/video.svg"
                                                                        alt="Video">
                                        <span><?php _e( 'Video Guide', 'ssl-zen' ); ?></span></a>
                                </div>

                                <!-- step by step guide. start of nested tab -->


                                <div id="sub-step-tab" role="tabpanel">
                                    <h3><?php _e( 'Step by step guide', 'ssl-zen' ); ?></h3>
                                    <!-- Nav tabs -->
                                    <ul class="sub-step-nav list-unstyled" role="tablist">
                                        <li role="presentation" class="active">
                                            <a href="#sub-step-1" aria-controls="sub-step-1" role="tab"
                                               data-toggle="tab"><?php _e( 'STEP 1', 'ssl-zen' ); ?></a>
                                        </li>

                                        <li role="presentation" class="">
                                            <a href="#sub-step-2" aria-controls="sub-step-2" role="tab"
                                               data-toggle="tab"><?php _e( 'STEP 2', 'ssl-zen' ); ?></a>
                                        </li>

                                        <li role="presentation" class="">
                                            <a href="#sub-step-3" aria-controls="sub-step-3" role="tab"
                                               data-toggle="tab"><?php _e( 'STEP 3', 'ssl-zen' ); ?></a>
                                        </li>

                                        <li role="presentation" class="">
                                            <a href="#sub-step-4" aria-controls="sub-step-4" role="tab"
                                               data-toggle="tab"><?php _e( 'STEP 4', 'ssl-zen' ); ?></a>
                                        </li>
                                    </ul>

                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active" id="sub-step-1">
                                            <ul class="list-unstyled sub-step-list">
                                                <li><img src="<?php echo SSL_ZEN_URL; ?>img/check-list.svg"
                                                         alt="Bullet Icon"> <?php _e( 'Log into your cPanel account',
														'ssl-zen' ); ?>
                                                </li>

                                                <li><img src="<?php echo SSL_ZEN_URL; ?>img/check-list.svg"
                                                         alt="Bullet Icon"> <?php _e( 'Locate and click on SSL/TLS Manager in the Security section',
														'ssl-zen' ); ?>
                                                </li>

                                                <li><img src="<?php echo SSL_ZEN_URL; ?>img/check-list.svg"
                                                         alt="Bullet Icon"> <?php _e( 'Click on "Manage SSL Sites" under the Install and Manage SSL',
														'ssl-zen' ); ?>
                                                </li>
                                            </ul>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="sub-step-2">
                                            <ul class="list-unstyled sub-step-list">
                                                <li>
                                                    <img src="<?php echo SSL_ZEN_URL; ?>img/check-list.svg"
                                                         alt="Bullet Icon">
													<?php _e( 'Copy the certificate code from certificate.txt sent to your email including -----BEGIN CERTIFICATE----- and -----END CERTIFICATE----- and paste it into the “Certificate: (CRT)” field',
														'ssl-zen' ); ?>
                                                </li>
                                            </ul>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="sub-step-3">
                                            <ul class="list-unstyled sub-step-list">
                                                <li>
                                                    <img src="<?php echo SSL_ZEN_URL; ?>img/check-list.svg"
                                                         alt="Bullet Icon">
													<?php _e( 'Copy the private key code from privatekey.txt including -----BEGIN PRIVATE KEY----- and -----END PRIVATE KEY----- and paste it into the “Private Key: (KEY)” field',
														'ssl-zen' ); ?>
                                                </li>
                                            </ul>
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="sub-step-4">
                                            <ul class="list-unstyled sub-step-list">
                                                <li>
                                                    <img src="<?php echo SSL_ZEN_URL; ?>img/check-list.svg"
                                                         alt="Bullet Icon">
													<?php _e( 'Copy the certificate authority bundle code from cabundle.txt including -----BEGIN CERTIFICATE----- and -----END CERTIFICATE----- and paste it into the “Certificate Authority Bundle: (CABUNDLE)” field',
														'ssl-zen' ); ?>
                                                </li>

                                                <li>
                                                    <img src="<?php echo SSL_ZEN_URL; ?>img/check-list.svg"
                                                         alt="Bullet Icon">
													<?php _e( 'Click on the “Install Certificate” button',
														'ssl-zen' ); ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-3">
                                <div class="step-3-note">

                                    <h4><?php _e( 'Note', 'ssl-zen' ); ?></h4>

									<?php _e( 'SSL certificates from LetsEncrypt are valid for 90 days but they are free to renew. ',
										'ssl-zen' ); ?>

                                    <br><br>

									<?php _e( 'You can renew your certificate by following the same steps you followed to generate your SSL certificate. ',
										'ssl-zen' ); ?>

                                    <br><br>

									<?php _e( 'We will send you a reminder 30 days before your certificate expires. Upgrade to pro version to automatically renew your SSL certificate',
										'ssl-zen' ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

			<?php
		}

		/**
		 * Function to display step 4 for SSL Zen.
		 *
		 * @since 1.0
		 * @static
		 */
		private static function step4() {
			if ( isset( $_REQUEST['info'] ) ) {
				$info = sanitize_text_field( $_REQUEST['info'] );
			}
			?>

            <form name="frmstep4" id="frmstep4" action="" method="post">

				<?php

				/* Display success / failure message */
				if ( isset( $info ) ) {
					$msg = '';

					switch ( $info ) {
						case 'error':
							$msg = __( 'Error: SSL Certificate is not installed correctly. Upgrade to Pro version to automatically install the SSL certificate.',
								'ssl-zen' );
							break;
						case 'api_error':
							$msg = __( 'LetsEncrypt API Error. Please try again later.', 'ssl-zen' );
							break;
					}

					if ( $msg != '' ) {
						?>
                        <div class="error notice is-dismissible">
                            <p><strong><?php echo $msg; ?></strong></p>
                            <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span>
                            </button>
                        </div>
						<?php
					}
				}
				?>

                <section class="controls clearfix">
                    <ul class="progress-list list-unstyled list-inline">
                        <li class="completed">
                            <a href="<?php echo admin_url( 'admin.php?page=ssl_zen&tab=step1' ); ?>"
                               title="<?php _e( 'WEBSITE DETAILS', 'ssl-zen' ); ?>">
                                <span><i class="fa fa-check-circle" aria-hidden="true"></i></span>
                            </a>
                            <p><?php _e( 'WEBSITE DETAILS', 'ssl-zen' ); ?></p>
                        </li>
                        <li class="completed">
                            <a href="<?php echo admin_url( 'admin.php?page=ssl_zen&tab=step2' ); ?>"
                               title="<?php _e( 'VERIFY DOMAIN OWNERSHIP', 'ssl-zen' ); ?>">
                                <span><i class="fa fa-check-circle" aria-hidden="true"></i></span>
                            </a>
                            <p><?php _e( 'VERIFY DOMAIN OWNERSHIP', 'ssl-zen' ); ?></p>
                        </li>
                        <li class="completed">
                            <a href="<?php echo admin_url( 'admin.php?page=ssl_zen&tab=step3' ); ?>"
                               title="<?php _e( 'INSTALL SSL CERTIFICATE', 'ssl-zen' ); ?>">
                                <span><i class="fa fa-check-circle" aria-hidden="true"></i></span>
                            </a>
                            <p><?php _e( 'INSTALL SSL CERTIFICATE', 'ssl-zen' ); ?></p>
                        </li>
                        <li class="active last-child">
                            <a href="<?php echo admin_url( 'admin.php?page=ssl_zen&tab=step4' ); ?>">
                                <span><i class="fa fa-circle" aria-hidden="true"></i></span>
                            </a>
                            <p><?php _e( 'ACTIVATE SSL CERTIFICATE', 'ssl-zen' ); ?></p>
                        </li>
                    </ul>

                    <input type='submit' class='btn btn-primary btnNext' name='ssl_zen_activate_ssl'
                           id='ssl_zen_activate_ssl' value='<?php _e( 'Next', 'ssl-zen' ); ?>'>
                </section>

				<?php
				wp_nonce_field( 'ssl_zen_activate_ssl', 'ssl_zen_activate_ssl_nonce' );
				?>

                <div class="ssl-zen-steps-container">
                    <img id="pointer" src="<?php echo SSL_ZEN_URL; ?>img/pointer.png" alt="">

                    <div class="container-fluid">

                        <div class="col-md-9">

                            <h4 style="line-height: 1.4;">
                                <b><?php _e( 'To start serving your wordpress website over SSL, we need to do the following -',
										'ssl-zen' ); ?></b>
                            </h4>

                            <br>

                            <ul class="list-unstyled sub-step-list step-4-list">
                                <li><img src="<?php echo SSL_ZEN_URL; ?>img/check-list.svg"
                                         alt="Bullet Icon"> <?php _e( 'Redirect all incoming http requests to https',
										'ssl-zen' ); ?>
                                </li>

                                <li><img src="<?php echo SSL_ZEN_URL; ?>img/check-list.svg"
                                         alt="Bullet Icon"> <?php _e( 'Change your site URL and Home URL to https',
										'ssl-zen' ); ?>
                                </li>

                                <li><img src="<?php echo SSL_ZEN_URL; ?>img/check-list.svg"
                                         alt="Bullet Icon"> <?php _e( 'Fix insecure content warning by replacing http url\'s to https url\'s',
										'ssl-zen' ); ?>
                                </li>
                            </ul>

                            <div class="cloudflare-area">
                                <img src="<?php echo SSL_ZEN_URL; ?>img/cf-logo.svg" alt="">

                                <br>
                                <div class="cf-checkbox-toggle">
                                    <label class="checkbox-inline">
                                        <input name="fix_cloudflare" id="fix_cloudflare" type="checkbox"
                                               data-toggle="toggle" data-on="Yes" data-off="No" data-style="success"
                                               value='0' <?php echo( in_array( get_option( 'ssl_zen_fix_cloudflare',
											'' ), array( '1' ) ) ? 'checked="checked"' : '' ); ?>>
                                        <span><?php _e( 'Are you using CloudFlare for your website?',
												'ssl-zen' ); ?></span>
                                    </label>
                                </div>

                                <h5><?php _e( 'Sometimes CloudFlare can create an infinite loop after activating the SSL certificate. Do you want us to fix this automatically?',
										'ssl-zen' ); ?></h5>
                            </div>

                        </div>


                        <div class="col-md-3">
                            <div class="step-3-note">

                                <h4><?php _e( 'Note', 'ssl-zen' ); ?></h4>

								<?php _e( 'Remember to clear your browser cache after SSL is activated on your website.',
									'ssl-zen' ); ?>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
			<?php
		}

		/**
		 * Function to display manage settings for SSL Zen.
		 *
		 * @since 1.0
		 * @static
		 */
		private static function settings() {
			if ( isset( $_REQUEST['info'] ) ) {
				$info = sanitize_text_field( $_REQUEST['info'] );
			}
			?>
			<?php

			/* Display success / failure message */
			if ( isset( $info ) ) {
				$msg   = '';
				$class = 'error';

				switch ( $info ) {
					case 'writeerr':
						$msg = __( '.htaccess file not writable. Settings saved successfully.', 'ssl-zen' );
						if ( get_option( 'ssl_zen_enable_301_htaccess_redirect', '' ) == '1' ) {
							$msg .= '<br>' . __( 'Manually paste the following lines of code to your .htaccess files or make the file writable.',
									'ssl-zen' );

							$rules       = self::get_htaccess_rules();
							$arr_search  = array( "<", ">", "\n" );
							$arr_replace = array( "&lt", "&gt", "<br>" );
							$rules       = str_replace( $arr_search, $arr_replace, $rules );

							$msg .= '<code>' . $rules . '</code>';
						}
						break;

					case 'lock':
						$msg = __( '.htaccess file is lock, could not write .htaccess file. Settings saved successfully.',
							'ssl-zen' );
						break;

					case 'renewlater':
						$msg = sprintf( __( 'You can renew your certificate after %s date.', 'ssl-zen' ),
							get_option( 'ssl_zen_certificate_60_days', '' ) );
						break;

					case 'success':
						$msg   = __( 'Settings saved successfully.', 'ssl-zen' );
						$class = 'updated';
						break;

					case 'activationsuccess':
						$msg   = __( 'SSL activated successfully.', 'ssl-zen' );
						$class = 'updated';
						break;
					case 'api_error':
						$msg = __( 'LetsEncrypt API Error. Please try again later.', 'ssl-zen' );
						break;
				}

				if ( $msg != '' ) {
					?>
                    <div class="<?php echo $class; ?> notice is-dismissible">
                        <p><strong><?php echo $msg; ?></strong></p>
                        <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span>
                        </button>
                    </div>
					<?php
				}
			}
			?>

            <div class="subpage-content">
                <form name="frmstepsettings" id="frmstepsettings" action="" method="post">

					<?php
					wp_nonce_field( 'ssl_zen_settings', 'ssl_zen_settings_nonce' );
					?>

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <ul class="list-unstyled settings-list">
                                    <li>
                                        <div class="checkbox">
                                            <input type='checkbox' class="styled" name='enable_301_htaccess_redirect'
                                                   id='enable_301_htaccess_redirect'
                                                   value='1' <?php echo( ( get_option( 'ssl_zen_enable_301_htaccess_redirect',
													'' ) == '1' ) ? 'checked="checked"' : '' ); ?> >
                                            <label for="enable_301_htaccess_redirect">
												<?php _e( 'Enable 301 htaccess redirect.', 'ssl-zen' ); ?>
                                            </label>
                                        </div>

                                        <h4><?php _e( 'Speeds up your website but might also cause a redirect loop and lock you out of your website.',
												'ssl-zen' ); ?></h4>
                                    </li>

                                    <li>
                                        <div class="checkbox">
                                            <input type='checkbox' class="styled" name='lock_htaccess_file'
                                                   id='lock_htaccess_file'
                                                   value='1' <?php echo( ( get_option( 'ssl_zen_lock_htaccess_file',
													'' ) == '1' ) ? 'checked="checked"' : '' ); ?> >
                                            <label for="lock_htaccess_file">
												<?php _e( 'Lock down .htaccess file.', 'ssl-zen' ); ?>
                                            </label>
                                        </div>

                                        <h4><?php _e( 'Disables the plugin from making changes so you can edit the file manually.',
												'ssl-zen' ); ?></h4>
                                    </li>
                                </ul>

                                <input type='submit' class="btn renew-certificate-btn" name='ssl_zen_settings'
                                       id='ssl_zen_settings' value='<?php _e( 'SAVE CHANGES', 'ssl-zen' ); ?>'>
                            </div>

                            <div class="col-sm-6">
                                <div class="buy-me-coffee">
                                    <h4><?php _e( 'A ridiculous amount of coffee was consumed in the process of building this project. Add some fuel if you want me to keep going.',
											'ssl-zen' ); ?></h4>

                                    <img src="<?php echo SSL_ZEN_URL; ?>img/coffee.svg" alt="">

                                    <br>
                                    <a href="https://www.paypal.me/sagarspatil" target="_blank"
                                       class="buy-coffee-btn"><img src="<?php echo SSL_ZEN_URL; ?>img/buy-coffee.svg"
                                                                   alt=""></a>
                                </div>

                                <div class="deactivate-plugin-area">
                                    <input type="submit" class="deactivate-plugin-btn" name="ssl_zen_deactivate_plugin"
                                           id="ssl_zen_deactivate_plugin"
                                           value="<?php _e( 'DEACTIVATE PLUGIN & KEEP HTTPS', 'ssl-zen' ); ?>">
                                    <h4><?php _e( 'You will be unable to renew your certificate if you uninstall this plugin.',
											'ssl-zen' ); ?></h4>
                                </div>
                            </div>


                        </div>
                    </div>
                </form>
            </div>
			<?php
		}

		/**
		 * Function to display FAQ for SSL Zen.
		 *
		 * @since 1.0
		 * @static
		 */
		private static function faq() {
			?>
            <div class="subpage-content">
                <div class='ssl-zen-faq'>
                    <label><?php _e( 'What a SSL Certificate?', 'ssl-zen' ); ?></label>
                    <p><?php _e( 'An SSL (Secure Sockets Layer) certificate is a digital certificate that authenticates the identity of a website and encrypts information sent to the server using SSL technology.',
							'ssl-zen' ); ?></p>
                </div>
                <div class='ssl-zen-faq'>
                    <label><?php _e( 'Do you support Wildcard SSL?', 'ssl-zen' ); ?></label>
                    <p><?php _e( 'Only supported in pro plugin, with a Wildcard SSL you can secure all your subdomains such as mail.example.com, blog.example.com, and others.',
							'ssl-zen' ); ?></p>
                </div>
                <div class='ssl-zen-faq'>
                    <label><?php _e( 'What benefits does SSL provide?', 'ssl-zen' ); ?></label>
                    <p><?php _e( 'An SSL Cert protects your customers’ sensitive information such as their name, address, password, or credit card number by encrypting the data during transmission from their computer to your web server.',
							'ssl-zen' ); ?></p>
                </div>
                <div class='ssl-zen-faq'>
                    <label><?php _e( 'How do I install my SSL certificate?', 'ssl-zen' ); ?></label>
                    <p><?php _e( 'You will be emailed a certificate and a private key. Simply go to your cPanel dashboard, click on SSL/TLS and follow the process to install SSL certificate.',
							'ssl-zen' ); ?></p>
                </div>
                <div class='ssl-zen-faq'>
                    <label><?php _e( 'How long are the certificates valid?', 'ssl-zen' ); ?></label>
                    <p><?php _e( 'Lets Encrypt certificates are valid for 90 days. You can use our plugin to manually renew them or buy our Pro plugin to automatically renew your certificates.',
							'ssl-zen' ); ?></p>
                </div>
                <div class='ssl-zen-faq'>
                    <label><?php _e( 'How do I renew an SSL certificate?', 'ssl-zen' ); ?></label>
                    <p><?php _e( 'Simply follow the same process you used to generate and install SSL certificate the first time and your certificates will be renewed.',
							'ssl-zen' ); ?></p>
                </div>
                <div class='ssl-zen-faq'>
                    <label><?php _e( 'Are your SSL certificates really free?', 'ssl-zen' ); ?></label>
                    <p><?php _e( 'Let’s Encrypt<sup>TM</sup> is a nonprofit, with a mission to create a more secure and privacy-respecting web by promoting the widespread adoption of HTTPS. Let’s Encrypt<sup>TM</sup> does not charge a fee for their certificates.',
							'ssl-zen' ); ?></p>
                </div>
                <div class='ssl-zen-faq'>
                    <label><?php _e( 'Do I need technical knowledge to set up an SSL?', 'ssl-zen' ); ?></label>
                    <p><?php _e( 'Our free plugin does require you to login using FTP and upload files to verify domain ownership. You can buy our pro plugin and let us handle the complex stuff.',
							'ssl-zen' ); ?></p>
                </div>
                <div class='ssl-zen-faq'>
                    <label><?php _e( 'Is your plugin safe to install on my website?', 'ssl-zen' ); ?></label>
                    <p><?php _e( 'Our plugin (free version) is open-source and anyone can inspect it here before installing on their website.',
							'ssl-zen' ); ?></p>
                </div>
            </div>

			<?php
		}

		/**
		 * Function to display Renew page for SSL Zen.
		 *
		 * @since 1.0
		 * @static
		 */
		private static function renew() {
			if ( get_option( 'ssl_zen_ssl_activated', '' ) == '1' ) {
				?>
                <div class="subpage-content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="ssl-activated-notice">
                                    <img src="<?php echo SSL_ZEN_URL; ?>img/success.svg" alt="">
                                    <p><?php _e( 'SSL Certificate successfully activated', 'ssl-zen' ); ?></p>
                                </div>

                                <ul class="list-unstyled site-details-list">
                                    <li>
                                        <h4><?php _e( 'Website', 'ssl-zen' ); ?></h4>
                                        <h5><?php echo implode( ', ',
												get_option( 'ssl_zen_domains', array() ) ); ?></h5>
                                    </li>

                                    <li>
                                        <h4><?php _e( 'Email', 'ssl-zen' ); ?></h4>
                                        <h5><?php echo get_option( 'ssl_zen_email', '' ); ?></h5>
                                    </li>
                                </ul>

                                <div class="certificate-validity">
                                    <h4><?php _e( 'Certificate Validity', 'ssl-zen' ); ?></h4>
									<?php

									$currentTimestamp = strtotime( date_i18n( 'Y-m-d' ) );

									$expiryDate = get_option( 'ssl_zen_certificate_90_days', '' );
									$expiryTime = strtotime( $expiryDate );
									$timeLeft   = $expiryTime - $currentTimestamp;
									$days       = floor( $timeLeft / ( 60 * 60 * 24 ) );

									$renewalDate     = get_option( 'ssl_zen_certificate_60_days', '' );
									$renewalTime     = strtotime( $renewalDate );
									$renewalTimeLeft = $renewalTime - $currentTimestamp;
									$renewalDays     = floor( $renewalTimeLeft / ( 60 * 60 * 24 ) );

									$allowRenew = ( $renewalDate <= date_i18n( 'Y-m-d' ) ) ? true : false;
									?>

									<?php
									if ( $days > 0 ) {
										?>
                                        <p class="<?php echo( $allowRenew ? 'critical' : '' ); ?>">
                                            <span><?php echo $days; ?></span> <?php _e( ( $days == '1' ? 'DAY' : 'DAYS' ),
												'ssl-zen' ); ?>
                                        </p>
										<?php
									} else {
										?>
                                        <p class="critical"><?php _e( 'Expired', 'ssl-zen' ); ?></p>
										<?php
									}
									?>

                                </div>

                                <div class="certificate-renew-area">
                                    <a href="<?php echo( $allowRenew ? admin_url( 'admin.php?page=ssl_zen&tab=step2' ) : '#' ); ?>"
                                       class="btn renew-certificate-btn" <?php echo( ! $allowRenew ? 'disabled' : '' ) ?>><?php _e( 'Renew',
											'ssl-zen' ); ?></a>
									<?php
									if ( $renewalDays > 0 ) {
										?>
                                        <p><?php echo sprintf( __( 'Too early. Come back after <span>%d</span> days.',
												'ssl-zen' ), $renewalDays ); ?></p>
										<?php
									}
									?>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="buy-me-coffee">
                                    <h4><?php _e( 'A ridiculous amount of coffee was consumed in the process of building this project. Add some fuel if you want me to keep going.',
											'ssl-zen' ); ?></h4>

                                    <img src="<?php echo SSL_ZEN_URL; ?>img/coffee.svg" alt="">

                                    <br>
                                    <a href="https://www.paypal.me/sagarspatil" target="_blank"
                                       class="buy-coffee-btn"><img src="<?php echo SSL_ZEN_URL; ?>img/buy-coffee.svg"
                                                                   alt=""></a>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
				<?php
			} else {
				$currentSettingTab = get_option( 'ssl_zen_settings_stage', '' );
				wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=' . $currentSettingTab ) );
				exit;
			}
		}

		/**
		 * Hook to be called when 'admin_init' action is called by wordpress.
		 * Handles all the processing on the various setting steps as well as
		 * redirection for incorrect steps
		 *
		 * @since 1.0
		 * @static
		 */
		public static function admin_init() {
			if ( isset( $_POST['ssl_zen_get_started_nonce'] ) ) {
				$get_started_nonce = sanitize_text_field( $_POST['ssl_zen_get_started_nonce'] );
			}
			if ( isset( $_POST['ssl_zen_generate_certificate_nonce'] ) ) {
				$certificate_nonce = sanitize_text_field( $_POST['ssl_zen_generate_certificate_nonce'] );
			}
			if ( isset( $_POST['ssl_zen_verify_nonce'] ) ) {
				$verify_nonce = sanitize_text_field( $_POST['ssl_zen_verify_nonce'] );
			}
			if ( isset( $_POST['ssl_zen_install_certificate_nonce'] ) ) {
				$install_certificate_nonce = sanitize_text_field( $_POST['ssl_zen_install_certificate_nonce'] );
			}
			if ( isset( $_POST['ssl_zen_activate_ssl_nonce'] ) ) {
				$activate_ssl_nonce = sanitize_text_field( $_POST['ssl_zen_activate_ssl_nonce'] );
			}
			if ( isset( $_POST['ssl_zen_settings_nonce'] ) ) {
				$settings_nonce = sanitize_text_field( $_POST['ssl_zen_settings_nonce'] );
			}
			if ( isset( $get_started_nonce ) && wp_verify_nonce( $get_started_nonce,
					'ssl_zen_get_started' ) ) {

				/* Executed when submitted from default step */

				update_option( 'ssl_zen_settings_stage', 'step1' );

				wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step1' ) );
				exit;

			} else if ( isset( $certificate_nonce ) && wp_verify_nonce( $certificate_nonce,
					'ssl_zen_generate_certificate' ) ) {

				/* Executed when submitted from step 1 */
				$includeWWW = ( isset( $_POST['include_www'] ) ? sanitize_text_field( $_POST['include_www'] ) : '0' );
				$baseDomain = ( isset( $_POST['base_domain_name'] ) ? sanitize_text_field( $_POST['base_domain_name'] ) : '' );
				$email      = ( isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '' );

				$arrDomains = array( $baseDomain );

				if ( $includeWWW == 1 ) {
					$arrDomains[] = 'www.' . $baseDomain;
				}

				/* Save form options in wordpress */
				update_option( 'ssl_zen_include_wwww', $includeWWW );
				update_option( 'ssl_zen_domains', $arrDomains );
				update_option( 'ssl_zen_base_domain', $baseDomain );
				update_option( 'ssl_zen_email', $email );

				self::deleteAll( SSL_ZEN_DIR . 'keys' );
				/* Generate an order for SSL certificate */
				ssl_zen_certificate::generateOrder();

				update_option( 'ssl_zen_settings_stage', 'step2' );

				wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step2' ) );
				exit;
			} else if ( isset( $verify_nonce ) && wp_verify_nonce( $verify_nonce,
					'ssl_zen_verify' ) ) {

				/* Executed when submitted from step 2 */

				/* Check all the pending autorizations and update validation status */
				ssl_zen_certificate::updateAuthorizations();

				/* Check if all authorizations are valid */
				$isValid = ssl_zen_certificate::validateAuthorization();
				if ( $isValid ) {

					/* Finalize the order for SSL Certificate */
					ssl_zen_certificate::finalizeOrder();

					/* Generate SSL Certificate */
					$arrCertificates = ssl_zen_certificate::generateCertificate();

					/* E-Mail certificates to the user if certificates are generated successfully */
					if ( is_array( $arrCertificates ) && count( $arrCertificates ) > 0 ) {

						if ( class_exists( 'ZipArchive' ) ) {
							$zip = new ZipArchive;
							$zip->open( SSL_ZEN_DIR . 'keys/certificates.zip', ZipArchive::CREATE );

							foreach ( $arrCertificates as $certificate ) {
								$certificateName = str_replace( SSL_ZEN_DIR . 'keys/', '', $certificate );
								$zip->addFromString( $certificateName,
									file_get_contents( $certificate ) ) ;
							}

							$zip->close();

							$arrCertificates = array( SSL_ZEN_DIR . 'keys/certificates.zip' );
						} else {
							$arrCertificates = array(
								SSL_ZEN_DIR . 'keys/privatekey.txt',
								SSL_ZEN_DIR . 'keys/certificate.txt',
								SSL_ZEN_DIR . 'keys/fullchain.txt',
								SSL_ZEN_DIR . 'keys/cabundle.txt'
							);
						}

						$headers = array( 'Content-Type: text/html; charset=UTF-8' );

						$message = __( 'Hello,', 'ssl-zen' ) . '<br><br>';
						$message .= __( 'Thank you for using SSLZen.com for generating your SSL certificate.',
								'ssl-zen' ) . '<br><br>';
						$message .= __( 'Download the attached files on your local computer, You will need them in the next step to install SSL certificate on your website.',
								'ssl-zen' ) . '<br>';
						$message .= __( 'You can open these files using any text editors such as Notepad.',
								'ssl-zen' ) . '<br><br>';
						$message .= __( 'What does these files do?', 'ssl-zen' ) . '<br>';
						$message .= __( 'privatekey.txt = Private Key: ( KEY )', 'ssl-zen' ) . '<br>';
						$message .= __( 'certificate.txt = Certificate: ( CRT )', 'ssl-zen' ) . '<br>';
						$message .= __( 'cabundle.txt = Certificate Authority Bundle: ( CABUNDLE )',
								'ssl-zen' ) . '<br><br>';
						$message .= __( 'Please return back to SSL Zen and complete the remaining steps.',
								'ssl-zen' ) . '<br><br>';

						$message .= __( 'Thanks,', 'ssl-zen' ) . '<br>';
						$message .= __( 'SSL Zen', 'ssl-zen' );

						wp_mail( get_option( 'ssl_zen_email', '' ),
							'Confidential: SSL Certificates for ' . get_option( 'ssl_zen_base_domain', '' ), $message,
							$headers, $arrCertificates );
					}

					update_option( 'ssl_zen_settings_stage', 'step3' );
					update_option( 'ssl_zen_certificate_60_days', date_i18n( 'Y-m-d', strtotime( "+60 day" ) ) );
					update_option( 'ssl_zen_certificate_90_days', date_i18n( 'Y-m-d', strtotime( "+90 day" ) ) );
					update_option( 'ssl_zen_certificate_60_days_email_sent', '' );
					update_option( 'ssl_zen_certificate_90_days_email_sent', '' );

					wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step3' ) );
					die;

				} else {

					/* Raise an error if authorizations are not valid */
					$info = 'invalid';

				}

				wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step2&info=' . $info ) );
				die;
			} else if ( isset( $install_certificate_nonce ) && wp_verify_nonce( $install_certificate_nonce,
					'ssl_zen_install_certificate' ) ) {

				$isValid = ssl_zen_certificate::verifyssl( get_option( 'ssl_zen_base_domain', '' ) );

				if ( $isValid ) {
					update_option( 'ssl_zen_settings_stage', 'step4' );
					wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step4' ) );
					die;
				} else {
					wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step3&info=error' ) );
					die;
				}
			} else if ( isset( $activate_ssl_nonce ) && wp_verify_nonce( $activate_ssl_nonce,
					'ssl_zen_activate_ssl' ) ) {

				$isValid = ssl_zen_certificate::verifyssl( get_option( 'ssl_zen_base_domain', '' ) );

				if ( $isValid ) {

					$fixCloudflare = ( isset( $_POST['fix_cloudflare'] ) ? '1' : '' );
					update_option( 'ssl_zen_fix_cloudflare', $fixCloudflare );

					$siteUrl = str_replace( "http://", "https://", get_option( 'siteurl' ) );
					$homeUrl = str_replace( "http://", "https://", get_option( 'home' ) );

					update_option( 'siteurl', $siteUrl );
					update_option( 'home', $homeUrl );
					update_option( 'ssl_zen_ssl_activated', '1' );
					update_option( 'ssl_zen_settings_stage', 'settings' );

					wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=settings&info=activationsuccess' ) );
					die;
				} else {
					$siteUrl = str_replace( "https://", "http://", get_option( 'siteurl' ) );
					$homeUrl = str_replace( "https://", "http://", get_option( 'home' ) );

					update_option( 'siteurl', $siteUrl );
					update_option( 'home', $homeUrl );
					update_option( 'ssl_zen_ssl_activated', '' );

					wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step4&info=error' ) );
					die;
				}

			} else if ( isset( $settings_nonce ) && wp_verify_nonce( $settings_nonce,
					'ssl_zen_settings' ) ) {

				if ( isset( $_POST['ssl_zen_deactivate_plugin'] ) && $_POST['ssl_zen_deactivate_plugin'] != '' ) {

					self::remove_plugin();

					wp_redirect( admin_url( 'plugins.php' ) );
					exit;

				} else {

					$htaccessRedirect = ( isset( $_POST['enable_301_htaccess_redirect'] ) ? '1' : '0' );
					$htaccessLock     = ( isset( $_POST['lock_htaccess_file'] ) ? '1' : '0' );

					update_option( 'ssl_zen_enable_301_htaccess_redirect', $htaccessRedirect );
					update_option( 'ssl_zen_lock_htaccess_file', $htaccessLock );

					$info             = 'success';
					$hasHtaccessRules = self::check_htaccess_rules();
					if ( ( $htaccessRedirect == '1' && $hasHtaccessRules === false ) || ( $htaccessRedirect == '0' && $hasHtaccessRules === true ) ) {
						if ( $htaccessLock ) {
							$info = 'lock';
						} else {
							/* Make sure htaccess is writable */
							if ( is_writable( ABSPATH . '.htaccess' ) ) {

								$htaccess = file_get_contents( ABSPATH . '.htaccess' );

								if ( $htaccessRedirect == '1' ) {
									/* Add rules to htaccess */

									$rules = self::get_htaccess_rules();

									/* insert rules before wordpress part. */
									if ( strlen( $rules ) > 0 ) {
										$wptag = "# BEGIN WordPress";
										if ( strpos( $htaccess, $wptag ) !== false ) {
											$htaccess = str_replace( $wptag, $rules . $wptag, $htaccess );
										} else {
											$htaccess = $htaccess . $rules;
										}

										insert_with_markers(ABSPATH . '.htaccess', 'SSL_ZEN', $htaccess);
									}
								} else {
									/* Remove rules from htaccess */
									$pattern = "/#\s?BEGIN\s?SSL_ZEN.*?#\s?END\s?SSL_ZEN/s";
									if ( preg_match( $pattern, $htaccess ) ) {
										$htaccess = preg_replace( $pattern, "", $htaccess );

										insert_with_markers(ABSPATH . '.htaccess', '', $htaccess);
									}
								}

							} else {
								$info = 'writeerr';
							}
						}
					}

					wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=settings&info=' . $info ) );
					die;
				}
			}
			if ( isset( $_REQUEST['page'] ) ) {
				$page = sanitize_text_field( $_REQUEST['page'] );
			}
			if ( isset( $_REQUEST['tab'] ) ) {
				$tab = trim( sanitize_text_field( $_REQUEST['tab'] ) );
			}
			if ( isset( $page ) && $page == 'ssl_zen' ) {

				/* Check if correct tab is loaded else redirect to the correct tab */

				$currentSettingTab = get_option( 'ssl_zen_settings_stage', '' );

				if ( $currentSettingTab != '' && ! isset( $tab ) ) {

					if ( $currentSettingTab == 'settings' ) {
						wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=renew' ) );
						exit;
					} else {
						wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=' . $currentSettingTab ) );
						exit;
					}

				} else {
					$tab = isset( $tab ) ? $tab : '';
					if ( $currentSettingTab != $tab && ! in_array( $tab, self::$allowedTabs[ $currentSettingTab ] ) ) {
						$url = 'admin.php?page=ssl_zen';
						if ( $currentSettingTab != '' ) {
							$url .= '&tab=' . $currentSettingTab;
						}

						wp_redirect( admin_url( $url ) );
						exit;
					}

					// Check the cPanel availability and update current tab value
					if ( $currentSettingTab == '' ) {
						if ( self::checkCPanelAvailabilityOfCurrentSite() ) {
							update_option( 'ssl_zen_settings_stage', 'started' );
							$url = 'admin.php?page=ssl_zen&tab=started';
							wp_redirect( admin_url( $url ) );
							exit;
						} else {
							update_option( 'ssl_zen_settings_stage', 'incompatible' );
							$url = 'admin.php?page=ssl_zen&tab=incompatible';
							wp_redirect( admin_url( $url ) );
						exit;
					}
					}

					// Uninstall the plugin
					if ( $currentSettingTab == 'incompatible' ) {
						if ( isset( $_POST['ssl_zen_deactivate_plugin'] ) && $_POST['ssl_zen_deactivate_plugin'] != '' ) {

							self::remove_plugin();

							wp_redirect( admin_url( 'plugins.php' ) );
							exit;

						}
					}
				}

				/* Executes when the user clicks to download domain authorization files. */
				if ( isset( $_REQUEST['download'] ) ) {
					$download = trim( sanitize_text_field( $_REQUEST['download'] ) );
				}
				if ( isset( $download ) && $download != '' ) {
					$arrPending = ssl_zen_certificate::getPendingAuthorization();

					if ( isset( $arrPending[ $download ] ) && is_array( $arrPending[ $download ] ) ) {

						$fileName    = ( isset( $arrPending[ $download ]['filename'] ) ? $arrPending[ $download ]['filename'] : '' );
						$fileContent = ( isset( $arrPending[ $download ]['content'] ) ? $arrPending[ $download ]['content'] : '' );

						header( 'Content-Type: text/plain' );
						header( 'Content-Disposition: attachment; filename=' . $fileName );
						header( 'Expires: 0' );
						header( 'Cache-Control: must-revalidate' );
						header( 'Pragma: public' );
						header( 'Content-Length: ' . strlen( $fileContent ) );
						echo $fileContent;
						die;

					} else {
						wp_redirect( admin_url( 'admin.php?page=ssl_zen&tab=step2&info=dlerr' ) );
						exit;
					}
				}
			}
		}

		/**
		 * Function to check the current site cPanel availability.
		 *
		 * @return bool
		 */
		public static function checkCPanelAvailabilityOfCurrentSite() {

		    $url = is_ssl() ? 'https://localhost:2083' : 'http://localhost:2082';
			$response = wp_remote_get( $url, [
				'headers' => [
					'Connection' => 'close'
				]
			] );

			if ( is_wp_error( $response ) ) {
				return false;
			} else {
				return true;
			}
		}

		/**
		 * Functon to delete all the files and folders within a directory
		 *
		 * @since 1.0
		 * @static
		 */
		public static function deleteAll( $str, $root = true ) {

			//It it's a file.
			if ( is_file( $str ) ) {
				//Attempt to delete it.
				unlink( $str );
			} //If it's a directory.
            elseif ( is_dir( $str ) ) {
				//Get a list of the files in this directory.
				$scan = glob( rtrim( $str, '/' ) . '/*' );

				//Loop through the list of files.
				foreach ( $scan as $index => $path ) {
					//Call our recursive function.
					self::deleteAll( $path, false );
					//Remove the directory itself.
				}

				if ( ! $root ) {
					@rmdir( $str );
				}
			}
		}

		/**
		 * Functon to check if htaccess rules exists
		 *
		 * @since 1.0
		 * @static
		 */
		public static function check_htaccess_rules() {

			if ( file_exists( ABSPATH . '.htaccess' ) && is_readable(ABSPATH . '.htaccess') ) {
				$htaccess = file_get_contents( ABSPATH . '.htaccess' );
				$check    = null;
				preg_match( "/BEGIN\s?SSL_ZEN/", $htaccess, $check );
				if ( count( $check ) === 0 ) {
					return false;
				} else {
					return true;
				}
			}

			return false;
		}

		/**
		 * Functon to get all the htaccess rules
		 *
		 * @since 1.0
		 * @static
		 */
		public static function get_htaccess_rules() {
			$rule = "";

			$response = wp_remote_get( home_url() );

			$filecontents = '';
			if ( is_array( $response ) ) {
				$filecontents = wp_remote_retrieve_body( $response );
			}

			//if the htaccess test was successfull, and we know the redirectype, edit
			$rule .= "<IfModule mod_rewrite.c>" . "\n";
			$rule .= "RewriteEngine on" . "\n";

			$or = "";
			if ( ( strpos( $filecontents,
						"#SERVER-HTTPS-ON#" ) !== false ) || ( isset( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) == 'on' ) ) {
				$rule .= "RewriteCond %{HTTPS} !=on [NC]" . "\n";
			} elseif ( ( strpos( $filecontents,
						"#SERVER-HTTPS-1#" ) !== false ) || ( isset( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) == '1' ) ) {
				$rule .= "RewriteCond %{HTTPS} !=1" . "\n";
			} elseif ( ( strpos( $filecontents,
						"#LOADBALANCER#" ) !== false ) || ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && ( $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) ) ) {
				$rule .= "RewriteCond %{HTTP:X-Forwarded-Proto} !https" . "\n";
			} elseif ( ( strpos( $filecontents,
						"#HTTP_X_PROTO#" ) !== false ) || ( isset( $_SERVER['HTTP_X_PROTO'] ) && ( $_SERVER['HTTP_X_PROTO'] == 'SSL' ) ) ) {
				$rule .= "RewriteCond %{HTTP:X-Proto} !SSL" . "\n";
			} elseif ( ( strpos( $filecontents,
						"#CLOUDFLARE#" ) !== false ) || ( isset( $_SERVER['HTTP_CF_VISITOR'] ) && ( $_SERVER['HTTP_CF_VISITOR'] == 'https' ) ) ) {
				$rule .= "RewriteCond %{HTTP:CF-Visitor} '" . '"scheme":"http"' . "'" . "\n";//some concatenation to get the quotes right.
			} elseif ( ( strpos( $filecontents,
						"#SERVERPORT443#" ) !== false ) || ( isset( $_SERVER['SERVER_PORT'] ) && ( '443' == $_SERVER['SERVER_PORT'] ) ) ) {
				$rule .= "RewriteCond %{SERVER_PORT} !443" . "\n";
			} elseif ( ( strpos( $filecontents,
						"#CLOUDFRONT#" ) !== false ) || ( isset( $_SERVER['HTTP_CLOUDFRONT_FORWARDED_PROTO'] ) && ( $_SERVER['HTTP_CLOUDFRONT_FORWARDED_PROTO'] == 'https' ) ) ) {
				$rule .= "RewriteCond %{HTTP:CloudFront-Forwarded-Proto} !https" . "\n";
			} elseif ( ( strpos( $filecontents,
						"#HTTP_X_FORWARDED_SSL_ON#" ) !== false ) || ( isset( $_SERVER['HTTP_X_FORWARDED_SSL'] ) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on' ) ) {
				$rule .= "RewriteCond %{HTTP:X-Forwarded-SSL} !on" . "\n";
			} elseif ( ( strpos( $filecontents,
						"#HTTP_X_FORWARDED_SSL_1#" ) !== false ) || ( isset( $_SERVER['HTTP_X_FORWARDED_SSL'] ) && $_SERVER['HTTP_X_FORWARDED_SSL'] == '1' ) ) {
				$rule .= "RewriteCond %{HTTP:X-Forwarded-SSL} !=1" . "\n";
			} elseif ( ( strpos( $filecontents,
						"#ENVHTTPS#" ) !== false ) || ( isset( $_ENV['HTTPS'] ) && ( 'on' == $_ENV['HTTPS'] ) ) ) {
				$rule .= "RewriteCond %{ENV:HTTPS} !=on" . "\n";
			}

			//if multisite, and NOT subfolder install (checked for in the detect_config function)
			//, add a condition so it only applies to sites where plugin is activated
			if ( is_multisite() ) {

				$sites = ( $wp_version >= 4.6 ) ? get_sites() : wp_get_sites();
				foreach ( $sites as $domain ) {

					//remove http or https.
					$domain = preg_replace( "/(http:\/\/|https:\/\/)/", "", $domain );
					//We excluded subfolders, so treat as domain

					$domain_no_www  = str_replace( "www.", "", $domain );
					$domain_yes_www = "www." . $domain_no_www;

					$rule .= "#rewritecond " . $domain . "\n";
					$rule .= "RewriteCond %{HTTP_HOST} ^" . preg_quote( $domain_no_www, "/" ) . " [OR]" . "\n";
					$rule .= "RewriteCond %{HTTP_HOST} ^" . preg_quote( $domain_yes_www, "/" ) . " [OR]" . "\n";
					$rule .= "#end rewritecond " . $domain . "\n";
				}

				//now remove last [OR] if at least on one site the plugin was activated, so we have at lease one condition
				if ( count( $sites ) > 0 ) {
					$rule = strrev( implode( "", explode( strrev( "[OR]" ), strrev( $rule ), 2 ) ) );
				}
			}

			//fastest cache compatibility
			if ( class_exists( 'WpFastestCache' ) ) {
				$rule .= "RewriteCond %{REQUEST_URI} !wp-content\/cache\/(all|wpfc-mobile-cache)" . "\n";
			}

			//Exclude .well-known/acme-challenge for Let's Encrypt validation
			$rule .= "RewriteCond %{REQUEST_URI} !^/\.well-known/acme-challenge/" . "\n";

			$rule .= "RewriteRule ^(.*)$ https://%{HTTP_HOST}/$1 [R=301,L]" . "\n";

			$rule .= "</IfModule>" . "\n";

			if ( strlen( $rule ) > 0 ) {
				$rule = "\n" . "# BEGIN SSL_ZEN\n" . $rule . "# END SSL_ZEN" . "\n";
			}

			$rule = preg_replace( "/\n+/", "\n", $rule );

			return $rule;
		}

		/**
		 * Functon to remove a plugin from the array
		 *
		 * @since 1.0
		 * @static
		 */
		private static function remove_plugin_from_array( $plugin, $activePlugins ) {
			$key = array_search( $plugin, $activePlugins );
			if ( false !== $key ) {
				unset( $activePlugins[ $key ] );
			}

			return $activePlugins;
		}

		/**
		 * Functon to remove a plugin from active plugins list
		 *
		 * @since 1.0
		 * @static
		 */
		private static function remove_plugin() {
			if ( is_multisite() ) {
				$activePlugins = get_site_option( 'active_sitewide_plugins', array() );
				if ( is_plugin_active_for_network( SSL_ZEN_BASEFILE ) ) {
					unset( $activePlugins[ SSL_ZEN_BASEFILE ] );
				}
				update_site_option( 'active_sitewide_plugins', $activePlugins );

				/* remove plugin one by one on each site */
				$sites = self::get_network_sites();
				foreach ( $sites as $site ) {
					self::switch_network_site( $site );

					$activePlugins = get_option( 'active_plugins', array() );
					$activePlugins = self::remove_plugin_from_array( SSL_ZEN_BASEFILE, $activePlugins );
					update_option( 'active_plugins', $activePlugins );

					/* switches back to previous blog, not current, so we have to do it each loop */
					restore_current_blog();
				}

			} else {

				$activePlugins = get_option( 'active_plugins', array() );
				$activePlugins = self::remove_plugin_from_array( SSL_ZEN_BASEFILE, $activePlugins );
				update_option( 'active_plugins', $activePlugins );
			}
		}

		/**
		 * Functon to get all network sites
		 *
		 * @since 1.0
		 * @static
		 */
		private static function get_network_sites() {
			global $wp_version;
			$sites = ( $wp_version >= 4.6 ) ? get_sites() : wp_get_sites();

			return $sites;
		}

		/**
		 * Functon to switch to network sites
		 *
		 * @since 1.0
		 * @static
		 */
		private static function switch_network_site( $site ) {
			global $wp_version;
			if ( $wp_version >= 4.6 ) {
				switch_to_blog( $site->blog_id );
			} else {
				switch_to_blog( $site['blog_id'] );
			}
		}

		/**
		 * Hook to remove all the plugin settings on deactivation
		 *
		 * @since 1.0
		 * @static
		 */
		public static function deactivate_plugin() {
			if ( is_multisite() ) {
				delete_site_option( 'ssl_zen_settings_stage' );
				delete_site_option( 'ssl_zen_include_wwww' );
				delete_site_option( 'ssl_zen_domains' );
				delete_site_option( 'ssl_zen_base_domain' );
				delete_site_option( 'ssl_zen_email' );
				delete_site_option( 'ssl_zen_certificate_60_days' );
				delete_site_option( 'ssl_zen_certificate_90_days' );
				delete_site_option( 'ssl_zen_certificate_60_days_email_sent' );
				delete_site_option( 'ssl_zen_certificate_90_days_email_sent' );
				delete_site_option( 'ssl_zen_fix_cloudflare' );
				delete_site_option( 'ssl_zen_ssl_activated' );
				delete_site_option( 'ssl_zen_enable_301_htaccess_redirect' );
				delete_site_option( 'ssl_zen_lock_htaccess_file' );

				$sites = self::get_network_sites();
				foreach ( $sites as $site ) {
					self::switch_network_site( $site );

					$siteUrl = str_replace( "https://", "http://", get_option( 'siteurl', '' ) );
					$homeUrl = str_replace( "https://", "http://", get_option( 'home', '' ) );

					update_option( 'siteurl', $siteUrl );
					update_option( 'home', $homeUrl );

					delete_option( 'ssl_zen_settings_stage' );
					delete_option( 'ssl_zen_include_wwww' );
					delete_option( 'ssl_zen_domains' );
					delete_option( 'ssl_zen_base_domain' );
					delete_option( 'ssl_zen_email' );
					delete_option( 'ssl_zen_certificate_60_days' );
					delete_option( 'ssl_zen_certificate_90_days' );
					delete_option( 'ssl_zen_certificate_60_days_email_sent' );
					delete_option( 'ssl_zen_certificate_90_days_email_sent' );
					delete_option( 'ssl_zen_fix_cloudflare' );
					delete_option( 'ssl_zen_ssl_activated' );
					delete_option( 'ssl_zen_enable_301_htaccess_redirect' );
					delete_option( 'ssl_zen_lock_htaccess_file' );

					restore_current_blog();
				}
			} else {
				/* Remove SSL from site and home urls */
				$siteUrl = str_replace( "https://", "http://", get_option( 'siteurl', '' ) );
				$homeUrl = str_replace( "https://", "http://", get_option( 'home', '' ) );

				update_option( 'siteurl', $siteUrl );
				update_option( 'home', $homeUrl );

				/* Remove all the database settings */
				delete_option( 'ssl_zen_settings_stage' );
				delete_option( 'ssl_zen_include_wwww' );
				delete_option( 'ssl_zen_domains' );
				delete_option( 'ssl_zen_base_domain' );
				delete_option( 'ssl_zen_email' );
				delete_option( 'ssl_zen_certificate_60_days' );
				delete_option( 'ssl_zen_certificate_90_days' );
				delete_option( 'ssl_zen_certificate_60_days_email_sent' );
				delete_option( 'ssl_zen_certificate_90_days_email_sent' );
				delete_option( 'ssl_zen_fix_cloudflare' );
				delete_option( 'ssl_zen_ssl_activated' );
				delete_option( 'ssl_zen_enable_301_htaccess_redirect' );
				delete_option( 'ssl_zen_lock_htaccess_file' );
			}

			/* Remove rules from .htaccess file */
			if ( is_writable( ABSPATH . '.htaccess' ) ) {

				$htaccess = file_get_contents( ABSPATH . '.htaccess' );
				/* Remove rules from htaccess */
				$pattern = "/#\s?BEGIN\s?SSL_ZEN.*?#\s?END\s?SSL_ZEN/s";
				if ( preg_match( $pattern, $htaccess ) ) {
					$htaccess = preg_replace( $pattern, "", $htaccess );
				}

				insert_with_markers(ABSPATH . '.htaccess', '', $htaccess);
			}

			self::remove_plugin();

			wp_redirect( admin_url( 'plugins.php?deactivate=true', 'http' ) );
			exit;
		}

		/**
		 * Hook to add custom links on the plugins page
		 *
		 * @since 1.0
		 * @static
		 */
		public static function plugin_action_links( $links ) {
			$links[] = '<a href="' . admin_url( 'admin.php?page=ssl_zen&tab=step1' ) . '">' . __( 'Setup',
					'ssl-zen' ) . '</a>';
			$links[] = '<a href="' . admin_url( 'admin.php?page=ssl_zen&tab=settings' ) . '">' . __( 'Settings',
					'ssl-zen' ) . '</a>';
			$links[] = '<a href="mailto: support@sslzen.com">' . __( 'Support', 'ssl-zen' ) . '</a>';
			$links[] = '<a href="https://www.paypal.me/sagarspatil" target="_blank">' . __( 'Buy me a coffee',
					'ssl-zen' ) . '</a>';

			return $links;
		}
	}

	/**
	 * Calling init function and activate hooks and filters.
	 */
	ssl_zen_admin::init();
}