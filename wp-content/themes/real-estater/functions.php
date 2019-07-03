<?php
/**
 * Real Estater functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Real_Estater
 */

if ( ! function_exists( 'real_estater_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function real_estater_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Real Estater, use a find and replace
		 * to change 'real-estater' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'real-estater', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		add_image_size('real-estater-slider-image', 1900, 900, true);
		add_image_size('real-estater-blog-image', 800, 500, true);
		add_image_size('real-estater-feature-image', 960, 579, true);
		add_image_size('real-estater-for-sale-image', 1000, 500, true);
		add_image_size('real-estater-gallery-image', 800, 500, true);
		add_image_size('real-estater-rent-image', 900, 500, true);
		add_image_size('real-estater-archive-image', 960, 579, true);

		

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'real-estater' ),
			'social-media'  => esc_html__( 'Social Media', 'real-estater' ),
		) );
        
		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'real_estater_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
		// Add Support for Block Styles
		add_theme_support( 'wp-block-styles');

		// Add Support for wide and full align image
		add_theme_support( 'align-wide');
	}
endif;
add_action( 'after_setup_theme', 'real_estater_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function real_estater_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'real_estater_content_width', 640 );
}
add_action( 'after_setup_theme', 'real_estater_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */

function real_estater_widgets_init() {

	register_sidebar( array(
		'name'          => esc_html__( 'Right Sidebar', 'real-estater' ),
		'id'            => 'real-estater-sidebar-right',
		'description'   => esc_html__( 'Add widgets here.', 'real-estater' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );	

	register_sidebar( array(
		'name'          => esc_html__( 'Left Sidebar', 'real-estater' ),
		'id'            => 'real-estater-sidebar-left',
		'description'   => esc_html__( 'Add widgets here.', 'real-estater' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
        'name' =>sprintf( esc_html__( 'Footer %d', 'real-estater' ), 1 ),
        'id' => 'footer-1',
        'description' => esc_html__('Appears in the buttom of footer area','real-estater'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );

    register_sidebar( array(
        'name' => sprintf( esc_html__( 'Footer  %d', 'real-estater' ), 2 ),
        'id' => 'footer-2',
        'description' => esc_html__('Appears in the buttom of footer area','real-estater'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );
    register_sidebar( array(
        'name' => sprintf( esc_html__( 'Footer  %d', 'real-estater' ), 3 ),
        'id' => 'footer-3',
        'description' => esc_html__('Appears in the buttom of footer area','real-estater'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );
    register_sidebar( array(
        'name' => sprintf( esc_html__( 'Footer %d', 'real-estater' ), 4),
        'id' => 'footer-4',
        'description' => esc_html__('Appears in the buttom of footer area','real-estater'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );
}
add_action( 'widgets_init', 'real_estater_widgets_init' );
/**
 * Enqueue scripts and styles.
 */
function real_estater_scripts() {

	$fonts_url = real_estater_fonts_url();	

	if ( ! empty( $fonts_url ) ) {
		wp_enqueue_style( 'real-estater-google-fonts', $fonts_url, array(), null );
	}	

	// Load fontawesome
	wp_enqueue_style( 'font-awesome', get_template_directory_uri().'/assest/css/font-awesome.min.css', array(), '4.4.0' );
	
    // Load OWl Carousel
	wp_enqueue_style( 'owl-carousel', get_template_directory_uri() . '/assest/css/owl.carousel.min.css', '', '2.2.0' );

	// Load OWl Carousel
	wp_enqueue_style( 'owl-theme', get_template_directory_uri().'/assest/css/owl.theme.css', array(), 'v2.2.0' );

	//Fancy Box
    wp_enqueue_style('fancybox',get_template_directory_uri().'/assest/css/jquery.fancybox.css');
    
	//meanmenu
	wp_enqueue_style( 'meanmenu', get_template_directory_uri().'/assest/css/meanmenu.min.css', array(), '2.0.7' );
 

	wp_enqueue_style( 'real-estater-style', get_stylesheet_uri() );

	wp_enqueue_script( 'jquery-fancybox', get_template_directory_uri() . '/assest/js/jquery.fancybox.js', array('jquery'), 'v3.0.8', true );	

   //navigations js
	wp_enqueue_script( 'real-estater-navigation', get_template_directory_uri() . '/assest/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'real-estater-skip-link-focus-fix', get_template_directory_uri() . '/assest/js/skip-link-focus-fix.js', array(), '20151215', true );

	//jquery-isotopes
	wp_enqueue_script( 'jquery-isotope', get_template_directory_uri() . '/assest/js/isotope.min.js', array('jquery'), 'v3.0.6', true );
	

	//owl carousel
	wp_enqueue_script( 'jquery-owl-carousel', get_template_directory_uri() . '/assest/js/owl.carousel.min.js', array('jquery'), 'v2.2.1', true );	
	
	//stellar
	wp_enqueue_script( 'jquery-stellar', get_template_directory_uri() . '/assest/js/stellar.js', array('jquery'), 'v0.6.2', true );

    //jquery-meanmenu
	wp_enqueue_script( 'jquery-meanmenu', get_template_directory_uri() . '/assest/js/jquery.meanmenu.js', array('jquery'), 'v2.0.8', true );


	
	wp_enqueue_script( 'real-estater-custom', get_template_directory_uri() . '/assest/js/custom.js', array(), '20170905', true );	

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'real_estater_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Custom Customizer file.
 */

require get_template_directory() . '/inc/real-estater-customizer.php';

/**
 * Load Custom functions file.
 */

require get_template_directory() . '/inc/real-estater-functions.php';

/** Metaboxe **/

require get_template_directory() . '/inc/real-estater-metabox.php';

/**
 * One Click Demo Import
 */
require_once trailingslashit( get_template_directory() ) . '/demo-content/demo-content-import.php';


/**
 * One Click Demo Import
 */
require_once trailingslashit( get_template_directory() ) . '/inc/class-tgm-plugin-activation.php';

/** Customizer Classes **/
require get_template_directory() . '/inc/customizer-classes.php';

