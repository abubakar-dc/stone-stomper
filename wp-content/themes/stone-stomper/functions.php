<?php
/**
 * Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * Please note that missing files will produce a fatal error.
 *
 * @package Stone Stomper
 * @since 1.0.0
 */

if ( ! defined( 'BASETHEME_BLOCK_DIR' ) ) {
	define( 'BASETHEME_BLOCK_DIR', __DIR__ . '/blocks' );
}


if ( ! defined( 'BASETHEME_DEFAULT_IMAGE' ) ) {
	define( 'BASETHEME_DEFAULT_IMAGE', esc_url( get_template_directory_uri() ) . '/assets/build/images/admin/defaults/default-image.webp' );
}


$sts_folder_includes = sts_includes( __DIR__ . '/includes/classes' );
/**
 * Checks if any file have error while including it.
 */
foreach ( $sts_folder_includes as $sts_folders ) {
	foreach ( $sts_folders as $sts_file ) {
		$sts_filepath = locate_template( str_replace( __DIR__ . '/', '', $sts_file ) );
		if ( file_exists( $sts_filepath ) ) {
			require_once $sts_filepath;
		} else {
			echo 'Unable to load configuration file ' . esc_html( basename( $sts_file ) ) . ' please check file name in functions.php in your current active theme.';
		}
	}
}
/**
 * Get folder Dir
 *
 * @param string $directory Folder dir path.
 */
function sts_includes( $directory ) {
	$folders = array();

	// Get all files and folders in the specified directory.
	$items = scandir( $directory );

	// Iterate through each item.
	foreach ( $items as $item ) {
		$full_path = $directory . '/' . $item;

		// Check if the item is a directory and not '.' or '..'.
		if ( is_dir( $full_path ) && '.' !== $item && '..' != $item ) {
			$folders[ $item ] = glob( __DIR__ . '/includes/classes/' . $item . '/*.php' );
		}
	}
	$folders['other'] = array(
		__DIR__ . '/includes/cpt.php',
		__DIR__ . '/includes/project.php',
	);

	return $folders;
}
add_action('woocommerce_before_calculate_totals', function($cart) {
    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    $chosen = WC()->session->get('chosen_shipping_method');

    if ($chosen) {
        // WooCommerce expects an array of chosen shipping methods
        WC()->session->set('chosen_shipping_methods', [$chosen]);
    }
});
// Show details in Cart/Checkout line item
add_filter('woocommerce_get_item_data', function($item_data, $cart_item) {
    // Simple fields (text)
    $fields = [
        'barwidth_mm'              => 'Towing Vehicle Barwidth',
        'caravan_width_mm'         => 'Caravan Width',
        'caravan_clearance_gap_mm' => 'Caravan Clearance Gap',
        'vinyl_inserts'            => 'Vinyl Inserts',
        'support_pockets'          => 'Support Pockets',
    ];

    foreach ($fields as $key => $label) {
        if (!empty($cart_item[$key])) {
            $val = $cart_item[$key];
            if ($key === 'support_pockets') {
                $val = ($val === '1' || $val === 1 || $val === 'yes' || $val === 'on') ? 'Yes' : 'No';
            }
            $item_data[] = [
                'key'     => $label,
                'value'   => is_array($val) ? implode(', ', array_map('wc_clean', array_map('strval', $val))) : wc_clean($val),
                'display' => is_array($val) ? implode(', ', array_map('wc_clean', array_map('strval', $val))) : wc_clean($val),
            ];
        }
    }

    // Helper: parse JSON/CSV into clean int IDs
    $parse_ids = static function($raw) {
        if (empty($raw)) return [];
        if (is_string($raw) && strpos(trim($raw), '[') === 0) {
            $raw = json_decode($raw, true);
        }
        if (is_string($raw)) {
            $raw = preg_split('/[\s,|]+/', $raw);
        }
        return array_values(array_filter(array_map('intval', (array) $raw)));
    };

    // Image attachments → render actual <img> tags
    $attachments = [
        'hitch_attachment_ids' => 'Hitch Photos',
        'rear_attachment_ids'  => 'Rear Photos',
        'front_attachment_ids' => 'Front Photos',
    ];

    foreach ($attachments as $key => $label) {
        if (!empty($cart_item[$key])) {
            $ids = $parse_ids($cart_item[$key]);
            if (!$ids) continue;

            $html = '<div class="cart-item-thumbs" style="display:flex;gap:6px;flex-wrap:wrap;">';
            foreach ($ids as $id) {
                $thumb = wp_get_attachment_image($id, [48, 48], true, [
                    'style' => 'width:48px;height:48px;object-fit:cover;border:1px solid #e5e7eb;border-radius:4px;display:block;'
                ]);
                $url = wp_get_attachment_url($id);
                if ($thumb && $url) {
                    $html .= '<a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer">' . $thumb . '</a>';
                } elseif ($thumb) {
                    $html .= $thumb;
                }
            }
            $html .= '</div>';

            // Use 'display' for HTML; keep 'value' as a safe fallback (IDs list)
            $item_data[] = [
                'key'     => $label,
                'value'   => implode(',', $ids),
                'display' => $html,
            ];
        }
    }

    return $item_data;
}, 10, 2);

add_filter('woocommerce_add_cart_item_data', function($cart_item_data, $product_id, $variation_id) {
    if (!empty($_POST['hitch_attachment_ids'])) {
        $cart_item_data['hitch_attachment_ids'] = sanitize_text_field($_POST['hitch_attachment_ids']);
    }
    if (!empty($_POST['rear_attachment_ids'])) {
        $cart_item_data['rear_attachment_ids'] = sanitize_text_field($_POST['rear_attachment_ids']);
    }
    if (!empty($_POST['front_attachment_ids'])) {
        $cart_item_data['front_attachment_ids'] = sanitize_text_field($_POST['front_attachment_ids']);
    }
    return $cart_item_data;
}, 10, 3);


// Persist to Order Items (admin)
add_action('woocommerce_checkout_create_order_line_item', function($item, $cart_item_key, $values, $order) {
    // Simple fields (incl. support_pockets)
    $fields = [
        'barwidth_mm'              => 'Towing Vehicle Barwidth',
        'caravan_width_mm'         => 'Caravan Width',
        'caravan_clearance_gap_mm' => 'Caravan Clearance Gap',
        'vinyl_inserts'            => 'Vinyl Inserts',
        'support_pockets'          => 'Support Pockets',
    ];

    foreach ($fields as $key => $label) {
        if (!empty($values[$key])) {
            $val = $values[$key];
            if ($key === 'support_pockets') {
                $val = ($val === '1' || $val === 1 || $val === 'yes' || $val === 'on') ? 'Yes' : 'No';
            }
            $item->add_meta_data($label, is_array($val) ? implode(', ', array_map('wc_clean', array_map('strval', $val))) : wc_clean($val), true);
        }
    }

    // Helper: parse JSON/CSV to clean int IDs
    $parse_ids = static function($raw) {
        if (empty($raw)) return [];
        if (is_string($raw) && strpos(trim($raw), '[') === 0) {
            $raw = json_decode($raw, true);
        }
        if (is_string($raw)) {
            $raw = preg_split('/[\s,|]+/', $raw);
        }
        return array_values(array_filter(array_map('intval', (array) $raw)));
    };

    // Save photo IDs using the same labels your admin renderer reads
    $attachments = [
        'hitch_attachment_ids' => 'Hitch Attachment Ids',
        'rear_attachment_ids'  => 'Rear Attachment Ids',
        'front_attachment_ids' => 'Front Attachment Ids',
    ];

    foreach ($attachments as $key => $label) {
        if (!empty($values[$key])) {
            $ids = $parse_ids($values[$key]);
            if ($ids) {
                $item->add_meta_data($label, implode(',', $ids), true);
            }
        }
    }
}, 10, 4);

add_filter( 'use_block_editor_for_post', function( $use_block_editor, $post ) {

    if ( $post && 'templates/template-stone-stomper.php' === get_page_template_slug( $post->ID ) ) {
        return false; // Disable Gutenberg for this template
    }
    return $use_block_editor;
}, 10, 2 );
add_action( 'admin_init', function() {
    $post_id = isset( $_GET['post'] ) ? (int) $_GET['post'] : 0;
    if ( ! $post_id ) {
        return;
    }

    // Check if current post uses your custom template
    if ( 'templates/template-stone-stomper.php' === get_page_template_slug( $post_id ) ) {
        // Disable Gutenberg
        add_filter( 'use_block_editor_for_post', '__return_false', 10 );

        // Disable Classic (WYSIWYG) editor
        remove_post_type_support( 'page', 'editor' );
    }
});





/**
 * Read the large JSON saved across cookies:
 * - order_form            (single)
 * - order_form_parts      (count)
 * - order_form_0..N       (chunks)
 */
function sts_read_order_form_cookie() {
	$prefix = 'order_form';

	if ( isset( $_COOKIE[ $prefix ] ) && $_COOKIE[ $prefix ] !== '' ) {
		$json = wp_unslash( $_COOKIE[ $prefix ] );
	}
	// } else {
	// 	$count = isset( $_COOKIE[ $prefix . '_parts' ] ) ? intval( $_COOKIE[ $prefix . '_parts' ] ) : 0;
	// 	$json  = '';
	// 	if ( $count > 0 ) {
	// 		for ( $i = 0; $i < $count; $i++ ) {
	// 			if ( isset( $_COOKIE[ "{$prefix}_{$i}" ] ) ) {
	// 				$json .= wp_unslash( $_COOKIE[ "{$prefix}_{$i}" ] );
	// 			}
	// 		}
	// 	}
	// }

	if ( ! $json ) return null;
	$decoded = json_decode( $json, true );
	return is_array( $decoded ) ? $decoded : null;
}

/**
 * Helper: normalize checkbox truthy values
 */
function sts_bool( $v ) {
	return ($v === '1' || $v === 1 || $v === true || $v === 'true' || $v === 'on' );
}

/**
 * Helper: ensure int array from mixed/stringified JSON
 */
function sts_to_int_array( $v ) {
	if ( is_string( $v ) ) {
		$maybe = json_decode( $v, true );
		if ( is_array( $maybe ) ) $v = $maybe;
	}
	if ( ! is_array( $v ) ) return array();
	return array_values( array_filter( array_map( 'intval', $v ) ) );
}

/**
 * Create/update a Customer CPT when an order is placed
 */
