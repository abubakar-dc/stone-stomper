<?php

$bst_var_elevate_ids = $attributes['elevateIds'] ?? null;
$bst_var_stay_ids = $attributes['stayIds'] ?? null;
$bst_var_post_values = array_merge($bst_var_elevate_ids, $bst_var_stay_ids);
$selectionMode = $attributes['selectionMode'] ?? 'recent';
$heading = $attributes['heading'] ?? '';
$description = $attributes['description'] ?? '';
$linkData = $attributes['linkData'] ?? null;

if ($bst_var_post_values) {
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
		<div class="perk-slider-main">
			<div class="swiper perk-slider">
				<div class="swiper-wrapper">
					<?php
					if ($bst_var_post_values) {
						foreach ($bst_var_post_values as $key =>  $post_data) {
							$post_id = $post_data['value']; // Extract the post ID
							list($post_id, $bst_fields, $bst_perks_fields) = StayEdits::defaults($post_id);
							$elevate_region_terms = get_the_terms($post_id, 'elevate-region');
							// Extract post details
							$bst_var_post_title     = get_the_title($post_id);
							$bst_var_post_permalink = get_permalink($post_id);
							$bst_var_perk_description = $bst_fields['bst_var_perk_description'] ?? '';

					?>
							<div class="swiper-slide">
								<div class="perk-slider-single">
									<div class="perk-single-image image-cover">
										<a href="<?php echo esc_url(get_the_permalink($post_id)); ?>" class="no-link-style">
											<?php StayEdits::the_featured_image($post_id, 800);  ?>
										</a>
									</div>
									<div class="perk-single-content">
										<div class="star-tag">
										<?php
										if (! is_wp_error($elevate_region_terms) && ! empty($elevate_region_terms)) {
											$term_names = wp_list_pluck($elevate_region_terms, 'name');
											echo implode(', ', $term_names);
										}
										?>
										</div>
										<h2 class="heading-5">
											<a href="<?php echo esc_url(get_the_permalink($post_id)); ?>" class="no-link-style">
												<?php echo esc_html($bst_var_post_title); ?>
											</a>
										</h2>
										<div class="perk-single-text">
											<p>
												<?php echo html_entity_decode($bst_var_perk_description); ?>
											</p>
										</div>
										<div class="perk-single-button">
											<a href="<?php echo esc_url(get_the_permalink($post_id)); ?>" class="link-arrow">
												<?php esc_html_e('Discover More', 'stayedits_td'); ?>
											</a>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>
					<?php } ?>
					</ul>
				</div>
				<div class="swiper-arrows services-swiper-arrows">
					<div class="services-swiper-button-next swiper-button-next"></div>
					<div class="services-swiper-button-prev swiper-button-prev"></div>
				</div>
				<div class="swiper-pagination">
				</div>
			</div>
		</div>
<?php } else { ?>
	<h2>Please select a elevate from the settings panel on the right.</h2>
<?php } ?>
