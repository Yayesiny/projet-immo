<?php
/**
 * GET Plugin template
 * *******************************************************
 */
function gsf_get_template($slug, $args = array())
{
	if ($args && is_array($args)) {
		extract($args);
	}
	$located = GSF_PLUGIN_DIR . $slug . '.php';
	if (!file_exists($located)) {
		_doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> does not exist.', $slug), '1.0');

		return;
	}
	include($located);
}

/**
 * Get Meta Boxes value
 * *******************************************************
 */
if (!function_exists('gsf_meta_box_value')) {
	function gsf_meta_box_value($key, $is_single = true, $post_id = 0, $args = array())
	{
		$post_id = !$post_id ? get_the_ID() : $post_id;
		$value = get_post_meta($post_id, $key, $is_single);

		if (isset($args['type'])) {
			switch ($args['type']) {
				case 'image':
					$default = array(
						'size' => 'thumbnail',
						'icon' => false
					);
					$args = array_merge($args, $default);

					return wp_get_attachment_image_src($value, $args['size'], $args['icon']);
				case 'gallery':
					$gallery = array();
					$default = array(
						'size' => 'thumbnail',
						'icon' => false
					);
					if (is_array($value)) {
						foreach ($value as $id) {
							$args = array_merge($args, $default);
							$attach = wp_get_attachment_image_src($id, $args['size'], $args['icon']);
							if ($attach) {
								$gallery[] = $attach;
							}
						}
					}

					return $gallery;
			}
		} elseif ($args == 'image') {
			return wp_get_attachment_image_src($value, 'full');
		} elseif ($args == 'gallery') {
			return wp_get_attachment_image_src($value, 'full');
		}

		return $value;
	}
}

/**
 * Determine whether we are in add New page/post/CPT or in edit page/post/CPT
 * *******************************************************
 */
if (!function_exists('gsf_is_edit_page')) {
	function gsf_is_edit_page($new_edit = null)
	{
		global $pagenow;
		//make sure we are on the backend
		if (!is_admin()) return false;


		if ($new_edit == "edit")
			return in_array($pagenow, array('post.php',));
		elseif ($new_edit == "new") //check for new post page
			return in_array($pagenow, array('post-new.php'));
		else //check for either new or edit
			return in_array($pagenow, array('post.php', 'post-new.php'));
	}
}

/**
 * Echo selected attribute in select field
 * *******************************************************
 */
if (!function_exists('gsf_the_selected')) {
	function gsf_the_selected($value, $current)
	{
		echo ((is_array($current) && in_array($value, $current)) || (!is_array($current) && ($value == $current))) ? 'selected' : '';
	}
}

/**
 * Change LIKE post title by keyword
 * *******************************************************
 */
if (!function_exists('gsf_title_like_posts_where')) {
	function gsf_title_like_posts_where($where, &$wp_query)
	{
		global $wpdb;
		if ($search_term = $wp_query->get('post_title_like')) {
			$where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql($wpdb->esc_like($search_term)) . '%\'';
		}

		return $where;
	}
}

/**
 * Get List Post of Post Type
 * *******************************************************
 */
if (!function_exists('gsf_ajax_get_posts')) {
	function gsf_ajax_get_posts()
	{
		add_filter('posts_where', 'gsf_title_like_posts_where', 10, 2);
		$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
		$post_type = isset($_GET['post_type']) ? $_GET['post_type'] : 'post';
		$search_query = array(
			'post_title_like' => $keyword,
			'order'           => 'ASC',
			'orderby'         => 'post_title',
			'post_type'       => $post_type,
			'post_status'     => 'publish',
			'posts_per_page'  => 10,
		);

		$search = new WP_Query($search_query);
		$ret = array();
		foreach ($search->posts as $post) {
			$ret[] = array(
				'value' => $post->ID,
				'label' => $post->post_title
			);
		}
		echo json_encode($ret);
		die();
	}

	add_action('wp_ajax_nopriv_gsf_get_posts', 'gsf_ajax_get_posts');
	add_action('wp_ajax_gsf_get_posts', 'gsf_ajax_get_posts');
}

/**
 * Get list sidebars
 * *******************************************************
 */
