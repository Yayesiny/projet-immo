<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Real_Estater
 */
?>
	</div><!-- #content -->
	<footer id="colophon" class="site-footer">
		<?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' )  || is_active_sidebar( 'footer-4' ) ) : ?>
			<div class="widget-area"> <!-- widget area starting from here -->
				<div class="container">
				 	<?php
					$column_count = 0;
					$class_coloumn =12;
						for ( $i = 1; $i <= 4; $i++ ) {
							if ( is_active_sidebar( 'footer-' . $i ) ) {
								$column_count++;
								$class_coloumn = 12/$column_count;
							}
						} ?>
						<?php $column_class = 'custom-col-' . absint( $class_coloumn );
						for ( $i = 1; $i <= 4 ; $i++ ) {
							if ( is_active_sidebar( 'footer-' . $i ) ) { ?>
								<div class="<?php echo esc_attr( $column_class ); ?>">
									<?php dynamic_sidebar( 'footer-' . $i ); ?>
								</div>
							<?php }
						} ?>	
				</div>
			</div>
     	<?php endif;?> 
		<div class="site-generator"> <!-- site-generator starting from here -->
			<div class="container">
				<span class="copy-right">
					<span class="copyright-text"><?php echo esc_html( get_theme_mod( 'real_estater_copyright_text'));?></span>
					<span class="sep"> | </span>
					<?php 
					printf( esc_html( 'Real Estater by %1$s.', 'real-estater' ), '<a href="'.esc_url( 'https://theme404.com/' ).'" rel="designer">'.esc_html__('404themes', 'real-estater').'</a>' ); ?>
				</span>
				<div class="inline-social-icons social-links">
					<ul>
					    <?php  if (get_theme_mod('real_estater_homepage_footer_section','no')=='yes') {
							 if ( has_nav_menu( 'social-media' ) ) : ?>
								<?php wp_nav_menu( array(
								'theme_location'  => 'social-media',
								'fallback_cb'     => 'wp_page_menu',
								) ); ?>
						<?php endif; ?>
			         <?php } ?>
					</ul>
				</div>
			</div>
		</div>
	</footer><!-- #colophon -->
	<div class="back-to-top">
        <a href="#masthead" title="Go to Top" class="fa-angle-up"></a>       
    </div>
</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>