add_action( 'woocommerce_new_order', function( $order_id ) {
	$order = wc_get_order( $order_id );
	error_log('checked order');
	$data = sts_read_order_form_cookie();


	error_log(print_r($data, true));

	$cust_name    = isset( $data['customer_name'] )   ? sanitize_text_field( $data['customer_name'] )   : '';
	$cust_phone    = isset( $data['customer_phone'] )   ? sanitize_text_field( $data['customer_phone'] )   : '';
	$cust_email   = isset( $data['customer_email'] )  ? sanitize_email( $data['customer_email'] )       : '';
	$cust_address = isset( $data['customer_address'] )? sanitize_text_field( $data['customer_address'] ): '';
	$cust_suburb  = isset( $data['customer_suburb'] ) ? sanitize_text_field( $data['customer_suburb'] ) : '';
	$cust_state   = isset( $data['customer_state'] )  ? sanitize_text_field( $data['customer_state'] )  : '';
	$product_type = isset( $data['product_type'] )    ? sanitize_text_field( $data['product_type'] )    : '';

	$vehicle_make  = isset( $data['vehicle_make'] )  ? sanitize_text_field( $data['vehicle_make'] )  : ( isset( $data['veh_make'] ) ? sanitize_text_field( $data['veh_make'] ) : '' );
	$vehicle_model = isset( $data['vehicle_model'] ) ? sanitize_text_field( $data['vehicle_model'] ) : ( isset( $data['veh_model'] ) ? sanitize_text_field( $data['veh_model'] ) : '' );
	$vehicle_year  = isset( $data['vehicle_year'] )  ? sanitize_text_field( $data['vehicle_year'] )  : ( isset( $data['veh_year'] ) ? sanitize_text_field( $data['veh_year'] ) : '' );


	$caravan_make  = isset( $data['caravan_make'] )  ? sanitize_text_field( $data['caravan_make'] )  : ( isset( $data['caravan_make'] ) ? sanitize_text_field( $data['caravan_make'] ) : ( isset( $data['van_make'] ) ? sanitize_text_field( $data['van_make'] ) : '' ) );
	$caravan_model = isset( $data['caravan_model'] ) ? sanitize_text_field( $data['caravan_model'] ) : ( isset( $data['van_model'] ) ? sanitize_text_field( $data['van_model'] ) : '' );


	// Accessories: check Gravity-like names and "other"
	$accessories = array();
	if ( ! empty( $data['input_1.2'] ) || ! empty( $data['toolbox'] ) ) $accessories[] = 'toolbox';
	if ( ! empty( $data['input_1.1'] ) || ! empty( $data['factory_stoneguard'] ) ) $accessories[] = 'factory-stoneguard';
	if ( ! empty( $data['other_a_frame'] ) ) $accessories[] = sanitize_text_field( $data['other_a_frame'] );

	// Measurements
	$measure_barwidth_mm         = isset( $data['barwidth_mm'] ) ? sanitize_text_field( $data['barwidth_mm'] ) : '';
	$toolbox_width_mm    = isset( $data['toolbox_width_mm'] ) ? sanitize_text_field( $data['toolbox_width_mm'] ) : '';
	$toolbox_length_mm   = isset( $data['toolbox_length_mm'] ) ? sanitize_text_field( $data['toolbox_length_mm'] ) : '';
	$caravan_width_mm    = isset( $data['caravan_width_mm'] ) ? sanitize_text_field( $data['caravan_width_mm'] ) : '';
	$a_frame_length_mm   = isset( $data['a_frame_length_mm'] ) ? sanitize_text_field( $data['a_frame_length_mm'] ) : '';
	$stoneguard_length_mm = isset( $data['stoneguard_length_mm'] ) ? sanitize_text_field( $data['stoneguard_length_mm'] ) : '';
	$stoneguard_width_mm = isset( $data['stoneguard_width_mm'] ) ? sanitize_text_field( $data['stoneguard_width_mm'] ) : '';
	$vinyl_width_mm     = isset( $data['vinyl_width_mm'] ) ? sanitize_text_field( $data['vinyl_width_mm'] ) : '';
	$vinyl_length_mm     = isset( $data['vinyl_length_mm'] ) ? sanitize_text_field( $data['vinyl_length_mm'] ) : '';
	// $support_pockets     = sts_bool( $data['support_pockets'] ?? 'yes' ) ? 'yes' : 'no';
	// $support_pockets_raw = $data['support_pockets'] ?? 'yes';
	// $support_pockets     = sts_bool( $support_pockets_raw ) ? 'yes' : 'no';


	// // Photos (hidden inputs hold JSON arrays of IDs)
	$hitch_ids     = sts_to_int_array( $data['hitch_ids'] ?? array() );
	$rear_ids     = sts_to_int_array( $data['rear_ids'] ?? array() );
	$front_ids     = sts_to_int_array( $data['front_ids'] ?? array() );


	// $photos = array(
	// 	'hitch_ids' => sts_to_int_array( $data['hitch_ids'] ?? array() ),
	// 	'rear_ids'  => sts_to_int_array( $data['rear_ids'] ?? array() ),
	// 	'front_ids' => sts_to_int_array( $data['front_ids'] ?? array() ),
	// );

	// Final details
	$final = array(
		'final_delivery'  => isset( $data['final_delivery'] ) ? sanitize_text_field( $data['final_delivery'] ) : ( isset( $data['final_address'] ) ? sanitize_text_field( $data['final_address'] ) : '' ),
		'shipping_method' => isset( $data['shipping_method'] ) ? sanitize_text_field( $data['shipping_method'] ) : ( isset( $data['shipping'] ) ? sanitize_text_field( $data['shipping'] ) : '' ),
		'acc_upsells'     => array_values( array_unique( array_map( 'intval', $data['acc_upsells'] ?? array() ) ) ),
	);

	// Create the CPT entry
	$title_bits = array_filter( array( $cust_name, $cust_email, 'Order #' . $order_id ) );
	$post_id = wp_insert_post( array(
		'post_type'   => 'customer',
		'post_status' => 'publish',
		'post_title'  => $cust_name,
	) );

	if ( is_wp_error( $post_id ) ) return;
	$customer_details = array(
    'name' => $cust_name, // or address
    'delivery_address' => $cust_email, // or address
);

error_log(print_r($cust_name,true));
	// Link to order + basic fields
	update_post_meta( $post_id, 'order_id', $order_id );
	update_post_meta( $post_id, 'name', $cust_name );
	update_post_meta( $post_id, 'customer_phone', $cust_phone );
	update_post_meta( $post_id, 'email', $cust_email );
	update_post_meta( $post_id, 'delivery_address', $cust_address );
	update_post_meta( $post_id, 'subrubs', $cust_suburb );
	update_post_meta( $post_id, 'state', $cust_state );
	// update_post_meta( $post_id, 'product_type', $product_type );
	update_post_meta( $post_id, 'vehicle_make', $vehicle_make );
	update_post_meta( $post_id, 'vehicle_model', $vehicle_model );
	update_post_meta( $post_id, 'caravan_make', $caravan_make );
	update_post_meta( $post_id, 'caravan_model', $caravan_model );
	update_post_meta( $post_id, 'year_of_manufacture', $vehicle_year );
	update_post_meta( $post_id, 'measure_barwidth_mm', $measure_barwidth_mm );
	update_post_meta( $post_id, 'bar_width_mm', $measure_barwidth_mm );
	update_post_meta( $post_id, 'caravan_width_mm', $caravan_width_mm );
	update_post_meta( $post_id, 'caravan_length_mm', $a_frame_length_mm );
	// // update_post_meta( $post_id, 'support_pockets', $support_pockets );
	// // Support pockets - save in ACF-compatible format (1/0)
	// // $support_pockets_raw = $data['support_pockets'] ?? 'no';
	// // $support_pockets     = ( strtolower( $support_pockets_raw ) === 'yes' || sts_bool( $support_pockets_raw ) ) ? 1 : 0;
	// // update_post_meta( $post_id, 'support_pockets', $support_pockets );

	// $support_pocket = isset( $data['support_pocket'] ) && $data['support_pocket'] === 'yes' ? 'yes' : 'no';
	// update_post_meta( $post_id, 'support_pocket', $support_pocket );


	update_post_meta( $post_id, 'factory_stoneguard_width', $stoneguard_length_mm );
	update_post_meta( $post_id, 'factory_stoneguard_height', $stoneguard_width_mm );
	update_post_meta( $post_id, 'vinyl_insert_width_mm', $vinyl_width_mm );
	update_post_meta( $post_id, 'vinyl_insert_height_mm', $vinyl_length_mm );
	update_post_meta( $post_id, 'toolbox_width_mm', $toolbox_width_mm );
	update_post_meta( $post_id, 'toolbox_height_mm', $toolbox_length_mm );

	// update_post_meta( $post_id, 'hitch_ids', $hitch_ids );
	// update_post_meta( $post_id, 'rear_ids', $rear_ids );
	// update_post_meta( $post_id, 'front_ids', $front_ids );

	// Initialize meta keys if they don't exist
	$meta_keys = [
		'hitch_ids' => $hitch_ids,
		'rear_ids'  => $rear_ids,
		'front_ids' => $front_ids,
	];

	foreach ( $meta_keys as $key => $value ) {
		// If the meta key doesn't exist, add it first
		if ( ! metadata_exists( 'post', $post_id, $key ) ) {
			add_post_meta( $post_id, $key, '', true );
		}

		// Then update it
		update_post_meta( $post_id, $key, $value );
	}


	// update_post_meta( $post_id, 'final_details', $final );

	// Optionally set a featured image from the first uploaded photo if any
	// $first_img = 0;
	// foreach ( array( 'hitch_ids','rear_ids','front_ids' ) as $k ) {
	// 	if ( ! empty( $photos[ $k ] ) ) { $first_img = intval( $photos[ $k ][0] ); break; }
	// }
	// if ( $first_img > 0 ) {
	// 	set_post_thumbnail( $post_id, $first_img );
	// }

	// Optional: associate CPT with logged-in user
	if ( $order && $order->get_user_id() ) {
		update_post_meta( $post_id, '_customer_user_id', $order->get_user_id() );
	}

	// Clear the cookies after saving
	$expire = time() - 3600;
	setcookie( 'order_form', '', $expire, '/' );
	if ( isset( $_COOKIE['order_form_parts'] ) ) {
		$count = intval( $_COOKIE['order_form_parts'] );
		setcookie( 'order_form_parts', '', $expire, '/' );
		for ( $i = 0; $i < $count; $i++ ) {
			setcookie( "order_form_{$i}", '', $expire, '/' );
		}
	}
}, 10, 1 );

add_filter( 'block_categories_all', function( $categories, $post ) {
    // Add your custom category on top
    $custom_category = array(
        'slug'  => 'theme-blocks',
        'title' => __( 'Theme Blocks', 'sample_td' ),
    );

    // Prepend it to the existing categories
    array_unshift( $categories, $custom_category );

    return $categories;
}, 10, 2 );

function allowed_block_types( $allowed_blocks, $editor_context ) {

    // Always allow these core blocks
    $core_blocks = array(
        'core/paragraph',
        'core/heading',
        'core/list',
        'core/table',
        'core/image',
        'core/cover',
        'core/buttons',
        'core/button',
        'core/group',
        'core/columns',
        'core/seperator',
        'core/column',
        'core/html',
        'core/spacer',
        'core/separator',
        'core/shortcode',
        'gravityforms/form',
		'samplepack/ingredients',
		'samplepack/ingredients-item',
    );

    // Get all registered blocks
    $all_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

    // Allow all blocks from category 'theme-blocks'
    foreach ( $all_blocks as $block_name => $block_data ) {
        if ( isset( $block_data->category ) && $block_data->category === 'theme-blocks' ) {
            $core_blocks[] = $block_name;
        }
    }

    return $core_blocks;
}
// add_filter( 'allowed_block_types_all', 'allowed_block_types', 10, 2 );


// 🔹 Add WooCommerce support in your theme
function mytheme_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );


// Handle upsell + main product add to cart
add_action( 'template_redirect', function() {
	if ( isset( $_POST['main_product_id'] ) && isset( $_POST['upsell_ids'] ) ) {
		$main_id   = absint( $_POST['main_product_id'] );
		$upsell_ids = array_map( 'absint', $_POST['upsell_ids'] );

		// Add main product
		WC()->cart->add_to_cart( $main_id );

		// Add upsells
		foreach ( $upsell_ids as $upsell_id ) {
			WC()->cart->add_to_cart( $upsell_id );
		}

		// Redirect to cart
		// wp_safe_redirect( wc_get_cart_url() );
		exit;
	}
});



