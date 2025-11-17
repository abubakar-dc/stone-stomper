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

	if (!empty($cart_item['barwidth_mm'])) {
        $item_data[] = [
            'key'   => __('Towing Vehicle Barwidth', 'stone-stomper'),
            'value' => esc_html($cart_item['barwidth_mm']) . ' mm',
        ];
    }
    if (!empty($cart_item['a_frame_length_mm'])) {
        $item_data[] = [
            'key'   => __('A-Frame Length', 'stone-stomper'),
            'value' => esc_html($cart_item['a_frame_length_mm']) . ' mm',
        ];
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

	// --- new measurement fields ---
    if (!empty($_POST['barwidth'])) {
        $cart_item_data['barwidth_mm'] = floatval($_POST['barwidth']);
    }
    if (!empty($_POST['a_frame_length'])) {
        $cart_item_data['a_frame_length_mm'] = floatval($_POST['a_frame_length']);
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

	 // Add measurements to order item meta
    if (!empty($values['barwidth_mm'])) {
        $item->add_meta_data('Towing Vehicle Barwidth (mm)', $values['barwidth_mm']);
    }
    if (!empty($values['a_frame_length_mm'])) {
        $item->add_meta_data('A-Frame Length (mm)', $values['a_frame_length_mm']);
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




add_action( 'woocommerce_checkout_create_order', function( $order, $data ) {

    $cookie = sts_read_order_form_cookie();

    if ( ! empty( $cookie['is_stone_stomper_order'] ) && $cookie['is_stone_stomper_order'] === 'yes' ) {
        $order->update_meta_data('_sts_order', 'yes');
    }

}, 10, 2 );


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
/**
 * Helper: ensure array of attachment IDs or URLs
 */
function sts_to_media_array( $v ) {
	if ( is_string( $v ) ) {
		$maybe = json_decode( $v, true );
		if ( is_array( $maybe ) ) {
			$v = $maybe;
		} else {
			$v = preg_split( '/[\s,|]+/', $v );
		}
	}

	if ( ! is_array( $v ) ) return [];

	$result = [];
	foreach ( $v as $item ) {
		$item = trim( $item );
		if ( ! $item ) continue;

		// If it's a numeric ID
		if ( is_numeric( $item ) ) {
			$result[] = intval( $item );
			continue;
		}

		// If it's a full URL (try to find attachment ID)
		if ( filter_var( $item, FILTER_VALIDATE_URL ) ) {
			$id = attachment_url_to_postid( $item );
			if ( $id ) {
				$result[] = intval( $id );
			} else {
				// Fallback: keep the URL itself if no ID found
				$result[] = esc_url_raw( $item );
			}
		}
	}
	return $result;
}


/**
 * Create/update a Customer CPT when an order is placed
 */
add_action( 'woocommerce_new_order', function( $order_id ) {

	$order = wc_get_order( $order_id );
	error_log('checked order');
	$data = sts_read_order_form_cookie();


	if ( empty($data['is_stone_stomper_order']) || $data['is_stone_stomper_order'] !== 'yes' ) {
		return;
	}


	error_log(print_r($data, true));

	$cust_name    = isset( $data['customer_name'] )   ? sanitize_text_field( $data['customer_name'] )   : '';
	$cust_phone    = isset( $data['customer_phone'] )   ? sanitize_text_field( $data['customer_phone'] )   : '';
	$cust_email   = isset( $data['customer_email'] )  ? sanitize_email( $data['customer_email'] )       : '';
	$cust_address = isset( $data['customer_address'] )? sanitize_text_field( $data['customer_address'] ): '';
	$cust_suburb  = isset( $data['customer_suburb'] ) ? sanitize_text_field( $data['customer_suburb'] ) : '';
	$cust_state   = isset( $data['customer_state'] )  ? sanitize_text_field( $data['customer_state'] )  : '';
	$product_type = isset( $data['product_type'] )    ? sanitize_text_field( $data['product_type'] )    : '';

	if($product_type === "712"){
		$product_type = "Mesh Only";
	} else {
		$product_type = "Stone Stomper";
	}

	$vehicle_make  = isset( $data['vehicle_make'] )  ? sanitize_text_field( $data['vehicle_make'] )  : ( isset( $data['veh_make'] ) ? sanitize_text_field( $data['veh_make'] ) : '' );
	$vehicle_model = isset( $data['vehicle_model'] ) ? sanitize_text_field( $data['vehicle_model'] ) : ( isset( $data['veh_model'] ) ? sanitize_text_field( $data['veh_model'] ) : '' );
	$vehicle_year  = isset( $data['vehicle_year'] )  ? sanitize_text_field( $data['vehicle_year'] )  : ( isset( $data['veh_year'] ) ? sanitize_text_field( $data['veh_year'] ) : '' );


	$caravan_make  = isset( $data['caravan_make'] )  ? sanitize_text_field( $data['caravan_make'] )  : ( isset( $data['caravan_make'] ) ? sanitize_text_field( $data['caravan_make'] ) : ( isset( $data['van_make'] ) ? sanitize_text_field( $data['van_make'] ) : '' ) );
	$caravan_model = isset( $data['caravan_model'] ) ? sanitize_text_field( $data['caravan_model'] ) : ( isset( $data['van_model'] ) ? sanitize_text_field( $data['van_model'] ) : '' );

	// Bar Option
	$bar_options    = isset( $data['bar_options'] )   ? sanitize_text_field( $data['bar_options'] )   : '';


	// Accessories: check Gravity-like names and "other"
	$accessories = array();
	if ( ! empty( $data['input_1.2'] ) || ! empty( $data['toolbox'] ) ) $accessories[] = 'toolbox';
	if ( ! empty( $data['input_1.1'] ) || ! empty( $data['factory_stoneguard'] ) ) $accessories[] = 'factory-stoneguard';
	if ( ! empty( $data['other_a_frame'] ) ) $accessories[] = sanitize_text_field( $data['other_a_frame'] );

	// Measurements
	$measure_barwidth_mm         = isset( $data['barwidth_mm'] ) ? sanitize_text_field( $data['barwidth_mm'] ) : '';
	$measure_meshmeasurment_mm         = isset( $data['meshmeasurment_mm'] ) ? sanitize_text_field( $data['meshmeasurment_mm'] ) : '';
	$toolbox_width_mm    = isset( $data['toolbox_width_mm'] ) ? sanitize_text_field( $data['toolbox_width_mm'] ) : '';
	$toolbox_length_mm   = isset( $data['toolbox_length_mm'] ) ? sanitize_text_field( $data['toolbox_length_mm'] ) : '';
	$caravan_width_mm    = isset( $data['caravan_width_mm'] ) ? sanitize_text_field( $data['caravan_width_mm'] ) : '';
	$a_frame_length_mm   = isset( $data['a_frame_length_mm'] ) ? sanitize_text_field( $data['a_frame_length_mm'] ) : '';
	$stoneguard_length_mm = isset( $data['stoneguard_length_mm'] ) ? sanitize_text_field( $data['stoneguard_length_mm'] ) : '';
	$stoneguard_width_mm = isset( $data['stoneguard_width_mm'] ) ? sanitize_text_field( $data['stoneguard_width_mm'] ) : '';
	$support_pocket_length_mm = isset( $data['support_pocket_length_mm'] ) ? sanitize_text_field( $data['support_pocket_length_mm'] ) : '';
	$vinyl_width_mm     = isset( $data['vinyl_width_mm'] ) ? sanitize_text_field( $data['vinyl_width_mm'] ) : '';
	$vinyl_length_mm     = isset( $data['vinyl_length_mm'] ) ? sanitize_text_field( $data['vinyl_length_mm'] ) : '';
	$support_pockets     = sts_bool( $data['support_pockets']) ? 'yes' : 'no';


	// // Photos (hidden inputs hold JSON arrays of IDs)
	$hitch_ids = sts_to_media_array( $data['hitch_ids'] ?? [] );
	$rear_ids  = sts_to_media_array( $data['rear_ids'] ?? [] );
	$front_ids = sts_to_media_array( $data['front_ids'] ?? [] );

	$final_delivery  = isset( $data['final_delivery'] )  ? sanitize_text_field( $data['final_delivery'] )  : ( isset( $data['final_address'] ) ? sanitize_text_field( $data['final_address'] ) : '' );


	// Final details
	$final = array(
		'final_delivery'  => isset( $data['final_delivery'] ) ? sanitize_text_field( $data['final_delivery'] ) : ( isset( $data['final_address'] ) ? sanitize_text_field( $data['final_address'] ) : '' ),
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
	update_post_meta( $post_id, 'product_type', $product_type );
	update_post_meta( $post_id, 'vehicle_make', $vehicle_make );
	update_post_meta( $post_id, 'vehicle_model', $vehicle_model );
	update_post_meta( $post_id, 'caravan_make', $caravan_make );
	update_post_meta( $post_id, 'caravan_model', $caravan_model );
	update_post_meta( $post_id, 'year_of_manufacture', $vehicle_year );
	update_post_meta( $post_id, 'measure_barwidth_mm', $measure_barwidth_mm );
	update_post_meta( $post_id, 'bar_width_mm', $measure_barwidth_mm );
	update_post_meta( $post_id, 'caravan_width_mm', $caravan_width_mm );
	update_post_meta( $post_id, 'caravan_length_mm', $a_frame_length_mm );
	update_post_meta( $post_id, 'support_pockets', $support_pockets );

	if ( $product_type === 'Mesh Only' ) {
		update_post_meta( $post_id, 'sts_var_caravan_mesh_only_measurement', $measure_meshmeasurment_mm );
	} else {
		update_post_meta( $post_id, 'sts_var_caravan_bar_option', $bar_options );
	}

	// Check condition and update Final delivery
	if ( strtolower( $final_delivery ) === 'move' ) {
		update_post_meta( $post_id, 'sts_var_proposed_on_the_move', 'Yes' );
	} elseif ( strtolower( $final_delivery ) === 'same' ) {
		update_post_meta( $post_id, 'sts_var_proposed_on_the_move', 'No' );
	} else {
		// Optional: if it's neither 'move' nor 'same', you can clear or skip
		update_post_meta( $post_id, 'sts_var_proposed_on_the_move', '' );
	}
	// Support pockets - save in ACF-compatible format (1/0)
	// $support_pockets_raw = $data['support_pockets'] ?? 'no';
	// $support_pockets     = ( strtolower( $support_pockets_raw ) === 'yes' || sts_bool( $support_pockets_raw ) ) ? 1 : 0;
	// update_post_meta( $post_id, 'support_pockets', $support_pockets );

	// $support_pocket = isset( $data['support_pocket'] ) && $data['support_pocket'] === 'yes' ? 'yes' : 'no';
	// update_post_meta( $post_id, 'support_pocket', $support_pocket );

	// Support pockets (always saved as true/false)


	// --- Toolbox ---
	if ( isset( $data['toolbox'] ) && sts_bool( $data['toolbox'] ) ) {
		update_post_meta( $post_id, 'toolbox_width_mm', $toolbox_width_mm );
		update_post_meta( $post_id, 'toolbox_height_mm', $toolbox_length_mm );
	}

	// --- Factory Stoneguard ---
	if ( isset( $data['factory_stoneguard'] ) && sts_bool( $data['factory_stoneguard'] ) ) {
		update_post_meta( $post_id, 'factory_stoneguard_width', $stoneguard_length_mm );
		update_post_meta( $post_id, 'factory_stoneguard_height', $stoneguard_width_mm );
	}

	// --- Factory Stoneguard ---
	if ( isset( $data['support_pockets'] ) && sts_bool( $data['support_pockets'] ) ) {
		update_post_meta( $post_id, 'support_pockets_measurement', $support_pocket_length_mm );
	}

	update_post_meta( $post_id, 'vinyl_insert_width_mm', $vinyl_width_mm );
	update_post_meta( $post_id, 'vinyl_insert_height_mm', $vinyl_length_mm );
	// update_post_meta( $post_id, 'toolbox_width_mm', $toolbox_width_mm );
	// update_post_meta( $post_id, 'toolbox_height_mm', $toolbox_length_mm );


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


	update_post_meta( $post_id, 'final_details', $final );

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


function render_towing_diagram($post_id) {
		$caravan_length_mm      = get_post_meta( $post_id, 'caravan_length_mm', true );
		$caravan_width_mm       = get_post_meta( $post_id, 'caravan_width_mm', true );
		$toolbox_height_mm      = get_post_meta( $post_id, 'toolbox_height_mm', true );
		$toolbox_width_mm       = get_post_meta( $post_id, 'toolbox_width_mm', true );
		$bar_width_mm           = get_post_meta( $post_id, 'bar_width_mm', true );
		$vinyl_insert_width_mm  = get_post_meta( $post_id, 'vinyl_insert_width_mm', true );
		$vinyl_insert_height_mm = get_post_meta( $post_id, 'vinyl_insert_height_mm', true );
		$stoneguard_width_mm    = get_post_meta( $post_id, 'factory_stoneguard_width', true );
		$stoneguard_height_mm   = get_post_meta( $post_id, 'factory_stoneguard_height', true );
		$sts_var_caravan_cut_out  = get_post_meta( $post_id, 'sts_var_caravan_cut_out', true );


		$sts_var_caravan_ss_length_adj  = get_post_meta( $post_id, 'sts_var_caravan_ss_length_adj', true );
		if($sts_var_caravan_ss_length_adj){
			$caravan_length_mm = $caravan_length_mm + $sts_var_caravan_ss_length_adj;
		}
		ob_start();
	?>

	<?php if($caravan_length_mm < 1800 ){ ?>
		<svg xmlns="http://www.w3.org/2000/svg" id="Layer_2" version="1.1" viewBox="0 0 1200 800">
			<!-- Generator: Adobe Illustrator 29.8.1, SVG Export Plug-In . SVG Version: 2.1.1 Build 2)  -->
			<defs>
				<style>
				.st0 {
					stroke: #fa3232;
				}

				.st0, .st1, .st2 {
					fill: #ffffff;
				}

				.st3, .st4 {
					fill: #ffffff;
				}

				.st3, .st1, .st2 {
					stroke: #000;
				}

				.st1 {
					stroke-width: 3px;
					fill: #ffffff;
				}

				.st5 {
					fill: #fa3232;
					font-family: OpenSans, 'Open Sans';
					font-size: 15px;
					letter-spacing: .03em;
				}

					rect {
						fill: #ffffff;
					}
				</style>
			</defs>
			<path class="st1" d="M935.7,173.33c.12-1.09-.69-1.99-1.79-1.99H278.24c-1.1,0-1.91.9-1.79,1.99l41.6,402.61c.11,1.09,1.11,1.99,2.21,1.99h246.73c1.1,0,2-.9,2-2v-61.51c0-1.1.9-2,2-2h68.28c1.1,0,2,.9,2,2v61.51c0,1.1.9,2,2,2h247.86c1.1,0,2.09-.9,2.21-1.99l42.37-402.61Z" fill="#ffffff" stroke="#000000"/>
			<!-- <polyline class="st2" points="329.42 577.92 288.76 180.94 923.79 180.94 882.27 577.92" fill="#ffffff" stroke="#000000"/> -->
			 <?php if($sts_var_caravan_cut_out){ ?>
				<g transform="translate(605.76,550.99)">
					<text class="st5" fill="#fa3232" text-anchor="middle" dominant-baseline="middle" y="0">
						<?php echo esc_html( $sts_var_caravan_cut_out ? $sts_var_caravan_cut_out.' mm' : '-' ); ?>
					</text>
				</g>
			<?php } ?>
			<circle class="st2" cx="928.02" cy="177.82" r="5.22" fill="#ffffff" stroke="#000000"/>
			<circle class="st2" cx="283.6" cy="177.82" r="5.22" fill="#ffffff" stroke="#000000"/>
			<rect class="st2" x="390.67" y="364.08" width="438.42" height="27.06" fill="#ffffff" stroke="#000000"/>
			<rect class="st3" x="504.04" y="315.51" width="211.2" height="195.21" fill="#ffffff" stroke="#000000"/>
			<?php if($vinyl_insert_width_mm){ ?>
				<g transform="translate(610.76,330.99)">
					<text class="st5" text-anchor="middle" dominant-baseline="middle" y="0"><?php echo esc_html( $vinyl_insert_width_mm ? $vinyl_insert_width_mm.' mm' : '-' ); ?></text>
				</g>
			<?php } ?>
			<?php if($vinyl_insert_height_mm){ ?>
				<g transform="translate(520.76,400.99) rotate(-90)">
					<text class="st5" text-anchor="middle" dominant-baseline="middle" y="0"><?php echo esc_html( $vinyl_insert_height_mm ? $vinyl_insert_height_mm.' mm' : '-' ); ?></text>
				</g>
			<?php } ?>
			<rect class="st2" x="404.09" y="381.28" width="8.47" height="196.64" fill="#ffffff" stroke="#000000"/>
			<rect class="st2" x="477.94" y="381.28" width="8.47" height="196.64" fill="#ffffff" stroke="#000000"/>
			<rect class="st2" x="732.88" y="381.28" width="8.47" height="196.64" fill="#ffffff" stroke="#000000"/>
			<rect class="st2" x="806.72" y="381.28" width="8.47" height="196.64" fill="#ffffff" stroke="#000000"/>
			<circle class="st2" cx="737.11" cy="374.25" r="4.24" fill="#ffffff"  stroke="#000000"/>
			<circle class="st2" cx="810.96" cy="374.25" r="4.24"  fill="#ffffff" stroke="#000000"/>
			<circle class="st2" cx="408.33" cy="374.25" r="4.24"  fill="#ffffff" stroke="#000000"/>
			<circle class="st2" cx="482.17" cy="374.25" r="4.24"  fill="#ffffff" stroke="#000000"/>
			<text class="st5" transform="translate(565.96 411.74)"><tspan fill="#fa3232" x="0" y="0">Vinyl Insert</tspan></text>
			<g>
				<line class="st0" x1="274.29" y1="139.99" x2="935.24" y2="139.99" stroke="#fa3232"/>
				<g transform="translate(604.76,139.99)">
					<rect class="st4" x="-67.5" y="-22" width="135" height="24" fill="#ffffff"  stroke="#ffffff"/>
					<text class="st5" text-anchor="middle" dominant-baseline="middle" y="0"><?php echo esc_html( $caravan_width_mm ? $caravan_width_mm.' mm' : '-' ); ?></text>
				</g>
				<polyline class="st0" points="278.3 143.72 274.29 139.99 278.3 136.27" stroke="#fa3232" fill="#ffffff"/>
				<polyline class="st0" points="931.24 136.27 935.24 140 931.24 143.72" stroke="#fa3232"  fill="#ffffff"/>
			</g>
			<g>
				<line class="st0" x1="317.29" y1="608.6" x2="894.24" y2="608.6" stroke="#fa3232"/>
				<g transform="translate(957.8,375.13)">
				<rect class="st4" x="10" y="-12" width="135" height="24" fill="#ffffff"  stroke="#ffffff"/>
				<text class="st5" text-anchor="start" dominant-baseline="middle" x="20"><?php echo esc_html( $caravan_length_mm ? $caravan_length_mm.' mm' : '-' ); ?></text>
				</g>
				<polyline class="st0" points="321.3 612.33 317.29 608.6 321.3 604.88" stroke="#fa3232" fill="#ffffff"/>
				<polyline class="st0" points="890.24 604.88 894.24 608.6 890.24 612.33" stroke="#fa3232" fill="#ffffff"/>
			</g>


			<g>
				<line class="st0" x1="957.8" y1="170.34" x2="957.8" y2="579.93" stroke="#fa3232"/>
				<g transform="translate(605.77,608.6)">
					<rect class="st4" x="-67.5" y="-12" width="135" height="24" fill="#ffffff" stroke="#ffffff"/>
					<text class="st5" text-anchor="middle" dominant-baseline="middle" y="0"><?php echo esc_html( $bar_width_mm ? $bar_width_mm.' mm' : '-' ); ?></text>
				</g>
				<polyline class="st0" points="954.07 174.34 957.8 170.34 961.52 174.34" stroke="#fa3232" fill="#ffffff"/>
				<polyline class="st0" points="961.52 575.92 957.8 579.93 954.07 575.92" stroke="#fa3232" fill="#ffffff"/>
			</g>
		</svg>
	<?php } else { ?>
		<svg id="Layer_2" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 1200 800">
			<!-- Generator: Adobe Illustrator 29.8.1, SVG Export Plug-In . SVG Version: 2.1.1 Build 2)  -->
			<defs>
				<style>
					.st0, .st1, .st2 {
						stroke: #fa3232;
					}

					.st0, .st1, .st2, .st3, .st4 {
						fill: #ffffff;
					}

					.st1 {
						stroke-dasharray: 5.39 5.39;
					}

					.st2 {
						stroke-dasharray: 5.07 5.07;
					}

					.st5, .st6 {
						fill: #fa3232;
					}

					.st7, .st8 {
						fill: #ffffff;
					}

					.st7, .st3, .st4 {
						stroke: #000000;
					}

					.st3 {
						stroke-width: 3px;
					}

					.st6 {
						font-family: OpenSans, 'Open Sans';
						font-size: 15px;
						letter-spacing: .03em;
					}
					rect {
						fill: #ffffff;
					}
				</style>
			</defs>
			<path class="st3" d="M935.7,173.33c.12-1.09-.69-1.99-1.79-1.99H278.24c-1.1,0-1.91.9-1.79,1.99l41.6,402.61c.11,1.09,1.11,1.99,2.21,1.99h246.73c1.1,0,2-.9,2-2v-61.51c0-1.1.9-2,2-2h68.28c1.1,0,2,.9,2,2v61.51c0,1.1.9,2,2,2h247.86c1.1,0,2.09-.9,2.21-1.99l42.37-402.61Z" fill="#ffffff" stroke="#000000"/>
			<!-- <polyline class="st4" points="329.42 577.92 288.76 180.94 923.79 180.94 882.27 577.92" fill="#ffffff" stroke="#000000"/> -->
			 <?php if($sts_var_caravan_cut_out){ ?>
				<g transform="translate(605.76,550.99)">
					<text class="st5" fill="#fa3232" text-anchor="middle" dominant-baseline="middle" y="0">
						<?php echo esc_html( $sts_var_caravan_cut_out ? $sts_var_caravan_cut_out.' mm' : '-' ); ?>
					</text>
				</g>
			<?php } ?>
			<circle class="st4" cx="928.02" cy="177.82" r="5.22" fill="#ffffff" stroke="#000000"/>
			<circle class="st4" cx="283.6" cy="177.82" r="5.22" fill="#ffffff" stroke="#000000"/>
			<rect class="st4" x="390.67" y="364.08" width="438.42" height="27.06" fill="#ffffff" stroke="#000000"/>
			<rect class="st7" x="504.04" y="315.51" width="211.2" height="195.21" fill="#ffffff" stroke="#000000"/>

			<?php if($vinyl_insert_width_mm){ ?>
				<g transform="translate(610.76,330.99)">
					<text class="st5" fill="#fa3232" text-anchor="middle" dominant-baseline="middle" y="0">
						<?php echo esc_html( $vinyl_insert_width_mm ? $vinyl_insert_width_mm.' mm' : '-' ); ?>
					</text>
				</g>
			<?php } ?>

			<?php if($vinyl_insert_height_mm){ ?>
				<g transform="translate(520.76,400.99) rotate(-90)">
					<text class="st5" fill="#fa3232" text-anchor="middle" dominant-baseline="middle" y="0"><?php echo esc_html( $vinyl_insert_height_mm ? $vinyl_insert_height_mm.' mm' : '-' ); ?></text>
				</g>
			<?php } ?>
			<rect class="st4" x="404.09" y="381.28" width="8.47" height="196.64" fill="#ffffff" stroke="#000000"/>
			<rect class="st4" x="477.94" y="381.28" width="8.47" height="196.64" fill="#ffffff" stroke="#000000"/>
			<rect class="st4" x="732.88" y="381.28" width="8.47" height="196.64" fill="#ffffff" stroke="#000000"/>
			<rect class="st4" x="806.72" y="381.28" width="8.47" height="196.64" fill="#ffffff" stroke="#000000"/>
			<circle class="st4" cx="737.11" cy="374.25" r="4.24" fill="#ffffff" stroke="#000000"/>
			<circle class="st4" cx="810.96" cy="374.25" r="4.24" fill="#ffffff" stroke="#000000"/>
			<circle class="st4" cx="408.33" cy="374.25" r="4.24" fill="#ffffff" stroke="#000000"/>
			<circle class="st4" cx="482.17" cy="374.25" r="4.24" fill="#ffffff" stroke="#000000"/>
			<text class="st6" transform="translate(565.96 411.74)"><tspan fill="#fa3232" x="0" y="0">Vinyl Insert</tspan></text>
			<g>
				<line class="st0" x1="274.29" y1="139.99" x2="935.24" y2="139.99" fill="#ffffff" stroke="#fa3232"/>
				<rect class="st8" x="539.36" y="127.61" width="135.53" height="22.16" fill="#ffffff" stroke="#ffffff"/>
				<g transform="translate(604.76,139.99)">
					<text class="st5" fill="#fa3232" text-anchor="middle" dominant-baseline="middle" y="0"><?php echo esc_html( $caravan_width_mm ? $caravan_width_mm.' mm' : '-' ); ?></text>
				</g>
				<polyline class="st0" points="278.3 143.72 274.29 139.99 278.3 136.27" fill="#ffffff" stroke="#fa3232"/>
				<polyline class="st0" points="931.24 136.27 935.24 140 931.24 143.72" fill="#ffffff" stroke="#fa3232"/>
			</g>
			<!-- Bottom Line -->
			<g>
				<line class="st0" x1="317.29" y1="608.6" x2="894.24" y2="608.6" fill="#ffffff" stroke="#fa3232"/>
				<rect class="st8" x="539.36" y="596.22" width="135.53" height="22.16" fill="#ffffff" stroke="#ffffff"/>
				<g transform="translate(605.77,608.6)">
					<rect class="st8" x="948.02" y="306.8" width="22.16" height="135.53" fill="#ffffff" stroke="#ffffff"/>
					<text class="st5" text-anchor="middle" dominant-baseline="middle" y="0"  fill="#fa3232"><?php echo esc_html( $bar_width_mm ? $bar_width_mm.' mm' : '-' ); ?></text>
				</g>
				<polyline class="st0" points="321.3 612.33 317.29 608.6 321.3 604.88" fill="#ffffff" stroke="#fa3232"/>
				<polyline class="st0" points="890.24 604.88 894.24 608.6 890.24 612.33" fill="#ffffff" stroke="#fa3232"/>
			</g>
			<!-- Verticle line -->
			<g>
				<line class="st0" x1="957.8" y1="170.34" x2="957.8" y2="579.93" fill="#ffffff" stroke="#fa3232"/>
				<rect class="st8" x="948.02" y="306.8" width="22.16" height="135.53" fill="#ffffff" stroke="#ffffff"/>
				<g transform="translate(915.8,375.13)">
					<!-- <rect class="st4" x="10" y="-12" width="135" height="24" fill="#ffffff"/> -->
					<text class="st5" text-anchor="start" dominant-baseline="middle" x="20" fill="#fa3232"><?php echo esc_html( $caravan_length_mm ? $caravan_length_mm.' mm' : '-' ); ?></text>
				</g>
				<polyline class="st0" points="954.07 174.34 957.8 170.34 961.52 174.34" fill="#ffffff" stroke="#fa3232"/>
				<polyline class="st0" points="961.52 575.92 957.8 579.93 954.07 575.92" fill="#ffffff" stroke="#fa3232"/>
			</g>
			<line class="st0" x1="884.28" y1="181.78" x2="884.28" y2="286.37" fill="#ffffff" stroke="#fa3232"/>
			<rect class="st8" x="873.2" y="212.51" width="22.16" height="43.67" fill="#ffffff" stroke="#ffffff"/>
			<g transform="translate(830,240.13)">
				<!-- <rect class="st4" x="10" y="-12" width="135" height="24" fill="#ffffff"/> -->

				<!-- Stonegard Size -->
				<?php if($stoneguard_height_mm){ ?>
					<text class="st5" text-anchor="start" dominant-baseline="middle" y="-15" x="20" fill="#fa3232"><?php echo esc_html( $stoneguard_height_mm ? 'S: '. $stoneguard_height_mm.' mm' : '-' ); ?></text>
				<?php } ?>

				<!-- Toolbox Size -->
				<?php if($toolbox_height_mm){ ?>
					<text class="st5" text-anchor="start" dominant-baseline="middle" x="20"  fill="#fa3232"><?php echo esc_html( $toolbox_height_mm ? 'T: '.$toolbox_height_mm.' mm' : '-' ); ?></text>
				<?php } ?>
			</g>
			<polyline class="st0" points="880.55 185.79 884.28 181.78 888 185.79" fill="#ffffff" stroke="#fa3232"/>
			<polyline class="st0" points="888 282.37 884.28 286.37 880.55 282.37" fill="#ffffff" stroke="#fa3232"/>
			<g id="Toolbox">
				<g>
				<line class="st0" x1="352.44" y1="266.29" x2="860.13" y2="266.29" fill="#ffffff" stroke="#fa3232"/>
				<rect class="st8" x="539.36" y="253.9" width="135.53" height="22.16" fill="#ffffff"  stroke="#ffffff"/>
				<g transform="translate(555,263.13)">
					<!-- <rect class="st4" x="10" y="-12" width="135" height="24" fill="#ffffff"/> -->
					<!-- Stonegard Size -->
					<?php if($stoneguard_width_mm){ ?>
					<text class="st5" text-anchor="start" dominant-baseline="middle" y="-15" x="20"  fill="#fa3232"><?php echo esc_html( $stoneguard_width_mm ? 'S: '. $stoneguard_width_mm.' mm' : '-' ); ?></text>
					<?php } ?>
					<!-- Toolbox Size -->
					<?php if($toolbox_height_mm){ ?>
						<text class="st5" text-anchor="start" dominant-baseline="middle" x="20"  fill="#fa3232"><?php echo esc_html( $toolbox_width_mm ? 'T: '. $toolbox_width_mm.' mm' : '-' ); ?></text>
					<?php } ?>
				</g>
				<polyline class="st0" points="356.43 270.01 352.42 266.29 356.43 262.56" fill="#ffffff" stroke="#fa3232"/>
				<polyline class="st0" points="856.13 262.56 860.13 266.29 856.13 270.01" fill="#ffffff" stroke="#fa3232"/>
				</g>
				<g>
					<g>
						<path class="st5" d="M533.06,211.74h-1.25v-9.6h-3.39v-1.11h8.03v1.11h-3.39v9.6Z"/>
						<path class="st5" d="M545.18,207.72c0,1.31-.33,2.33-.99,3.07-.66.74-1.57,1.1-2.73,1.1-.72,0-1.36-.17-1.91-.51-.56-.34-.99-.82-1.29-1.45-.3-.63-.45-1.37-.45-2.21,0-1.31.33-2.33.98-3.06.65-.73,1.56-1.09,2.72-1.09s2.02.37,2.68,1.12c.66.75.99,1.76.99,3.03ZM539.06,207.72c0,1.03.21,1.81.62,2.34.41.54,1.01.81,1.81.81s1.4-.27,1.81-.8c.41-.53.62-1.32.62-2.35s-.21-1.79-.62-2.33c-.41-.53-1.02-.79-1.83-.79s-1.4.26-1.8.78-.61,1.3-.61,2.34Z"/>
						<path class="st5" d="M554.6,207.72c0,1.31-.33,2.33-.99,3.07-.66.74-1.57,1.1-2.73,1.1-.72,0-1.36-.17-1.91-.51-.56-.34-.99-.82-1.29-1.45-.3-.63-.45-1.37-.45-2.21,0-1.31.33-2.33.98-3.06.65-.73,1.56-1.09,2.72-1.09s2.02.37,2.68,1.12c.66.75.99,1.76.99,3.03ZM548.48,207.72c0,1.03.21,1.81.62,2.34.41.54,1.01.81,1.81.81s1.4-.27,1.81-.8c.41-.53.62-1.32.62-2.35s-.21-1.79-.62-2.33c-.41-.53-1.02-.79-1.83-.79s-1.4.26-1.8.78-.61,1.3-.61,2.34Z"/>
						<path class="st5" d="M558.32,211.74h-1.22v-11.4h1.22v11.4Z"/>
						<path class="st5" d="M565,203.58c1.05,0,1.87.36,2.46,1.08.58.72.88,1.74.88,3.06s-.29,2.34-.88,3.07c-.59.73-1.41,1.09-2.45,1.09-.52,0-1-.1-1.43-.29-.43-.19-.79-.49-1.09-.89h-.09l-.26,1.03h-.87v-11.4h1.22v2.77c0,.62-.02,1.18-.06,1.67h.06c.57-.8,1.41-1.2,2.52-1.2ZM564.82,204.6c-.83,0-1.43.24-1.79.71-.37.48-.55,1.28-.55,2.41s.19,1.94.56,2.42c.38.49.98.73,1.81.73.75,0,1.3-.27,1.67-.82.37-.54.55-1.33.55-2.35s-.18-1.82-.55-2.34-.93-.77-1.7-.77Z"/>
						<path class="st5" d="M577.76,207.72c0,1.31-.33,2.33-.99,3.07-.66.74-1.57,1.1-2.73,1.1-.72,0-1.36-.17-1.91-.51-.56-.34-.99-.82-1.29-1.45-.3-.63-.45-1.37-.45-2.21,0-1.31.33-2.33.98-3.06.65-.73,1.56-1.09,2.72-1.09s2.02.37,2.68,1.12c.66.75.99,1.76.99,3.03ZM571.64,207.72c0,1.03.21,1.81.62,2.34.41.54,1.01.81,1.81.81s1.4-.27,1.81-.8c.41-.53.62-1.32.62-2.35s-.21-1.79-.62-2.33c-.41-.53-1.02-.79-1.83-.79s-1.4.26-1.8.78-.61,1.3-.61,2.34Z"/>
						<path class="st5" d="M582.2,207.63l-2.79-3.92h1.38l2.12,3.08,2.11-3.08h1.37l-2.79,3.92,2.94,4.11h-1.38l-2.25-3.25-2.27,3.25h-1.38l2.94-4.11Z"/>
						<path class="st5" d="M596.08,201.03l-3.99,10.71h-1.22l3.99-10.71h1.22Z"/>
						<path class="st5" d="M607.65,208.89c0,.94-.34,1.68-1.03,2.21s-1.61.79-2.78.79c-1.27,0-2.25-.16-2.93-.49v-1.2c.44.19.92.33,1.44.44s1.03.16,1.54.16c.83,0,1.46-.16,1.88-.47.42-.32.63-.75.63-1.32,0-.37-.08-.67-.22-.91-.15-.24-.4-.46-.75-.66-.35-.2-.88-.43-1.59-.68-1-.36-1.71-.78-2.13-1.27-.43-.49-.64-1.12-.64-1.91,0-.83.31-1.48.93-1.97s1.44-.73,2.46-.73,2.04.2,2.94.59l-.39,1.08c-.88-.37-1.74-.56-2.58-.56-.66,0-1.17.14-1.55.42-.37.28-.56.68-.56,1.18,0,.37.07.67.21.91.14.24.37.45.69.65.32.2.82.42,1.49.66,1.12.4,1.9.83,2.32,1.29.42.46.63,1.05.63,1.79Z"/>
						<path class="st5" d="M612.25,210.88c.21,0,.42-.02.62-.05.2-.03.36-.06.48-.1v.93c-.13.06-.33.12-.58.16-.26.04-.49.06-.69.06-1.55,0-2.33-.82-2.33-2.45v-4.78h-1.15v-.59l1.15-.51.51-1.71h.7v1.86h2.33v.95h-2.33v4.72c0,.48.11.85.34,1.11s.54.39.95.39Z"/>
						<path class="st5" d="M621.88,207.72c0,1.31-.33,2.33-.99,3.07-.66.74-1.57,1.1-2.73,1.1-.72,0-1.35-.17-1.91-.51-.56-.34-.99-.82-1.29-1.45-.3-.63-.45-1.37-.45-2.21,0-1.31.33-2.33.98-3.06.65-.73,1.56-1.09,2.72-1.09s2.01.37,2.68,1.12c.66.75.99,1.76.99,3.03ZM615.76,207.72c0,1.03.21,1.81.62,2.34.41.54,1.01.81,1.81.81s1.4-.27,1.81-.8c.41-.53.62-1.32.62-2.35s-.21-1.79-.62-2.33c-.41-.53-1.02-.79-1.83-.79s-1.4.26-1.8.78-.61,1.3-.61,2.34Z"/>
						<path class="st5" d="M629.5,211.74v-5.19c0-.65-.15-1.14-.45-1.46s-.76-.48-1.4-.48c-.84,0-1.46.23-1.85.68s-.59,1.2-.59,2.25v4.21h-1.22v-8.03h.99l.2,1.1h.06c.25-.4.6-.7,1.05-.92.45-.22.95-.33,1.5-.33.97,0,1.69.23,2.18.7.49.47.73,1.21.73,2.24v5.24h-1.22Z"/>
						<path class="st5" d="M636.61,211.89c-1.19,0-2.12-.36-2.81-1.08-.69-.72-1.03-1.73-1.03-3.01s.32-2.32.96-3.08c.64-.76,1.49-1.14,2.57-1.14,1.01,0,1.8.33,2.39.99.59.66.88,1.54.88,2.62v.77h-5.53c.02.94.26,1.66.71,2.15.45.49,1.09.73,1.91.73.86,0,1.72-.18,2.56-.54v1.08c-.43.19-.84.32-1.22.4-.38.08-.85.12-1.39.12ZM636.28,204.59c-.64,0-1.16.21-1.54.63-.38.42-.61,1-.68,1.74h4.2c0-.77-.17-1.35-.51-1.76-.34-.41-.83-.61-1.46-.61Z"/>
						<path class="st5" d="M648.2,203.71v.77l-1.49.18c.14.17.26.39.37.67.11.28.16.59.16.93,0,.79-.27,1.41-.81,1.88s-1.27.7-2.21.7c-.24,0-.46-.02-.67-.06-.52.27-.78.62-.78,1.03,0,.22.09.38.27.49.18.1.49.16.93.16h1.42c.87,0,1.54.18,2,.55.47.37.7.9.7,1.6,0,.89-.36,1.57-1.07,2.03-.71.47-1.75.7-3.12.7-1.05,0-1.86-.2-2.43-.59-.57-.39-.85-.94-.85-1.66,0-.49.16-.91.47-1.27.31-.36.75-.6,1.32-.73-.21-.09-.38-.24-.52-.43-.14-.19-.21-.42-.21-.68,0-.29.08-.55.23-.77.16-.22.4-.43.74-.64-.42-.17-.75-.46-1.01-.87-.26-.41-.39-.88-.39-1.41,0-.88.26-1.56.79-2.03.53-.48,1.27-.71,2.24-.71.42,0,.8.05,1.14.15h2.78ZM641.8,213.09c0,.43.18.76.55.99s.89.34,1.57.34c1.02,0,1.78-.15,2.27-.46.49-.31.74-.72.74-1.24,0-.44-.13-.74-.4-.91-.27-.17-.77-.25-1.52-.25h-1.46c-.55,0-.98.13-1.29.4s-.46.64-.46,1.13ZM642.46,206.28c0,.56.16.99.48,1.28s.76.43,1.33.43c1.19,0,1.78-.58,1.78-1.73s-.6-1.81-1.8-1.81c-.57,0-1.01.15-1.32.46s-.46.76-.46,1.37Z"/>
						<path class="st5" d="M650.99,203.71v5.21c0,.66.15,1.14.45,1.46s.76.48,1.4.48c.84,0,1.45-.23,1.84-.69.39-.46.58-1.21.58-2.25v-4.22h1.22v8.03h-1l-.18-1.08h-.07c-.25.39-.59.7-1.04.91-.44.21-.95.31-1.51.31-.98,0-1.71-.23-2.19-.7-.49-.46-.73-1.21-.73-2.23v-5.25h1.23Z"/>
						<path class="st5" d="M663.99,211.74l-.24-1.14h-.06c-.4.5-.8.84-1.2,1.02-.4.18-.89.27-1.49.27-.8,0-1.42-.21-1.87-.62-.45-.41-.68-.99-.68-1.75,0-1.62,1.3-2.47,3.89-2.55l1.36-.04v-.5c0-.63-.14-1.09-.41-1.39-.27-.3-.7-.45-1.3-.45-.67,0-1.43.21-2.27.62l-.37-.93c.4-.21.83-.38,1.3-.51.47-.12.94-.18,1.42-.18.96,0,1.67.21,2.13.64.46.42.69,1.11.69,2.04v5.48h-.9ZM661.25,210.88c.76,0,1.35-.21,1.78-.62.43-.42.65-1,.65-1.74v-.72l-1.22.05c-.97.03-1.66.18-2.09.45-.43.27-.64.68-.64,1.24,0,.44.13.77.4,1,.27.23.64.34,1.12.34Z"/>
						<path class="st5" d="M671.06,203.57c.36,0,.68.03.96.09l-.17,1.13c-.33-.07-.62-.11-.88-.11-.65,0-1.21.26-1.67.79-.46.53-.69,1.18-.69,1.97v4.31h-1.22v-8.03h1l.14,1.49h.06c.3-.52.66-.92,1.08-1.21s.88-.42,1.38-.42Z"/>
						<path class="st5" d="M678.99,210.67h-.07c-.56.82-1.4,1.22-2.52,1.22-1.05,0-1.87-.36-2.45-1.08-.58-.72-.88-1.74-.88-3.06s.29-2.35.88-3.08,1.4-1.1,2.45-1.1,1.92.4,2.5,1.19h.1l-.05-.58-.03-.56v-3.27h1.22v11.4h-.99l-.16-1.08ZM676.55,210.87c.83,0,1.43-.23,1.81-.68.37-.45.56-1.18.56-2.19v-.26c0-1.14-.19-1.95-.57-2.43-.38-.49-.98-.73-1.81-.73-.71,0-1.26.28-1.64.83-.38.55-.57,1.34-.57,2.35s.19,1.8.56,2.32.93.78,1.66.78Z"/>
					</g>
				</g>
			</g>
		</svg>
	<?php } ?>

	<?php return ob_get_clean(); ?>
<?php }

// function get_towing_diagram_svg_png($post_id) {
//     ob_start();
//     render_towing_diagram($post_id, true); // this currently echoes SVG
//     return ob_get_clean();
// }

function get_towing_diagram_svg_png($post_id) {
    $html = render_towing_diagram($post_id);

    // Extract only the <svg>...</svg> content
    if (preg_match('/<svg[^>]*>.*<\/svg>/is', $html, $match)) {
        $svg = $match[0];
        return $svg;
    }

    return false;
}

// function svg_to_png_temp($svg_content) {
//     $tmp_png = tempnam(sys_get_temp_dir(), 'diagram_') . '.png';
//     $imagick = new \Imagick();
//     $imagick->setBackgroundColor(new \ImagickPixel('white')); // white background
//     $imagick->readImageBlob($svg_content);
//     $imagick->setImageFormat("png32"); // 32-bit PNG preserves transparency
// 	$imagick->trimImage(0);
// 	$imagick->setImagePage(0, 0, 0, 0); // reset canvas after trimming
//     $imagick->writeImage($tmp_png);
//     $imagick->clear();
//     $imagick->destroy();

//     return $tmp_png;
// }

function svg_to_png_temp($svg_content) {
    $tmp_png = tempnam(sys_get_temp_dir(), 'diagram_') . '.png';

    $imagick = new \Imagick();
    $imagick->setBackgroundColor(new \ImagickPixel('white'));

    // very important for correct bounding box
    $imagick->setResolution(300, 300);

    $imagick->readImageBlob($svg_content);

    // flatten white instead of transparency to avoid black areas
    $imagick->setImageAlphaChannel(\Imagick::ALPHACHANNEL_REMOVE);

    $imagick->setImageFormat("png");

    // trim extra whitespace
    $imagick->trimImage(0);
    $imagick->setImagePage(0, 0, 0, 0);

    $imagick->writeImage($tmp_png);
    $imagick->clear();
    $imagick->destroy();

    return $tmp_png;
}


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
		$first_name   = $order->get_shipping_first_name();
		$last_name    = $order->get_shipping_last_name();
		$company      = $order->get_shipping_company();
		$address_1    = $order->get_shipping_address_1();
		$address_2    = $order->get_shipping_address_2();
		$city         = $order->get_shipping_city();
		$state        = $order->get_shipping_state();
		$postcode     = $order->get_shipping_postcode();
		$country      = $order->get_shipping_country();
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
	$final_details = get_post_meta( $post->ID, 'final_details', true );

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
    // $toolbox_width_mm    = get_post_meta( $post->ID, 'toolbox_width_mm', true );
    // $toolbox_height_mm    = get_post_meta( $post->ID, 'toolbox_height_mm', true );

    $vehicle_make    = get_post_meta( $post->ID, 'vehicle_make', true );
    $caravan_make    = get_post_meta( $post->ID, 'caravan_make', true );
    $sts_var_caravan_bar_option    	= get_post_meta( $post->ID, 'sts_var_caravan_bar_option', true );
    $sts_var_caravan_bar_bend    	= get_post_meta( $post->ID, 'sts_var_caravan_bar_bend', true );
    $sts_var_caravan_ss_length_adj  = get_post_meta( $post->ID, 'sts_var_caravan_ss_length_adj', true );
	if($sts_var_caravan_ss_length_adj){
		$caravan_length_mm = $caravan_length_mm + $sts_var_caravan_ss_length_adj;
	}

    $sts_var_caravan_cut_out    = get_post_meta( $post->ID, 'sts_var_caravan_cut_out', true );
    $sts_var_caravan_mesh_only_measurement    = get_post_meta( $post->ID, 'sts_var_caravan_mesh_only_measurement', true );
    $sts_var_caravan_break_form    = get_post_meta( $post->ID, 'sts_var_caravan_break_form', true );
    $sts_var_caravan_hr_form    = get_post_meta( $post->ID, 'sts_var_caravan_hr_form', true );
    $sts_var_caravan_eyelet_tab    = get_post_meta( $post->ID, 'sts_var_caravan_eyelet_tab', true );
    $sts_var_proposed_date_of_delivery    = get_post_meta( $post->ID, 'sts_var_proposed_date_of_delivery', true );

	$hitch_ids = get_post_meta( $post->ID, 'hitch_ids', true );
	$rear_ids  = get_post_meta( $post->ID, 'rear_ids', true );
	$front_ids = get_post_meta( $post->ID, 'front_ids', true );
	$support_pockets = get_post_meta( $post->ID, 'support_pockets', true );

	// var_dump($final_details['final_delivery']);

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
				<?php foreach ( $hitch_ids as $hitch_id ) :

				$img_url = esc_url($hitch_id); ?>
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
					<?php foreach ( $rear_ids as $hitch_id ) :

					$img_url = esc_url($hitch_id); ?>
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
				<?php foreach ( $front_ids as $hitch_id ) :
				$img_url = esc_url($hitch_id); ?>

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

	<div style="margin-top:96px;"></div>

	<!-- Order Preview Image -->
    <div style="text-align:center; padding:20px;">

		<div class="functional-buttons" id="functional-buttons">
				<span class="button button-primary save-chnages" id="save-chnages" style="margin-right:10px;">Save Changes <span class="spinner my-custom-spinner" style="float: left; margin-left: -40px;"></span> </span>
				<br>
				<br>
			<span class="button button-secondary generate-diagram" style="margin-right:10px;">Generate Diagram</span>
			<a href="<?php echo admin_url( 'admin-ajax.php?action=download_customer_word&post_id=' . $post->ID ); ?>" target="_blank" class="button button-secondary generate-word-doc" style="margin-right:10px;">Generate Word Document</a>
			<a href="<?php echo admin_url( 'admin-ajax.php?action=download_customer_pdf&post_id=' . $post->ID ); ?>" target="_blank" class="button button-secondary generate-pdf" style="margin-right:10px; display:none;">Generate PDF</a>
			<a href="#" class="button button-secondary email-to-manufacturer" style="margin-right:10px;">Email to Manufacturer</a>
			<a href="#" id="show-order-popup" class="button button-secondary" style="">View Order #<?php echo esc_html( $order_id ); ?></a>
		</div>


		<div class="stone-stomper-vector" style="display:none;">
			<div class="stone-stomper-vector-inner">
				<?php
					echo render_towing_diagram( $post->ID, true );
				?>

			</div>
		</div>
	</div>

	<!-- Data Table -->
	<div style="margin-top:30px; text-align:left; font-size:16px;">
		<h3 style="margin-bottom:10px; text-align:center; "> Measurements</h3>
		<table style="margin:0 auto; border-collapse:collapse; font-size:15px;">
			<?php if($sts_var_proposed_date_of_delivery){ ?>
				<tr>
					<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Proposed Date of Delivery:</td>
					<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo date( 'd-F-Y', $sts_var_proposed_date_of_delivery ?: '-' ); ?></td>
				</tr>
			<?php } ?>

			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Phone:</td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $customer_phone ?: '-' ); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">SS Width (mm):</td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $caravan_width_mm ? $caravan_width_mm.' mm'  : '-' ); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">SS Length (mm):</td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $caravan_length_mm ? $caravan_length_mm.' mm' : '-' ); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Towing Vehicle BarWidth (mm):</td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $bar_width_mm ? $bar_width_mm.' mm' : '-' ); ?></td>
			</tr>

			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Vinyl Insert Width (mm):</td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $vinyl_insert_width_mm ? $vinyl_insert_width_mm.' mm' : '-' ); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Vinyl Insert Length (mm):</td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $vinyl_insert_height_mm ? $vinyl_insert_height_mm.' mm': '-' ); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Stoneguard Width (mm):</td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $factory_stoneguard_width ? $factory_stoneguard_width.' mm': '-' ); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Stoneguard Length (mm):</td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $factory_stoneguard_height ? $factory_stoneguard_height.' mm': '-' ); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Toolbox Length (mm):</td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $toolbox_width_mm ? $toolbox_width_mm.' mm': '-' ); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Distance from the Caravan:</td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $toolbox_height_mm ? $toolbox_height_mm.' mm': '-' ); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Support Pockets:</td>

				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $support_pockets ?: '-' ); ?></td>
			</tr>

			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold; ">Bar Option</span></td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_bar_option); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold; ">Bar Bend</span></td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_bar_bend); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold; ">SS Length Adjustment</span></td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_ss_length_adj); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold; ">Cut Out</span></td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_cut_out); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold; ">Mesh only Measurement</span></td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_mesh_only_measurement); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold; ">Break Foam</span></td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_break_form); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold; ">Hr Foam</span></td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_hr_form); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold; ">Eyelet Tab</span></td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_eyelet_tab); ?></td>
			</tr>

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
							<b>Name: </b><?php echo esc_html( $customer_name ); ?>
							&nbsp;&nbsp;&nbsp;
							<b>Phone: </b><?php echo esc_html( $customer_phone ); ?>
							&nbsp;&nbsp;&nbsp;
							<strong>Email: </strong><?php echo esc_html( $customer_email ); ?>
						</div>
						<div class="customer-details inv-order-row">
							<strong>Home Address: </strong><?php echo html_entity_decode( $delivery_address ); ?>
						</div>

						<?php if($final_details && $final_details['final_delivery'] && $final_details['final_delivery'] === 'move'){ ?>
							<div class="customer-details inv-order-row">
								<strong>Delivery Address: </strong><?php echo 'I am on the move'; ?>
							</div>
						<?php } ?>
						<div class="customer-details inv-order-row">
							<strong>Delivery Instructions/Authority to Leave:</strong>
							<?php echo ! empty( $delivery_instructions ) ? esc_html( $delivery_instructions ) : 'No'; ?>
						</div>
						<div class="customer-details inv-order-row">
							<?php if($caravan_make){ ?>
								<strong>Trailer Make: </strong><?php echo esc_html( $caravan_make ); ?>
							<?php } ?>
							&nbsp;&nbsp;&nbsp;
							<?php if($vehicle_make){ ?>
								<strong>Vehicle Make: </strong><?php echo esc_html( $vehicle_make ); ?>
							<?php } ?>
						</div>
						<?php if($sts_var_proposed_date_of_delivery){ ?>
							<div class="customer-details inv-order-row">
								<strong>Date Required: </strong><?php echo date( 'd-F-Y', $sts_var_proposed_date_of_delivery ?: '-' ); ?>
							</div>
						<?php } ?>
					</div>

					<br>

					<table class="order-table" style="width:100%; border-collapse:collapse;">
						<tr>
							<td style="text-align:center;"><strong>Sr. NO</strong></td>
							<td style="text-align:center;"><strong>Description</strong></td>
							<td style="text-align:center;"><strong>Unit Price</strong></td>
							<td style="text-align:center;"><strong>Total</strong></td>
						</tr>

						<?php if ( $products ) { ?>

							<?php foreach( $products as $key =>  $product ){ ?>
								<tr>
									<td style="text-align:center;"><?php echo ++$key; ?></td>
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
						<div class="stone-stomper-vector-inner">
							<?php echo render_towing_diagram( $post->ID, true ); ?>
						</div>
					</div>
					<div class="thanks-message">THANK YOU FOR YOUR BUSINESS</div>
				</div>
				<!-- popup second page -->
				 <hr>
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
								<tr><td><strong>P/O#:</strong> </td></tr>
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
						<div class="customer-details inv-order-row">
							<strong>Delivery Address: </strong><?php echo html_entity_decode( $delivery_address ); ?>
						</div>
						<div class="customer-details inv-order-row">
							<strong>Delivery Instructions/Authority to Leave:</strong>
							<?php echo ! empty( $delivery_instructions ) ? esc_html( $delivery_instructions ) : 'No'; ?>
						</div>
						<div class="customer-details inv-order-row">
							<?php if($caravan_make){ ?>
								<strong>Trailer Make: </strong><?php echo esc_html( $caravan_make ); ?>
							<?php } ?>
							&nbsp;&nbsp;&nbsp;
							<?php if($vehicle_make){ ?>
								<strong>Vehicle Make: </strong><?php echo esc_html( $vehicle_make ); ?>
							<?php } ?>
						</div>
						<?php if($sts_var_proposed_date_of_delivery){ ?>
							<div class="customer-details inv-order-row">
								<strong>Date Required: </strong><?php echo date( 'd-F-Y', $sts_var_proposed_date_of_delivery ?: '-' ); ?>
							</div>
						<?php } ?>

						<!-- table -->
						<table class="order-table" style="width:100%; border-collapse:collapse;">
							<!-- SS Width -->
							<tr>
								<td style="padding:6px 15px; border:1px solid #ccc;">SS Width (mm):</td>
								<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $caravan_width_mm ? $caravan_width_mm   : '-' ); ?></td>
							</tr>
							<tr>
								<td style="padding:6px 15px; border:1px solid #ccc;">SS Length (mm):</td>
								<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $caravan_length_mm ? $caravan_length_mm  : '-' ); ?></td>
							</tr>
							<tr>
								<td style="padding:6px 15px; border:1px solid #ccc;">Towing Vehicle BarWidth (mm):</td>
								<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $bar_width_mm ? $bar_width_mm  : '-' ); ?></td>
							</tr>
							<tr>
								<td style="padding:6px 15px; border:1px solid #ccc;">Vinyl Insert Width (mm):</td>
								<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $vinyl_insert_width_mm ? $vinyl_insert_width_mm  : '-' ); ?></td>
							</tr>
							<tr>
								<td style="padding:6px 15px; border:1px solid #ccc;">Vinyl Insert Length (mm):</td>
								<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $vinyl_insert_height_mm ? $vinyl_insert_height_mm : '-' ); ?></td>
							</tr>
							<tr>
								<td style="padding:6px 15px; border:1px solid #ccc;">Stoneguard Width (mm):</td>
								<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $factory_stoneguard_width ? $factory_stoneguard_width : '-' ); ?></td>
							</tr>
							<tr>
								<td style="padding:6px 15px; border:1px solid #ccc;">Stoneguard Length (mm):</td>
								<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $factory_stoneguard_height ? $factory_stoneguard_height : '-' ); ?></td>
							</tr>
							<tr>
								<td style="padding:6px 15px; border:1px solid #ccc;">Toolbox Length (mm):</td>
								<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $toolbox_width_mm ? $toolbox_width_mm : '-' ); ?></td>
							</tr>
							<tr>
								<td style="padding:6px 15px; border:1px solid #ccc;">Distance from the Caravan:</td>
								<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $toolbox_height_mm ? $toolbox_height_mm : '-' ); ?></td>
							</tr>
							<tr>
								<td style="padding:6px 15px; border:1px solid #ccc;">Bar Option</span></td>
								<td style="padding:6px 15px; border:1px solid #ccc;"><span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_bar_option); ?></td>
							</tr>
							<tr>
								<td style="padding:6px 15px; border:1px solid #ccc;">Bar Bend</span></td>
								<td style="padding:6px 15px; border:1px solid #ccc;"><span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_bar_bend); ?></td>
							</tr>
							<tr>
								<td style="padding:6px 15px; border:1px solid #ccc;">SS Length Adjustment</span></td>
								<td style="padding:6px 15px; border:1px solid #ccc;"><span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_ss_length_adj); ?></td>
							</tr>
							<tr>
								<td style="padding:6px 15px; border:1px solid #ccc;">Cut Out</span></td>
								<td style="padding:6px 15px; border:1px solid #ccc;"><span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_cut_out); ?></td>
							</tr>
							<tr>
								<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold; ">Mesh only Measurement</span></td>
								<td style="padding:6px 15px; border:1px solid #ccc;"><span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_mesh_only_measurement); ?></td>
							</tr>
							<tr>
								<td style="padding:6px 15px; border:1px solid #ccc;">Break Foam</span></td>
								<td style="padding:6px 15px; border:1px solid #ccc;"><span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_break_form); ?></td>
							</tr>
							<tr>
								<td style="padding:6px 15px; border:1px solid #ccc;">Hr Foam</span></td>
								<td style="padding:6px 15px; border:1px solid #ccc;"><span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_hr_form); ?></td>
							</tr>
							<tr>
								<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold; ">Eyelet Tab</span></td>
								<td style="padding:6px 15px; border:1px solid #ccc;"><span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_eyelet_tab); ?></td>
							</tr>
						</table>

						<div class="stone-stomper-vector">
							<div class="stone-stomper-vector-inner">
							<?php echo render_towing_diagram( $post->ID, true ); ?>
						</div>
						</div>
					</div>
				</div>
        </div>
    </div>

	<script>
		jQuery(document).ready(function () {
			jQuery('#save-chnages').on('click', function(e) {
				e.preventDefault();

				// Show spinner
				jQuery(this).find('.my-custom-spinner').addClass('is-active');

				jQuery(this).addClass('disabled');

				// Mark that we want to scroll after reload
        		localStorage.setItem('scrollToFunctionalButtons', '1');

				// Save the post
				jQuery('#post').submit();
			});

			// After reload, check flag
			if (localStorage.getItem('scrollToFunctionalButtons') === '1') {

				// Scroll to section
				jQuery('html, body').animate({
					scrollTop: jQuery('#functional-buttons').offset().top - 50
				}, 400);

				// Remove flag so it doesn't scroll every time
				localStorage.removeItem('scrollToFunctionalButtons');
			}

			if (window.location.hash === '#functional-buttons') {
				jQuery('html, body').animate({
					scrollTop: jQuery('#functional-buttons').offset().top - 60
				}, 400);
			}

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

			// Generate Image
			jQuery(".generate-diagram").on("click", function (e) {
				jQuery(".stone-stomper-vector").slideDown();
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
        'Stone Stomper Preview',     // Title
        'show_towing_svg_in_editor', // Callback`
        'customer',                  // Post type (CPT slug)
        'normal',                    // Position (side or normal)
        'low'                       // Priority
    );
});

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Element\TextRun;

function generate_customer_order_word_file($post_id) {
	require_once __DIR__ . '/vendor/autoload.php';
    $phpWord = new \PhpOffice\PhpWord\PhpWord();

    // Set Default Font
    $phpWord->setDefaultFontName('Arial');
    $phpWord->setDefaultFontSize(10);

    $order_id = get_post_meta($post_id, 'order_id', true);
    $order = wc_get_order($order_id);
    if (!$order) return false;

    // Fetch standard order data
    $order_date       = $order->get_date_created()->date_i18n('d-F-Y');
    $customer_name    = $order->get_formatted_billing_full_name();
    $customer_phone   = get_post_meta($post_id, 'customer_phone', true);
    $customer_email   = $order->get_billing_email();
	$first_name   = $order->get_shipping_first_name();
	$last_name    = $order->get_shipping_last_name();
	$company      = $order->get_shipping_company();
    $address_1    = $order->get_shipping_address_1();
	$address_2    = $order->get_shipping_address_2();
	$city         = $order->get_shipping_city();
	$state        = $order->get_shipping_state();
	$postcode     = $order->get_shipping_postcode();
	$country      = $order->get_shipping_country();
	// Combine all parts with a space, ignoring empty values
	$delivery_address = implode(' ', array_filter([
		$first_name . ' ' . $last_name,
		$company,
		$address_1,
		$address_2,
		$city,
		$state,
		$postcode,
		$country
	]));
    $delivery_cost    = $order->get_shipping_total();
    $order_total      = $order->get_total();
    $delivery_instructions = $order->get_customer_note();


    // Fetch custom measurement meta
    $caravan_make           = get_post_meta($post_id, 'caravan_make', true);
    $caravan_model           = get_post_meta($post_id, 'caravan_model', true);
    $vehicle_make           = get_post_meta($post_id, 'vehicle_make', true);
    $vehicle_model           = get_post_meta($post_id, 'vehicle_model', true);
    $vehicle_year           = get_post_meta($post_id, 'year_of_manufacture', true);
    $caravan_width_mm       = get_post_meta($post_id, 'caravan_width_mm', true);
    $caravan_length_mm      = get_post_meta($post_id, 'caravan_length_mm', true);
    $bar_width_mm           = get_post_meta($post_id, 'bar_width_mm', true);
    $vinyl_insert_width_mm  = get_post_meta($post_id, 'vinyl_insert_width_mm', true);
    $vinyl_insert_height_mm = get_post_meta($post_id, 'vinyl_insert_height_mm', true);
    $factory_stoneguard_width  = get_post_meta($post_id, 'factory_stoneguard_width', true);
    $factory_stoneguard_height = get_post_meta($post_id, 'factory_stoneguard_height', true);
    $toolbox_width_mm       = get_post_meta($post_id, 'toolbox_width_mm', true);
    $toolbox_height_mm      = get_post_meta($post_id, 'toolbox_height_mm', true);
    $support_pockets        = get_post_meta($post_id, 'support_pockets', true);

    $sts_var_caravan_bar_option     = get_post_meta($post_id, 'sts_var_caravan_bar_option', true);
    $sts_var_caravan_bar_bend       = get_post_meta($post_id, 'sts_var_caravan_bar_bend', true);
    $sts_var_caravan_ss_length_adj  = get_post_meta($post_id, 'sts_var_caravan_ss_length_adj', true);
    $sts_var_caravan_cut_out        = get_post_meta($post_id, 'sts_var_caravan_cut_out', true);
    $sts_var_caravan_mesh_only_measurement     = get_post_meta($post_id, 'sts_var_caravan_mesh_only_measurement', true);
    $sts_var_caravan_break_form     = get_post_meta($post_id, 'sts_var_caravan_break_form', true);
    $sts_var_caravan_hr_form        = get_post_meta($post_id, 'sts_var_caravan_hr_form', true);
    $sts_var_caravan_eyelet_tab        = get_post_meta($post_id, 'sts_var_caravan_eyelet_tab', true);
	$final_details = get_post_meta( $post_id, 'final_details', true );

	$final_delivery_address = "Same As Home Address";

	if($final_details && $final_details['final_delivery'] === 'move' ) {
		$final_delivery_address = "I am on the Move";
	}

    $proposed_date = get_post_meta($post_id, 'sts_var_proposed_date_of_delivery', true);
    $proposed_date = $proposed_date ? date('d-F-Y', strtotime($proposed_date)) : '-';

    // Start Section
    $section = $phpWord->addSection();

    // HEADER LAYOUT: Logo + Business Info + Order Info
    $table = $section->addTable();
    $table->addRow();

    $logo_cell = $table->addCell(5000, ['valign' => 'center']);
    $logo = get_template_directory() . '/assets/src/images/invoice-gaurd.png';

    if (file_exists($logo)) {
        $logo_cell->addImage($logo, ['width' => 100]);
    }

	$info_cell = $table->addCell(5000, ['valign' => 'center']);

	$textRun = $info_cell->addTextRun();
	$textRun->addText("ORDER DATE: ", ['bold' => true]);
	$textRun->addText($order_date);

	$textRun = $info_cell->addTextRun();
	$textRun->addText("ORDER NUMBER: ", ['bold' => true]);
	$textRun->addText($order_id);

    $section->addTextBreak(1);

	$compact = ['spaceBefore' => 0, 'spaceAfter' => 0];

	// CUSTOMER & DELIVERY + VEHICLE INFO SIDE-BY-SIDE
	$infoTable = $section->addTable(['cellMargin' => 80]);
	$infoTable->addRow();

	// LEFT COLUMN
	$leftCell = $infoTable->addCell(5000);

	$textRun = $leftCell->addTextRun($compact);
	$textRun->addText("Name: ", ['bold' => true]);
	$textRun->addText($customer_name);

	$textRun = $leftCell->addTextRun($compact);
	$textRun->addText("Phone: ", ['bold' => true]);
	$textRun->addText($customer_phone);

	$textRun = $leftCell->addTextRun($compact);
	$textRun->addText("Email: ", ['bold' => true]);
	$textRun->addText($customer_email);

	$textRun = $leftCell->addTextRun(['spaceBefore' => 0, 'spaceAfter' => 0]);
	$textRun->addText("Home Address: ", ['bold' => true]);
	// Nested table to restrict width
	$addressTable = $leftCell->addTable(['cellMargin' => 0]);
	$addressTable->addRow();
	$addressTable->addCell(3000)->addText(strip_tags($delivery_address), [], ['spaceBefore' => 0, 'spaceAfter' => 0]); // ~50% of 6000 cell

	$textRun = $leftCell->addTextRun(['spaceBefore' => 0, 'spaceAfter' => 0]);
	$textRun->addText("Delivery Address: ", ['bold' => true]);
	// Nested table to restrict width
	$addressTable = $leftCell->addTable(['cellMargin' => 0]);
	$addressTable->addRow();
	$addressTable->addCell(3000)->addText(strip_tags($final_delivery_address), [], ['spaceBefore' => 0, 'spaceAfter' => 0]); // ~50% of 6000 cell

	$textRun = $leftCell->addTextRun($compact);
	$textRun->addText("Delivery Instructions: ", ['bold' => true]);
	$textRun->addText($delivery_instructions ?: 'No');

	// RIGHT COLUMN
	$rightCell = $infoTable->addCell(5000);

	$textRun = $rightCell->addTextRun($compact);
	$textRun->addText("Trailer Make: ", ['bold' => true]);
	$textRun->addText($caravan_make);

	$textRun = $rightCell->addTextRun($compact);
	$textRun->addText("Trailer Model: ", ['bold' => true]);
	$textRun->addText($caravan_model);

	$textRun = $rightCell->addTextRun($compact);
	$textRun->addText("Vehicle Make: ", ['bold' => true]);
	$textRun->addText($vehicle_make);

	$textRun = $rightCell->addTextRun($compact);
	$textRun->addText("Vehicle Model: ", ['bold' => true]);
	$textRun->addText($vehicle_model);

	$textRun = $rightCell->addTextRun($compact);
	$textRun->addText("Vehicle Year: ", ['bold' => true]);
	$textRun->addText($vehicle_year);

	$textRun = $rightCell->addTextRun($compact);
	$textRun->addText("Date Required: ", ['bold' => true]);
	$textRun->addText($proposed_date);

	$section->addTextBreak(1);

    // MEASUREMENTS TABLE
    $section->addText("STONE STOMPER DETAILS", ['bold' => true, 'size' => 10]);

	$measure = $section->addTable(
		[
			'borderSize' => 6,
			'borderColor' => 'cdcdcd', // gray border
			'cellMarginTop' => 0,
			'cellMarginBottom' => 0,
			'cellMarginLeft' => 50,
			'cellMarginRight' => 50
		]
	);

	$row = $measure->addRow(200);
	$row->addCell(6000)->addText("SS Width (mm):", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText($caravan_width_mm, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("SS Length (mm):", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText($caravan_length_mm, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("Towing Vehicle Bar Width (mm):", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText($bar_width_mm, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("Vinyl Insert Width (mm):", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText("$vinyl_insert_width_mm", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("Vinyl Insert Length (mm):", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText("$vinyl_insert_height_mm", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("Stoneguard Length (mm):", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText("$factory_stoneguard_width", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("Stoneguard Distance from Caravan", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText("$factory_stoneguard_height", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("Toolbox Length (mm):", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText("$toolbox_width_mm", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("Toolbox Distance from the Caravan:", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText("$toolbox_height_mm", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("Support Pockets:", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText($support_pockets, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("Bar Option:", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText($sts_var_caravan_bar_option, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("Bar Bend:", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText($sts_var_caravan_bar_bend, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("SS Length Adjustment:", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText($sts_var_caravan_ss_length_adj, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("Cut Out:", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText($sts_var_caravan_cut_out, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("Mesh Only Measurement:", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText($sts_var_caravan_mesh_only_measurement, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("Break Foam:", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText($sts_var_caravan_break_form, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("HR Foam:", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText($sts_var_caravan_hr_form, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("Eyelet Tab:", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText($sts_var_caravan_eyelet_tab, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	// Wanna call vector svg here
	$svg = get_towing_diagram_svg_png($post_id);

	if ($svg) {
		$diagram_png = svg_to_png_temp($svg);
		if (file_exists($diagram_png)) {
			$section->addImage($diagram_png, [
				'width' => 400,
				'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
			]);
		}
	}

    $section->addTextBreak(2);

	$section->addText("ORDER DETAILS:", ['bold' => true, 'size' => 10]);

	$items_table = $section->addTable(
		[
			'borderSize' => 6,
			'borderColor' => 'cdcdcd',
			'cellMarginTop' => 0,
			'cellMarginBottom' => 0,
			'cellMarginLeft' => 50,
			'cellMarginRight' => 50
		]
	);

	$items_table->addRow(200);
	$items_table->addCell(1000)->addText("Quantity", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$items_table->addCell(5000)->addText("Product", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$items_table->addCell(2000)->addText("Unit Price", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$items_table->addCell(2000)->addText("Total", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$i = 1;

	foreach ($order->get_items() as $item) {
		$name = $item->get_name();
		$qty = $item->get_quantity();
		$total = wc_format_decimal($item->get_total(), 2);
		$unit = wc_format_decimal($item->get_total() / $qty, 2);

		$items_table->addRow(200);
		$items_table->addCell(1000)->addText($i++, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
		$items_table->addCell(5000)->addText($name, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
		$items_table->addCell(2000)->addText("$" . $unit, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
		$items_table->addCell(2000)->addText("$" . $total, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	}

	$items_table->addRow(200);
	$items_table->addCell(1000)->addText('', [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$items_table->addCell(5000)->addText('', [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$items_table->addCell(2000)->addText("Delivery:", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$items_table->addCell(2000)->addText("$" . $delivery_cost, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$items_table->addRow(200);
	$items_table->addCell(1000)->addText('', [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$items_table->addCell(5000)->addText('', [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$items_table->addCell(2000)->addText("Total Due:", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$items_table->addCell(2000)->addText("$" . $order_total, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$items_table->addRow(200);
	$items_table->addCell(1000)->addText('', [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$items_table->addCell(5000)->addText('', [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$items_table->addCell(2000)->addText('GST( Included)', [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$items_table->addCell(2000)->addText("-", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$section->addTextBreak(1);


    // Customer Notes
    $section->addText("CUSTOMER ORDER NOTES:", ['bold' => true]);
    $section->addText($delivery_instructions ?: 'No notes.');

    $section->addTextBreak(6);

	// HEADER TABLE (Logo + Company Info)
	$table = $section->addTable([
		'borderColor' => 'cdcdcd', // gray border
		'cellMarginTop' => 0,
		'cellMarginBottom' => 0,
		'cellMarginLeft' => 50,
		'cellMarginRight' => 50
	]);

	$table->addRow(300);

	// LEFT CELL → LOGO (same variable you used)
	$logo_cell = $table->addCell(3000, ['valign' => 'center']);
	$logo = get_template_directory() . '/assets/src/images/invoice-gaurd.png';
	if (file_exists($logo)) {
		$logo_cell->addImage($logo, ['width' => 100]);
	}

	// RIGHT CELL → TEXT (same text you used)
	$info_cell = $table->addCell(6000,  ['valign' => 'center']);

	// Company name bold (same variable structure)
	$info_cell->addText("Stone Stomper", ['bold' => true, 'size' => 10], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$info_cell->addText("PO Box 204, Port Noarlunga, SA 5167", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$info_cell->addText("Factory location: Lonsdale SA", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$info_cell->addText("Email: sales@stonestomper.com.au", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	// Small spacing below header
	$section->addTextBreak(1);


    // Save file
    $upload_dir = wp_upload_dir();
    $file_path = $upload_dir['path'] . "/customer-order-{$post_id}.docx";
    $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    $writer->save($file_path);

    return $file_path;
}




function download_customer_word_callback() {
    $post_id = intval($_GET['post_id'] ?? 0);

    $file_path = generate_customer_order_word_file($post_id);

    if (!$file_path || !file_exists($file_path)) {
        wp_die('File generation failed.');
    }

    header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
    header('Content-Disposition: attachment; filename="customer-order-' . $post_id . '.docx"');
    readfile($file_path);

    exit;
}

add_action( 'wp_ajax_download_customer_word', 'download_customer_word_callback' );
add_action( 'wp_ajax_nopriv_download_customer_word', 'download_customer_word_callback' );

function email_to_manufacturer_callback() {
	list( $sts_var_post_id, $sts_fields, $sts_option_fields ) = StoneStomper::defaults();

    $post_id = intval($_POST['post_id']);
    $file_path = generate_customer_order_word_file($post_id);

    // Get manufacturers list from option fields array
    $emails = [];

    if ( !empty($sts_option_fields['sts_var_manufacturers']) && is_array($sts_option_fields['sts_var_manufacturers']) ) {
        foreach ($sts_option_fields['sts_var_manufacturers'] as $manufacturer) {
            if (!empty($manufacturer['email'])) {
                $emails[] = $manufacturer['email'];
            }
        }
    }

    // Fallback if no emails found
    if (empty($emails)) {
        $emails = ['tmaeder@boylen.com.au'];
    }

    $subject = "New Customer Order Details (Order #{$post_id})";
    $message = "Hello,\n\nPlease find attached the customer order details document.\n\nThanks.";

    wp_mail($emails, $subject, $message, [], [$file_path]);

    wp_send_json_success('Email Sent');
}
add_action('wp_ajax_email_to_manufacturer', 'email_to_manufacturer_callback');



add_action('admin_enqueue_scripts', function($hook){

    global $post;

    // Sirf post editor screen par run karo
    if ($hook !== 'post.php' && $hook !== 'post-new.php') return;

    // Sirf hamari required post type ke liye
    if (!isset($post) || $post->post_type !== 'customer') return;

    // Admin JS enqueue
    wp_enqueue_script(
        'customer-admin-js',
        get_template_directory_uri() . '/assets/src/js/customer-admin.js',
        ['jquery'],
        false,
        true
    );

});





// 1️⃣ Register the new "Manufacturing Queue" status
add_action( 'init', function() {
	register_post_status( 'wc-manufacturing', array(
		'label'                     => 'Manufacturing Queue',
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Manufacturing Queue <span class="count">(%s)</span>', 'Manufacturing Queue <span class="count">(%s)</span>' ),
	) );
} );

// 2️⃣ Add it to WooCommerce status dropdowns (in admin & everywhere)
add_filter( 'wc_order_statuses', function( $statuses ) {
	// Insert after "processing"
	$new_statuses = [];

	foreach ( $statuses as $key => $label ) {
		$new_statuses[ $key ] = $label;

		if ( 'wc-processing' === $key ) {
			$new_statuses['wc-manufacturing'] = __( 'Manufacturing Queue', 'stonestomper_td' );
		}
	}

	return $new_statuses;
} );


/**
 * Add a WooCommerce Order Status meta box to single Customer (Order) edit screen
 */
add_action( 'add_meta_boxes', function() {
	add_meta_box(
		'customer_order_status_box',
		__( 'Order Status', 'stonestomper_td' ),
		'stonestomper_render_order_status_box',
		'customer', // your CPT slug
		'side',
		'high'
	);
} );

/**
 * Render the status dropdown
 */
function stonestomper_render_order_status_box( $post ) {
	$order_id = get_field( 'order_id', $post->ID ); // your linked WooCommerce order ID
	if ( ! $order_id ) {
		echo '<p><em>No linked WooCommerce order found.</em></p>';
		return;
	}

	$order = wc_get_order( $order_id );
	if ( ! $order ) {
		echo '<p><em>Invalid WooCommerce order.</em></p>';
		return;
	}

	$current_status = $order->get_status();
	$statuses       = wc_get_order_statuses();

	echo '<select name="wc_order_status" id="wc_order_status" style="width:100%;">';
	foreach ( $statuses as $status_key => $status_label ) {
		$selected = selected( $current_status, str_replace( 'wc-', '', $status_key ), false );
		echo '<option value="' . esc_attr( $status_key ) . '" ' . $selected . '>' . esc_html( $status_label ) . '</option>';
	}
	echo '</select>';

	wp_nonce_field( 'update_wc_order_status_nonce', 'wc_order_status_nonce' );
}

/**
 * Save WooCommerce status change
 */
add_action( 'save_post_customer', function( $post_id, $post, $update ) {
	// Permission + nonce check
	if ( ! isset( $_POST['wc_order_status_nonce'] ) || ! wp_verify_nonce( $_POST['wc_order_status_nonce'], 'update_wc_order_status_nonce' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( empty( $_POST['wc_order_status'] ) ) {
		return;
	}

	$order_id = get_field( 'order_id', $post_id );
	if ( ! $order_id ) {
		return;
	}

	$order = wc_get_order( $order_id );
	if ( ! $order ) {
		return;
	}

	$new_status = sanitize_text_field( $_POST['wc_order_status'] );
	$order->update_status( str_replace( 'wc-', '', $new_status ) );
}, 10, 3 );



add_filter('woocommerce_get_price_html', function($price_html, $product) {
	$raw_price = $product->get_price();
	if (is_numeric($raw_price)) {
		$formatted = number_format((float)$raw_price, 2, '.', '');
		$price_html = wc_price($formatted);
	}
	return $price_html;
}, 10, 2);

add_action('init', function() {
    if ( isset($_GET['test_sts']) ) {
        $order = wc_get_order( 1215 );
        exit;
    }
});



// add_action('woocommerce_cart_calculate_fees', function($cart) {
//     if (is_admin() && !defined('DOING_AJAX')) return;

//     // Loop through all cart items
//     foreach ($cart->get_cart() as $cart_item) {
//         $barwidth       = $cart_item['barwidth_mm'] ?? 0;
//         $a_frame_length = $cart_item['a_frame_length_mm'] ?? 0;

//         // --- Bar Width Extra Charges ---
//         if ($barwidth >= 1900 && $barwidth <= 2100) {
//             $cart->add_fee(__('Extra Bar Width (1900–2100mm)', 'stone-stomper'), 35);
//         } elseif ($barwidth > 2100) {
//             $cart->add_fee(__('Extra Bar Width (>2100mm)', 'stone-stomper'), 100);
//         }

//         // --- A-Frame Length Extra Charges ---
//         if ($a_frame_length >= 1800 && $a_frame_length <= 2300) {
//             $cart->add_fee(__('Extra Mesh Length (1800–2300mm)', 'stone-stomper'), 35);
//         } elseif ($a_frame_length > 2300) {
//             $cart->add_fee(__('Extra Mesh Length (>2300mm)', 'stone-stomper'), 100);
//         }
//     }
// });

add_action('woocommerce_cart_calculate_fees', function($cart) {
    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    $has_stone_stomper = false;

    // First loop — check if Stone Stomper exists in cart
    foreach ($cart->get_cart() as $cart_item) {
        if (!empty($cart_item['product_type']) && $cart_item['product_type'] === '545') {
            $has_stone_stomper = true;
            break;
        }
    }

    // If NOT stone stomper → no fees, no extra charges
    if (!$has_stone_stomper) {
        return;
    }

    // Second loop — apply extra fees but ONLY for stone stomper product
    foreach ($cart->get_cart() as $cart_item) {

        if (
            empty($cart_item['product_type']) ||
            $cart_item['product_type'] !== '545'
        ) {
            continue; // skip non-stone-stomper items
        }

        $barwidth       = isset($cart_item['barwidth_mm']) ? floatval($cart_item['barwidth_mm']) : 0;
        $a_frame_length = isset($cart_item['a_frame_length_mm']) ? floatval($cart_item['a_frame_length_mm']) : 0;

        // --- Bar Width Extra Charges ---
        if ($barwidth >= 1900 && $barwidth <= 2100) {
            $cart->add_fee(__('Extra Bar Width (1900–2100mm)', 'stone-stomper'), 35);
        } elseif ($barwidth > 2100) {
            $cart->add_fee(__('Extra Bar Width (>2100mm)', 'stone-stomper'), 100);
        }

        // --- A-Frame Length Extra Charges ---
        if ($a_frame_length >= 1800 && $a_frame_length <= 2300) {
            $cart->add_fee(__('Extra Mesh Length (1800–2300mm)', 'stone-stomper'), 35);
        } elseif ($a_frame_length > 2300) {
            $cart->add_fee(__('Extra Mesh Length (>2300mm)', 'stone-stomper'), 100);
        }
    }
});