if (!function_exists('gsf_get_sidebars')) {
	function gsf_get_sidebars()
	{
		$sidebars = array();
		if (is_array($GLOBALS['wp_registered_sidebars'])) {
			foreach ($GLOBALS['wp_registered_sidebars'] as $sidebar) {
				$sidebars[$sidebar['id']] = ucwords($sidebar['name']);
			}
		}
		return $sidebars;
	}
}

/**
 * Get list menus
 * *******************************************************
 */
if (!function_exists('gsf_get_menus')) {
	function gsf_get_menus()
	{
		$user_menus = get_categories(array(
			'taxonomy'   => 'nav_menu',
			'hide_empty' => false,
			'orderby'    => 'name',
			'order'      => 'ASC'
		));
		$menus = array();
		foreach ($user_menus as $menu) {
			$menus[$menu->term_id] = $menu->name;
		}

		return $menus;
	}
}

/**
 * Get list taxonomies
 * *******************************************************
 */
if (!function_exists('gsf_get_taxonomies')) {
	function gsf_get_taxonomies($params = array())
	{
		$args = array(
			'orderby' => 'name',
			'order'   => 'ASC'
		);
		if (!empty($params)) {
			$args = array_merge($args, $params);
		}

		$categories = get_categories($args);
		$taxs = array();
		foreach ($categories as $cate) {
			$taxs[$cate->term_id] = $cate->name;
		}

		return $taxs;
	}
}

/**
 * Get list posts
 * *******************************************************
 */
if (!function_exists('gsf_get_posts')) {
	function gsf_get_posts($params = array())
	{
		$args = array(
			'numberposts' => 20,
			'orderby' => 'post_title',
			'order'   => 'ASC',
		);
		if (!empty($params)) {
			$args = array_merge($args, $params);
		}
		$posts = get_posts($args);
		$ret_posts = array();
		foreach ($posts as $post) {
			$ret_posts[$post->ID] = $post->post_title;
		}

		return $ret_posts;
	}
}

/**
 * Set GLOBAL Config Layout
 * *******************************************************
 */
if (!function_exists('gsf_set_config_layout')) {
	function gsf_set_config_layout($value)
	{
		$GLOBALS['gsf_config_layout'] = $value;
	}
}

/**
 * Get GLOBAL Config Layout
 * *******************************************************
 */
if (!function_exists('gsf_get_config_layout')) {
	function gsf_get_config_layout()
	{
		return isset($GLOBALS['gsf_config_layout']) ? $GLOBALS['gsf_config_layout'] : '';
	}
}

/**
 * Set GLOBAL Config Layout
 * *******************************************************
 */
if (!function_exists('gsf_set_config_type')) {
	function gsf_set_config_type($value)
	{
		$GLOBALS['gsf_config_type'] = $value;
	}
}

/**
 * Get GLOBAL Config Layout
 * *******************************************************
 */
if (!function_exists('gsf_get_config_type')) {
	function gsf_get_config_type()
	{
		return isset($GLOBALS['gsf_config_type']) ? $GLOBALS['gsf_config_type'] : 'meta_box';
	}
}

/**
 * Get Field class name
 * *******************************************************
 */
if (!function_exists('gsf_get_field_class_name')) {
	function gsf_get_field_class_name($field_type)
	{
		$type = str_replace('_', ' ', $field_type);
		$type = ucwords($type);
		$type = str_replace(' ', '_', $type);

		return 'GSF_Field_' . $type;
	}
}

/**
 * Get options config keys
 *
 * @package SmartFramework
 * @subpackage Function
 * @author g5plus
 * @since 1.0
 * @return Array
 */
if (!function_exists('gsf_get_option_config_keys')) {
	function gsf_get_option_config_keys($configs) {
		$field_keys = array();
		if (isset($configs['section'])) {
			foreach ($configs['section'] as $tabs) {
				if (isset($tabs['fields'])) {
					$field_keys = array_merge($field_keys, gsf_get_config_field_keys($tabs['fields'], '', $tabs['id']));
				}
			}
		} else {

			if (isset($configs['fields'])) {
				$field_keys = array_merge($field_keys, gsf_get_config_field_keys($configs['fields'], '', ''));
			}
		}
		return $field_keys;
	}
}

/**
 * Get config field keys
 * Method called by gsf_get_config_keys
 *
 * @package SmartFramework
 * @subpackage Function
 * @author g5plus
 * @since 1.0
 * @return Array
 */