function show_towing_svg_in_editor( $post ) {
    // Get all meta data
   $order_id = get_post_meta( $post->ID, 'order_id', true );
	$order    = wc_get_order( $order_id );

	if ( $order ) {
		// Basic info
		$order_date       = $order->get_date_created()->date_i18n('Y-m-d');
		$customer_name    = $order->get_formatted_billing_full_name();
		// $customer_phone   = $order->get_billing_phone();
		$customer_email   = $order->get_billing_email();
		$delivery_address = $order->get_formatted_shipping_address();
		$delivery_cost    = $order->get_shipping_total();
		$order_total      = $order->get_total();
			$delivery_instructions = $order->get_customer_note(); // 🟢 Add this line


		$products         = [];

		// Loop products in the order
		foreach ( $order->get_items() as $item_id => $item ) {
			$product_name = $item->get_name();
			$quantity     = $item->get_quantity();
			$total        = $item->get_total();

			$products[] = [
				'name'     => $product_name,
				'quantity' => $quantity,
				'total'    => $total,
			];
		}
	}

	$customer_phone = get_post_meta( $post->ID, 'customer_phone', true );
	// var_dump(get_post_meta( $post->ID, 'phone', true ));
	$measure_barwidth_mm = get_post_meta( $post->ID, 'measure_barwidth_mm', true );
    $bar_width_mm        = get_post_meta( $post->ID, 'bar_width_mm', true );
    $caravan_length_mm        = get_post_meta( $post->ID, 'caravan_length_mm', true );
    $caravan_width_mm    = get_post_meta( $post->ID, 'caravan_width_mm', true );
	$support_pocket = get_post_meta( $post->ID, 'support_pocket', true );

    $factory_stoneguard_width    = get_post_meta( $post->ID, 'factory_stoneguard_width', true );
    $factory_stoneguard_height    = get_post_meta( $post->ID, 'factory_stoneguard_height', true );
    $vinyl_insert_width_mm    = get_post_meta( $post->ID, 'vinyl_insert_width_mm', true );
    $vinyl_insert_height_mm    = get_post_meta( $post->ID, 'vinyl_insert_height_mm', true );
    $toolbox_width_mm    = get_post_meta( $post->ID, 'toolbox_width_mm', true );
    $toolbox_height_mm    = get_post_meta( $post->ID, 'toolbox_height_mm', true );

    $vehicle_make    = get_post_meta( $post->ID, 'vehicle_make', true );
    $caravan_make    = get_post_meta( $post->ID, 'caravan_make', true );
    $sts_var_caravan_bar_option    	= get_post_meta( $post->ID, 'sts_var_caravan_bar_option', true );
    $sts_var_caravan_bar_bend    	= get_post_meta( $post->ID, 'sts_var_caravan_bar_bend', true );
    $sts_var_caravan_ss_length_adj  = get_post_meta( $post->ID, 'sts_var_caravan_ss_length_adj', true );
    $sts_var_caravan_cut_out    = get_post_meta( $post->ID, 'sts_var_caravan_cut_out', true );
    $sts_var_caravan_break_form    = get_post_meta( $post->ID, 'sts_var_caravan_break_form', true );
    $sts_var_caravan_hr_form    = get_post_meta( $post->ID, 'sts_var_caravan_hr_form', true );
    $sts_var_proposed_date_of_delivery    = get_post_meta( $post->ID, 'sts_var_proposed_date_of_delivery', true );

	$hitch_ids = get_post_meta( $post->ID, 'hitch_ids', true );
	$rear_ids  = get_post_meta( $post->ID, 'rear_ids', true );
	$front_ids = get_post_meta( $post->ID, 'front_ids', true );

	// $checked = ($support_pocket === 'yes') ? 'checked' : 'not';
	// var_dump( get_post_meta( $post->ID));
	// var_dump( $support_pocket);

	function show_meta_images( $meta_value ) {
		if ( empty( $meta_value ) ) return;

		// If meta is an array containing serialized values
		foreach ( (array) $meta_value as $maybe_serialized ) {
			$ids = maybe_unserialize( $maybe_serialized );

			// If it's still serialized (nested), unserialize again
			if ( is_string( $ids ) && str_starts_with( $ids, 'a:' ) ) {
				$ids = maybe_unserialize( $ids );
			}

			// Single ID case
			$id = intval( $ids );
			$url = wp_get_attachment_image_url( $id, 'full' );
			if ( $url ) {
				echo '<img src="' . esc_url( $url ) . '" alt="" style="max-width:150px; margin:5px;">';
			}

		}
	}



	if ( ! empty( $sts_var_proposed_date_of_delivery ) ) {
		// Convert to timestamp
		$sts_var_proposed_date_of_delivery = strtotime( $sts_var_proposed_date_of_delivery );
	}
    ?>
		<div class="customer-upload-images">
		<?php if ( $hitch_ids ) { ?>
			<div class="row row-1">
			<h3>Hitch Images</h3>
			<div class="hitch-images image-group">
				<?php foreach ( $hitch_ids as $id ) :
				$img_url = wp_get_attachment_image_url( $id, 'large' ); ?>
				<img src="<?php echo esc_url( $img_url ); ?>" alt="" class="popup-image" />
				<?php endforeach; ?>
			</div>
			<div class="image-lightbox">
				<div class="lightbox-inner">
				<img src="" alt="" class="lightbox-img" />
				<div class="lightbox-controls">
					<span class="lightbox-prev">&#10094;</span>
					<span class="lightbox-next">&#10095;</span>
					<span class="lightbox-close">&times;</span>
				</div>
				</div>
			</div>
			</div>
		<?php } ?>

		<?php if ( $rear_ids ) { ?>
			<div class="row row-1">
			<h3>Rear Images</h3>
			<div class="rear-images image-group">
				<?php foreach ( $rear_ids as $id ) :
				$img_url = wp_get_attachment_image_url( $id, 'large' ); ?>
				<img src="<?php echo esc_url( $img_url ); ?>" alt="" class="popup-image" />
				<?php endforeach; ?>
			</div>
			<div class="image-lightbox">
				<div class="lightbox-inner">
				<img src="" alt="" class="lightbox-img" />
				<div class="lightbox-controls">
					<span class="lightbox-prev">&#10094;</span>
					<span class="lightbox-next">&#10095;</span>
					<span class="lightbox-close">&times;</span>
				</div>
				</div>
			</div>
			</div>
		<?php } ?>

		<?php if ( $front_ids ) { ?>
			<div class="row row-1">
			<h3>Front Images</h3>
			<div class="front-images image-group">
				<?php foreach ( $front_ids as $id ) :
				$img_url = wp_get_attachment_image_url( $id, 'large' ); ?>
				<img src="<?php echo esc_url( $img_url ); ?>" alt="" class="popup-image" />
				<?php endforeach; ?>
			</div>
			<div class="image-lightbox">
				<div class="lightbox-inner">
				<img src="" alt="" class="lightbox-img" />
				<div class="lightbox-controls">
					<span class="lightbox-prev">&#10094;</span>
					<span class="lightbox-next">&#10095;</span>
					<span class="lightbox-close">&times;</span>
				</div>
				</div>
			</div>
			</div>
		<?php } ?>
		</div>

	<!-- Order Preview Image -->
    <div style="text-align:center; padding:20px;">

		<div class="functional-buttons">
			<a href="#" class="button button-primary generate-diagram" style="margin-right:10px;">Generate Diagram</a>
			<a href="#" class="button button-secondary generate-word-doc" style="margin-right:10px;">Generate Word Document</a>
			<a href="#" class="button button-primary generate-pdf" style="margin-right:10px;">Generate PDF</a>
			<a href="#" class="button button-secondary email-to-manufacturer" style="margin-right:10px;">Email to Manufacturer</a>
			<a href="<?php echo esc_url( get_edit_post_link( $order_id ) ); ?>" class="button button-secondary" style="">View Order #<?php echo esc_html( $order_id ); ?></a>
		</div>

        <a href="#" class="stone-stomper-vector" id="show-order-popup">

			<?php

			if($caravan_length_mm < 1900 ){ ?>
				<svg xmlns="http://www.w3.org/2000/svg" id="Layer_2" version="1.1" viewBox="0 0 1200 800">
					<!-- Generator: Adobe Illustrator 29.8.1, SVG Export Plug-In . SVG Version: 2.1.1 Build 2)  -->
					<defs>
						<style>
						.st0 {
							stroke: #fa3232;
						}

						.st0, .st1, .st2 {
							fill: none;
						}

						.st3, .st4 {
							fill: #fff;
						}

						.st3, .st1, .st2 {
							stroke: #000;
						}

						.st1 {
							stroke-width: 3px;
						}

						.st5 {
							fill: #fa3232;
							font-family: OpenSans, 'Open Sans';
							font-size: 15px;
							letter-spacing: .03em;
						}
						</style>
					</defs>
					<path class="st1" d="M935.7,173.33c.12-1.09-.69-1.99-1.79-1.99H278.24c-1.1,0-1.91.9-1.79,1.99l41.6,402.61c.11,1.09,1.11,1.99,2.21,1.99h246.73c1.1,0,2-.9,2-2v-61.51c0-1.1.9-2,2-2h68.28c1.1,0,2,.9,2,2v61.51c0,1.1.9,2,2,2h247.86c1.1,0,2.09-.9,2.21-1.99l42.37-402.61Z"/>
					<polyline class="st2" points="329.42 577.92 288.76 180.94 923.79 180.94 882.27 577.92"/>
					<circle class="st2" cx="928.02" cy="177.82" r="5.22"/>
					<circle class="st2" cx="283.6" cy="177.82" r="5.22"/>
					<rect class="st2" x="390.67" y="364.08" width="438.42" height="27.06"/>
					<rect class="st3" x="504.04" y="315.51" width="211.2" height="195.21"/>
					<rect class="st2" x="404.09" y="381.28" width="8.47" height="196.64"/>
					<rect class="st2" x="477.94" y="381.28" width="8.47" height="196.64"/>
					<rect class="st2" x="732.88" y="381.28" width="8.47" height="196.64"/>
					<rect class="st2" x="806.72" y="381.28" width="8.47" height="196.64"/>
					<circle class="st2" cx="737.11" cy="374.25" r="4.24"/>
					<circle class="st2" cx="810.96" cy="374.25" r="4.24"/>
					<circle class="st2" cx="408.33" cy="374.25" r="4.24"/>
					<circle class="st2" cx="482.17" cy="374.25" r="4.24"/>
					<text class="st5" transform="translate(565.96 411.74)"><tspan x="0" y="0">Vinyl Insert</tspan></text>
					<g>
						<line class="st0" x1="274.29" y1="139.99" x2="935.24" y2="139.99"/>
						<g transform="translate(604.76,139.99)">
							<rect class="st4" x="-67.5" y="-22" width="135" height="24"/>
							<text class="st5" text-anchor="middle" dominant-baseline="middle" y="0"><?php echo esc_html( $caravan_width_mm ?: '-' ); ?></text>
						</g>
						<polyline class="st0" points="278.3 143.72 274.29 139.99 278.3 136.27"/>
						<polyline class="st0" points="931.24 136.27 935.24 140 931.24 143.72"/>
					</g>
					<g>
						<line class="st0" x1="317.29" y1="608.6" x2="894.24" y2="608.6"/>
						<g transform="translate(957.8,375.13)">
						<rect class="st4" x="10" y="-12" width="135" height="24"/>
						<text class="st5" text-anchor="start" dominant-baseline="middle" x="20"><?php echo esc_html( $caravan_length_mm ?: '-' ); ?></text>
						</g>
						<polyline class="st0" points="321.3 612.33 317.29 608.6 321.3 604.88"/>
						<polyline class="st0" points="890.24 604.88 894.24 608.6 890.24 612.33"/>
					</g>


					<g>
						<line class="st0" x1="957.8" y1="170.34" x2="957.8" y2="579.93"/>
						<g transform="translate(605.77,608.6)">
							<rect class="st4" x="-67.5" y="-12" width="135" height="24"/>
							<text class="st5" text-anchor="middle" dominant-baseline="middle" y="0"><?php echo esc_html( $bar_width_mm ?: '-' ); ?></text>
						</g>
						<polyline class="st0" points="954.07 174.34 957.8 170.34 961.52 174.34"/>
						<polyline class="st0" points="961.52 575.92 957.8 579.93 954.07 575.92"/>
					</g>
				</svg>
			<?php } else { ?>


				<svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 1200 800">
				<!-- Generator: Adobe Illustrator 29.8.1, SVG Export Plug-In . SVG Version: 2.1.1 Build 2)  -->
				<defs>
					<style>
						.st0, .st1, .st2, .st3, .st4, .st5 {
							fill: none;
						}

						.st1, .st2, .st3, .st4 {
							stroke: #fa3232;
						}

						.st2 {
							stroke-dasharray: 5.39 5.39;
						}

						.st3 {
							stroke-dasharray: 5.23 5.23;
						}

						.st4 {
							stroke-dasharray: 5.07 5.07;
						}

						.st6, .st7 {
							fill: #2c2a2a;
						}

						.st7 {
							opacity: .1;
						}

						.st8, .st9 {
							fill: #fa3232;
						}

						.st9 {
							font-family: OpenSans, 'Open Sans';
							font-size: 15px;
						}

						.st10 {
							fill: #e6e6e6;
						}

						.st10, .st5 {
							stroke: #000;
							stroke-miterlimit: 10;
							stroke-width: 2.9px;
						}

						.st11 {
							clip-path: url(#clippath-1);
						}

						.st12 {
							clip-path: url(#clippath-3);
						}

						.st13 {
							clip-path: url(#clippath-4);
						}

						.st14 {
							clip-path: url(#clippath-2);
						}

						.st15 {
							clip-path: url(#clippath-7);
						}

						.st16 {
							clip-path: url(#clippath-6);
						}

						.st17 {
							clip-path: url(#clippath-9);
						}

						.st18 {
							clip-path: url(#clippath-8);
						}

						.st19 {
							clip-path: url(#clippath-5);
						}

						.st20 {
							fill: #fff;
						}

						.st21 {
							opacity: .1;
						}

						.st22 {
							letter-spacing: .03em;
						}

						.st23 {
							letter-spacing: .02em;
						}

						.st24 {
							letter-spacing: .02em;
						}

						.st25 {
							clip-path: url(#clippath);
						}
					</style>

					<clipPath id="clippath">
					<rect class="st0" x="339.32" y="404.87" width="146.76" height="37.86"/>
					</clipPath>
					<clipPath id="clippath-1">
					<rect class="st0" x="442.55" y="415.97" width="26.88" height="17.88"/>
					</clipPath>
					<clipPath id="clippath-2">
					<rect class="st0" x="355.97" y="415.97" width="26.88" height="17.88"/>
					</clipPath>
					<clipPath id="clippath-3">
					<rect class="st0" x="442.55" y="214.04" width="26.88" height="206.49"/>
					</clipPath>
					<clipPath id="clippath-4">
					<rect class="st0" x="355.97" y="214.04" width="26.88" height="206.49"/>
					</clipPath>
					<clipPath id="clippath-5">
					<rect class="st0" x="721.94" y="404.87" width="146.76" height="37.86"/>
					</clipPath>
					<clipPath id="clippath-6">
					<rect class="st0" x="825.17" y="415.97" width="26.88" height="17.88"/>
					</clipPath>
					<clipPath id="clippath-7">
					<rect class="st0" x="738.59" y="415.97" width="26.88" height="17.88"/>
					</clipPath>
					<clipPath id="clippath-8">
					<rect class="st0" x="825.17" y="214.04" width="26.88" height="206.49"/>
					</clipPath>
					<clipPath id="clippath-9">
					<rect class="st0" x="738.59" y="214.04" width="26.88" height="206.49"/>
					</clipPath>
				</defs>
				<g>
					<path class="st5" d="M273.19,735.73v-150.81c0-10.37,8.41-18.78,18.78-18.78h624.07c10.37,0,18.78,8.41,18.78,18.78v150.81"/>
					<path class="st5" d="M273.19,755.72v-150.8c0-10.37,8.41-18.78,18.78-18.78h624.07c10.37,0,18.78,8.41,18.78,18.78v150.8"/>
					<path class="st5" d="M911.21,43.84v150.8c0,10.37-8.41,18.78-18.78,18.78H315.59c-10.37,0-18.78-8.41-18.78-18.78V43.84"/>
					<path class="st5" d="M903.97,43.84v150.81c0,6.36-5.18,11.54-11.54,11.54H315.59c-6.36,0-11.54-5.18-11.54-11.54V43.84"/>
					<path class="st5" d="M896.73,43.84v150.81c0,2.37-1.93,4.3-4.3,4.3H315.59c-2.37,0-4.3-1.93-4.3-4.3V43.84"/>
					<polyline class="st5" points="889.49 46.78 889.49 191.71 318.53 191.71 318.53 46.78"/>
				</g>
				<rect class="st5" x="528.75" y="566.14" width="150.53" height="11.48"/>
				<rect class="st5" x="433.19" y="635.03" width="341.64" height="35.97"/>
				<rect x="480.53" y="629.5" width="17.43" height="11.91"/>
				<rect x="595.29" y="629.5" width="17.43" height="11.91"/>
				<rect x="710.05" y="629.5" width="17.43" height="11.91"/>
				<polygon class="st5" points="583.81 304.26 568.08 304.26 472.83 566.19 488.56 566.19 583.81 304.26"/>
				<polygon class="st5" points="624.21 304.26 639.94 304.26 735.19 566.19 719.45 566.19 624.21 304.26"/>
				<line class="st5" x1="574.88" y1="304.26" x2="633.14" y2="304.26"/>
				<line class="st5" x1="551.57" y1="392.71" x2="656.44" y2="392.71"/>
				<line class="st5" x1="522.94" y1="472.08" x2="685.08" y2="472.08"/>
				<rect class="st5" x="595.51" y="297.41" width="17.01" height="174.42"/>
				<rect class="st5" x="588.77" y="246.28" width="30.48" height="37.74"/>
				<rect class="st5" x="599.29" y="284.02" width="9.45" height="13.37"/>
				<rect class="st5" x="609.49" y="213.65" width="22.17" height="13.37"/>
				<rect class="st5" x="599.29" y="213.92" width="9.45" height="26.59"/>
				<rect class="st5" x="599.29" y="239.09" width="9.45" height="6.99"/>
				<polygon class="st5" points="696.92 420.06 700.71 420.06 723.7 483.27 719.9 483.27 696.92 420.06"/>
				<line class="st5" x1="685.86" y1="431.09" x2="701.16" y2="431.09"/>
				<line class="st5" x1="701.48" y1="472.23" x2="716.79" y2="472.23"/>
				<polygon class="st5" points="511.02 420.06 507.22 420.06 484.24 483.27 488.03 483.27 511.02 420.06"/>
				<line class="st5" x1="522.08" y1="431.09" x2="506.77" y2="431.09"/>
				<line class="st5" x1="506.45" y1="472.23" x2="491.15" y2="472.23"/>
				<line class="st1" x1="299.44" y1="269.28" x2="908.77" y2="269.28"/>
				<polyline class="st1" points="303.45 273.01 299.44 269.28 303.45 265.56"/>
				<polyline class="st1" points="904.77 265.56 908.77 269.28 904.77 273.01"/>
				<rect class="st20" x="532.83" y="261.27" width="142.56" height="16.03"/>
				<text class="st9" transform="translate(542.83 274.3)"><tspan class="st23" x="0" y="0">Vehicl</tspan><tspan class="st22" x="43.44" y="0">e </tspan><tspan class="st23" x="56.5" y="0">B</tspan><tspan class="st22" x="66.58" y="0">arw</tspan><tspan class="st23" x="93.84" y="0">i</tspan><tspan class="st22" x="97.99" y="0">d</tspan><tspan class="st24" x="107.56" y="0">t</tspan><tspan x="113.21" y="0">h</tspan></text>
				<line class="st1" x1="350.44" y1="510.83" x2="858.13" y2="510.83"/>
				<rect class="st20" x="537.36" y="480.28" width="135.53" height="44.32"/>
				<rect class="st7" x="349.31" y="453.64" width="509.93" height="98.6"/>
				<g>
					<path class="st8" d="M573.66,497.6h-1.25v-9.6h-3.39v-1.11h8.03v1.11h-3.39v9.6Z"/>
					<path class="st8" d="M585.78,493.58c0,1.31-.33,2.33-.99,3.07-.66.74-1.57,1.1-2.73,1.1-.72,0-1.36-.17-1.91-.51-.56-.34-.99-.82-1.29-1.45-.3-.63-.45-1.37-.45-2.21,0-1.31.33-2.33.98-3.06.65-.73,1.56-1.09,2.72-1.09s2.02.37,2.68,1.12c.66.75.99,1.76.99,3.03ZM579.66,493.58c0,1.03.21,1.81.62,2.34.41.54,1.01.81,1.81.81s1.4-.27,1.81-.8c.41-.53.62-1.32.62-2.35s-.21-1.79-.62-2.33c-.41-.53-1.02-.79-1.83-.79s-1.4.26-1.8.78-.61,1.3-.61,2.34Z"/>
					<path class="st8" d="M595.2,493.58c0,1.31-.33,2.33-.99,3.07-.66.74-1.57,1.1-2.73,1.1-.72,0-1.36-.17-1.91-.51-.56-.34-.99-.82-1.29-1.45-.3-.63-.45-1.37-.45-2.21,0-1.31.33-2.33.98-3.06.65-.73,1.56-1.09,2.72-1.09s2.02.37,2.68,1.12c.66.75.99,1.76.99,3.03ZM589.08,493.58c0,1.03.21,1.81.62,2.34.41.54,1.01.81,1.81.81s1.4-.27,1.81-.8c.41-.53.62-1.32.62-2.35s-.21-1.79-.62-2.33c-.41-.53-1.02-.79-1.83-.79s-1.4.26-1.8.78-.61,1.3-.61,2.34Z"/>
					<path class="st8" d="M598.92,497.6h-1.22v-11.4h1.22v11.4Z"/>
					<path class="st8" d="M605.59,489.44c1.05,0,1.87.36,2.46,1.08.58.72.88,1.74.88,3.06s-.29,2.34-.88,3.07c-.59.73-1.41,1.09-2.45,1.09-.52,0-1-.1-1.43-.29-.43-.19-.79-.49-1.09-.89h-.09l-.26,1.03h-.87v-11.4h1.22v2.77c0,.62-.02,1.18-.06,1.67h.06c.57-.8,1.41-1.2,2.52-1.2ZM605.42,490.46c-.83,0-1.43.24-1.79.71-.37.48-.55,1.28-.55,2.41s.19,1.94.56,2.42c.38.49.98.73,1.81.73.75,0,1.3-.27,1.67-.82.37-.54.55-1.33.55-2.35s-.18-1.82-.55-2.34-.93-.77-1.7-.77Z"/>
					<path class="st8" d="M618.36,493.58c0,1.31-.33,2.33-.99,3.07-.66.74-1.57,1.1-2.73,1.1-.72,0-1.36-.17-1.91-.51-.56-.34-.99-.82-1.29-1.45-.3-.63-.45-1.37-.45-2.21,0-1.31.33-2.33.98-3.06.65-.73,1.56-1.09,2.72-1.09s2.02.37,2.68,1.12c.66.75.99,1.76.99,3.03ZM612.24,493.58c0,1.03.21,1.81.62,2.34.41.54,1.01.81,1.81.81s1.4-.27,1.81-.8c.41-.53.62-1.32.62-2.35s-.21-1.79-.62-2.33c-.41-.53-1.02-.79-1.83-.79s-1.4.26-1.8.78-.61,1.3-.61,2.34Z"/>
					<path class="st8" d="M622.79,493.49l-2.79-3.92h1.38l2.12,3.08,2.11-3.08h1.37l-2.79,3.92,2.94,4.11h-1.38l-2.25-3.25-2.27,3.25h-1.38l2.94-4.11Z"/>
					<path class="st8" d="M636.68,486.89l-3.99,10.71h-1.22l3.99-10.71h1.22Z"/>
					<path class="st8" d="M549.04,512.75c0,.94-.34,1.68-1.03,2.21s-1.61.79-2.78.79c-1.27,0-2.25-.16-2.93-.49v-1.2c.44.19.92.33,1.44.44s1.03.16,1.54.16c.83,0,1.46-.16,1.88-.47.42-.32.63-.75.63-1.32,0-.37-.07-.67-.22-.91-.15-.24-.4-.46-.75-.66-.35-.2-.88-.43-1.59-.68-1-.36-1.71-.78-2.13-1.27-.43-.49-.64-1.12-.64-1.91,0-.83.31-1.48.93-1.97s1.44-.73,2.46-.73,2.04.2,2.94.59l-.39,1.08c-.88-.37-1.74-.56-2.58-.56-.66,0-1.17.14-1.54.42-.37.28-.56.68-.56,1.18,0,.37.07.67.21.91.14.24.37.45.69.65.33.2.82.42,1.49.66,1.12.4,1.9.83,2.32,1.29.42.46.63,1.05.63,1.79Z"/>
					<path class="st8" d="M553.64,514.75c.21,0,.42-.02.62-.05.2-.03.36-.06.48-.1v.93c-.13.06-.33.12-.58.16-.26.04-.49.06-.69.06-1.55,0-2.33-.82-2.33-2.45v-4.78h-1.15v-.59l1.15-.51.51-1.71h.7v1.86h2.33v.95h-2.33v4.72c0,.48.12.85.34,1.11s.54.39.94.39Z"/>
					<path class="st8" d="M563.27,511.58c0,1.31-.33,2.33-.99,3.07-.66.74-1.57,1.1-2.73,1.1-.72,0-1.36-.17-1.91-.51-.56-.34-.99-.82-1.29-1.45-.3-.63-.45-1.37-.45-2.21,0-1.31.33-2.33.98-3.06.65-.73,1.56-1.09,2.72-1.09s2.02.37,2.68,1.12c.66.75.99,1.76.99,3.03ZM557.16,511.58c0,1.03.21,1.81.62,2.34.41.54,1.01.81,1.81.81s1.4-.27,1.81-.8c.41-.53.62-1.32.62-2.35s-.21-1.79-.62-2.33c-.41-.53-1.02-.79-1.83-.79s-1.4.26-1.8.78-.61,1.3-.61,2.34Z"/>
					<path class="st8" d="M570.9,515.6v-5.19c0-.65-.15-1.14-.45-1.46s-.76-.48-1.4-.48c-.84,0-1.46.23-1.85.68s-.59,1.2-.59,2.25v4.21h-1.22v-8.03h.99l.2,1.1h.06c.25-.4.6-.7,1.05-.92.45-.22.95-.33,1.5-.33.97,0,1.69.23,2.18.7.49.47.73,1.21.73,2.24v5.24h-1.22Z"/>
					<path class="st8" d="M578,515.75c-1.19,0-2.12-.36-2.81-1.08-.69-.72-1.03-1.73-1.03-3.01s.32-2.32.96-3.08c.64-.76,1.49-1.14,2.57-1.14,1.01,0,1.8.33,2.39.99.59.66.88,1.54.88,2.62v.77h-5.53c.02.94.26,1.66.71,2.15.45.49,1.09.73,1.91.73.86,0,1.72-.18,2.56-.54v1.08c-.43.19-.84.32-1.22.4-.38.08-.85.12-1.39.12ZM577.67,508.45c-.64,0-1.16.21-1.54.63-.38.42-.61,1-.68,1.74h4.2c0-.77-.17-1.35-.51-1.76-.34-.41-.83-.61-1.46-.61Z"/>
					<path class="st8" d="M589.6,507.58v.77l-1.49.18c.14.17.26.39.37.67.11.28.16.59.16.93,0,.79-.27,1.41-.81,1.88s-1.27.7-2.21.7c-.24,0-.46-.02-.67-.06-.52.27-.78.62-.78,1.03,0,.22.09.38.27.49.18.1.49.16.93.16h1.42c.87,0,1.54.18,2,.55.47.37.7.9.7,1.6,0,.89-.36,1.57-1.07,2.03-.71.47-1.75.7-3.12.7-1.05,0-1.86-.2-2.43-.59-.57-.39-.85-.94-.85-1.66,0-.49.16-.91.47-1.27.31-.36.75-.6,1.32-.73-.21-.09-.38-.24-.52-.43-.14-.19-.21-.42-.21-.68,0-.29.08-.55.23-.77.16-.22.4-.43.74-.64-.42-.17-.75-.46-1.01-.87-.26-.41-.39-.88-.39-1.41,0-.88.26-1.56.79-2.03.53-.48,1.27-.71,2.24-.71.42,0,.8.05,1.13.15h2.78ZM583.2,516.95c0,.43.18.76.55.99s.89.34,1.58.34c1.02,0,1.78-.15,2.27-.46.49-.31.74-.72.74-1.24,0-.44-.13-.74-.4-.91-.27-.17-.77-.25-1.52-.25h-1.46c-.55,0-.98.13-1.29.4s-.46.64-.46,1.13ZM583.86,510.15c0,.56.16.99.48,1.28s.76.43,1.33.43c1.19,0,1.78-.58,1.78-1.73s-.6-1.81-1.8-1.81c-.57,0-1.01.15-1.32.46s-.46.76-.46,1.37Z"/>
					<path class="st8" d="M592.39,507.58v5.21c0,.66.15,1.14.45,1.46s.76.48,1.4.48c.84,0,1.45-.23,1.84-.69.39-.46.58-1.21.58-2.25v-4.22h1.22v8.03h-1l-.18-1.08h-.07c-.25.39-.59.7-1.04.91-.44.21-.95.31-1.51.31-.98,0-1.71-.23-2.19-.7-.49-.46-.73-1.21-.73-2.23v-5.25h1.23Z"/>
					<path class="st8" d="M605.39,515.6l-.24-1.14h-.06c-.4.5-.8.84-1.2,1.02-.4.18-.89.27-1.49.27-.8,0-1.42-.21-1.87-.62-.45-.41-.68-.99-.68-1.75,0-1.62,1.3-2.47,3.89-2.55l1.36-.04v-.5c0-.63-.13-1.09-.41-1.39-.27-.3-.7-.45-1.3-.45-.67,0-1.43.21-2.27.62l-.37-.93c.4-.21.83-.38,1.3-.51.47-.12.94-.18,1.42-.18.96,0,1.67.21,2.13.64.46.42.69,1.11.69,2.04v5.48h-.9ZM602.64,514.75c.76,0,1.35-.21,1.78-.62.43-.42.65-1,.65-1.74v-.72l-1.22.05c-.97.03-1.66.18-2.09.45-.43.27-.64.68-.64,1.24,0,.44.13.77.4,1,.27.23.64.34,1.12.34Z"/>
					<path class="st8" d="M612.46,507.43c.36,0,.68.03.96.09l-.17,1.13c-.33-.07-.62-.11-.88-.11-.65,0-1.21.26-1.67.79-.46.53-.69,1.18-.69,1.97v4.31h-1.22v-8.03h1l.14,1.49h.06c.3-.52.66-.92,1.08-1.21s.88-.42,1.38-.42Z"/>
					<path class="st8" d="M620.38,514.53h-.07c-.56.82-1.4,1.22-2.52,1.22-1.05,0-1.87-.36-2.45-1.08-.58-.72-.88-1.74-.88-3.06s.29-2.35.88-3.08,1.4-1.1,2.45-1.1,1.92.4,2.5,1.19h.09l-.05-.58-.03-.56v-3.27h1.22v11.4h-.99l-.16-1.08ZM617.95,514.73c.83,0,1.43-.23,1.81-.68.37-.45.56-1.18.56-2.19v-.26c0-1.14-.19-1.95-.57-2.43-.38-.49-.98-.73-1.81-.73-.71,0-1.26.28-1.64.83-.38.55-.57,1.34-.57,2.35s.19,1.8.56,2.32.93.78,1.66.78Z"/>
					<path class="st8" d="M637.53,515.6h-1.23l-2.16-7.17c-.1-.32-.22-.72-.34-1.2-.13-.48-.19-.77-.2-.87-.11.64-.28,1.35-.51,2.12l-2.09,7.13h-1.23l-2.85-10.71h1.32l1.69,6.61c.23.93.41,1.77.51,2.52.13-.89.33-1.77.59-2.62l1.92-6.51h1.32l2.01,6.57c.23.76.43,1.61.59,2.56.09-.69.27-1.54.53-2.54l1.68-6.6h1.32l-2.86,10.71Z"/>
					<path class="st8" d="M641.79,505.4c0-.28.07-.48.21-.61.14-.13.31-.19.51-.19s.36.07.5.2.21.33.21.61-.07.48-.21.61c-.14.13-.31.2-.5.2-.21,0-.38-.07-.51-.2-.14-.13-.21-.34-.21-.61ZM643.11,515.6h-1.22v-8.03h1.22v8.03Z"/>
					<path class="st8" d="M651.15,514.53h-.07c-.56.82-1.4,1.22-2.52,1.22-1.05,0-1.87-.36-2.45-1.08-.58-.72-.88-1.74-.88-3.06s.29-2.35.88-3.08,1.4-1.1,2.45-1.1,1.92.4,2.5,1.19h.1l-.05-.58-.03-.56v-3.27h1.22v11.4h-.99l-.16-1.08ZM648.72,514.73c.83,0,1.43-.23,1.81-.68.37-.45.56-1.18.56-2.19v-.26c0-1.14-.19-1.95-.57-2.43-.38-.49-.98-.73-1.81-.73-.71,0-1.26.28-1.64.83-.38.55-.57,1.34-.57,2.35s.19,1.8.56,2.32.93.78,1.66.78Z"/>
					<path class="st8" d="M657.47,514.75c.21,0,.42-.02.62-.05.2-.03.36-.06.48-.1v.93c-.13.06-.33.12-.58.16-.26.04-.49.06-.69.06-1.55,0-2.33-.82-2.33-2.45v-4.78h-1.15v-.59l1.15-.51.51-1.71h.7v1.86h2.33v.95h-2.33v4.72c0,.48.12.85.34,1.11s.54.39.94.39Z"/>
					<path class="st8" d="M665.67,515.6v-5.19c0-.65-.15-1.14-.45-1.46s-.76-.48-1.4-.48c-.84,0-1.46.23-1.85.69-.39.46-.58,1.21-.58,2.26v4.2h-1.22v-11.4h1.22v3.45c0,.42-.02.76-.06,1.03h.07c.24-.39.58-.69,1.02-.91.44-.22.95-.33,1.51-.33.98,0,1.72.23,2.21.7.49.47.74,1.21.74,2.22v5.24h-1.22Z"/>
					<path class="st8" d="M558.22,529.49c0-1.29.19-2.5.57-3.63.38-1.13.92-2.12,1.64-2.97h1.19c-.7.94-1.23,1.98-1.58,3.11-.35,1.13-.53,2.29-.53,3.48s.18,2.32.54,3.43.88,2.14,1.56,3.06h-1.17c-.72-.83-1.26-1.8-1.64-2.91-.38-1.11-.56-2.3-.56-3.57Z"/>
					<path class="st8" d="M563.53,533.6v-10.71h1.25v10.71h-1.25Z"/>
					<path class="st8" d="M571.14,526.52h-2.04v7.08h-1.22v-7.08h-1.44v-.55l1.44-.44v-.45c0-1.97.86-2.96,2.58-2.96.42,0,.92.08,1.49.26l-.31.97c-.47-.15-.87-.23-1.2-.23-.46,0-.8.15-1.02.46-.22.3-.33.79-.33,1.47v.52h2.04v.95Z"/>
					<path class="st8" d="M583.42,533.6l-1.33-3.41h-4.29l-1.32,3.41h-1.26l4.23-10.75h1.05l4.21,10.75h-1.29ZM581.7,529.08l-1.25-3.32c-.16-.42-.33-.94-.5-1.55-.11.47-.26.98-.46,1.55l-1.26,3.32h3.46Z"/>
					<path class="st8" d="M589.73,533.75c-.52,0-1-.1-1.43-.29-.43-.19-.79-.49-1.09-.89h-.09c.06.47.09.91.09,1.33v3.3h-1.22v-11.63h.99l.17,1.1h.06c.31-.44.68-.76,1.09-.95s.89-.29,1.43-.29c1.06,0,1.89.36,2.46,1.09.58.73.87,1.75.87,3.06s-.29,2.34-.88,3.07c-.59.73-1.41,1.09-2.45,1.09ZM589.56,526.46c-.82,0-1.41.23-1.78.68s-.55,1.18-.56,2.17v.27c0,1.13.19,1.94.56,2.42.38.49.98.73,1.81.73.69,0,1.24-.28,1.63-.84.39-.56.59-1.33.59-2.32s-.2-1.77-.59-2.3c-.39-.54-.95-.8-1.66-.8Z"/>
					<path class="st8" d="M598.92,533.75c-.52,0-1-.1-1.43-.29-.43-.19-.79-.49-1.09-.89h-.09c.06.47.09.91.09,1.33v3.3h-1.22v-11.63h.99l.17,1.1h.06c.31-.44.68-.76,1.09-.95s.89-.29,1.43-.29c1.06,0,1.89.36,2.46,1.09.58.73.87,1.75.87,3.06s-.29,2.34-.88,3.07c-.59.73-1.41,1.09-2.45,1.09ZM598.75,526.46c-.82,0-1.41.23-1.78.68s-.55,1.18-.56,2.17v.27c0,1.13.19,1.94.56,2.42.38.49.98.73,1.81.73.69,0,1.24-.28,1.63-.84.39-.56.59-1.33.59-2.32s-.2-1.77-.59-2.3c-.39-.54-.95-.8-1.66-.8Z"/>
					<path class="st8" d="M605.6,533.6h-1.22v-11.4h1.22v11.4Z"/>
					<path class="st8" d="M608.07,523.4c0-.28.07-.48.21-.61.14-.13.31-.19.51-.19s.36.07.51.2.21.33.21.61-.07.48-.21.61c-.14.13-.31.2-.51.2-.21,0-.38-.07-.51-.2-.14-.13-.21-.34-.21-.61ZM609.39,533.6h-1.22v-8.03h1.22v8.03Z"/>
					<path class="st8" d="M615.18,533.75c-1.16,0-2.06-.36-2.7-1.07-.64-.71-.96-1.73-.96-3.04s.32-2.38.97-3.11c.65-.73,1.57-1.1,2.77-1.1.39,0,.77.04,1.16.12s.69.18.91.29l-.37,1.03c-.27-.11-.56-.2-.88-.27-.32-.07-.6-.11-.84-.11-1.63,0-2.45,1.04-2.45,3.12,0,.99.2,1.74.6,2.27.4.53.99.79,1.77.79.67,0,1.36-.14,2.06-.43v1.08c-.54.28-1.21.42-2.03.42Z"/>
					<path class="st8" d="M624.05,533.6l-.24-1.14h-.06c-.4.5-.8.84-1.2,1.02-.4.18-.89.27-1.49.27-.8,0-1.42-.21-1.87-.62-.45-.41-.68-.99-.68-1.75,0-1.62,1.3-2.47,3.89-2.55l1.36-.04v-.5c0-.63-.13-1.09-.41-1.39-.27-.3-.7-.45-1.3-.45-.67,0-1.43.21-2.27.62l-.37-.93c.4-.21.83-.38,1.3-.51.47-.12.94-.18,1.42-.18.96,0,1.67.21,2.13.64.46.42.69,1.11.69,2.04v5.48h-.9ZM621.3,532.75c.76,0,1.35-.21,1.78-.62.43-.42.65-1,.65-1.74v-.72l-1.22.05c-.97.03-1.66.18-2.09.45-.43.27-.64.68-.64,1.24,0,.44.13.77.4,1,.27.23.64.34,1.12.34Z"/>
					<path class="st8" d="M631.19,525.44c1.05,0,1.87.36,2.46,1.08.58.72.88,1.74.88,3.06s-.29,2.34-.88,3.07c-.59.73-1.41,1.09-2.45,1.09-.52,0-1-.1-1.43-.29-.43-.19-.79-.49-1.09-.89h-.09l-.26,1.03h-.87v-11.4h1.22v2.77c0,.62-.02,1.18-.06,1.67h.06c.57-.8,1.41-1.2,2.52-1.2ZM631.01,526.46c-.83,0-1.43.24-1.79.71-.37.48-.55,1.28-.55,2.41s.19,1.94.56,2.42c.38.49.98.73,1.81.73.75,0,1.3-.27,1.67-.82.37-.54.55-1.33.55-2.35s-.18-1.82-.55-2.34-.93-.77-1.7-.77Z"/>
					<path class="st8" d="M637.86,533.6h-1.22v-11.4h1.22v11.4Z"/>
					<path class="st8" d="M643.83,533.75c-1.19,0-2.12-.36-2.81-1.08-.69-.72-1.03-1.73-1.03-3.01s.32-2.32.96-3.08c.64-.76,1.49-1.14,2.57-1.14,1.01,0,1.8.33,2.39.99.59.66.88,1.54.88,2.62v.77h-5.53c.02.94.26,1.66.71,2.15.45.49,1.09.73,1.91.73.86,0,1.72-.18,2.56-.54v1.08c-.43.19-.84.32-1.22.4-.38.08-.85.12-1.39.12ZM643.5,526.45c-.64,0-1.16.21-1.54.63-.38.42-.61,1-.68,1.74h4.2c0-.77-.17-1.35-.51-1.76-.34-.41-.83-.61-1.46-.61Z"/>
					<path class="st8" d="M651.4,529.49c0,1.29-.19,2.48-.57,3.59-.38,1.11-.92,2.07-1.64,2.89h-1.17c.68-.92,1.2-1.94,1.56-3.06.36-1.12.54-2.27.54-3.44s-.18-2.35-.53-3.48c-.35-1.13-.88-2.16-1.58-3.11h1.19c.72.85,1.26,1.85,1.64,2.98.38,1.13.56,2.34.56,3.62Z"/>
				</g>
				<polyline class="st1" points="354.43 514.55 350.42 510.83 354.43 507.1"/>
				<polyline class="st1" points="854.13 507.1 858.13 510.83 854.13 514.55"/>
				<g>
					<line class="st1" x1="276.24" y1="429.03" x2="931.97" y2="429.03"/>
					<polyline class="st1" points="280.25 432.75 276.24 429.03 280.25 425.31"/>
					<polyline class="st1" points="927.97 425.31 931.97 429.03 927.97 432.75"/>
					<rect class="st20" x="547.52" y="421.01" width="115.2" height="16.03"/>
					<text class="st9" transform="translate(550.59 435.05)"><tspan class="st22" x="0" y="0">Cara</tspan><tspan class="st23" x="33.77" y="0">v</tspan><tspan class="st22" x="41.65" y="0">a</tspan><tspan class="st24" x="50.36" y="0">n</tspan><tspan class="st22" x="59.93" y="0"> </tspan><tspan class="st23" x="64.2" y="0">Wi</tspan><tspan class="st22" x="82.6" y="0">d</tspan><tspan class="st23" x="92.17" y="0">t</tspan><tspan x="97.82" y="0">h</tspan></text>
				</g>
				<line class="st3" x1="935.91" y1="412.02" x2="935.91" y2="589.08"/>
				<line class="st3" x1="272.11" y1="412.02" x2="272.11" y2="589.08"/>
				<line class="st3" x1="912.21" y1="192.07" x2="912.21" y2="284.02"/>
				<line class="st3" x1="912.21" y1="242.66" x2="610.47" y2="242.66"/>
				<line class="st3" x1="295.81" y1="192.07" x2="295.81" y2="284.02"/>
				<g>
					<g class="st21">
					<g class="st25">
						<rect class="st6" x="348.32" y="405.87" width="128.76" height="28.86"/>
					</g>
					</g>
					<g class="st21">
					<g class="st11">
						<path class="st6" d="M455.99,416.97c-2.45,0-4.44,1.99-4.44,4.44s1.99,4.44,4.44,4.44,4.44-1.99,4.44-4.44-1.99-4.44-4.44-4.44"/>
					</g>
					</g>
					<g class="st21">
					<g class="st14">
						<path class="st6" d="M369.41,416.97c-2.45,0-4.44,1.99-4.44,4.44s1.99,4.44,4.44,4.44,4.44-1.99,4.44-4.44-1.99-4.44-4.44-4.44"/>
					</g>
					</g>
					<g class="st21">
						<g class="st12">
							<rect class="st6" x="451.55" y="215.04" width="8.88" height="197.49"/>
						</g>
					</g>
					<g class="st21">
					<g class="st13">
						<rect class="st6" x="364.97" y="215.04" width="8.88" height="197.49"/>
					</g>
					</g>
					<g class="st21">
					<g class="st19">
						<rect class="st6" x="730.94" y="405.87" width="128.76" height="28.86"/>
					</g>
					</g>
					<g class="st21">
					<g class="st16">
						<path class="st6" d="M838.61,416.97c-2.45,0-4.44,1.99-4.44,4.44s1.99,4.44,4.44,4.44,4.44-1.99,4.44-4.44-1.99-4.44-4.44-4.44"/>
					</g>
					</g>
					<g class="st21">
					<g class="st15">
						<path class="st6" d="M752.03,416.97c-2.45,0-4.44,1.99-4.44,4.44s1.99,4.44,4.44,4.44,4.44-1.99,4.44-4.44-1.99-4.44-4.44-4.44"/>
					</g>
					</g>
					<g class="st21">
					<g class="st18">
						<rect class="st6" x="834.17" y="215.04" width="8.88" height="197.49"/>
					</g>
					</g>
					<g class="st21">
					<g class="st17">
						<rect class="st6" x="747.59" y="215.04" width="8.88" height="197.49"/>
					</g>
					</g>
				</g>
				<line class="st2" x1="859.24" y1="543.65" x2="859.24" y2="461.92"/>
				<line class="st4" x1="850.95" y1="453.64" x2="353.43" y2="453.64"/>
				<line class="st2" x1="349.31" y1="462.73" x2="349.31" y2="543.96"/>
				<line class="st4" x1="357.61" y1="552.24" x2="855.13" y2="552.24"/>
				<path class="st1" d="M855.13,552.24h4.11v-5.28M349.31,546.96v5.28h4.11M353.43,453.64h-4.11v5.28M859.24,458.92v-5.28h-4.11"/>
				<g>
					<line class="st1" x1="881.51" y1="563.25" x2="881.51" y2="243.67"/>
					<polyline class="st1" points="885.23 559.24 881.51 563.25 877.79 559.24"/>
					<polyline class="st1" points="877.79 247.68 881.51 243.67 885.23 247.68"/>
					<rect class="st20" x="873.49" y="282.59" width="16.03" height="135.74"/>
					<g>
					<path class="st8" d="M878.53,399.98l-3.41,1.33v4.29l3.41,1.32v1.26l-10.75-4.23v-1.05l10.75-4.21v1.29ZM874,401.7l-3.32,1.24c-.42.16-.94.33-1.55.5.47.11.98.26,1.55.46l3.32,1.26v-3.46Z"/>
					<path class="st8" d="M875.06,397.71h-1.11v-3.6h1.11v3.6Z"/>
					<path class="st8" d="M878.53,390.41v1.25h-10.71v-5.97h1.11v4.72h3.92v-4.44h1.11v4.44h4.58Z"/>
					<path class="st8" d="M870.35,380.07c0-.36.03-.68.09-.96l1.13.17c-.07.33-.11.62-.11.88,0,.65.26,1.21.79,1.67.53.46,1.18.69,1.97.69h4.31v1.22h-8.03v-1l1.49-.14v-.06c-.52-.3-.92-.66-1.21-1.08s-.42-.88-.42-1.38Z"/>
					<path class="st8" d="M878.53,372.3l-1.14.24v.06c.5.4.84.8,1.02,1.2.18.4.27.89.27,1.49,0,.8-.21,1.42-.62,1.87-.41.45-.99.68-1.75.68-1.62,0-2.47-1.3-2.55-3.89l-.04-1.36h-.5c-.63,0-1.09.14-1.39.41-.3.27-.45.7-.45,1.3,0,.67.21,1.43.62,2.27l-.93.37c-.21-.4-.38-.83-.51-1.3-.12-.47-.18-.94-.18-1.42,0-.96.21-1.67.64-2.13.42-.46,1.11-.69,2.04-.69h5.48v.9ZM877.67,375.05c0-.76-.21-1.35-.62-1.78-.42-.43-1-.65-1.74-.65h-.72l.05,1.22c.03.97.18,1.66.45,2.09.27.43.68.64,1.24.64.44,0,.77-.13,1-.4.23-.27.34-.64.34-1.12Z"/>
					<path class="st8" d="M878.53,358.29h-5.22c-.64,0-1.12.14-1.44.41-.32.27-.48.7-.48,1.27,0,.76.22,1.32.65,1.68s1.1.54,2.01.54h4.48v1.22h-5.22c-.64,0-1.12.14-1.44.41-.32.27-.48.7-.48,1.28,0,.76.23,1.32.68,1.67.46.35,1.2.53,2.25.53h4.21v1.22h-8.03v-.99l1.1-.2v-.06c-.39-.23-.7-.55-.92-.97-.22-.42-.33-.88-.33-1.4,0-1.25.45-2.08,1.36-2.46v-.06c-.42-.24-.75-.59-1-1.04s-.37-.97-.37-1.55c0-.91.23-1.59.7-2.04.47-.45,1.21-.68,2.24-.68h5.24v1.22Z"/>
					<path class="st8" d="M878.67,350.8c0,1.19-.36,2.12-1.08,2.81s-1.73,1.03-3.01,1.03-2.32-.32-3.08-.96c-.76-.64-1.14-1.49-1.14-2.57,0-1.01.33-1.8.99-2.39.66-.59,1.54-.88,2.62-.88h.77v5.53c.94-.02,1.66-.26,2.15-.71.49-.45.73-1.09.73-1.91,0-.86-.18-1.72-.54-2.56h1.08c.19.43.32.84.4,1.22.08.38.12.85.12,1.39ZM871.37,351.13c0,.64.21,1.16.63,1.54s1,.61,1.74.68v-4.2c-.77,0-1.35.17-1.76.51-.41.34-.61.83-.61,1.46Z"/>
					<path class="st8" d="M878.53,340.96h-10.71v-1.24h9.58v-4.72h1.13v5.97Z"/>
					<path class="st8" d="M878.67,329.59c0,1.19-.36,2.12-1.08,2.81-.72.69-1.73,1.03-3.01,1.03s-2.32-.32-3.08-.96c-.76-.64-1.14-1.49-1.14-2.57,0-1.01.33-1.8.99-2.39.66-.59,1.54-.88,2.62-.88h.77v5.53c.94-.02,1.66-.26,2.15-.71.49-.45.73-1.09.73-1.91,0-.86-.18-1.72-.54-2.56h1.08c.19.43.32.84.4,1.22.08.38.12.85.12,1.39ZM871.37,329.92c0,.64.21,1.16.63,1.54.42.38,1,.61,1.74.68v-4.2c-.77,0-1.35.17-1.76.51-.41.34-.61.83-.61,1.46Z"/>
					<path class="st8" d="M878.53,318.71h-5.19c-.65,0-1.14.15-1.46.45-.32.3-.48.76-.48,1.4,0,.84.23,1.46.68,1.85s1.2.59,2.25.59h4.21v1.22h-8.03v-.99l1.1-.2v-.06c-.4-.25-.7-.6-.92-1.05-.22-.45-.33-.95-.33-1.5,0-.97.23-1.69.7-2.18.47-.49,1.21-.73,2.24-.73h5.24v1.22Z"/>
					<path class="st8" d="M870.5,308.07h.77l.18,1.49c.17-.14.39-.26.67-.37.28-.11.59-.16.93-.16.79,0,1.41.27,1.88.81s.7,1.27.7,2.21c0,.24-.02.46-.06.67.27.52.62.78,1.03.78.22,0,.38-.09.49-.27.1-.18.16-.49.16-.93v-1.42c0-.87.18-1.54.55-2,.37-.47.9-.7,1.6-.7.89,0,1.57.36,2.03,1.07.47.71.7,1.75.7,3.12,0,1.05-.2,1.86-.59,2.43-.39.57-.94.85-1.66.85-.49,0-.91-.16-1.27-.47-.36-.31-.6-.75-.73-1.32-.09.21-.24.38-.43.52-.19.14-.42.21-.68.21-.29,0-.55-.08-.77-.23-.22-.16-.43-.4-.64-.74-.17.42-.46.75-.87,1.01-.41.26-.88.39-1.41.39-.88,0-1.56-.26-2.03-.79-.48-.53-.71-1.27-.71-2.24,0-.42.05-.8.15-1.14v-2.78ZM873.07,313.81c.56,0,.99-.16,1.28-.48s.43-.76.43-1.33c0-1.19-.58-1.78-1.73-1.78s-1.81.6-1.81,1.8c0,.57.15,1.01.46,1.32s.76.46,1.37.46ZM879.87,314.47c.43,0,.76-.18.99-.55s.34-.89.34-1.58c0-1.02-.15-1.78-.46-2.27-.31-.49-.72-.74-1.24-.74-.44,0-.74.13-.91.4-.17.27-.25.77-.25,1.52v1.46c0,.55.13.98.4,1.29s.64.46,1.13.46Z"/>
					<path class="st8" d="M877.67,303.46c0-.21-.02-.42-.05-.62-.03-.2-.06-.36-.1-.48h.93c.06.13.12.33.16.58.04.26.06.49.06.69,0,1.55-.82,2.33-2.45,2.33h-4.78v1.15h-.59l-.51-1.15-1.71-.51v-.7h1.86v-2.33h.95v2.33h4.72c.48,0,.85-.11,1.11-.34.26-.23.39-.54.39-.94Z"/>
					<path class="st8" d="M878.53,294.9h-5.19c-.65,0-1.14.15-1.46.45-.32.3-.48.76-.48,1.4,0,.84.23,1.46.69,1.85.46.39,1.21.58,2.26.58h4.2v1.22h-11.4v-1.22h3.45c.42,0,.76.02,1.03.06v-.07c-.39-.24-.69-.58-.91-1.02-.22-.44-.33-.95-.33-1.51,0-.98.23-1.72.7-2.21.47-.49,1.21-.74,2.22-.74h5.24v1.22Z"/>
					<path class="st8" d="M895.67,408.37c0-.21-.02-.42-.05-.62-.03-.2-.06-.36-.1-.48h.93c.06.13.12.33.16.58.04.26.06.49.06.69,0,1.55-.82,2.33-2.45,2.33h-4.78v1.15h-.59l-.51-1.15-1.71-.51v-.7h1.86v-2.33h.95v2.33h4.72c.48,0,.85-.11,1.11-.34.26-.23.39-.54.39-.94Z"/>
					<path class="st8" d="M892.5,398.74c1.31,0,2.33.33,3.07.99.74.66,1.1,1.57,1.1,2.73,0,.72-.17,1.36-.51,1.91-.34.56-.82.99-1.45,1.29-.63.3-1.37.45-2.21.45-1.31,0-2.33-.33-3.06-.98-.73-.65-1.09-1.56-1.09-2.72s.37-2.02,1.12-2.68c.75-.66,1.76-.99,3.03-.99ZM892.5,404.85c1.03,0,1.81-.21,2.34-.62.54-.41.81-1.01.81-1.81s-.27-1.4-.8-1.81c-.53-.41-1.32-.62-2.35-.62s-1.79.21-2.33.62c-.53.41-.79,1.02-.79,1.83s.26,1.4.78,1.8c.52.41,1.3.61,2.34.61Z"/>
					<path class="st8" d="M896.53,389.23v1.25h-9.6v3.39h-1.11v-8.03h1.11v3.39h9.6Z"/>
					<path class="st8" d="M892.5,377.48c1.31,0,2.33.33,3.07.99.74.66,1.1,1.57,1.1,2.73,0,.72-.17,1.35-.51,1.91-.34.56-.82.99-1.45,1.29-.63.3-1.37.45-2.21.45-1.31,0-2.33-.33-3.06-.98-.73-.65-1.09-1.56-1.09-2.72s.37-2.02,1.12-2.68c.75-.66,1.76-.99,3.03-.99ZM892.5,383.6c1.03,0,1.81-.21,2.34-.62.54-.41.81-1.01.81-1.81s-.27-1.4-.8-1.81c-.53-.41-1.32-.62-2.35-.62s-1.79.21-2.33.62c-.53.41-.79,1.02-.79,1.83s.26,1.4.78,1.8,1.3.61,2.34.61Z"/>
					<path class="st8" d="M896.67,371.62c0,.52-.1,1-.29,1.43-.19.43-.49.79-.89,1.09v.09c.47-.06.91-.09,1.33-.09h3.3v1.22h-11.63v-.99l1.1-.17v-.06c-.44-.31-.76-.68-.95-1.09s-.29-.89-.29-1.43c0-1.06.36-1.89,1.09-2.46.73-.58,1.75-.87,3.06-.87s2.34.29,3.07.88c.73.59,1.09,1.41,1.09,2.45ZM889.38,371.79c0,.82.23,1.41.68,1.78s1.18.55,2.17.56h.27c1.13,0,1.94-.19,2.42-.56.49-.38.73-.98.73-1.81,0-.69-.28-1.24-.84-1.63-.56-.39-1.33-.59-2.32-.59s-1.77.2-2.3.59c-.54.39-.8.95-.8,1.66Z"/>
					<path class="st8" d="M892.5,355.33c1.31,0,2.33.33,3.07.99.74.66,1.1,1.57,1.1,2.73,0,.72-.17,1.35-.51,1.91-.34.56-.82.99-1.45,1.29-.63.3-1.37.45-2.21.45-1.31,0-2.33-.33-3.06-.98-.73-.65-1.09-1.56-1.09-2.72s.37-2.02,1.12-2.68c.75-.66,1.76-.99,3.03-.99ZM892.5,361.45c1.03,0,1.81-.21,2.34-.62.54-.41.81-1.01.81-1.81s-.27-1.4-.8-1.81c-.53-.41-1.32-.62-2.35-.62s-1.79.21-2.33.62c-.53.41-.79,1.02-.79,1.83s.26,1.4.78,1.8,1.3.61,2.34.61Z"/>
					<path class="st8" d="M889.44,349.58v2.04h7.08v1.22h-7.08v1.44h-.55l-.44-1.44h-.45c-1.97,0-2.96-.86-2.96-2.59,0-.42.08-.92.26-1.49l.97.31c-.15.47-.23.87-.23,1.2,0,.46.15.8.46,1.02.3.22.79.33,1.47.33h.52v-2.04h.95Z"/>
					<path class="st8" d="M896.53,340.74v1.24h-9.6v3.39h-1.11v-8.03h1.11v3.39h9.6Z"/>
					<path class="st8" d="M892.5,329c1.31,0,2.33.33,3.07.99.74.66,1.1,1.57,1.1,2.73,0,.72-.17,1.35-.51,1.91-.34.56-.82.99-1.45,1.29-.63.3-1.37.46-2.21.46-1.31,0-2.33-.33-3.06-.98-.73-.65-1.09-1.56-1.09-2.72s.37-2.01,1.12-2.68c.75-.66,1.76-.99,3.03-.99ZM892.5,335.11c1.03,0,1.81-.21,2.34-.62.54-.41.81-1.01.81-1.81s-.27-1.4-.8-1.81c-.53-.41-1.32-.62-2.35-.62s-1.79.21-2.33.62c-.53.41-.79,1.02-.79,1.83s.26,1.4.78,1.8,1.3.61,2.34.61Z"/>
					<path class="st8" d="M896.53,320.31l-4.71,1.47c-.29.09-.94.27-1.96.52v.06c.85.2,1.51.37,1.98.51l4.7,1.52v1.41l-8.03,2.19v-1.28c2.02-.52,3.55-.91,4.61-1.18,1.05-.27,1.76-.43,2.13-.46v-.06c-.28-.05-.64-.14-1.08-.26-.44-.12-.79-.22-1.05-.31l-4.61-1.47v-1.32l4.61-1.44c.84-.27,1.54-.46,2.12-.56v-.06c-.18-.02-.45-.07-.81-.16-.37-.08-2.34-.59-5.91-1.53v-1.26l8.03,2.22v1.44Z"/>
					<path class="st8" d="M885.82,311.12v-3.02c0-1.42.21-2.45.64-3.08.42-.63,1.1-.95,2.01-.95.63,0,1.16.18,1.57.53.41.35.68.87.8,1.55h.07c.28-1.62,1.13-2.44,2.56-2.44.96,0,1.7.32,2.24.97.54.65.81,1.55.81,2.71v3.73h-10.71ZM890.4,309.87v-2.05c0-.88-.14-1.51-.41-1.9-.28-.39-.74-.58-1.39-.58-.6,0-1.03.22-1.3.64-.27.43-.4,1.11-.4,2.05v1.83h3.51ZM891.46,309.87h4.01v-2.23c0-.86-.17-1.51-.5-1.95-.33-.44-.86-.66-1.57-.66-.66,0-1.15.22-1.46.67-.31.45-.47,1.13-.47,2.04v2.13Z"/>
					<path class="st8" d="M896.53,296.64l-1.14.24v.06c.5.4.84.8,1.02,1.2.18.4.27.89.27,1.49,0,.8-.21,1.42-.62,1.87-.41.45-.99.68-1.75.68-1.62,0-2.47-1.3-2.55-3.89l-.04-1.36h-.5c-.63,0-1.09.13-1.39.41-.3.27-.45.7-.45,1.3,0,.67.21,1.43.62,2.27l-.93.37c-.21-.39-.38-.83-.51-1.3-.12-.47-.18-.94-.18-1.42,0-.96.21-1.67.64-2.13.42-.46,1.11-.69,2.04-.69h5.48v.9ZM895.67,299.39c0-.76-.21-1.35-.62-1.78-.42-.43-1-.65-1.74-.65h-.72l.05,1.21c.03.97.18,1.66.45,2.09.27.43.68.64,1.24.64.44,0,.77-.13,1-.4.23-.27.34-.64.34-1.12Z"/>
					<path class="st8" d="M888.35,289.58c0-.36.03-.68.09-.96l1.13.17c-.07.33-.11.62-.11.88,0,.65.26,1.21.79,1.67.53.46,1.18.69,1.97.69h4.31v1.22h-8.03v-1l1.49-.14v-.06c-.52-.3-.92-.66-1.21-1.08s-.42-.88-.42-1.38Z"/>
					</g>
				</g>
				<g>
					<line class="st1" x1="329.89" y1="563.43" x2="329.89" y2="453.85"/>
					<polyline class="st1" points="333.61 559.24 329.89 563.25 326.16 559.24"/>
					<polyline class="st1" points="326.16 457.68 329.89 453.67 333.61 457.68"/>
					<rect class="st20" x="307.05" y="471.15" width="40.75" height="74.97"/>
					<g>
					<path class="st8" d="M317.9,555.39v1.25h-9.6v3.39h-1.11v-8.03h1.11v3.39h9.6Z"/>
					<path class="st8" d="M313.88,543.28c1.31,0,2.33.33,3.07.99.74.66,1.1,1.57,1.1,2.73,0,.72-.17,1.36-.51,1.91-.34.56-.82.99-1.45,1.29-.63.3-1.37.45-2.21.45-1.31,0-2.33-.33-3.06-.98-.73-.65-1.09-1.56-1.09-2.72s.37-2.02,1.12-2.68c.75-.66,1.76-.99,3.03-.99ZM313.88,549.39c1.03,0,1.81-.21,2.34-.62.54-.41.81-1.01.81-1.81s-.27-1.4-.8-1.81c-.53-.41-1.32-.62-2.35-.62s-1.79.21-2.33.62c-.53.41-.79,1.02-.79,1.83s.26,1.4.78,1.8,1.3.61,2.34.61Z"/>
					<path class="st8" d="M317.05,538.2c0-.21-.02-.42-.05-.62-.03-.2-.06-.36-.1-.48h.93c.06.13.12.33.16.58.04.26.06.49.06.69,0,1.55-.82,2.33-2.45,2.33h-4.78v1.15h-.59l-.51-1.15-1.71-.51v-.7h1.86v-2.33h.95v2.33h4.72c.48,0,.85-.12,1.11-.34s.39-.54.39-.94Z"/>
					<path class="st8" d="M317.9,530.2l-1.14.24v.06c.5.4.84.8,1.02,1.2.18.4.27.89.27,1.49,0,.8-.21,1.42-.62,1.87-.41.45-.99.68-1.75.68-1.62,0-2.47-1.3-2.55-3.89l-.04-1.36h-.5c-.63,0-1.09.14-1.39.41-.3.27-.45.7-.45,1.3,0,.67.21,1.43.62,2.27l-.93.37c-.21-.4-.38-.83-.51-1.3-.12-.47-.18-.94-.18-1.42,0-.96.21-1.67.64-2.13.42-.46,1.11-.69,2.04-.69h5.48v.9ZM317.05,532.94c0-.76-.21-1.35-.62-1.78-.42-.43-1-.65-1.74-.65h-.72l.05,1.22c.03.97.18,1.66.45,2.09.27.43.68.64,1.24.64.44,0,.77-.13,1-.4.23-.27.34-.64.34-1.12Z"/>
					<path class="st8" d="M317.9,525.21v1.21h-11.4v-1.21h11.4Z"/>
					<path class="st8" d="M312.45,509.29c1.77,0,3.12.48,4.05,1.44.93.96,1.4,2.34,1.4,4.14v2.97h-10.71v-3.28c0-1.66.46-2.96,1.38-3.88s2.21-1.38,3.87-1.38ZM312.49,510.61c-1.4,0-2.45.35-3.16,1.05-.71.7-1.06,1.74-1.06,3.12v1.81h8.55v-1.52c0-1.48-.37-2.6-1.1-3.35-.73-.75-1.81-1.12-3.24-1.12Z"/>
					<path class="st8" d="M307.7,506.83c-.28,0-.48-.07-.61-.21-.13-.14-.19-.31-.19-.51s.07-.36.2-.51c.13-.14.33-.21.61-.21s.48.07.61.21c.13.14.2.31.2.51,0,.21-.07.38-.2.51-.13.14-.34.21-.61.21ZM317.9,505.51v1.22h-8.03v-1.22h8.03Z"/>
					<path class="st8" d="M315.71,497.39c.75,0,1.32.28,1.73.83s.61,1.34.61,2.34c0,1.06-.17,1.89-.51,2.49h-1.13c.2-.39.35-.8.46-1.24s.17-.87.17-1.28c0-.63-.1-1.12-.3-1.46-.2-.34-.51-.51-.93-.51-.31,0-.58.13-.8.41-.22.27-.48.8-.79,1.59-.28.75-.52,1.28-.73,1.59-.21.31-.44.55-.71.7-.26.15-.58.23-.94.23-.65,0-1.17-.27-1.55-.8-.38-.53-.57-1.26-.57-2.19,0-.87.18-1.71.53-2.54l.99.43c-.33.81-.5,1.54-.5,2.19,0,.58.09,1.01.27,1.3s.43.44.75.44c.21,0,.4-.05.55-.16s.29-.29.43-.53.33-.71.59-1.41c.35-.95.7-1.6,1.05-1.93s.79-.5,1.33-.5Z"/>
					<path class="st8" d="M317.05,492.46c0-.21-.02-.42-.05-.62-.03-.2-.06-.36-.1-.48h.93c.06.13.12.33.16.58.04.26.06.49.06.69,0,1.55-.82,2.33-2.45,2.33h-4.78v1.15h-.59l-.51-1.15-1.71-.51v-.7h1.86v-2.33h.95v2.33h4.72c.48,0,.85-.11,1.11-.34.26-.23.39-.54.39-.94Z"/>
					<path class="st8" d="M317.9,484.46l-1.14.24v.06c.5.4.84.8,1.02,1.2.18.4.27.89.27,1.49,0,.8-.21,1.42-.62,1.87-.41.45-.99.68-1.75.68-1.62,0-2.47-1.3-2.55-3.89l-.04-1.36h-.5c-.63,0-1.09.13-1.39.41-.3.27-.45.7-.45,1.3,0,.67.21,1.43.62,2.27l-.93.37c-.21-.39-.38-.83-.51-1.3-.12-.47-.18-.94-.18-1.42,0-.96.21-1.67.64-2.13.42-.46,1.11-.69,2.04-.69h5.48v.9ZM317.05,487.21c0-.76-.21-1.35-.62-1.78-.42-.43-1-.65-1.74-.65h-.72l.05,1.21c.03.97.18,1.66.45,2.09.27.43.68.64,1.24.64.44,0,.77-.13,1-.4.23-.27.34-.64.34-1.12Z"/>
					<path class="st8" d="M317.9,475.2h-5.19c-.65,0-1.14.15-1.46.45-.32.3-.48.76-.48,1.4,0,.84.23,1.46.68,1.85s1.2.59,2.25.59h4.21v1.22h-8.03v-.99l1.1-.2v-.06c-.4-.25-.7-.6-.92-1.05-.22-.45-.33-.95-.33-1.5,0-.97.23-1.69.7-2.18.47-.49,1.21-.73,2.24-.73h5.24v1.22Z"/>
					<path class="st8" d="M318.05,467.92c0,1.16-.36,2.06-1.07,2.7-.71.64-1.73.96-3.04.96s-2.38-.32-3.11-.97c-.73-.65-1.1-1.57-1.1-2.77,0-.38.04-.77.12-1.16s.18-.69.29-.91l1.03.37c-.11.27-.2.56-.27.88-.07.32-.11.6-.11.84,0,1.63,1.04,2.45,3.12,2.45.99,0,1.74-.2,2.27-.6.53-.4.79-.99.79-1.77,0-.67-.14-1.35-.43-2.06h1.08c.28.54.42,1.21.42,2.03Z"/>
					<path class="st8" d="M318.05,460.24c0,1.19-.36,2.12-1.08,2.81-.72.69-1.73,1.03-3.01,1.03s-2.32-.32-3.08-.96c-.76-.64-1.14-1.49-1.14-2.57,0-1.01.33-1.8.99-2.39.66-.59,1.54-.88,2.62-.88h.77v5.53c.94-.02,1.66-.26,2.15-.71.49-.45.73-1.09.73-1.91,0-.86-.18-1.72-.54-2.56h1.08c.19.43.32.84.4,1.22.08.38.12.85.12,1.39ZM310.75,460.57c0,.64.21,1.16.63,1.54.42.38,1,.61,1.74.68v-4.2c-.77,0-1.35.17-1.76.51-.41.34-.61.83-.61,1.46Z"/>
					<path class="st8" d="M335.9,539.96v1.25h-10.71v-5.97h1.11v4.72h3.92v-4.44h1.11v4.44h4.58Z"/>
					<path class="st8" d="M327.73,529.62c0-.36.03-.68.09-.96l1.13.17c-.07.33-.11.62-.11.88,0,.65.26,1.21.79,1.67.53.46,1.18.69,1.97.69h4.31v1.22h-8.03v-1l1.49-.14v-.06c-.52-.3-.92-.66-1.21-1.08s-.42-.88-.42-1.38Z"/>
					<path class="st8" d="M331.88,519.87c1.31,0,2.33.33,3.07.99.74.66,1.1,1.57,1.1,2.73,0,.72-.17,1.35-.51,1.91-.34.56-.82.99-1.45,1.29-.63.3-1.37.46-2.21.46-1.31,0-2.33-.33-3.06-.98-.73-.65-1.09-1.56-1.09-2.72s.37-2.01,1.12-2.68c.75-.66,1.76-.99,3.03-.99ZM331.88,525.99c1.03,0,1.81-.21,2.34-.62.54-.41.81-1.01.81-1.81s-.27-1.4-.8-1.81c-.53-.41-1.32-.62-2.35-.62s-1.79.21-2.33.62c-.53.41-.79,1.02-.79,1.83s.26,1.4.78,1.8,1.3.61,2.34.61Z"/>
					<path class="st8" d="M335.9,507.15h-5.22c-.64,0-1.12.14-1.44.41-.32.27-.48.7-.48,1.28,0,.76.22,1.32.65,1.68s1.1.54,2.01.54h4.48v1.22h-5.22c-.64,0-1.12.14-1.44.41-.32.27-.48.7-.48,1.28,0,.76.23,1.32.68,1.67.46.35,1.2.53,2.25.53h4.21v1.22h-8.03v-.99l1.1-.2v-.06c-.39-.23-.7-.55-.92-.97-.22-.42-.33-.88-.33-1.4,0-1.25.45-2.08,1.36-2.46v-.06c-.42-.24-.75-.59-1-1.04s-.37-.97-.37-1.55c0-.91.23-1.59.7-2.04.47-.45,1.21-.68,2.24-.68h5.24v1.21Z"/>
					<path class="st8" d="M325.19,492.87v-1.34l10.71,3.86v1.23l-10.71,3.84v-1.32l6.93-2.46c.8-.28,1.57-.51,2.32-.67-.79-.18-1.58-.41-2.37-.69l-6.88-2.45Z"/>
					<path class="st8" d="M335.9,485.31l-1.14.24v.06c.5.4.84.8,1.02,1.2.18.4.27.89.27,1.49,0,.8-.21,1.42-.62,1.87-.41.45-.99.68-1.75.68-1.62,0-2.47-1.3-2.55-3.89l-.04-1.36h-.5c-.63,0-1.09.13-1.39.41-.3.27-.45.7-.45,1.3,0,.67.21,1.43.62,2.27l-.93.37c-.21-.39-.38-.83-.51-1.3-.12-.47-.18-.94-.18-1.42,0-.96.21-1.67.64-2.13.42-.46,1.11-.69,2.04-.69h5.48v.9ZM335.05,488.05c0-.76-.21-1.35-.62-1.78-.42-.43-1-.65-1.74-.65h-.72l.05,1.21c.03.97.18,1.66.45,2.09.27.43.68.64,1.24.64.44,0,.77-.13,1-.4.23-.27.34-.64.34-1.12Z"/>
					<path class="st8" d="M335.9,476.41h-5.19c-.65,0-1.14.15-1.46.45-.32.3-.48.76-.48,1.4,0,.84.23,1.46.68,1.85s1.2.59,2.25.59h4.21v1.22h-8.03v-.99l1.1-.2v-.06c-.4-.25-.7-.6-.92-1.05-.22-.45-.33-.95-.33-1.5,0-.97.23-1.69.7-2.18.47-.49,1.21-.73,2.24-.73h5.24v1.22Z"/>
					</g>
				</g>
				<g class="st21">
					<polygon class="st6" points="311.01 215.04 289.01 564.08 919.01 564.08 893.01 215.04 311.01 215.04"/>
				</g>
				<circle class="st10" cx="604.28" cy="242.66" r="7.45"/>
				</svg>


			<?php } ?>

        </a>
	</div>

	<!-- Data Table -->
	<div style="margin-top:30px; text-align:left; font-size:16px;">
		<h3 style="margin-bottom:10px; text-align:center; "> Measurements</h3>
		<table style="margin:0 auto; border-collapse:collapse; font-size:15px;">

			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Phone:</td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $customer_phone ?: '-' ); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">SS Width (mm):</td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $caravan_width_mm ?: '-' ); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">SS Length (mm):</td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $caravan_length_mm ?: '-' ); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Towing Vehicle BarWidth (mm):</td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $bar_width_mm ?: '-' ); ?></td>
			</tr>

			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Vinyl Insert Width (mm):</td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $vinyl_insert_width_mm ?: '-' ); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Vinyl Insert Length (mm):</td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $vinyl_insert_height_mm ?: '-' ); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Stoneguard Width (mm):</td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $factory_stoneguard_width ?: '-' ); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Stoneguard Length (mm):</td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $factory_stoneguard_height ?: '-' ); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Toolbox Width (mm):</td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $toolbox_width_mm ?: '-' ); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Toolbox Length (mm):</td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $toolbox_height_mm ?: '-' ); ?></td>
			</tr>
			<?php if($sts_var_proposed_date_of_delivery){ ?>
				<tr>
					<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Proposed Date of Delivery:</td>
					<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo date( 'd-F-Y', $sts_var_proposed_date_of_delivery ?: '-' ); ?></td>
				</tr>
			<?php } ?>
		</table>
	</div>

	<!-- output these images over here -->
	<div style="margin-top:30px;"></div>

    <!-- Popup container -->
    <div id="order-popup" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:9999;">
    	<!-- <div id="order-popup" style=""> -->
        <div class="ss-invoice-popup" style="">
            <a href="#" id="close-popup" style="position:absolute; top:15px; right:20px; font-size:20px; text-decoration:none;">✖</a>
				<!-- popup First page -->

				<div class="inv-one">
					<div class="invoice-header-section d-flex justify-content-between " >
						<div class="invoice-logo inv-column">
							<img src="<?php echo get_template_directory_uri(); ?>/assets/src/images/invoice-gaurd.png" style="max-width:600px;cursor:pointer;" />
						</div>
						<div class="invoice-bussiness-details inv-column">
							<div class="heading-6">Stone Stomper</div>
							<p>PO Box 204, Port Noarlunga, SA 5167 <br> Factory location:  Lonsdale SA <br>
							<strong>
								Email:
							</strong>
							<br>
							<a href="mailto:sales@stonestomper.com.au"></a>sales@stonestomper.com.au</p>
						</div>
						<div class="invoice-right-column inv-column">
							<h3>Quote/<br>Invoice</h2>
							<table>
								<tr><td><strong>DATE:</strong> <?php echo esc_html( $order_date ); ?> </td></tr>
								<tr><td><strong>INV#:</strong> <?php echo esc_html( $order_id ); ?> </td></tr>
							</table>
						</div>
					</div>
					<br>
						<br>
					<div class="inv-order-details">
						<div class="customer-details inv-order-row">
							<strong>Name: </strong><?php echo esc_html( $customer_name ); ?>
							&nbsp;&nbsp;&nbsp;
							<strong>Phone: </strong><?php echo esc_html( $customer_phone ); ?>
							&nbsp;&nbsp;&nbsp;
							<strong>Email: </strong><?php echo esc_html( $customer_email ); ?>
						</div>
						<br>
						<div class="customer-details inv-order-row">
							<strong>Delivery Address: </strong><?php echo html_entity_decode( $delivery_address ); ?>
						</div>
						<br>
						<div class="customer-details inv-order-row">
							<strong>Delivery Instructions/Authority to Leave:</strong>
							<?php echo ! empty( $delivery_instructions ) ? esc_html( $delivery_instructions ) : 'No'; ?>
						</div>
						<br>
						<div class="customer-details inv-order-row">
							<?php if($caravan_make){ ?>
								<strong>Trailer Make: </strong><?php echo esc_html( $caravan_make ); ?>
							<?php } ?>
							&nbsp;&nbsp;&nbsp;
							<?php if($vehicle_make){ ?>
								<strong>Vehicle Make: </strong><?php echo esc_html( $vehicle_make ); ?>
							<?php } ?>
						</div>
						<br>
						<div class="customer-details inv-order-row">
							<strong>Bar Option: </strong>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<strong>Date Required: </strong><?php echo esc_html( $order_date ); ?>
						</div>

					</div>

					<br>

					<table class="order-table" style="width:100%; border-collapse:collapse;">
						<tr>
							<td style="text-align:center;"><strong>Quantity</strong></td>
							<td style="text-align:center;"><strong>Description</strong></td>
							<td style="text-align:center;"><strong>Unit Price</strong></td>
							<td style="text-align:center;"><strong>Total</strong></td>
						</tr>

						<?php if ( $products ) { ?>

							<?php foreach( $products as $key =>  $product ){ ?>
								<tr>
									<td style="text-align:center;"><?php echo $key; ?></td>
									<td style="text-align:center;"><?php echo esc_html( $product['name'] ); ?></td>
									<td style="text-align:center;">$<?php echo wc_format_decimal( $product['total'] / $product['quantity'], 2 ); ?></td>
									<td style="text-align:center;">$<?php echo wc_format_decimal( $product['total'], 2 ); ?></td>
								</tr>
							<?php } ?>
						<?php }	?>
						<br>

						<tr>
							<td style="text-align:center;"><!-- remain empty --></td>
							<td style="text-align:center;"><!-- remain empty --></td>
							<td style="text-align:center;"><strong>Delivery</strong></td>
							<td style="text-align:center;"><?php echo esc_html( $delivery_cost ); ?></td>
						</tr>

						<tr>
							<td style="text-align:center;"><!-- remain empty --></td>
							<td style="text-align:center;"><!-- remain empty --></td>
							<td style="text-align:center;"><strong>Total Due</strong></td>
							<td style="text-align:center;"><?php echo esc_html( $order_total ); ?></td>
						</tr>
						<tr>
							<td style="text-align:center;"><!-- remain empty --></td>
							<td style="text-align:center;"><!-- remain empty --></td>
							<td style="text-align:center;">GST (included)</td>
							<td>-</td>
						</tr>
					</table>
					<div class="stone-stomper-vector">
						<div class="ss-width">
							<span>
								<?php echo esc_html( $caravan_width_mm ?: '1900' ); ?>
							</span>
						</div>
						<div class="ss-length">
							<span>
								<?php echo esc_html( $bar_width_mm ?: '1900' ); ?>
							</span>
						</div>
						<img src="<?php echo get_template_directory_uri(); ?>/assets/src/images/stone-stomper-vector.png" style="max-width:600px;cursor:pointer;" />
						<div class="towing-vehicle-bar-width">
							<span>
								<?php echo esc_html( $bar_width_mm ?: '1900' ); ?>
							</span>
						</div>
					</div>
					<div class="thanks-message">THANK YOU FOR YOUR BUSINESS</div>
				</div>
				<!-- popup second page -->
				<div class="inv-two office-use">

					<div class="invoice-header-section d-flex justify-content-between " >
						<div class="invoice-logo inv-column">
							<img src="<?php echo get_template_directory_uri(); ?>/assets/src/images/invoice-gaurd.png" style="max-width:600px;cursor:pointer;" />
						</div>
						<div class="invoice-bussiness-details inv-column">
							<div class="h4">Stone Stomper</div>
							<p>PO Box 204, Port Noarlunga, SA 5167 <br> Factory location:  Lonsdale SA <br>
							<strong>
								Email:
							</strong>
							<br>
							<a href="mailto:sales@stonestomper.com.au"></a>sales@stonestomper.com.au</p>
						</div>
						<div class="invoice-right-column inv-column">
							<h3>Quote/Invoice</h2>
							<table>
								<tr><td><strong>DATE:</strong> <?php echo esc_html( $order_date ); ?> </td></tr>
								<tr><td><strong>INV#:</strong> <?php echo esc_html( $order_id ); ?> </td></tr>
								<tr><td><strong>P/O#:</strong> <?php echo esc_html( $order_id ); ?> </td></tr>
							</table>
						</div>
					</div>
					<br>
						<br>
					<div class="inv-order-details">
						<div class="customer-details inv-order-row">
							<strong>Name: </strong><?php echo esc_html( $customer_name ); ?>
							&nbsp;&nbsp;&nbsp;
							<strong>Phone: </strong><?php echo esc_html( $customer_phone ); ?>
							&nbsp;&nbsp;&nbsp;
							<strong>Email: </strong><?php echo esc_html( $customer_email ); ?>
						</div>
						<br>
						<div class="customer-details inv-order-row">
							<strong>Delivery Address: </strong><?php echo html_entity_decode( $delivery_address ); ?>
						</div>
						<br>
						<div class="customer-details inv-order-row">
							<strong>Delivery Instructions/Authority to Leave:</strong>
							<?php echo ! empty( $delivery_instructions ) ? esc_html( $delivery_instructions ) : 'No'; ?>
						</div>
						<br>
						<div class="customer-details inv-order-row">
							<?php if($caravan_make){ ?>
								<strong>Trailer Make: </strong><?php echo esc_html( $caravan_make ); ?>
							<?php } ?>
							&nbsp;&nbsp;&nbsp;
							<?php if($vehicle_make){ ?>
								<strong>Vehicle Make: </strong><?php echo esc_html( $vehicle_make ); ?>
							<?php } ?>
						</div>
						<br>
						<div class="customer-details inv-order-row">
							<strong>Bar Option: </strong>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<strong>Date Required: </strong><?php echo esc_html( $order_date ); ?>
						</div>

						<!-- table -->
						<table class="order-table" style="width:100%; border-collapse:collapse;">

							<tr>
								<td style="text-align:left;">Bar Option <span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_bar_option); ?> </span></td>
								<td style="text-align:left;"></td>
							</tr>
							<tr>
								<td style="text-align:left;">Bar Bend <span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_bar_bend); ?> </span></td>
								<td style="text-align:left;"></td>
							</tr>
							<tr>
								<td style="text-align:left;">SS Length Adjustment <span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_ss_length_adj); ?> </span></td>
								<td style="text-align:left;"></td>
							</tr>
							<tr>
								<td style="text-align:left;">Cut Out <span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_cut_out); ?> </span></td>
								<td style="text-align:left;"></td>
							</tr>
							<tr>
								<td style="text-align:left;">Break Foam <span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_break_form); ?> </span></td>
								<td style="text-align:left;"></td>
							</tr>
							<tr>
								<td style="text-align:left;">Hr Foam <span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_hr_form); ?> </span></td>
								<td style="text-align:left;"></td>
							</tr>

						</table>
						<div class="stone-stomper-vector">
							<div class="ss-width">
								<span>
									<?php echo esc_html( $caravan_width_mm ?: '1900' ); ?>
								</span>
							</div>
							<div class="ss-length">
								<span>
									<?php echo esc_html( $bar_width_mm ?: '1900' ); ?>
								</span>
							</div>
							<img src="<?php echo get_template_directory_uri(); ?>/assets/src/images/stone-stomper-vector.png" style="max-width:600px;cursor:pointer;" />
							<div class="towing-vehicle-bar-width">
								<span>
									<?php echo esc_html( $bar_width_mm ?: '1900' ); ?>
								</span>
							</div>
						</div>

					</div>
				</div>

				<div style="text-align:center; margin-top:25px;">
					<a href="<?php echo admin_url( 'admin-ajax.php?action=download_customer_pdf&post_id=' . $post->ID ); ?>" target="_blank" class="button button-primary">Download PDF</a>
				</div>
        </div>
    </div>

	<script>
		jQuery(document).ready(function () {
		const allImageGroups = jQuery(".hitch-images, .rear-images, .front-images");

		if (!jQuery(".image-lightbox").length) {
			jQuery("body").append(`
				<div class="image-lightbox">
					<div class="lightbox-inner">
						<img src="" alt="" class="lightbox-img">
						<div class="lightbox-controls">
							<span class="lightbox-prev">&#10094;</span>
							<span class="lightbox-next">&#10095;</span>
							<span class="lightbox-close">&times;</span>
						</div>
					</div>
				</div>
			`);
		}

		const lightbox = jQuery(".image-lightbox");
		const lightboxImg = jQuery(".lightbox-img");
		let currentGroup = null;
		let currentIndex = 0;

		function showImage(index) {
			const src = jQuery(currentGroup[index]).attr("src");
			lightboxImg.attr("src", src);
			currentIndex = index;
			lightbox.addClass("active");
		}

		function closeLightbox() {
			lightbox.removeClass("active");
			setTimeout(() => {
				lightboxImg.attr("src", "");
				currentGroup = null;
			}, 400);
		}

		function showNext() {
			if (!currentGroup) return;
			currentIndex = (currentIndex + 1) % currentGroup.length;
			showImage(currentIndex);
		}

		function showPrev() {
			if (!currentGroup) return;
			currentIndex = (currentIndex - 1 + currentGroup.length) % currentGroup.length;
			showImage(currentIndex);
		}

		allImageGroups.each(function () {
			const images = jQuery(this).find("img");
			images.on("click", function () {
				currentGroup = images;
				showImage(images.index(this));
			});
		});

		jQuery(".lightbox-close").on("click", closeLightbox);
		jQuery(".lightbox-next").on("click", showNext);
		jQuery(".lightbox-prev").on("click", showPrev);

		jQuery(document).on("keydown", function (e) {
			if (lightbox.hasClass("active")) {
				if (e.key === "Escape") closeLightbox();
				if (e.key === "ArrowRight") showNext();
				if (e.key === "ArrowLeft") showPrev();
			}
		});

		lightbox.on("click", function (e) {
			if (jQuery(e.target).is(".image-lightbox")) closeLightbox();
		});
	});


		jQuery(document).ready(function($){


			jQuery('#show-order-popup').on('click', function(e){
				e.preventDefault();
				jQuery('#order-popup').fadeIn(200);
			});

			jQuery('#close-popup').on('click', function(e){
				e.preventDefault();
				jQuery('#order-popup').fadeOut(200);
			});

			jQuery(document).on('click', '#order-popup', function(e){
				if( e.target.id === 'order-popup' ) {
				jQuery(this).fadeOut(200);
				}
			});

		});
	</script>
    <?php
}

