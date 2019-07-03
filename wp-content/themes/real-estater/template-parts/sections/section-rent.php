<?php
/**
 * Rent Section 
*/
//Starting Rent Section
if (get_theme_mod('real_estater_homepage_rent_section','no')=='yes') { 
  $rent_category = get_theme_mod('real_estater_for_rent_section_category' );
  $number = get_theme_mod('real_estater_rent_num',3);
?>  
	<section class="featured-section"> <!-- featured section starting from here --> 
		<div class="rent-section">
			<?php $section_title =  get_theme_mod('real_estater_for_rent_title',esc_html__('For Rent Title','real-estater'));
			if(!empty( $section_title ) ): ?>
			<header class="entry-header heading">
					<h2 class="entry-title"><?php echo esc_html( $section_title );?></h2>
			</header>
			<?php endif; ?>

			<div class="container">
				<?php
		     	if ( !empty( $rent_category) ) {
		     		
		     		$loop = new WP_Query(array('post_type'=>'post','posts_per_page'=> absint( $number ), 'category_name'=>esc_html( $rent_category ) ) );
		     	} else{
		     		$loop = new WP_Query( array( 'post_type'=>'post','posts_per_page'=> absint( $number ), ) );
		     	} 
					?>  
					<div class="row">
							<?php
			            if($loop->have_posts()) {
							while($loop->have_posts()) {
								$loop->the_post();
								$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'real-estater-rent-image', true );
							?>
				 				<div class="custom-col-4">

				 				 	<div class="post">
				 				 		
								 		<figure class="post-featured-image">
								 			<a href="<?php the_permalink();?>"><img src="<?php echo esc_url($image[0]); ?>" /></a>
								 			<?php $meta_value = get_post_meta($post->ID, 'cost', true );
								 			if (!empty($meta_value)) { ?> 
									 			<span class="price">
									 				<?php 
														echo ''. esc_html($meta_value) .'';
													?>	
						                      </span>
					                  		<?php } ?>
								 		</figure>
								 		<header class="<entry-header">
								 			<?php real_estater_tags(); ?>
								 			<h3 class="entry-title">
						                    	<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						                    </h3>
								 		</header>

								 		<?php 
										$bedroom = get_post_meta($post->ID, 'bedroom', true );
										$bedroom_num = get_post_meta($post->ID, 'bedroom_num', true );
										$bathroom = get_post_meta($post->ID, 'bathroom', true );
										$bathroom_num = get_post_meta($post->ID, 'bathroom_num', true );
										$garage = get_post_meta($post->ID, 'garage', true );
										$garage_num  =get_post_meta($post->ID, 'garage_num', true );
										?>

										<?php if ( !empty( $bedroom ) || !empty( $bedroom_num ) || !empty( $bathroom ) || !empty( $bathroom_num ) ||!empty( $garage )|| !empty( $garage_num ) ): ?>
										<?php if (get_theme_mod('real_estater_homepage_feature_section_rent_meta','no')=='yes') { ?>
								 		<footer class="entry-footer">
					                        <div class="property-meta entry-meta">
					                            <div class="meta-wrapper">
					                              <span class="meta-unit">
					                              	  <?php echo esc_html( $bedroom ); ?>
					                              </span>
					                              <span class="meta-value">
					                              	<?php echo absint( $bedroom_num ); ?>
					                              </span>
					                            </div>
					                            <div class="meta-wrapper">
					                              <span class="meta-unit">
					                              	<?php echo esc_html( $bathroom ); ?>
					                              </span>
					                              <span class="meta-value">
					                              	<?php echo absint( $bathroom_num ); ?>
					                              </span>
					                            </div>
					                            <div class="meta-wrapper">
					                              <span class="meta-unit">
					                              	<?php echo esc_html( $garage ); ?>
					                              </span>
					                              <span class="meta-value">
					                              	<?php echo absint( $garage_num ); ?>
					                              </span>
					                            </div>
					                        </div>
				                       </footer>
										<?php } ?>
                                      <?php endif; ?>

							      	</div>
						      	</div>
					        <?php }
				            wp_reset_postdata();
			    		} ?>
			      	</div>
		  	</div>
		</div>
	</section>
<?php }