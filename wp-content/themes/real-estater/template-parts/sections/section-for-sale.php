<?php
/**
 * Choose For Sale Section 
*/
// Starting For Sale Section
 if (get_theme_mod('real_estater_homepage_sale_section','no')=='yes') {
	$for_sale_category = get_theme_mod( 'real_estater_for_sale_section_category'  );
	$number = get_theme_mod( 'real_estater_for_sale_num',3 );

?>
<section class="featured-section"> <!-- featured section starting from here --> 
 	<div class="sale-section">
 		<?php $section_title =  get_theme_mod('real_estater_for_sale_title',esc_html__( 'For Sale Section Title','real-estater' ) );
		if(!empty( $section_title ) ):    ?>
		<header class="entry-header heading">
			<h2 class="entry-title"><?php echo esc_html( $section_title );?></h2>
		</header>
		<?php endif; ?>
		<div class="container">
		       <?php
		     	if ( !empty( $for_sale_category) ) {
		     		$loop = new WP_Query(array('post_type'=>'post','posts_per_page'=> absint( $number ), 'category_name'=>esc_html( $for_sale_category) ) );
		     	} else{
		     		$loop = new WP_Query( array( 'post_type'=>'post','posts_per_page'=> absint( $number ), ) );
		     	}  
          		if($loop->have_posts()): ?> 
				<div class="row">
					<?php
						while($loop->have_posts()) {
							$loop->the_post();
							$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'real-estater-for-sale-image', true );
							?>
							<div class="custom-col-4">
								<div class="post">
									<?php $images = get_post_gallery_images();
									if(!empty( $images)) { ?>
										<div id="property-slider" class="property-slider owl-carousel owl-theme">
											<?php
											$images = get_post_gallery_images();
											?>
											<?php foreach ($images as $imagesss) { ?>
											<div class="slider-content">
												<figure class="post-featured-image">	
													<img src="<?php echo esc_url($imagesss);?>">      			
												</figure>
											</div>	
											<?php } ?>								
										</div> 	     	 	 			 	
										<?php } else{ ?>
										<figure class="post-featured-image">
											<a href="<?php the_permalink();?>"><img src="<?php echo esc_url($image[0]); ?>" /></a>
											<?php $meta_value = get_post_meta($post->ID, 'cost', true );
											  if (!empty($meta_value)) { ?> 
												<span class="price">
												 <?php echo ''. esc_html($meta_value) .''; ?>
												</span>
											<?php } ?>
										</figure>
										<header class="entry-header">
											<?php real_estater_tags(); ?>
											<h3 class="entry-header">
												<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
											</h3>
										</header>
									<?php } ?>

									<?php 
									$bedroom = get_post_meta($post->ID, 'bedroom', true );
									$bedroom_num = get_post_meta($post->ID, 'bedroom_num', true );
									$bathroom = get_post_meta($post->ID, 'bathroom', true );
									$bathroom_num = get_post_meta($post->ID, 'bathroom_num', true );
									$garage = get_post_meta($post->ID, 'garage', true );
									$garage_num  =get_post_meta($post->ID, 'garage_num', true );
									?>

									<?php if ( !empty( $bedroom ) || !empty( $bedroom_num ) || !empty( $bathroom ) || !empty( $bathroom_num ) ||!empty( $garage )|| !empty( $garage_num ) ): ?>
									<?php if (get_theme_mod('real_estater_homepage_sale_section_property_meta','no')=='yes') {	?>
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
							<?php 
							}
							wp_reset_postdata();
						?>
					</div> 
		<?php endif ?>
		</div>    
 	</div>
</section>
<?php 
}
?> 
