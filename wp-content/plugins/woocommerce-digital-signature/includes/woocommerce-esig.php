<?php
/**
 * 
 * @package ESIG_WOOCOMMERCE
 * @author  Approve me <abushoaib73@gmail.com>
 */

if ( ! defined( 'ABSPATH' ) ) { 
	exit; // Exit if accessed directly
}

if (!class_exists('ESIG_WOOCOMMERCE')) :
class ESIG_WOOCOMMERCE {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.1
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';
	
	

	/**
	 *
	 * Unique identifier for plugin.
	 *
	 * @since     0.1
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'esig-woocommerce';

	/**
	 * Instance of this class.
	 *
	 * @since     1.0.1
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     0.1
	 */
	private function __construct() {

		add_filter('esig_notices_display',array($this,'esig_woo_requirement_msg'),10,1);
                
                add_filter('admin_notices',array($this,'esig_woo_requirement_msg'));
		
		add_action( 'admin_init',array($this, 'esign_woo_after_install') );
		
		add_filter( 'plugin_row_meta', array($this,'about_page_action_link'),10,2 );
                
                // add action for 
                add_action("after_plugin_row",array($this,"esig_woo_core_missing"),10,2);
                
                add_action('admin_menu', array(&$this, 'esig_woocommerce_adminmenu'));
                
                add_action('admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
	}
        
        /**
	 * This is method esig_usr_adminmenu
	 *   Create a admin menu for esinature roles . 
	 * @return mixed This is the return value description
	 */    
	public function esig_woocommerce_adminmenu()
	{
		add_submenu_page(null, __( 'About', 'esig' ), __( 'About', 'esig' ), 'read', 'esign-woocommerce-about', array(&$this, 'woo_about_page'));
		
		if(!function_exists('WP_E_Sig'))
                {
                       
                    //add_menu_page('E-Signature','E-Signature', 'read',"woo-esign",array(&$this, 'woo_about_page'), plugins_url('assets/images/pen_icon.svg',__FILE__));
                    if (empty ($GLOBALS['admin_page_hooks']['esign'] ) )
                    {
                      add_menu_page('E-Signature','E-Signature', 'read',"esign",array(&$this, 'esig_core_page'),ESIGN_WOO_URI .'/assets/images/pen_icon.svg');
                    }

                      add_submenu_page("esign","Woocommerce E-signature","Woocommerce E-signature", 'read',"esign-woo",array(&$this, 'woo_about_page'));

                    return;
                }
				
		
		$esigrole = new WP_E_Esigrole();
		if($esigrole->esig_current_user_can('have_licenses'))
		{
			add_submenu_page(null, __('Esig Woocommerce Settings Page','esig-woocommerce'), __('Esig Woocommerce Settings Page','esig-woocommerce'), 'read','esign-woocommerce', array(&$this, 'esign_woocommerce_view'));
			add_submenu_page(null, __('Esig Woocommerce Core not installed','esig-woocommerce'), __('Esig Woocommerce Core not installed','esig-woocommerce'), 'read','esign-not-core', array(&$this, 'esign_not_core_view'));
		}
		
	}
	
	
	public function woo_about_page()
	{
		
		include_once(ESIGN_WOO_PATH ."/admin/views/woocommerce-esign-about.php");
		
	}
    
    public function esig_core_page()
	{
		
		include_once(ESIGN_WOO_PATH ."/admin/views/esig-core-about.php");
		
	}
	
	
	public function esign_not_core_view(){
		
		$template_data=array(
			"ESIGN_ASSETS_DIR_URI"=>ESIGN_ASSETS_DIR_URI,
			
			);

		$branding_template = ESIGN_WOO_PATH ."admin/views/esig-not-core-view.php";
		WP_E_View::instance()->renderPartial('', $template_data, true, '', $branding_template);
		
	}
	public function esig_woo_core_missing($plugin_file, $plugin_data)
        {
            
            if(function_exists('WP_E_Sig'))
                     return;
            
            
            
            if ( strpos( $plugin_file, 'woocommerce-esig.php' ) !== false ) 
                {
                    echo '<tr class="plugin-update-tr active">

       <td colspan="3" class="plugin-update colspanchange">
       <div class="update-message">
       This plugin is missing some required plugins by <a href="https://www.approveme.com/?utm_source=wprepo&utm_medium=link&utm_campaign=woocommerce">Approve Me</a> Which is available to Business License holders <a href="https://www.approveme.com/email-limited-pricing/?utm_source=wprepo&utm_medium=link&utm_campaign=woocommerce">Purchase License Today</a>

       </div> </div></td></tr>' ; 
            }
             
        }
	
	public function about_page_action_link( $links, $file ) {
	  
	    
	    if ( strpos( $file, 'woocommerce-esig.php' ) !== false ) {
	        $new_links = array(
	            '<a href="'. get_admin_url(null, 'index.php?page=esign-woocommerce-about') .'">'. __('Need help getting started?','esig-woocommerce') .'</a>'
	        );
	    
	        $links = array_merge( $links, $new_links );
	    }
	    
	    return $links;
	    
	}
	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     0.1
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		$screen = get_current_screen();
		$admin_screens = array(
			'dashboard_page_esign-woocommerce-about',
			
			);
	
		if (in_array($screen->id, $admin_screens)) {
                    
			wp_enqueue_style( $this->plugin_slug .'-admin-styles-one', ESIGN_WOO_URI . '/assets/css/esign-woocommerce.css', array());
		}

	}
	
