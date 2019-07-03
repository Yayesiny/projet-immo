<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Real_Estater
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function real_estater_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

    $sidebar = get_theme_mod('real_estater_archive_setting_sidebar_option','sidebar-right');

    if ( is_archive ()  || is_category() ) {
        $classes[] = $sidebar;
    }

	global $post;
    $post_id = "";
    if(is_front_page()){
    	$post_id = get_option('page_on_front');
    }else{
    	if($post)
    	$post_id = $post->ID;
    }
    $classes[] = esc_attr(get_post_meta( esc_attr($post_id) , 'real_estater_sidebar_layout', true ) );


	// Adds a class of no-sidebar when there is no sidebar present.


	return $classes;
}
add_filter( 'body_class', 'real_estater_body_classes' );


/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function real_estater_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'real_estater_pingback_header' );
