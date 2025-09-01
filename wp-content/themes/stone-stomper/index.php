<?php
/**
 * The template for displaying all pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Stone Stomper
 * @since 1.0.0
 */

get_header();
	list( $sts_var_post_id, $sts_fields, $sts_option_fields,$sts_query_object ) = StoneStomper::defaults();

?>
<section id="hero-section" class="hero-section hero-section-default">
	<!-- Hero Start -->
	<div class="hero-single">
		<div class="wrapper">
		<h1><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h1>
		<p><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
		</div>
	</div>
	<!-- Hero End -->
</section>
<section id="page-section" class="page-section">
	<div class="wrapper">
		<div class="<?php StoneStomper::have_post_class( 'three-columns' ); ?>">
			<!-- Content Start -->
			<?php $sts_query = StoneStomper::query(); ?>
			<div class="ts-80"></div>
			<!-- Content End -->
		</div>
	</div>
</section>
<?php get_footer(); ?>
