<?php
/**
 * Plugin Name: Display Review IDs for WooCommerce
 * Plugin URI: https://projektisle.com/pi-plugins/display-review-ids-for-woocommerce
 * Description: Adds a custom column to the WooCommerce reviews page to display the unique Review ID.
 * Version: 1.0.0
 * Author: ProjektIsle
 * Author URI: https://projektisle.com
 * Requires Plugins: woocommerce
 * License: GPLv3 or later
 * Text Domain: display-review-ids-for-woocommerce
 * Domain Path:/languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// 1. Add column.
add_filter('manage_product_page_product-reviews_columns', 'pidriwoo_add_review_id_column');

function pidriwoo_add_review_id_column($columns) {
    $new_columns = array();
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ('comment' === $key) {
            $new_columns['pidriwoo-review-ids'] = __('Review IDs', 'display-review-ids-for-woocommerce');
        }
    }
    return $new_columns;
}

// 2. Populate column with the review IDs.
add_action('woocommerce_product_reviews_table_column_pidriwoo-review-ids', 'pidriwoo_display_review_id', 10, 1);

function pidriwoo_display_review_id($comment) {
    if ($comment && is_a($comment, 'WP_Comment')) {
        echo esc_html($comment->comment_ID);
    }
}

// 3. "Get Review IDs" link to the Reviews admin Page.
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'pidriwoo_add_action_links');

function pidriwoo_add_action_links($links) {
    $reviews_link = '<a href="' . esc_url(admin_url('edit.php?post_type=product&page=product-reviews')) . '">' . __('Get Review IDs', 'display-review-ids-for-woocommerce') . '</a>';
    array_unshift($links, $reviews_link);
    return $links;
}

