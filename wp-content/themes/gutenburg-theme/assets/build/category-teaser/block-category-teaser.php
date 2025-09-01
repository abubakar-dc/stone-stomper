<?php
/**
 * Block: Category Teaser
 * Render Callback File
 *
 * This file handles the server-side rendering of the block.
 *
 * @package Stay Edits Package
 * @since 1.0.0
 */


$all_style_categories = get_terms('category', ['hide_empty' => true]);

if ( ! empty($all_style_categories) ) { ?>
	<div class="services-slider-main">
		<div class="swiper services-slider">
			<div class="swiper-wrapper">
				<?php foreach ( $all_style_categories as $key => $category ) {
					$category_image = get_field('bst_var_category_image', 'category_' . $category->term_id);
					?>
					<div class="swiper-slide">
						<div class="service-single-slide">
							<div class="service-single-image">
								<a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="no-link-style">
									<figure class="wp-block-image image-cover size-full">
										<?php if ( $category_image ) { ?>
											<?php StayEdits::the_attachment_image( $category_image, 1000 ); ?>
										<?php } ?>
									</figure>
								</a>
							</div>
							<div class="service-single-text">
								<h3 class="heading-4">
									<?php echo esc_html( $category->name ); ?>
								</h3>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
			<div class="swiper-arrows services-swiper-arrows">
				<div class="services-swiper-button-next swiper-button-next"></div>
				<div class="services-swiper-button-prev swiper-button-prev"></div>
			</div>
			<div class="swiper-pagination">
			</div>
		</div>
	</div>
<?php } ?>


