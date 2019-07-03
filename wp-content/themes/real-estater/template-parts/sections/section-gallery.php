<?php
/**
 * Gallery Section 
*/
//Starting Gallery Section

 if (get_theme_mod('real_estater_homepage_gallery_section','no')=='yes') {
	$real_estater_gallery_cat = get_theme_mod('real_estater_gallery_section_category');
	if ( empty( $real_estater_gallery_cat ) ){
		return;
	}	
 	$real_estater_cat_id = get_category_by_slug( $real_estater_gallery_cat )->term_id;

 	$real_estater_port_args = array('post_type' => 'post', 'cat' => absint($real_estater_cat_id), 'order' => 'DESC', 'posts_per_page' => -1);
	$real_estater_port_query = new WP_Query($real_estater_port_args);

	$real_estater_fil_categories = get_categories(array('type' => 'post', 'parent' => $real_estater_cat_id, 'hide_empty' => false));	
?>
	<section class="gallery-section"> <!-- gallery section starting from here --> 
		    <?php
		     $section_title =  get_theme_mod('real_estater_gallery_title',esc_html__('Gallery Section Title','real-estater')); ?>
		      <?php if(!empty( $section_title ) ): ?>
				<header class="entry-header heading">
						<h2 class="entry-title"><?php echo esc_html( $section_title );?></h2>
				</header>
			<?php endif; ?>
	 		<div class="portfolio-gallery-section">
	 			<div class="portfolio-gallery-menu">
	 				<ul>
	 					<li class="filter active" data-filter="*"><?php esc_html_e('All', 'real-estater'); ?></li>
	 					<?php foreach ($real_estater_fil_categories as $real_estater_fil_cat) : ?>
	                    <li class="filter" data-filter=".category-<?php echo esc_attr($real_estater_fil_cat->term_id); ?>"><?php echo esc_html($real_estater_fil_cat->name); ?>
	                        </li>
	 					<?php endforeach; ?>
	 				</ul>
	 			</div>
	 			<div id="mixit-container" class="portfolio-gallery-demo">
	 				 <?php if ($real_estater_port_query->have_posts() && $real_estater_cat_id) : ?> 
	 				 	
		 				 <?php while ($real_estater_port_query->have_posts()) : $real_estater_port_query->the_post(); ?>
		                 <?php
		                 $real_estater_cats = get_the_category();
		                 $real_estater_cat_list = '';
		                foreach ($real_estater_cats as $real_estater_cat) :
		                     if ($real_estater_cat->term_id != $real_estater_cat_id) {
		                         $real_estater_cat_list .= 'category-' . $real_estater_cat->term_id . ' ';
		                        }
		                endforeach;
		                $real_estater_img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'real-estater-gallery-image');
		                $real_estater_img_src = $real_estater_img[0]; ?>

		 				<div class="single-gallery mix  <?php echo esc_attr($real_estater_cat_list); ?>">
		 					<div class="portfolio-single-gallery ">
		 						<figure class="protfolio-image">
		 							<?php if (has_post_thumbnail()) : ?>
		                                 <img src="<?php echo esc_url($real_estater_img_src); ?>" />
		                              <?php endif; ?>
		                              <a href="<?php echo esc_url($real_estater_img_src); ?>">
		 							<div class="gallery-information">
			                         	 <i class="fa fa-search-plus"></i>
			                        </div>
			                        </a>

		 						</figure>

		 					</div>
		 				</div> 				
	 				<?php endwhile; ?>
	 			</div>

	 			<div class="load-portfolio">
	                <a class="load-button" href="<?php echo esc_url(get_category_link( $real_estater_cat_id ));?>"><?php echo esc_html__( 'Load More','real-estater' );?></a>
	             </div>
	             
	           <?php endif; ?>
	 		</div>
	</section>
<?php }