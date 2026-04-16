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
	$sts_var_section_bar_options_description_option_two         	= $sts_var_section_bar_options['option_two_description'] ?? null;
	$sts_var_section_bar_options_description_option_three         	= $sts_var_section_bar_options['option_three_description'] ?? null;
	$sts_var_section_bar_options_bar_options         = $sts_var_section_bar_options['bar_options'] ?? null;
	$sts_var_section_bar_options_bar_gallery         = $sts_var_section_bar_options['bar_gallery'] ?? null;
	$mesh_only_measurement_field_notice_text         = $sts_fields['mesh_only_measurement_field_notice_text'] ?? null;

	// Bar option question labels and images
	$sts_var_bar_question_1 = $sts_var_section_bar_options['bar_question_1'] ?? null;
	$sts_var_bar_question_2 = $sts_var_section_bar_options['bar_question_2'] ?? null;
	$sts_var_bar_question_3 = $sts_var_section_bar_options['bar_question_3'] ?? null;
	$sts_var_bar_question_4 = $sts_var_section_bar_options['bar_question_4'] ?? null;
	$sts_var_bar_no_option_message = $sts_var_section_bar_options['bar_no_option_message'] ?? null;

	// Question 1
	$sts_var_bar_question_1_label = $sts_var_bar_question_1['label'] ?? 'Do you have greater than 41mm on your shank?';
	$sts_var_bar_question_1_image = $sts_var_bar_question_1['image'] ?? null;
	$sts_var_bar_question_1_image_url = $sts_var_bar_question_1_image ? wp_get_attachment_image_url($sts_var_bar_question_1_image, 'full') : '';
	$sts_var_bar_question_1_hitch_measurement = $sts_var_bar_question_1['hitch_measurement'] ?? null;
	$sts_var_bar_question_1_bar_option_value = $sts_var_bar_question_1['bar_option_value'] ?? null;

	// Question 2
	$sts_var_bar_question_2_label = $sts_var_bar_question_2['label'] ?? 'Do you have a DO35 or DO45 hitch?';
	$sts_var_bar_question_2_image = $sts_var_bar_question_2['image'] ?? null;
	$sts_var_bar_question_2_image_url = $sts_var_bar_question_2_image ? wp_get_attachment_image_url($sts_var_bar_question_2_image, 'full') : '';
	$sts_var_bar_question_2_hitch_measurement = $sts_var_bar_question_2['hitch_measurement'] ?? null;
	$sts_var_bar_question_2_bar_option_value = $sts_var_bar_question_2['bar_option_value'] ?? null;

	// Question 3

	$sts_var_bar_question_3_label = $sts_var_bar_question_3['label'] ?? 'Do you have an Adjustable Hitch?';
	$sts_var_bar_question_3_image = $sts_var_bar_question_3['image'] ?? null;
	$sts_var_bar_question_3_image_url = $sts_var_bar_question_3_image ? wp_get_attachment_image_url($sts_var_bar_question_3_image, 'full') : '';
	$sts_var_bar_question_3_hitch_measurement = $sts_var_bar_question_3['hitch_measurement'] ?? null;
	$sts_var_bar_question_3_bar_option_value = $sts_var_bar_question_3['bar_option_value'] ?? null;

	// Question 4

	$sts_var_bar_question_4_label = $sts_var_bar_question_4['label'] ?? 'Do you have 85mm on your tongue?';
	$sts_var_bar_question_4_image = $sts_var_bar_question_4['image'] ?? null;
	$sts_var_bar_question_4_image_url = $sts_var_bar_question_4_image ? wp_get_attachment_image_url($sts_var_bar_question_4_image, 'full') : '';
	$sts_var_bar_question_4_hitch_measurement = $sts_var_bar_question_4['hitch_measurement'] ?? null;
	$sts_var_bar_question_4_bar_option_value = $sts_var_bar_question_4['bar_option_value'] ?? null;
	$sts_var_bar_question_4_bar_option_value_yes = $sts_var_bar_question_4['bar_option_value_yes'] ?? null;
	$sts_var_bar_question_4_bar_option_value_no = $sts_var_bar_question_4['bar_option_value_no'] ?? null;

	// Final measurements Video

	$sts_var_cptr_mrmnts_video_btn_text 	   	= $sts_fields['sts_var_cptr_mrmnts_video_btn_text'] ?? null;
	$sts_var_cptr_mrmnts_video_type 	  		= $sts_fields['sts_var_cptr_mrmnts_video_type'] ?? null;
	$sts_var_cptr_mrmnts_video_embed 	   		= $sts_fields['sts_var_cptr_mrmnts_video_embed'] ?? null;
	$sts_var_cptr_mrmnts_video_upload 	   		= $sts_fields['sts_var_cptr_mrmnts_video_upload'] ?? null;

	// Final measurements notices

	$sts_var_notice_towing_vehicle_barwidth_mm         = $sts_fields['sts_var_notice_towing_vehicle_barwidth_mm'] ?? null;
	$sts_var_notice_caravan_width_mm         = $sts_fields['sts_var_notice_caravan_width_mm'] ?? null;
	$sts_var_a_frame_length_mm         = $sts_fields['sts_var_a_frame_length_mm'] ?? null;


	// var_dump($mesh_only_measurement_field_notice_text);
