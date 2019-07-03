<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Real_Estater
 */

get_header();
?>
	 <div class="site-content" id="homepage">
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">
				<section class="error-404 not-found">
                    <div class="container">
                        <header class="page-header">
                            <h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo; be found.', 'real-estater' ); ?></h1>
                        </header><!-- .page-header -->

                        <div class="page-content">
                            <div class="error-404-section">
                                <div class="error-404-contain-wrap">
                                    <p><?php esc_html_e( 'It looks like nothing was found at this location.', 'real-estater' ); ?></p>
                                    <h2 class="error-404-title"><span><?php echo esc_html__('404', 'real-estater')?></span> <?php echo esc_html__('error', 'real-estater')?></h2>
                                </div>
                                <?php get_search_form(); ?>	
                            </div>
                        </div><!-- .page-content -->
                    </div>
				</section><!-- .error-404 -->

			</main><!-- #main -->
		</div><!-- #primary -->
	</div>
<?php
get_footer();