if (!function_exists('gsf_get_config_field_keys')) {
	function gsf_get_config_field_keys($fields, $parent_type = '', $section = '')
	{
		$field_keys = array();
		foreach ($fields as $field) {
			if (!isset($field['type'])) {
				continue;
			}

			switch ($field['type']) {
				case 'repeater':
					if (!isset($field['id'])) {
						break;
					}
					if (($parent_type === 'repeater') || !isset($field['fields'])) {
						break;
					}
					$field_keys[$field['id']] = array(
						'type' => $field['type'],
						'clone' => false,
						'section' => $section,
						'default' => isset($field['default']) ? $field['default'] : '',
					);
					$field_keys = array_merge($field_keys, gsf_get_config_field_keys($field['fields'], $field['type'], $section));
					break;
				case 'row':
				case 'group':
					if (($parent_type === 'repeater') || !isset($field['fields'])) {
						break;
					}
					$field_keys = array_merge($field_keys, gsf_get_config_field_keys($field['fields'], $field['type'], $section));
					break;
				default:
					if (!isset($field['id'])) {
						break;
					}
					$class_field = gsf_get_field_class_name($field['type']);
					$field_obj = new $class_field($field, $parent_type);

					$field_keys[$field['id']] = array(
						'type' => $field['type'],
						'clone' => (isset($field['clone']) && $field['clone']) || ($parent_type === 'repeater'),
						'section' => $section,
						'default' => $field_obj->get_default(),
					);
					break;
			}
		}

		return $field_keys;
	}
}

/**
 * Get Attachment ID from url
 *
 * @package SmartFramework
 * @subpackage Function
 * @author g5plus
 * @since 1.0
 * @return int
 */
if (!function_exists('gsf_get_attachment_id')) {
	function gsf_get_attachment_id($url)
	{
		global $wpdb;
		$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $url));
		if (!empty($attachment)) {
			return $attachment[0];
		}

		return 0;
	}
}

/**
 * Ajax Get Attachment ID
 *
 * @package SmartFramework
 * @subpackage Function
 * @author g5plus
 * @since 1.0
 * @return int
 */
if (!function_exists('gsf_ajax_get_attachment_id')) {
	function gsf_ajax_get_attachment_id()
	{
		if (!isset($_GET['url'])) {
			echo 0;
		} else {
			echo gsf_get_attachment_id($_GET['url']);
		}
		die();

	}

	add_action('wp_ajax_nopriv_gsf_get_attachment_id', 'gsf_ajax_get_attachment_id');
	add_action('wp_ajax_gsf_get_attachment_id', 'gsf_ajax_get_attachment_id');
}

/**
 * Ajax Get google list json
 * Read more https://developers.google.com/fonts/docs/developer_api
 *
 * @package SmartFramework
 * @subpackage Function
 * @author g5plus
 * @since 1.0
 * @return string
 */
