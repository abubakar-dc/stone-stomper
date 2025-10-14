<?php

$sts_var_testimonial_ids = $attributes['testimonialIds'] ?? null;
$selectionMode = $attributes['selectionMode'] ?? 'recent';
$heading = $attributes['heading'] ?? '';

?>
<div class="testimonials">
	<?php if ($heading): ?>
		<div class="section-head">
			<h2 class="heading-2"><?php echo html_entity_decode($heading); ?></h2>
		</div>
	<?php endif; ?>

	<div class="testimonials-items">
		<?php if ($selectionMode === 'recent'): ?>
			<?php
			$args = [
				'post_type'      => 'testimonial',
				'posts_per_page' => -1,
				'orderby'        => 'date',
				'order'          => 'DESC',
			];
			$query = new WP_Query($args);
			if ($query->have_posts()):
				while ($query->have_posts()):
					$query->the_post();
					$post_id = get_the_ID();
					$sts_var_test_quote    = get_post_meta($post_id, 'sts_var_test_quote', true);
					$sts_var_test_name     = get_post_meta($post_id, 'sts_var_test_name', true);
					$sts_var_test_location = get_post_meta($post_id, 'sts_var_test_location', true);
					?>
					<div class="testimonial-item">
						<div class="testimonial-item-inner">
							<h3 class="heading-3"><?php echo esc_html(get_the_title($post_id)); ?></h3>
							<?php if ($sts_var_test_quote): ?>
								<p><?php echo esc_html($sts_var_test_quote); ?></p>
							<?php endif; ?>
						</div>
						<?php if ($sts_var_test_name || $sts_var_test_location): ?>
							<div class="testimonial-bottom">
								<?php if ($sts_var_test_name): ?>
									<div class="testimonial-author"><span><?php echo esc_html($sts_var_test_name); ?></span>,</div>
								<?php endif; ?>
								<?php if ($sts_var_test_location): ?>
									<div class="testimonial-location"><span><?php echo esc_html($sts_var_test_location); ?></span></div>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endwhile; wp_reset_postdata(); endif; ?>
					<?php elseif ($selectionMode === 'manual'): ?>
						<?php wp_reset_postdata();
						if ($sts_var_testimonial_ids) {
							foreach ($sts_var_testimonial_ids as $key => $post_data) {
								$post_id = is_array($post_data) && isset($post_data['value']) ? $post_data['value'] : intval($post_data);
								$post = get_post($post_id);
								if (!$post) continue;
								$sts_var_test_quote    = get_post_meta($post_id, 'sts_var_test_quote', true);
								$sts_var_test_name     = get_post_meta($post_id, 'sts_var_test_name', true);
								$sts_var_test_location = get_post_meta($post_id, 'sts_var_test_location', true);
								?>
								<div class="testimonial-item">
									<div class="testimonial-item-inner">
										<h3 class="heading-3"><?php echo esc_html(get_the_title($post_id)); ?></h3>
										<?php if ($sts_var_test_quote): ?>
											<p><?php echo esc_html($sts_var_test_quote); ?></p>
										<?php endif; ?>
									</div>
									<?php if ($sts_var_test_name || $sts_var_test_location): ?>
										<div class="testimonial-bottom">
											<?php if ($sts_var_test_name): ?>
												<div class="testimonial-author"><span><?php echo esc_html($sts_var_test_name); ?></span>,</div>
											<?php endif; ?>
											<?php if ($sts_var_test_location): ?>
												<div class="testimonial-location"><span><?php echo esc_html($sts_var_test_location); ?></span></div>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</div>
							<?php } ?>
						<?php } ?>
		<?php endif; ?>
	</div>
</div>
