<?php
/**
 * The template for displaying website footer
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Base Theme Package
 * @since 1.0.0
 */

list( $bst_var_post_id, $bst_fields, $bst_option_fields ) = BaseTheme::defaults();
// Default Footer Options.
$bst_var_footer_scripts = $bst_option_fields['footer_scripts'] ?? '';



// Schema Markup - ACF variables.
$bst_var_schema_check = $bst_option_fields['bst_var_schema_check'] ?? null;
if ( $bst_var_schema_check ) {
	$bst_var_schema_business_name       = $bst_option_fields['bst_var_schema_business_name'] ?? null;
	$bst_var_schema_business_legal_name = $bst_option_fields['bst_var_schema_business_legal_name'] ?? null;
	$bst_var_schema_street_address      = $bst_option_fields['bst_var_schema_street_address'] ?? null;
	$bst_var_schema_locality            = $bst_option_fields['bst_var_schema_locality'] ?? null;
	$bst_var_schema_region              = $bst_option_fields['bst_var_schema_region'] ?? null;
	$bst_var_schema_postal_code         = $bst_option_fields['bst_var_schema_postal_code'] ?? null;
	$bst_var_schema_map_short_link      = $bst_option_fields['bst_var_schema_map_short_link'] ?? null;
	$bst_var_schema_latitude            = $bst_option_fields['bst_var_schema_latitude'] ?? null;
	$bst_var_schema_longitude           = $bst_option_fields['bst_var_schema_longitude'] ?? null;
	$bst_var_schema_opening_hours       = $bst_option_fields['bst_var_schema_opening_hours'] ?? null;
	$bst_var_schema_telephone           = $bst_option_fields['bst_var_schema_telephone'] ?? null;
	$bst_var_schema_business_email      = $bst_option_fields['bst_var_schema_business_email'] ?? null;
	$bst_var_schema_business_logo       = $bst_option_fields['bst_var_schema_business_logo'] ?? null;
	$bst_var_schema_price_range         = $bst_option_fields['bst_var_schema_price_range'] ?? null;
	$bst_var_schema_type                = $bst_option_fields['bst_var_schema_type'] ?? null;
}
// Custom - ACF variables.

$bst_var_ftrop_title     = $bst_option_fields['bst_var_ftrop_title'] ?? null;
$bst_var_ftrop_text      = $bst_option_fields['bst_var_ftrop_text'] ?? null;
$bst_var_ftrop_copyright = $bst_option_fields['bst_var_ftrop_copyright'] ?? null;
$bst_var_social_profiles = $bst_option_fields['bst_var_social_profiles'] ?? null;

?>
<?php get_template_part( 'partials/cta' ); ?>
</main>
<footer id="footer-section" class="footer-section">
	<!-- Footer Start -->
	<div class="footer-ctn">
		<div class="wrapper">

			<div class="footer-widgets d-flex justify-content-between flex-wrap">
				<div class="single-widget">
					<div class="footer-logo">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
							<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/build/images/site-logo-white.svg"
								alt="Logo" />
						</a>
					</div>
					<?php if ( $bst_var_ftrop_title ) { ?>
					<h5><?php echo html_entity_decode( $bst_var_ftrop_title ); ?></h5>
					<?php } ?>
					<?php if ( $bst_var_ftrop_text ) { ?>
					<div class="address"><?php echo html_entity_decode( $bst_var_ftrop_text ); ?></div>
					<?php } ?>
					<div class="social-icons d-flex">
						<?php BaseTheme::the_social_icons( $bst_var_social_profiles ); ?>
					</div>
				</div>
				<div class="single-widget">
					<div class="footer-nav">
						<?php
							wp_nav_menu(
								array(
									'theme_location' => 'footer-nav-one',
									'fallback_cb'    => 'BaseTheme::nav_fallback',
								)
							);
							?>
					</div>
				</div>
				<div class="single-widget">
					<div class="footer-nav">
						<?php
							wp_nav_menu(
								array(
									'theme_location' => 'footer-nav-two',
									'fallback_cb'    => 'BaseTheme::nav_fallback',
								)
							);
							?>
					</div>
				</div>
				<div class="single-widget">
					<div class="footer-nav">
						<?php
							wp_nav_menu(
								array(
									'theme_location' => 'footer-nav-three',
									'fallback_cb'    => 'BaseTheme::nav_fallback',
								)
							);
							?>
					</div>
				</div>
			</div>
			<div class="gl-s72"></div>
			<div class="footer-bottom d-flex align-items-center justify-content-between">
				<?php if ( $bst_var_ftrop_copyright ) { ?>
				<div class="copy-right"><?php echo esc_html( $bst_var_ftrop_copyright ); ?></div>
				<?php } ?>
				<div class="legal-nav">
					<?php
						wp_nav_menu(
							array(
								'theme_location' => 'legal-nav',
								'fallback_cb'    => 'BaseTheme::nav_fallback',
							)
						);
						?>
				</div>
			</div>
		</div>
	</div>
	<!-- Footer End -->
	<?php
	if ( $bst_var_schema_check ) {
		?>
	<script type="application/ld+json">
	{
		"@context": "http://schema.org",
		"@type": "<?php echo esc_html( $bst_var_schema_type ); ?>",
		"address": {
			"@type": "PostalAddress",
			"addressLocality": "<?php echo esc_html( $bst_var_schema_locality ); ?>",
			"addressRegion": "<?php echo esc_html( $bst_var_schema_region ); ?>",
			"postalCode": "<?php echo esc_html( $bst_var_schema_postal_code ); ?>",
			"streetAddress": "<?php echo esc_html( $bst_var_schema_street_address ); ?>"
		},
		"hasMap": "<?php echo esc_html( $bst_var_schema_map_short_link ); ?>",
		"geo": {
			"@type": "GeoCoordinates",
			"latitude": "<?php echo esc_html( $bst_var_schema_latitude ); ?>",
			"longitude": "<?php echo esc_html( $bst_var_schema_longitude ); ?>"
		},
		"name": "<?php echo esc_html( $bst_var_schema_business_name ); ?>",
		"openingHours": "<?php echo esc_html( $bst_var_schema_opening_hours ); ?>",
		"telephone": "<?php echo esc_html( $bst_var_schema_telephone ); ?>",
		"email": "<?php echo esc_html( $bst_var_schema_business_email ); ?>",
		"url": "<?php echo esc_url( home_url() ); ?>",
		"image": "<?php echo esc_html( $bst_var_schema_business_logo ); ?>",
		"legalName": "<?php echo esc_html( $bst_var_schema_business_legal_name ); ?>",
		"priceRange": "<?php echo esc_html( $bst_var_schema_price_range ); ?>"
	}
	</script> <?php } ?>
</footer>
<?php wp_footer(); ?>
<?php
if ( '' !== $bst_var_footer_scripts ) {
	?>
<div style="display: none;">
	<?php echo html_entity_decode( $bst_var_footer_scripts, ENT_QUOTES ); ?>
</div>
<?php } ?>
</body>

</html>
