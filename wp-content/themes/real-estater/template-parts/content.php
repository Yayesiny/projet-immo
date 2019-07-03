<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Real_Estater
 */
$archive_button_text = esc_html(get_theme_mod('real_estater_archive_submit',esc_html__('Read More','real-estater')));
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
         <?php if (has_post_thumbnail()): ?>
    <figure>
        <a href="<?php the_permalink();?>">
            <?php the_post_thumbnail('real-estater-archive-image');?>
        </a>
    </figure>
    <?php endif; ?>
         <header class="entry-header">
      <?php
      if ( is_singular() ) :
        the_title( '<h2 class="entry-title">', '</h2>' );
      else :
        the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
      endif;
        ?>
        <?php if (get_theme_mod('real_estater_archive_section_date','no')=='yes') { ?>
         <div class="entry-meta">
            <?php real_estater_posted_on();?>
        </div><!-- .entry-meta -->
         <?php }?>   
    </header><!-- .entry-header -->
    <?php
    $bedroom = get_post_meta($post->ID, 'bedroom', true );
    $bathroom = get_post_meta($post->ID, 'bathroom', true );
    $icons1 = get_post_meta($post->ID, 'icons1', true );
    $icons2 = get_post_meta($post->ID, 'bedroom_num', true );
    $icons3 = get_post_meta($post->ID, 'icons2', true );
    $bathroom_num = get_post_meta($post->ID, 'bathroom_num', true );
    $icons5 = get_post_meta($post->ID, 'icons3', true );
    $icons6 = get_post_meta($post->ID, 'garage', true );
    $icons7 = get_post_meta($post->ID, 'garage_num', true ); ?>
    <div class="entry-content">
            <?php
            if ( is_single() ) :
                the_content(); ?>
             <footer class="entry-footer-inner">
                <div class="property-meta-inner entry-meta-inner">

                    <?php  $locations = real_estater_tags(); ?>
                    <?php if(!empty( $locations ) ): ?>
                        <div class="location">
                            <?php echo esc_html($locations) ?>
                        </div>
                    <?php endif; ?> 

                    <?php if ( !empty( $bedroom ) || !empty( $icons1 ) || !empty( $icons2 ) || !empty( $icons3 ) ||!empty( $bathroom_num )|| !empty( $icons5 )|| !empty( $icons6 ) || !empty( $icons7 ) ): ?>
                        <div class="meta-wrapper">
                            <span class="meta-icon-inner">
                                <?php echo wp_kses_post( $icons1 ); ?>
                            </span>
                            <span class="meta-unit-inner">
                                <?php echo wp_kses_post($bedroom ); ?>
                            </span>
                            <span class="meta-value-inner">
                                <?php echo wp_kses_post( $icons2 ); ?>
                            </span>
                        </div>
                        <div class="meta-wrapper-inner">
                            <span class="meta-icon-inner">
                                <?php echo wp_kses_post( $icons3 ); ?>
                            </span>
                            <span class="meta-unit-inner">
                                <?php echo wp_kses_post( $bathroom ); ?>
                            </span>
                            <span class="meta-value-inner">
                                <?php echo wp_kses_post( $bathroom_num ); ?>
                            </span>
                        </div>
                        <div class="meta-wrapper-inner">
                            <span class="meta-icon-inner">
                                <?php echo wp_kses_post( $icons5 ); ?>
                            </span>
                            <span class="meta-unit-inner">
                                <?php echo wp_kses_post( $icons6 ); ?>
                            </span>
                            <span class="meta-value-inner">
                                <?php echo wp_kses_post( $icons7 ); ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
         </footer>
          <?php  else:
                the_excerpt();
                ?>
              <?php if($archive_button_text){ ?>
                     <a class="box-button" href="<?php the_permalink(); ?>"><?php echo esc_html($archive_button_text); ?></a>
              <?php } ?>  
               <?php
        endif;?>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->
