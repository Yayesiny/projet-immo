<?php
/**
 * @var $extra
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$taxonomy = array_key_exists('taxonomy', $extra) ? $extra['taxonomy'] : 'type';
$taxonomy_items = array();
switch ($taxonomy) {
    case 'type':
        $taxonomy_items = array_key_exists('types', $extra) ? $extra['types'] : array();
        break;
    case 'status':
        $taxonomy_items = array_key_exists('status', $extra) ? $extra['status'] : array();
        break;
    case 'city':
        $taxonomy_items = array_key_exists('cities', $extra) ? $extra['cities'] : array();
        break;
    case 'feature':
        $taxonomy_items = array_key_exists('features', $extra) ? $extra['features'] : array();
        break;
    case 'neighborhood':
        $taxonomy_items = array_key_exists('neighborhoods', $extra) ? $extra['neighborhoods'] : array();
        break;
    case 'state':
        $taxonomy_items = array_key_exists('states', $extra) ? $extra['states'] : array();
        break;
    case 'label':
        $taxonomy_items = array_key_exists('labels', $extra) ? $extra['labels'] : array();
        break;
    default:
        $taxonomy_items = array_key_exists('types', $extra) ? $extra['types'] : array();
        break;
}

$columns = array_key_exists('columns', $extra) ? $extra['columns'] : '1';
$show_count = array_key_exists('show_count', $extra) ? $extra['show_count'] : '0';
$color_scheme = array_key_exists('color_scheme', $extra) ? $extra['color_scheme'] : 'scheme-dark';

$widget_wrapper_classes = array('ere-widget-listing-property-taxonomy clearfix', $color_scheme);
if($columns==='2') {
    $widget_wrapper_classes[] = 'taxonomy-2-columns';
}

$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
wp_print_styles( ERE_PLUGIN_PREFIX . 'listing-property-taxonomy-widget');
?>
    <div class="<?php echo implode( ' ',$widget_wrapper_classes  ); ?>">
        <ul>
            <?php foreach ($taxonomy_items as $item){
                $term = get_term_by( 'slug', $item, 'property-'.$taxonomy, 'OBJECT' );
                $count = '';
                if($term!=false)
                {
                    if($show_count) {
                        $count = '<span class="item-count">('.$term->count.')</span>';
                    }
                    echo '<li>
                    <a href="'.get_term_link( $item, 'property-'.$taxonomy ).'">
                        <i class="fa fa-caret-right"></i> '.$term->name.$count.'</a>
                </li>';
                }
            }?>
        </ul>
    </div>
<?php
wp_reset_postdata();