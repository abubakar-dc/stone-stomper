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


	error_log(print_r($data,true));

	$cust_name    = isset( $data['customer_name'] )   ? sanitize_text_field( $data['customer_name'] )   : '';
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
	$support_pockets     = sts_bool( $data['support_pockets'] ?? '' ) ? 'yes' : 'no';


	// Photos (hidden inputs hold JSON arrays of IDs)
	$photos = array(
		'hitch_ids' => sts_to_int_array( $data['hitch_ids'] ?? array() ),
		'rear_ids'  => sts_to_int_array( $data['rear_ids'] ?? array() ),
		'front_ids' => sts_to_int_array( $data['front_ids'] ?? array() ),
	);

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
	update_post_meta( $post_id, 'sts_var_caravan_support_pockets', $support_pockets );
	update_post_meta( $post_id, 'factory_stoneguard_width', $stoneguard_length_mm );
	update_post_meta( $post_id, 'factory_stoneguard_height', $stoneguard_width_mm );
	update_post_meta( $post_id, 'vinyl_insert_width_mm', $vinyl_width_mm );
	update_post_meta( $post_id, 'vinyl_insert_height_mm', $vinyl_length_mm );
	update_post_meta( $post_id, 'toolbox_width_mm', $toolbox_width_mm );
	update_post_meta( $post_id, 'toolbox_height_mm', $toolbox_length_mm );

	// update_post_meta( $post_id, 'final_details', $final );

	// Optionally set a featured image from the first uploaded photo if any
	$first_img = 0;
	foreach ( array( 'hitch_ids','rear_ids','front_ids' ) as $k ) {
		if ( ! empty( $photos[ $k ] ) ) { $first_img = intval( $photos[ $k ][0] ); break; }
	}
	if ( $first_img > 0 ) {
		set_post_thumbnail( $post_id, $first_img );
	}

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
		$customer_phone   = $order->get_billing_phone();
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


	$measure_barwidth_mm = get_post_meta( $post->ID, 'measure_barwidth_mm', true );
    $bar_width_mm        = get_post_meta( $post->ID, 'bar_width_mm', true );
    $caravan_width_mm    = get_post_meta( $post->ID, 'caravan_width_mm', true );
    $vehicle_make    = get_post_meta( $post->ID, 'vehicle_make', true );
    $caravan_make    = get_post_meta( $post->ID, 'caravan_make', true );

    ?>

    <div style="text-align:center; padding:20px;">
        <a href="#" class="stone-stomper-vector" id="show-order-popup">
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
        </a>
		<div style="margin-top:30px; text-align:center; font-size:16px;">
			<h3 style="margin-bottom:10px;"> Measurements</h3>
			<table style="margin:0 auto; border-collapse:collapse; font-size:15px;">
				<tr>
					<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Measure Barwidth (mm):</td>
					<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $measure_barwidth_mm ?: '-' ); ?></td>
				</tr>
				<tr>
					<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Bar Width (mm):</td>
					<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $bar_width_mm ?: '-' ); ?></td>
				</tr>
				<tr>
					<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Caravan Width (mm):</td>
					<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $caravan_width_mm ?: '-' ); ?></td>
				</tr>
			</table>
        </div>
    </div>

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
							<div class="h4">Stone Stomper</div>
							<p>PO Box 204, Port Noarlunga, SA <br> 5167 <br> Factory location:  Lonsdale SA <br>
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
					<div class="tanks-message">THANK YOU FOR YOUR BUSINESS</div>
				</div>
				<!-- popup second page -->
				<div class="inv-two office-use">

					<div class="invoice-header-section d-flex justify-content-between " >
						<div class="invoice-logo inv-column">
							<img src="<?php echo get_template_directory_uri(); ?>/assets/src/images/invoice-gaurd.png" style="max-width:600px;cursor:pointer;" />
						</div>
						<div class="invoice-bussiness-details inv-column">
							<div class="h4">Stone Stomper</div>
							<p>PO Box 204, Port Noarlunga, SA <br> 5167 <br> Factory location:  Lonsdale SA <br>
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
								<td style="text-align:center;">Fittings 150mm</td>
								<td style="text-align:center;"></td>
							</tr>
							<tr>
								<td style="text-align:center;">Fittings 150mm</td>
								<td style="text-align:center;"></td>
							</tr>
							<tr>
								<td style="text-align:center;">Fittings 150mm</td>
								<td style="text-align:center;"></td>
							</tr>
							<tr>
								<td style="text-align:center;">Fittings 150mm</td>
								<td style="text-align:center;"></td>
							</tr>
							<tr>
								<td style="text-align:center;">Fittings 150mm</td>
								<td style="text-align:center;"></td>
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


