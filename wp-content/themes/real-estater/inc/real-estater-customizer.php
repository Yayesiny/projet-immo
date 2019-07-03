<?php
/**
 *  Real Estater Theme Customizer Custom
 *
 * @package Real_Estater 
 */

/**
 * Add new options the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function real_estater_custom_customize_register( $wp_customize ) {
    require get_template_directory() . '/inc/real-estater-sanitizer.php';

    
    /*/** Theme Info section **/
	$wp_customize->add_section(
	    'real_estater_theme_info_section',
	    array(
	        'title'		=> esc_html__( 'Theme Info', 'real-estater' ),
	        'priority'  => 1,
	    )
	);
	// More Themes
	$wp_customize->add_setting(
	    'real_estater_pro_information', 
	    array(
	        'type'              => 'theme_info',
	        'capability'        => 'edit_theme_options',
	        'sanitize_callback' => 'esc_attr',
	    )
	);
	$wp_customize->add_control( new real_estater_Theme_Info( 
	    $wp_customize ,
	    'real_estater_pro_information',
	        array(
	          'label' => esc_html__( 'Real Estater Pro Theme' , 'real-estater' ),
	          'section' => 'real_estater_theme_info_section',
	        )
	    )
	);


    //Real Estater Category Posts List.
    $real_estater_category_lists 	=	real_estater_category_lists();

    /****************  Add Deafult  Pannel   ***********************/
	$wp_customize->add_panel('real_estater_default_setups',array(
		'priority' => '10',
		'capability' => 'edit_theme_options',
		'theme_supports' => '',
		'title' => esc_html__('Default Settings','real-estater'),
	));

	/****************  Add Default Sections to General Panel ************/
	$wp_customize->get_section('title_tagline')->panel = 'real_estater_default_setups'; //priority 20
	$wp_customize->get_section('colors')->panel = 'real_estater_default_setups'; //priority 40
	$wp_customize->get_section('background_image')->panel = 'real_estater_default_setups'; //priority 80
	$wp_customize->get_section('static_front_page')->panel = 'real_estater_default_setups'; //priority 120


	/****************  Header Call To Section Starting  ************/
    $wp_customize->add_section('real_estater_header_setting_callto',
    	array(
			'title' => esc_html__(' Header Call To','real-estater'),
			'priority' => '10',
			'panel' => 'real_estater_default_setups'
		)
	);
	$wp_customize->add_setting('real_estater_header_callto_text',
		array(
			'default' => '',
			'sanitize_callback' => 'real_estater_sanitize_text',
		)
	);

	$wp_customize->add_control('real_estater_header_callto_text',
		array(
			'type' => 'textarea',
			'label' => esc_html__(' Header Call To Text  ','real-estater'),
			'description' => esc_html__('Enter text or HTML for call to actions','real-estater'),
			'section' => 'real_estater_header_setting_callto'
		)
	);

	$wp_customize->add_setting('real_estater_header_callto_telephone',
		array(
			'default' => '',
			'sanitize_callback' => 'real_estater_sanitize_text',
		)
	);

	$wp_customize->add_control('real_estater_header_callto_telephone',
		array(
			'type' => 'text',
			'label' => esc_html__(' Header Telephone ','real-estater'),
			'description' => esc_html__('Fill The Telephone Number For Header Call To','real-estater'),
			'section' => 'real_estater_header_setting_callto'
		)
	);

    /*********** Adding the Homepage Setup Panel ****************/

    $wp_customize->add_panel('real_estater_homepage_setups',
    	array(
			'priority' => '15',
			'capability' => 'edit_theme_options',
			'title' => esc_html__('Front Page Settings','real-estater'),
		)
    );
	/***********************************  Starting SLider Section **********************/
	$wp_customize->add_section('real_estater_slider_setups',
		array(
			'priority' => '1',
			'capability' => 'edit_theme_options',
			'title' => esc_html__('Slider Section','real-estater'),
			'description' => esc_html__('Manage Slides for the site','real-estater'),
			'panel' => 'real_estater_homepage_setups'
		)
	);
	//slider Section Enable/Disable
	$wp_customize->add_setting('real_estater_homepage_slider_section',
        array(
            'default'           =>  'no',
            'sanitize_callback' =>  'real_estater_sanitize_radio_yes_no',
            )
        );
	
  	$wp_customize->add_control('real_estater_homepage_slider_section',array(
	    'description'   =>  esc_html__('Enable/Disable This Section','real-estater'),
	    'section'       =>  'real_estater_slider_setups',
	    'setting'       =>  'real_estater_homepage_slider_section',
	    'priority'      =>  1,
	    'type'          =>  'radio',
	    'choices'        =>  array(
	        'yes'   =>  esc_html__('Yes','real-estater'),
	        'no'    =>  esc_html__('No','real-estater')
	        )
	    )
	);
	//Select Category For Slider
	 $wp_customize->add_setting('real_estater_slider_section_category',
	 	array(
	        'default'           =>  '0',
	        'sanitize_callback' =>  'real_estater_sanitize_category_select',
        )
    );
	 $wp_customize->add_control('real_estater_slider_section_category',
	 	array(
	        'priority'      =>  2,
	        'label'         =>  esc_html__('Select category for slider','real-estater'),
	        'section'       =>  'real_estater_slider_setups',
	        'setting'       =>  'real_estater_slider_section_category',
	        'type'          =>  'select',
	        'choices'       =>  $real_estater_category_lists
	    )
    );		            
    //Slider Read More Text
    $wp_customize->add_setting('real_estater_slider_readmore',
    	array(
	        'default'           =>  esc_html__('Learn More','real-estater'),
	        'sanitize_callback' =>  'sanitize_text_field',
        )
    );

    $wp_customize->add_control('real_estater_slider_readmore',
    	array(
	        'priority'      =>  3,
	        'label'         =>  esc_html__('Learn More Text','real-estater'),
	        'section'       =>  'real_estater_slider_setups',
	        'setting'       =>  'real_estater_slider_readmore',
	        'type'          =>  'text',  
        )                                     
    );

	 //Slider read more text
    $wp_customize->add_setting('real_stater_slider_contact_now',
    	array(
	        'default'           =>  esc_html__('Contact Us','real-estater'),
	        'sanitize_callback' =>  'sanitize_text_field',
        )
    );

    $wp_customize->add_control('real_stater_slider_contact_now',
    	array(
	        'priority'      =>  5,
	        'label'         =>  esc_html__('Contact Us','real-estater'),
	        'section'       =>  'real_estater_slider_setups',
	        'setting'       =>  'real_stater_slider_contact_now',
	        'type'          =>  'text',  
        )                                     
    );

    // Read More link
	$wp_customize->add_setting('real_estater_contact_us_link', 
		array(
			'default' => '',
			'sanitize_callback' => 'esc_url_raw',		
		)
	);
	$wp_customize->add_control('real_estater_contact_us_link',
		array(
			'priority'      =>  6,
			'type' => 'text',
			'label' => esc_html__('Contact Us Link','real-estater'),
			'section' => 'real_estater_slider_setups',
			'setting' => 'real_estater_contact_us_link'
		)
	);

  	// Slider Post Number Count
  	$wp_customize->add_setting('real_estater_slider_num', 
  		array(
	    	'default' => '5',
	        'sanitize_callback' => 'real_estater_integer_sanitize',
  		)
	);
    
    $wp_customize->add_control('real_estater_slider_num',
    	array(
	        'type' => 'number',
	        'label' => esc_html__('No. of Slider','real-estater'),
	        'section' => 'real_estater_slider_setups',
	        'setting' => 'real_estater_slider_num',
	        'input_attrs' => array(
			    'min' => 1,
			    'max' => 9,
		   	),
   		)
   	);
	/********* Starting About Us Section ****************/
	$wp_customize->add_section('real_estater_about_section',
		array(
			'priority' => '2',
			'capability' => 'edit_theme_options',
			'theme_supports' => '',
			'title' => esc_html__('About Us Section','real-estater'),
			'description' => esc_html__('Manage About Us Section ','real-estater'),
			'panel' => 'real_estater_homepage_setups'
		)
	);

	//slider Section Enable/Disable
	$wp_customize->add_setting('real_estater_homepage_about_section',
    	array(
	        'default'           =>  'no',
	        'sanitize_callback' =>  'real_estater_sanitize_radio_yes_no',
        )
    );
  	$wp_customize->add_control('real_estater_homepage_about_section',
  		array(
	        'description'   =>  esc_html__('Enable/Disable This Section','real-estater'),
	        'section'       =>  'real_estater_about_section',
	        'setting'       =>  'real_estater_homepage_about_section',
	        'priority'      =>  1,
	        'type'          =>  'radio',
	        'choices'        =>  array(
	            'yes'   =>  esc_html__('Yes','real-estater'),
	            'no'    =>  esc_html__('No','real-estater')
            )
        )
    );
    $wp_customize->add_setting('real_estater_page_about',
        array(
	        'default'           =>  0,
	        'sanitize_callback' =>  'real_stater_sanitize_dropdown_pages',
        )
    );

    $wp_customize->add_control('real_estater_page_about',
    array(
        'priority'=>    25,
        'label'   =>    esc_html__( 'Select Page','real-estater' ),
        'description' => esc_html__( 'Page Selection About  Us.','real-estater' ),
        'section' =>    'real_estater_about_section',
        'setting' =>    'real_estater_page_about',
        'type'    =>    'dropdown-pages',
        )                                     
    );
   
    /*********** Starting Feature Section ************************/

    $wp_customize->add_section('real_estater_feature_section',
    	array(
			'priority' => '3',
			'capability' => 'edit_theme_options',
			'theme_supports' => '',
			'title' => esc_html__('Feature  Section','real-estater'),
			'description' => esc_html__('Manage Feature  for the site','real-estater'),
			'panel' => 'real_estater_homepage_setups'
		)
	);

	//Feature Section Enable/Disable
	$wp_customize->add_setting('real_estater_homepage_feature_section',
	    array(
	        'default'           =>  'no',
	        'sanitize_callback' =>  'real_estater_sanitize_radio_yes_no',
        )
    );
  	$wp_customize->add_control('real_estater_homepage_feature_section',
  		array(
	        'description'   =>  esc_html__('Enable/Disable This Section','real-estater'),
	        'section'       =>  'real_estater_feature_section',
	        'setting'       =>  'real_estater_homepage_feature_section',
	        'priority'      =>  1,
	        'type'          =>  'radio',
	        'choices'        =>  array(
	            'yes'   =>  esc_html__('Yes','real-estater'),
	            'no'    =>  esc_html__('No','real-estater')
            )
        )
    );
	$wp_customize->add_setting('real_estater_feature_title',
		array(
			'default'           =>  esc_html__('Feature Section','real-estater'),
			'sanitize_callback' =>  'sanitize_text_field',
			'transport'     =>  'refresh',
      	)
  	);

    $wp_customize->add_control('real_estater_feature_title',
    	array(
	      'priority'      =>  2,
	      'label'         =>  esc_html__('Feature title text','real-estater'),
	      'section'       =>  'real_estater_feature_section',
	      'setting'       =>  'real_estater_feature_title',
	      'type'          =>  'text',  
      	)                                     
  	);
    //Select Category For Feature Section
	$wp_customize->add_setting('real_estater_feature_section_category',
		array(
	        'default'           =>  '0',
	        'sanitize_callback' =>  'real_estater_sanitize_category_select',
        )
    );
	 $wp_customize->add_control('real_estater_feature_section_category',
	 	array(
	        'priority'      =>  4,
	        'label'         =>  esc_html__('Select category for Renr Section','real-estater'),
	        'section'       =>  'real_estater_feature_section',
	        'setting'       =>  'real_estater_feature_section_category',
	        'type'          =>  'select',
	        'choices'       =>  $real_estater_category_lists
        )
    );
	 
	// Feature Post Number Count
  	$wp_customize->add_setting('real_estater_feature_num', array(
    'default' => '3',
        'sanitize_callback' => 'real_estater_integer_sanitize',
  		));
    
    $wp_customize->add_control('real_estater_feature_num',array(
        'type' => 'number',
        'label' => esc_html__('No. of Post on Feature Section ','real-estater'),
        'section' => 'real_estater_feature_section',
        'setting' => 'real_estater_feature_num',
        'input_attrs' => array(
	    'min' => 1,
	    'max' => 9,
	  ),
   		 )); 

	//Feature Section Enable/Disable
	$wp_customize->add_setting('real_estater_homepage_feature_section_property_meta',
	    array(
	        'default'           =>  'no',
	        'sanitize_callback' =>  'real_estater_sanitize_radio_yes_no',
        )
    );
  	$wp_customize->add_control('real_estater_homepage_feature_section_property_meta',
  		array(
	        'description'   =>  esc_html__('Enable/Disable Property Meta','real-estater'),
	        'section'       =>  'real_estater_feature_section',
	        'setting'       =>  'real_estater_homepage_feature_section_property_meta',
	        'priority'      =>  7,
	        'type'          =>  'radio',
	        'choices'        =>  array(
	            'yes'   =>  esc_html__('Yes','real-estater'),
	            'no'    =>  esc_html__('No','real-estater')
            )
        )
    );

	/************************************ Starting Service Section **********************************************/

	 $wp_customize->add_section('real_estater_service_section',array(
		'priority' => '4',
		'capability' => 'edit_theme_options',
		'theme_supports' => '',
		'title' => esc_html__('Service  Section','real-estater'),
		'description' => esc_html__('Manage Service  Section  for the site','real-estater'),
		'panel' => 'real_estater_homepage_setups'
		));
    //Service Section Enable/Disable
	$wp_customize->add_setting('real_estater_homepage_service_section',
                    array(
		                'default'           =>  'no',
		                'sanitize_callback' =>  'real_estater_sanitize_radio_yes_no',
		                )
		            );
  	$wp_customize->add_control('real_estater_homepage_service_section',array(
		                'description'   =>  esc_html__('Enable/Disable This Section','real-estater'),
		                'section'       =>  'real_estater_service_section',
		                'setting'       =>  'real_estater_homepage_service_section',
		                'priority'      =>  1,
		                'type'          =>  'radio',
		                'choices'        =>  array(
		                    'yes'   =>  esc_html__('Yes','real-estater'),
		                    'no'    =>  esc_html__('No','real-estater')
		                    )
		                )
		            );
  	$wp_customize->add_setting(
                  'real_estater_service_title',array(
                      'default'           =>  esc_html__('Service Section','real-estater'),
                      'sanitize_callback' =>  'sanitize_text_field',
                      'transport'     =>  'refresh',
                      )
                  );

    $wp_customize->add_control(
                 'real_estater_service_title',array(
                      'priority'      =>  2,
                      'label'         =>  esc_html__('Servce title text','real-estater'),
                      'section'       =>  'real_estater_service_section',
                      'setting'       =>  'real_estater_service_title',
                      'type'          =>  'text',  
                      )                                     
                  );
	$wp_customize->add_setting( 'real_estater_page_first', array(
	  'capability'            => 'edit_theme_options',
	  'default'               => '',
	  'sanitize_callback'     => 'real_stater_sanitize_dropdown_pages'
	) );

	$wp_customize->add_control( 'real_estater_page_first', array(
	  'label'                 =>  esc_html__( 'First Page For Service Section', 'real-estater' ),
	  'description'           =>  esc_html__('Select page for Service  Section', 'real-estater'),
	  'section'               => 'real_estater_service_section',
	  'type'                  => 'dropdown-pages',
	  'priority'              => 3,
	  'settings'              => 'real_estater_page_first',
	) );
	$wp_customize->add_setting( 'real_estater_page_second', array(
	  'capability'            => 'edit_theme_options',
	  'default'               => '',
	  'sanitize_callback'     => 'real_stater_sanitize_dropdown_pages'
	) );

	$wp_customize->add_control( 'real_estater_page_second', array(
	  'label'                 =>  esc_html__( 'Second Page For Service Section', 'real-estater' ),
	  'description'           =>  esc_html__('Select page for Service  Section', 'real-estater'),
	  'section'               => 'real_estater_service_section',
	  'type'                  => 'dropdown-pages',
	  'priority'              => 4,
	  'settings'              => 'real_estater_page_second',
	) );
	$wp_customize->add_setting( 'real_estater_page_third', array(
	  'capability'            => 'edit_theme_options',
	  'default'               => '',
	  'sanitize_callback'     => 'real_stater_sanitize_dropdown_pages'
	) );

	$wp_customize->add_control( 'real_estater_page_third', array(
	  'label'                 =>  esc_html__( ' Third Page For Service Section', 'real-estater' ),
	  'description'           =>  esc_html__('Select page for Service  Section', 'real-estater'),
	  'section'               => 'real_estater_service_section',
	  'type'                  => 'dropdown-pages',
	  'priority'              => 5,
	  'settings'              => 'real_estater_page_third',
	) );
	    $wp_customize->add_setting( 'real_estater_page_forth', array(
	  'capability'            => 'edit_theme_options',
	  'default'               => '',
	  'sanitize_callback'     => 'real_stater_sanitize_dropdown_pages'
	) );

	$wp_customize->add_control( 'real_estater_page_forth', array(
	  'label'                 =>  esc_html__( 'Forth Page For Service Section', 'real-estater' ),
	  'description'           =>  esc_html__('Select page for Service  Section', 'real-estater'),
	  'section'               => 'real_estater_service_section',
	  'type'                  => 'dropdown-pages',
	  'priority'              => 6,
	  'settings'              => 'real_estater_page_forth',
	) );
    $wp_customize->add_setting( 'real_estater_page_fifth', array(
	  'capability'            => 'edit_theme_options',
	  'default'               => '',
	  'sanitize_callback'     => 'real_stater_sanitize_dropdown_pages'
	) );

	$wp_customize->add_control( 'real_estater_page_fifth', array(
	  'label'                 =>  esc_html__( 'Fifth Page For Service Section', 'real-estater' ),
	  'description'           =>  esc_html__('Select page for Service  Section', 'real-estater'),
	  'section'               => 'real_estater_service_section',
	  'type'                  => 'dropdown-pages',
	  'priority'              => 7,
	  'settings'              => 'real_estater_page_fifth',
	) );
	$wp_customize->add_setting( 'real_estater_page_sixth', array(
	  'capability'            => 'edit_theme_options',
	  'default'               => '',
	  'sanitize_callback'     => 'real_stater_sanitize_dropdown_pages'
	) );

	$wp_customize->add_control( 'real_estater_page_sixth', array(
	  'label'                 =>  esc_html__( 'Sixth Page For Service Section', 'real-estater' ),
	  'description'           =>  esc_html__('Select page for Service  Section', 'real-estater'),
	  'section'               => 'real_estater_service_section',
	  'type'                  => 'dropdown-pages',
	  'priority'              => 8,
	  'settings'              => 'real_estater_page_sixth',
	) );
	$wp_customize->add_setting('first_service_icon',
		array(
		    'default' => 'fa-desktop',
		    'sanitize_callback' => 'sanitize_text_field',		     
	     )
    );
    $wp_customize->add_control( new real_esatater_Customize_Icons_Control($wp_customize,'first_service_icon', 
         array(
	        'type' 		=> 'real_estater_icons',	                
	        'label' 	=> esc_html__( 'First Service Icon', 'real-estater' ),
	        'description' 	=> esc_html__( 'Choose the icon from lists.', 'real-estater' ),
	        'section' 	=> 'real_estater_service_section',
	        'priority'  => '9',
	    )	            	
    ));
	$wp_customize->add_setting('second_service_icon',
		array(
		    'default' => 'fa-car ',
		    'sanitize_callback' => 'sanitize_text_field',
		    
     	)
    );
    $wp_customize->add_control( new real_esatater_Customize_Icons_Control($wp_customize,'second_service_icon', 
		array(
			'type' 		=> 'real_estater_icons',	                
			'label' 	=> esc_html__( 'Second Service Icons', 'real-estater' ),
			'description' 	=> esc_html__( 'Choose the icon from lists.', 'real-estater' ),
			'section' 	=> 'real_estater_service_section',
			'priority'  => '10',
		)	            	
	));

	$wp_customize->add_setting('third_service_icon',
		array(
			'default' => 'fa-car ',
			'sanitize_callback' => 'sanitize_text_field',
			
		)
	);
    $wp_customize->add_control( new real_esatater_Customize_Icons_Control($wp_customize,'third_service_icon', 
			array(
				'type' 		=> 'real_estater_icons',	                
				'label' 	=> esc_html__( 'Third Service Icons', 'real-estater' ),
				'description' 	=> esc_html__( 'Choose the icon from lists.', 'real-estater' ),
				'section' 	=> 'real_estater_service_section',
				'priority'  => '11',
			)	            	
		));
	$wp_customize->add_setting('forth_service_icon',
		array(
			'default' => 'fa-car ',
			'sanitize_callback' => 'sanitize_text_field',
			
		)
	);
    $wp_customize->add_control( new real_esatater_Customize_Icons_Control($wp_customize,'forth_service_icon', 
		array(
		'type' 		=> 'real_estater_icons',	                
		'label' 	=> esc_html__( 'Forth Service Icons', 'real-estater' ),
		'description' 	=> esc_html__( 'Choose the icon from lists.', 'real-estater' ),
		'section' 	=> 'real_estater_service_section',
		'priority'  => '12',
		)	            	
	));
	$wp_customize->add_setting('fifth_service_icon',
		array(
			'default' => 'fa-car ',
			'sanitize_callback' => 'sanitize_text_field',
			
		)
	);
    $wp_customize->add_control( new real_esatater_Customize_Icons_Control($wp_customize,'fifth_service_icon', 
		array(
			'type' 		=> 'real_estater_icons',	                
			'label' 	=> esc_html__( 'Fifth Service Icons', 'real-estater' ),
			'description' 	=> esc_html__( 'Choose the icon from lists.', 'real-estater' ),
			'section' 	=> 'real_estater_service_section',
			'priority'  => '13',
		)	            	
	));
	$wp_customize->add_setting('sixth_service_icon',
		array(
			'default' => 'fa-car ',
			'sanitize_callback' => 'sanitize_text_field',
			
		)
	);
    $wp_customize->add_control( new real_esatater_Customize_Icons_Control($wp_customize,'sixth_service_icon', 
		array(
			'type' 		=> 'real_estater_icons',	                
			'label' 	=> esc_html__( 'Sixth Service Icons', 'real-estater' ),
			'description' 	=> esc_html__( 'Choose the icon from lists.', 'real-estater' ),
			'section' 	=> 'real_estater_service_section',
			'priority'  => '14',
		)	            	
	));

    /******************* Starting Property Section **************************/
	$wp_customize->add_section('real_estater_property_section',
		array(
			'priority' => '5',
			'capability' => 'edit_theme_options',
			'theme_supports' => '',
			'title' => esc_html__('Property  Section','real-estater'),
			'description' => esc_html__('Manage property  Section  for the site','real-estater'),
			'panel' => 'real_estater_homepage_setups'
		)
	);

    //Property Section Enable/Disable
	$wp_customize->add_setting('real_estater_homepage_property_section',
    	array(
	        'default'           =>  'no',
	        'sanitize_callback' =>  'real_estater_sanitize_radio_yes_no',
        )
    );
  	$wp_customize->add_control('real_estater_homepage_property_section',
  		array(
	        'description'   =>  esc_html__('Enable/Disable This Section','real-estater'),
	        'section'       =>  'real_estater_property_section',
	        'setting'       =>  'real_estater_homepage_property_section',
	        'priority'      =>  1,
	        'type'          =>  'radio',
	        'choices'        =>  array(
	            'yes'   =>  esc_html__('Yes','real-estater'),
	            'no'    =>  esc_html__('No','real-estater')
	            )
        )
    );
	$wp_customize->add_setting('real_estater_property_title',
		array(
		      'default'           =>  esc_html__('Property Section Title','real-estater'),
		      'sanitize_callback' =>  'sanitize_text_field',
		      'transport'     =>  'refresh',
      	)
  	);

    $wp_customize->add_control('real_estater_property_title',
    	array(
			'priority'      =>  2,
			'label'         =>  esc_html__('property title text','real-estater'),
			'section'       =>  'real_estater_property_section',
			'setting'       =>  'real_estater_property_title',
			'type'          =>  'text',  
	    )                                     
  	);
	$wp_customize->add_setting( 'real_estater_property_page_one', 
		array(
		  'capability'            => 'edit_theme_options',
		  'default'               => '',
		  'sanitize_callback'     => 'real_stater_sanitize_dropdown_pages'
		) 
	);

	$wp_customize->add_control( 'real_estater_property_page_one',
		array(
		  'label'                 =>  esc_html__( ' Page For Property Section', 'real-estater' ),
		  'description'           =>  esc_html__('Select page for Property  Section', 'real-estater'),
		  'section'               => 'real_estater_property_section',
		  'type'                  => 'dropdown-pages',
		  'priority'              => 4,
		  'settings'              => 'real_estater_property_page_one',
		) 
	);
	$wp_customize->add_setting( 'real_estater_property_page_two',
		array(
			'capability'            => 'edit_theme_options',
			'default'               => '',
			'sanitize_callback'     => 'real_stater_sanitize_dropdown_pages'
		)
	);

	$wp_customize->add_control( 'real_estater_property_page_two',
		array(
			'label'                 =>  esc_html__( ' Page For Property Section', 'real-estater' ),
			'description'           =>  esc_html__('Select page for Property  Section', 'real-estater'),
			'section'               => 'real_estater_property_section',
			'type'                  => 'dropdown-pages',
			'priority'              => 5,
			'settings'              => 'real_estater_property_page_two',
		) 
	);
	$wp_customize->add_setting( 'real_estater_property_page_three',
		array(
			'capability'            => 'edit_theme_options',
			'default'               => '',
			'sanitize_callback'     => 'real_stater_sanitize_dropdown_pages'
		) 
	);

	$wp_customize->add_control( 'real_estater_property_page_three',
		array(
			'label'                 =>  esc_html__( ' Page For Property Section', 'real-estater' ),
			'description'           =>  esc_html__('Select page for Property  Section', 'real-estater'),
			'section'               => 'real_estater_property_section',
			'type'                  => 'dropdown-pages',
			'priority'              => 6,
			'settings'              => 'real_estater_property_page_three',
		) 
	);
    $wp_customize->add_setting('property_icon_first',
    	array(
		    'default' => 'fa-car ',
		    'sanitize_callback' => 'sanitize_text_field',
		    
     	)
    );
    $wp_customize->add_control( new real_esatater_Customize_Icons_Control( $wp_customize,'property_icon_first', 
		array(
			'type' 		=> 'real_estater_icons',	                
			'label' 	=> esc_html__( 'Property Icons First', 'real-estater' ),
			'description' 	=> esc_html__( 'Choose the icon from lists.', 'real-estater' ),
			'section' 	=> 'real_estater_property_section',
			'priority'  => '7',
		)	            	
	));
	$wp_customize->add_setting('property_icon_second',
		array(
			'default' => 'fa-car ',
			'sanitize_callback' => 'sanitize_text_field',
			
		)
	);
    $wp_customize->add_control( new real_esatater_Customize_Icons_Control($wp_customize,'property_icon_second', 
		array(
			'type' 		=> 'real_estater_icons',	                
			'label' 	=> esc_html__( 'Property Icons Second', 'real-estater' ),
			'description' 	=> esc_html__( 'Choose the icon from lists.', 'real-estater' ),
			'section' 	=> 'real_estater_property_section',
			'priority'  => '8',
			)	            	
		)
	);
	$wp_customize->add_setting('property_icon_third',
		array(
		    'default' => 'fa-car ',
		    'sanitize_callback' => 'sanitize_text_field',
		    
	    )
    );
    $wp_customize->add_control( new real_esatater_Customize_Icons_Control($wp_customize,'property_icon_third', 
        array(
	        'type' 		=> 'real_estater_icons',	                
	        'label' 	=> esc_html__( 'Property Icons Third', 'real-estater' ),
	        'description' 	=> esc_html__( 'Choose the icon from lists.', 'real-estater' ),
	        'section' 	=> 'real_estater_property_section',
	          'priority'  => '9',
        )	            	
    ));

    // Property Read More Text
    $wp_customize->add_setting('real_estater_property_readmore',
    	array(
	        'default'           =>  esc_html__('Submit Your Property','real-estater'),
	        'sanitize_callback' =>  'sanitize_text_field',
        )
    );

    $wp_customize->add_control('real_estater_property_readmore',
		array(
            'priority'      =>  10,
            'label'         =>  esc_html__('Submit Your Property','real-estater'),
            'section'       =>  'real_estater_property_section',
            'setting'       =>  'real_estater_property_readmore',
            'type'          =>  'text',  
        )                                     
    );

    // Read More Link
	$wp_customize->add_setting('real_estater_theme_readmore_submit_link', array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw',
		
		));
	$wp_customize->add_control('real_estater_theme_readmore_submit_link',array(
		 'priority'      =>  11,
		'type' => 'text',
		'label' => esc_html__('Submit Your Property Link','real-estater'),
		'section' => 'real_estater_property_section',
		'setting' => 'real_estater_theme_readmore_submit_link'
		));

	/************************************ Starting  For Sale Section **********************************************/

	$wp_customize->add_section('real_estater_for_sale_section',
		array(
			'priority' => '6',
			'capability' => 'edit_theme_options',
			'theme_supports' => '',
			'title' => esc_html__('For Sale Section','real-estater'),
			'description' => esc_html__('Manage For Sale  Section  for the site','real-estater'),
			'panel' => 'real_estater_homepage_setups'
		)
	);
	//Property Section Enable/Disable
	$wp_customize->add_setting('real_estater_homepage_sale_section',
		array(
			'default'           =>  'no',
			'sanitize_callback' =>  'real_estater_sanitize_radio_yes_no',
		)
	);
  	$wp_customize->add_control('real_estater_homepage_sale_section',
		array(
			'description'   =>  esc_html__('Enable/Disable This Section','real-estater'),
			'section'       =>  'real_estater_for_sale_section',
			'setting'       =>  'real_estater_homepage_sale_section',
			'priority'      =>  1,
			'type'          =>  'radio',
			'choices'        =>  array(
				'yes'   =>  esc_html__('Yes','real-estater'),
				'no'    =>  esc_html__('No','real-estater')
			)
		)
	);
	$wp_customize->add_setting('real_estater_for_sale_title',
		array(
			'default'           =>  esc_html__('For Sale Section','real-estater'),
			'sanitize_callback' =>  'sanitize_text_field',
			'transport'     =>  'refresh',
		)
	);

    $wp_customize->add_control('real_estater_for_sale_title',
		array(
			'priority'      =>  2,
			'label'         =>  esc_html__('For Sale title text','real-estater'),
			'section'       =>  'real_estater_for_sale_section',
			'setting'       =>  'real_estater_for_sale_title',
			'type'          =>  'text',  
		)                                     
	);
  	//Select Category For Sale Section
	 $wp_customize->add_setting('real_estater_for_sale_section_category',
		array(
			'default'           =>  '0',
			'sanitize_callback' =>  'real_estater_sanitize_category_select',
		)
	);
	$wp_customize->add_control('real_estater_for_sale_section_category',
		array(
			'priority'      =>  4,
			'label'         =>  esc_html__('Select category for sale Section','real-estater'),
			'section'       =>  'real_estater_for_sale_section',
			'setting'       =>  'real_estater_for_sale_section_category',
			'type'          =>  'select',
			'choices'       =>  $real_estater_category_lists
		)
	);
	 // For Sale Post Number Count
  	$wp_customize->add_setting('real_estater_for_sale_num',
		array(
			'default' => '3',
			'sanitize_callback' => 'real_estater_integer_sanitize',
		)
	);
    
    $wp_customize->add_control('real_estater_for_sale_num',
		array(
			'type' => 'number',
			'label' => esc_html__('No. of Post on For Sale Section ','real-estater'),
			'section' => 'real_estater_for_sale_section',
			'setting' => 'real_estater_for_sale_num',
			'input_attrs' => array(
				'min' => 1,
				'max' => 9,
			),
		)
	); 
	//For Sale Section Enable/Disable
	$wp_customize->add_setting('real_estater_homepage_sale_section_property_meta',
	    array(
	        'default'           =>  'no',
	        'sanitize_callback' =>  'real_estater_sanitize_radio_yes_no',
	    )
	);
		$wp_customize->add_control('real_estater_homepage_sale_section_property_meta',
			array(
	        'description'   =>  esc_html__('Enable/Disable Property Meta','real-estater'),
	        'section'       =>  'real_estater_for_sale_section',
	        'setting'       =>  'real_estater_homepage_sale_section_property_meta',
	        'priority'      =>  7,
	        'type'          =>  'radio',
	        'choices'        =>  array(
	            'yes'   =>  esc_html__('Yes','real-estater'),
	            'no'    =>  esc_html__('No','real-estater')
	        )
	    )
	);
	/************************************ Starting Rent  Section **********************************************/
	$wp_customize->add_section('real_estater_rent_section',
		array(
			'priority' => '7',
			'capability' => 'edit_theme_options',
			'theme_supports' => '',
			'title' => esc_html__('For Rent Section','real-estater'),
			'description' => esc_html__('Manage For Rent Section  for your site','real-estater'),
			'panel' => 'real_estater_homepage_setups'
		)	
	);

	//Property Section Enable/Disable
	$wp_customize->add_setting('real_estater_homepage_rent_section',
		array(
			'default'           =>  'no',
			'sanitize_callback' =>  'real_estater_sanitize_radio_yes_no',
		)
	);

	$wp_customize->add_control('real_estater_homepage_rent_section',
		array(
			'description'   =>  esc_html__('Enable/Disable This Section','real-estater'),
			'section'       =>  'real_estater_rent_section',
			'setting'       =>  'real_estater_homepage_rent_section',
			'priority'      =>  1,
			'type'          =>  'radio',
			'choices'        =>  array(
				'yes'   =>  esc_html__('Yes','real-estater'),
				'no'    =>  esc_html__('No','real-estater')
			)
		)
	);

	//  For Rent  Section Title
	$wp_customize->add_setting('real_estater_for_rent_title',
		array(
			'default'           =>  esc_html__('For Rent Section','real-estater'),
			'sanitize_callback' =>  'sanitize_text_field',
			'transport'     =>  'refresh',
		)
	);

	$wp_customize->add_control('real_estater_for_rent_title',
		array(
			'priority'      =>  2,
			'label'         =>  esc_html__('For Rent title text','real-estater'),
			'section'       =>  'real_estater_rent_section',
			'setting'       =>  'real_estater_for_rent_title',
			'type'          =>  'text',  
		)                                     
	);

	//Select Category For Rent  Section
	$wp_customize->add_setting('real_estater_for_rent_section_category',
		array(
			'default'           =>  '0',
			'sanitize_callback' =>  'real_estater_sanitize_category_select',
		)
	);

	$wp_customize->add_control('real_estater_for_rent_section_category',
		array(
			'priority'      =>  4,
			'label'         =>  esc_html__('Select category for Renr Section','real-estater'),
			'section'       =>  'real_estater_rent_section',
			'setting'       =>  'real_estater_for_rent_section_category',
			'type'          =>  'select',
			'choices'       =>  $real_estater_category_lists
		)
	);

	// For  Rent  Post Number Count
	$wp_customize->add_setting('real_estater_rent_num',
		array(
			'default' => '3',
			'sanitize_callback' => 'real_estater_integer_sanitize',
		)
	);

	$wp_customize->add_control('real_estater_rent_num',
		array(
			'type' => 'number',
			'label' => esc_html__('No. of Post on Rent Section ','real-estater'),
			'section' => 'real_estater_rent_section',
			'setting' => 'real_estater_rent_num',
			'input_attrs' => array(
				'min' => 1,
				'max' => 9,
			),
		)
	);
	$wp_customize->add_setting('real_estater_homepage_feature_section_rent_meta',
	    array(
	        'default'           =>  'no',
	        'sanitize_callback' =>  'real_estater_sanitize_radio_yes_no',
        )
    );
  	$wp_customize->add_control('real_estater_homepage_feature_section_rent_meta',
  		array(
	        'description'   =>  esc_html__('Enable/Disable Property Meta','real-estater'),
	        'section'       =>  'real_estater_rent_section',
	        'setting'       =>  'real_estater_homepage_feature_section_rent_meta',
	        'priority'      =>  7,
	        'type'          =>  'radio',
	        'choices'        =>  array(
	            'yes'   =>  esc_html__('Yes','real-estater'),
	            'no'    =>  esc_html__('No','real-estater')
            )
        )
    );
