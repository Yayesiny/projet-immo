<?php
/**
 * CF7 Styler mdoule class
 * 
 * @package CF7 Styler
 * @author DiviPeople
 * @link https://divipeople.com
 * @since 1.0.0
 */

class DVPPL_CF7_Styler extends ET_Builder_Module {

	// Module slug (also used as shortcode tag)
	public $slug       = 'dvppl_cf7_styler';
	public $vb_support = 'on';

  // Module Credits (Appears at the bottom of the module settings modal)
	protected $module_credits = array(
		'module_uri' => 'https://divipeople.com',
		'author'     => 'DiviPeople',
		'author_uri' => 'https://divipeople.com',
	);

	public function init() {

		// Module name
		$this->name					= 	esc_html__( 'Contact Form 7 Styler', 'dvppl-cf7-styler' );
		
		$this->icon_path		=  plugin_dir_path( __FILE__ ) . 'cf7.svg';

		// $this->main_css_element
		$this->main_css_element 	= '%%order_class%%.dvppl_cf7_styler';

		// Toggle settings
		$this->settings_modal_toggles = array(

			'general'  => array(
				'toggles' => array(
					'general' 			=>		esc_html__( 'General', 'dvppl-cf7-styler' ),
				),
			),

			'advanced' => array(
				'toggles' => array(
					'form_field' 			=>		esc_html__( 'Form Fields', 'dvppl-cf7-styler' ),
					'labels' 					=>		esc_html__( 'Labels', 'dvppl-cf7-styler' ),
					'placeholder' 		=>		esc_html__( 'Placeholder', 'dvppl-cf7-styler' ),
					'radio_checkbox' 	=>		esc_html__( 'Radio & Checkbox', 'dvppl-cf7-styler' ),
					'submit_button' 	=>		esc_html__( 'Submit Button', 'dvppl-cf7-styler' ),
					'suc_err_msg' 	  =>		esc_html__( 'Success / Error Message', 'dvppl-cf7-styler' ),
				),
			),

		);

		$this->custom_css_fields = array(

			'cf7_fields' => array(
				'label'    => esc_html__( 'Form Fields', 'dvppl-cf7-styler' ),
				'selector' => '%%order_class%% .dvppl-cf7-styler input',
			),

			'cf7_labels' => array(
				'label'    => esc_html__( 'Form Labels', 'dvppl-cf7-styler' ),
				'selector' => '%%order_class%% .dvppl-cf7-styler label',
			),
		);
	}

	/**
	* Contact form 7
	*/
	public static function select_wpcf7() {

		if ( function_exists( 'wpcf7' ) ) {
			$options = array();

			$args = array(
				'post_type'         => 'wpcf7_contact_form',
				'posts_per_page'    => -1
			);

			$contact_forms = get_posts( $args );

			if ( ! empty( $contact_forms ) && ! is_wp_error( $contact_forms ) ) {

				$i = 0;

				foreach ( $contact_forms as $post ) {	
					if ( $i == 0 ) {
						$options[0] = esc_html__( 'Select a Contact form', 'dvppl-cf7-styler' );
					}
					$options[ $post->ID ] = $post->post_title;
					$i++;
				}

			}
		} else {
			$options = array();
		}

		return $options;
	}

