<?php

if (! class_exists('WooCommerce')) {
	return;
}

$value_fallback = blocksy_akg('fallback', $attributes, '');

$value = '';

$has_fallback = false;

$product = wc_get_product();

if (! $product) {
	return;
}

if ($field === 'woo:price') {
	$value = $product->get_price_html();
}

if ($field === 'woo:stock_status') {
	$value = $product->get_stock_status() === 'instock' ?
			__('In Stock', 'blocksy-companion') :
			__('Out of Stock', 'blocksy-companion');
}

if ($field === 'woo:sku') {
	if ( $product->get_sku() ) {
		$value = $product->get_sku();
	}
}

if ($field === 'woo:rating') {
	ob_start();
	woocommerce_template_loop_rating();
	$value = ob_get_clean();
}

if ($field === 'woo:brands') {
	$value = blocksy_render_view(
		dirname(__FILE__) . '/brands-grid.php',
		[
			'attributes' => $attributes
		]
	);
}

if (empty(trim($value))) {
	return;
}

$value_after = blocksy_akg('after', $attributes, '');
$value_before = blocksy_akg('before', $attributes, '');

if (! empty($value_after) && ! $has_fallback) {
	$value .= $value_after;
}

if (! empty($value_before) && ! $has_fallback) {
	$value = $value_before . $value;
}

$tagName = blocksy_akg('tagName', $attributes, 'div');

$classes = ['ct-dynamic-data'];

if (! empty($attributes['align'])) {
	$classes[] = 'has-text-align-' . $attributes['align'];
}

$wrapper_attr['class'] = implode(' ', $classes);

$border_result = get_block_core_post_featured_image_border_attributes(
	$attributes
);

if (! empty($border_result['class'])) {
	$wrapper_attr['class'] .= ' ' . $border_result['class'];
}

if (! empty($border_result['style'])) {
	$wrapper_attr['style'] .= $border_result['style'];
}

$block_type = WP_Block_Type_Registry::get_instance()->get_registered('blocksy/dynamic-data');
$block_type->supports['color'] = true;
wp_apply_colors_support($block_type, $attributes);

$wrapper_attr = get_block_wrapper_attributes($wrapper_attr);

echo blocksy_html_tag(
	$tagName,
	$wrapper_attr,
	$value
);

