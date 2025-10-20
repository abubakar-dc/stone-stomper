<?php
/**
 * Functions for custom post types
 *
 * @link https://developer.wordpress.org/themes/basics/post-types/
 *
 * @package Stone Stomper
 * @since 1.0.0
 */

use StoneStomper\CPT\WP_Theme_CPT;

new WP_Theme_CPT(
	array(
		'labels'       => array(
			'singular_capital'   => 'Testimonial',
			'plural_capital'     => 'Testimonials',
			'singular_lowercase' => 'testimonial',
			'plural_lowercase'   => 'testimonials',
			// CPT Slug & Name.
			'register_key'       => 'testimonial',
			'slug'               => 'testimonial',
		),
		'supports'     => array( 'title','author' ),
		'menu_icon'    => 'dashicons-columns',
		'public'       => false,
		'show_in_menu' => true,
		'show_ui'      => true,
	)
);
new WP_Theme_CPT(
	array(
		'labels'       => array(
			'singular_capital'   => 'Caravan',
			'plural_capital'     => 'Caravans',
			'singular_lowercase' => 'caravan',
			'plural_lowercase'   => 'caravans',
			// CPT Slug & Name.
			'register_key'       => 'caravan',
			'slug'               => 'caravan',
		),
		'supports'     => array( 'title', 'thumbnail', 'author' ),
		'menu_icon'    => 'dashicons-columns',
		'public'       => false,
		'show_in_menu' => true,
		'show_ui'      => true,
		'taxonomies' => array(
			array(
				'slug'          => 'caravan-category',
				'register_key'  => 'caravan-category', // if not given default is slug value.
				'name'          => 'Category',
				'singular_name' => 'Category',
				'plural_name'   => 'Categories',
			)
		 ),
	)
);
new WP_Theme_CPT(
	array(
		'labels'    => array(
			'singular_capital'   => 'Car',
			'plural_capital'     => 'Cars',
			'singular_lowercase' => 'car',
			'plural_lowercase'   => 'cars',
			// CPT Slug & Name.
			'register_key'       => 'car',
			'slug'               => 'car',
		),
		'supports'  => array( 'title', 'thumbnail', 'author' ),
		'menu_icon' => 'dashicons-car',
		'public'    => false,
		'taxonomies' => array(
			array(
				'slug'          => 'car-category',
				'register_key'  => 'car-category', // if not given default is slug value.
				'name'          => 'Category',
				'singular_name' => 'Category',
				'plural_name'   => 'Categories',
			)
		 ),
	)
);
new WP_Theme_CPT(
	array(
		'labels'    => array(
			'singular_capital'   => 'Order',
			'plural_capital'     => 'Orders',
			'singular_lowercase' => 'order',
			'plural_lowercase'   => 'orders',
			'register_key'       => 'customer',
			'slug'               => 'order',
		),
		'supports'  => array( 'title', 'thumbnail' ),
		'menu_icon' => 'dashicons-groups',
		'public'    => false,
	)
);

/**
 * Add a WooCommerce Status column to the custom Order CPT
 */
add_filter( 'manage_customer_posts_columns', function ( $columns ) {
	$new_columns = [];

	foreach ( $columns as $key => $label ) {
		$new_columns[ $key ] = $label;
		if ( 'title' === $key ) {
			$new_columns['order_status'] = __( 'WooCommerce Status', 'textdomain' );
		}
	}

	return $new_columns;
} );

add_action( 'manage_customer_posts_custom_column', function ( $column, $post_id ) {
	if ( 'order_status' === $column ) {

		// 🧠 Get the related WooCommerce Order ID from your ACF field
		$order_id = get_field( 'order_id', $post_id ); // <-- change if your ACF key is different

		if ( ! $order_id ) {
			echo '<em style="color:#888;">No linked WooCommerce order</em>';
			return;
		}

		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			echo '<em style="color:#888;">Invalid Order #' . esc_html( $order_id ) . '</em>';
			return;
		}

		$current_status = $order->get_status();
		$statuses       = wc_get_order_statuses();

		echo '<select class="wc-order-status" data-order-id="' . esc_attr( $order_id ) . '">';
		foreach ( $statuses as $status_key => $status_label ) {
			$selected = selected( $current_status, str_replace( 'wc-', '', $status_key ), false );
			echo '<option value="' . esc_attr( $status_key ) . '" ' . $selected . '>' . esc_html( $status_label ) . '</option>';
		}
		echo '</select>';
	}
}, 10, 2 );

/**
 * AJAX handler to update WooCommerce order status
 */
add_action( 'wp_ajax_update_wc_order_status', function () {
	if ( ! current_user_can( 'edit_shop_orders' ) ) {
		wp_send_json_error( 'Permission denied' );
	}

	$order_id = intval( $_POST['order_id'] ?? 0 );
	$status   = sanitize_text_field( $_POST['status'] ?? '' );

	if ( ! $order_id || ! $status ) {
		wp_send_json_error( 'Invalid data' );
	}

	$order = wc_get_order( $order_id );
	if ( ! $order ) {
		wp_send_json_error( 'Order not found' );
	}

	$order->update_status( str_replace( 'wc-', '', $status ) );
	wp_send_json_success( 'Status updated to ' . $status );
} );

/**
 * JS to handle inline status change
 */
add_action( 'admin_footer-edit.php', function () {
	$screen = get_current_screen();
	if ( 'edit-customer' !== $screen->id ) {
		return;
	}
	?>
	<script type="text/javascript">
	jQuery(document).on('change', '.wc-order-status', function () {
		let select = jQuery(this);
		let orderId = select.data('order-id');
		let status = select.val();

		select.css('opacity', '0.5');

		jQuery.post(ajaxurl, {
			action: 'update_wc_order_status',
			order_id: orderId,
			status: status
		}, function (response) {
			select.css('opacity', '1');
			if (response.success) {
				select.css('background-color', '#c6efce');
				setTimeout(() => select.css('background-color', ''), 1000);
			} else {
				alert('Error: ' + response.data);
				select.css('background-color', '#ffc7ce');
				setTimeout(() => select.css('background-color', ''), 1000);
			}
		});
	});
	</script>
	<?php
} );

new WP_Theme_CPT(
	array(
		'labels'    => array(
			'singular_capital'   => 'Tow Hitch',
			'plural_capital'     => 'Tow Hitches',
			'singular_lowercase' => 'Tow hitch',
			'plural_lowercase'   => 'Tow hitches',
			// CPT Slug & Name.
			'register_key'       => 'tow-hitch',
			'slug'               => 'tow-hitch',
		),
		'supports'  => array( 'title', 'thumbnail'   ),
		'menu_icon' => 'dashicons-admin-links',
		'public'    => false,
	)
);
