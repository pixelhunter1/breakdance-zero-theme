<?php

if (!function_exists('breakdance_zero_theme_setup')) {
    function breakdance_zero_theme_setup()
    {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');

        // Add WooCommerce support
        add_theme_support('woocommerce');

        // Add support for WooCommerce product gallery features
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }
}

add_action('after_setup_theme', 'breakdance_zero_theme_setup');


if (!function_exists('warn_if_breakdance_is_disabled')) {
    add_action( 'admin_notices', 'warn_if_breakdance_is_disabled' );

    function warn_if_breakdance_is_disabled() {
        if (defined('__BREAKDANCE_DIR__')){
            return;
        }

        ?>
        <div class="notice notice-error is-dismissible">
            <p>You're using Breakdance's Zero Theme but Breakdance is not enabled. This isn't supported.</p>
        </div>
        <?php
    }
}

/**
 * WooCommerce specific functions and customizations
 */

// Enqueue WooCommerce styles
function zero_theme_woocommerce_scripts() {
    wp_enqueue_style('zero-woocommerce', get_template_directory_uri() . '/woocommerce.css', array(), '1.0.0');
}
add_action('wp_enqueue_scripts', 'zero_theme_woocommerce_scripts');

// Remove default WooCommerce wrapper
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

// Add custom wrapper
if (!function_exists('zero_theme_woocommerce_wrapper_before')) {
    function zero_theme_woocommerce_wrapper_before() {
        ?>
        <div class="zero-woocommerce-wrapper">
        <?php
    }
}
add_action('woocommerce_before_main_content', 'zero_theme_woocommerce_wrapper_before', 10);

if (!function_exists('zero_theme_woocommerce_wrapper_after')) {
    function zero_theme_woocommerce_wrapper_after() {
        ?>
        </div>
        <?php
    }
}
add_action('woocommerce_after_main_content', 'zero_theme_woocommerce_wrapper_after', 10);

// Change number of products per row
function zero_theme_loop_columns() {
    return 4; // 4 products per row
}
add_filter('loop_shop_columns', 'zero_theme_loop_columns', 999);

// Remove some default WooCommerce elements
function zero_theme_remove_woocommerce_elements() {
    // Remove result count
    remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);

    // Remove default sorting dropdown
    remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

    // Remove product rating from archive pages
    remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
}
add_action('init', 'zero_theme_remove_woocommerce_elements');

// Add custom classes to products
function zero_theme_product_classes($classes, $product) {
    $classes[] = 'zero-product';
    return $classes;
}
add_filter('woocommerce_post_class', 'zero_theme_product_classes', 10, 2);

// Customize add to cart button
function zero_theme_loop_add_to_cart_args($args) {
    $args['class'] = 'zero-add-to-cart ' . $args['class'];
    return $args;
}
add_filter('woocommerce_loop_add_to_cart_args', 'zero_theme_loop_add_to_cart_args', 10, 1);