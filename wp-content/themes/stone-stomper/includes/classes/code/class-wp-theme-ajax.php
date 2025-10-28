<?php
/**
 * Ajax related functions
 *
 * @link https://codex.wordpress.org/AJAX#Ajax_in_WordPress
 *
 * @package Stone Stomper
 * @since 1.0.0
 */

namespace StoneStomper\Ajax;

/**
 * Template Class For Ajax
 *
 * Template Class
 *
 * @category Setting_Class
 * @package  Stone Stomper
 */
class WP_Theme_Ajax {
	/**
	 * Define class Constructor
	 **/
	public function __construct() {

		add_action( 'wp_ajax_nopriv_fetch_form_data', array( $this, 'fetch_form_data' ) );
		add_action( 'wp_ajax_fetch_form_data', array( $this, 'fetch_form_data' ) );
		add_action( 'wp_ajax_nopriv_fetch_caravan_data', array( $this, 'fetch_caravan_data' ) );
		add_action( 'wp_ajax_fetch_caravan_data', array( $this, 'fetch_caravan_data' ) );
		add_action( 'wp_ajax_nopriv_woocommerce_ajax_add_to_cart', array( $this, 'woocommerce_ajax_add_to_cart' ) );
		add_action( 'wp_ajax_woocommerce_ajax_add_to_cart', array( $this, 'woocommerce_ajax_add_to_cart' ) );
		add_action( 'wp_ajax_nopriv_bst_handle_upload_order_photos', array( $this, 'bst_handle_upload_order_photos' ) );
		add_action( 'wp_ajax_bst_handle_upload_order_photos', array( $this, 'bst_handle_upload_order_photos' ) );
		add_action( 'wp_ajax_nopriv_save_order_form_cookie', array( $this, 'save_order_form_cookie' ) );
		add_action( 'wp_ajax_save_order_form_cookie', array( $this, 'save_order_form_cookie' ) );
		add_action( 'wp_ajax_nopriv_mytheme_add_upsell_products', array( $this, 'mytheme_add_upsell_products' ) );
		add_action( 'wp_ajax_mytheme_add_upsell_products', array( $this, 'mytheme_add_upsell_products' ) );


		add_action( 'wp_ajax_nopriv_woocommerce_ajax_update_summary', array( $this, 'woocommerce_ajax_update_summary' ) );
		add_action( 'wp_ajax_woocommerce_ajax_update_summary', array( $this, 'woocommerce_ajax_update_summary' ) );

	}
	public function  mytheme_add_upsell_products() {
		if ( empty( $_POST['main_id'] ) ) {
			wp_send_json_error( [ 'message' => 'Missing main product ID' ] );
		}

		$main_id    = absint( $_POST['main_id'] );
		$upsell_ids = ! empty( $_POST['upsells'] ) ? array_map( 'absint', $_POST['upsells'] ) : [];

		// Add main product
		WC()->cart->add_to_cart( $main_id );

		// Add upsells
		foreach ( $upsell_ids as $upsell_id ) {
			WC()->cart->add_to_cart( $upsell_id );
		}
		wp_send_json_success([
            'added'    => true,
            'redirect' => '/cart',
        ]);


		wp_die();
	}


















