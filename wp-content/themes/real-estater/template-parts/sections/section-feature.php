<?php
/**
 * Feature Section 
*/
//Starting Feature Section
if (get_theme_mod( 'real_estater_homepage_feature_section','no' )=='yes') {
	$feature_category = get_theme_mod( 'real_estater_feature_section_category' );
	$number = get_theme_mod( 'real_estater_feature_num',3 );
?>  
	<section class="featured-properties"> <!-- featured propertise starting from here --> 
		<div class="container">
			<?php $section_title =  get_theme_mod( 'real_estater_feature_title' ,esc_html__( 'Feature Section Title','real-estater' ) );
			if(!empty( $section_title ) ):    ?>
				<header class="entry-header heading">
						<h2 class="entry-title"><?php echo esc_html( $section_title );?></h2>
				</header>
			<?php endif; ?>
			<?php
			if ( !empty( $feature_category) ) {
				$loop = new WP_Query(array('post_type'=>'post','posts_per_page'=> absint( $number ),'category_name'=>esc_html($feature_category) ) );
			} else{
				$loop = new WP_Query( array( 'post_type'=>'post','posts_per_page'=> absint( $number ), ) );
			}  

			if($loop->have_posts()): ?>
				<div class="row">
					<?php
					while($loop->have_posts()) {
						$loop->the_post();
						$bedroom = get_post_meta($post->ID, 'bedroom', true );
						$bathroom = get_post_meta($post->ID, 'bathroom', true );
						$icons1 = get_post_meta($post->ID, 'icons1', true );
						$icons2 = get_post_meta($post->ID, 'bedroom_num', true );
						$icons3 = get_post_meta($post->ID, 'icons2', true );
						$bathroom_num = get_post_meta($post->ID, 'bathroom_num', true );
						$icons5 = get_post_meta($post->ID, 'icons3', true );
						$icons6 = get_post_meta($post->ID, 'garage', true );
						$icons7 = get_post_meta($post->ID, 'garage_num', true );

						$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'real-estater-feature-image', true );

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
								<?php  }?>
							</figure>
							<header class="entry-header">
								<?php real_estater_tags(); ?>
								<h3 class="entry-title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h3>
							</header>
							<div class="entry-content">
								<p><?php echo esc_html(wp_trim_words(get_the_content(),25,'&hellip;')); ?></p>
							</div>
							<?php if (get_theme_mod( 'real_estater_homepage_feature_section_property_meta','no' )=='yes') {?>
							<footer class="entry-footer">
								<div class="property-meta entry-meta">
									<?php if ( !empty( $bedroom ) || !empty( $icons1 ) || !empty( $icons2 ) || !empty( $icons3 ) ||!empty( $bathroom_num )|| !empty( $icons5 )|| !empty( $icons6 ) || !empty( $icons7 ) ): ?>
										<div class="meta-wrapper">
											<span class="meta-icon">
												<?php echo wp_kses_post( $icons1 ); ?>
											</span>
											<span class="meta-unit">
												<?php echo esc_html($bedroom ); ?>
											</span>
											<span class="meta-value">
												<?php echo wp_kses_post( $icons2 ); ?>
											</span>
										</div>
										<div class="meta-wrapper">
											<span class="meta-icon">
												<?php echo wp_kses_post( $icons3 ); ?>
											</span>
											<span class="meta-unit">
												<?php echo esc_html( $bathroom ); ?>
											</span>
											<span class="meta-value">
												<?php echo absint( $bathroom_num ); ?>
											</span>
										</div>
									<div class="meta-wrapper">
										<span class="meta-icon">
											<?php echo wp_kses_post( $icons5 ); ?>
										</span>
										<span class="meta-unit">
											<?php echo esc_html( $icons6 ); ?>
										</span>
										<span class="meta-value">
											<?php echo absint( $icons7 ); ?>
										</span>
									</div>
									<?php endif; ?>
								</div>
							</footer>
							<?php } ?>
						</div>   
					</div>
					<?php }
					wp_reset_postdata();
					?>
				</div>
			<?php 
			endif; 
			?>       
		</div>
	</section>
<?php }