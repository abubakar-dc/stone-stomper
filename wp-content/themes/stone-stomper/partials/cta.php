<?php
/**
 * Template part for footer cta
 *
 * @link https://developer.wordpress.org/themes/template-files-section/partial-and-miscellaneous-template-files/
 *
 * @package Stone Stomper
 * @since 1.0.0
 */

list( $sts_var_post_id, $sts_fields, $sts_option_fields, $sts_queried_object ) = StoneStomper::defaults();

$sts_var_to_cta_headline = $sts_option_fields['sts_var_to_cta_headline'] ?? null;

$sts_var_page_cta_pagevisibility = $sts_fields['sts_var_page_cta_pagevisibility'] ?? null;
$sts_var_ftrcta_headline         = $sts_fields['sts_var_page_cta_headline'] ?? $sts_var_to_cta_headline;
?>

<section id="cta-section" class="cta-section">
	<!-- cta Start -->
	<div class="cta-single">
		<div class="wrapper">
			<h4><?php echo esc_html( $sts_var_ftrcta_headline ); ?></h4>
		</div>
	</div>
	<!-- cta End -->
</section>