if (!function_exists('gsf_ajax_get_fonts')) {
	function gsf_ajax_get_fonts()
	{
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		WP_Filesystem();
		global $wp_filesystem;
		$webfonts = json_decode($wp_filesystem->get_contents(GSF_PLUGIN_DIR . 'assets/webfonts.json'), true);
		$standard_fonts = array(
			"Arial, Helvetica, sans-serif"                         => "Arial, Helvetica, sans-serif",
			"'Arial Black', Gadget, sans-serif"                    => "Arial Black, Gadget, sans-serif",
			"'Bookman Old Style', serif"                           => "Bookman Old Style, serif",
			"'Comic Sans MS', cursive"                             => "Comic Sans MS, cursive",
			"Courier, monospace"                                   => "Courier, monospace",
			"Garamond, serif"                                      => "Garamond, serif",
			"Georgia, serif"                                       => "Georgia, serif",
			"Impact, Charcoal, sans-serif"                         => "Impact, Charcoal, sans-serif",
			"'Lucida Console', Monaco, monospace"                  => "Lucida Console, Monaco, monospace",
			"'Lucida Sans Unicode', 'Lucida Grande', sans-serif"   => "Lucida Sans Unicode, 'Lucida Grande', sans-serif",
			"'MS Sans Serif', Geneva, sans-serif"                  => "MS Sans Serif, Geneva, sans-serif",
			"'MS Serif', 'New York', sans-serif"                   => "MS Serif, New York, sans-serif",
			"'Palatino Linotype', 'Book Antiqua', Palatino, serif" => "Palatino Linotype, Book Antiqua, Palatino, serif",
			"Tahoma,Geneva, sans-serif"                            => "Tahoma,Geneva, sans-serif",
			"'Times New Roman', Times,serif"                       => "Times New Roman, Times,serif",
			"'Trebuchet MS', Helvetica, sans-serif"                => "Trebuchet MS, Helvetica, sans-serif",
			"Verdana, Geneva, sans-serif"                          => "Verdana, Geneva, sans-serif",
		);

		$fonts = array(
			'basic'  => array(
				'label' => esc_html__('Standard Fonts', 'smart-framework'),
			),
			'google' => array(
				'label' => esc_html__('Google Webfonts', 'smart-framework'),
			),
		);
		foreach ($standard_fonts as $font_name => $font_label) {
			$fonts['basic']['items'][] = array(
				'kind'         => 'basic',
				'family'       => $font_name,
				'family_label' => $font_label,
				'variants'     => array(
					'400',
					'400italic',
					'700',
					'700italic',
				),
				'subsets'      => array(),
			);
		}
		$variants = array();
		$varName = $fontWeight = $fontStyle = '';
		foreach ($webfonts['items'] as $font) {
			$variants = isset($font['variants']) ? $font['variants'] : array();
			foreach ($variants as &$varName) {
				$fontWeight = str_replace('italic', '', $varName);
				$fontStyle = substr($varName, strlen($fontWeight));
				$fontWeight = ($fontWeight === '') || ($fontWeight === 'regular') ? '400' : $fontWeight;
				$varName = $fontWeight . $fontStyle;
			}
			$fonts['google']['items'][] = array(
				'kind'         => 'google',
				'family'       => isset($font['family']) ? "'" . $font['family'] . "'" : '',
				'family_label' => isset($font['family']) ? $font['family'] : '',
				'variants'     => $variants,
				'subsets'      => isset($font['subsets']) ? $font['subsets'] : array(),
			);
		}
		echo json_encode(apply_filters('gsf_font_list', $fonts));
		die();

	}

	add_action('wp_ajax_nopriv_gsf_get_fonts', 'gsf_ajax_get_fonts');
	add_action('wp_ajax_gsf_get_fonts', 'gsf_ajax_get_fonts');
}

/**
 * Make Google web font config
 *
 * @package SmartFramework
 * @subpackage Function
 * @author g5plus
 * @since 1.0
 * @return string
 */
if (!function_exists('gsf_make_webfont_config')) {
	function gsf_make_webfont_config() {
		$google_font_config = array();
		$font_options = get_option(GSF_OPTIONS_FONT_USING);
		$options_font = apply_filters('gsf_options_font', array());
		if (is_array($font_options)) {
			foreach($font_options as $font_key => $font_using) {
				if (!in_array($font_key, $options_font)) {
					continue;
				}

				$font_items = array();
				if (count($font_using) > 0) {
					foreach ($font_using as $font) {
						if ($font['font_kind'] === 'google') {
							$font_family = str_replace("'", '', $font['font_family']);
							if (!isset($font_items[$font_family])) {
								$font_items[$font_family] = array();
							}
							if (!empty($font['font_subsets'])) {
								$font_items[$font_family][] = $font['font_subsets'];
							}
						}
					}
					foreach ($font_items as $font_family => $subsets) {
						$google_font_config[] = sprintf('"%s:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i%s"'
							, $font_family
							, count($subsets) > 0 ? ':' . join(',', $subsets) : '');
					}
				}
			}
		}
		return join(',', $google_font_config);
	}
}

/**
 * Ajax get font icon array list
 * You can change font icon array list via filter gsf_font_icons
 *
 * @package SmartFramework
 * @subpackage Function
 * @author g5plus
 * @since 1.0
 * @return string
 */
