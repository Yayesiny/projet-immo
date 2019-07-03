<?php

/**
 * Class ERE_Captcha
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('ERE_Captcha')) {
	class ERE_Captcha
	{
		public function render_recaptcha() {
			$enable_captcha = ere_get_option('enable_captcha', array());
			if (is_array($enable_captcha) && count($enable_captcha)>0) {
				wp_enqueue_script('ere-google-recaptcha');
				$captcha_site_key = ere_get_option('captcha_site_key', '');
				?>
				<script type="text/javascript">
					var ere_widget_ids = [];
					var ere_captcha_site_key = '<?php echo $captcha_site_key; ?>';
					/**
					 * reCAPTCHA render
					 */
					var ere_recaptcha_onload_callback = function() {
						jQuery('.ere-google-recaptcha').each( function( index, el ) {
							var widget_id = grecaptcha.render( el, {
								'sitekey' : ere_captcha_site_key
							} );
							ere_widget_ids.push( widget_id );
						} );
					};
					/**
					 * reCAPTCHA reset
					 */
					var ere_reset_recaptcha = function() {
						if( typeof ere_widget_ids != 'undefined' ) {
							var arrayLength = ere_widget_ids.length;
							for( var i = 0; i < arrayLength; i++ ) {
								grecaptcha.reset( ere_widget_ids[i] );
							}
						}
					};
				</script>
				<?php
			}
		}

		public function verify_recaptcha() {
			if (isset($_POST['g-recaptcha-response'])) {
				$captcha_secret_key = ere_get_option('captcha_secret_key', '');
				$response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret=". $captcha_secret_key ."&response=". $_POST['g-recaptcha-response']);
				$response = json_decode($response["body"], true);
				if (true == $response["success"]) {
				} else {
					echo json_encode( array(
						'success' => false,
						'message' => esc_attr__( 'Captcha Invalid', 'essential-real-estate' )
					) );
					wp_die();
				}
			}
		}

		public function form_recaptcha() {
			$enable_captcha = ere_get_option('enable_captcha', array());
			if (is_array($enable_captcha) && count($enable_captcha)>0) {
				?>
				<div class="ere-recaptcha-wrap clearfix">
					<div class="ere-google-recaptcha"></div>
				</div>
				<?php
			}
		}
	}
}