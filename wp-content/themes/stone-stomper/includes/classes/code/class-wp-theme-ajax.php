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

	}

public function woocommerce_ajax_add_to_cart() {
    $product_id = intval($_POST['product_id']);
    $quantity   = intval($_POST['quantity']);
	$shipping   = sanitize_text_field($_POST['shipping']);

    if ($product_id > 0 && $quantity > 0) {
		  $measurements = [
            'barwidth_mm'              => sanitize_text_field($_POST['barwidth_mm'] ?? ''),
            'caravan_width_mm'         => sanitize_text_field($_POST['caravan_width_mm'] ?? ''),
            'caravan_clearance_gap_mm' => sanitize_text_field($_POST['caravan_clearance_gap_mm'] ?? ''),
            'vinyl_inserts'            => sanitize_text_field($_POST['vinyl_inserts'] ?? ''),
        ];
        $added = WC()->cart->add_to_cart($product_id, $quantity, 0, [], $measurements);
		if ($added) {
			WC()->session->set('chosen_shipping_method', $shipping);
		}

      wp_send_json(
			array(
				'added'  => $shipping,
				'post'  => $_POST,
			)
			);
    wp_die(); // Always end AJAX functions with wp_die()
}
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