if (!function_exists('gsf_ajax_get_font_icons')) {
	function gsf_ajax_get_font_icons() {
		echo json_encode(apply_filters('gsf_font_icons', array(
			'dashicons' => array(
				'label' => esc_html__('Dashicons','smart-framework'),
				'icons' => array('dashicons dashicons-menu','dashicons dashicons-admin-site','dashicons dashicons-dashboard','dashicons dashicons-admin-media','dashicons dashicons-admin-page','dashicons dashicons-admin-comments','dashicons dashicons-admin-appearance','dashicons dashicons-admin-plugins','dashicons dashicons-admin-users','dashicons dashicons-admin-tools','dashicons dashicons-admin-settings','dashicons dashicons-admin-network','dashicons dashicons-admin-generic','dashicons dashicons-admin-home','dashicons dashicons-admin-collapse','dashicons dashicons-filter','dashicons dashicons-admin-customizer','dashicons dashicons-admin-multisite','dashicons dashicons-admin-links','dashicons dashicons-admin-post','dashicons dashicons-format-image','dashicons dashicons-format-gallery','dashicons dashicons-format-audio','dashicons dashicons-format-video','dashicons dashicons-format-chat','dashicons dashicons-format-status','dashicons dashicons-format-aside','dashicons dashicons-format-quote','dashicons dashicons-welcome-write-blog','dashicons dashicons-welcome-add-page','dashicons dashicons-welcome-view-site','dashicons dashicons-welcome-widgets-menus','dashicons dashicons-welcome-comments','dashicons dashicons-welcome-learn-more','dashicons dashicons-image-crop','dashicons dashicons-image-rotate','dashicons dashicons-image-rotate-left','dashicons dashicons-image-rotate-right','dashicons dashicons-image-flip-vertical','dashicons dashicons-image-flip-horizontal','dashicons dashicons-image-filter','dashicons dashicons-undo','dashicons dashicons-redo','dashicons dashicons-editor-bold','dashicons dashicons-editor-italic','dashicons dashicons-editor-ul','dashicons dashicons-editor-ol','dashicons dashicons-editor-quote','dashicons dashicons-editor-alignleft','dashicons dashicons-editor-aligncenter','dashicons dashicons-editor-alignright','dashicons dashicons-editor-insertmore','dashicons dashicons-editor-spellcheck','dashicons dashicons-editor-distractionfree','dashicons dashicons-editor-contract','dashicons dashicons-editor-kitchensink','dashicons dashicons-editor-underline','dashicons dashicons-editor-justify','dashicons dashicons-editor-textcolor','dashicons dashicons-editor-paste-word','dashicons dashicons-editor-paste-text','dashicons dashicons-editor-removeformatting','dashicons dashicons-editor-video','dashicons dashicons-editor-customchar','dashicons dashicons-editor-outdent','dashicons dashicons-editor-indent','dashicons dashicons-editor-help','dashicons dashicons-editor-strikethrough','dashicons dashicons-editor-unlink','dashicons dashicons-editor-rtl','dashicons dashicons-editor-break','dashicons dashicons-editor-code','dashicons dashicons-editor-paragraph','dashicons dashicons-editor-table','dashicons dashicons-align-left','dashicons dashicons-align-right','dashicons dashicons-align-center','dashicons dashicons-align-none','dashicons dashicons-lock','dashicons dashicons-unlock','dashicons dashicons-calendar','dashicons dashicons-calendar-alt','dashicons dashicons-visibility','dashicons dashicons-hidden','dashicons dashicons-post-status','dashicons dashicons-edit','dashicons dashicons-post-trash','dashicons dashicons-sticky','dashicons dashicons-external','dashicons dashicons-arrow-up','dashicons dashicons-arrow-down','dashicons dashicons-arrow-left','dashicons dashicons-arrow-right','dashicons dashicons-arrow-up-alt','dashicons dashicons-arrow-down-alt','dashicons dashicons-arrow-left-alt','dashicons dashicons-arrow-right-alt','dashicons dashicons-arrow-up-alt2','dashicons dashicons-arrow-down-alt2','dashicons dashicons-arrow-left-alt2','dashicons dashicons-arrow-right-alt2','dashicons dashicons-leftright','dashicons dashicons-sort','dashicons dashicons-randomize','dashicons dashicons-list-view','dashicons dashicons-exerpt-view','dashicons dashicons-grid-view','dashicons dashicons-move','dashicons dashicons-hammer','dashicons dashicons-art','dashicons dashicons-migrate','dashicons dashicons-performance','dashicons dashicons-universal-access','dashicons dashicons-universal-access-alt','dashicons dashicons-tickets','dashicons dashicons-nametag','dashicons dashicons-clipboard','dashicons dashicons-heart','dashicons dashicons-megaphone','dashicons dashicons-schedule','dashicons dashicons-wordpress','dashicons dashicons-wordpress-alt','dashicons dashicons-pressthis','dashicons dashicons-update','dashicons dashicons-screenoptions','dashicons dashicons-cart','dashicons dashicons-feedback','dashicons dashicons-cloud','dashicons dashicons-translation','dashicons dashicons-tag','dashicons dashicons-category','dashicons dashicons-archive','dashicons dashicons-tagcloud','dashicons dashicons-text','dashicons dashicons-media-archive','dashicons dashicons-media-audio','dashicons dashicons-media-code','dashicons dashicons-media-default','dashicons dashicons-media-document','dashicons dashicons-media-interactive','dashicons dashicons-media-spreadsheet','dashicons dashicons-media-text','dashicons dashicons-media-video','dashicons dashicons-playlist-audio','dashicons dashicons-playlist-video','dashicons dashicons-controls-play','dashicons dashicons-controls-pause','dashicons dashicons-controls-forward','dashicons dashicons-controls-skipforward','dashicons dashicons-controls-back','dashicons dashicons-controls-skipback','dashicons dashicons-controls-repeat','dashicons dashicons-controls-volumeon','dashicons dashicons-controls-volumeoff','dashicons dashicons-yes','dashicons dashicons-no','dashicons dashicons-no-alt','dashicons dashicons-plus','dashicons dashicons-plus-alt','dashicons dashicons-plus-alt2','dashicons dashicons-minus','dashicons dashicons-dismiss','dashicons dashicons-marker','dashicons dashicons-star-filled','dashicons dashicons-star-half','dashicons dashicons-star-empty','dashicons dashicons-flag','dashicons dashicons-info','dashicons dashicons-warning','dashicons dashicons-share','dashicons dashicons-share1','dashicons dashicons-share-alt','dashicons dashicons-share-alt2','dashicons dashicons-twitter','dashicons dashicons-rss','dashicons dashicons-email','dashicons dashicons-email-alt','dashicons dashicons-facebook','dashicons dashicons-facebook-alt','dashicons dashicons-networking','dashicons dashicons-googleplus','dashicons dashicons-location','dashicons dashicons-location-alt','dashicons dashicons-camera','dashicons dashicons-images-alt','dashicons dashicons-images-alt2','dashicons dashicons-video-alt','dashicons dashicons-video-alt2','dashicons dashicons-video-alt3','dashicons dashicons-vault','dashicons dashicons-shield','dashicons dashicons-shield-alt','dashicons dashicons-sos','dashicons dashicons-search','dashicons dashicons-slides','dashicons dashicons-analytics','dashicons dashicons-chart-pie','dashicons dashicons-chart-bar','dashicons dashicons-chart-line','dashicons dashicons-chart-area','dashicons dashicons-groups','dashicons dashicons-businessman','dashicons dashicons-id','dashicons dashicons-id-alt','dashicons dashicons-products','dashicons dashicons-awards','dashicons dashicons-forms','dashicons dashicons-testimonial','dashicons dashicons-portfolio','dashicons dashicons-book','dashicons dashicons-book-alt','dashicons dashicons-download','dashicons dashicons-upload','dashicons dashicons-backup','dashicons dashicons-clock','dashicons dashicons-lightbulb','dashicons dashicons-microphone','dashicons dashicons-desktop','dashicons dashicons-laptop','dashicons dashicons-tablet','dashicons dashicons-smartphone','dashicons dashicons-phone','dashicons dashicons-smiley','dashicons dashicons-index-card','dashicons dashicons-carrot','dashicons dashicons-building','dashicons dashicons-store','dashicons dashicons-album','dashicons dashicons-palmtree','dashicons dashicons-tickets-alt','dashicons dashicons-money','dashicons dashicons-thumbs-up','dashicons dashicons-thumbs-down','dashicons dashicons-layout','dashicons dashicons-paperclip'),
			)
		)));
		die();
	}

	add_action('wp_ajax_nopriv_gsf_get_font_icons', 'gsf_ajax_get_font_icons');
	add_action('wp_ajax_gsf_get_font_icons', 'gsf_ajax_get_font_icons');
}

