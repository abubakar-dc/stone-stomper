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
/**
 * Add WooCommerce-related columns to the custom Order CPT
 */
add_filter( 'manage_customer_posts_columns', function ( $columns ) {

	$new_columns = [];

	foreach ( $columns as $key => $label ) {
		$new_columns[ $key ] = $label;

		if ( 'title' === $key ) {

			// 👇 Add Order ID, WooCommerce Status, and Proposed Date after Title
			$new_columns['order_id']       = __( 'Order ID', 'textdomain' );
			$new_columns['order_status']   = __( 'Status', 'textdomain' );
			$new_columns['proposed_date']  = __( 'Proposed Date', 'textdomain' );
		}
	}

	return $new_columns;
} );

add_action( 'manage_customer_posts_custom_column', function ( $column, $post_id ) {

	// 🧠 Get linked WooCommerce Order ID from your ACF field
	$order_id = get_field( 'order_id', $post_id ); // update if your ACF key is different

	switch ( $column ) {

		// ✅ Order ID Column
		case 'order_id':
			if ( $order_id ) {
				echo '<a href="' . esc_url( admin_url( 'post.php?post=' . $order_id . '&action=edit' ) ) . '">#' . esc_html( $order_id ) . '</a>';
			} else {
				echo '<em style="color:#888;">—</em>';
			}
			break;

		// ✅ WooCommerce Order Status Column
		case 'order_status':
			if ( ! $order_id ) {
				echo '<em style="color:#888;">No linked WooCommerce order</em>';
				break;
			}

			$order = wc_get_order( $order_id );
			if ( ! $order ) {
				echo '<em style="color:#888;">Invalid Order #' . esc_html( $order_id ) . '</em>';
				break;
			}

			$current_status = $order->get_status();
			$statuses       = wc_get_order_statuses();

			echo '<select class="wc-order-status" data-order-id="' . esc_attr( $order_id ) . '">';
			foreach ( $statuses as $status_key => $status_label ) {
				$selected = selected( $current_status, str_replace( 'wc-', '', $status_key ), false );
				echo '<option value="' . esc_attr( $status_key ) . '" ' . $selected . '>' . esc_html( $status_label ) . '</option>';
			}
			echo '</select>';
			break;

		// ✅ Proposed Date Column
		case 'proposed_date':
			$proposed_date = get_field( 'sts_var_proposed_date_of_delivery', $post_id ); // update ACF key if needed
			if ( $proposed_date ) {
				echo esc_html( date_i18n( 'F j, Y', strtotime( $proposed_date ) ) );
			} else {
				echo '<em style="color:#888;">—</em>';
			}
			break;
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
			setTimeout(() => {
				select.css('background-color', '');

				// ✅ If current filter is a specific status, go to "All" view after reload
				let currentUrl = new URL(window.location.href);
				let currentStatus = currentUrl.searchParams.get('post_status');

				if (currentStatus && currentStatus !== 'all') {
					currentUrl.searchParams.set('post_status', 'all');
					window.location.href = currentUrl.toString(); // redirect to All tab
				} else {
					location.reload(); // otherwise just reload current view
				}

			}, 600);
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

/**
 * Add WooCommerce-like status filter tabs in the custom Customer Orders list
 */
/**
 * Add WooCommerce-like status filter tabs next to default "All | Mine | Published | Trash"
 */
add_filter( 'views_edit-customer', function ( $views ) {

	global $wpdb;

	// Fetch all customer CPT posts
	$customer_posts = get_posts( [
		'post_type'      => 'customer',
		'posts_per_page' => -1,
		'fields'         => 'ids',
	] );

	if ( empty( $customer_posts ) ) {
		return $views;
	}

	// Collect statuses from linked WooCommerce orders
	$status_counts = [];
	foreach ( $customer_posts as $post_id ) {
		$order_id = get_field( 'order_id', $post_id );
		if ( ! $order_id ) continue;

		$order = wc_get_order( $order_id );
		if ( ! $order ) continue;

		$status = $order->get_status();
		if ( ! isset( $status_counts[ $status ] ) ) {
			$status_counts[ $status ] = 0;
		}
		$status_counts[ $status ]++;
	}

	if ( empty( $status_counts ) ) {
		return $views;
	}

	$current_status = isset( $_GET['wc_status'] ) ? sanitize_text_field( $_GET['wc_status'] ) : '';

	foreach ( $status_counts as $status => $count ) {
		$label = wc_get_order_status_name( 'wc-' . $status );
		$url   = add_query_arg( 'wc_status', $status, remove_query_arg( 'paged' ) );
		$class = ( $current_status === $status ) ? 'class="current"' : '';
		$views[ 'wc_' . $status ] = sprintf(
			'<a href="%s" %s>%s <span class="count">(%d)</span></a>',
			esc_url( $url ),
			$class,
			esc_html( $label ),
			intval( $count )
		);
	}

	return $views;
} );

/**
 * Filter the CPT query by WooCommerce order status
 */
add_action( 'pre_get_posts', function ( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	// Only for your Orders (Customer CPT)
	if ( isset( $_GET['post_type'] ) && $_GET['post_type'] === 'customer' ) {

		// Default sort only if user hasn't chosen their own
		if ( empty( $_GET['orderby'] ) ) {
			$query->set( 'orderby', 'date' );
			$query->set( 'order', 'DESC' );
		}

		// Handle WC status filter if present
		if ( ! empty( $_GET['wc_status'] ) ) {
			$status_filter = sanitize_text_field( $_GET['wc_status'] );

			$matching_ids = [];
			$posts = get_posts( [
				'post_type'      => 'customer',
				'posts_per_page' => -1,
				'fields'         => 'ids',
			] );

			foreach ( $posts as $post_id ) {
				$order_id = get_field( 'order_id', $post_id );
				if ( ! $order_id ) continue;

				$order = wc_get_order( $order_id );
				if ( $order && $order->get_status() === $status_filter ) {
					$matching_ids[] = $post_id;
				}
			}

			$query->set( 'post__in', $matching_ids ?: [ 0 ] );

			// Restore ordering so post__in doesn’t break sorting
			if ( isset( $_GET['orderby'] ) && $_GET['orderby'] === 'title' ) {
				$query->set( 'orderby', 'title' );
				$query->set( 'order', strtoupper( $_GET['order'] ?? 'ASC' ) );
			} else {
				$query->set( 'orderby', 'date' );
				$query->set( 'order', 'DESC' );
			}
		}
	}
});






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
