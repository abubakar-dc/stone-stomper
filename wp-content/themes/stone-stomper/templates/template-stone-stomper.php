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
	$sts_var_select_products = $sts_fields['sts_var_select_products'] ?? null;
	$sts_var_section_head           = $sts_fields['sts_var_section_head'] ?? null;

	if($sts_var_section_head){
		$sts_var_section_headline = $sts_var_section_head['headline']??null;
		$sts_var_section_head_text = $sts_var_section_head['text']??null;
		$sts_var_section_head_image = $sts_var_section_head['image']??null;
		$sts_var_section_head_image_caption = $sts_var_section_head['image_caption'] ?? null;
	}

	$sts_var_vichle_detail_image           = $sts_fields['sts_var_vichle_detail_image'] ?? null;
	$sts_var_vichle_image_caption           = $sts_fields['sts_var_vichle_image_caption'] ?? null;
	$sts_var_example_photographs           = $sts_fields['sts_var_example_photographs'] ?? null;
	$sts_var_caravan_detail_image           = $sts_fields['sts_var_caravan_detail_image'] ?? null;

	if($sts_var_caravan_detail_image ){
		$sts_var_caravan_detail_factory_stoneguard = $sts_var_caravan_detail_image['carvan'] ? $sts_var_caravan_detail_image['carvan'] : null;
		$sts_var_caravan_image_caption = $sts_var_caravan_detail_image['image_caption'] ? $sts_var_caravan_detail_image['image_caption'] : null;
	}

	$sts_var_measurements_image           = $sts_fields['sts_var_measurements_image'] ?? null;
	$sts_var_measurements_image_caption   = $sts_fields['sts_var_measurements_image_caption'] ?? null;
	$sts_var_section_head_notices         = $sts_fields['sts_var_section_head_notices'] ?? null;

	$sts_var_section_bar_options         = $sts_fields['sts_var_section_bar_options'] ?? null;
	$sts_var_section_bar_options_title         = $sts_var_section_bar_options['title'] ?? null;
	$sts_var_section_bar_options_description         = $sts_var_section_bar_options['description'] ?? null;
	$sts_var_section_bar_options_bar_options         = $sts_var_section_bar_options['bar_options'] ?? null;
	$sts_var_section_bar_options_bar_gallery         = $sts_var_section_bar_options['bar_gallery'] ?? null;
	$mesh_only_measurement_field_notice_text         = $sts_fields['mesh_only_measurement_field_notice_text'] ?? null;

	// var_dump($mesh_only_measurement_field_notice_text);
?>


