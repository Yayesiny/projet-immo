<?php
/**
 * Sanitization functions.
 *
 * @package Real_Estater
 */

function real_estater_sanitize_radio_yes_no($input){
        $option = array(
                'yes'   =>  esc_html__('Yes','real-estater'),
                'no'    =>  esc_html__('No','real-estater')
            );     
        if(array_key_exists($input, $option)){
            return $input;
        }
        else
            return '';
    }
function real_estater_sanitize_category_select($input){
    $real_estater_category_lists = real_estater_category_lists();
    if(array_key_exists($input,$real_estater_category_lists)){
        return $input;
    }else{
        return '';
    }
}


function real_estater_radio_sanitize_archive_sidebar($input) {
  $valid_keys = array(
    'sidebar-left' =>  __('Sidebar Left','real-estater'),
    'sidebar-right' =>  __('Sidebar Right','real-estater'),
    'sidebar-both' =>  __('Sidebar Both','real-estater'),
    'sidebar-no' =>  __('Sidebar No','real-estater'),
  );
  if ( array_key_exists( $input, $valid_keys ) ) {
     return $input;
  } else {
     return '';
  }
}


// sanitizer for call to action

function real_estater_sanitize_text( $input ) {
    return wp_kses_post( force_balance_tags( $input ) );
}

if ( ! function_exists( 'real_stater_sanitize_dropdown_pages' ) ) :

    /**
     * Sanitize dropdown pages.
     *
     * @since 1.0.0
     *
     * @param int                  $page_id Page ID.
     * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
     * @return int|string Page ID if the page is published; otherwise, the setting default.
     */
    function real_stater_sanitize_dropdown_pages( $page_id, $setting ) {

        // Ensure $input is an absolute integer.
        $page_id = absint( $page_id );

        // If $page_id is an ID of a published page, return it; otherwise, return the default.
        return ( 'publish' === get_post_status( $page_id ) ? $page_id : $setting->default );

    }

endif;

//integer sanitize
   function real_estater_integer_sanitize($input){
        return intval( $input );
   }
if ( ! function_exists( 'real_estater_sanitize_multiple_dropdown_taxonomies' ) ) :

/**
 *  Sanitize Multiple Dropdown Taxonomies.
 *  @since 1.0.0
 */
function real_estater_sanitize_multiple_dropdown_taxonomies( $input ) {
    // Make sure we have array.
    $input = (array) $input;

    // Sanitize each array element.
    $input = array_map( 'absint', $input );

    // Remove null elements.
    $input = array_values( array_filter( $input ) );

    return $input;
}
endif;
