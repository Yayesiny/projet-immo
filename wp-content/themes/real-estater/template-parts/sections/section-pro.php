<?php
/**
 * Promotional Section 
*/
//Starting Promotional Section

if (get_theme_mod('real_estater_homepage_pro_section','no')=='yes') { 

    $real_estater_readmore_button = get_theme_mod( 'real_estater_pro_submit', esc_html__('Submit Now', 'real-estater') );
    $real_estater_first_button_url = get_theme_mod( 'real_estater_theme_submit_link', esc_url( home_url( '/' ).'#focus' ) );?>

        <section class="promotional-bar-section" data-stellar-background-ratio="0.6"> <!-- promotional bal  starting from here --> 
            <div class="container">
                <?php $section_title =  get_theme_mod('real_estater_pro_title',esc_html__('Promotional Section Title','real-estater')); 
                    if(!empty( $section_title ) ):    ?>
                    <div class="promotional-bar-content">
                        <h3 class="entry-title"><?php echo esc_html(get_theme_mod('real_estater_pro_title',esc_html__('Promotional title text','real-estater')));?></h3>  
                    </div>
                <?php endif; ?>
                <?php 
                if( !empty( $real_estater_readmore_button ) ) { ?>
                    <div class="promotional-bar-button">
                        <a href="<?php echo esc_url($real_estater_first_button_url); ?>" class="box-button">
                            <?php echo esc_html($real_estater_readmore_button); ?>
                        </a>
                    </div>
                <?php } ?>
            </div>
    </section> 
    
<?php }