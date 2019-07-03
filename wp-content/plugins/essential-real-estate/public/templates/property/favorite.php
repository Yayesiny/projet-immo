<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 30/12/2016
 * Time: 8:20 SA
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
global $current_user;
wp_get_current_user();
$key = false;
$user_id = $current_user->ID;
$my_favorites = get_user_meta($user_id, ERE_METABOX_PREFIX . 'favorites_property', true);
$property_id= get_the_ID();
if (!empty($my_favorites)) {
    $key = array_search($property_id, $my_favorites);
}
$title_not_favorite = $title_favorited = '';
if ($key !== false) {
    $css_class = 'fa fa-star';
    $title = esc_attr__('It is my favorite', 'essential-real-estate');
} else {
    $css_class = 'fa fa-star-o';
    $title =esc_attr__('Add to Favorite', 'essential-real-estate');
}
?>
<a href="javascript:void(0)" class="property-favorite" data-property-id="<?php echo intval($property_id) ?>"
   data-toggle="tooltip"
   title="<?php echo($title) ?>" data-title-not-favorite="<?php esc_attr_e('Add to Favorite', 'essential-real-estate') ?>" data-title-favorited="<?php esc_attr_e('It is my favorite', 'essential-real-estate'); ?>" data-icon-not-favorite="fa fa-star-o" data-icon-favorited="fa fa-star"><i
        class="<?php echo esc_attr($css_class); ?>"></i></a>