/************************************ Starting Gallery Section **********************************************/

	$wp_customize->add_section('real_estater_gallery_section',
		array(
			'priority' => '8',
			'capability' => 'edit_theme_options',
			'theme_supports' => '',
			'title' => esc_html__('Gallery Section','real-estater'),
			'description' => esc_html__('Manage Gallery Section  for the site','real-estater'),
			'panel' => 'real_estater_homepage_setups'
		)
	);

	//Gallery Section Enable/Disable
	$wp_customize->add_setting('real_estater_homepage_gallery_section',
		array(
			'default'           =>  'no',
			'sanitize_callback' =>  'real_estater_sanitize_radio_yes_no',
		)
	);

	$wp_customize->add_control('real_estater_homepage_gallery_section',
		array(
			'description'   =>  esc_html__('Enable/Disable This Section','real-estater'),
			'section'       =>  'real_estater_gallery_section',
			'setting'       =>  'real_estater_homepage_gallery_section',
			'priority'      =>  1,
			'type'          =>  'radio',
			'choices'        =>  array(
				'yes'   =>  esc_html__('Yes','real-estater'),
				'no'    =>  esc_html__('No','real-estater')
			)
		)
	); 
	$wp_customize->add_setting('real_estater_gallery_title',
		array(
			'default'           =>  esc_html__('Gallery  Section Title','real-estater'),
			'sanitize_callback' =>  'sanitize_text_field',
			'transport'     =>  'refresh',
		)
	);

	$wp_customize->add_control('real_estater_gallery_title',
		array(
			'priority'      =>  2,
			'label'         =>  esc_html__('Gallery title text','real-estater'),
			'section'       =>  'real_estater_gallery_section',
			'setting'       =>  'real_estater_gallery_title',
			'type'          =>  'text',  
		)                                     
	);

	//Select Category For Choose City Section
	$wp_customize->add_setting('real_estater_gallery_section_category',
		array(
			'default'           =>  '0',
			'sanitize_callback' =>  'real_estater_sanitize_category_select',
		)
	);
	$wp_customize->add_control('real_estater_gallery_section_category',
		array(
			'priority'      =>  3,
			'label'         =>  esc_html__('Select category for gallery Section','real-estater'),
			'section'       =>  'real_estater_gallery_section',
			'setting'       =>  'real_estater_gallery_section_category',
			'type'          =>  'select',
			'choices'       =>  $real_estater_category_lists
		)
	);

	/************************************ Starting Promotional Section **********************************************/

	$wp_customize->add_section('real_estater_pro_section',
		array(
			'priority' => '10',
			'capability' => 'edit_theme_options',
			'theme_supports' => '',
			'title' => esc_html__('Promotional Section','real-estater'),
			'description' => esc_html__('Manage Promotional  Section  for the site','real-estater'),
			'panel' => 'real_estater_homepage_setups'
		)
	);

	// Promotional Section Enable/Disable
	$wp_customize->add_setting('real_estater_homepage_pro_section',
		array(
			'default'           =>  'no',
			'sanitize_callback' =>  'real_estater_sanitize_radio_yes_no',
		)
	);
	$wp_customize->add_control('real_estater_homepage_pro_section',
		array(
			'description'   =>  esc_html__('Enable/Disable This Section','real-estater'),
			'section'       =>  'real_estater_pro_section',
			'setting'       =>  'real_estater_homepage_pro_section',
			'priority'      =>  1,
			'type'          =>  'radio',
			'choices'        =>  array(
				'yes'   =>  esc_html__('Yes','real-estater'),
				'no'    =>  esc_html__('No','real-estater')
			)
		)
	);

	$wp_customize->add_setting('real_estater_pro_title',
		array(
			'default'           =>  esc_html__('Promotional Section Title','real-estater'),
			'sanitize_callback' =>  'sanitize_text_field',
			'transport'     =>  'refresh',
			)
		);
	$wp_customize->add_control('real_estater_pro_title',
		array(
			'priority'      =>  2,
			'label'         =>  esc_html__('Promotional title text','real-estater'),
			'section'       =>  'real_estater_pro_section',
			'setting'       =>  'real_estater_pro_title',
			'type'          =>  'text',  
		)                                     
	);

	//Promotional Read More Text
	$wp_customize->add_setting('real_estater_pro_submit',
		array(
			'default'           =>  esc_html__('Submit Now ','real-estater'),
			'sanitize_callback' =>  'sanitize_text_field',
		)
	);

	$wp_customize->add_control('real_estater_pro_submit',
		array(
			'priority'      =>  3,
			'label'         =>  esc_html__('Submit Now ','real-estater'),
			'section'       =>  'real_estater_pro_section',
			'setting'       =>  'real_estater_pro_submit',
			'type'          =>  'text',  
		)                                     
	);

	// Read More Link
	$wp_customize->add_setting('real_estater_theme_submit_link',
		array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control('real_estater_theme_submit_link',
		array(
			'priority'      =>  4,
			'type' => 'text',
			'label' => esc_html__('Learn More Text Link','real-estater'),
			'section' => 'real_estater_pro_section',
			'setting' => 'real_estater_theme_submit_link'
		)
	);

	/************************************ Starting Blog Section **********************************************/

	$wp_customize->add_section('real_estater_blog_section',
		array(
			'priority' => '11',
			'capability' => 'edit_theme_options',
			'theme_supports' => '',
			'title' => esc_html__('Blog Section','real-estater'),
			'description' => esc_html__('Manage Blog Section  Section  for the site','real-estater'),
			'panel' => 'real_estater_homepage_setups'
		)
	);

	//Blog Section Enable/Disable
	$wp_customize->add_setting('real_estater_homepage_blog_section',
		array(
			'default'           =>  'no',
			'sanitize_callback' =>  'real_estater_sanitize_radio_yes_no',
		)
	);
	$wp_customize->add_control('real_estater_homepage_blog_section',
		array(
			'description'   =>  esc_html__('Enable/Disable This Section','real-estater'),
			'section'       =>  'real_estater_blog_section',
			'setting'       =>  'real_estater_homepage_blog_section',
			'priority'      =>  1,
			'type'          =>  'radio',
			'choices'        =>  array(
				'yes'   =>  esc_html__('Yes','real-estater'),
				'no'    =>  esc_html__('No','real-estater')
			)
		)
	); 

	//Blog Page Selection
	$wp_customize->add_setting('real_estater_blog_title',
		array(
			'default'           =>  esc_html__('Blog Section Title','real-estater'),
			'sanitize_callback' =>  'sanitize_text_field',
			'transport'     =>  'refresh',
		)
	);

	$wp_customize->add_control('real_estater_blog_title',
		array(
			'priority'      =>  2,
			'label'         =>  esc_html__('Blog Title Text','real-estater'),
			'section'       =>  'real_estater_blog_section',
			'setting'       =>  'real_estater_blog_title',
			'type'          =>  'text',  
		)                                     
	);

	//Select Category For Choose City Section
	$wp_customize->add_setting('real_estater_blog_section_category',
		array(
			'default'           =>  '0',
			'sanitize_callback' =>  'real_estater_sanitize_category_select',
		)
	);

	$wp_customize->add_control('real_estater_blog_section_category',
		array(
			'priority'      =>  3,
			'label'         =>  esc_html__('Select category for this section','real-estater'),
			'section'       =>  'real_estater_blog_section',
			'setting'       =>  'real_estater_blog_section_category',
			'type'          =>  'select',
			'choices'       =>  $real_estater_category_lists
		)
	);
	//Promotional Read More Text
	$wp_customize->add_setting('real_estater_blog_submit',
		array(
			'default'           =>  esc_html__('Read More ','real-estater'),
			'sanitize_callback' =>  'sanitize_text_field',
		)
	);

	$wp_customize->add_control('real_estater_blog_submit',
		array(
			'priority'      =>  4,
			'label'         =>  esc_html__('Read More ','real-estater'),
			'section'       =>  'real_estater_blog_section',
			'setting'       =>  'real_estater_blog_submit',
			'type'          =>  'text',  
		)                                     
	);
	// For  Blog Post Number Count
	$wp_customize->add_setting('real_estater_blog_num',
		 array(
			'default' => '5',
			'sanitize_callback' => 'real_estater_integer_sanitize',
		)
	);

	$wp_customize->add_control('real_estater_blog_num',
		array(
			'type' => 'number',
			'label' => esc_html__('No. of Post on Blog Section ','real-estater'),
			'section' => 'real_estater_blog_section',
			'setting' => 'real_estater_blog_num',
			'input_attrs' => array(
				'min' => 1,
				'max' => 9,
			),
		)
	);  

	/************************************Adding the Footer Setting Panel**********************************************/
	$wp_customize->add_panel('real_estater_footer_setting',
		array(
			'priority' => '16',
			'capability' => 'edit_theme_options',
			'theme_supports' => '',
			'title' => esc_html__('Footer Settings','real-estater'),
		)
	);	 

	/************************************ Starting Footer Social  Section **********************************************/
	$wp_customize->add_section('real_estater_footer_social',
		array(
			'priority' => '1',
			'capability' => 'edit_theme_options',
			'theme_supports' => '',
			'title' => esc_html__('Footer Social Section','real-estater'),
			'description' => esc_html__('Manage Footer  Section  for Social','real-estater'),
			'panel' => 'real_estater_footer_setting'
		)
	);

	//Footer Section Enable/Disable
	$wp_customize->add_setting('real_estater_homepage_footer_section',
		array(
			'default'           =>  'no',
			'sanitize_callback' =>  'real_estater_sanitize_radio_yes_no',
		)
	);

	$wp_customize->add_control('real_estater_homepage_footer_section',
		array(
			'description'   =>  esc_html__('Enable/Disable Footer Social Icons On Footer Section','real-estater'),
			'section'       =>  'real_estater_footer_social',
			'setting'       =>  'real_estater_homepage_footer_section',
			'priority'      =>  1,
			'type'          =>  'radio',
			'choices'        =>  array(
				'yes'   =>  esc_html__('Yes','real-estater'),
				'no'    =>  esc_html__('No','real-estater')
			)
		)
	); 

	$wp_customize->add_section('real_estater_footer_cpy',
		array(
			'priority' => '1',
			'capability' => 'edit_theme_options',
			'theme_supports' => '',
			'title' => esc_html__('Footer Copy Right Section','real-estater'),
			'description' => esc_html__('Manage Footer  Section  for Footer Copy Right','real-estater'),
			'panel' => 'real_estater_footer_setting'
		)
	);

	$wp_customize->add_setting('real_estater_copyright_text', 
		array(
			'default' => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control('real_estater_copyright_text',
		array(
			'type' => 'text',
			'label' => esc_html__( 'Copyright Text', 'real-estater' ),
			'section' => 'real_estater_footer_cpy',
			'priority' => 5
		)
	);

    //Archive Page Settings panel
    $wp_customize->add_panel('real_estater_archive_setting', array(
      'capabitity' => 'edit_theme_options',
      'priority' => 38,
      'title' => __('Archive Page Settings', 'real-estater')
   ));
   //Archive pages sidebar section
    $wp_customize->add_section(
        'real_estater_archive_setting_sidebar',
        array(
          'title' => __('Sidebar Layout', 'real-estater'),
          'panel' => 'real_estater_archive_setting'
          )
      );
     $wp_customize->add_section(
        'real_estater_archive_setting',
        array(
          'title' => __('Archive Inner Setting', 'real-estater'),
          'panel' => 'real_estater_archive_setting'
          )
      );
    $wp_customize->add_setting(
	      'real_estater_archive_setting_sidebar_option',
	      array(
	        'default' =>  'sidebar-right',
	        'sanitize_callback' =>  'real_estater_radio_sanitize_archive_sidebar'
        )
      );  
    $wp_customize->add_control(
      'real_estater_archive_setting_sidebar_option',
     	 array(
        'description' => __('Choose the sidebar Layout for the archive page','real-estater'),
        'section' => 'real_estater_archive_setting_sidebar',
        'type'    =>  'radio',
        'choices' =>  array(
            'sidebar-left' =>  __('Sidebar Left','real-estater'),
            'sidebar-right' =>  __('Sidebar Right','real-estater'),
            'sidebar-both' =>  __('Sidebar Both','real-estater'),
            'sidebar-no' =>  __('Sidebar No','real-estater'),
          )
        )
      );
	$wp_customize->add_setting('real_estater_archive_section_date',
	    array(
	        'default'           =>  'no',
	        'sanitize_callback' =>  'real_estater_sanitize_radio_yes_no',
	        )
	    );
	$wp_customize->add_control('real_estater_archive_section_date',array(
	        'description'   =>  esc_html__('Enable/Disable Date','real-estater'),
	        'section'       =>  'real_estater_archive_setting',
	        'setting'       =>  'real_estater_archive_section_date',
	        'priority'      =>  3,
	        'type'          =>  'radio',
	        'choices'        =>  array(
	            'yes'   =>  esc_html__('Yes','real-estater'),
	            'no'    =>  esc_html__('No','real-estater')
	            )
	        )
	    );
	$wp_customize->add_setting(
	            'real_estater_archive_submit',array(
	                'default'           =>  esc_html__('Read More ','real-estater'),
	                'sanitize_callback' =>  'sanitize_text_field',
	                )
	            );

	$wp_customize->add_control(
	            'real_estater_archive_submit',array(
	                'priority'      =>  4,
	                'label'         =>  esc_html__('Read More ','real-estater'),
	                'section'       =>  'real_estater_archive_setting',
	                'setting'       =>  'real_estater_archive_submit',
	                'type'          =>  'text',  
	                )                                     
	            );    
}
add_action( 'customize_register', 'real_estater_custom_customize_register' );	