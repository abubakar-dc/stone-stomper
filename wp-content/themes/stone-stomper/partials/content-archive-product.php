<?php
/**
 * Template part for displaying posts in an archive
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Stone Stomper
 * @since 1.0.0
 */
if(isset($args['sts_var_product_id'])){
	list( $sts_var_product_id, $sts_fields, $sts_option_fields ) = StoneStomper::defaults($args['sts_var_product_id']);
} else {
	list( $sts_var_product_id, $sts_fields, $sts_option_fields ) = StoneStomper::defaults();
}
$sts_var_product_sub_title = $sts_fields['sts_var_product_sub_title'] ?? null;
$product = wc_get_product( $sts_var_product_id );
$upsells = $product->get_upsell_ids();

$sts_var_product_title     = get_the_title($sts_var_product_id);
$sts_var_product_permalink = get_permalink($sts_var_product_id);
$sts_var_product_description = $product->get_short_description();
$sts_var_product_price = $product->get_price();
?>

<div class="product-item">
	<div class="product-image">
		<?php StoneStomper::the_featured_image(  $sts_var_product_id, 500 ); ?>
	</div>
	<div class="product-title">
		<h3>
			<?php echo esc_html(get_the_title($sts_var_product_id)); ?>
		</h3>
	</div>
	<div class="product-description">
		<?php echo html_entity_decode($sts_var_product_description); ?>
	</div>
	<div class="product-price">
	</div>
	<?php
	if($sts_var_product_sub_title){ ?>
		<h3><?php echo esc_html($sts_var_product_sub_title); ?></h3>
		<a href="<?php echo esc_url(get_the_permalink($sts_var_product_id)); ?>"> Place Your Order </a>
	<?php } else { ?>
	<?php if($upsells ){ ?>
		<h3> <?php echo StoneStomper::show_product_with_upsells($sts_var_product_id,$upsells); ?></h3>
		<a href="<?php echo esc_url(get_the_permalink($sts_var_product_id)); ?>"> Place Your Order </a>
		<?php } else { ?>
		<?php echo get_woocommerce_currency_symbol(); ?><?php echo html_entity_decode($sts_var_product_price); ?>
	<?php echo do_shortcode( '[add_to_cart  id="' . esc_attr( $sts_var_product_id ) . '" show_price="false"]' ); ?>
	<?php } ?>
	<?php }

	?>
</div>
