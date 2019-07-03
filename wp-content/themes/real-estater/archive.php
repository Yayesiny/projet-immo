<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Real_Estater
 */

get_header();
$sidebar = get_theme_mod('real_estater_archive_setting_sidebar_option','sidebar-right');
?>
    <div class="container">

          <div class="row">

        	<?php 
			global $post;
			$custom_class = 'custom-col-8';
			if( 'sidebar-no' == $sidebar ){
				$custom_class = 'custom-col-12';
			} elseif ( 'sidebar-both' == $sidebar ) {
				$custom_class = 'custom-col-4';
			}else{
				$custom_class = 'custom-col-8';
			}
			if($sidebar=='sidebar-both' || $sidebar=='sidebar-left'){
				get_sidebar('left');
			}
			?> 
				<div id="primary" class="content-area <?php echo esc_attr( $custom_class );?>">
					<main id="main" class="site-main">
					<?php if ( have_posts() ) : ?>
						<header class="page-header">
							<?php
							the_archive_title( '<h2 class="page-title">', '</h2>' );
							the_archive_description( '<div class="archive-description">', '</div>' );
							?>
							<?php 
						    ?>
						</header><!-- .page-header -->

						<?php
						/* Start the Loop */
						while ( have_posts() ) :
							the_post();

							/*
							 * Include the Post-Type-specific template for the content.
							 * If you want to override this in a child theme, then include a file
							 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
							 */
							get_template_part( 'template-parts/content', get_post_type() );

						endwhile;

						the_posts_navigation();

					else :

						get_template_part( 'template-parts/content', 'none' );

					endif;
					?>
					</main><!-- #main -->
			</div><!-- #primary -->
            <?php  get_sidebar();?>
            
		</div>
	</div>
<?php get_footer(); ?>
