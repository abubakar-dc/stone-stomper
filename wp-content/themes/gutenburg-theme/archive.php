<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Base Theme Package
 * @since 1.0.0
 */

// Include header.
get_header();

list( $bst_var_post_id, $bst_fields, $bst_option_fields ) = BaseTheme::defaults();

?>
<section id="hero-section" class="hero-section hero-section-default">
	<!-- Hero Start -->
	<div class="hero-single">
		<div class="wrapper">
			<h1><?php the_archive_title(); ?></h1>
		</div>
	</div>
	<!-- Hero End -->
</section>
<section id="page-section" class="page-section">
	<!-- Content Start -->
	<div class="wrapper">
		<div class="<?php BaseTheme::have_post_class( 'three-columns' ); ?>">
			<?php
			global $wp_query;
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();
					// Include specific template for the content.
					get_template_part( 'partials/content-archive', get_post_type() );
				}
			} else {
				// If no content, include the "No posts found" template.
				get_template_part( 'partials/content', 'none' );
			}
			?>
		</div>
		<div class="ts-40"></div>
		<?php
		if ( have_posts() ) {
			if ( class_exists( 'BaseTheme' ) && $wp_query->max_num_pages > 1 ) {
				?>
				<div class="center-align">
					<?php BaseTheme::pagination( $wp_query->max_num_pages ); ?>
				</div>
				<?php
			}
		}
		?>
		<div class="ts-80"></div>
	</div>
	<!-- Content End -->
</section>
<?php get_footer(); ?>
