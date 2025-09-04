<?php
/**
 * The template for displaying website footer
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Stone Stomper
 * @since 1.0.0
 */

list( $sts_var_post_id, $sts_fields, $sts_option_fields ) = StoneStomper::defaults();
// Default Footer Options.
$sts_var_footer_scripts = $sts_option_fields['footer_scripts'] ?? '';



// Schema Markup - ACF variables.
$sts_var_schema_check = $sts_option_fields['sts_var_schema_check'] ?? null;
if ( $sts_var_schema_check ) {
	$sts_var_schema_business_name       = $sts_option_fields['sts_var_schema_business_name'] ?? null;
	$sts_var_schema_business_legal_name = $sts_option_fields['sts_var_schema_business_legal_name'] ?? null;
	$sts_var_schema_street_address      = $sts_option_fields['sts_var_schema_street_address'] ?? null;
	$sts_var_schema_locality            = $sts_option_fields['sts_var_schema_locality'] ?? null;
	$sts_var_schema_region              = $sts_option_fields['sts_var_schema_region'] ?? null;
	$sts_var_schema_postal_code         = $sts_option_fields['sts_var_schema_postal_code'] ?? null;
	$sts_var_schema_map_short_link      = $sts_option_fields['sts_var_schema_map_short_link'] ?? null;
	$sts_var_schema_latitude            = $sts_option_fields['sts_var_schema_latitude'] ?? null;
	$sts_var_schema_longitude           = $sts_option_fields['sts_var_schema_longitude'] ?? null;
	$sts_var_schema_opening_hours       = $sts_option_fields['sts_var_schema_opening_hours'] ?? null;
	$sts_var_schema_telephone           = $sts_option_fields['sts_var_schema_telephone'] ?? null;
	$sts_var_schema_business_email      = $sts_option_fields['sts_var_schema_business_email'] ?? null;
	$sts_var_schema_business_logo       = $sts_option_fields['sts_var_schema_business_logo'] ?? null;
	$sts_var_schema_price_range         = $sts_option_fields['sts_var_schema_price_range'] ?? null;
	$sts_var_schema_type                = $sts_option_fields['sts_var_schema_type'] ?? null;
}
// Custom - ACF variables.

$sts_var_ftrop_title     = $sts_option_fields['sts_var_ftrop_title'] ?? null;
$sts_var_ftrop_text      = $sts_option_fields['sts_var_ftrop_text'] ?? null;
$sts_var_ftrop_copyright = $sts_option_fields['sts_var_ftrop_copyright'] ?? null;
$sts_var_social_profiles = $sts_option_fields['sts_var_social_profiles'] ?? null;

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
					<?php if ( $sts_var_ftrop_title ) { ?>
					<h5><?php echo html_entity_decode( $sts_var_ftrop_title ); ?></h5>
					<?php } ?>
					<?php if ( $sts_var_ftrop_text ) { ?>
					<div class="address"><?php echo html_entity_decode( $sts_var_ftrop_text ); ?></div>
					<?php } ?>
					<div class="social-icons d-flex">
						<?php StoneStomper::the_social_icons( $sts_var_social_profiles ); ?>
					</div>
				</div>
				<div class="single-widget">
					<div class="footer-nav">
						<?php
							wp_nav_menu(
								array(
									'theme_location' => 'footer-nav-one',
									'fallback_cb'    => 'StoneStomper::nav_fallback',
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
									'fallback_cb'    => 'StoneStomper::nav_fallback',
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
									'fallback_cb'    => 'StoneStomper::nav_fallback',
								)
							);
							?>
					</div>
				</div>
			</div>
			<div class="st-s72"></div>
			<div class="footer-bottom d-flex align-items-center justify-content-between">
				<?php if ( $sts_var_ftrop_copyright ) { ?>
				<div class="copy-right"><?php echo esc_html( $sts_var_ftrop_copyright ); ?></div>
				<?php } ?>
				<div class="legal-nav">
					<?php
						wp_nav_menu(
							array(
								'theme_location' => 'legal-nav',
								'fallback_cb'    => 'StoneStomper::nav_fallback',
							)
						);
						?>
				</div>
			</div>
		</div>
	</div>
	<!-- Footer End -->
	<?php
	if ( $sts_var_schema_check ) {
		?>
	<script type="application/ld+json">
	{
		"@context": "http://schema.org",
		"@type": "<?php echo esc_html( $sts_var_schema_type ); ?>",
		"address": {
			"@type": "PostalAddress",
			"addressLocality": "<?php echo esc_html( $sts_var_schema_locality ); ?>",
			"addressRegion": "<?php echo esc_html( $sts_var_schema_region ); ?>",
			"postalCode": "<?php echo esc_html( $sts_var_schema_postal_code ); ?>",
			"streetAddress": "<?php echo esc_html( $sts_var_schema_street_address ); ?>"
		},
		"hasMap": "<?php echo esc_html( $sts_var_schema_map_short_link ); ?>",
		"geo": {
			"@type": "GeoCoordinates",
			"latitude": "<?php echo esc_html( $sts_var_schema_latitude ); ?>",
			"longitude": "<?php echo esc_html( $sts_var_schema_longitude ); ?>"
		},
		"name": "<?php echo esc_html( $sts_var_schema_business_name ); ?>",
		"openingHours": "<?php echo esc_html( $sts_var_schema_opening_hours ); ?>",
		"telephone": "<?php echo esc_html( $sts_var_schema_telephone ); ?>",
		"email": "<?php echo esc_html( $sts_var_schema_business_email ); ?>",
		"url": "<?php echo esc_url( home_url() ); ?>",
		"image": "<?php echo esc_html( $sts_var_schema_business_logo ); ?>",
		"legalName": "<?php echo esc_html( $sts_var_schema_business_legal_name ); ?>",
		"priceRange": "<?php echo esc_html( $sts_var_schema_price_range ); ?>"
	}
	</script> <?php } ?>
</footer>
<?php wp_footer(); ?>
<?php
if ( '' !== $sts_var_footer_scripts ) {
	?>
<div style="display: none;">
	<?php echo html_entity_decode( $sts_var_footer_scripts, ENT_QUOTES ); ?>
</div>
<?php } ?>
</body>

</html>
