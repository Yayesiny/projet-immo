<?php
/**
 * @var $custom_property_image_size
 * @var $property_item_class
 */
/**
 * ere_before_loop_property hook.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
do_action( 'ere_before_loop_property' );
/**
 * ere_loop_property hook.
 *
 * @hooked loop_property - 10
 */
do_action( 'ere_loop_property', $property_item_class, $custom_property_image_size);
/**
 * ere_after_loop_property hook.
 */
do_action( 'ere_after_loop_property' );