add_action( 'add_meta_boxes', function() {
    add_meta_box(
        'towing_svg_preview',        // ID
        'Stone Stomper Preview',       // Title
        'show_towing_svg_in_editor', // Callback
        'customer',                  // Post type (CPT slug)
        'normal',                      // Position (side or normal)
        'low'                       // Priority
    );
});

use Dompdf\Dompdf;

function download_customer_pdf_callback() {
    $post_id = intval( $_GET['post_id'] ?? 0 );
    if ( ! $post_id ) {
        wp_die( 'Invalid request.' );
    }

    $order_id = get_post_meta( $post_id, 'order_id', true );
    $order    = wc_get_order( $order_id );

    if ( ! $order ) {
        wp_die( 'Order not found.' );
    }

    $order_date       = $order->get_date_created()->date_i18n('Y-m-d');
    $customer_name    = $order->get_formatted_billing_full_name();
    $customer_phone   = $order->get_billing_phone();
    $customer_email   = $order->get_billing_email();
    $delivery_address = $order->get_formatted_shipping_address();
    $delivery_cost    = $order->get_shipping_total();
    $order_total      = $order->get_total();
    $inv_logo      = get_template_directory_uri().'/assets/src/images/invoice-gaurd.png';

    $product_rows = '';
    foreach ( $order->get_items() as $item ) {
        $name     = $item->get_name();
        $qty      = $item->get_quantity();
        $total    = wc_format_decimal( $item->get_total(), 2 );
        $unit     = wc_format_decimal( $item->get_total() / $qty, 2 );
        $product_rows .= "
            <tr>
                <td style='text-align:center;'>$qty</td>
                <td style='text-align:center;'>$name</td>
                <td style='text-align:center;'>$$unit</td>
                <td style='text-align:center;'>$$total</td>
            </tr>";
    }

    $html = "

		<div class='invoice-header-section d-flex justify-content-between ' >
				<div class='invoice-logo inv-column'>
					<img src='{$inv_logo}' style='max-width:600px;cursor:pointer;' />
				</div>
				<div class='invoice-bussiness-details inv-column'>
					<div class='h4'>Stone Stomper</div>
					<p>PO Box 204, Port Noarlunga, SA <br> 5167 <br> Factory location:  Lonsdale SA <br>
					<strong>
						Email:
					</strong>
					<br>
					<a href='mailto:sales@stonestomper.com.au'></a>sales@stonestomper.com.au</p>
				</div>
				<div class='invoice-right-column inv-column'>
					<h3>Quote/Invoice</h2>
					<table>
						<tr><td><strong>DATE:</strong> <?php echo esc_html( $order_date ); ?> </td></tr>
						<tr><td><strong>INV#:</strong> <?php echo esc_html( $order_id ); ?> </td></tr>
						<tr><td><strong>P/O#:</strong>  </td></tr>
					</table>
				</div>
			</div>

    <h2 style='text-align:center;'>Customer Order Summary</h2>
    <p><strong>Date:</strong> {$order_date}<br>
       <strong>Customer:</strong> {$customer_name}<br>
       <strong>Email:</strong> {$customer_email}<br>
       <strong>Phone:</strong> {$customer_phone}<br>
       <strong>Address:</strong> {$delivery_address}</p>

    <table border='1' cellspacing='0' cellpadding='5' width='100%' style='border-collapse:collapse;'>
        <thead>
            <tr>
                <th>Qty</th>
                <th>Description</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>$product_rows</tbody>
        <tfoot>
            <tr>
                <td colspan='3' style='text-align:right;'><strong>Delivery</strong></td>
                <td style='text-align:center;'>$$delivery_cost</td>
            </tr>
            <tr>
                <td colspan='3' style='text-align:right;'><strong>Total Due</strong></td>
                <td style='text-align:center;'>$$order_total</td>
            </tr>
        </tfoot>
    </table>";

    // Load Dompdf
    require_once __DIR__ . '/vendor/autoload.php';

    $dompdf = new Dompdf();
    $dompdf->loadHtml( $html );
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Output to browser
    $dompdf->stream( "customer-order-{$post_id}.pdf", [ 'Attachment' => false ] );
    exit;
}

add_action( 'wp_ajax_download_customer_pdf', 'download_customer_pdf_callback' );
add_action( 'wp_ajax_nopriv_download_customer_pdf', 'download_customer_pdf_callback' );








// order by date

add_action( 'pre_get_posts', function( $query ) {
	if ( is_admin() && $query->is_main_query() ) {
		$screen = get_current_screen();
		if ( 'customer' === $screen->post_type ) {
			$query->set( 'orderby', 'date' );
			$query->set( 'order', 'DESC' );
		}
	}
});
