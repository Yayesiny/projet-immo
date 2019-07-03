<?php
/**
 * Functions to provide support for the One Click Demo Import plugin (wordpress.org/plugins/one-click-demo-import)
 *
 * @package Business_Process
 */

/**
* Remove branding
*/
add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );

/*Import demo data*/
if ( ! function_exists( 'real_estater_demo_import_files' ) ) :
    function real_estater_demo_import_files() {
        return array(
            array(
                'import_file_name'             => 'Real Estater',                
                'local_import_file'            => trailingslashit( get_template_directory() ) . 'demo-content/daillyshop.wordpress.2019-02-07.xml',
                'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'demo-content/demo.theme404.com-test-widgets.wie',
                'local_import_customizer_file' => trailingslashit( get_template_directory() ) . 'demo-content/real-estater-export.dat',
                'import_notice'                => esc_html__( 'Please waiting for a few minutes, do not close the window or refresh the page until the data is imported', 'real-estater' ),
            ),
        ); 
    }

    add_filter( 'pt-ocdi/import_files', 'real_estater_demo_import_files' );

endif;

/**
 * Action that happen after import
 */
if ( ! function_exists( 'real_estater_after_demo_import' ) ) :
    function real_estater_after_demo_import( $selected_import ) {
            //Set Menu
            $primary_menu = get_term_by('name', 'Main Menu', 'nav_menu'); 

            $social_menu = get_term_by('name', 'Social Menu', 'nav_menu');  

            $footer_menu  = get_term_by( 'name', 'Footer menu', 'nav_menu');

            set_theme_mod( 'nav_menu_locations' , array( 

                'menu-1' => $primary_menu->term_id,

                'social-media' => $social_menu->term_id, 

                'footer-menu' => $footer_menu->term_id, 

                ) 

            );
            //Set Front page
            $page = get_page_by_title( 'Home');
            if ( isset( $page->ID ) ) {
                update_option( 'page_on_front', $page->ID );
                update_option( 'show_on_front', 'page' );
            }          

        
            
    }

    add_action( 'pt-ocdi/after_import', 'real_estater_after_demo_import' );



endif;