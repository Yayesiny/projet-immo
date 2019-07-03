<?php
/**
 * Service  Section 
*/
//starting Service Page Service
if (get_theme_mod('real_estater_homepage_service_section','no')=='yes') {

        $service_icons[0] = get_theme_mod('first_service_icon', esc_html__( 'fa-desktop','real-estater' ) );
        $service_icons[1] = get_theme_mod('second_service_icon', esc_html__( 'fa-car','real-estater' ) );
        $service_icons[2] = get_theme_mod('third_service_icon', esc_html__( 'fa-car','real-estater' ) );
        $service_icons[3]= get_theme_mod('forth_service_icon', esc_html__( 'fa-car','real-estater' ) );
        $service_icons[4] = get_theme_mod('fifth_service_icon', esc_html__( 'fa-car','real-estater' ) );
        $service_icons[5]= get_theme_mod('sixth_service_icon', esc_html__( 'fa-car','real-estater' ) );

        $service_page[0] = get_theme_mod( 'real_estater_page_first');
        $service_page[1]= get_theme_mod( 'real_estater_page_second');
        $service_page[2]= get_theme_mod( 'real_estater_page_third');
        $service_page[3]= get_theme_mod( 'real_estater_page_forth');
        $service_page[4]= get_theme_mod( 'real_estater_page_fifth');
        $service_page[5]= get_theme_mod( 'real_estater_page_sixth');

        if( !empty( $service_page ) ): ?>
        <?php $args = array (                                       
        'post__in'               => $service_page ,
        'orderby '    => 'post__in',
        'post_status'     => 'publish',
        'post_type'             => 'page',
        );

        $loop = new WP_Query($args); 

        if ( $loop->have_posts() ) : $cn= -1; ?>
            <section class="service-section">
                <div class="container">
                        <?php $section_title =  get_theme_mod('real_estater_service_title',esc_html__('Service Section','real-estater'));
                        if(!empty( $section_title ) ):    ?>
                            <header class="entry-header heading">
                                    <h2 class="entry-title"><?php echo esc_html( $section_title );?></h2>
                            </header>
                         <?php endif; ?>
                        <div class="row">
                            <?php while ($loop->have_posts()) : $loop->the_post(); $cn++;?>

                                <div class="custom-col-4">
                                    <div class="post">
                                        <div class="post-content-wrapper">
                                            <span class="service-icon">
                                                <i class="fa <?php echo esc_attr($service_icons[$cn]); ?>"></i>
                                            </span>
                                            <div class="post-content-wrap">
                                                <header class="entry-header">
                                                    <h3 class="featured-title">
                                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                    </h3>
                                                    <div class="entry-content">
                                                        <p> <?php echo esc_html(wp_trim_words(get_the_content(),25,'&hellip;')); ?></p>
                                                    </div>
                                                </header>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile;
                            wp_reset_postdata();?>
                        </div>
                </div>
            </section>

        <?php endif;?>

    <?php endif;
}