<?php
/**
 * Template Name: Stone Stomper Order Form
 * Template Post Type: page
 *
 * This template is for displaying the Stone Stomper order form.
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-template-files/
 *
 * @package Stone Stomper
 * @since 1.0.0
 */

// Include header.
get_header();
list( $sts_var_post_id, $sts_fields, $sts_option_fields ) = StoneStomper::defaults();

$sts_var_section_head           = $sts_fields['sts_var_section_head'] ?? null;
if($sts_var_section_head){
	$sts_var_section_headline = $sts_var_section_head['headline']??null;
	$sts_var_section_head_text = $sts_var_section_head['text']??null;
	$sts_var_section_head_image = $sts_var_section_head['image']??null;
}
$sts_var_section_head_notices           = $sts_fields['sts_var_section_head_notices'] ?? null;
$sts_var_example_photographs           = $sts_fields['sts_var_example_photographs'] ?? null;
$sts_var_measurements_images           = $sts_fields['sts_var_measurements_images'] ?? null;

?>
<section id="page-section" class="page-section">
	<!-- Content Start -->
	 <section>
		<div class="image-cover" >
		<?php if(has_post_thumbnail($sts_var_post_id)){
			StoneStomper::the_featured_image($sts_var_post_id,2000);
		}  ?>
			<div class="image-overlay">
				<h1><?php echo esc_html(get_the_title($sts_var_post_id)); ?></h1>
			</div>
		</div>
	 </section>
	<div class="st-s200"></div>
	<section>
		<div class="wrapper">
			<div class="order-section two-columns justify-content-between align-items-center image-at-left">
				<?php if($sts_var_section_head_image){
					?>
				<div class="iat-form-image column" tabindex="0" role="img" aria-label="Image illustrating the content of this block">
					<?php StoneStomper::the_attachment_image($sts_var_section_head_image,800 ); ?>
				</div>
				<?php } ?>
				<div class="iat-form-content column">
					<div class="content-head">
						<?php if($sts_var_section_headline){ ?>
						<h2><?php echo esc_html($sts_var_section_headline); ?></h2>
						<?php }
						if($sts_var_section_head_text){
							echo html_entity_decode($sts_var_section_head_text);
						}
						?>
					</div>
					<form id="orderForm" novalidate>
						<div id="form-all">
							<!-- Your Details -->
							<div class="block" id="blk-details">
								<?php if($sts_var_section_head_notices){
									foreach($sts_var_section_head_notices as $sts_key => $notice){
									$sts_var_headline = $notice['headline']??null;
									$sts_var_text = $notice['text']??null;
									if($sts_key === 0){ ?>
										<?php if ( $sts_var_headline ) {
											?>
										<h3><?php echo esc_html($sts_var_headline); ?></h3>
										<?php }
										if($sts_var_text){ ?>
											<p><?php echo html_entity_decode($sts_var_text); ?></p>
										<?php } ?>
									<?php
									}
										}
								} ?>
								<div class="grid cols-2">
									<div class="field">
										<label class="req" for="cust_name">Name</label>
										<input id="cust_name" name="customer_name" type="text" required />
									</div>
									<div class="field">
										<label class="req" for="cust_email">Email Address</label>
										<input id="cust_email" name="customer_email" type="email" required />
									</div>
									<div class="field">
										<label class="req" for="cust_address">Delivery Address</label>
										<input id="cust_address" name="customer_address" type="text" required />
									</div>
									<div class="grid cols-2">
										<div class="field">
											<label class="req" for="cust_suburb">Suburb</label>
											<input id="cust_suburb" name="customer_suburb" type="text"
												required />
										</div>
										<div class="field">
											<label class="req" for="cust_state">State</label>
											<select id="cust_state" name="customer_state" required>
												<option value="">Select…</option>
												<option>NSW</option>
												<option>VIC</option>
												<option>QLD</option>
												<option>SA</option>
												<option>WA</option>
												<option>TAS</option>
												<option>ACT</option>
												<option>NT</option>
											</select>
										</div>
									</div>
									 <?php
									 	if($sts_var_section_head_notices){
											foreach($sts_var_section_head_notices as $sts_key => $notice) {
											$sts_var_notice = $notice['notice']??null;
											if($sts_key === 0){ ?>
											<?php if ( $sts_var_notice ) { ?>
													<p class="note"><?php echo html_entity_decode($sts_var_notice); ?></p>
												<?php } ?>
											<?php
											}
												}
											}
										?>
								</div>
							</div>

							<!-- Towing Vehicle Details -->
							 <div class="full-width d-flex" >
								<div class="grid cols-2 vehicle-images ">
								 <div class="vehicle-image" id="towing-vehicle-image" tabindex="0" role="img"  aria-label="Image illustrating the content of this block">
								</div>
								 <div class="block" id="blk-vehicle">
								<?php if($sts_var_section_head_notices){
									foreach($sts_var_section_head_notices as $sts_key => $notice){
									$sts_var_headline = $notice['headline']??null;
									$sts_var_text = $notice['text']??null;
									if($sts_key === 1){ ?>
										<?php if ( $sts_var_headline ) {
											?>
										<h3><?php echo esc_html($sts_var_headline); ?></h3>
										<?php }
										if($sts_var_text){ ?>
											<p><?php echo html_entity_decode($sts_var_text); ?></p>
										<?php } ?>
									<?php
									}
										}
									} ?>
									 <div class="grid cols-2">
										 <div class="field">
											 <label class="req" for="veh_make">Vehicle Make</label>
											 <select id="veh_make" name="vehicle_make" required>
												 <?php
													 $parent_terms = get_terms([
														 'taxonomy'   => 'car-category', // Replace with your taxonomy slug
														 'parent'     => 0,                    // Only top-level terms
														 'hide_empty' => false                 // Include terms even if they have no posts
													 ]);
												 ?>
												 <option value="">Select Vehicle Make</option>
												 <?php foreach ($parent_terms as $term) : ?>
													 <option class="ajax-car-make" value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option>
												 <?php endforeach; ?>
												 <option value="other">Other</option>
											 </select>
										 </div>
										 <div class="field hidden" id="veh_make_other_wrap">
											 <label class="req" for="veh_make_other">Other Make</label>
											 <input id="veh_make_other" name="vehicle_make_other" type="text" />
										 </div>
										 <div class="field">
											 <label class="req" for="veh_model">Vehicle Model</label>
											 <select id="veh_model" name="vehicle_model" required >

											 </select>
										 </div>
										 <div class="field">
											 <label class="req" for="veh_year">Year of Manufacture</label>
											 <select id="veh_year" name="vehicle_year" required>
												 <option value="">Select…</option>
											 </select>
										 </div>
									 </div>
									 <?php
									 	if($sts_var_section_head_notices){
											foreach($sts_var_section_head_notices as $sts_key => $notice) {
											$sts_var_notice = $notice['notice']??null;
											if($sts_key === 1){ ?>
											<?php if ( $sts_var_notice ) { ?>
													<p class="note"><?php echo html_entity_decode($sts_var_notice); ?></p>
												<?php } ?>
											<?php
											}
												}
											}
										?>
								 </div>
							 </div>
							 </div>

							<!-- Caravan Details -->
							 <div>
								<div class="grid cols-2 vehicle-images ">
									<div class="vehicle-image" id="caravan-images" tabindex="0" role="img"  aria-label="Image illustrating the content of this block">
									</div>
								</div>
								<div class="block" id="blk-caravan">
								<?php if($sts_var_section_head_notices){
									foreach($sts_var_section_head_notices as $sts_key => $notice){
									$sts_var_headline = $notice['headline']??null;
									$sts_var_text = $notice['text']??null;
									if($sts_key === 2){ ?>
										<?php if ( $sts_var_headline ) {
											?>
										<h3><?php echo esc_html($sts_var_headline); ?></h3>
										<?php }
										if($sts_var_text){ ?>
											<p><?php echo html_entity_decode($sts_var_text); ?></p>
										<?php } ?>
									<?php
									}
										}
									} ?>
								<div class="grid cols-2">
									<div class="field">
										<label class="req" for="van_make">Caravan Make</label>
										<select id="van_make" name="caravan_make" required>
											<?php
											$terms = get_terms( array(
												'taxonomy'   => 'caravan-category', // Replace with your taxonomy slug
												'hide_empty' => false,      // Show terms even if they have no posts
											) );

											?>
											<option value="">Select Caravan Make</option>
											<?php foreach ( $terms as $term ) : ?>
												<option value="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></option>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="field">
										<label class="req" for="van_model">Caravan Model</label>
										<select id="van_model" name="caravan_model" required>
											<option value="">Select Caravan Model</option>
										</select>
									</div>
								</div>
								<fieldset>
									<div class="ginput_container ginput_container_checkbox">
										<div class="gchoice">
											<input class="gfield-choice-input" name="input_1.2" type="checkbox" value="toolbox" id="toolbox">
											<label for="toolbox" id="label_4_1_2">Toolbox</label>
										</div>
										<div class="gchoice">
											<input class="gfield-choice-input" name="input_1.1" type="checkbox" value="factory-stoneguard" id="factory_stoneguard">
											<label for="factory_stoneguard" id="label_4_1_1">Factory Stoneguard
											</label>
										</div>

									</div>
								</fieldset>
								<div class="checkboxes">
									<label>
										Other Information
										<input id="opt_other_access" placeholder="Other Information" type="text" name="other_a_frame" />
									</label>
								</div>
								<?php
								if($sts_var_section_head_notices){
									foreach($sts_var_section_head_notices as $sts_key => $notice) {
									$sts_var_notice = $notice['notice']??null;
									if($sts_key === 2){ ?>
									<?php if ( $sts_var_notice ) { ?>
											<p class="note"><?php echo html_entity_decode($sts_var_notice); ?></p>
										<?php } ?>
									<?php
									}
										}
									}
								?>
							</div>
							</div>

							<!-- Photographs -->
							<div class="block" id="blk-photos">
								<?php if($sts_var_section_head_notices){
									foreach($sts_var_section_head_notices as $sts_key => $notice){
									$sts_var_headline = $notice['headline']??null;
									$sts_var_text = $notice['text']??null;
									if($sts_key === 3){ ?>
										<?php if ( $sts_var_headline ) {
											?>
										<h3><?php echo esc_html($sts_var_headline); ?></h3>
										<?php }
										if($sts_var_text){ ?>
											<p><?php echo html_entity_decode($sts_var_text); ?></p>
										<?php } ?>
									<?php
									}
										}
									} ?>
								<div class="grid cols-2">
									<div class="field">
										<label class="req" for="photo_hitch">Hitch Photograph</label>
										<input id="photo_hitch" name="photo_hitch" type="file" accept="image/*"
											multiple />
										<ul class="uploads" id="list_hitch"></ul>
										<input type="hidden" id="hitch_ids" name="hitch_ids" value="[]">
									</div>
									<div class="field">
										<label class="req" for="photo_rear">Towing Vehicle Rear
											Photograph</label>
										<input id="photo_rear" name="photo_rear" type="file" accept="image/*"
											multiple />
										<ul class="uploads" id="list_rear"></ul>
										<input type="hidden" id="rear_ids"  name="rear_ids"  value="[]">
									</div>
									<div class="field">
										<label class="req" for="photo_front">Front of Caravan Photograph</label>
										<input id="photo_front" name="photo_front" type="file" accept="image/*"
											multiple />
										<ul class="uploads" id="list_front"></ul>
										<input type="hidden" id="front_ids" name="front_ids" value="[]">
									</div>
								</div>
								<?php
								if($sts_var_section_head_notices){
									foreach($sts_var_section_head_notices as $sts_key => $notice) {
									$sts_var_notice = $notice['notice']??null;
									if($sts_key === 3){ ?>
									<?php if ( $sts_var_notice ) { ?>
											<p class="note"><?php echo html_entity_decode($sts_var_notice); ?></p>
										<?php } ?>
									<?php
									}
										}
									}
								?>
								<div class="example-images">
									<?php
									if($sts_var_example_photographs){
										foreach($sts_var_example_photographs as $sts_key => $photo){
											StoneStomper::the_attachment_image($photo,300 );
										}
									}
									?>

								</div>
							</div>

							<!-- Final Measurements -->
							<div class="block" id="blk-measure">
								<?php if($sts_var_section_head_notices){
									foreach($sts_var_section_head_notices as $sts_key => $notice){
									$sts_var_headline = $notice['headline']??null;
									$sts_var_text = $notice['text']??null;
									if($sts_key === 4){ ?>
										<?php if ( $sts_var_headline ) {
											?>
										<h3><?php echo esc_html($sts_var_headline); ?></h3>
										<?php }
										if($sts_var_text){ ?>
											<p><?php echo html_entity_decode($sts_var_text); ?></p>
										<?php } ?>
									<?php
									}
										}
									} ?>
								<div class="grid cols-2">
									<div class="field">
										<label class="req" for="barwidth">Towing Vehicle Barwidth</label>
										<input id="barwidth" name="barwidth_mm" type="text"
											placeholder="e.g. 1800 mm" required />
									</div>
									<div class="field">
										<label class="req" for="toolbox_width">Toolbox Width</label>
										<input id="toolbox_width" name="toolbox_width_mm" type="text"
											placeholder="e.g. 900 mm" required />
									</div>
									<div class="field">
										<label class="req" for="toolbox_length">Toolbox Length</label>
										<input id="toolbox_length" name="toolbox_length_mm" type="text"
											placeholder="e.g. 900 mm" required />
									</div>
									<div class="field">
										<label class="req" for="vanwidth">Caravan Width</label>
										<input id="vanwidth" name="caravan_width_mm" type="text"
											placeholder="e.g. 2260 mm" required />
									</div>
									<div class="field">
										<label class="req" for="a_frame_length">A-Frame Length</label>
										<input id="a_frame_length" name="a_frame_length_mm" type="text"
											placeholder="e.g. 2260 mm" required />
									</div>
									<div class="field">
										<label class="req" for="stoneguard_length">Stoneguard Length</label>
										<input id="stoneguard_length" name="stoneguard_length_mm" type="text"
											placeholder="e.g.600 mm" required />
									</div>
									<div class="field">
										<label class="req" for="stoneguard_width">Stoneguard Width</label>
										<input id="stoneguard_width" name="stoneguard_width_mm" type="text"
											placeholder="e.g.600 mm" required />
									</div>
									<div class="field">
										<label class="req" for="vinyl_width">Vinyl Insert Width</label>
										<input id="vinyl_width" name="vinyl_width_mm" type="text"
											placeholder="e.g.600 mm" required />
									</div>
									<div class="field">
										<label class="req" for="vinyl_length">Vinyl Insert Length</label>
										<input id="vinyl_length" name="vinyl_length_mm" type="text"
											placeholder="e.g.600 mm" required />
									</div>

									<label class="row"><input id="support_pockets" type="checkbox"
											name="support_pockets" /> Support
										Pockets</label>
								</div>
								<?php
								if($sts_var_section_head_notices){
									foreach($sts_var_section_head_notices as $sts_key => $notice) {
									$sts_var_notice = $notice['notice']??null;
									if($sts_key === 4){ ?>
									<?php if ( $sts_var_notice ) { ?>
											<p class="note"><?php echo html_entity_decode($sts_var_notice); ?></p>
										<?php } ?>
									<?php
									}
										}
									}
								?>
								<div class="measurements-example">
									<?php
									if($sts_var_measurements_images){
										StoneStomper::the_attachment_image($sts_var_measurements_images,300 );
									}
									?>
								</div>
							</div>

							<!-- Final Details & Summary -->
							<div class="block" id="blk-final">
								<?php if($sts_var_section_head_notices){
									foreach($sts_var_section_head_notices as $sts_key => $notice){
									$sts_var_headline = $notice['headline']??null;
									$sts_var_text = $notice['text']??null;
									if($sts_key === 5){ ?>
										<?php if ( $sts_var_headline ) {
											?>
										<h3><?php echo esc_html($sts_var_headline); ?></h3>
										<?php }
										if($sts_var_text){ ?>
											<p><?php echo html_entity_decode($sts_var_text); ?></p>
										<?php } ?>
									<?php
									}
										}
									} ?>
								<div class="grid cols-2">
									<div class="field">
										<label class="req" for="final_address">Delivery Address</label>
										<select id="final_address" name="final_delivery" required>
											<option value="">Select…</option>
											<option value="same">Same as above</option>
											<option value="move">I am on the move</option>
										</select>
										<p class="note hidden" id="move_note">We’ll contact you by phone after
											manufacture to arrange
											delivery details.</p>
									</div>
									<div class="field">
										<label class="req" for="shipping">Shipping (Australia Wide)</label>
										   <select id="shipping" name="shipping_method" required>
										  <option value="flat_rate:4">Standard Shipping $75</option>
  											<option value="flat_rate:5">Express Shipping $150</option>
										</select>
									</div>
								</div>

							<?php
								global $product;
								$p = wc_get_product( 62 );
								$upsell_ids = $p ? $p->get_upsell_ids() : [];

								if ( $upsell_ids ) {
									echo '<div class="checkboxes" style="margin-top:8px">';
									echo '<strong>Accessories</strong>';

									foreach ( $upsell_ids as $upsell_id ) {
										$upsell = wc_get_product( $upsell_id );
										if ( ! $upsell ) {
											continue;
										}

										$price = $upsell->get_price();
										$title = $upsell->get_name();
										echo '<div class="checkbox-item">';
										if( has_post_thumbnail( $upsell_id ) ) {
										echo '<div class="thumb">';
										echo '<img src="' . esc_url( get_the_post_thumbnail_url( $upsell_id, 'thumbnail' ) ) . '" alt="' . esc_attr( $title ) . '" />';
										echo '<p>' . esc_html( $title ) . '</p>';
										echo '</div>';
										}
										echo '<label>';
										echo '<input type="checkbox" class="acc-upsell" data-product-id="' . esc_attr( $upsell_id ) . '" data-price-value="' . esc_attr( $price ) . '" />';
										echo ' ' . esc_html( $title ) . ' for ' . wc_price( $price );
										echo '</label>';
										echo '</div>';
									}

									echo '</div>';
								}
								?>

								<div class="summary" id="order_summary">
									<div class="line"><span>Stone Stomper®</span><strong>$<span
												data-id="base">825.00</span></strong></div>
									<!-- <div class="line" id="sum_sleeve" style="display:none"><span>Stone Stomper®
											Bar
											Sleeve</span><strong>$<span data-id="sleeve">95.00</span></strong>
									</div> -->
									<div class="line"><span>Shipping</span><strong>$<span
												data-id="shipping">75.00</span></strong></div>
									<div class="total"><span>Total</span> <strong>$<span
												data-id="total">900.00</span></strong> <span class="muted">inc.
											GST</span></div>
								</div>

								<div class="actions">
									<button class="btn secondary" type="button" id="btn_save">Save
										Order</button>
									<button class="btn primary" type="submit" id="btn_cart">Add to Cart</button>
								</div>
							</div>
						</div>
					</form>


				</div>
			</div>
		</div>
	</section>
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			// Include specific template for the content.
			get_template_part( 'partials/content', 'page' );

		}
	}
	?>
	<div class="ts-80"></div>
	<!-- Content End -->
	<div class="st-s200"></div>

</section>
<?php get_footer(); ?>
