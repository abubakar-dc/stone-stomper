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

			$sts_var_product_title     = get_the_title($product_id);
			$sts_var_product_permalink = get_permalink($product_id);
			$sts_var_product_description = $product->get_description();
			$sts_var_product_price = $product->get_price();
			?>
				<div class="slick-slide">
					<div class="product-item">
						<div class="product-image">
							<?php StoneStomper::the_attachment_image( get_post_thumbnail_id( $product_id ), 500 ); ?>
						</div>
						<div class="product-title">
							<h3>
								<?php echo html_entity_decode($sts_var_product_title); ?>
							</h3>
						</div>
						<div class="product-description">
							<?php echo html_entity_decode($sts_var_product_description); ?>
						</div>
						<div class="product-price">
							<?php echo html_entity_decode($sts_var_product_price); ?>
						</div>
						<a href="#" class="add-to-cart">Add to Cart</a>
					</div>
				</div>
		<?php } ?>
	</div>
<?php } else { ?>
	<h2>Please select a post from the settings panel on the right.</h2>
<?php } ?>
