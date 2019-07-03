<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Real_Estater
 */

global $post;

$post_class = get_post_meta( $post->ID, 'real_estater_sidebar_layout', true );
if ( is_archive() || is_category() ){
	$post_class = get_theme_mod('real_estater_archive_setting_sidebar_option','sidebar-right');
} elseif ( is_singular() && '' !== $post_class ){
	$post_class =  get_post_meta( $post->ID, 'real_estater_sidebar_layout', true );
} else{
	$post_class = 'sidebar-right';
}

if ( 'sidebar-no' == $post_class || ! is_active_sidebar( 'real-estater-sidebar-right' ) ) {
	return;
}



if($post_class=='sidebar-right' || $post_class=='sidebar-both'){
	?>
	<div id="secondary" class="custom-col-4  widget-area"> <!-- secondary starting from here -->
		<?php dynamic_sidebar( 'real-estater-sidebar-right' );   ?>
	</div>

<?php } ?>