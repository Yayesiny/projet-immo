<?php
/**
 * Property Section 
*/
//Starting Property Page Service
  if (get_theme_mod('real_estater_homepage_property_section','no')=='yes') {
    $property_icon[0] = get_theme_mod('property_icon_first', esc_html__( 'fa-car','real-estater' ) );
    $property_icon[1] = get_theme_mod('property_icon_second', esc_html__( 'fa-car','real-estater' ) );
    $property_icon[2] = get_theme_mod('property_icon_third', esc_html__( 'fa-car','real-estater' ) );
    $proprty_page[0] = get_theme_mod( 'real_estater_property_page_one', 0 );
    $proprty_page[1] = get_theme_mod( 'real_estater_property_page_two', 0 );
    $proprty_page[2] = get_theme_mod( 'real_estater_property_page_three', 0 );

    $property_readmore_button = get_theme_mod( 'real_estater_property_readmore', esc_html__('Submit Your Property', 'real-estater') );
    $property_first_button_url = get_theme_mod( 'real_estater_theme_readmore_submit_link', esc_url( home_url( '/' ).'#focus' ) );

    if( !empty( $proprty_page ) ): ?>
        <?php $args = array (                                       
            'post__in'               => $proprty_page ,
            'orderby '    => 'post__in',
            'post_status'     => 'publish',
            'post_type'             => 'page',
        );
        $loop = new WP_Query($args);
        if ( $loop->have_posts() ) : $cn= -1; ?>
            
            <section class="submit-property" data-stellar-background-ratio="0.6">
             	<div class="container">  
                    <?php $section_title =  get_theme_mod('real_estater_property_title',esc_html__('Property Section Title','real-estater'));
                    if(!empty( $section_title ) ):    ?>                  
                        <header class="entry-header heading">
                                <h2 class="entry-title"><?php echo esc_html( $section_title );?></h2>
                        </header>
                     <?php endif; ?>
                    <div class="row">
                        <?php while ($loop->have_posts()) : $loop->the_post(); $cn++;?>
                        <div class="custom-col-4">
                            <article>
                                <figure>
                                    <i class="fa <?php echo esc_attr($property_icon[$cn]); ?>"></i>
                                </figure>
                                <h3 class="entry-title">
                                    <?php the_title(); ?>
                                </h3>
                                <div class="entry-content">
                                    <p> <?php the_excerpt(); ?></p>
                                </div>
                            </article>
                        </div>
                        <?php endwhile;
                        wp_reset_postdata();?>
                        <?php 
                        if( !empty( $property_readmore_button ) ) { ?>
                            <div class="submit-button">
                                <a href="<?php echo esc_url($property_first_button_url); ?>" class="box-button">
                                <?php echo esc_html($property_readmore_button); ?>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
             	</div>
            </section>
        <?php endif;
    endif; 
} 