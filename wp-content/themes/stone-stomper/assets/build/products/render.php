<?php
/**
 * Block Render Callback Template
 *
 * This file is used to render dynamic blocks via PHP.
 *
 * @link https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/#render_callback
 *
 * @package Base_Theme_Package
 * @since 1.0.0
 */


$sts_var_elevate_ids = $attributes['productIds'] ?? null;
$selectionMode = $attributes['selectionMode'] ?? 'recent';
$heading = $attributes['heading'] ?? '';


?>
<?php if ($sts_var_elevate_ids) { ?>
	<div class="products-teaser-slider">
		<?php foreach ($sts_var_elevate_ids as $key =>  $post_data) {
			$product_id = $post_data['value']; // Extract the post ID
			list($product_id, $sts_fields, $sts_perks_fields) = StoneStomper::defaults($product_id);
			$product = wc_get_product( $product_id );
			$upsells = $product->get_upsell_ids();
			?>
				<div class="slick-slide">
					<?php
					get_template_part(
						'partials/content',
						'archive-product',
						array( 'sts_var_product_id' => $product_id )
					);
?>
				</div>
		<?php } ?>
	</div>
<?php } else { ?>
	<h2>Please select a post from the settings panel on the right.</h2>
<?php } ?>
