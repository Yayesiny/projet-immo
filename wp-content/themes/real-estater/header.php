<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Real_Estater
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'real-estater' ); ?></a>
	<header id="masthead" class="site-header">
		  <div class="container">
		  	   <div class="hgroup-wrap">
				  <section class="site-branding"> <!-- site branding starting from here -->
						<?php
						the_custom_logo();
						if ( is_front_page() && is_home() ) :
							?>
							<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
							<?php
						else :
							?>
							<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
							<?php
						endif;
						$real_estater_description = get_bloginfo( 'description', 'display' );
						if ( $real_estater_description || is_customize_preview() ) :
							?>
							<span class="site-description"><?php echo $real_estater_description; /* WPCS: xss ok. */ ?></span>
						<?php endif; ?>
				</section><!-- .site-branding -->
				 <div class="hgroup-right"> <!-- hgroup right starting from here -->
				 	<div id="navbar" class="navbar">  <!-- navbar starting from here -->
				 		<nav id="site-navigation" class="navigation main-navigation">
				 			<div class="menu-top-menu-container">
								<?php
								wp_nav_menu(
								array(
									'theme_location' => 'menu-1',
									'container'		=>	'div',	
									'fallback_cb'    => 'wp_page_menu',
									'container_class' => 'menu-main-menu-container',
										)
									);
								?>
							</div>
				 		</nav>
				 	</div><!-- navbar ends here -->
				 	<div class="calling-info"> <!-- calling info from here -->
				 		<?php
	                     //call to us
	                     $header_callto = get_theme_mod('real_estater_header_callto_text');
	                     	 echo wp_kses_post($header_callto);
	                      ?> 
	                      <?php
	                      $telephone= get_theme_mod('real_estater_header_callto_telephone'); ?>
	                      <a href="tel:<?php echo esc_attr( $telephone ); ?>"><?php echo esc_html( $telephone ); ?> </a>
                 	</div>	
				</div>	
		</div>
	 </div>
	</header><!-- #masthead -->
<?php
if(is_front_page()){
do_action('real_estater_slider_callback_action');
} ?>
<div id="content" class="site-content">
