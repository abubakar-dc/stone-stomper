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


