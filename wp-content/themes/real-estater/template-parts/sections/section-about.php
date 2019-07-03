<?php
/**
 * About Section 
*/
//Starting About Us Section

	if (get_theme_mod('real_estater_homepage_about_section','no')=='yes') {
		$real_estater_page_about 	= get_theme_mod('real_estater_page_about');
		if( !empty( $real_estater_page_about ) ): 
			$args = array (	            		            
				'page_id'			=> absint($real_estater_page_about ),
				'post_status'   	=> 'publish',
				'post_type' 		=> 'page',
			);
			$loop = new WP_Query($args);
			if ( $loop->have_posts() ) : ?>
				<section class="about-section"> <!-- about section starting from here -->
					<?php while ($loop->have_posts()) : $loop->the_post();?>	

						<div class="container">
							<header class="entry-header heading">
								<h2 class="entry-title"><?php the_title(); ?> </h2>
							</header>
							<div class="row">
								<div class="custom-col-6 ">
									<div class="entry-content gallery">									
										<?php  the_content(); ?>									
									</div>
								</div>
								<div class="custom-col-6">
									<figure>
										<?php the_post_thumbnail(); ?>
									</figure>
								</div>
							</div> 
						</div>

					<?php endwhile;
					wp_reset_postdata();?>
				</section>
			<?php endif;
		endif;
	} ?>





