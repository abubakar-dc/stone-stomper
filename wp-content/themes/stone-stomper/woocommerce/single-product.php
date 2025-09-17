<?php
defined( 'ABSPATH' ) || exit;

get_header(); ?>

<div class="single-product-custom">

	<?php
	while ( have_posts() ) :
		the_post();
		global $product;
	?>

		<div class="product-image">
			<?php echo $product->get_image(); ?>
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