if (!function_exists('gsf_ajax_select_default_image')) {
	function gsf_ajax_select_default_image() {
		$imageDefaultDir = apply_filters('gsf_image_default_dir', array());
		if (empty($imageDefaultDir) || empty($imageDefaultDir['url']) || empty($imageDefaultDir['dir'])) {
			die();
		}
		$fileArr = array();
		if (file_exists($imageDefaultDir['dir'])) {
			$files = scandir($imageDefaultDir['dir']);
			foreach ($files as $file) {
				if (!in_array($file, array('.', '..'))) {
					$fileArr[] = $file;
				}
			}
		}
		?>
		<div class="gsf-image-default-popup">
			<div class="gsf-image-default-popup-content">
				<h1><?php esc_html_e('Select Images','smart-framework'); ?> <span class="dashicons dashicons-no-alt"></span></h1>
				<div class="gsf-image-default-popup-listing">
					<?php foreach ($fileArr as $file): ?>
						<div class="gsf-image-default-popup-item">
							<div class="thumbnail">
								<div class="centered">
									<img title="<?php echo esc_attr($file); ?>" src="<?php echo esc_url($imageDefaultDir['url'] . $file); ?>" alt=""/>
								</div>
							</div>
						</div>
					<?php endforeach;?>
				</div>
			</div>
		</div>
		<?php
		die();
	}
	add_action('wp_ajax_nopriv_gsf_select_default_image', 'gsf_ajax_select_default_image');
	add_action('wp_ajax_gsf_select_default_image', 'gsf_ajax_select_default_image');
}

