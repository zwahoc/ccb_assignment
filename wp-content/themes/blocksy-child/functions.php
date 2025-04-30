<?php

if (! defined('WP_DEBUG')) {
	die( 'Direct access forbidden.' );
}

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
});

function rewrite_asset_urls_to_s3($url) {
    // Define your CDN or S3 base URL
    $cdn_base = 'https://tarumt-grad-hub-bucket.s3.us-east-1.amazonaws.com'; // or your S3 public URL

    // Only replace for wp-includes and wp-content assets
    if (strpos($url, '/wp-includes/') !== false || strpos($url, '/wp-content/') !== false) {
        $url = $cdn_base . parse_url($url, PHP_URL_PATH);
    }

    return $url;
}

add_filter('script_loader_src', 'rewrite_asset_urls_to_s3');
add_filter('style_loader_src', 'rewrite_asset_urls_to_s3');

