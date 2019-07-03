<?php
/**
 * Blog Section 
*/
//Starting Blog Section

if (get_theme_mod( 'real_estater_homepage_blog_section','no' )=='yes') {
    $blog_category = get_theme_mod( 'real_estater_blog_section_category');
    $blogreadmore_button = get_theme_mod( 'real_estater_blog_submit', esc_html__( 'Read More', 'real-estater' ) );
    $number = get_theme_mod( 'real_estater_blog_num',10 );
?>
    <section class="blog-section"> <!-- blog section starting from here -->
        <div class="container">

        <?php $section_title =  get_theme_mod('real_estater_blog_title',esc_html__( 'Blog  Section Title','real-estater') );
         if(!empty( $section_title ) ):    ?>
            <header class="entry-header heading">
                    <h2 class="entry-title"><?php echo esc_html( $section_title );?></h2>
            </header>
         <?php endif; ?>
         
        <?php
        if ( !empty( $blog_category) ) {
            $loop = new WP_Query(array('post_type'=>'post','posts_per_page'=>absint( $number ),'category_name'=>esc_html( $blog_category) ) );
        } else{
            $loop = new WP_Query( array( 'post_type'=>'post','posts_per_page'=>absint( $number ) ) );
        } 
        if($loop->have_posts()): ?>
            <div class="row">
                <?php
                while($loop->have_posts()) {
                $loop->the_post();
                $image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'real-estater-blog-image', true );
                ?>
                <div class="custom-col-4">
                    <article class="post">
                        <figure class="post-featured-image">
                            <img src="<?php echo esc_url($image[0]);?>" />
                        </figure>
                        <header class="entry-header">
                            <h3 class="entry-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                        </header>
                        <div class="post-details">
                             <?php real_estater_posted_on();?>  
                        </div>
                        <div class="entry-content">
                            <p> <?php echo esc_html(wp_trim_words(get_the_content(),25,'&hellip;')); ?></p>
                        </div>
                       <?php  if( !empty( $blogreadmore_button ) ) { ?>
                            <a href="<?php the_permalink(); ?>" class="box-button"><?php echo esc_html($blogreadmore_button); ?></a>
                        <?php } ?>
                    </article>
                </div>
                <?php 
                }
                wp_reset_postdata(); 
                ?>
            </div>
        <?php endif; 
        ?>
        </div>
    </section>
<?php }  ?>    