<?php
/**
 * The template for displaying all pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Stone Stomper
 * @since 1.0.0
 */

// Include header.
get_header();


list( $sts_var_post_id, $sts_fields, $sts_option_fields ) = StoneStomper::defaults();

$sts_var_tmp_def_title  = $sts_fields['sts_var_tmp_def_title'] ?? get_the_title();
$sts_var_tmp_def_text   = $sts_fields['sts_var_tmp_def_text'] ?? null;
$sts_var_tmp_def_button = $sts_fields['sts_var_tmp_def_button'] ?? null;

if(has_blocks( $post->post_content )){
?>
<section id="hero-section" class="hero-section hero-section-default" style="display:none;">
	<!-- Hero Start -->
	<div class="hero-ctn">
		<div class="wrapper">
			<h1><?php echo html_entity_decode( $sts_var_tmp_def_title ); ?></h1>
		</div>
	</div>
	<!-- Hero End -->
</section>
<?php
} else { ?>
<section id="hero-section" class="hero-section hero-section-default">
	<!-- Hero Start -->
	<div class="hero-ctn">
		<div class="wrapper">
			<h1><?php echo html_entity_decode( $sts_var_tmp_def_title ); ?></h1>
		</div>
	</div>
	<!-- Hero End -->
</section>

<?php }
	?>

<section id="page-section" class="page-section">
	<!-- Content Start -->
	<?php
		global $wp_query;
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			// Include specific template for the content.
			get_template_part( 'partials/content', 'page' );
		}
		?>
		<?php
	} else {
		// If no content, include the "No posts found" template.
		get_template_part( 'partials/content', 'none' );
	}
	?>
	<div class="ts-80"></div>
	<!-- Content End -->
</section>
<?php get_footer(); ?>
