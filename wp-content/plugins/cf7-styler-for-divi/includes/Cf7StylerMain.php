<?php
/**
 * CF7 Styler Main class
 * 
 * @package CF7 Styler
 * @author DiviPeople
 * @link https://divipeople.com
 * @since 1.0.0
 */


class DVPPL_Cf7Styler_Main extends DiviExtension {

	/**
	 * The gettext domain for the extension's translations.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $gettext_domain = 'dvppl-cf7-styler';

	/**
	 * The extension's WP Plugin name.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $name = 'cf7-styler';

	/**
	 * The extension's version
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $version = '1.0.0';

	/**
	 * DVPPL_Cf7Styler_Main constructor.
	 *
	 * @param string $name
	 * @param array  $args
	 */
	public function __construct( $name = 'cf7-styler', $args = array() ) {
		$this->plugin_dir     = plugin_dir_path( __FILE__ );
		$this->plugin_dir_url = plugin_dir_url( $this->plugin_dir );

		parent::__construct( $name, $args );
	}

}

new DVPPL_Cf7Styler_Main;