	public function save_order_form_cookie() {
		error_log('save_order_form_cookie called');
		if ( isset( $_POST['formData'] ) ) {
			$form_data = wp_unslash( $_POST['formData'] );
			setcookie( 'orderFormData', $form_data, time() + ( 30 * 24 * 60 * 60 ), COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true );
			wp_send_json_success( array( 'message' => 'Form data saved in cookie.' ) );
		} else {
			wp_send_json_error( array( 'message' => 'No form data received.' ) );
		}
		wp_die();
	}


public function bst_handle_upload_order_photos() {
	$slots  = array( 'hitch', 'rear', 'front' );
	$result = array( 'hitch' => array(), 'rear' => array(), 'front' => array() );

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	// 🔹 Backup original upload_dir before overriding
	$original_upload_dir = wp_upload_dir();

	// Define custom filter function
	$custom_upload_dir = function( $uploads ) {
		$subdir             = '/order-form/' . date( 'Y/m' );
		$uploads['subdir']  = $subdir;
		$uploads['path']    = $uploads['basedir'] . $subdir;
		$uploads['url']     = $uploads['baseurl'] . $subdir;
		return $uploads;
	};

	// Apply temporary upload dir change
	add_filter( 'upload_dir', $custom_upload_dir );

	$overrides = array(
		'test_form' => false,
		'mimes'     => array(
			'jpg|jpeg' => 'image/jpeg',
			'png'      => 'image/png',
			'gif'      => 'image/gif',
			'webp'     => 'image/webp',
			'heic'     => 'image/heic',
		),
	);

	foreach ( $slots as $slot ) {
		if ( empty( $_FILES[ $slot ] ) ) {
			continue;
		}

		$files = self::bst_reformat_files_array( $_FILES[ $slot ] );
		if ( empty( $files ) ) {
			continue;
		}

		foreach ( $files as $file ) {
			$uploaded = wp_handle_upload( $file, $overrides );

			if ( isset( $uploaded['error'] ) ) {
				continue;
			}

			// Generate result with direct URL (no Media Library entry)
			$result[ $slot ][] = array(
				'url'  => $uploaded['url'],
				'name' => basename( $uploaded['file'] ),
			);
		}

	}

	// 🔹 Remove filter after upload
	remove_filter( 'upload_dir', $custom_upload_dir );

	wp_send_json_success( $result );
}


	/**
	 * Define ajax filter
	 **/
	public function  bst_reformat_files_array( $file_post ) {
		$files = array();

		// Multiple files
		if ( is_array( $file_post['name'] ?? null ) ) {
			$count = count( $file_post['name'] );
			for ( $i = 0; $i < $count; $i++ ) {
				if ( empty( $file_post['name'][ $i ] ) ) {
					continue;
				}
				$files[] = array(
					'name'     => $file_post['name'][ $i ],
					'type'     => $file_post['type'][ $i ],
					'tmp_name' => $file_post['tmp_name'][ $i ],
					'error'    => $file_post['error'][ $i ],
					'size'     => $file_post['size'][ $i ],
				);
			}
		} else { // Single file
			if ( ! empty( $file_post['name'] ?? '' ) ) {
				$files[] = $file_post;
			}
		}

		return $files;
	}





	/**
	 * Define ajax filter
	 **/
	public function woocommerce_ajax_add_to_cart() {
		$product_ids  = $_POST['ids'] ?? [];
		$quantity = 1;
		$shipping = sanitize_text_field($_POST['shipping'] ?? '');

		$added_any = false;

		foreach ($product_ids as $product_id) {
			if ($product_id > 0) {
				$added = WC()->cart->add_to_cart($product_id, $quantity);
				if ($added) {
					$added_any = true;
				}
			}
		}

		if ($added_any) {
			WC()->session->set('chosen_shipping_methods', [$shipping]);
			wp_send_json_success([
				'added'    => true,
				'redirect' => '/cart',
			]);
		} else {
			wp_send_json_error(['message' => 'Failed to add products to cart']);
		}

		wp_die();
	}
	/**
	 * Stone Stomper Update Summary
	 **/

	public function woocommerce_ajax_update_summary() {
		$product_ids  = $_POST['ids'] ?? [];
		$quantity = 1;
		$shipping = sanitize_text_field($_POST['shipping'] ?? '');

		$added_any = false;

		$total_price = 0;
		$html_output = '';

		foreach ($product_ids as $product_id) {
			if ($product_id > 0) {
				$product = wc_get_product($product_id);
				if ($product) {
					$added_any = true;
					$title = $product->get_name();
					$price = $product->get_price();

					$total_price += $price;

					$html_output .= '<div class="line">';
					$html_output .= '<span>' . esc_html($title) . '</span>';
					$html_output .= '<strong tabindex="0">$<span>' . number_format($price, 2) . '</span></strong>';
					$html_output .= '</div>';
				}
			}
		}
		$shipping = 75.00;
		$total_price += $shipping;

		$html_output .= '<div class="line"><span>Shipping</span><strong tabindex="0">$<span data-id="shipping">' . number_format($shipping, 2) . '</span></strong></div>';

		// Optionally, show total as well
		$html_output .= '<div class="line total"><span>Total</span><strong tabindex="0">$<span data-id="total">' . number_format($total_price, 2) . '</span></strong></div>';


		if ($added_any) {
			WC()->session->set('chosen_shipping_methods', [$shipping]);
			wp_send_json_success([
				'html'   => $html_output,
				'added'    => true,
			]);
		} else {
			wp_send_json_success([
				'html'   => $html_output,
				'added'    => false,
			]);

		}

		wp_die();
	}


