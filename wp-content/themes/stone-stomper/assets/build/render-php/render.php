<?php
/**
 * Block Render Callback Template
 *
 * This file is used to render dynamic blocks via PHP.
 *
 * @link https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/#render_callback
 *
 * @package Base_Theme_Package
 * @since 1.0.0
 */


$sts_var_elevate_ids = $attributes['elevateIds'] ?? null;
$sts_var_stay_ids = $attributes['stayIds'] ?? null;
$sts_var_post_values = array_merge($sts_var_elevate_ids, $sts_var_stay_ids);
$selectionMode = $attributes['selectionMode'] ?? 'recent';
$heading = $attributes['heading'] ?? '';
$description = $attributes['description'] ?? '';
$linkData = $attributes['linkData'] ?? null;

if ($sts_var_post_values) {
?>
	<div class="latest-article-block">
		<div class="section-head">
			<div class="section-head-left">
				<?php if ($heading) { ?>
					<h2 id="section-head-title" class="heading-2">
						<?php echo esc_html($heading); ?>
					</h2>
				<?php } ?>
				<?php if ($description) { ?>
					<p><?php echo esc_html($description); ?></p>
				<?php } ?>
			</div>
			<div class="section-head-right">
				<?php if ($linkData) { ?>
					<div class="wp-block-button is-style-with-arrow is-color-brown is-size-large">
						<a href="<?php echo esc_url($linkData['url']); ?>" <?php if ($linkData['target'] === true) {
																				echo "target='_blank'";
																			} ?> class="wp-block-button__link wp-element-button" tabindex="0"><?php echo html_entity_decode($linkData['title']); ?></a>
					</div>
				<?php } ?>
			</div>
		</div>
			<div class="three-column">
				<?php
				if ($sts_var_post_values) {
					foreach ($sts_var_post_values as $key =>  $post_data) {
						$post_id = $post_data['value']; // Extract the post ID
						list($post_id, $sts_fields, $sts_perks_fields) = StayEdits::defaults($post_id);
						$elevate_region_terms = get_the_terms($post_id, 'elevate-region');
						// Extract post details
						$sts_var_post_title     = get_the_title($post_id);
						$sts_var_post_permalink = get_permalink($post_id);
						$sts_var_perk_description = $sts_fields['sts_var_perk_description'] ?? '';
						get_template_part('partials/content', 'archive-post');
						} ?>
				<?php } ?>
				</ul>
			</div>
<?php } else { ?>
	<h2>Please select a post from the settings panel on the right.</h2>
<?php } ?>
