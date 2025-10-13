<?php
defined( 'ABSPATH' ) || exit;

get_header();
list( $sts_var_post_id, $sts_fields, $sts_option_fields ) = StoneStomper::defaults();

?>

<section id="hero-section" class="hero-section hero-section-default">
	<!-- hero start -->
	<div class="hero-default">
		<div class="wp-block-cover has-custom-content-position is-position-bottom-left">

			<?php if(has_post_thumbnail($sts_var_post_id)){
				StoneStomper::the_featured_image($sts_var_post_id,2000,   array(  'class' => 'wp-block-cover__image-background wp-image-342 size-large' ) );
			}  ?>

			<span aria-hidden="true" class="wp-block-cover__background has-background-dim" style="background-color:#645641"></span>
			<div class="wp-block-cover__inner-container is-layout-constrained wp-block-cover-is-layout-constrained">
				<h1 class="" tabindex="0"><?php echo the_title(); ?></h1>
			</div>
		</div>
	</div>
</section>

<div class="single-product-custom">

	<?php
	while ( have_posts() ) :
		the_post();
		global $product;
	?>
		<div class="single-content-section two-column">

		</div>
		<div class="product-image-gallery two-columns">
			<?php
				if($product){
					$attachment_ids = $product->get_gallery_image_ids();
					foreach ( $attachment_ids as $attachment_id ) {
						StoneStomper::the_attachment_image($attachment_id, 1000);
					}
				}
			?>
		</div>

		<div class="product-summary">
			<h1><?php the_title(); ?></h1>

			<!-- 🔹 Product Price -->
			<div class="price">
				<?php echo $product->get_price_html(); ?>
			</div>

			<!-- 🔹 Product Description -->
			<div class="description">
				<?php the_content(); ?>
			</div>
		</div>

		<div class="upsell-products">
			<?php
			$upsells = $product->get_upsell_ids();

			if ( $upsells ) {
				$upsell_products = wc_get_products( array( 'include' => $upsells ) );
				foreach ( $upsell_products as $upsell ) {
					echo '<label class="upsell-item">';
					echo '<input type="checkbox" class="upsell-checkbox" value="' . esc_attr( $upsell->get_id() ) . '"> ';
					echo '<span class="upsell-name">' . esc_html( $upsell->get_name() ) . '</span> ';
					echo '<span class="upsell-price">' . wp_kses_post( $upsell->get_price_html() ) . '</span>';
					echo '</label><br>';
				}
			}
			?>

			<button type="button" class="button add-upsell-to-cart"
				data-main-product="<?php echo esc_attr( $product->get_id() ); ?>">
				<?php esc_html_e( 'Add to cart', 'woocommerce' ); ?>
			</button>
		</div>

	<?php endwhile; ?>

</div>

<?php get_footer(); ?>
