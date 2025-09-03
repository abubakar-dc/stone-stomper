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
add_filter('woocommerce_get_item_data', function($item_data, $cart_item) {
    $fields = [
        'barwidth_mm'              => 'Towing Vehicle Barwidth',
        'caravan_width_mm'         => 'Caravan Width',
        'caravan_clearance_gap_mm' => 'Caravan Clearance Gap',
        'vinyl_inserts'            => 'Vinyl Inserts',
        'support_pockets'          => 'Support Pockets',
    ];

    foreach ($fields as $key => $label) {
        if (!empty($cart_item[$key])) {
            $item_data[] = [
                'key'   => $label,
                'value' => wc_clean($cart_item[$key]),
            ];
        }
    }

    return $item_data;
}, 10, 2);

// Save to order items
add_action('woocommerce_checkout_create_order_line_item', function($item, $cart_item_key, $values, $order) {
    $fields = [
        'barwidth_mm'              => 'Towing Vehicle Barwidth',
        'caravan_width_mm'         => 'Caravan Width',
        'caravan_clearance_gap_mm' => 'Caravan Clearance Gap',
        'vinyl_inserts'            => 'Vinyl Inserts',
    ];

    foreach ($fields as $key => $label) {
        if (!empty($values[$key])) {
            $item->add_meta_data($label, $values[$key], true);
        }
    }
}, 10, 4);

