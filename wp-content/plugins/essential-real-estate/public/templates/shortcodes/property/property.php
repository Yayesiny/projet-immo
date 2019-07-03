<?php
/**
 * Shortcode attributes
 * @var $atts
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$layout_style = $property_type = $property_status = $property_feature = $property_city = $property_state = $property_neighborhood =
$property_label = $property_featured = $item_amount = $columns_gap = $columns = $items_md = $items_sm = $items_xs = $items_mb =
$view_all_link = $image_size = $show_paging = $include_heading = $heading_sub_title = $heading_title =
$dots = $nav = $move_nav = $nav_position = $autoplay = $autoplaytimeout = $paged = $author_id = $agent_id = $el_class = '';
extract(shortcode_atts(array(
    'layout_style' => 'property-grid',
    'property_type' => '',
    'property_status' => '',
    'property_feature' => '',
    'property_city' => '',
    'property_state' => '',
    'property_neighborhood' => '',
    'property_label' => '',
    'property_featured' => '',
    'item_amount' => '6',
    'columns_gap' => 'col-gap-30',
    'columns' => '3',
    'items_lg' => '4',
    'items_md' => '3',
    'items_sm' => '2',
    'items_xs' => '1',
    'items_mb' => '1',
    'view_all_link' => '',
    'image_size' => '330x180',
    'show_paging' => '',
    'include_heading' => '',
    'heading_sub_title' => '',
    'heading_title' => '',
    'dots' => '',
    'nav' => 'true',
    'move_nav' => '',
    'nav_position' => '',
    'autoplay' => 'true',
    'autoplaytimeout' => '1000',
    'paged' => '1',
    'author_id' => '',
    'agent_id' => '',
    'el_class' => ''
), $atts));
$property_item_class = array('ere-item-wrap property-item');
$property_content_class = array('property-content');
$property_content_attributes = array();
$wrapper_attributes = array();
$wrapper_classes = array(
    'ere-property clearfix',
    $layout_style,
    $el_class
);

if ($layout_style == 'property-zigzac' || $layout_style == 'property-list') {
    $columns_gap = 'col-gap-0';
}
if ($layout_style == 'property-carousel') {
    $property_content_class[] = 'owl-carousel manual';
    if ($nav) {
        if (!$move_nav && !empty($nav_position)) {
            $property_content_class[] = 'owl-nav-' . $nav_position;
        } elseif ($move_nav) {
            $property_content_class[] = 'owl-nav-top-right';
            $wrapper_classes[] = 'owl-move-nav-par-with-heading';
        }
    }
    if ($columns_gap == 'col-gap-30') {
        $col_gap = 30;
    } elseif ($columns_gap == 'col-gap-20') {
        $col_gap = 20;
    } elseif ($columns_gap == 'col-gap-10') {
        $col_gap = 10;
    } else {
        $col_gap = 0;
    }

    $owl_responsive_attributes = array();
    // Mobile <= 480px
    $owl_responsive_attributes[] = '"0" : {"items" : ' . $items_mb . ', "margin": ' . (($items_mb > 1) ? $col_gap : '0') . '}';

    // Extra small devices ( < 768px)
    $owl_responsive_attributes[] = '"480" : {"items" : ' . $items_xs . ', "margin": ' . (($items_xs > 1) ? $col_gap : '0') . '}';

    // Small devices Tablets ( < 992px)
    $owl_responsive_attributes[] = '"768" : {"items" : ' . $items_sm . ', "margin": ' . (($items_sm > 1) ? $col_gap : '0') . '}';

    // Medium devices ( < 1199px)
    $owl_responsive_attributes[] = '"992" : {"items" : ' . $items_md . ', "margin": ' . (($items_md > 1) ? $col_gap : '0') . '}';

    // Medium devices ( > 1199px)
    $owl_responsive_attributes[] = '"1200" : {"items" : ' . (($columns >= 4) ? 4 : $columns) . ', "margin": ' . (($columns > 1) ? $col_gap : '0') . '}';

    $owl_responsive_attributes[] = '"1820" : {"items" : ' . $columns . ', "margin": ' . $col_gap . '}';

    $owl_attributes = array(
        '"dots": ' . ($dots ? 'true' : 'false'),
        '"nav": ' . ($nav ? 'true' : 'false'),
        '"autoplay": ' . ($autoplay ? 'true' : 'false'),
        '"autoplayTimeout": ' . $autoplaytimeout,
        '"responsive": {' . implode(', ', $owl_responsive_attributes) . '}'
    );
    $property_content_attributes[] = "data-plugin-options='{" . implode(', ', $owl_attributes) . "}'";
} else {
    $wrapper_classes[] = $columns_gap;
    if ($columns_gap == 'col-gap-30') {
        $property_item_class[] = 'mg-bottom-30';
    } elseif ($columns_gap == 'col-gap-20') {
        $property_item_class[] = 'mg-bottom-20';
    } elseif ($columns_gap == 'col-gap-10') {
        $property_item_class[] = 'mg-bottom-10';
    }

    if ($layout_style == 'property-grid') {
        $property_content_class[] = 'columns-' . $columns . ' columns-md-' . $items_md . ' columns-sm-' . $items_sm . ' columns-xs-' . $items_xs . ' columns-mb-' . $items_mb;
    }
    if ($layout_style == 'property-list') {
        //$image_size = '330x180';
        $property_item_class[] = 'mg-bottom-30';
    }
    if ($layout_style == 'property-zigzac') {
        //$image_size = '290x270';
        $property_content_class[] = 'columns-2 columns-md-2 columns-sm-1';
    }
}

if (!empty($view_all_link)) {
    $wrapper_attributes[] = "data-view-all-link='" . $view_all_link . "'";
}

$args = array(
    'posts_per_page' => ($item_amount > 0) ? $item_amount : -1,
    'post_type' => 'property',
    'paged' => $paged,
    'post_status' => 'publish',
    'orderby'   => array(
        'menu_order'=>'ASC',
        'date' =>'DESC',
    ),
);
$featured_toplist = ere_get_option('featured_toplist', 1);
if($featured_toplist!=0)
{
    $args['orderby'] = array(
        'menu_order'=>'ASC',
        'meta_value_num' => 'DESC',
        'date' => 'DESC',
    );
    $args['meta_key'] = ERE_METABOX_PREFIX . 'property_featured';
}
if (!empty($property_type) || !empty($property_status) || !empty($property_feature) || !empty($property_city)
    || !empty($property_state) || !empty($property_neighborhood) || !empty($property_label)
) {
    $args['tax_query'] = array();
    if (!empty($property_type)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'property-type',
            'field' => 'slug',
            'terms' => explode(',', $property_type),
            'operator' => 'IN'
        );
    }
    if (!empty($property_status)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'property-status',
            'field' => 'slug',
            'terms' => explode(',', $property_status),
            'operator' => 'IN'
        );
    }
    if (!empty($property_feature)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'property-feature',
            'field' => 'slug',
            'terms' => explode(',', $property_feature),
            'operator' => 'IN'
        );
    }
    if (!empty($property_city)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'property-city',
            'field' => 'slug',
            'terms' => explode(',', $property_city),
            'operator' => 'IN'
        );
    }
    if (!empty($property_state)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'property-state',
            'field' => 'slug',
            'terms' => explode(',', $property_state),
            'operator' => 'IN'
        );
    }
    if (!empty($property_neighborhood)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'property-neighborhood',
            'field' => 'slug',
            'terms' => explode(',', $property_neighborhood),
            'operator' => 'IN'
        );
    }
    if (!empty($property_label)) {
        $args['tax_query'][] = array(
            'taxonomy' => 'property-label',
            'field' => 'slug',
            'terms' => explode(',', $property_label),
            'operator' => 'IN'
        );
    }
}
$args['meta_query'] = array();
if (!empty($author_id) && !empty($agent_id)) {
    $args['meta_query'] = array(
        'relation' => 'OR',
        array(
            'key' => ERE_METABOX_PREFIX . 'property_agent',
            'value' => explode(',', $agent_id),
            'compare' => 'IN'
        ),
        array(
            'key' => ERE_METABOX_PREFIX . 'property_author',
            'value' => explode(',', $author_id),
            'compare' => 'IN'
        )
    );
} else {
    if (!empty($author_id)) {
        $args['author'] = $author_id;
    } else if (!empty($agent_id)) {
        $args['meta_query'] = array(
            array(
                'key' => ERE_METABOX_PREFIX . 'property_agent',
                'value' => explode(',', $agent_id),
                'compare' => 'IN'
            )
        );
    }
}

if ($property_featured == 'true') {
    $args['meta_query'][] = array(
        'key' => ERE_METABOX_PREFIX . 'property_featured',
        'value' => true,
        'compare' => '=',
    );
}
$data = new WP_Query($args);
$total_post = $data->found_posts;

$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
wp_print_styles(ERE_PLUGIN_PREFIX . 'property');

$min_suffix_js = ere_get_option('enable_min_js', 0) == 1 ? '.min' : '';
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'owl_carousel', ERE_PLUGIN_URL . 'public/assets/js/ere-carousel' . $min_suffix_js . '.js', array('jquery'), ERE_PLUGIN_VER, true);
?>
<div class="ere-property-wrap">
    <div class="<?php echo join(' ', $wrapper_classes) ?>" <?php echo implode(' ', $wrapper_attributes); ?>>
        <?php if ($include_heading) :
            $heading_class='';
            ?>
        <div class="container">
            <div class="ere-heading ere-item-wrap <?php echo esc_attr($heading_class); ?>">
                <?php if (!empty($heading_title)): ?>
                    <h2><?php echo esc_html($heading_title); ?></h2>
                <?php endif; ?>
                <?php if (!empty($heading_sub_title)): ?>
                    <p><?php echo esc_html($heading_sub_title); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        <?php if ($layout_style == 'property-carousel'): ?>
        <div class="<?php echo join(' ', $property_content_class) ?>" data-section-id="<?php echo uniqid(); ?>"
             data-callback="owl_callback" <?php echo implode(' ', $property_content_attributes); ?>>
            <?php else: ?>
            <div class="<?php echo join(' ', $property_content_class) ?>">
                <?php endif; ?>
                <?php if ($data->have_posts()) :
                    $index = 0;
                    $no_image_src= ERE_PLUGIN_URL . 'public/assets/images/no-image.jpg';
                    $default_image=ere_get_option('default_property_image','');
                    if (preg_match('/\d+x\d+/', $image_size)) {
                        $image_sizes = explode('x', $image_size);
                        $width=$image_sizes[0];$height= $image_sizes[1];
                        if($default_image!='')
                        {
                            if(is_array($default_image)&& $default_image['url']!='')
                            {
                                $resize = ere_image_resize_url($default_image['url'], $width, $height, true);
                                if ($resize != null && is_array($resize)) {
                                    $no_image_src = $resize['url'];
                                }
                            }
                        }
                    } else {
                        if($default_image!='')
                        {
                            if(is_array($default_image)&& $default_image['url']!='')
                            {
                                $no_image_src = $default_image['url'];
                            }
                        }
                    }
                    while ($data->have_posts()): $data->the_post();
                        $property_id=get_the_ID();
                        $attach_id = get_post_thumbnail_id();
                        $image_src = '';
                        $item_class = '';
                        $width = 330; $height = 180;
                        if (preg_match('/\d+x\d+/', $image_size)) {
                            $image_sizes = explode('x', $image_size);
                            $width=$image_sizes[0];$height= $image_sizes[1];
                            $image_src = ere_image_resize_id($attach_id, $width, $height, true);
                        } else {
                            if (!in_array($image_size, array('full', 'thumbnail'))) {
                                $image_size = 'full';
                            }
                            $image_src = wp_get_attachment_image_src($attach_id, $image_size);
                            if ($image_src && !empty($image_src[0])) {
                                $image_src = $image_src[0];
                            }
                            if (!empty($image_src)) {
                                list($width, $height) = getimagesize($image_src);
                            }
                        }
                        $excerpt = get_the_excerpt();

                        $property_meta_data = get_post_custom($property_id);

                        $price = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price'][0] : '';
                        $price_short = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_short']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_short'][0] : '';
                        $price_unit = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_unit']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_unit'][0] : '';
                        $price_prefix = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_prefix']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_prefix'][0] : '';
                        $price_postfix = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price_postfix']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price_postfix'][0] : '';
                        $property_address = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_address']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_address'][0] : '';
                        $property_size = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_size']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_size'][0] : '';
                        $property_bedrooms = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_bedrooms']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_bedrooms'][0] : '0';
                        $property_bathrooms = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_bathrooms']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_bathrooms'][0] : '0';


                        $property_item_featured = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_featured']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_featured'][0] : '0';

                        // Get Agent name
                        $agent_display_option = isset($property_meta_data[ERE_METABOX_PREFIX . 'agent_display_option']) ? $property_meta_data[ERE_METABOX_PREFIX . 'agent_display_option'][0] : '';
                        $property_agent = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_agent']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_agent'][0] : '';
                        $agent_name = $agent_link = '';
                        if ($agent_display_option == 'author_info') {
                            global $post;
                            $user_id = $post->post_author;
                            $user_info = get_userdata($user_id);

                            if(empty($user_info->first_name) && empty($user_info->last_name))
                            {
                                $agent_name=$user_info->user_login;
                            }
                            else
                            {
                                $agent_name     = $user_info->first_name . ' ' . $user_info->last_name;
                            }
                            $author_agent_id = get_the_author_meta(ERE_METABOX_PREFIX . 'author_agent_id', $user_id);
                            if(empty($author_agent_id))
                            {
                                $agent_link = get_author_posts_url($user_id);
                            }
                            else
                            {
                                $agent_link = get_the_permalink($author_agent_id);
                            }

                        } elseif ($agent_display_option == 'other_info') {
                            $agent_name = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_other_contact_name']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_other_contact_name'][0] : '';
                        } elseif ($agent_display_option == 'agent_info' && !empty($property_agent)) {
                            $agent_name = get_the_title($property_agent);
                            $agent_link = get_the_permalink($property_agent);
                        }

                        $property_item_type = get_the_terms($property_id, 'property-type');
                        $property_item_label = get_the_terms($property_id, 'property-label');
                        $property_item_status = get_the_terms($property_id, 'property-status');

                        $property_link = get_the_permalink();
                        $property_avatar_class = array();
                        $property_item_content_class = array();

                        if ($layout_style == 'property-zigzac') {
                            if (($index + 1) % 4 == 0) {
                                $property_avatar_class[] = 'col-md-push-6 col-sm-push-6';
                                $property_item_content_class[] = 'col-md-pull-6 col-sm-pull-6';
                            }
                            if (($index + 2) % 4 == 0) {
                                $property_avatar_class[] = 'col-md-push-6';
                                $property_item_content_class[] = 'col-md-pull-6';
                            }
                            if (($index + 3) % 4 == 0) {
                                $property_avatar_class[] = 'col-md-push-0 col-sm-push-6';
                                $property_item_content_class[] = 'col-md-pull-0 col-sm-pull-6';
                            }
                        }
                        ?>
                        <div class="<?php echo join(' ', $property_item_class); ?>">
                            <div class="property-inner">
                                <div class="property-image <?php echo join(' ', $property_avatar_class); ?>">
                                    <img width="<?php echo esc_attr($width) ?>"
                                         height="<?php echo esc_attr($height) ?>"
                                         src="<?php echo esc_url($image_src) ?>" onerror="this.src = '<?php echo esc_url($no_image_src) ?>';" alt="<?php the_title(); ?>"
                                         title="<?php the_title(); ?>">
                                    <div class="property-action block-center">
                                        <div class="block-center-inner">
                                            <?php
                                            /**
                                             * ere_property_action hook.
                                             *
                                             * @hooked property_social_share - 5
                                             * @hooked property_favorite - 10
                                             * @hooked property_compare - 15
                                             */
                                            do_action('ere_property_action'); ?>
                                        </div>
                                        <a class="property-link" href="<?php echo esc_url($property_link); ?>"
                                           title="<?php the_title(); ?>"></a>
                                    </div>
                                    <?php if ($property_item_label || $property_item_featured): ?>
                                        <div class="property-label property-featured">
                                            <?php if ($property_item_featured): ?>
                                                <p class="label-item">
                                                    <span
                                                        class="property-label-bg"><?php esc_html_e('Featured', 'essential-real-estate'); ?>
                                                        <span class="property-arrow"></span></span>
                                                </p>
                                            <?php endif; ?>
                                            <?php if ($property_item_label): ?>
                                                <?php foreach ($property_item_label as $label_item): ?>
                                                    <?php $label_color = get_term_meta($label_item->term_id, 'property_label_color', true); ?>
                                                    <p class="label-item">
                                                    <span class="property-label-bg"
                                                          style="background-color: <?php echo esc_attr($label_color) ?>"><?php echo esc_html($label_item->name) ?>
                                                        <span class="property-arrow"
                                                              style="border-left-color: <?php echo esc_attr($label_color) ?>; border-right-color: <?php echo esc_attr($label_color) ?>"></span>
                                                    </span>
                                                    </p>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($property_item_status): ?>
                                        <div class="property-status">
                                            <?php foreach ($property_item_status as $status): ?>
                                                <?php $status_color = get_term_meta($status->term_id, 'property_status_color', true); ?>
                                                <p class="status-item">
                                        <span class="property-status-bg"
                                              style="background-color: <?php echo esc_attr($status_color) ?>"><?php echo esc_html($status->name) ?>
                                            <span class="property-arrow"
                                                  style="border-left-color: <?php echo esc_attr($status_color) ?>; border-right-color: <?php echo esc_attr($status_color) ?>"></span>
                                        </span>
                                                </p>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div
                                    class="property-item-content <?php echo join(' ', $property_item_content_class); ?>">
                                    <div class="property-heading">
                                        <h2 class="property-title"><a
                                                href="<?php echo esc_url($property_link); ?>"
                                                title="<?php the_title(); ?>"><?php the_title() ?></a>
                                        </h2>
                                        <?php if (!empty($price)): ?>
                                            <div class="property-price">
                                                <span>
                                                    <?php if (!empty($price_prefix)) {
                                                        echo '<span class="property-price-prefix">' . $price_prefix . ' </span>';
                                                    } ?>
                                                    <?php echo ere_get_format_money($price_short,$price_unit) ?>
                                                    <?php if (!empty($price_postfix)) {
                                                        echo '<span class="property-price-postfix"> / ' . $price_postfix . '</span>';
                                                    } ?>
                                                </span>
                                            </div>
                                        <?php elseif (ere_get_option('empty_price_text', '') != ''): ?>
                                            <div class="property-price">
                                                <span><?php echo ere_get_option('empty_price_text', '') ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($property_address)):
                                        $property_location = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_location', true);
                                        if($property_location)
                                        {
                                            $google_map_address_url = "http://maps.google.com/?q=" . $property_location['address'];
                                        }
                                        else
                                        {
                                            $google_map_address_url = "http://maps.google.com/?q=" . $property_address;
                                        }?>
                                        <div class="property-location" title="<?php echo esc_attr($property_address) ?>">
                                            <i class="fa fa-map-marker"></i>
                                            <a target="_blank"
                                               href="<?php echo esc_url($google_map_address_url); ?>"><span><?php echo esc_html($property_address) ?></span></a>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($layout_style != 'property-zigzac') : ?>
                                        <div class="property-element-inline">
                                            <?php if ($property_item_type): ?>
                                                <div class="property-type-list">
                                                    <i class="fa fa-tag"></i>
                                                    <?php foreach ($property_item_type as $type): ?>
                                                        <a href="<?php echo esc_url(get_term_link($type->slug, 'property-type')); ?>"
                                                           title="<?php echo esc_attr($type->name); ?>"><span><?php echo esc_html($type->name); ?> </span></a>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($agent_name)): ?>
                                                <div class="property-agent">
                                                    <?php echo !empty($agent_link) ? ('<a href="' . $agent_link . '" title="' . $agent_name . '">') : ''; ?>
                                                    <i class="fa fa-user"></i>
                                                    <span><?php echo esc_attr($agent_name) ?></span>
                                                    <?php echo !empty($agent_link) ? ('</a>') : ''; ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="property-date"><i class="fa fa-calendar"></i>
                                                <?php
                                                $get_the_time=get_the_time('U');
                                                $current_time=current_time('timestamp');
                                                $human_time_diff=human_time_diff($get_the_time, $current_time);
                                                printf(_x(' %s ago', '%s = human-readable time difference', 'essential-real-estate'), $human_time_diff); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (isset($excerpt) && !empty($excerpt)): ?>
                                        <div class="property-excerpt">
                                            <p><?php echo esc_html($excerpt) ?></p>
                                        </div>
                                    <?php endif; ?>
                                    <div class="property-info">
                                        <div class="property-info-inner">
                                            <?php if (!empty($property_size)): ?>
                                                <div class="property-area">
                                                    <div class="property-area-inner property-info-item-tooltip"
                                                         data-toggle="tooltip"
                                                         title="<?php esc_html_e('Size', 'essential-real-estate'); ?>">
                                                        <span class="fa fa-arrows"></span>
	                                            <span class="property-info-value"><?php
                                                    $measurement_units = ere_get_measurement_units();
                                                    echo (ere_get_format_number($property_size) . ' ' . $measurement_units); ?>
												</span>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($property_bedrooms)): ?>
                                                <div class="property-bedrooms">
                                                    <div class="property-bedrooms-inner property-info-item-tooltip"
                                                         data-toggle="tooltip"
                                                         title="<?php
                                                         printf( _n( '%s Bedroom', '%s Bedrooms', $property_bedrooms, 'essential-real-estate' ), $property_bedrooms );
                                                         ?>">
                                                        <span class="fa fa-hotel"></span>
                                                        <span
                                                            class="property-info-value"><?php echo esc_html($property_bedrooms) ?></span>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($property_bathrooms)): ?>
                                                <div class="property-bathrooms">
                                                    <div class="property-bathrooms-inner property-info-item-tooltip"
                                                         data-toggle="tooltip"
                                                         title="<?php
                                                         printf( _n( '%s Bathroom', '%s Bathrooms', $property_bathrooms, 'essential-real-estate' ), $property_bathrooms );
                                                         ?>">
                                                        <span class="fa fa-bath"></span>
                                                        <span
                                                            class="property-info-value"><?php echo esc_html($property_bathrooms) ?></span>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $index++; ?>
                    <?php endwhile;
                else: if (empty($agent_id) && empty($author_id)): ?>
                    <div class="item-not-found"><?php esc_html_e('No item found', 'essential-real-estate'); ?></div>
                <?php endif; ?>
                <?php endif; ?>
                <?php if ($layout_style == 'property-carousel'): ?>
            </div>
            <?php else: ?>
        </div>
        <div class="clearfix"></div>
    <?php endif; ?>
        <?php if (!empty($view_all_link)): ?>
            <div class="view-all-link">
                <a href="<?php echo esc_url($view_all_link) ?>"
                   class="btn btn-xs btn-dark btn-classic"><?php esc_html_e('View All', 'essential-real-estate') ?></a>
            </div>
        <?php endif; ?>
        <?php
        if ($show_paging) { ?>
            <div class="property-paging-wrap"
                 data-admin-url="<?php echo ERE_AJAX_URL; ?>"
                 data-layout="<?php echo esc_attr($layout_style); ?>"
                 data-items-amount="<?php echo esc_attr($item_amount); ?>"
                 data-columns="<?php echo esc_attr($columns); ?>"
                 data-image-size="<?php echo esc_attr($image_size); ?>"
                 data-columns-gap="<?php echo esc_attr($columns_gap); ?>"
                 data-view-all-link="<?php echo esc_attr($view_all_link); ?>"
                 data-property-type="<?php echo esc_attr($property_type); ?>"
                 data-property-status="<?php echo esc_attr($property_status); ?>"
                 data-property-feature="<?php echo esc_attr($property_feature); ?>"
                 data-property-city="<?php echo esc_attr($property_city); ?>"
                 data-property-state="<?php echo esc_attr($property_state); ?>"
                 data-property-neighborhood="<?php echo esc_attr($property_neighborhood); ?>"
                 data-property-label="<?php echo esc_attr($property_label); ?>"
                 data-property-featured="<?php echo esc_attr($property_featured); ?>"
                 data-author-id="<?php echo esc_attr($author_id); ?>"
                 data-agent-id="<?php echo esc_attr($agent_id); ?>">
                <?php $max_num_pages = $data->max_num_pages;
                set_query_var('paged', $paged);
                ere_get_template('global/pagination.php', array('max_num_pages' => $max_num_pages));
                ?>
            </div>
        <?php }
        wp_reset_postdata(); ?>
    </div>
</div>