/**
 * Ajax import theme options
 *
 * @package SmartFramework
 * @subpackage Function
 * @author g5plus
 * @since 1.0
 * @return: int = 1: success, string: error string
 */
if (!function_exists('gsf_ajax_import_theme_options')) {
	function gsf_ajax_import_theme_options() {
		$page = $_POST['_current_page'];
		$configs = &gsf_get_options_config($page);
		$options_name = $configs['option_name'];
		if ( ! wp_verify_nonce( $_POST['wpnonce'], $options_name ) ) {
			return;
		}
		if (!isset($_POST['backup_data'])) {
			return;
		}
		$backup = json_decode(base64_decode($_POST['backup_data']), true);
		$options = get_option($options_name);

		foreach ($backup as $key => $value) {
			$options[$key] = $value;
		}
		update_option($options_name, $options);

		$config_keys = gsf_get_option_config_keys($configs);
		$font_using = array();
		foreach ($config_keys as $key => $value) {
			if ($value['type'] === 'font') {
				$font_using[$key] = wp_unslash($options[$key]);
			}
		}

		$font_option = get_option(GSF_OPTIONS_FONT_USING);
		$font_option[$options_name] = $font_using;
		update_option(GSF_OPTIONS_FONT_USING, $font_option);

		echo 1;
		die();
	}

	add_action('wp_ajax_nopriv_gsf_import_theme_options', 'gsf_ajax_import_theme_options');
	add_action('wp_ajax_gsf_import_theme_options', 'gsf_ajax_import_theme_options');
}

/**
 * Ajax export theme options
 *
 * @package SmartFramework
 * @subpackage Function
 * @author g5plus
 * @since 1.0
 * @return: file json theme options content (base 64)
 */
if (!function_exists('gsf_ajax_export_theme_options')) {
	function gsf_ajax_export_theme_options() {
		$page = $_GET['_current_page'];
		$configs = &gsf_get_options_config($page);
		$options_name = $configs['option_name'];
		if ( ! wp_verify_nonce( $_GET['wpnonce'], $options_name ) ) {
			return;
		}

		$options = get_option($options_name);
		header( 'Content-Description: File Transfer' );
		header( 'Content-type: application/txt' );
		header( 'Content-Disposition: attachment; filename="smart_framework_' . $options_name . '_backup_' . date( 'd-m-Y' ) . '.json"' );
		header( 'Content-Transfer-Encoding: binary' );
		header( 'Expires: 0' );
		header( 'Cache-Control: must-revalidate' );
		header( 'Pragma: public' );

		echo base64_encode(json_encode($options));
		die();
	}

	add_action('wp_ajax_nopriv_gsf_export_theme_options', 'gsf_ajax_export_theme_options');
	add_action('wp_ajax_gsf_export_theme_options', 'gsf_ajax_export_theme_options');
}

/**
 * Ajax reset options
 *
 * @package SmartFramework
 * @subpackage Function
 * @author g5plus
 * @since 1.0
 * @return int = 1: success, string: error string
 */
