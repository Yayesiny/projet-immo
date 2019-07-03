<?php
/*
Plugin Name: Contact Form 7 Styler for Divi
Plugin URI:  https://divipeople.com
Description: Design beuatiful contact forms with <strong>Contact Form 7 Styler for Divi</strong>
Version:     1.0.2
Author:      DiviPeople
Author URI:  https://divipeople.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: dvppl-cf7-styler
Domain Path: /languages

Contact Form 7 Styler for Divi is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Contact Form 7 Styler for Divi is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with CF7 Styler. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'DVPPL_CF7_URL', plugins_url( '/', __FILE__ ) );
define( 'DVPPL_CF7_PATH', plugin_dir_path( __FILE__ ) );

if ( ! function_exists( 'dvppl_initialize_extension' ) ):

/**
 * Creates the extension's main class instance.
 *
 * @since 1.0.0
 */
function dvppl_initialize_extension() {
	require_once DVPPL_CF7_PATH . 'includes/Cf7StylerMain.php';
}

add_action( 'divi_extensions_init', 'dvppl_initialize_extension' );

endif;

if ( ! function_exists( 'dvppl_cf7_installed' ) ):
/**
 * Check if Elementor is installed
 *
 * @since 1.0.0
 */
function dvppl_cf7_installed() {
	$file_path = 'contact-form-7/wp-contact-form-7.php';
	$installed_plugins = get_plugins();
	return isset( $installed_plugins[ $file_path ] );
}

endif;

if ( ! function_exists( 'dvppl_scripts' ) ):

function dvppl_scripts() {
	if ( isset( $_GET['et_fb'] ) ) {
		wp_enqueue_style('dvppl-et-admin', plugin_dir_url( __FILE__ ) . 'assets/css/admin.css' );
	}
}

add_action('wp_enqueue_scripts', 'dvppl_scripts');

endif;


if ( ! function_exists( 'dvppl_cf7_admin_notice' ) ):
/**
 * Admin Notices
 * 
 * @since 1.0.0
 */
function dvppl_cf7_admin_notice() {

  $plugin = 'contact-form-7/wp-contact-form-7.php';

	if( dvppl_cf7_installed() ) {

		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$cf7_install_active_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
    
    $message = __( 'Contact Form 7 Styler for Divi requires Coctact Form 7 plugin to be active. Please activate Contact Form 7 to continue.', 'dvppl-cf7-styler' );

		$button_text = __( 'Activate Contact Form 7', 'dvppl-cf7-styler' );
		
		$button = '<p><a href="' . $cf7_install_active_url . '" class="button-primary">' . $button_text . '</a></p>';

	} else {

		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$cf7_install_active_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=contact-form-7' ), 'install-plugin_contact-form-7' );

    $message = __( 'Contact Form 7 Styler for Divi plugin to be installed and activated. Please install Contact Form 7 to continue.', 'dvppl-cf7-styler' );

		$button_text = __( 'Install Contact Form 7', 'dvppl-cf7-styler' );
		
		$button = '<p><a href="' . $cf7_install_active_url . '" class="button-primary install-now button">' . $button_text . '</a></p>';

	}
  
  printf( '<div class="error"><p>%1$s</p>%2$s</div>', esc_html( $message ), $button );
}

endif;

if ( ! function_exists( 'dvppl_cf7_plugin_is_loaded' ) ):

/**
 * Contact form 7 is loaded
 */
function dvppl_cf7_plugin_is_loaded() {
  if ( ! is_plugin_active('contact-form-7/wp-contact-form-7.php') ) {
		add_action( 'admin_notices', 'dvppl_cf7_admin_notice' );
	} 
}

add_action( 'admin_init', 'dvppl_cf7_plugin_is_loaded' );

endif;