?>


<section id="page-section" class="page-section">
	<section id="hero-section" class="hero-section hero-section-default">
		<div class="hero-default">
			<div class="default-hero-cover has-custom-content-position is-position-bottom-left">
				<?php if(has_post_thumbnail($sts_var_post_id)){
					StoneStomper::the_featured_image($sts_var_post_id,2000,   array(  'class' => 'wp-block-cover__image-background wp-image-342 size-large' ) );
				}  ?>
				<div class="default-hero-cover-content">
					<h1 class="" tabindex="0"><?php echo esc_html(get_the_title($sts_var_post_id)); ?></h1>
				</div>
			</div>
		</div>
	</section>
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
									<div class="clear-form-button">
										<button type="button" id="clear-order-form">Clear Form</button>
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
												<input id="first_name" placeholder="First Name (Required)" name="customer_first_name" type="text" required />
											</div>
											<div class="field half-input-field last">
												<input id="last_name" placeholder="Last Name (Required)" name="customer_last_name" type="text" required />
											</div>
											<div class="field">
												<input id="cust_phone" placeholder="Phone (Required)" name="customer_phone" type="text" required inputmode="numeric" pattern="[0-9]*"/>
											</div>
											<div class="field">
												<input id="cust_email" placeholder="Email Address" name="customer_email" type="email" required />
											</div>
											<?php
												if($sts_var_section_head_notices) {
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
										if($sts_var_section_head_notices) {
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
									<div id="bar-question-image-container" class="bar-question-image-display"
										data-q1-image="<?php echo esc_attr($sts_var_bar_question_1_image_url); ?>"
										data-q2-image="<?php echo esc_attr($sts_var_bar_question_2_image_url); ?>"
										data-q3-image="<?php echo esc_attr($sts_var_bar_question_3_image_url); ?>"
										data-q4-image="<?php echo esc_attr($sts_var_bar_question_4_image_url); ?>">
										<?php if($sts_var_bar_question_1_image){ ?>
											<?php StoneStomper::the_attachment_image($sts_var_bar_question_1_image, 1200, array('class' => 'bar-q-image', 'id' => 'bar-q-image-1', 'data-question' => '1')); ?>
										<?php } ?>
										<img id="bar-q-image-2" class="bar-q-image" data-question="2" src="<?php echo esc_attr($sts_var_bar_question_2_image_url); ?>" alt="Question 2" style="display:none;" />
										<img id="bar-q-image-3" class="bar-q-image" data-question="3" src="<?php echo esc_attr($sts_var_bar_question_3_image_url); ?>" alt="Question 3" style="display:none;" />
										<img id="bar-q-image-4" class="bar-q-image" data-question="4" src="<?php echo esc_attr($sts_var_bar_question_4_image_url); ?>" alt="Question 4" style="display:none;" />
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
										<div class="grid cols-2 " data-q1-hitch="<?php echo esc_attr($sts_var_bar_question_1_hitch_measurement); ?>" data-q2-hitch="<?php echo esc_attr($sts_var_bar_question_2_hitch_measurement); ?>" data-q3-hitch="<?php echo esc_attr($sts_var_bar_question_3_hitch_measurement); ?>" data-q4-hitch="<?php echo esc_attr($sts_var_bar_question_4_hitch_measurement); ?>">
											<div class="field">
												<label class="req question-label" for="bar_q_shank_41"><?php echo esc_html( $sts_var_bar_question_1_label ); ?></label>
												<select id="bar_q_shank_41" name="bar_q_shank_41" required data-question="1" data-hitch-measurement="<?php echo esc_attr($sts_var_bar_question_1_hitch_measurement); ?>" data-bar-option-value="<?php echo esc_attr($sts_var_bar_question_1_bar_option_value); ?>">
													<option value="">Select</option>
													<option value="yes">Yes</option>
													<option value="no">No</option>
												</select>
											</div>
											<div class="field bar-question bar-q-do35" style="display:none;">
												<label class="req question-label" for="bar_q_do35_do45"><?php echo esc_html( $sts_var_bar_question_2_label ); ?></label>
												<select id="bar_q_do35_do45" name="bar_q_do35_do45" data-question="2" data-hitch-measurement="<?php echo esc_attr($sts_var_bar_question_2_hitch_measurement); ?>" data-bar-option-value="<?php echo esc_attr($sts_var_bar_question_2_bar_option_value); ?>">
													<option value="">Select</option>
													<option value="yes">Yes</option>
													<option value="no">No</option>
												</select>
											</div>
											<div class="field bar-question bar-q-adjustable" style="display:none;">
												<label class="req question-label" for="bar_q_adjustable_hitch"><?php echo esc_html( $sts_var_bar_question_3_label ); ?></label>
												<select id="bar_q_adjustable_hitch" name="bar_q_adjustable_hitch" data-question="3" data-hitch-measurement="<?php echo esc_attr($sts_var_bar_question_3_hitch_measurement); ?>" data-bar-option-value="<?php echo esc_attr($sts_var_bar_question_3_bar_option_value); ?>">
													<option value="">Select</option>
													<option value="yes">Yes</option>
													<option value="no">No</option>
												</select>
											</div>
											<div class="field bar-question bar-q-tongue" style="display:none;">
												<label class="req question-label" for="bar_q_tongue_85"><?php echo esc_html( $sts_var_bar_question_4_label ); ?></label>
												<select id="bar_q_tongue_85" name="bar_q_tongue_85" data-question="4" data-hitch-measurement="<?php echo esc_attr($sts_var_bar_question_4_hitch_measurement); ?>">
													<option value="">Select</option>
													<option value="yes" data-bar-option-value="<?php echo esc_attr($sts_var_bar_question_4_bar_option_value_yes); ?>">Yes</option>
													<option value="no" data-bar-option-value="<?php echo esc_attr($sts_var_bar_question_4_bar_option_value_no); ?>">No</option>
												</select>
											</div>
											<div class="field">
												<select id="bar_options" name="bar_options" style="display:none;">
													<option value="">Select Bar Option</option>
													<?php foreach ( $sts_var_section_bar_options_bar_options as $option ) {
														$single_bar_option = $option['add_option'];
														$add_value = $option['add_value'];
													?>
														<option value="<?php echo esc_attr( $single_bar_option ); ?>" data-value="<?php echo esc_attr( $add_value ); ?>"><?php echo esc_html( $single_bar_option ); ?></option>
													<?php } ?>
												</select>
												<input type="hidden" name="bar_option_value" id="bar_option_value" value="">
												<input type="hidden" name="sts_var_caravan_bar_option" id="sts_var_caravan_bar_option" value="">
												<input type="hidden" name="question_bar_option_value" id="question_bar_option_value" value="">
											</div>
											<fieldset class="hitch_measurement_dropdown" style="display:none">
												<?php if($sts_var_section_bar_options_description_option_two){ ?>
													<div id="option-two-description" class="note notice-bar option-two-description mt-0"  style="display:none">
														<?php echo html_entity_decode( $sts_var_section_bar_options_description_option_two ); ?>
													</div>
												<?php } ?>
												<?php if($sts_var_section_bar_options_description_option_three){ ?>
													<div id="option-three-description" class="note notice-bar option-three-description mt-0"  style="display:none">
														<?php echo html_entity_decode( $sts_var_section_bar_options_description_option_three ); ?>
													</div>
												<?php } ?>
												<?php if($sts_var_bar_no_option_message){ ?>
													<div id="no-option-message" class="note notice-bar no-option-message mt-0"  style="display:none">
														<?php echo html_entity_decode( $sts_var_bar_no_option_message ); ?>
													</div>
												<?php } else { ?>
													<div id="no-option-message" class="note notice-bar no-option-message mt-0"  style="display:none">
														Please attach clear photographs in the next section and we will identify the right option for you.
													</div>
												<?php } ?>
												<div class="field">
													<label class="req" for="additional_hitch_measurement">Mesurement  (mm)</label>
													<input id="additional_hitch_measurement" name="additional_hitch_measurement" type="text" inputmode="numeric" pattern="[0-9]*"
														placeholder="eg. 300 mm" required />
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
										if($sts_key === 3) { ?>
											<?php if ( $sts_var_headline ) { ?>
												<h3><?php echo esc_html($sts_var_headline); ?></h3>
											<?php }
											if($sts_var_text) { ?>
												<p><?php echo html_entity_decode($sts_var_text); ?></p>
											<?php } ?>
											<?php
											}
										}
									} ?>
									<div class="form-image-slider mobile-image">
										<?php if($sts_var_example_photographs){ ?>
											<?php foreach($sts_var_example_photographs as $sts_key => $photo){
												$caption = wp_get_attachment_caption( $photo ); ?>
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
											<input id="photo_hitch" name="photo_hitch" required type="file" accept="image/*" />
											<ul class="uploads" id="list_hitch"></ul>
											<input type="hidden" id="hitch_ids" required name="hitch_ids" value="[]">
										</div>
										<div class="field">
											<label class="req" for="photo_rear">Towing Vehicle Rear
												Photograph</label>
											<input id="photo_rear" name="photo_rear" required type="file" accept="image/*" />
											<ul class="uploads" id="list_rear"></ul>
											<input type="hidden" id="rear_ids" required name="rear_ids"  value="[]">
										</div>
										<div class="field">
											<label class="req" for="photo_front">Front of Caravan Photograph</label>
											<input id="photo_front" name="photo_front" required type="file" accept="image/*" />
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
										foreach($sts_var_section_head_notices as $sts_key => $notice) {
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
									<?php
									if (
										($sts_var_cptr_mrmnts_video_type === 'embed' && !empty($sts_var_cptr_mrmnts_video_embed)) ||
										($sts_var_cptr_mrmnts_video_type === 'upload' && !empty($sts_var_cptr_mrmnts_video_upload))
									) { ?>
										<div class="video-capture-measurement">
											<?php if ($sts_var_cptr_mrmnts_video_type === 'embed') { ?>
												<a class="popup-video button" data-lity="true" href="<?php echo $sts_var_cptr_mrmnts_video_embed; ?>">
													<?php echo html_entity_decode($sts_var_cptr_mrmnts_video_btn_text); ?>
												</a>
											<?php } elseif ($sts_var_cptr_mrmnts_video_type === 'upload') { ?>
												<a class="popup-video button" data-lity="true" href="<?php echo $sts_var_cptr_mrmnts_video_upload; ?>">
													<?php echo html_entity_decode($sts_var_cptr_mrmnts_video_btn_text); ?>
												</a>
											<?php } ?>
										</div>
									<?php } ?>
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
									<div class="grid cols-2 final-measurements-fields">
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
											<select id="barwidth" name="barwidth_mm">
												<option value="1700">1700</option>
												<option value="1800">1800</option>
												<option value="1850">1850</option>
												<option value="1900">1900</option>
												<option value="1960">1960</option>
												<option value="2000">2000</option>
												<option value="2050">2050</option>
												<option value="2100">2100</option>
												<option value="2150">2150</option>
												<option value="2200">2200</option>
												<option value="2250">2250</option>
												<option value="2300">2300</option>
												<option value="2350">2350</option>
												<option value="2400">2400</option>
											</select>
											<?php if ( $sts_var_notice_towing_vehicle_barwidth_mm ) { ?>
												<div class="note notice-bar"><?php echo html_entity_decode($sts_var_notice_towing_vehicle_barwidth_mm); ?></div>
											<?php } ?>
										</div>
										<div class="field" >
											<label class="req" for="vanwidth">Caravan Width (mm) measured under the A-Frame</label>
											<input id="vanwidth" name="caravan_width_mm" type="text" inputmode="numeric" pattern="[0-9]*"
												placeholder="e.g. 1900" required />
											<?php if ( $sts_var_notice_caravan_width_mm ) { ?>
												<div class="note notice-bar"><?php echo html_entity_decode($sts_var_notice_caravan_width_mm); ?></div>
											<?php } ?>
										</div>
										<div class="field">
											<label class="req" for="a_frame_length">A-Frame Length (mm)</label>
											<input id="a_frame_length" name="a_frame_length_mm" type="text" inputmode="numeric" pattern="[0-9]*"
												placeholder="e.g. 1800" required />
											<?php if ( $sts_var_a_frame_length_mm ) { ?>
												<div class="note notice-bar"><?php echo html_entity_decode($sts_var_a_frame_length_mm); ?></div>
											<?php } ?>
										</div>
										<div class="field">
											<input id="a_frame_tick" name="a_frame_tick" type="checkbox" required />
											<label class="req a_frame_tick_label" for="a_frame_tick">I confirm I have measured tight under the A-frame *</label>
											<div class="error-notes-checkbox" >
												This is a mandatory field - please select to confirm
											</div>
										</div>
										<fieldset class="ss-support-options" style="display:none">
											<div class="note notice-bar mt-0">As the A-Frame length is longer than 1800mm, we require a mid-fixing point for your Stone Stomper. Please select one of the following options. If you are unsure, please select "Support Pockets"</div>
											<div class="ginput_container ginput_container_checkbox extra-support">
												<!-- ToolBox -->
												<div class="gchoice stone-stomper-supports">
													<div class="checkbox-item">
														<input class="gfield-choice-input" name="input_1.3" type="radio" value="toolbox" id="toolbox">
														<label for="toolbox" id="label_4_1_2">Toolbox (if 1900 mm or wider)</label>
													</div>
													<div class="toolbox-support" style="display:none">
														<div class="factory_stoneguard_inner two-columns-fields">
															<div class="field extra-support" >
																<label class="req" for="toolbox_width">Width (mm)</label>
																<input id="toolbox_width" name="toolbox_width_mm" type="text" inputmode="numeric" pattern="[0-9]*"
																	placeholder="e.g. 1900mm" required />
															</div>
															<div class="field extra-support">
																<label class="req" for="toolbox_length">Distance from Van Crossbeam (mm)</label>
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
																	placeholder="e.g. 1900mm" required />
															</div>
															<div class="field extra-support">
																<label class="req" for="stoneguard_length">Distance from Van Crossbeam (mm)</label>
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
															<label class="req" for="support_pocket_length">Distance From Van Crossbeam (Attachment Point)</label>
															<input id="support_pocket_length" name="support_pocket_length_mm" type="text" inputmode="numeric" pattern="[0-9]*"
																placeholder="e.g.600 mm" required />
														</div>
													</div>
												</div>
											</div>
										</fieldset>
										<div class="field hidden">
											<label class="req" for="vinyl_width">Vinyl Insert Width</label>
											<input id="vinyl_width" name="vinyl_width_mm" type="text" inputmode="numeric" pattern="[0-9]*" placeholder="e.g.600 mm" required />
										</div>
										<div class="field hidden">
											<label class="req" for="vinyl_length">Vinyl Insert Length</label>
											<input id="vinyl_length" name="vinyl_length_mm" type="text" inputmode="numeric" pattern="[0-9]*" placeholder="e.g.600 mm" required />
										</div>
										<!--
											<label class="row">
												<input id="support_pockets" type="checkbox" name="support_pockets" />
													Support Pockets
											</label>
										-->
									</div>
									<?php
										if($sts_var_section_head_notices) {
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
						<input type="hidden" name="is_vehicle_make_other" id="is_vehicle_make_other" value="no">
						<input type="hidden" name="is_vehicle_model_other" id="is_vehicle_model_other" value="no">
						<input type="hidden" name="is_vehicle_year_other" id="is_vehicle_year_other" value="no">
						<input type="hidden" name="is_caravan_make_other" id="is_caravan_make_other" value="no">
						<input type="hidden" name="is_caravan_model_other" id="is_caravan_model_other" value="no">
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
	<div class="st-s200 mobile-image-hide"></div>
</section>
<?php get_footer(); ?>
