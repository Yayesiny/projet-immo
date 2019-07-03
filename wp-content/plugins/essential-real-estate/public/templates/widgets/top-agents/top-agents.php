<?php
/**
 * Created by G5Theme.
 * User: Kaga
 * Date: 21/12/2016
 * Time: 9:35 AM
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$number = (!empty($instance['number'])) ? absint($instance['number']) : 3;
if (!$number)
	$number = 3;

$args = array(
	'post_type'           => 'agent',
	'ignore_sticky_posts' => true,
	'post_status'         => 'publish',
);
$data = new WP_Query($args);
$array_agent = array();

if ($data->have_posts()):
	$ere_property = new ERE_Property();
	while ($data->have_posts()): $data->the_post();
		$agent_id = get_the_ID();
		$agent_user_id = get_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_user_id', true);
		$user = get_user_by('id', $agent_user_id);
		if (empty($user)) {
			$agent_user_id = 0;
		}
		$total_property = $ere_property->get_total_properties_by_user($agent_id, $agent_user_id);
		$array_agent[$agent_id] = ($total_property);
	endwhile;
endif;

arsort($array_agent);

$agent_id_arr = array_keys($array_agent);
$total_property_arr = array_values($array_agent);

$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
wp_print_styles( ERE_PLUGIN_PREFIX . 'top-agents');

?>
	<div class="ere-list-top-agents-wrap">
		<div class="ere-list-top-agents">
			<?php if ($data->have_posts()):
				$width = 270; $height = 340;
				$no_avatar_src= ERE_PLUGIN_URL . 'public/assets/images/profile-avatar.png';
				$default_avatar=ere_get_option('default_user_avatar','');
				if($default_avatar!='')
				{
					if(is_array($default_avatar)&& $default_avatar['url']!='')
					{
						$resize = ere_image_resize_url($default_avatar['url'], $width, $height, true);
						if ($resize != null && is_array($resize)) {
							$no_avatar_src = $resize['url'];
						}
					}
				}
				for ($i = 0; $i < $number; $i++) {
					$agent_id = $agent_id_arr[$i];
					$agent_name = get_the_title($agent_id);
					$agent_link = get_the_permalink($agent_id);

					$agent_position = get_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_position', true);

					$avatar_id = get_post_thumbnail_id($agent_id);
					$avatar_src = ere_image_resize_id($avatar_id, $width, $height, true);
					$total_property = $total_property_arr[$i];

					?>
					<div class="agent-item">
						<div class="agent-avatar"><a title="<?php echo esc_attr($agent_name) ?>"
													 href="<?php echo esc_url($agent_link) ?>">
							<img src="<?php echo esc_url($avatar_src) ?>"
								 onerror="this.src = '<?php echo esc_url($no_avatar_src) ?>';"
								 alt="<?php echo esc_attr($agent_name) ?>"
								 title="<?php echo esc_attr($agent_name) ?>"></a>
						</div>
						<div class="agent-info">
							<?php if (!empty($agent_name)): ?>
								<h2 class="agent-name"><a title="<?php echo esc_attr($agent_name) ?>"
														  href="<?php echo esc_url($agent_link) ?>"><?php echo esc_html($agent_name) ?></a>
								</h2>
							<?php endif;
							if (!empty($agent_position)): ?>
								<span class="agent-position"><?php echo esc_html($agent_position) ?></span>
							<?php endif; ?>
							<div class="agent-total-properties">
							<?php
							printf( _n( '<span class="total-properties">%s</span> property', '<span class="total-properties">%s</span> properties', $total_property, 'essential-real-estate' ), ere_get_format_number($total_property ));
							?>
							</div>
						</div>
					</div>
				<?php
				}
			else: ?>
				<div class="item-not-found"><?php esc_html_e('No item found', 'essential-real-estate'); ?></div>
			<?php endif; ?>
		</div>
	</div>

<?php
wp_reset_postdata();