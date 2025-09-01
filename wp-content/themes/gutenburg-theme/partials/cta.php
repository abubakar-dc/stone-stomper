<?php
/**
 * Template part for footer cta
 *
 * @link https://developer.wordpress.org/themes/template-files-section/partial-and-miscellaneous-template-files/
 *
 * @package Base Theme Package
 * @since 1.0.0
 */

list( $bst_var_post_id, $bst_fields, $bst_option_fields, $bst_queried_object ) = BaseTheme::defaults();

$bst_var_to_cta_headline = $bst_option_fields['bst_var_to_cta_headline'] ?? null;

$bst_var_page_cta_pagevisibility = $bst_fields['bst_var_page_cta_pagevisibility'] ?? null;
$bst_var_ftrcta_headline         = $bst_fields['bst_var_page_cta_headline'] ?? $bst_var_to_cta_headline;
?>

<section id="cta-section" class="cta-section">
	<!-- cta Start -->
	<div class="cta-single">
		<div class="wrapper">
			<h4><?php echo esc_html( $bst_var_ftrcta_headline ); ?></h4>
		</div>
	</div>
	<!-- cta End -->
</section>