if (!function_exists('gsf_ajax_reset_theme_options')) {
	function gsf_ajax_reset_theme_options() {
		$page = $_POST['_current_page'];
		$configs = &gsf_get_options_config($page);
		$options_name = $configs['option_name'];
		if ( ! wp_verify_nonce( $_POST['wpnonce'], $options_name ) ) {
			return;
		}

		$config_keys = gsf_get_option_config_keys($configs);
		$options = array();

		$font_using = array();
		foreach ($config_keys as $key => $value) {
			$options[$key] = wp_unslash($value['default']);
			if ($value['type'] === 'font') {
				$font_using[$key] = wp_unslash($value['default']);
			}
		}
		update_option($options_name, $options);

		$font_option = get_option(GSF_OPTIONS_FONT_USING);
		$font_option[$options_name] = $font_using;
		update_option(GSF_OPTIONS_FONT_USING, $font_option);

		echo 1;
		die();
	}

	add_action('wp_ajax_nopriv_gsf_reset_theme_options', 'gsf_ajax_reset_theme_options');
	add_action('wp_ajax_gsf_reset_theme_options', 'gsf_ajax_reset_theme_options');
}

/**
 * Ajax reset options in section
 *
 * @package SmartFramework
 * @subpackage Function
 * @author g5plus
 * @since 1.0
 * @return int = 1: success, string: error string
 */
if (!function_exists('gsf_ajax_reset_section_options')) {
	function gsf_ajax_reset_section_options() {
		$page = $_POST['_current_page'];
		$configs = &gsf_get_options_config($page);
		$options_name = $configs['option_name'];

		if ( ! wp_verify_nonce( $_POST['wpnonce'], $options_name ) ) {
			return;
		}
		$section = $_POST['section'];
		if (!empty($section)) {
			$section = substr($section, 8);
		}

		$config_keys = gsf_get_option_config_keys($configs);
		$options = get_option($options_name);

		$font_using=array();
		foreach ($config_keys as $key => $value) {
			if ($value['section'] == $section) {
				$options[$key] = wp_unslash($value['default']);
			}
			if ($value['type'] === 'font') {
				$font_using[$key] = wp_unslash($value['default']);
			}
		}
		update_option($options_name, $options);

		$font_option = get_option(GSF_OPTIONS_FONT_USING);
		$font_option[$options_name] = $font_using;
		update_option(GSF_OPTIONS_FONT_USING, $font_option);

		echo 1;
		die();
	}

	add_action('wp_ajax_nopriv_gsf_reset_section_options', 'gsf_ajax_reset_section_options');
	add_action('wp_ajax_gsf_reset_section_options', 'gsf_ajax_reset_section_options');
}

/**
 * Get GLOBAL options config
 * Change options config by filter: gsf_option_config
 *
 * @since   1.0
 * @return  array
 */
if (!function_exists('gsf_get_options_config')) {
	function &gsf_get_options_config($page = '') {
		if (!isset($GLOBALS['gsf_option_config'])) {
			$GLOBALS['gsf_option_config'] = apply_filters('gsf_option_config', array());
		}
		if ($page === '') {
			return $GLOBALS['gsf_option_config'];
		}
		if (isset($GLOBALS['gsf_option_config'][$page])) {
			return $GLOBALS['gsf_option_config'][$page];
		}
		return array();

	}
}

/**
 * Get GLOBAL meta box config
 * Change meta box config by filter: gsf_meta_box_config
 *
 * @since   1.0
 * @return  array
 */
if (!function_exists('gsf_get_meta_boxes_config')) {
	function &gsf_get_meta_boxes_config() {
		if (!isset($GLOBALS['gsf_meta_box_config'])) {
			$GLOBALS['gsf_meta_box_config'] = apply_filters('gsf_meta_box_config', array());
		}
		return $GLOBALS['gsf_meta_box_config'];
	}
}

/**
 * Get GLOBAL term meta config
 * Change meta box config by filter: gsf_term_meta_config
 *
 * @since   1.0
 * @return  array
 */
if (!function_exists('gsf_get_term_meta_config')) {
	function &gsf_get_term_meta_config() {
		if (!isset($GLOBALS['gsf_term_meta_config'])) {
			$GLOBALS['gsf_term_meta_config'] = apply_filters('gsf_term_meta_config', array());
		}
		return $GLOBALS['gsf_term_meta_config'];
	}
}