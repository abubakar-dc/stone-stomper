<?php
/**
 * Template Name: Shop
 * Template Post Type: page
 *
 * This template is for displaying resource page.
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-template-files/
 *
 * @package Stone Stomper
 * @since 1.0.0
 */

// Include header.
get_header();

list( $sts_var_post_id, $sts_fields, $sts_option_fields ) = StoneStomper::defaults();

$sts_var_pagetitle          = $sts_fields['sts_var_pagetitle'] ?? get_the_title();
$sts_var_shop_sub_title = $sts_fields['sts_var_shop_sub_title'] ?? null;

?>
<section id="hero-section" class="hero-section hero-section-default">
	<!-- hero start -->
	<div class="hero-default">
		<div class="default-hero-cover has-custom-content-position is-position-bottom-left">
			<?php if(has_post_thumbnail($sts_var_post_id)){
				StoneStomper::the_featured_image($sts_var_post_id,2000,   array(  'class' => 'wp-block-cover__image-background wp-image-342 size-large' ) );
			}  ?>
			<div class="default-hero-cover-content">
				<h1 class="" tabindex="0"><?php echo esc_html($sts_var_pagetitle); ?></h1>
			</div>
		</div>
	</div>
</section>

<section id="page-section" class="page-section">
	<!-- Content Start -->
	 <div class="st-s128"></div>
	<div class="wrapper">
		<h2 class="product-main-title"><?php echo esc_html($sts_var_shop_sub_title); ?></h2>
		<div class="three-columns product-items">



	<?php
			// WP_Query .
			$sts_args = array(
				'post_type'      => array( 'product' ),
				'posts_per_page' => -1

			);
			// The Query.
			$sts_query = new WP_Query( $sts_args );
			// The Loop.
			if ( $sts_query->have_posts() ) {
				while ( $sts_query->have_posts() ) {
					$sts_query->the_post();
					// Include specific template for the content.
					get_template_part( 'partials/content', 'archive-product' );
				}
				?>
				<?php
			} else {
				// If no content, include the "No posts found" template.
				get_template_part( 'partials/content', 'none' );
			}
			?>
				</div>
			</div>
		<div class="ts-80"></div>
		<!-- Content End -->
	</div>
</section>

<?php
get_footer();