<section id="page-section" class="page-section">
	<section id="hero-section" class="hero-section hero-section-default">
		<!-- hero start -->
		<div class="hero-default">
			<div class="wp-block-cover has-custom-content-position is-position-bottom-left">

				<?php if(has_post_thumbnail($sts_var_post_id)){
					StoneStomper::the_featured_image($sts_var_post_id,2000,   array(  'class' => 'wp-block-cover__image-background wp-image-342 size-large' ) );
				}  ?>

				<span aria-hidden="true" class="wp-block-cover__background has-background-dim"></span>
				<div class="wp-block-cover__inner-container is-layout-constrained wp-block-cover-is-layout-constrained">
					<h1 class="" tabindex="0"><?php echo esc_html(get_the_title($sts_var_post_id)); ?></h1>
				</div>
			</div>
		</div>
	</section>
	<!-- Content Start -->
	<div class="st-s156"></div>
	<section>
		<div class="wrapper">
			<div class="order-section">
				<div class="iat-form-content">
					<form id="orderForm" novalidate>
						<input type="hidden" class="hidden" name="is_stone_stomper_order" value="yes">
						<div id="form-all">
							<!-- Your Details -->
							 <div class="order-form-section-inner form-detail-section d-flex justify-content-between align-items-start image-at-left">
								<div class="form-section-left example-photographs column mobile-image-hide" tabindex="0" role="img" aria-label="Image illustrating the content of this block">
									<?php if($sts_var_section_head_image){ ?>
										<?php StoneStomper::the_attachment_image($sts_var_section_head_image,1200 ); ?>
										<?php if($sts_var_section_head_image_caption){ ?>
											<div class="image-caption-area">
												<div class="image-caption">
													<p><?php echo $sts_var_section_head_image_caption; ?></p>
												</div>
											</div>
										<?php } ?>
									<?php } ?>
								</div>
								<div class="form-section-right column" id="details-section">
									<div class="content-head">
										<?php if($sts_var_section_headline){ ?>
											<h2 class=""><?php echo esc_html($sts_var_section_headline); ?></h2>
										<?php }
											if($sts_var_section_head_text){
												echo html_entity_decode($sts_var_section_head_text);
											}
										?>
									</div>
									<div class="products-select">
										<?php if ( $sts_var_select_products ) { ?>
											<div class="field jump-01">
												<select id="product_type" placeholder="Please Select" name="product_type" required>
													<option value="">Select Product</option>
													<?php foreach( $sts_var_select_products as $key =>  $sts_var_select_product ){ ?>
														<option value="<?php echo esc_html($sts_var_select_product);?>" > <?php echo esc_html(get_the_title($sts_var_select_product));  ?> </option>
													<?php } ?>
												</select>
											</div>
										<?php } ?>
									</div>
									<div class="st-s36"></div>
								<div id="jump-01" class="section-disable">
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
									<div class="mobile-image example-photographs" tabindex="0" role="img" aria-label="Image illustrating the content of this block">
										<?php if($sts_var_section_head_image){ ?>
											<?php StoneStomper::the_attachment_image($sts_var_section_head_image,1200 ); ?>
											<?php if($sts_var_section_head_image_caption){ ?>
												<div class="image-caption-area">
													<div class="image-caption">
														<p><?php echo $sts_var_section_head_image_caption; ?></p>
													</div>
												</div>
											<?php } ?>
										<?php } ?>
									</div>
									<div class="grid vehicle-details cols-2">
										<div class="field half-input-field">
											<input id="first_name" placeholder="First Name" name="customer_first_name" type="text" required />
										</div>
										<div class="field half-input-field last">
											<input id="last_name" placeholder="Last Name" name="customer_last_name" type="text" required />
										</div>
										<div class="field">
											<input id="cust_phone" placeholder="Phone" name="customer_phone" type="text" required />
										</div>
										<div class="field">
											<input id="cust_address" placeholder="Home Address" name="customer_address" type="text" required />
										</div>
										<div class="grid cols-2 two-columns-fields">
											<div class="field">
												<input id="cust_suburb" placeholder="Suburb" name="customer_suburb" type="text"
													required />
											</div>
											<div class="field">
												<select id="cust_state" placeholder="State" name="customer_state" required>
													<option value="">State*</option>
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
										<div class="field">
											<input id="cust_email" placeholder="Email Address" name="customer_email" type="email" required />
										</div>


										<?php
											if($sts_var_section_head_notices){
												foreach($sts_var_section_head_notices as $sts_key => $notice) {
												$sts_var_notice = $notice['notice']??null;
												if($sts_key === 0){ ?>
												<?php if ( $sts_var_notice ) { ?>
														<div class="note notice-bar"><?php echo html_entity_decode($sts_var_notice); ?></div>
													<?php } ?>
												<?php
												}
													}
												}
											?>
									</div>
									</div>
								</div>
							</div>
							<!-- Towing Vehicle Details -->
							<div id="vehicle-details" class="section-disable order-form-section-inner d-flex form-vehicle-section justify-content-between align-items-start image-at-left">
								<div class="form-section-left column" tabindex="0" role="img" aria-label="Image illustrating the content of this block">
									<div class="grid cols-2 vehicle-images ">
										<div class="towing-vehicle-image" id="towing-vehicle-image" tabindex="0" role="img"  aria-label="Image illustrating the content of this block">
												<?php
													if($sts_var_vichle_detail_image ){
													 	StoneStomper::the_attachment_image($sts_var_vichle_detail_image,1200 );
														if($sts_var_vichle_image_caption){ ?>
															 <div class="image-caption-area">
																 <div class="image-caption">
																	 <p><?php echo $sts_var_vichle_image_caption; ?></p>
																 </div>
															 </div>
														 <?php }
													}
												?>
										</div>
									</div>
								</div>
								<div class="form-section-right column" id="blk-vehicle">
									<?php if($sts_var_section_head_notices){
										foreach($sts_var_section_head_notices as $sts_key => $notice){
											$sts_var_headline = $notice['headline']??null;
											$sts_var_text = $notice['text']??null;

											if($sts_key === 1){ ?>
												<?php if ( $sts_var_headline ) { ?>
													<h3><?php echo esc_html($sts_var_headline); ?></h3>
												<?php }
												if($sts_var_text){ ?>
													<p><?php echo html_entity_decode($sts_var_text); ?></p>
												<?php } ?>
											<?php }
										}
									} ?>
									<div class="towing-vehicle-image mobile-image" id="towing-vehicle-image" tabindex="0" role="img"  aria-label="Image illustrating the content of this block">
										<?php
											if($sts_var_vichle_detail_image ){
												StoneStomper::the_attachment_image($sts_var_vichle_detail_image,1200 );
												if($sts_var_vichle_image_caption){ ?>
													<div class="image-caption-area">
														<div class="image-caption">
															<p><?php echo $sts_var_vichle_image_caption; ?></p>
														</div>
													</div>
												<?php }
											}
										?>
									</div>
									<div class="grid cols-2 caravan-details">
										<div class="field vehicle-make-field-set">
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
										<div class="veh_make_other">
										</div>
										<div class="field vehicle-model-group">
											<label class="req" for="veh_model">Vehicle Model</label>
											<!-- default should be dropdown -->
											<select id="veh_model" name="vehicle_model" required >
												<option value="">Select Vehicle Model </option>
											</select>
											<div class="veh_model_other"></div>
										</div>
										<div class="field vehicle-year-group">
											<label class="req" for="veh_year">Year of Manufacture</label>
											<select id="veh_year" name="vehicle_year" required>
												<option value="">Select Model Year </option>
											</select>
											 <div class="veh_year_other">
											</div>

										</div>
									</div>
									<?php
										if($sts_var_section_head_notices){
											foreach($sts_var_section_head_notices as $sts_key => $notice) {
											$sts_var_notice = $notice['notice']??null;
											if($sts_key === 1){ ?>
											<?php if ( $sts_var_notice ) { ?>
												<div id="vehicle-notice-bar" class="note notice-bar" style="display:none;"><?php echo html_entity_decode($sts_var_notice); ?></div>
												<?php } ?>
											<?php }
											}
										}
									?>
								</div>
							</div>
							<!-- Caravan Details -->
							<div id="caravan-details" class="section-disable  order-form-section-inner d-flex form-carvan-section justify-content-between align-items-start image-at-left">
								<div class="form-section-left column" tabindex="0" role="img" aria-label="Image illustrating the content of this block">
									<div class="grid cols-2 vehicle-images">
										<div class="vehicle-images mobile-image-hide" id="caravan-images" tabindex="0" role="img" aria-label="Image illustrating the content of this block">
											<?php if ( $sts_var_caravan_detail_factory_stoneguard ) { ?>
												<div class="vehicle-image">
													<?php StoneStomper::the_attachment_image( $sts_var_caravan_detail_factory_stoneguard, 1200 ); ?>
													<?php if($sts_var_caravan_image_caption){ ?>
														<div class="image-caption-area">
															<div class="image-caption">
																<p><?php echo $sts_var_caravan_image_caption; ?></p>
															</div>
														</div>
													<?php } ?>
												</div>
											<?php } ?>
										</div>
									</div>
								</div>
								<div class="form-section-right column photographs-details" id="blk-caravan">
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
									<div class="vehicle-image  mobile-image" id="caravan-images" tabindex="0" role="img"  aria-label="Image illustrating the content of this block">
										<?php
											if($sts_var_caravan_detail_factory_stoneguard ){
												StoneStomper::the_attachment_image($sts_var_caravan_detail_factory_stoneguard,1200 );
												if($sts_var_caravan_image_caption){ ?>
													<div class="image-caption-area">
														<div class="image-caption">
															<p><?php echo $sts_var_caravan_image_caption; ?></p>
														</div>
													</div>
												<?php }
											}
										?>
									</div>
									<div class="grid cols-2 ">
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
												<option value="other">Other</option>

											</select>
										</div>
										<div class="field van-model-group">
											<label class="req" for="van_model">Caravan Model</label>
											<select id="van_model" name="caravan_model" required>
												<option value="">Select Caravan Model</option>
											</select>
											<div class="van_model_other">

											</div>
										</div>
									</div>
									<?php
									if($sts_var_section_head_notices){
										foreach($sts_var_section_head_notices as $sts_key => $notice) {
										$sts_var_notice = $notice['notice']??null;
										if($sts_key === 2){ ?>
										<?php if ( $sts_var_notice ) { ?>
												<div id="caravan-notice-bar" class="note notice-bar" style="display:none;"><?php echo html_entity_decode($sts_var_notice); ?></div>
											<?php } ?>
										<?php
										}
											}
										}
									?>
								</div>
							</div>
							<!-- Bar Option -->
							<div id="bar-options-section" class="section-disable  order-form-section-inner d-flex form-bar-options-section justify-content-between align-items-start image-at-left">
								<div class="form-section-left hide-on-mobile column" tabindex="0" role="img" aria-label="Image illustrating the content of this block">
									<div class="form-image-slider">
										<?php if($sts_var_section_bar_options_bar_gallery){ ?>
											<?php foreach($sts_var_section_bar_options_bar_gallery as $sts_key => $photo){
												$caption = wp_get_attachment_caption( $photo );
												?>
												<div class="slick-slide">
													<div class="slider-image">
														<?php StoneStomper::the_attachment_image($photo,1200 ); ?>
														<?php if ( $caption ) { ?>
															<div class="image-caption-area">
																<div class="image-caption">
																	<p><?php echo esc_html( $caption ); ?></p>
																</div>
															</div>
														<?php } ?>
													</div>
												</div>
											<?php }
										} ?>
									</div>
								</div>
								<div class="form-section-right column photographs-details" id="blk-caravan">
									<!-- Section Head -->
									<?php if ( $sts_var_section_bar_options_title ) { ?>
										<h3><?php echo esc_html($sts_var_section_bar_options_title); ?></h3>
									<?php }
									if($sts_var_section_bar_options_description){ ?>
										<p><?php echo html_entity_decode($sts_var_section_bar_options_description); ?></p>
									<?php } ?>

									<!-- Mobile Image -->
									<div class="vehicle-image mobile-form-image-slider" id="caravan-images" tabindex="0" role="img"  aria-label="Image illustrating the content of this block">
										<?php if($sts_var_section_bar_options_bar_gallery){ ?>
											<?php foreach($sts_var_section_bar_options_bar_gallery as $sts_key => $photo){
												$caption = wp_get_attachment_caption( $photo );
												?>
												<div class="slick-slide">
													<div class="slider-image">
														<?php StoneStomper::the_attachment_image($photo,1200 ); ?>
														<?php if ( $caption ) { ?>
															<div class="image-caption-area">
																<div class="image-caption">
																	<p><?php echo esc_html( $caption ); ?></p>
																</div>
															</div>
														<?php } ?>
													</div>
												</div>
											<?php }
										} ?>
									</div>
									<?php if($sts_var_section_bar_options_bar_options){ ?>
										<div class="grid cols-2 ">
											<div class="field">
												<select id="bar_options" name="bar_options" required>
													<option value="">Select Bar Option</option>
													<?php foreach ( $sts_var_section_bar_options_bar_options as $option ) {
														$single_bar_option = $option['add_option']; ?>
														<option value="<?php echo esc_attr( $single_bar_option ); ?>"><?php echo esc_html( $single_bar_option ); ?></option>
													<?php } ?>
												</select>
											</div>
											<fieldset class="hitch_measurement_dropdown" style="display:none">
												<div class="note notice-bar mt-0">
													Additional Measurement required for this bar option. Please refer to the image gallery to determine how to find this length. Alternatively, view instructions for "Option 2" ( <a href="https://stonestomper.com.au/wp-admin/upload.php?item=2177">https://stonestomper.com.au/wp-admin/upload.php?item=2177</a> )  or Option 3" ( <a href="https://stonestomper.com.au/wp-admin/upload.php?item=2176">https://stonestomper.com.au/wp-admin/upload.php?item=2176</a> ).
												</div>
												<div class="field">
													<label class="req screen-reader-text" for="additional_hitch_measurement">Additional Measurement required for this bar option.</label>
													<input id="additional_hitch_measurement" name="additional_hitch_measurement" type="text" inputmode="numeric" pattern="[0-9]*"
														placeholder="e.g. 1800" required />
												</div>
											</fieldset>
										</div>
									<?php } ?>
								</div>
							</div>
							<!-- Photographs -->
							<div id="photographs-details" class=" section-disable order-form-section-inner d-flex form-carvan-section justify-content-between align-items-start image-at-left">
								<div class="form-section-left column" tabindex="0" role="img" aria-label="Image illustrating the content of this block">
									<div class="form-image-slider">
										<?php if($sts_var_example_photographs){ ?>
											<?php foreach($sts_var_example_photographs as $sts_key => $photo){
												$caption = wp_get_attachment_caption( $photo );
												?>
												<div class="slick-slide">
													<div class="slider-image">
														<?php StoneStomper::the_attachment_image($photo,1200 ); ?>
														<?php if ( $caption ) { ?>
															<div class="image-caption-area">
																<div class="image-caption">
																	<p><?php echo esc_html( $caption ); ?></p>
																</div>
															</div>
														<?php } ?>
													</div>
												</div>
											<?php }
										} ?>
									</div>
								</div>
								<div class="form-section-right column" id="blk-photos">
									<?php if($sts_var_section_head_notices) {
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
									<div class="form-image-slider mobile-image">
										<?php if($sts_var_example_photographs){ ?>
											<?php foreach($sts_var_example_photographs as $sts_key => $photo){
												$caption = wp_get_attachment_caption( $photo );

												?>
												<div class="slick-slide">
													<div class="slider-image">
														<?php StoneStomper::the_attachment_image($photo,1200 ); ?>
														<?php if ( $caption ) { ?>
																<div class="image-caption-area">
																	<div class="image-caption">
																		<p><?php echo esc_html( $caption ); ?></p>
																	</div>
																</div>
															<?php } ?>
													</div>
												</div>
											<?php }
										} ?>
									</div>
									<div class="grid cols-2">
										<div class="field">
											<label class="req" for="photo_hitch">Hitch Photograph</label>
											<input id="photo_hitch" name="photo_hitch" required type="file" accept="image/*"
												multiple />
											<ul class="uploads" id="list_hitch"></ul>
											<input type="hidden" id="hitch_ids" required name="hitch_ids" value="[]">
										</div>
										<div class="field">
											<label class="req" for="photo_rear">Towing Vehicle Rear
												Photograph</label>
											<input id="photo_rear" name="photo_rear" required type="file" accept="image/*"
												multiple />
											<ul class="uploads" id="list_rear"></ul>
											<input type="hidden" id="rear_ids" required name="rear_ids"  value="[]">
										</div>
										<div class="field">
											<label class="req" for="photo_front">Front of Caravan Photograph</label>
											<input id="photo_front" name="photo_front" required type="file" accept="image/*"
												multiple />
											<ul class="uploads" id="list_front"></ul>
											<input type="hidden" id="front_ids" required name="front_ids" value="[]">
										</div>
									</div>
									<?php
										if($sts_var_section_head_notices) {
											foreach($sts_var_section_head_notices as $sts_key => $notice) {
												$sts_var_notice = $notice['notice']??null;
												if($sts_key === 3){ ?>
												<?php if ( $sts_var_notice ) { ?>
														<div class="note notice-bar"><?php echo html_entity_decode($sts_var_notice); ?></div>
													<?php } ?>
												<?php
												}
											}
										}
									?>
								</div>
							</div>
							<!-- Final Measurements -->
							<div id="final-measurements" class=" section-disable order-form-section-inner d-flex form-measurements-section justify-content-between align-items-start image-at-left">
								<div class="form-section-left column" tabindex="0" role="img" aria-label="Image illustrating the content of this block">
									<div class="grid cols-2 measurements-images example-photographs">
										<?php
											if($sts_var_measurements_image){
												StoneStomper::the_attachment_image($sts_var_measurements_image,1200 );
											}
										?>
										<?php if($sts_var_measurements_image_caption){ ?>
											<div class="image-caption-area">
												<div class="image-caption">
													<p><?php echo $sts_var_measurements_image_caption; ?></p>
												</div>
											</div>
										<?php } ?>
									</div>
								</div>
								<div class="form-section-right column" id="blk-measure">
									<?php if($sts_var_section_head_notices) {
										foreach($sts_var_section_head_notices as $sts_key => $notice){
											$sts_var_headline = $notice['headline']??null;
											$sts_var_text = $notice['text']??null;
											if($sts_key === 4) { ?>
												<?php if ( $sts_var_headline ) { ?>
													<h3><?php echo esc_html($sts_var_headline); ?></h3>
												<?php }
												if($sts_var_text){ ?>
													<p><?php echo html_entity_decode($sts_var_text); ?></p>
												<?php } ?>
											<?php }
										}
									} ?>
									<div class="grid cols-2 measurements-images mobile-image example-photographs">
										<?php
											if($sts_var_measurements_image){
												StoneStomper::the_attachment_image($sts_var_measurements_image,1200 );
											}
										?>
										<?php if($sts_var_measurements_image_caption){ ?>
											<div class="image-caption-area">
												<div class="image-caption">
													<p><?php echo $sts_var_measurements_image_caption; ?></p>
												</div>
											</div>
										<?php } ?>
									</div>
									<div class="grid cols-2">
										<div class="field mesh-only-field">
											<label class="req" for="meshmeasurment">Bracket on the caravan to Stone Stomper while hitched up straight</label>
											<input id="meshmeasurment" name="meshmeasurment_mm" type="text" inputmode="numeric" pattern="[0-9]*"
												placeholder="e.g. 1800" required />
											<?php if ( $mesh_only_measurement_field_notice_text ) { ?>
												<div class="note notice-bar"><?php echo html_entity_decode($mesh_only_measurement_field_notice_text); ?></div>
											<?php } ?>
										</div>
										<div class="field">
											<label class="req" for="barwidth">Towing Vehicle Barwidth (mm)</label>
											<input id="barwidth" name="barwidth_mm" type="text" inputmode="numeric" pattern="[0-9]*"
												placeholder="e.g. 1800" required />
										</div>
										<div class="field" >
											<label class="req" for="vanwidth">Caravan Width (mm)</label>
											<input id="vanwidth" name="caravan_width_mm" type="text" inputmode="numeric" pattern="[0-9]*"
												placeholder="e.g. 1900" required />
										</div>
										<div class="field">
											<label class="req" for="a_frame_length">A-Frame Length (mm)</label>
											<input id="a_frame_length" name="a_frame_length_mm" type="text" inputmode="numeric" pattern="[0-9]*"
												placeholder="e.g. 1800" required />
										</div>
										<fieldset class="ss-support-options" style="display:none">
											<div class="note notice-bar mt-0">As the A-Frame length is longer than 1800mm, we require a mid-fixing point for your Stone Stomper. Please select one of the following options. If you are unsure, please select "Support Pockets"</div>
											<div class="ginput_container ginput_container_checkbox extra-support">
												<!-- ToolBox -->
												<div class="gchoice stone-stomper-supports">
													<div class="checkbox-item">
														<input class="gfield-choice-input" name="input_1.3" type="radio" value="toolbox" id="toolbox">
														<label for="toolbox" id="label_4_1_2">Toolbox</label>
													</div>
													<div class="toolbox-support" style="display:none">
														<div class="factory_stoneguard_inner two-columns-fields">
															<div class="field extra-support" >
																<label class="req" for="toolbox_width">Width (mm)</label>
																<input id="toolbox_width" name="toolbox_width_mm" type="text" inputmode="numeric" pattern="[0-9]*"
																	placeholder="e.g. 500 mm" required />
															</div>
															<div class="field extra-support">
																<label class="req" for="toolbox_length">Distance from Caravan (mm)</label>
																<input id="toolbox_length" name="toolbox_length_mm" type="text" inputmode="numeric" pattern="[0-9]*"
																	placeholder="e.g. 500 mm" required />
															</div>
														</div>
													</div>
												</div>
												<!-- Stone Gaurd -->
												<div class="gchoice stone-stomper-supports">
													<input class="gfield-choice-input" name="input_1.3" type="radio" value="factory-stoneguard" id="factory_stoneguard">
													<label for="factory_stoneguard" id="label_4_1_1">Factory Stoneguard</label>
													<div class="factory_stoneguard" style="display:none">
														<div class="factory_stoneguard_inner two-columns-fields">
															<div class="field extra-support">
																<label class="req" for="stoneguard_width">Width (mm)</label>
																<input id="stoneguard_width" name="stoneguard_width_mm" type="text" inputmode="numeric" pattern="[0-9]*"
																	placeholder="e.g.600 mm" required />
															</div>
															<div class="field extra-support">
																<label class="req" for="stoneguard_length">Distance from Caravan (mm)</label>
																<input id="stoneguard_length" name="stoneguard_length_mm" type="text" inputmode="numeric" pattern="[0-9]*"
																	placeholder="e.g.600 mm" required />
															</div>
														</div>
													</div>
												</div>
												<!-- Support Pockets -->
												<div class="gchoice stone-stomper-supports">
													<input class="gfield-choice-input" name="input_1.3" type="radio" value="support_pockets" id="support_pockets">
													<label for="support_pockets" id="label_4_1_3">Support Pockets</label>
													<div class="support_pockets" style="display:none">
														<div class="field extra-support">
															<label class="req" for="support_pocket_length">Support Pocket Distance From Caravan (MM)</label>
															<input id="support_pocket_length" name="support_pocket_length_mm" type="text" inputmode="numeric" pattern="[0-9]*"
																placeholder="e.g.600 mm" required />
														</div>
													</div>
												</div>
											</div>
										</fieldset>
										<div class="field hidden">
											<label class="req" for="vinyl_width">Vinyl Insert Width</label>
											<input id="vinyl_width" name="vinyl_width_mm" type="text" inputmode="numeric" pattern="[0-9]*"
												placeholder="e.g.600 mm" required />
										</div>
										<div class="field hidden">
											<label class="req" for="vinyl_length">Vinyl Insert Length</label>
											<input id="vinyl_length" name="vinyl_length_mm" type="text" inputmode="numeric" pattern="[0-9]*"
												placeholder="e.g.600 mm" required />
										</div>
										<!--
											<label class="row">
												<input id="support_pockets" type="checkbox" name="support_pockets" />
													Support Pockets
											</label>
										-->
									</div>
									<?php
										if($sts_var_section_head_notices){
											foreach($sts_var_section_head_notices as $sts_key => $notice) {
											$sts_var_notice = $notice['notice']??null;
											if($sts_key === 4){ ?>
											<?php if ( $sts_var_notice ) { ?>
													<div class="note notice-bar"><?php echo html_entity_decode($sts_var_notice); ?></div>
												<?php } ?>
											<?php
											}
											}
										}
									?>
								</div>
							</div>
							<!-- Final Details & Summary -->
							<div id="final-summary" class=" section-disable order-form-section-inner d-flex form-details-section justify-content-between align-items-start image-at-left">
								<div class="form-section-left column" tabindex="0" role="img" aria-label="Image illustrating the content of this block">
									<?php
										global $product;
										$p = wc_get_product( 545 );
										$upsell_ids = $p ? $p->get_upsell_ids() : [];
										$count_ids = count($upsell_ids);
									?>
									<?php if ( $upsell_ids ) { ?>
										<div class="form-image-slider <?php if($count_ids === 1){ echo 'no-slider'; } ?>">
											<?php
												foreach ( $upsell_ids as $upsell_id ) {
													$upsell = wc_get_product( $upsell_id );
													if ( ! $upsell ) {
														continue;
													}

													$title = $upsell->get_name();

													if ( has_post_thumbnail( $upsell_id ) ) {
														?>
														<div class="slick-slide">
															<div class="slider-image">
																<?php echo get_the_post_thumbnail( $upsell_id, 'thumb_800', [ 'alt' => esc_attr( $title ) ] ); ?>

																<?php if ( $title ) { ?>
																	<div class="image-caption-area">
																		<div class="image-caption">
																			<p><?php echo esc_html( $title ); ?></p>
																		</div>
																	</div>
																<?php } ?>
															</div>
														</div>
														<?php
													}
												}
											?>
										</div>
									<?php } ?>
								</div>
								<div class="form-section-right column" id="blk-final">
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
											<label class="req" for="order_notes-details">Order Notes</label>
											<textarea id="order_notes-details" name="order_notes" placeholder="Add any special notes here" maxlength="500"></textarea>
											<p class="note notice-bar hidden" id="move_note">Our Team will contact you once your order is ready on your given phone number</p>
										</div>
										<div class="field">
											<label class="req" for="final_address">Delivery Address</label>
											<select id="final_address" name="final_delivery" required>
												<option value="">Select…</option>
												<option value="same">Same as above</option>
												<option value="move">I am on the move</option>
											</select>
											<p class="note notice-bar hidden" id="move_note">Our Team will contact you once your order is ready on your given phone number</p>
										</div>

									</div>
									<div class="form-section-left column mobile-image" tabindex="0" role="img" aria-label="Image illustrating the content of this block">
										<div class="grid cols-2 details-images ">
											<?php
												global $product;
												$p = wc_get_product( 545 );
												$upsell_ids = $p ? $p->get_upsell_ids() : [];

												if ( $upsell_ids ) {
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
																	echo '<img src="' . esc_url( get_the_post_thumbnail_url( $upsell_id, 'thumb_800' ) ) . '" alt="' . esc_attr( $title ) . '" />';
																	echo '<p>' . esc_html( $title ) . '</p>';
																echo '</div>';
															}
														echo '</div>';
													}
												}
											?>
										</div>
									</div>
									<!-- Section ends -->
									<?php
									global $product;
									$p = wc_get_product( 545 );
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
												echo '<div class="checkbox-item" style="margin-top:8px">';
													echo '<label>';
														echo '<input type="checkbox" class="acc-upsell" data-product-id="' . esc_attr( $upsell_id ) . '" data-price-value="' . esc_attr( $price ) . '" />';
														echo '<span> ' . $title . ' for ' . wc_price( $price ). '</span>';
													echo '</label>';
												echo '</div>';
											}

										echo '</div>';
									}
									?>
									<div class="summary" id="order_summary">
										<div class="line">
											<span>Stone Stomper®</span>
											<strong id="selected-product-price" >$825.00</strong>
										</div>
										<div class="total"><span>Total</span> <strong>$<span data-id="total">825.00</span></strong> <span class="muted">inc. GST</span></div>
									</div>
									<div class="actions">
										<button class="btn primary" type="button" id="btn_cart">Add to Cart</button>
									</div>

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
	<div class="ts-80 mobile-image-hide"></div>
	<!-- Content End -->
	<div class="st-s200 mobile-image-hide"></div>

</section>
<?php get_footer(); ?>
