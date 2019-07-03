<?php
/**
 * Plugin Name: QuadMenu - Divi Mega Menu
 * Plugin URI: http://www.quadmenu.com
 * Description: Integrates QuadMenu with the Divi theme.
 * Version: 1.1.9
 * Author: Divi Mega Menu
 * Author URI: http://www.quadmenu.com
 * License: codecanyon
 * Copyright: 2018 QuadMenu (http://www.quadmenu.com)
 */
if (!defined('ABSPATH')) {
  die('-1');
}

if (!class_exists('QuadMenu_Divi')) {

  final class QuadMenu_Divi {

    function __construct() {

      require_once(ABSPATH . 'wp-admin/includes/plugin.php');

      add_action('admin_notices', array($this, 'notices'));

      add_action('init', array($this, 'hooks'), -30);

      add_action('init', array($this, 'primary_menu'));

      add_filter('quadmenu_locate_template', array($this, 'template'), 10, 5);

      add_filter('quadmenu_default_options', array($this, 'defaults'), 10);

      add_filter('quadmenu_default_themes', array($this, 'themes'), 10);

      add_filter('quadmenu_developer_options', array($this, 'options'), 10);
    }

    function notices() {

      $screen = get_current_screen();

      if (isset($screen->parent_file) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id) {
        return;
      }

      $plugin = 'quadmenu/quadmenu.php';

      if (is_plugin_active($plugin)) {
        return;
      }

      if (is_quadmenu_installed()) {

        if (!current_user_can('activate_plugins')) {
          return;
        }
        ?>
        <div class="error">
          <p>
            <a href="<?php echo wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1', 'activate-plugin_' . $plugin); ?>" class='button button-secondary'><?php _e('Activate QuadMenu', 'quadmenu'); ?></a>
            <?php esc_html_e('QuadMenu Divi not working because you need to activate the QuadMenu plugin.', 'quadmenu'); ?>   
          </p>
        </div>
        <?php
      } else {

        if (!current_user_can('install_plugins')) {
          return;
        }
        ?>
        <div class="error">
          <p>
            <a href="<?php echo wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=quadmenu'), 'install-plugin_quadmenu'); ?>" class='button button-secondary'><?php _e('Install QuadMenu', 'quadmenu'); ?></a>
            <?php esc_html_e('QuadMenu Divi not working because you need to install the QuadMenu plugin.', 'quadmenu'); ?>
          </p>
        </div>
        <?php
      }
    }

    static function is_divi() {

      if (!function_exists('et_divi_fonts_url'))
        return false;

      if (!function_exists('et_get_option'))
        return false;

      return true;
    }

    function hooks() {

      if (self::is_divi()) {

        add_action('wp_enqueue_scripts', array($this, 'enqueue'));

        add_filter('quadmenu_compiler_files', array($this, 'files'));

        add_filter('redux/options/' . QUADMENU_OPTIONS . '/section/quadmenu_location_primary-menu', array($this, 'divi_primary_menu_info'));
      }
    }

    function divi_primary_menu_info($section) {

      $section['fields'][] = array(
          'customizer' => false,
          'id' => $section['id'] . '_alert',
          'type' => 'info',
          'title' => esc_html__('Alert', 'quadmenu'),
          'style' => 'critical',
          'desc' => sprintf(__('This theme is not officially supported. This means the automatic adjustments will not be applied and you will have to make your own integration. If you need more information about the integration process you can check our documentation <a href="%s">here</a>.', 'quadmenu'), admin_url('customize.php?et_customizer_option_set=theme'), 'https://quadmenu.com/documentation/integration/divi/?utm_source=quadmenu_admin'),
          'required' => array(
              array(
                  'primary-menu_theme',
                  '!=',
                  'divi_primary_menu'
              ),
              array(
                  'primary-menu_theme',
                  '!=',
                  'divi'
              ),
              array(
                  'primary-menu_theme',
                  '!=',
                  ''
              )
          ),
      );

      return $section;
    }

    function files($files) {

      $files[] = plugin_dir_url(__FILE__) . 'assets/quadmenu-divi.less';

      return $files;
    }

    function enqueue() {

      if (is_file(QUADMENU_PATH_CSS . 'quadmenu-divi.css')) {
        wp_enqueue_style('quadmenu-divi', QUADMENU_URL_CSS . 'quadmenu-divi.css', array(), filemtime(QUADMENU_PATH_CSS . 'quadmenu-divi.css'), 'all');
      }
    }

    function primary_menu() {

      if (!self::is_divi())
        return;

      if (!function_exists('is_quadmenu_location'))
        return;

      if (!is_quadmenu_location('primary-menu'))
        return;

      if (has_action('et_header_top', 'et_add_mobile_navigation')) {
        remove_action('et_header_top', 'et_add_mobile_navigation');
        add_action('et_header_top', array($this, 'primary_menu_integration'));
      }
    }

    function primary_menu_integration() {

      if (is_customize_preview() || ( 'slide' !== et_get_option('header_style', 'left') && 'fullscreen' !== et_get_option('header_style', 'left') )) {
        ?>
        <div id="et_mobile_nav_menu">
          <div class="mobile_nav closed">
            <span class="select_page"><?php esc_html_e('Select Page', 'Divi'); ?></span>
            <span class="mobile_menu_bar mobile_menu_bar_toggle"></span>
            <div class="et_mobile_menu">
              <?php wp_nav_menu(array('theme_location' => 'primary-menu', 'layout' => 'inherit')); ?>
            </div>
          </div>
        </div>

        <?php
      }
    }

    function template($template, $template_name, $template_path, $default_path, $args) {

      if (!self::is_divi())
        return $template;

      if (et_get_option('header_style') === 'slide') {
        return plugin_dir_path(__FILE__) . '/collapsed.php';
      }

      return $template;
    }

    function defaults($defaults) {

      if (self::is_divi()) {
        $defaults['gutter'] = '30';
        $defaults['screen_sm_width'] = '981';
        $defaults['screen_md_width'] = '1100';
        $defaults['screen_lg_width'] = '1200';
        $defaults['primary-menu_integration'] = et_get_option('primary-menu_integration', true);
        $defaults['primary-menu_theme'] = et_get_option('primary-menu_theme', 'divi');
        $defaults['divi_layout'] = 'collapse';
        $defaults['divi_layout_offcanvas_float'] = 'right';
        $defaults['divi_layout_align'] = 'right';
        $defaults['divi_layout_breakpoint'] = '';
        $defaults['divi_layout_width'] = '0';
        $defaults['divi_layout_width_selector'] = '';
        $defaults['divi_layout_trigger'] = 'hoverintent';
        $defaults['divi_layout_current'] = '';
        $defaults['divi_layout_animation'] = 'quadmenu_btt';
        $defaults['divi_layout_classes'] = '';
        $defaults['divi_layout_sticky'] = '0';
        $defaults['divi_layout_sticky_offset'] = '90';
        $defaults['divi_layout_divider'] = 'hide';
        $defaults['divi_layout_caret'] = 'show';
        $defaults['divi_layout_hover_effect'] = '';
        $defaults['divi_navbar_background'] = 'color';
        $defaults['divi_navbar_background_color'] = 'transparent';
        $defaults['divi_navbar_background_to'] = 'transparent';
        $defaults['divi_navbar_background_deg'] = '17';
        $defaults['divi_navbar_divider'] = 'transparent';
        $defaults['divi_navbar_text'] = '#8585bd';
        $defaults['divi_navbar_height'] = '90';
        $defaults['divi_navbar_width'] = '260';
        $defaults['divi_navbar_mobile_border'] = 'transparent';
        $defaults['divi_navbar_toggle_open'] = '#2ea3f2';
        $defaults['divi_navbar_toggle_close'] = '#2ea3f2';
        $defaults['divi_navbar_logo'] = array(
            'url' => '',
            'id' => '',
            'height' => '',
            'width' => '',
            'thumbnail' => '',
            'title' => '',
            'caption' => '',
            'alt' => '',
            'description' => '',
        );
        $defaults['divi_navbar_logo_height'] = '43';
        $defaults['divi_navbar_logo_bg'] = array(
            'color' => '#ffffff',
            'alpha' => '0',
            'rgba' => 'rgba(255,255,255,0)',
        );
        $defaults['divi_navbar_link_margin'] = array(
            'border-top' => '0px',
            'border-right' => '0px',
            'border-bottom' => '0px',
            'border-left' => '0px',
            'border-style' => '',
            'border-color' => '',
        );
        $defaults['divi_navbar_link_radius'] = array(
            'border-top' => '0px',
            'border-right' => '0px',
            'border-bottom' => '0px',
            'border-left' => '0px',
            'border-style' => '',
            'border-color' => '',
        );
        $defaults['divi_navbar_link_transform'] = 'none';
        $defaults['divi_navbar_link'] = '#666666';
        $defaults['divi_navbar_link_hover'] = '#949494';
        $defaults['divi_navbar_link_bg'] = array(
            'color' => '#ffffff',
            'alpha' => '0',
            'rgba' => 'rgba(255,255,255,0)',
        );
        $defaults['divi_navbar_link_bg_hover'] = array(
            'color' => '#111111',
            'alpha' => '0',
            'rgba' => 'rgba(17,17,17,0)',
        );
        $defaults['divi_navbar_link_hover_effect'] = array(
            'color' => '#09e1c0',
            'alpha' => '1',
            'rgba' => 'rgba(9,225,192,1)',
        );
        $defaults['divi_navbar_button'] = '#ffffff';
        $defaults['divi_navbar_button_bg'] = '#09e1c0';
        $defaults['divi_navbar_button_hover'] = '#ffffff';
        $defaults['divi_navbar_button_bg_hover'] = '#7272ff';
        $defaults['divi_navbar_link_icon'] = '#09e1c0';
        $defaults['divi_navbar_link_icon_hover'] = '#7272ff';
        $defaults['divi_navbar_link_subtitle'] = '#8585bd';
        $defaults['divi_navbar_link_subtitle_hover'] = '#949494';
        $defaults['divi_navbar_badge'] = '#09e1c0';
        $defaults['divi_navbar_badge_color'] = '#ffffff';
        $defaults['divi_sticky_background'] = array(
            'color' => '#ffffff',
            'alpha' => '0',
            'rgba' => 'rgba(255,255,255,0)',
        );
        $defaults['divi_sticky_height'] = '60';
        $defaults['divi_sticky_logo_height'] = '25';
        $defaults['divi_navbar_scrollbar'] = '#09e1c0';
        $defaults['divi_navbar_scrollbar_rail'] = '#ffffff';
        $defaults['divi_dropdown_shadow'] = 'show';
        $defaults['divi_dropdown_margin'] = '5';
        $defaults['divi_dropdown_radius'] = '2';
        $defaults['divi_dropdown_border'] = array(
            'border-top' => '0px',
            'border-right' => '',
            'border-bottom' => '',
            'border-left' => '',
            'border-style' => '',
            'border-color' => '#000000',
        );
        $defaults['divi_dropdown_background'] = array(
            'color' => '#ffffff',
            'alpha' => '1',
            'rgba' => 'rgba(255,255,255,1)',
        );
        $defaults['divi_dropdown_scrollbar'] = '#09e1c0';
        $defaults['divi_dropdown_scrollbar_rail'] = '#ffffff';
        $defaults['divi_dropdown_title'] = '#2e2545';
        $defaults['divi_dropdown_title_border'] = array(
            'border-top' => '1px',
            'border-right' => '',
            'border-bottom' => '',
            'border-left' => '',
            'border-style' => 'solid',
            'border-color' => '#09e1c0',
        );
        $defaults['divi_dropdown_link'] = '#666666';
        $defaults['divi_dropdown_link_hover'] = '#949494';
        $defaults['divi_dropdown_link_bg_hover'] = array(
            'color' => '#f4f4f4',
            'alpha' => '1',
            'rgba' => 'rgba(244,244,244,1)',
        );
        $defaults['divi_dropdown_link_border'] = array(
            'border-top' => '0px',
            'border-right' => '0px',
            'border-bottom' => '0px',
            'border-left' => '0px',
            'border-style' => 'none',
            'border-color' => '#f4f4f4',
        );
        $defaults['divi_dropdown_link_transform'] = 'none';
        $defaults['divi_dropdown_button'] = '#ffffff';
        $defaults['divi_dropdown_button_hover'] = '#ffffff';
        $defaults['divi_dropdown_button_bg'] = '#09e1c0';
        $defaults['divi_dropdown_button_bg_hover'] = '#7272ff';
        $defaults['divi_dropdown_link_icon'] = '#09e1c0';
        $defaults['divi_dropdown_link_icon_hover'] = '#7272ff';
        $defaults['divi_dropdown_link_subtitle'] = '#8585bd';
        $defaults['divi_dropdown_link_subtitle_hover'] = '#949494';
        $defaults['divi_font'] = array(
            'font-family' => 'Open Sans',
            'font-options' => '',
            'google' => '1',
            'font-weight' => '400',
            'font-style' => '',
            'subsets' => '',
            'font-size' => '14px',
        );
        $defaults['divi_navbar_font'] = array(
            'font-family' => 'Open Sans',
            'font-options' => '',
            'google' => '1',
            'font-weight' => '600',
            'font-style' => '',
            'subsets' => '',
            'font-size' => '14px',
        );
        $defaults['divi_dropdown_font'] = array(
            'font-family' => 'Open Sans',
            'font-options' => '',
            'google' => '1',
            'font-weight' => '600',
            'font-style' => '',
            'subsets' => '',
            'font-size' => '14px',
        );
      }

      return $defaults;
    }

    function themes($themes) {

      $themes['divi'] = 'Primary Menu';

      return $themes;
    }

    function options($options) {

      if (self::is_divi()) {

        $options['primary-menu_information'] = false;
        $options['primary-menu_manual'] = false;
        $options['primary-menu_unwrap'] = false;

        // Custom
        // ---------------------------------------------------------------------

        $options['menu_height'] = et_get_option('menu_height', '66');

        $options['minimized_menu_height'] = et_get_option('minimized_menu_height', '40');

        $options['viewport'] = 0;

        $options['divi_theme_title'] = 'Divi Theme';

        $options['divi_navbar_logo'] = array(
            'url' => null
        );

        $options['divi_layout_breakpoint'] = 980;

        $options['divi_layout_width'] = 0;

        $options['divi_layout_width_selector'] = '';

        $options['divi_layout_sticky'] = 0;

        $options['divi_layout_sticky_offset'] = 0;

        $options['divi_layout_hover_effect'] = null;

        $options['divi_mobile_shadow'] = 'hide';

        $options['divi_navbar_background'] = 'color';

        $options['divi_navbar_background_color'] = 'transparent';
        $options['divi_navbar_background_to'] = 'transparent';

        $options['divi_navbar'] = '';
        $options['divi_navbar_height'] = '80';
        $options['divi_navbar_width'] = '260';
      }

      return $options;
    }
    
    static function activation() {

      update_option('_quadmenu_compiler', true);

      if (class_exists('QuadMenu')) {

        QuadMenu_Redux::add_notification('blue', esc_html__('Thanks for install QuadMenu Divi. We have to create the stylesheets. Please wait.', 'quadmenu'));

        QuadMenu_Activation::activation();
      }
    }

  }

  new QuadMenu_Divi();

  register_activation_hook(__FILE__, array('QuadMenu_Divi', 'activation'));
}


if (!function_exists('is_quadmenu_installed')) {

  function is_quadmenu_installed() {

    $file_path = 'quadmenu/quadmenu.php';

    $installed_plugins = get_plugins();

    return isset($installed_plugins[$file_path]);
  }

}
