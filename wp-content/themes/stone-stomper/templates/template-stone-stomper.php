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

?>
<section id="page-section" class="page-section">
	<!-- Content Start -->
	<div class="gl-s200"></div>
	<section>
		<div class="wrapper">
			<div class="order-section two-columns justify-content-between align-items-center image-at-left">
				<div class="iat-form-image column" tabindex="0" role="img"
					aria-label="Image illustrating the content of this block">
					<img src="../assets/src/images/admin/defaults/default-image.webp" alt="">
				</div>
				<div class="iat-form-content column">
					<div class="content-head">

						<h2>C U S T O M I S E Y O U R
							STONE STOMPER ®</h2>
						<p>Stone Stomper’s® reinforced one piece Truck Mesh trapeze style stone
							guard protects your car and recreational vehicle from stones, rocks, bitumen
							and road splatter. Each order is custom made to your measurements and
							attaches easily with no drilling to the vehicle.</p>
					</div>
					<!-- i need order form with your detail section - detail section will include following details -->
					<!-- name, delivery address, subrub, state, email address -->
					<!-- Then another section will be towing vehicle details
						vehicle make, vehicle model, year of manufacture
						then another section will be carvan details
						carvan make, carvan model, checkboxes( factory stone gaurd, Toolbox, spare tyres, other A frame accessories)
						then another section will be upload photographs
						hitch, towing vehicle rear photograph, carvan front photograph
						then another section will be Final mesurments (Towing Vehicle Barwidth, Caravan Width, Caravan Clearance Gap,Vinyl Insert/s (based on Jayco CrossTrail)
						then another section will be Final Details (delivery address, accossories checkboxes, shiping cost, and then order summary)
						then finally a add to cart button and save order button
						-->
					<form id="orderForm" novalidate>
						<div id="form-all">
							<!-- Your Details -->
							<div class="block" id="blk-details">
								<h3>Your Details</h3>
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
								</div>
							</div>

							<!-- Towing Vehicle Details -->
							<div class="block" id="blk-vehicle">
								<h3>Towing Vehicle Details</h3>
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
								<p class="note">If your vehicle isn’t listed, choose “Other” and specify.</p>
							</div>

							<!-- Caravan Details -->
							<div class="block" id="blk-caravan">
								<h3>Caravan Details</h3>
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

								<div class="checkboxes">
									<label><input  id="opt_guard" type="checkbox" name="factory_stoneguard" />
										Factory Stoneguard</label>
									<label><input id="opt_toolbox" type="checkbox" name="toolbox" />
										Toolbox</label>
									<label><input id="opt_spare" type="checkbox" name="spare_tyre" /> Spare
										Tyre</label>
									<label><input id="opt_other_access" type="checkbox" name="other_a_frame" />
										Other A-frame
										Accessories</label>
								</div>

								<div class="field hidden" id="other_access_wrap">
									<label for="other_access_text">Please specify accessories</label>
									<input id="other_access_text" name="other_accessories_text" type="text"
										placeholder="e.g. Gas bottles and jockey wheel" />
								</div>
							</div>

							<!-- Photographs -->
							<div class="block" id="blk-photos">
								<h3>Photographs</h3>
								<div class="grid cols-2">
									<div class="field">
										<label class="req" for="photo_hitch">Hitch Photograph</label>
										<input id="photo_hitch" name="photo_hitch" type="file" accept="image/*"
											multiple />
										<ul class="uploads" id="list_hitch"></ul>
									</div>
									<div class="field">
										<label class="req" for="photo_rear">Towing Vehicle Rear
											Photograph</label>
										<input id="photo_rear" name="photo_rear" type="file" accept="image/*"
											multiple />
										<ul class="uploads" id="list_rear"></ul>
									</div>
									<div class="field">
										<label class="req" for="photo_front">Front of Caravan Photograph</label>
										<input id="photo_front" name="photo_front" type="file" accept="image/*"
											multiple />
										<ul class="uploads" id="list_front"></ul>
									</div>
								</div>
								<p class="note">Provide several clear photos and multiple angles to ensure a
									perfect fit.</p>
							</div>

							<!-- Final Measurements -->
							<div class="block" id="blk-measure">
								<h3>Final Measurements</h3>
								<div class="grid cols-2">
									<div class="field">
										<label class="req" for="barwidth">Towing Vehicle Barwidth</label>
										<input id="barwidth" name="barwidth_mm" type="text"
											placeholder="e.g. 1800 mm" required />
									</div>
									<div class="field">
										<label class="req" for="vanwidth">Caravan Width</label>
										<input id="vanwidth" name="caravan_width_mm" type="text"
											placeholder="e.g. 2260 mm" required />
									</div>
									<div class="field">
										<label class="req" for="cleargap">Caravan Clearance Gap</label>
										<input id="cleargap" name="caravan_clearance_gap_mm" type="text"
											placeholder="e.g. 1820 mm" required />
									</div>
									<div class="field">
										<label for="vinyl">Vinyl Insert/s (based on Jayco CrossTrail)</label>
										<input id="vinyl" name="vinyl_inserts" type="text"
											placeholder="e.g. 600 × 600 mm" />
									</div>
									<label class="row"><input id="support_pockets" type="checkbox"
											name="support_pockets" /> Support
										Pockets</label>
								</div>
								<p class="note">Need help measuring? See our <a href="#" target="_blank"
										rel="noopener">Measuring Guide</a>.
								</p>
							</div>

							<!-- Final Details & Summary -->
							<div class="block" id="blk-final">
								<h3>Final Details</h3>
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

								<div class="checkboxes" style="margin-top:8px">
									<strong>Accessories</strong>
									<label><input id="acc_sleeve" type="checkbox" data-price="95" /> Add Stone
										Stomper® Bar Sleeve for
										$95</label>
									<label class="muted"><input id="acc_extra1" type="checkbox" data-price="0"
											disabled /> Add another
										accessory in here</label>
									<label class="muted"><input id="acc_extra2" type="checkbox" data-price="0"
											disabled /> Add another
										accessory in here</label>
								</div>

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
	<div class="gl-s200"></div>

</section>
<?php get_footer(); ?>
