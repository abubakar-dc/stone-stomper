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

	}


	public function bst_handle_upload_order_photos() {
	// Nonce check

	$slots  = array( 'hitch', 'rear', 'front' ); // MUST match your JS "slot" keys
	$result = array( 'hitch' => array(), 'rear' => array(), 'front' => array() );

	// WordPress upload helpers
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';

	// Optional: size/type limits
	$overrides = array(
		'test_form' => false,
		'mimes'     => array(
			'jpg|jpeg' => 'image/jpeg',
			'png'      => 'image/png',
			'gif'      => 'image/gif',
			'webp'     => 'image/webp',
			'heic'     => 'image/heic',
		),
		// 'unique_filename_callback' => 'your_custom_filename_cb', // optional
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
			// Optional: block too-large files (e.g., > 15MB)
			// if ( (int) $file['size'] > 15 * 1024 * 1024 ) { continue; }

			$uploaded = wp_handle_upload( $file, $overrides );
			if ( isset( $uploaded['error'] ) ) {
				// You can collect per-file errors if desired
				continue;
			}

			$attachment = array(
				'post_mime_type' => $uploaded['type'],
				'post_title'     => sanitize_file_name( wp_basename( $uploaded['file'] ) ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			);

			$attach_id = wp_insert_attachment( $attachment, $uploaded['file'] );
			if ( is_wp_error( $attach_id ) ) {
				continue;
			}

			$attach_data = wp_generate_attachment_metadata( $attach_id, $uploaded['file'] );
			wp_update_attachment_metadata( $attach_id, $attach_data );

			$result[ $slot ][] = array(
				'id'  => $attach_id,
				'url' => wp_get_attachment_url( $attach_id ),
			);
		}
	}

	wp_send_json_success( $result );
}
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
public function woocommerce_ajax_add_to_cart() {
	$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
	$quantity   = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
	$shipping   = isset($_POST['shipping']) ? $_POST['shipping'] : '';

	if ($product_id <= 0 || $quantity <= 0) {
		wp_send_json_error(['message' => 'Invalid product or quantity']);
	}

	$measurements = [
		'barwidth_mm'              => sanitize_text_field($_POST['barwidth_mm'] ?? ''),
		'caravan_width_mm'         => sanitize_text_field($_POST['caravan_width_mm'] ?? ''),
		'caravan_clearance_gap_mm' => sanitize_text_field($_POST['caravan_clearance_gap_mm'] ?? ''),
		'vinyl_inserts'            => sanitize_text_field($_POST['vinyl_inserts'] ?? ''),
	];

	$parse_ids = static function($raw) {
		if (is_array($raw)) {
			return array_values(array_filter(array_map('intval', $raw)));
		}
		if (is_string($raw)) {
			$raw = trim($raw);
			if ($raw === '') return [];
			if (strpos($raw, '[') === 0) {
				$decoded = json_decode($raw, true);
				if (is_array($decoded)) {
					return array_values(array_filter(array_map('intval', $decoded)));
				}
			}
			$parts = preg_split('/[\s,|]+/', $raw);
			return array_values(array_filter(array_map('intval', $parts)));
		}
		return [];
	};

	$attachments = [
		'hitch_attachment_ids' => $parse_ids($_POST['hitch_attachment_ids'] ?? []),
		'rear_attachment_ids'  => $parse_ids($_POST['rear_attachment_ids'] ?? []),
		'front_attachment_ids' => $parse_ids($_POST['front_attachment_ids'] ?? []),
	];

	$cart_item_data = array_merge($measurements, $attachments);

	$added = WC()->cart->add_to_cart($product_id, $quantity, 0, [], $cart_item_data);

	if ($added) {
		WC()->session->set('chosen_shipping_methods', [ $shipping ]);
		// Correct Woo key expects an array
	}

	wp_send_json_success([
		'added'      => (bool) $added,
		'redirect'   => '/cart',
	]);
	wp_die();
}


	/**
	 * Define ajax filter
	 **/
	public function fetch_form_data() {


		$post_id = $_POST['postID'] ?? null;
		$sts_var_car_year = get_field('sts_var_car_year', $post_id);
		$barwidth = get_field('sts_var_car_barwidth', $post_id);
		$year    = '<option>Select Model Year</option>';
		$year .= '<option value="'.esc_attr($sts_var_car_year).'">'.esc_html($sts_var_car_year).'</option>';
		if(isset($_POST['carMake'])){
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
			wp_send_json(
			array(
				'args'  => $child_terms,
				'models'  => $html,
				'year'  => $year,
				'barwidth'  => $barwidth,
				'posts' => $posts,
				'post'  => $_POST,
			)
			);
	}
		wp_die();
	}


		public function fetch_caravan_data() {

		$caravanMake = $_POST['caravanMake'] ?? null;
		$caravanPostID = $_POST['caravanPostID'] ?? null;

		$sts_var_caravan_barwidth = get_field('sts_var_caravan_barwidth', $caravanPostID);
		$sts_var_caravan_vinyl_insert = get_field('sts_var_caravan_vinyl_insert', $caravanPostID);

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


		wp_send_json(
			array(
				'html'  => $html,
				'caravanbarwidth'  => $sts_var_caravan_barwidth,
				'vinylinsert'  => $sts_var_caravan_vinyl_insert,
				'post'  => $_POST,
			)
		);

		wp_die();
		}


}
new WP_Theme_Ajax();
