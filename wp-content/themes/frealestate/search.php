<?php
/**
 * The template for displaying search results pages.
 *
 */

 get_header(); ?>

<div id="main-content-wrapper">

	<div id="main-content">

		<div id="infoTxt">
			<?php 
				/* translators: %s: search query */
				printf( esc_html__( 'You searched for "%s". Here are the results:', 'frealestate' ),
						get_search_query() );
			?>
		</div><!-- #infoTxt -->

	<?php if ( have_posts() ) :

				// starts the loop
				while ( have_posts() ) :

					the_post();

					/**
				 	 * Run the loop for the search to output the results.
				 	 */
					get_template_part( 'template-parts/content', 'excerpt' );

				endwhile;
	
				the_posts_pagination( array(
		                        'prev_next' => '',
		                    ) );

		else :

				// if no content is loaded, show the 'no found' template
				get_template_part( 'template-parts/content', 'none' );
			
		  endif;
	?>

	</div><!-- #main-content -->

	<?php get_sidebar(); ?>

</div><!-- #main-content-wrapper -->

<?php get_footer(); ?>