	/**
	 * Define ajax filter
	 **/
	public function fetch_form_data() {

		$post_id = $_POST['postID'] ?? null;

		//Getting Models

		if(isset($_POST['carMake'])) {
			$carMake = $_POST['carMake'] ?? null;
			$html    = '<option>Select Model Make</option>';
			$posts   = array();
			$carMake_terms = get_term_by( 'slug', $carMake, 'car-category' );
			if ( $carMake_terms && ! is_wp_error( $carMake_terms ) ) {
			$child_terms = get_terms( array(
				'taxonomy'   => 'car-category',
				'parent'     => $carMake_terms->term_id,
				'hide_empty' => false,
			) );

			if ( ! empty( $child_terms ) && ! is_wp_error( $child_terms ) ) {
				foreach ( $child_terms as $child ) {
					// Append term to dropdown

					// Fetch posts for this child term
					$query = new \WP_Query( array(
						'post_type'      => 'car', // 🔹 change to your custom post type if needed
						'posts_per_page' => -1,
						'tax_query'      => array(
							array(
								'taxonomy' => 'car-category',
								'field'    => 'term_id',
								'terms'    => $child->term_id,
							),
						),
					) );

					if ( $query->have_posts() ) {
						while ( $query->have_posts() ) {
							$query->the_post();

							$html .= '<option class="ajax-car-model" data-post-id="'.get_the_ID().'" value="' . esc_attr( $child->slug ) . '">' . esc_html( $child->name ) . ' - ' . get_the_title() . '</option>';
						}
						wp_reset_postdata();
					}
				}
			}
			}
		}
		//Getting Year
		if($post_id){
			$sts_var_car_year = get_field('sts_var_car_year', $post_id);
			$year    = '<option>Select Model Year</option>';
			$year .= '<option value="'.esc_attr($sts_var_car_year).'">'.esc_html($sts_var_car_year).'</option>';
		} else {
			$year    = '<option>Select Model Year</option>';
		}

		//Getting Vehicle Image
		if($post_id && has_post_thumbnail( $post_id )){
			$thumb_id   = get_post_thumbnail_id( $post_id );
			$caption    = wp_get_attachment_caption( $thumb_id );

			$vehicleImage = '<img src="'.get_the_post_thumbnail_url( $post_id, 'thumb_1000' ).'" alt="'.get_the_title($post_id).'" />';
			if ( $caption ) {
				$vehicleImage .= '
					<div class="image-caption-area">
						<div class="image-caption">
							<p>' . esc_html( $caption ) . '</p>
						</div>
					</div>';
				}
			}

		//Getting Bar Width
		if($post_id){
			$barwidth = get_field('sts_var_car_barwidth', $post_id);
		} else {
			$barwidth = '';
		}

		wp_send_json(
		array(
			'args'  => $child_terms,
			'models'  => $html,
			'vehicleImage'  => $vehicleImage,
			'year'  => $year,
			'barwidth'  => $barwidth,
			'posts' => $posts,
			'post'  => $_POST,
		)
		);
		wp_die();
	}


public function fetch_caravan_data() {

		$caravanMake = $_POST['caravanMake'] ?? null;
		$caravanPostID = $_POST['caravanPostID'] ?? null;

		$html    = '<option>Select Caravan Model</option>';

		$query = new \WP_Query( array(
						'post_type'      => 'caravan', // 🔹 change to your custom post type if needed
						'posts_per_page' => -1,
						'tax_query'      => array(
							array(
								'taxonomy' => 'caravan-category',
								'field'    => 'slug',
								'terms'    => $caravanMake,
							),
						),
					) );

					if ( $query->have_posts() ) {
						while ( $query->have_posts() ) {
							$query->the_post();

							$html .= '<option class="ajax-caravan-model" data-post-id="'.get_the_ID().'">' . esc_html( get_the_title() ) . '</option>';
						}
						wp_reset_postdata();
					}
		if($caravanPostID){
		$sts_var_caravan_ss_mesurements = get_field('sts_var_caravan_ss_mesurements', $caravanPostID);
		if($sts_var_caravan_ss_mesurements){
			$sts_var_caravan_barwidth = $sts_var_caravan_ss_mesurements['width'] ?? '';
			$sts_var_caravan_barheight = $sts_var_caravan_ss_mesurements['height'] ?? '';
		} else {
			$sts_var_caravan_barwidth = '';
			$sts_var_caravan_barheight = '';
		}

		$sts_var_caravan_factory = get_field('sts_var_caravan_factory', $caravanPostID);
		if($sts_var_caravan_factory){
			$sts_var_caravan_factory_width = $sts_var_caravan_factory['width'] ?? '';
			$sts_var_caravan_factory_height = $sts_var_caravan_factory['height'] ?? '';
		} else {
			$sts_var_caravan_factory_width = '';
			$sts_var_caravan_factory_height = '';
		}
		$sts_var_caravan_vinyl_insert = get_field('sts_var_caravan_vinyl_insert', $caravanPostID);
		if($sts_var_caravan_vinyl_insert ){
			$sts_var_caravan_vinyl_insert_width = $sts_var_caravan_vinyl_insert['width'] ?? '';
			$sts_var_caravan_vinyl_insert_height = $sts_var_caravan_vinyl_insert['height'] ?? '';
		} else {
			$sts_var_caravan_vinyl_insert_width = '';
			$sts_var_caravan_vinyl_insert_height = '';
		}
		$sts_var_caravan_toolbox = get_field('sts_var_caravan_toolbox', $caravanPostID);
		if($sts_var_caravan_toolbox ){
			$sts_var_caravan_toolbox_width = $sts_var_caravan_toolbox['width'] ?? '';
			$sts_var_caravan_toolbox_height = $sts_var_caravan_toolbox['height'] ?? '';
		} else {
			$sts_var_caravan_toolbox_width = '';
			$sts_var_caravan_toolbox_height = '';
		}




		$sts_var_caravan_images = get_field('sts_var_caravan_images', $caravanPostID);
		if($sts_var_caravan_images){
			$sts_var_factory_stoneguard_image_id = $sts_var_caravan_images['carvan'] ?? '';
			$sts_var_carvan_image_caption = $sts_var_caravan_images['image_caption'] ?? '';
		} else {
			$sts_var_factory_stoneguard_image_id = '';
			$sts_var_carvan_image_caption = '';
		}


		} else {
			$sts_var_caravan_barwidth = '';
			$sts_var_caravan_barheight = '';
			$sts_var_caravan_factory_width = '';
			$sts_var_caravan_factory_height = '';
			$sts_var_caravan_vinyl_insert_width = '';
			$sts_var_caravan_vinyl_insert_height = '';
			$sts_var_toolbox_image_id = '';
		}

		if($sts_var_factory_stoneguard_image_id){
			$stoneguard_image = '<img src="'.wp_get_attachment_url($sts_var_factory_stoneguard_image_id).'" alt="'.get_the_title($caravanPostID).'" />';
			if($sts_var_carvan_image_caption){
				$stoneguard_image .= '<div class="image-caption-area">
										<div class="image-caption">
											<p>'.esc_html($sts_var_carvan_image_caption).'</p>
										</div>
									</div>';
			}
		} else {
			$stoneguard_image = '';
		}



		wp_send_json(
			array(
				'html'  => $html,
				'barwidth'  => $sts_var_caravan_barwidth,
				'barheight'  => $sts_var_caravan_barheight,
				'stoneguard_width'  => $sts_var_caravan_factory_width,
				'stoneguard_height'  => $sts_var_caravan_factory_height,
				'toolbox_width'  => $sts_var_caravan_toolbox_width,
				'toolbox_height'  => $sts_var_caravan_toolbox_height,
				'vinyl_insert_width'  => $sts_var_caravan_vinyl_insert_width,
				'vinyl_insert_height'  => $sts_var_caravan_vinyl_insert_height,
				'stoneguard_image'  => $stoneguard_image,
				'toolbox_image'  => $toolbox_image,
				'post'  => $_POST,
			)
		);

		wp_die();
		}


}
new WP_Theme_Ajax();
