<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Real_Estater
 */
global $post;

$post_class = get_post_meta( $post->ID, 'real_estater_sidebar_layout', true );

if ( is_archive() || is_category() ){
	$post_class = get_theme_mod('real_estater_archive_setting_sidebar_option','sidebar-right');
} elseif ( is_singular() ){
	$post_class =  get_post_meta( $post->ID, 'real_estater_sidebar_layout', true );
} else{
	$post_class = 'sidebar-right';
}

if($post_class=='sidebar-left' || $post_class=='sidebar-both'){
	?>
	<div id="secondary" class=" custom-col-4 widget-area">
		<?php if ( is_active_sidebar( 'real-estater-sidebar-left' ) ) : ?>
		<?php dynamic_sidebar( 'real-estater-sidebar-left' ); ?>
		<?php endif; ?>
	</div>
	<?php    
}
?>
