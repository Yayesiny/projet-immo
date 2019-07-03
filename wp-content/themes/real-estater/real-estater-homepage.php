<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * Template Name: Home Page
 * @package Real_Estater
 */

get_header(); ?>
        <div id="primary" class="content-area">
                <main id="main" class="site-main" role="main">
                        <?php
                         /** About Section **/
                        get_template_part( 'template-parts/sections/section', 'about' );
                         /** Feature  Section **/
                        get_template_part( 'template-parts/sections/section', 'feature' );
                         /** Service  Section **/
                        get_template_part( 'template-parts/sections/section', 'service' );
                        /** Property  Section **/
                        get_template_part( 'template-parts/sections/section', 'property' );
                         /** For Sale   Section **/
                         get_template_part( 'template-parts/sections/section', 'for-sale' );   
                        /** Rent  Section **/
                        get_template_part( 'template-parts/sections/section', 'rent' );    
                        /** Gallery  Section **/
                        get_template_part( 'template-parts/sections/section', 'gallery' );
                        /** Pro  Section **/
                        get_template_part( 'template-parts/sections/section', 'pro' );
                         /** Blog  Section **/
                        get_template_part( 'template-parts/sections/section', 'blog' );
                        ?>
                </main><!-- #main -->
        </div><!-- #primary -->
<?php get_footer();?>