	/**
	 * Get Fields
	 */
	public function get_fields() {

		return array(

			'cf7' => array(
				'label'             => esc_html__( 'Select Form', 'dvppl-cf7-styler' ),
				'type'              => 'select',
				'option_category'   => 'layout',
				'options'           => self::select_wpcf7(),
				'description'       => esc_html__( 'Choose a contact form to display.', 'dvppl-cf7-styler' ),
				
				'computed_affects' => array(
					'__cf7form',
				),

				'toggle_slug'       => 'general',
			),

			'form_background_color' => array(
				'label'             => esc_html__( 'Form Field Background Color', 'dvppl-cf7-styler' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'default'          	=> '#f5f5f5',
				'toggle_slug'       => 'form_field',
				'tab_slug'          => 'advanced',
			),

			'form_field_active_color' => array(
				'label'             => esc_html__( 'Form Field Active Color', 'dvppl-cf7-styler' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'form_field',
			),

			'cr_size' => array(
				'label'           => esc_html__( 'Size', 'dvppl-cf7-styler' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'radio_checkbox',
				'default_unit'    => 'px',
				'default'         => '20',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '50',
					'step' => '1',
				),
			),

			'cr_background_color' => array(
				'label'             => esc_html__( 'Background Color', 'dvppl-cf7-styler' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'radio_checkbox',
			),

			'cr_selected_color' => array(
				'label'             => esc_html__( 'Selected Color', 'dvppl-cf7-styler' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'default'          	=> '#222222',
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'radio_checkbox',
			),

			'cr_border_color' => array(
				'label'             => esc_html__( 'Border Color', 'dvppl-cf7-styler' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'default'          	=> '#222222',
				'toggle_slug'       => 'radio_checkbox',
				'tab_slug'          => 'advanced',
			),

			'cr_border_size' => array(
				'label'           => esc_html__( 'Border Size', 'dvppl-cf7-styler' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'radio_checkbox',
				'default_unit'    => 'px',
				'default'         => '1',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '5',
					'step' => '1',
				),
			),

			'cr_label_color' => array(
				'label'             => esc_html__( 'Label Color', 'dvppl-cf7-styler' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'radio_checkbox',
			),

			// Success / Error Message
			'cf7_message_color' => array(
				'label'             => esc_html__( 'Message Text Color', 'dvppl-cf7-styler' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'suc_err_msg',
			),

			'cf7_message_bg_color' => array(
				'label'             => esc_html__( 'Message Background Color', 'dvppl-cf7-styler' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'suc_err_msg',
			),

			'cf7_border_highlight_color' => array(
				'label'             => esc_html__( 'Border Highlight Color', 'dvppl-cf7-styler' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'suc_err_msg',
			),

			// Success
			'cf7_success_message_color' => array(
				'label'             => esc_html__( 'Success Message Text Color', 'dvppl-cf7-styler' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'suc_err_msg',
			),

			'cf7_success_message_bg_color' => array(
				'label'             => esc_html__( 'Success Message Background Color', 'dvppl-cf7-styler' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'suc_err_msg',
			),

			'cf7_success_border_color' => array(
				'label'             => esc_html__( 'Success Border Color', 'dvppl-cf7-styler' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'suc_err_msg',
			),
			
			// Error
			'cf7_error_message_color' => array(
				'label'             => esc_html__( 'Error Message Text Color', 'dvppl-cf7-styler' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'suc_err_msg',
			),

			'cf7_error_message_bg_color' => array(
				'label'             => esc_html__( 'Error Message Background Color', 'dvppl-cf7-styler' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'suc_err_msg',
			),

			'cf7_error_border_color' => array(
				'label'             => esc_html__( 'Error Border Color', 'dvppl-cf7-styler' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'suc_err_msg',
			),

			'cf7_message_padding' => array(
				'label'           => esc_html__( 'Message Padding', 'dvppl-cf7-styler' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'suc_err_msg',
				'default_unit'    => 'px',
				'default'         => '0',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '50',
					'step' => '1',
				),
			),


			'cf7_message_margin_top' => array(
				'label'           => esc_html__( 'Message Margin Top', 'dvppl-cf7-styler' ),
				'type'            => 'range',
				'option_category' => 'layout',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'suc_err_msg',
				'default_unit'    => 'px',
				'default'         => '0',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '50',
					'step' => '1',
				),
			),
			
			/**
			 * Computed
			 */
			'__cf7form' => array(
				'type'                => 'computed',
				'computed_callback'   => array( 'DVPPL_CF7_Styler', 'get_cf7_shortcode_html' ),
				'computed_depends_on' => array(
					'cf7',
				),
			),

		);
	}

	/**
	 * Config Advanced Fields
	 */
	public function get_advanced_fields_config() {

		$advanced_fields = array();

		/**
		 * Fonts ( Labels, Placeholer)
		 */
		$advanced_fields['fonts'] = false;
		$advanced_fields['text'] = false;
		$advanced_fields['text_shadow'] = false;

		$advanced_fields['fonts'] = array(
			'form_field_font'		=> array(
				'label'		=> esc_html__( 'Field', 'dvppl-cf7-styler'),

				'css'      => array(
					'main' => implode( ', ', array(
							"{$this->main_css_element} .dvppl-cf7 .wpcf7 input:not([type=submit])",
							"{$this->main_css_element} .dvppl-cf7 .wpcf7 input::placeholder",
							"{$this->main_css_element} .dvppl-cf7 .wpcf7 select",
							"{$this->main_css_element} .dvppl-cf7 .wpcf7 textarea",
							"{$this->main_css_element} .dvppl-cf7 .wpcf7 textarea::placeholder",
						) ),
					'important' => array(
						'font',
						'size',
						'letter-spacing',
						'line-height',
						// 'text-align',
						'all_caps',
					),
				),

				'toggle_slug'	=> 'form_field',
			),
		);

		$advanced_fields['fonts']['labels'] = array(
			'labels'		=> array(
				'label'		=> esc_html__( 'Label', 'dvppl-cf7-styler'),
				'css'			=> array(
					'main'	=> "{$this->main_css_element} .dvppl-cf7 .wpcf7 label",
					'important' => 'all'
				),

				'toggle_slug'	=> 'labels',
			),
		);

		$advanced_fields['button'] = array(
			'submit_button' => array(
				'label' => esc_html__( 'Submit Button', 'dvppl-cf7-styler' ),
				'css' => array(
					'main'	=> implode( ', ', array( 
						"{$this->main_css_element} .dvppl-cf7 .wpcf7 input[type=submit]",
						)
					),
				),
				'no_rel_attr' => true,
				'box_shadow'  => array(
					'css' => array(
						'main' => implode( ', ', array( 
						"{$this->main_css_element} .dvppl-cf7 .wpcf7 input[type=submit]",
						)
					),
						'important' => true,
					),
				),
			)
		);

		$advanced_fields['borders']['default'] = array();

		$advanced_fields['borders']['field'] = array(
			'label_prefix' => esc_html__( 'Field', 'dvppl-cf7-styler' ),
			'toggle_slug'  => 'form_field',
			'css'          => array(
				'main'      => array(
					'border_radii'  => sprintf('
						%1$s .dvppl-cf7-styler .wpcf7 input:not([type=submit]), 
						%1$s .dvppl-cf7-styler .wpcf7 select, 
						%1$s .dvppl-cf7-styler .wpcf7 textarea
						', 
						$this->main_css_element 
					),

					'border_styles' => sprintf('
						%1$s .dvppl-cf7-styler .wpcf7 input:not([type=submit]), 
						%1$s .dvppl-cf7-styler .wpcf7 select, 
						%1$s .dvppl-cf7-styler .wpcf7 textarea
						', 
						$this->main_css_element 
					),
				),

				'important' => 'all',
			),
		);

		$advanced_fields['borders']['field'] = array(
			'label_prefix' => esc_html__( 'Field', 'dvppl-cf7-styler' ),
			'toggle_slug'  => 'form_field',
			'css'          => array(
				'main'      => array(
					'border_radii'  => sprintf('
						%1$s .dvppl-cf7-styler .wpcf7 input:not([type=submit]), 
						%1$s .dvppl-cf7-styler .wpcf7 select, 
						%1$s .dvppl-cf7-styler .wpcf7 textarea
						', 
						$this->main_css_element 
					),

					'border_styles' => sprintf('
						%1$s .dvppl-cf7-styler .wpcf7 input:not([type=submit]), 
						%1$s .dvppl-cf7-styler .wpcf7 select, 
						%1$s .dvppl-cf7-styler .wpcf7 textarea
						', 
						$this->main_css_element 
					),
				),

				'important' => 'all',
			),
		);

		return $advanced_fields;
	}

	/**
	 * Get Contact form 7 Shortcode with id
	 */
	function get_cf7_shortcode( $args = array() ) {
		
		$cf7_id = $this->props['cf7'];

		$cf7_shortcode = '';

		if( 0 == $cf7_id ) {
			$cf7_shortcode = 'Please select a Contact Form 7.';
		} else {
			$cf7_shortcode = do_shortcode( sprintf( '[contact-form-7 id="%1$s" ]', $cf7_id ) );
		}

		return $cf7_shortcode;
	}

	/**
	 * Contact form 7 shortcode convert to html for using in VB
	 */
	static function get_cf7_shortcode_html( $args = array() ) {
		$cf7_shortcode = new self();
		$cf7_shortcode->props = $args;
		$output = $cf7_shortcode->get_cf7_shortcode( array() );

		return sprintf( '
			<div class="dvppl-cf7-container">
				<div class="dvppl-cf7 dvppl-cf7-styler">
					%1$s
				</div>
			</div>',
			$output 
		);
	}

	/**
	 * Render Method
	 * 
	 * @param  $attrs
	 * @param  $content
	 * @param  $render_slug
	 */
	public function render( $attrs, $content = null, $render_slug ) {

		$cf7_fields 									=		$this->props['cf7'];
		
		$form_background_color      	=		$this->props['form_background_color'];
		$form_background_color_hover	=		$this->get_hover_value( 'form_background_color' );
		$form_field_active_color			=		$this->props['form_field_active_color'];
		
		$cr_size       								=		$this->props['cr_size'];
		$cr_border_size       				=		$this->props['cr_border_size'];
		$cr_background_color       		=		$this->props['cr_background_color'];
		$cr_selected_color       			=		$this->props['cr_selected_color'];
		$cr_border_color       				=		$this->props['cr_border_color'];
		$cr_label_color       				=		$this->props['cr_label_color'];

		$cf7_message_color       			=		$this->props['cf7_message_color'];
		$cf7_message_bg_color      		=		$this->props['cf7_message_bg_color'];
		$cf7_border_highlight_color 	=		$this->props['cf7_border_highlight_color'];


		$cf7_success_message_color    =		$this->props['cf7_success_message_color'];
		$cf7_success_message_bg_color =		$this->props['cf7_success_message_bg_color'];
		$cf7_success_border_color 		=		$this->props['cf7_success_border_color'];

		$cf7_error_message_color      =		$this->props['cf7_error_message_color'];
		$cf7_error_message_bg_color   =		$this->props['cf7_error_message_bg_color'];
		$cf7_error_border_color 			=		$this->props['cf7_error_border_color'];

		$cf7_message_padding					=		$this->props['cf7_message_padding'];
		$cf7_message_margin_top 			=		$this->props['cf7_message_margin_top'];

		if ( '' !== $form_background_color ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dvppl-cf7 input:not([type=submit]), %%order_class%% .dvppl-cf7 select, %%order_class%% .dvppl-cf7 textarea, %%order_class%% .dvppl-cf7 .wpcf7-checkbox input[type="checkbox"] + span:before, %%order_class%% .dvppl-cf7 .wpcf7-acceptance input[type="checkbox"] + span:before, %%order_class%% .dvppl-cf7 .wpcf7-radio input[type="radio"]:not(:checked) + span:before',

				'declaration' => sprintf(
					'background-color: %1$s%2$s;',
					esc_html( $form_background_color ),
					et_is_builder_plugin_active() ? ' !important' : ''
				),
			) );
		}

		if ( '' !== $form_field_active_color ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dvppl-cf7 .wpcf7 input:not([type=submit]):focus, %%order_class%% .dvppl-cf7 .wpcf7 select:focus, %%order_class%% .dvppl-cf7 .wpcf7 textarea:focus',
				'declaration' => sprintf(
					'border-color: %1$s%2$s;',
					esc_html( $form_field_active_color ),
					et_is_builder_plugin_active() ? ' !important' : ''
				),
			) );
		}

		if ( '' !== $cr_size || '' !== $cr_border_size ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dvppl-cf7 .wpcf7-checkbox input[type="checkbox"] + span:before, %%order_class%% .dvppl-cf7 .wpcf7-acceptance input[type="checkbox"] + span:before, %%order_class%% .dvppl-cf7 .wpcf7-radio input[type="radio"] + span:before',
				'declaration' => sprintf(
					'width: %1$s%2$s; height: %1$s%2$s; border-width:%3$s%2$s;',
					esc_html( $cr_size ),
					et_is_builder_plugin_active() ? ' !important' : '',
					esc_html( $cr_border_size )
				),
			) );
		}

		if ( '' !== $cr_size ) {
			$font_size = $cr_size / 1.2;
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dvppl-cf7 .wpcf7-acceptance input[type=checkbox]:checked + span:before, %%order_class%% .dvppl-cf7 .wpcf7-checkbox input[type=checkbox]:checked + span:before',
				'declaration' => sprintf(
					'font-size: ',
					esc_html( $font_size ),
					et_is_builder_plugin_active() ? ' !important' : ''
				),
			) );
		}

		if ( '' !== $cr_background_color ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dvppl-cf7 .wpcf7-checkbox input[type="checkbox"] + span:before, %%order_class%% .dvppl-cf7 .wpcf7-acceptance input[type="checkbox"] + span:before, %%order_class%% .dvppl-cf7 .wpcf7-radio input[type="radio"]:not(:checked) + span:before',
				'declaration' => sprintf(
					'background-color: %1$s%2$s;',
					esc_html( $cr_background_color ),
					et_is_builder_plugin_active() ? ' !important' : ''
				),
			) );
		}

		if ( '' !== $cr_background_color ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dvppl-cf7 .wpcf7-radio input[type="radio"]:checked + span:before',
				'declaration' => sprintf(
					'box-shadow:inset 0px 0px 0px 4px %1$s%2$s;',
					esc_html( $cr_background_color ),
					et_is_builder_plugin_active() ? ' !important' : ''
				),
			) );
		}

		if ( '' !== $cr_selected_color ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dvppl-cf7 .wpcf7-checkbox input[type="checkbox"]:checked + span:before, %%order_class%% .dvppl-cf7 .wpcf7-acceptance input[type="checkbox"]:checked + span:before',
				'declaration' => sprintf(
					'color: %1$s%2$s;',
					esc_html( $cr_selected_color ),
					et_is_builder_plugin_active() ? ' !important' : ''
				),
			) );
		}

		if ( '' !== $cr_selected_color ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dvppl-cf7 .wpcf7-radio input[type="radio"]:checked + span:before',
				'declaration' => sprintf(
					'background-color: %1$s%2$s;',
					esc_html( $cr_selected_color ),
					et_is_builder_plugin_active() ? ' !important' : ''
				),
			) );
		}

		if ( '' !== $cr_border_color ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dvppl-cf7 .wpcf7-checkbox input[type=radio] + span:before, %%order_class%% .dvppl-cf7 .wpcf7-radio input[type=checkbox] + span:before, %%order_class%% .dvppl-cf7 .wpcf7-acceptance input[type="checkbox"] + span:before',
				'declaration' => sprintf(
					'border-color: %1$s%2$s;',
					esc_html( $cr_border_color ),
					et_is_builder_plugin_active() ? ' !important' : ''
				),
			) );
		}

		if ( '' !== $cr_label_color ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dvppl-cf7 .wpcf7-checkbox label, %%order_class%% .wpcf7-radio label',
				'declaration' => sprintf(
					'color: %1$s%2$s;',
					esc_html( $cr_label_color ),
					et_is_builder_plugin_active() ? ' !important' : ''
				),
			) );
		}

		if ( '' !== $cf7_message_color ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dvppl-cf7 span.wpcf7-not-valid-tip',
				'declaration' => sprintf(
					'color: %1$s%2$s;',
					esc_html( $cf7_message_color ),
					et_is_builder_plugin_active() ? ' !important' : ''
				),
			) );
		}

		if ( '' !== $cf7_message_bg_color ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dvppl-cf7 span.wpcf7-not-valid-tip',
				'declaration' => sprintf(
					'background-color: %1$s%2$s;',
					esc_html( $cf7_message_bg_color ),
					et_is_builder_plugin_active() ? ' !important' : ''
				),
			) );
		}

		if ( '' !== $cf7_border_highlight_color ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dvppl-cf7 span.wpcf7-not-valid-tip',
				'declaration' => sprintf(
					'border-color: %1$s%2$s;',
					esc_html( $cf7_border_highlight_color ),
					et_is_builder_plugin_active() ? ' !important' : ''
				),
			) );
		}

		// Success 
		if ( '' !== $cf7_success_message_color ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dvppl-cf7 .wpcf7-mail-sent-ok',
				'declaration' => sprintf(
					'color: %1$s%2$s;',
					esc_html( $cf7_success_message_color ),
					et_is_builder_plugin_active() ? ' !important' : ''
				),
			) );
		}

		if ( '' !== $cf7_success_message_bg_color ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dvppl-cf7 .wpcf7-mail-sent-ok',
				'declaration' => sprintf(
					'background-color: %1$s%2$s;',
					esc_html( $cf7_success_message_bg_color ),
					et_is_builder_plugin_active() ? ' !important' : ''
				),
			) );
		}

		if ( '' !== $cf7_success_border_color ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .dvppl-cf7 .wpcf7-mail-sent-ok',
				'declaration' => sprintf(
					'border-color: %1$s%2$s;',
					esc_html( $cf7_success_border_color ),
					et_is_builder_plugin_active() ? ' !important' : ''
				),
			) );
		}

		// Error
		if ( '' !== $cf7_error_message_color ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .wpcf7-validation-errors',
				'declaration' => sprintf(
					'color: %1$s%2$s;',
					esc_html( $cf7_error_message_color ),
					et_is_builder_plugin_active() ? ' !important' : ''
				),
			) );
		}

		if ( '' !== $cf7_error_message_bg_color ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .wpcf7-validation-errors',
				'declaration' => sprintf(
					'background-color: %1$s%2$s;',
					esc_html( $cf7_error_message_bg_color ),
					et_is_builder_plugin_active() ? ' !important' : ''
				),
			) );
		}

		if ( '' !== $cf7_error_border_color ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .wpcf7-validation-errors',
				'declaration' => sprintf(
					'border-color: %1$s%2$s;',
					esc_html( $cf7_error_border_color ),
					et_is_builder_plugin_active() ? ' !important' : ''
				),
			) );
		}

		if ( '' !== $cf7_message_padding ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% span.wpcf7-not-valid-tip',
				'declaration' => sprintf(
					'padding: %1$s%2$s;',
					esc_html( $cf7_message_padding ),
					et_is_builder_plugin_active() ? ' !important' : ''
				),
			) );
		}

		if ( '' !== $cf7_message_margin_top ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% span.wpcf7-not-valid-tip',
				'declaration' => sprintf(
					'margin-top: %1$s%2$s;',
					esc_html( $cf7_message_margin_top ),
					et_is_builder_plugin_active() ? ' !important' : ''
				),
			) );
		}


		/**
		 * Output
		 */
		return sprintf( '
			<div class="dvppl-cf7-container">
				<div class="dvppl-cf7 dvppl-cf7-styler">
					%1$s
				</div>
			</div>
			',
			$this->get_cf7_shortcode( array() ) );
	}
}

new DVPPL_CF7_Styler;