	public function esign_woo_after_install() 
	{
		
		if( ! is_admin() )
                      return;
		
		// Delete the transient
		//delete_transient( '_esign_activation_redirect' );
		if(delete_transient( '_esign_woo_redirect' )) 
		{
			wp_safe_redirect( admin_url( 'index.php?page=esign-woocommerce-about' ));
			exit;
		}
		
	}
	
	public function esig_woo_requirement_msg($msg)
	{
            
		if (!is_plugin_active( 'woocommerce/woocommerce.php' )) 
		{
			$msg .=_e('<div class="error"><span class="icon-esig-alert"></span><h4>Hi there! It looks like the <a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce plugin</a> is not active. You need to activate the WooCommerce plugin in order to use the E-signature WooCommerce add-on features.<br></h4></div>','esig-woocommerce');
		}
                
		if (!class_exists('ESIG_SAD_Admin')) 
		{
			$msg .=_e('<div class="error"><span class="icon-esig-alert"></span><h4>Hi there! It looks like the E-signature <a href="https://www.approveme.com/downloads/stand-alone-documents/?utm_source=wprepo&utm_medium=link&utm_campaign=woocommerce">Stand Alone Document</a> plugin is not active. You need to activate the Stand Alone Document add-on to use the E-signature WooCommerce Features.<br></h4></div>','esig-woocommerce');
		}
		
                
		return $msg;
		
	}
        
        public function esig_woo_requirement()
	{
                $msg = '';
		if (!is_plugin_active( 'woocommerce/woocommerce.php' )) 
		{
			$msg .=_e('<div class="error"><span class="icon-esig-alert"></span><h4>Hi there! It looks like the <a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce plugin</a> is not active. You need to activate the WooCommerce plugin in order to use the E-signature WooCommerce add-on features.<br></h4></div>','esig-woocommerce');
		}
		if (!class_exists('ESIG_SAD_Admin')) 
		{
			$msg .=_e('<div class="error"><span class="icon-esig-alert"></span><h4>Hi there! It looks like the E-signature <a href="https://www.approveme.com/downloads/stand-alone-documents/?utm_source=wprepo&utm_medium=link&utm_campaign=woocommerce">Stand Alone Document</a> plugin is not active. You need to activate the Stand Alone Document add-on to use the E-signature WooCommerce Features.<br></h4></div>','esig-woocommerce');
		}
		
                
		echo $msg;
		
	}
	
	/**
	 * Returns the plugin slug.
	 *
	 * @since     0.1
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Returns an instance of this class.
	 *
	 * @since     0.1
	 * @return    object    A single instance of this class.
	 */
	 
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since     0.1
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	 
	public static function activate( $network_wide ) {
		self::single_activate();
		
		set_transient( '_esign_woo_redirect', true, 30 );	
		
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since     0.1
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
		self::single_deactivate();
	}

	

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since     0.1
	 */
	private static function single_activate() {
		//@TODO: Define activation functionality here
         if(get_option('WP_ESignature__Auto_Add_My_Signature_documentation'))
        {
			update_option('WP_ESignature__woocommerce_documentation','http://wordpress.org/plugins/woocommerce-digital-signature/');
            
        }
        else
        {
           
			add_option('WP_ESignature__woocommerce_documentation','http://wordpress.org/plugins/woocommerce-digital-signature/');
        }
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since     0.1
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here
	}

	
	
	
}
endif;
