<?php
/**
 *    Plugin Name: Smart Framework
 *    Plugin URI: http://smartframework.g5plus.net/
 *    Description: Smart Framework is a simple, truly extensible and fully responsive options framework for WordPress themes and plugins.
 *    Version: 1.0
 *    Author: g5plus
 *    Author URI: http://g5plus.net
 *
 *    Text Domain: smart-framework
 *    Domain Path: /languages/
 *
 * @package     SmartFramework
 * @subpackage  Core
 * @author      g5plus
 *
 **/
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('GSF_SmartFramework')) {
	class GSF_SmartFramework
	{

		/*
		 * loader instances
		 */
		public static $instance;

		/**
		 * Init SP_Loader
		 * *******************************************************
		 */
		public static function init()
		{
			if (self::$instance == NULL) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor SP_Loader
		 * *******************************************************
		 */
		public function __construct()
		{
			$this->define_constants();
			$this->load_textdomain();
			$this->includes();
			$this->add_actions();
			$this->add_filters();

			/*
			 * Register auto loader for fields type
			 */
			spl_autoload_register(array($this, 'fields_autoload'));
		}

		/**
		 * Define constant using in PLUGIN
		 * *******************************************************
		 */
		private function define_constants()
		{
			$plugin_dir = str_replace('\\', '/', trailingslashit(dirname(__FILE__)));
			$template_dir = str_replace('\\', '/', trailingslashit(get_template_directory()));
			$plugin_url = '';

			/**
			 * Define plugin DIR
			 */
			if (!defined('GSF_PLUGIN_DIR')) {
				define('GSF_PLUGIN_DIR', plugin_dir_path(__FILE__));
			}

			if (strpos($plugin_dir, $template_dir) === false) {
				$plugin_dir_name = 'smart-framework';
				$plugin_url = trailingslashit(plugins_url($plugin_dir_name));
			} else {
				$sub_template_dir = substr($plugin_dir, strlen($template_dir));
				$plugin_url = trailingslashit(get_template_directory_uri()) . $sub_template_dir;
			}

			$plugin_url = apply_filters('gsf_plugin_url',$plugin_url);

			/**
			 * Define plugin URL
			 */
			if (!defined('GSF_PLUGIN_URL')) {
				define('GSF_PLUGIN_URL', $plugin_url);
			}

			if (!defined('GSF_PLUGIN_RESOURCE_PREFIX')) {
				define('GSF_PLUGIN_RESOURCE_PREFIX', 'gsf_');
			}
			/**
			 * Define Plugin VERSION
			 */
			if (!defined('GSF_VER')) {
				define('GSF_VER', '1.0');
			}
		}

		/**
		 * Includes library for plugin
		 * *******************************************************
		 */
		private function includes()
		{
			/*
			 * Common function in plugin
			 */
			require_once GSF_PLUGIN_DIR . 'inc/functions.php';

			/*
			 * Define post type for Smart Attribute
			 */
			require_once GSF_PLUGIN_DIR . 'inc/post-type.php';

			/*
			 * Define taxonomy for post type
			 */
			require_once GSF_PLUGIN_DIR . 'inc/taxonomy.php';

			/*
			 * Meta box for post type Smart Attribute
			 */
			require_once GSF_PLUGIN_DIR . 'inc/meta-box.php';

			/*
			 * Define term meta custom config
			 */
			require_once GSF_PLUGIN_DIR . 'inc/term-meta.php';

			/*
			 * Meta box for post type Smart Attribute
			 */
			require_once GSF_PLUGIN_DIR . 'inc/theme-options.php';

			/*
			 * Required Field abstract class
			 */
			require_once GSF_PLUGIN_DIR . 'fields/field.php';
		}

		/**
		 * Add action
		 * *******************************************************
		 */
		private function add_actions()
		{
			add_action( 'plugins_loaded', array($this, 'plugins_loaded') );
			add_action('init', array($this, 'theme_init'));
			add_action('wp_enqueue_scripts', array($this, 'load_google_fonts'),1);
			// Edit plugin metalinks
			add_filter( 'plugin_row_meta', array( $this, 'plugin_metalinks' ), null, 2 );
		}

		/**
		 * Add filters
		 * *******************************************************
		 */
		private function add_filters()
		{

		}

		public function plugins_loaded() {
//			$this->load_textdomain();
		}

		public function theme_init()
		{
			if (!defined('GSF_OPTIONS_FONT_USING')) {
				define('GSF_OPTIONS_FONT_USING', 'gsf_font_using');
			}
		}

		public function load_google_fonts()
		{
			/**
			 * Enqueue Google Fonts if used in theme options
			 */
			$google_font_config = gsf_make_webfont_config();
			?>
			<?php if (!empty($google_font_config)): ?>
			<script>
				/* <![CDATA[ */
				if (typeof WebFontConfig === 'undefined') {
					WebFontConfig = new Object();
				}
				WebFontConfig['google'] = {families: [<?php echo ($google_font_config) ?>]};

				(function () {
					var wf = document.createElement('script');
					wf.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js';
					wf.type = 'text/javascript';
					wf.async = 'true';
					var s = document.getElementsByTagName('script')[0];
					s.parentNode.insertBefore(wf, s);
				})();
				/* ]]> */
			</script>
		<?php endif; ?>
			<?php
		}

		public function plugin_metalinks($links, $file) {
			if ( strpos( $file, 'smart-framework.php' ) !== false && is_plugin_active( $file ) ) {
				$new_links = array(
					'<a href="http://smartframework.g5plus.net/docs/" target="_blank">' . __( 'Docs', 'smart-framework' ) . '</a>',
					'<a href="http://smartframework.g5plus.net/support/">' . __( 'Get Support', 'smart-framework' ) . '</a>',
				);

				if ( get_option('gsf_demo_active') ) {
					$new_links[1] .= '<br /><span style="display: block; padding-top: 6px;"><a href="./plugins.php?SmartFrameworkPluginDemo=deactivate" style="color: #b5161f;">' . __( 'Deactivate Demo Mode', 'smart-framework' ) . '</a></span>';
				} else {
					$new_links[1] .= '<br /><span style="display: block; padding-top: 6px;"><a href="./plugins.php?SmartFrameworkPluginDemo=activate" style="color: #b5161f;">' . __( 'Activate Demo Mode', 'smart-framework' ) . '</a></span>';
				}

				$links = array_merge( $links, $new_links );
			}

			return $links;
		}

		/**
		 * Auto load fields
		 * *******************************************************
		 */
		public function fields_autoload($class_name)
		{
			$class = preg_replace('/^GSF_Field_/', '', $class_name);
			if ($class != $class_name) {
				$class = strtolower($class);
				include_once(GSF_PLUGIN_DIR . "fields/{$class}/{$class}.class.php");
			}
		}

		public function load_textdomain() {
			$mofile = GSF_PLUGIN_DIR . 'languages/' . 'smart-framework-' . get_locale() .'.mo';

			if (file_exists($mofile)) {
				load_textdomain('smart-framework', $mofile );
			}
		}
	}

	/**
	 * Instantiate the G5PLUS FRAMEWORK loader class.
	 */
	GSF_SmartFramework::init();
}