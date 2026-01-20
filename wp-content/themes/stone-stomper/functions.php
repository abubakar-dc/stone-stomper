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
		$support_pockets_measurement      = get_post_meta( $post_id, 'support_pockets_measurement', true );
		$toolbox_width_mm       = get_post_meta( $post_id, 'toolbox_width_mm', true );
		$bar_width_mm           = get_post_meta( $post_id, 'bar_width_mm', true );
		$vinyl_insert_width_mm  = get_post_meta( $post_id, 'vinyl_insert_width_mm', true );
		$vinyl_insert_height_mm = get_post_meta( $post_id, 'vinyl_insert_height_mm', true );
		$stoneguard_width_mm    = get_post_meta( $post_id, 'factory_stoneguard_width', true );
		$stoneguard_height_mm   = get_post_meta( $post_id, 'factory_stoneguard_height', true );
		$sts_var_caravan_cut_out  = get_post_meta( $post_id, 'sts_var_caravan_cut_out', true );
		$sts_var_caravan_ss_length_adj = get_post_meta( $post_id, 'sts_var_caravan_ss_length_adj', true );
		if ( $sts_var_caravan_ss_length_adj !== '' ) {
			$caravan_length_mm = (int) $caravan_length_mm + (int) $sts_var_caravan_ss_length_adj;
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
					<text class="st5" fill="#fa3232" stroke="#fa3232" stroke-width="0.3" text-anchor="middle" dominant-baseline="middle" y="0" style="font-size: 20px; font-weight: 500;">
						<?php echo esc_html( $sts_var_caravan_cut_out ? $sts_var_caravan_cut_out : '-' ); ?>
					</text>
				</g>
			<?php } ?>
			<circle class="st2" cx="928.02" cy="177.82" r="5.22" fill="#ffffff" stroke="#000000"/>
			<circle class="st2" cx="283.6" cy="177.82" r="5.22" fill="#ffffff" stroke="#000000"/>
			<rect class="st2" x="390.67" y="364.08" width="438.42" height="27.06" fill="#ffffff" stroke="#000000"/>
			<rect class="st3" x="504.04" y="315.51" width="211.2" height="195.21" fill="#ffffff" stroke="#000000"/>
			<?php if($vinyl_insert_width_mm){ ?>
				<g transform="translate(610.76,330.99)">
					<text class="st5" fill="#fa3232" stroke="#fa3232" stroke-width="0.3" text-anchor="middle" dominant-baseline="middle" y="0" style="font-size: 20px; font-weight: 500;"><?php echo esc_html( $vinyl_insert_width_mm ? $vinyl_insert_width_mm : '-' ); ?></text>
				</g>
			<?php } ?>
			<?php if($vinyl_insert_height_mm){ ?>
				<g transform="translate(520.76,400.99) rotate(-90)">
					<text class="st5" fill="#fa3232" stroke="#fa3232" stroke-width="0.3" text-anchor="middle" dominant-baseline="middle" y="0" style="font-size: 20px; font-weight: 500;"><?php echo esc_html( $vinyl_insert_height_mm ? $vinyl_insert_height_mm : '-' ); ?></text>
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
			<text class="st5" transform="translate(565.96 411.74)" style="font-size: 20px; font-weight: 500;">
				<tspan fill="#fa3232" stroke="#fa3232" stroke-width="0.3" x="0" y="0">Vinyl Insert</tspan>
			</text>
			<g>
				<line class="st0" x1="274.29" y1="139.99" x2="935.24" y2="139.99" stroke="#fa3232"/>
				<g transform="translate(604.76,139.99)">
					<rect class="st4" x="-67.5" y="-22" width="135" height="24" fill="#ffffff"  stroke="#ffffff"/>
					<text class="st5" fill="#fa3232" stroke="#fa3232" stroke-width="0.3" text-anchor="middle" dominant-baseline="middle" y="0" style="font-size: 20px; font-weight: 500;"><?php echo esc_html( $caravan_width_mm ? $caravan_width_mm : '-' ); ?></text>
				</g>
				<polyline class="st0" points="278.3 143.72 274.29 139.99 278.3 136.27" stroke="#fa3232" fill="#ffffff"/>
				<polyline class="st0" points="931.24 136.27 935.24 140 931.24 143.72" stroke="#fa3232"  fill="#ffffff"/>
			</g>
			<g>
				<line class="st0" x1="317.29" y1="608.6" x2="894.24" y2="608.6" stroke="#fa3232"/>
				<g transform="translate(957.8,375.13)">
				<rect class="st4" x="10" y="-12" width="135" height="24" fill="#ffffff"  stroke="#ffffff"/>
				<text class="st5" text-anchor="start" dominant-baseline="middle" x="20" style="font-size: 20px; font-weight: 500;"><?php echo esc_html( $caravan_length_mm ? $caravan_length_mm : '-' ); ?></text>
				</g>
				<polyline class="st0" points="321.3 612.33 317.29 608.6 321.3 604.88" stroke="#fa3232" fill="#ffffff"/>
				<polyline class="st0" points="890.24 604.88 894.24 608.6 890.24 612.33" stroke="#fa3232" fill="#ffffff"/>
			</g>


			<g>
				<line class="st0" x1="957.8" y1="170.34" x2="957.8" y2="579.93" stroke="#fa3232"/>
				<g transform="translate(605.77,608.6)">
					<rect class="st4" x="-67.5" y="-12" width="135" height="24" fill="#ffffff" stroke="#ffffff"/>
					<text class="st5" text-anchor="middle" dominant-baseline="middle" y="0" fill="#fa3232" stroke="#fa3232" stroke-width="0.3" style="font-size: 20px; font-weight: 500;"><?php echo esc_html( $bar_width_mm ? $bar_width_mm : '-' ); ?></text>
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
					<text class="st5" fill="#fa3232" stroke="#fa3232" stroke-width="0.3" text-anchor="middle" dominant-baseline="middle" y="0" style="font-size: 20px; font-weight: 500;">
						<?php echo esc_html( $sts_var_caravan_cut_out ? $sts_var_caravan_cut_out : '-' ); ?>
					</text>
				</g>
			<?php } ?>
			<circle class="st4" cx="928.02" cy="177.82" r="5.22" fill="#ffffff" stroke="#000000"/>
			<circle class="st4" cx="283.6" cy="177.82" r="5.22" fill="#ffffff" stroke="#000000"/>
			<rect class="st4" x="390.67" y="364.08" width="438.42" height="27.06" fill="#ffffff" stroke="#000000"/>
			<rect class="st7" x="504.04" y="315.51" width="211.2" height="195.21" fill="#ffffff" stroke="#000000"/>
			<?php if($vinyl_insert_width_mm){ ?>
				<g transform="translate(610.76,330.99)">
					<text class="st5" fill="#fa3232" stroke="#fa3232" stroke-width="0.3" text-anchor="middle" dominant-baseline="middle" y="0"  style="font-size: 20px; font-weight: 500;">
						<?php echo esc_html( $vinyl_insert_width_mm ? $vinyl_insert_width_mm : '-' ); ?>
					</text>
				</g>
			<?php } ?>

			<?php if($vinyl_insert_height_mm){ ?>
				<g transform="translate(520.76,400.99) rotate(-90)">
					<text class="st5" fill="#fa3232" stroke="#fa3232" stroke-width="0.3" text-anchor="middle" dominant-baseline="middle" y="0"  style="font-size: 20px; font-weight: 500;">
						<?php echo esc_html( $vinyl_insert_height_mm ? $vinyl_insert_height_mm : '-' ); ?>
					</text>
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
			<!-- <text class="st6" transform="translate(565.96 411.74)"><tspan fill="#fa3232" x="0" y="0" stroke="">Vinyl Insert</tspan></text> -->
			<text transform="translate(565.96 411.74)"
				fill="#fa3232"
				stroke="#fa3232"
				stroke-width="0.3"
				stroke-linejoin="round" style="font-size: 20px; font-weight: 500;">
				Vinyl Insert
			</text>
			<g>
				<line class="st0" x1="274.29" y1="139.99" x2="935.24" y2="139.99" fill="#ffffff" stroke="#fa3232"/>
				<rect class="st8" x="539.36" y="127.61" width="135.53" height="22.16" fill="#ffffff" stroke="#ffffff"/>
				<g transform="translate(604.76,139.99)">
					<text class="st5" fill="#fa3232" stroke="#fa3232" stroke-width="0.3" text-anchor="middle" dominant-baseline="middle" y="0" style="font-size: 20px; font-weight: 500;">
						<?php echo esc_html( $caravan_width_mm ? $caravan_width_mm : '-' ); ?>
					</text>
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
					<text class="st5" text-anchor="middle" dominant-baseline="middle" y="0"  fill="#fa3232"  stroke="#fa3232" stroke-width="0.3" style="font-size: 20px; font-weight: 500;"><?php echo esc_html( $bar_width_mm ? $bar_width_mm : '-' ); ?></text>
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
					<text class="st5" text-anchor="start" dominant-baseline="middle" x="20" fill="#fa3232"  stroke="#fa3232" stroke-width="0.3" style="font-size: 20px; font-weight: 500;"><?php echo esc_html( $caravan_length_mm ? $caravan_length_mm : '-' ); ?></text>
				</g>
				<polyline class="st0" points="954.07 174.34 957.8 170.34 961.52 174.34" fill="#ffffff" stroke="#fa3232"/>
				<polyline class="st0" points="961.52 575.92 957.8 579.93 954.07 575.92" fill="#ffffff" stroke="#fa3232"/>
			</g>
			<g transform="translate(280,220.13)">
				<g transform="translate(-835,-220)">
					<line class="st0" x1="884.28" y1="181.78" x2="884.28" y2="286.37" fill="#ffffff" stroke="#fa3232"/>
					<rect class="st8" x="873.2" y="212.51" width="22.16" height="43.67" fill="#ffffff" stroke="#ffffff"/>
					<polyline class="st0" points="880.55 185.79 884.28 181.78 888 185.79" fill="#ffffff" stroke="#fa3232"/>
					<polyline class="st0" points="888 282.37 884.28 286.37 880.55 282.37" fill="#ffffff" stroke="#fa3232"/>
				</g>
				<?php if($stoneguard_height_mm){ ?>
					<text class="st5" text-anchor="start" dominant-baseline="middle" y="0" x="55" fill="#fa3232" stroke="#fa3232" stroke-width="0.3" style="font-size: 20px; font-weight: 500;">
						<?php echo esc_html( $stoneguard_height_mm ? 'S: '. $stoneguard_height_mm : '-' ); ?>
					</text>
				<?php } ?>
				<?php if($toolbox_height_mm){ ?>
					<text class="st5" text-anchor="start" dominant-baseline="middle" y="20" x="55"  fill="#fa3232" stroke="#fa3232" stroke-width="0.3" style="font-size: 20px; font-weight: 500;">
						<?php echo esc_html( $toolbox_height_mm ? 'T: '.$toolbox_height_mm : '-' ); ?>
					</text>
				<?php } ?>
				<?php if($support_pockets_measurement){ ?>
					<text class="st5" text-anchor="start" dominant-baseline="middle" y="40" x="55"  fill="#fa3232"  stroke="#fa3232" stroke-width="0.3" style="font-size: 20px; font-weight: 500;">
						<?php echo esc_html( $support_pockets_measurement ? 'SP: '.$support_pockets_measurement : '-' ); ?>
					</text>
				<?php } ?>
			</g>
			<g id="Toolbox" transform="translate(0,30)">
				<g>
					<g transform="translate(0,-10)">
						<line class="st0" x1="352.44" y1="266.29" x2="860.13" y2="266.29" fill="#ffffff" stroke="#fa3232"/>
						<rect class="st8" x="539.36" y="253.9" width="135.53" height="22.16" fill="#ffffff"  stroke="#ffffff"/>
						<polyline class="st0" points="356.43 270.01 352.42 266.29 356.43 262.56" fill="#ffffff" stroke="#fa3232"/>
						<polyline class="st0" points="856.13 262.56 860.13 266.29 856.13 270.01" fill="#ffffff" stroke="#fa3232"/>
					</g>
					<g transform="translate(555,263.13)">
						<?php if($stoneguard_width_mm){ ?>
						<text class="st5" text-anchor="start" dominant-baseline="middle" y="-20" x="0" fill="#fa3232" stroke="#fa3232" stroke-width="0.3" style="font-size: 20px; font-weight: 500;">
							<?php echo esc_html( $stoneguard_width_mm ? 'S: '. $stoneguard_width_mm : '-' ); ?>
						</text>
						<?php } ?>
						<?php if($toolbox_height_mm){ ?>
							<text class="st5" text-anchor="start" dominant-baseline="middle" x="0" fill="#fa3232" stroke="#fa3232" stroke-width="0.3" style="font-size: 20px; font-weight: 500;">
								<?php echo esc_html( $toolbox_width_mm ? 'T: '. $toolbox_width_mm : '-' ); ?>
							</text>
						<?php } ?>
					</g>

				</g>
			</g>
		</svg>
	<?php } ?>
	<?php return ob_get_clean(); ?>
<?php }

function get_towing_diagram_svg_png($post_id) {
    $html = render_towing_diagram($post_id);

    // Extract only the <svg>...</svg> content
    if (preg_match('/<svg[^>]*>.*<\/svg>/is', $html, $match)) {
        $svg = $match[0];
        return $svg;
    }

    return false;
}

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

function sts_get_images_from_meta( $post_id, $meta_key ) {
	$raw = get_post_meta( $post_id, $meta_key, true );

	if ( empty( $raw ) ) {
		return [];
	}

	if ( is_string( $raw ) ) {
		$decoded = json_decode( $raw, true );
		if ( is_array( $decoded ) ) {
			return $decoded;
		}
	}

	if ( is_array( $raw ) ) {
		return $raw;
	}

	return [];
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
	$product_type = get_post_meta( $post->ID, 'product_type', true );

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


	// New fields
    $extension_plate        = get_post_meta($post->ID, 'extension_plate', true);
    $fittings        = get_post_meta($post->ID, 'fittings', true);
    $sleeve        = get_post_meta($post->ID, 'sleeve', true);
    $extra_bungee        = get_post_meta($post->ID, 'extra_bungee', true);
    $extra_vinyl_width_mm        = get_post_meta($post->ID, 'extra_vinyl_width_mm', true);
    $extra_vinyl_length_mm        = get_post_meta($post->ID, 'extra_vinyl_length_mm', true);
    $extra_vinyl_position        = get_post_meta($post->ID, 'extra_vinyl_position', true);
    $angled_stone_guard_width_mm        = get_post_meta($post->ID, 'angled_stone_guard_width_mm', true);
    $angled_stone_guard_depth_mm        = get_post_meta($post->ID, 'angled_stone_guard_depth_mm', true);

    $vehicle_make    = get_post_meta( $post->ID, 'vehicle_make', true );
    $caravan_make    = get_post_meta( $post->ID, 'caravan_make', true );
    $sts_var_caravan_bar_option    	= get_post_meta( $post->ID, 'sts_var_caravan_bar_option', true );
    $sts_var_caravan_bar_bend    	= get_post_meta( $post->ID, 'sts_var_caravan_bar_bend', true );
	$sts_var_caravan_ss_length_adj = get_post_meta( $post->ID, 'sts_var_caravan_ss_length_adj', true );


	if ( $sts_var_caravan_ss_length_adj !== '' ) {
		$caravan_length_mm = (int) $caravan_length_mm + (int) $sts_var_caravan_ss_length_adj;
	}

    $sts_var_caravan_cut_out    = get_post_meta( $post->ID, 'sts_var_caravan_cut_out', true );
    $sts_var_caravan_mesh_only_measurement    = get_post_meta( $post->ID, 'sts_var_caravan_mesh_only_measurement', true );
    $sts_var_caravan_foam    = get_post_meta( $post->ID, 'sts_var_caravan_foam', true );

    $sts_var_caravan_eyelet_tab    = get_post_meta( $post->ID, 'sts_var_caravan_eyelet_tab', true );
    $sts_var_proposed_date_of_delivery    = get_post_meta( $post->ID, 'sts_var_proposed_date_of_delivery', true );

	$hitch_images = sts_get_images_from_meta( $post->ID, 'hitch_images', true );
	$rear_images  = sts_get_images_from_meta( $post->ID, 'rear_images', true );
	$front_images = sts_get_images_from_meta( $post->ID, 'front_images', true );
	$support_pockets = get_post_meta( $post->ID, 'support_pockets', true );
	$support_pockets_measurement    = get_post_meta( $post->ID, 'support_pockets_measurement', true );

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

	function sts_get_image_url( $value ) {
		if ( is_numeric( $value ) ) {
			return wp_get_attachment_url( (int) $value );
		}

		if ( filter_var( $value, FILTER_VALIDATE_URL ) ) {
			return esc_url( $value );
		}

		return false;
	}
	?>
	<div class="customer-upload-images">
		<?php if ( ! empty( $hitch_images ) ) { ?>
			<div class="row row-1">
				<h3>Hitch Images</h3>
				<div class="hitch-images image-group">
					<?php foreach ( $hitch_images as $id ) :

						$img_url = sts_get_image_url( $id );
						if ( ! $img_url ) continue;
					?>

						<img src="<?php echo esc_url( $img_url ); ?>" alt="" class="popup-image" />

					<?php endforeach; ?>
				</div>
			</div>
		<?php } ?>
		<?php if ( $rear_images ) { ?>
			<div class="row row-1">
				<h3>Rear Images</h3>
				<div class="rear-images image-group">
					<?php foreach ( $rear_images as $hitch_id ) :

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
		<?php if ( $front_images ) { ?>
			<div class="row row-1">
				<h3>Front Images</h3>
				<div class="front-images image-group">
					<?php foreach ( $front_images as $hitch_id ) :
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

	<?php if($hitch_images || $rear_images || $front_images){ ?>
		<div style="margin-top:96px;"></div>
	<?php } ?>

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
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Product Type</td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $product_type ? $product_type : '-' ); ?></td>
			</tr>
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
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">SG Distance From The Carvan (mm):</td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $factory_stoneguard_height ? $factory_stoneguard_height.' mm': '-' ); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Toolbox Width (mm):</td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $toolbox_width_mm ? $toolbox_width_mm.' mm': '-' ); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Toolbox Distance from the Caravan:</td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $toolbox_height_mm ? $toolbox_height_mm.' mm': '-' ); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Support Pockets:</td>

				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $support_pockets ?: '-' ); ?></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold;">Support Pocket Distance From Caravan (mm) :</td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $support_pockets_measurement ?: '-' ); ?></td>
			</tr>

			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold; ">Bar Option</span></td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_bar_option); ?></span></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold; ">Bar Bend</span></td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_bar_bend); ?></span></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold; ">SS Length Adjustment</span></td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_ss_length_adj); ?></span></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold; ">Cut Out</span></td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_cut_out); ?></span></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold; ">Mesh only Measurement</span></td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_mesh_only_measurement); ?></span></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold; ">Foam</span></td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_foam); ?></span></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold; ">Eyelet Tab</span></td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_eyelet_tab); ?></span></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold; ">Angled Stone Guard Width (mm)</span></td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><span> <?php echo html_entity_decode($angled_stone_guard_width_mm); ?></span></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold; ">Angled Stone Guard Depth (mm)</span></td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><span> <?php echo html_entity_decode($angled_stone_guard_depth_mm); ?></span></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold; ">Extension Plate</span></td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><span> <?php echo html_entity_decode($extension_plate); ?></span></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold; ">Fittings</span></td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><span> <?php echo html_entity_decode($fittings); ?></span></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold; ">Sleeve</span></td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><span> <?php echo html_entity_decode($sleeve); ?></span></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold; ">Extra Bungee</span></td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><span> <?php echo html_entity_decode($extra_bungee); ?></span></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold; ">Extra Vinyl Width (mm)</span></td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><span> <?php echo html_entity_decode($extra_vinyl_width_mm); ?></span></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold; ">Extra Vinyl Length (mm)</span></td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><span> <?php echo html_entity_decode($extra_vinyl_length_mm); ?></span></td>
			</tr>
			<tr>
				<td style="padding:6px 15px; border:1px solid #ccc; font-weight:bold; ">Extra Vinyl Position</span></td>
				<td style="padding:6px 15px; border:1px solid #ccc;"><span> <?php echo html_entity_decode($extra_vinyl_position); ?></span></td>
			</tr>

		</table>
	</div>

	<!-- output these images over here -->
	<div style="margin-top:30px;"></div>

    <!-- Popup container -->
    <div id="order-popup" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:9999;">
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
						<?php if($product_type){ ?>
							<strong>Product Type: </strong><?php echo esc_html( $product_type ); ?>
						<?php } ?>
						&nbsp;&nbsp;&nbsp;
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
						<td style="text-align:center;"><strong>Quantity</strong></td>
						<td style="text-align:center;"><strong>Product</strong></td>
						<td style="text-align:center;"><strong>Unit Price</strong></td>
						<td style="text-align:center;"><strong>Total</strong></td>
					</tr>
					<?php if ( $products ) { ?>
						<?php foreach( $products as $key =>  $product ){ ?>
							<tr>
								<td style="text-align:center;"><?php echo esc_html( $product['quantity'] ); ?></td>
								<td style="text-align:center;"><?php echo esc_html( $product['name'] ); ?></td>
								<td style="text-align:center;">$<?php echo wc_format_decimal( $product['total'] / $product['quantity'], 2 ); ?></td>
								<td style="text-align:center;">$<?php echo wc_format_decimal( $product['total'], 2 ); ?></td>
							</tr>
						<?php }
					} ?>
					<br>
					<tr>
						<td style="text-align:center;"><!-- remain empty --></td>
						<td style="text-align:center;"><!-- remain empty --></td>
						<td style="text-align:center;"><strong>Delivery</strong></td>
						<td style="text-align:center;"><?php echo esc_html( '$' . $delivery_cost ); ?></td>
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
						<?php if($product_type){ ?>
							<strong>Product Type: </strong><?php echo esc_html( $product_type ); ?>
						<?php } ?>
						&nbsp;&nbsp;&nbsp;
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
					<table class="order-table" style="width:100%; border-collapse:collapse;">
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
							<td style="padding:6px 15px; border:1px solid #ccc;">SG Distance From The Carvan (mm):</td>
							<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $factory_stoneguard_height ? $factory_stoneguard_height : '-' ); ?></td>
						</tr>
						<tr>
							<td style="padding:6px 15px; border:1px solid #ccc;">Toolbox Width (mm):</td>
							<td style="padding:6px 15px; border:1px solid #ccc;"><?php echo esc_html( $toolbox_width_mm ? $toolbox_width_mm : '-' ); ?></td>
						</tr>
						<tr>
							<td style="padding:6px 15px; border:1px solid #ccc;">Toolbox Distance from the Caravan:</td>
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
							<td style="padding:6px 15px; border:1px solid #ccc; ">Mesh only Measurement</span></td>
							<td style="padding:6px 15px; border:1px solid #ccc;"><span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_mesh_only_measurement); ?></td>
						</tr>
						<tr>
							<td style="padding:6px 15px; border:1px solid #ccc;">Foam</span></td>
							<td style="padding:6px 15px; border:1px solid #ccc;"><span class="clr-red">
								<?php echo html_entity_decode($sts_var_caravan_foam);?>
							</td>
						</tr>

						<tr>
							<td style="padding:6px 15px; border:1px solid #ccc; ">Eyelet Tab</span></td>
							<td style="padding:6px 15px; border:1px solid #ccc;"><span class="clr-red"> <?php echo html_entity_decode($sts_var_caravan_eyelet_tab); ?></td>
						</tr>
						<tr>
							<td style="padding:6px 15px; border:1px solid #ccc; ">Angled Stone Guard Width (mm)</span></td>
							<td style="padding:6px 15px; border:1px solid #ccc;"><span> <?php echo html_entity_decode($angled_stone_guard_width_mm); ?></span></td>
						</tr>
						<tr>
							<td style="padding:6px 15px; border:1px solid #ccc; ">Angled Stone Guard Depth (mm)</span></td>
							<td style="padding:6px 15px; border:1px solid #ccc;"><span> <?php echo html_entity_decode($angled_stone_guard_depth_mm); ?></span></td>
						</tr>
						<tr>
							<td style="padding:6px 15px; border:1px solid #ccc; ">Extension Plate</span></td>
							<td style="padding:6px 15px; border:1px solid #ccc;"><span> <?php echo html_entity_decode($extension_plate); ?></span></td>
						</tr>
						<tr>
							<td style="padding:6px 15px; border:1px solid #ccc; ">Fittings</span></td>
							<td style="padding:6px 15px; border:1px solid #ccc;"><span> <?php echo html_entity_decode($fittings); ?></span></td>
						</tr>
						<tr>
							<td style="padding:6px 15px; border:1px solid #ccc; ">Sleeve</span></td>
							<td style="padding:6px 15px; border:1px solid #ccc;"><span> <?php echo html_entity_decode($sleeve); ?></span></td>
						</tr>
						<tr>
							<td style="padding:6px 15px; border:1px solid #ccc; ">Extra Bungee</span></td>
							<td style="padding:6px 15px; border:1px solid #ccc;"><span> <?php echo html_entity_decode($extra_bungee); ?></span></td>
						</tr>
						<tr>
							<td style="padding:6px 15px; border:1px solid #ccc; ">Extra Vinyl Width (mm)</span></td>
							<td style="padding:6px 15px; border:1px solid #ccc;"><span> <?php echo html_entity_decode($extra_vinyl_width_mm); ?></span></td>
						</tr>
						<tr>
							<td style="padding:6px 15px; border:1px solid #ccc; ">Extra Vinyl Length (mm)</span></td>
							<td style="padding:6px 15px; border:1px solid #ccc;"><span> <?php echo html_entity_decode($extra_vinyl_length_mm); ?></span></td>
						</tr>
						<tr>
							<td style="padding:6px 15px; border:1px solid #ccc; ">Extra Vinyl Position</span></td>
							<td style="padding:6px 15px; border:1px solid #ccc;"><span> <?php echo html_entity_decode($extra_vinyl_position); ?></span></td>
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
    $phpWord->setDefaultFontName('Arial');
    $phpWord->setDefaultFontSize(10);

    $order_id = get_post_meta($post_id, 'order_id', true);
    $order = $order_id ? wc_get_order($order_id) : false;

    // Fallbacks
    $order_date = $order ? $order->get_date_created()->date_i18n('d-F-Y') : get_the_date('d-F-Y', $post_id);
    $customer_name = $order ? $order->get_formatted_billing_full_name() : get_post_meta($post_id, 'name', true);
    $customer_phone = get_post_meta($post_id, 'customer_phone', true);
    $customer_email = $order ? $order->get_billing_email() : get_post_meta($post_id, 'email', true);

    $first_name = $order ? $order->get_shipping_first_name() : '';
    $last_name = $order ? $order->get_shipping_last_name() : '';
    $company = $order ? $order->get_shipping_company() : '';
    $address_1 = $order ? $order->get_shipping_address_1() : '';
    $address_2 = $order ? $order->get_shipping_address_2() : '';
    $city = $order ? $order->get_shipping_city() : '';
    $state = $order ? $order->get_shipping_state() : '';
    $postcode = $order ? $order->get_shipping_postcode() : '';
    $country = $order ? $order->get_shipping_country() : '';

    $delivery_address = implode(' ', array_filter([
        trim($first_name . ' ' . $last_name),
        $company,
        $address_1,
        $address_2,
        $city,
        $state,
        $postcode,
        $country
    ]));

    $delivery_cost = $order ? $order->get_shipping_total() : '-';
    $order_total = $order ? $order->get_total() : '-';
    $delivery_instructions = $order ? $order->get_customer_note() : 'No';

	function wpword_escape($value) {
		if (!is_string($value)) {
			return $value;
		}
		// Strip HTML tags and escape XML entities
		return htmlspecialchars(strip_tags($value), ENT_QUOTES | ENT_XML1, 'UTF-8');
	}

    // Fetch custom measurement meta
    $customer_name           						= get_post_meta($post_id, 'name', true);
    $customer_email           						= get_post_meta($post_id, 'email', true);
    $customer_phone           						= get_post_meta($post_id, 'customer_phone', true);
    $product_type           						= get_post_meta($post_id, 'product_type', true);
    $address_meta_address           				= get_post_meta($post_id, 'delivery_address', true);
    $caravan_make           						= get_post_meta($post_id, 'caravan_make', true);
    $caravan_model           						= get_post_meta($post_id, 'caravan_model', true);
    $vehicle_make           						= get_post_meta($post_id, 'vehicle_make', true);
    $vehicle_model           						= get_post_meta($post_id, 'vehicle_model', true);
    $vehicle_year           						= get_post_meta($post_id, 'year_of_manufacture', true);
    $caravan_width_mm       						= get_post_meta($post_id, 'caravan_width_mm', true);
    $caravan_length_mm      						= get_post_meta($post_id, 'caravan_length_mm', true);
    $bar_width_mm           						= get_post_meta($post_id, 'bar_width_mm', true);
    $vinyl_insert_width_mm  						= get_post_meta($post_id, 'vinyl_insert_width_mm', true);
    $vinyl_insert_height_mm 						= get_post_meta($post_id, 'vinyl_insert_height_mm', true);
    $factory_stoneguard_width  						= get_post_meta($post_id, 'factory_stoneguard_width', true);
    $factory_stoneguard_height 						= get_post_meta($post_id, 'factory_stoneguard_height', true);
    $toolbox_width_mm       						= get_post_meta($post_id, 'toolbox_width_mm', true);
    $toolbox_height_mm      						= get_post_meta($post_id, 'toolbox_height_mm', true);
    $support_pockets        						= get_post_meta($post_id, 'support_pockets', true);
    $support_pockets_measurement        			= get_post_meta($post_id, 'support_pockets_measurement', true);
    $sts_var_caravan_bar_option     				= get_post_meta($post_id, 'sts_var_caravan_bar_option', true);
    $sts_var_caravan_bar_bend       				= get_post_meta($post_id, 'sts_var_caravan_bar_bend', true);
    $sts_var_caravan_ss_length_adj  				= get_post_meta($post_id, 'sts_var_caravan_ss_length_adj', true);
    $sts_var_caravan_cut_out        				= get_post_meta($post_id, 'sts_var_caravan_cut_out', true);
    $sts_var_caravan_mesh_only_measurement     		= get_post_meta($post_id, 'sts_var_caravan_mesh_only_measurement', true);
    $sts_var_caravan_crfoam        					= get_post_meta($post_id, 'sts_var_caravan_foam', true);
    $sts_var_caravan_eyelet_tab        				= get_post_meta($post_id, 'sts_var_caravan_eyelet_tab', true);
    $sts_var_order_notes        					= get_post_meta($post_id, 'sts_var_order_notes', true);
	$final_details 									= get_post_meta( $post_id, 'final_details', true );
	$order_notes 									= get_post_meta( $post_id, 'order_notes', true );
	$extra_fittings 								= get_post_meta( $post_id, 'extra_fittings', true );
	$sts_var_caravan_bar_bend_type 					= get_post_meta( $post_id, 'sts_var_caravan_bar_bend_type', true );
	$tab_on_back 									= get_post_meta( $post_id, 'tab_on_back', true );

	// New fields
    $extension_plate        = get_post_meta($post_id, 'extension_plate', true);
    $fittings        = get_post_meta($post_id, 'fittings', true);
    $sleeve        = get_post_meta($post_id, 'sleeve', true);
    $extra_bungee        = get_post_meta($post_id, 'extra_bungee', true);
    $extra_vinyl_width_mm        = get_post_meta($post_id, 'extra_vinyl_width_mm', true);
    $extra_vinyl_length_mm        = get_post_meta($post_id, 'extra_vinyl_length_mm', true);
    $extra_vinyl_position        = get_post_meta($post_id, 'extra_vinyl_position', true);
    $angled_stone_guard_width_mm        = get_post_meta($post_id, 'angled_stone_guard_width_mm', true);
    $angled_stone_guard_depth_mm        = get_post_meta($post_id, 'angled_stone_guard_depth_mm', true);

	$sanitize_fields = [
		'customer_name', 'customer_email', 'customer_phone',
		'product_type', 'address_meta_address',
		'caravan_make', 'caravan_model', 'vehicle_make', 'vehicle_model',
		'vehicle_year', 'caravan_width_mm', 'caravan_length_mm',
		'bar_width_mm', 'vinyl_insert_width_mm', 'vinyl_insert_height_mm',
		'factory_stoneguard_width', 'factory_stoneguard_height',
		'toolbox_width_mm', 'toolbox_height_mm',
		'support_pockets', 'support_pockets_measurement',
		'sts_var_caravan_bar_option', 'sts_var_caravan_bar_bend',
		'sts_var_caravan_ss_length_adj', 'sts_var_caravan_cut_out',
		'sts_var_caravan_mesh_only_measurement', 'sts_var_caravan_foam',
		'sts_var_caravan_eyelet_tab', 'sts_var_order_notes',
		'extension_plate', 'fittings', 'sleeve', 'extra_bungee',
		'extra_vinyl_width_mm', 'extra_vinyl_length_mm', 'extra_vinyl_position',
		'angled_stone_guard_width_mm', 'angled_stone_guard_depth_mm', ''
	];

	// Sanitize everything for DOCX safety
	foreach ($sanitize_fields as $field) {
		if (isset($$field)) {
			$$field = wpword_escape($$field);
		}
	}

	$final_delivery_address = "Same As Home Address";

	if( $final_details && $final_details['final_delivery'] === 'move' ) {
		$final_delivery_address = "I am on the Move";
	}

    $proposed_date = get_post_meta($post_id, 'sts_var_proposed_date_of_delivery', true);
    $proposed_date = $proposed_date ? date('d-F-Y', strtotime($proposed_date)) : '-';


	if ( $sts_var_caravan_ss_length_adj !== '' ) {
		$caravan_length_mm = (int) $caravan_length_mm + (int) $sts_var_caravan_ss_length_adj;
	}

    // Start Section
    $section = $phpWord->addSection();

    // HEADER LAYOUT: Logo + Business Info + Order Info
    $table = $section->addTable();
    $table->addRow();

    $logo_cell = $table->addCell(5000, ['valign' => 'center']);
	$textRun = $logo_cell->addTextRun();
	// Nested table to restrict width
	$logoCellTitle = $logo_cell->addTable(['cellMargin' => 0]);
	$logoCellTitle->addRow();
	$logoCellTitle->addCell(3000)->addText("STONE STOMPER PACK SHEET", ['bold' => true, 'size' => 14]);

	$info_cell = $table->addCell(5000, ['valign' => 'center']);

	if ( $order ) {
		$textRun = $info_cell->addTextRun(['spaceBefore' => 0, 'spaceAfter' => 0]);
		$textRun->addText("ORDER DATE: ", ['bold' => true]);
		$textRun->addText($order_date);
	} else {
		$textRun = $info_cell->addTextRun(['spaceBefore' => 0, 'spaceAfter' => 0]);
		$textRun->addText("Order Created: ", ['bold' => true]);
		$textRun->addText($order_date);
	}

	$textRun = $info_cell->addTextRun(['spaceBefore' => 1, 'spaceAfter' => 0]);
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
	$addressTable->addCell(3000)->addText(strip_tags($address_meta_address ?: $address_meta_address), [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	if ( $order ) {
		$textRun = $leftCell->addTextRun(['spaceBefore' => 0, 'spaceAfter' => 0]);
		$textRun->addText("Delivery Address: ", ['bold' => true]);
		// Nested table to restrict width
		$addressTable = $leftCell->addTable(['cellMargin' => 0]);
		$addressTable->addRow();
		$addressTable->addCell(3000)->addText(strip_tags($final_delivery_address), [], ['spaceBefore' => 0, 'spaceAfter' => 0]); // ~50% of 6000 cell
	}
	// $textRun = $leftCell->addTextRun($compact);
	// $textRun->addText("Customer Notes: ", ['bold' => true]);
	// $textRun->addText($delivery_instructions ?: 'No');

	// RIGHT COLUMN
	$rightCell = $infoTable->addCell(5000);

	if ( $order ) {
		$textRun = $rightCell->addTextRun($compact);
		$textRun->addText("Product Type: ", ['bold' => true]);
		$textRun->addText($product_type);
	} else {
		$textRun = $rightCell->addTextRun($compact);
		$textRun->addText("Product Type: ", ['bold' => true]);
		$textRun->addText('Manual Order');
	}

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

	// Second Section: Stone Stomper Accessories Pack Details

	$section->addText("STONE STOMPER ACCESSORIES PACK DETAILS", ['bold' => true, 'size' => 10]);

	$tableAccessories = $section->addTable(
		[
			'borderSize' => 6,
			'borderColor' => 'cdcdcd', // gray border
			'cellMarginTop' => 0,
			'cellMarginBottom' => 0,
			'cellMarginLeft' => 50,
			'cellMarginRight' => 50
		]
	);

	$row = $tableAccessories->addRow(200);
	$row->addCell(6500)->addText("Fittings:", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(3500)->addText($fittings, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $tableAccessories->addRow(200);
	$row->addCell(6500)->addText("Extra Fittings:", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(3500)->addText($extra_fittings, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $tableAccessories->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6500)->addText("Extension Plate:", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(3500)->addText($extension_plate , [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $tableAccessories->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6500)->addText("Foam", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(3500)->addText($sts_var_caravan_crfoam, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $tableAccessories->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6500)->addText("Sleeves", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(3500)->addText("$sleeve", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $tableAccessories->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6500)->addText("Towing Vehicle Bar Width (mm):", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(3500)->addText("$bar_width_mm", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $tableAccessories->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6500)->addText("Bar Bend:", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(3500)->addText("$sts_var_caravan_bar_bend", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $tableAccessories->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6500)->addText("Bar Bend Type:", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(3500)->addText("$sts_var_caravan_bar_bend_type", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $tableAccessories->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6500)->addText("Tab on Back:", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(3500)->addText("$tab_on_back", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $tableAccessories->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6500)->addText("Bar Option:", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(3500)->addText("$sts_var_caravan_bar_option", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);


	$section->addTextBreak(1);

	// Third Section: Order Details

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
	$items_table->addCell(9000)->addText("Product", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	if ( $order ) {
		$i = 1;
		foreach ( $order->get_items() as $item ) {
			$name  = $item->get_name();
			$qty   = $item->get_quantity();
			$total = wc_format_decimal($item->get_total(), 2);
			$unit  = wc_format_decimal($item->get_total() / $qty, 2);

			$items_table->addRow(200);
			$items_table->addCell(2000)->addText($qty, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
			$items_table->addCell(8000)->addText($name, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
		}
	} else {
		$items_table->addRow(200);
		$items_table->addCell(2000)->addText('-', [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
		$items_table->addCell(8000)->addText('Manual order', [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	}
	$section->addTextBreak(1);

	// Fourth Section: Custom Order Notes

    $section->addText("Customer Notes:", ['bold' => true]);
    $section->addText($sts_var_order_notes, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$section->addText('');
    $section->addText("CUSTOMER ORDER NOTES:", ['bold' => true]);
    $section->addText($order_notes, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$section->addPageBreak();

	// Fifth Section: Manufacture Sheet

	$table = $section->addTable();
    $table->addRow();
    $logo_cell = $table->addCell(5000, ['valign' => 'center']);
	$textRun = $logo_cell->addTextRun();

	// Nested table to restrict width

	$logoCellTitle = $logo_cell->addTable(['cellMargin' => 0]);
	$logoCellTitle->addRow();
	$logoCellTitle->addCell(4000)->addText("STONE STOMPER MANUFACTURE SHEET", ['bold' => true, 'size' => 14]);
	$info_cell = $table->addCell(5000, ['spaceBefore' => 2, 'spaceAfter' => 0]);
	$textRun = $info_cell->addTextRun(['spaceBefore' => 0, 'spaceAfter' => 0]);
	$textRun->addText("ORDER Name: ", ['bold' => true]);
	$textRun->addText($customer_name);
	$textRun = $info_cell->addTextRun(['spaceBefore' => 1, 'spaceAfter' => 0]);
	$textRun->addText("ORDER NUMBER: ", ['bold' => true]);
	$textRun->addText($order_id);
	$textRun = $info_cell->addTextRun(['spaceBefore' => 1, 'spaceAfter' => 0]);
	$textRun->addText("Date Required: ", ['bold' => true]);
	$textRun->addText($proposed_date);
	$textRun = $info_cell->addTextRun(['spaceBefore' => 1, 'spaceAfter' => 0]);
	if ( $order ) {
		$textRun->addText("Product Type: ", ['bold' => true]);
		$textRun->addText($product_type);
		$section->addTextBreak(1);
	} else {
		$textRun->addText("Product Type: ", ['bold' => true]);
		$textRun->addText('Manual Order');
		$section->addTextBreak(1);
	}

	// Wanna call vector svg here
	$svg = get_towing_diagram_svg_png($post_id);

	if ($svg) {
		$diagram_png = svg_to_png_temp($svg);
		if (file_exists($diagram_png)) {
			$section->addImage($diagram_png, [
				'width' => 390,
				'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
			]);
		}
	}

	$section->addTextBreak(1);

    // MEASUREMENTS TABLE
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
	$row->addCell(6000)->addText("Stoneguard Width (mm)", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText("$factory_stoneguard_width", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("Stoneguard Distance From Carvan (mm):", [], ['spaceBefore' => 0, 'spaceAfter' => 0]); 	// this is not sure
	$row->addCell(4000)->addText("$factory_stoneguard_height", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("Toolbox Width (mm):", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText("$toolbox_width_mm", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("Toolbox Distance from the Caravan (mm):", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText("$toolbox_height_mm", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("Support Pockets:", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText($support_pockets, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("Support Pocket Distance from Caravan (mm)", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText($support_pockets_measurement, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

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
	$row->addCell(6000)->addText("Eyelet Tab:", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText($sts_var_caravan_eyelet_tab, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("Extra Bungee", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText($extra_bungee, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("Extra Vinyl Width (mm)", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText($extra_vinyl_width_mm, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("Extra Vinyl Length (mm)", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText($extra_vinyl_length_mm, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("Extra Vinyl Position", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText($extra_vinyl_position, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("Angled Stone Guard Width (mm)", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText($angled_stone_guard_width_mm, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);

	$row = $measure->addRow(200, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(6000)->addText("Angled Stone Guard Depth (mm)", [], ['spaceBefore' => 0, 'spaceAfter' => 0]);
	$row->addCell(4000)->addText($angled_stone_guard_depth_mm, [], ['spaceBefore' => 0, 'spaceAfter' => 0]);


    // Save file
    $upload_dir = wp_upload_dir();
    $file_path = $upload_dir['path'] . "/customer-order-{$post_id}.docx";
    $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    $writer->save($file_path);

    return $file_path;
}

function download_customer_word_callback() {
    $post_id = intval($_GET['post_id'] ?? 0);
	if (!$post_id) wp_die('Invalid post ID.');

	$order_id = get_post_meta($post_id, 'order_id', true);
	if (empty($order_id)) {
		$order_id = $post_id;
    }

    $file_path = generate_customer_order_word_file($post_id);

    if (!$file_path || !file_exists($file_path)) {
        wp_die('File generation failed.');
    }

    header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
    header('Content-Disposition: attachment; filename="customer-order-' . $order_id . '.docx"');
    readfile($file_path);
    exit;
}

add_action( 'wp_ajax_download_customer_word', 'download_customer_word_callback' );
add_action( 'wp_ajax_nopriv_download_customer_word', 'download_customer_word_callback' );


// Excel sheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// 1. Display Date Filters and Custom Export Buttons
add_action('restrict_manage_posts', function ($post_type) {
    // Only display buttons on the 'customer' post type screen
    if ($post_type !== 'customer') return;

    // Define the custom buttons and their corresponding status keys
   $buttons = [
        'all'             => 'Export All',
        'manufacturing_l' => 'Export Manufacturing L',
        'manufacturing_m' => 'Export Manufacturing M',
        'processing'      => 'Export Processing',
    ];

    // Output the HTML for the buttons
    foreach ($buttons as $status_key => $label) {
        $export_url = add_query_arg([
            'export_customer_excel' => 1,
            'order_status'          => $status_key,
        ]);
        echo '<a href="' . esc_url($export_url) . '" class="button" style="margin-left:6px; margin-right:6px;">' . esc_html($label) . '</a>';
    }
});

// 2. Handle Custom Export Buttons (Status Filtered)
add_action('admin_init', function () {
    // Check if the custom export flag is set
    if (!isset($_GET['export_customer_excel']) || intval($_GET['export_customer_excel']) !== 1) {
        return;
    }

    // Security check
    if (!current_user_can('edit_posts')) {
        wp_die('You do not have permission to perform this action.');
    }

    $status_key = isset($_GET['order_status']) ? sanitize_key($_GET['order_status']) : 'all';

    // Validate the status key to prevent unexpected file names or queries
    $valid_statuses = ['all', 'manufacturing_l', 'manufacturing_m', 'processing'];
    if (!in_array($status_key, $valid_statuses)) {
        wp_die('Invalid order status for export.');
    }

    // Call the generation function. We pass the status key and an empty array for post_ids
    generate_bulk_customer_excel_file($status_key);
    exit;
});

function generate_bulk_customer_excel_file($status_key = 'all', $post_ids = []) {
    // IMPORTANT: Ensure your autoloader path is correct relative to this file.
    require_once __DIR__ . '/vendor/autoload.php';

    // Global variable for WordPress database access
    global $wpdb;

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $row_index = 1;

    // 1. Set Headers
    $headers = ['Order ID', 'Customer Name', 'Proposed Date'];
    $sheet->fromArray($headers, NULL, 'A' . $row_index++);
	$header_style = [
        'font' => [
            'bold' => true,
			'size' => 14,
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            // ARGB hex code for yellow (FF - opacity, FFFF00 - bright yellow)
            'startColor' => [
                'argb' => 'FFFFFF00',
            ],
        ],
		'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_LEFT,
			'indent' => 1, // adds some left spacing
        ],
    ];
	$sheet->getStyle('A1:C1')->applyFromArray($header_style);

    // 2. Define WP_Query Arguments
    $args = [
        'post_type'      => 'customer',
        'posts_per_page' => -1, // Get all matching posts
        'post_status'    => 'any',
    ];

    if (!empty($post_ids)) {
        // Case 1: Posts selected via checkboxes (standard bulk action)
        $args['post__in'] = array_map('intval', $post_ids);
        $filename_key = 'selected';
    } else {
        // Case 2: Custom button clicked (status filter) - REQUIRES WC ORDER LOOKUP

        $statuses_to_filter_by = [];

        // --- Determine Target WC Statuses (WC get_status() returns slugs without 'wc-') ---
        switch ($status_key) {
			case 'manufacturing_l':
				// Targets the 'manufacturing' slug
				$statuses_to_filter_by = ['manufacturing'];
				break;

			case 'manufacturing_m':
				// Targets the 'manufacturing-m' slug
				$statuses_to_filter_by = ['manufacturing-m'];
				break;

			case 'processing':
				$statuses_to_filter_by = ['processing'];
				break;

			case 'all':
			default:
				$statuses_to_filter_by = [];
				break;
		}

        if (!empty($statuses_to_filter_by)) {

            $all_customer_ids = get_posts([
                'post_type'      => 'customer',
                'posts_per_page' => -1,
                'fields'         => 'ids',
                'post_status'    => 'any',
            ]);

            $matching_customer_ids = [];

            foreach ($all_customer_ids as $customer_id) {
                $wc_order_id = get_post_meta($customer_id, 'order_id', true);

                if ($wc_order_id) {
                    $order = wc_get_order($wc_order_id);

                    if ($order && in_array($order->get_status(), $statuses_to_filter_by)) {
                        $matching_customer_ids[] = $customer_id;
                    }
                }
            }

            if (!empty($matching_customer_ids)) {
                $args['post__in'] = $matching_customer_ids;
            } else {
                $args['post__in'] = [0];
            }

        }

        $filename_key = $status_key;
    }

    // 3. Fetch Posts
    $customer_posts = get_posts($args);
    if (empty($customer_posts)) {
        wp_die('No matching customer orders found for export criteria.');
    }

    // 4. Populate Spreadsheet Data
    $export_data = [];

    foreach ($customer_posts as $post) {
        $post_id        = $post->ID;
        $order_id       = get_post_meta($post_id, 'order_id', true);
        $customer_name  = get_post_meta($post_id, 'name', true);
        $customer_email = get_post_meta($post_id, 'email', true);
        $customer_phone = get_post_meta($post_id, 'customer_phone', true);
		$proposed_date_raw = get_post_meta($post->ID, 'sts_var_proposed_date_of_delivery', true);
		$proposed_date_formatted = '-'; // Default value

		if ($proposed_date_raw) {
			// Attempt to create a DateTime object from the raw date string
			$date_obj = date_create($proposed_date_raw);

			// Check if the date object was successfully created
			if ($date_obj !== false) {
				// Format the date to day/month/year (e.g., 25/12/2025)
				$proposed_date_formatted = date_format($date_obj, 'd/m/Y');
			} else {
				// If formatting fails, use the raw string
				$proposed_date_formatted = $proposed_date_raw;
			}
		}

		 // --- Format Date Created (YYYY-MM-DD HH:MM:SS -> DD/MM/YYYY HH:MM) ---
        $post_date_formatted = '-';
        if ($post->post_date && $post->post_date !== '0000-00-00 00:00:00') {
            // Note: WordPress post_date is YYYY-MM-DD HH:MM:SS
            $date_obj = date_create($post->post_date);
            if ($date_obj !== false) {
                // Format as Day/Month/Year Hour:Minute (removes seconds)
                $post_date_formatted = date_format($date_obj, 'd/m/Y H:i');
            } else {
                $post_date_formatted = $post->post_date;
            }
        }

        // --- Get Status ---
        $order_status_slug = 'N/A';
        $order_status_label = 'N/A';

        if ($order_id) {
            $order = wc_get_order($order_id);
            if ($order) {
                $order_status_slug = $order->get_status(); // e.g., 'processing'

                // Ensure we use the 'wc-' prefix to get the human-readable label
                $all_statuses = wc_get_order_statuses();
                $wc_slug = 'wc-' . $order_status_slug;
                $order_status_label = isset($all_statuses[$wc_slug]) ? $all_statuses[$wc_slug] : ucfirst($order_status_slug);
            }
        }

        // Store the collected data in an array
        $export_data[] = [
            'order_id'           => $order_id ?: $post_id,
            'customer_name'      => $customer_name,
			'proposed_date'      => $proposed_date_formatted, // ADD THIS LINE
        ];
    }

    // 4c. Write Sorted Data to Spreadsheet
    foreach ($export_data as $data) {
        $sheet->setCellValue('A' . $row_index, $data['order_id']);
        $sheet->setCellValue('B' . $row_index, $data['customer_name']);
		$sheet->setCellValue('C' . $row_index, $data['proposed_date']);
        $row_index++;
    }

	// 4d. Apply Left Alignment to All Data Cells (A2 to last row)
    $data_row_end = $row_index - 1;

    // Define style for left alignment
    $data_style = [
		'font' => [
			'size' => 14,
		],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_LEFT,
			'indent' => 1, // adds some left spacing
        ],
    ];

    // Apply left alignment to the entire data range (A2 to G[last data row])
    if ($data_row_end >= 2) {
        $sheet->getStyle('A2:C' . $data_row_end)->applyFromArray($data_style);
    }

    // 5. Finalize and Output File
    // Auto-size columns for readability
    foreach (range('A', 'C') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $filename = "customer-orders-{$filename_key}-" . date('YmdHis') . ".xlsx";

    // Clean any prior output buffer to prevent corruption
    if (ob_get_length()) ob_end_clean();

    // Set headers for file download
    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
}

// 3. Handle Bulk Export Action (for selected posts via checkboxes)
add_filter('handle_actions-customer', function ($redirect_to, $action, $post_ids) {
    // Only proceed if the bulk action is our custom export action
    if ($action !== 'bulk_export_excel') {
        return $redirect_to;
    }

    // Require posts to be selected
    if (empty($post_ids)) {
        return $redirect_to;
    }

    // Security check
    if (!current_user_can('edit_posts')) {
        wp_die('You do not have permission to perform this action.');
    }

    // Call the function with 'all' status key and the selected post IDs
    generate_bulk_customer_excel_file('all', $post_ids);
    exit; // Terminate script execution after file generation
}, 10, 3);

// Email to manufacturer

function email_to_manufacturer_callback() {
	list( $sts_var_post_id, $sts_fields, $sts_option_fields ) = StoneStomper::defaults();

    $post_id = intval($_POST['post_id']);
	$order_id = get_post_meta($post_id, 'order_id', true);
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

    $subject = "New Customer Order Details (Order #{$order_id})";
    $message = "Hello,\n\nPlease find attached the customer order details document.\n\nThanks.";

    wp_mail($emails, $subject, $message, [], [$file_path]);

    wp_send_json_success('Email Sent');
}

add_action('wp_ajax_email_to_manufacturer', 'email_to_manufacturer_callback');

add_action('admin_enqueue_scripts', function($hook) {
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

// 1️⃣ Register the new "Manufacturing L" status
add_action( 'init', function() {
	register_post_status( 'wc-manufacturing', array(
		'label'                     => 'Manufacturing L',
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Manufacturing L <span class="count">(%s)</span>', 'Manufacturing L <span class="count">(%s)</span>' ),
	) );
	register_post_status( 'wc-manufacturing-m', array(
		'label'                     => 'Manufacturing M',
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( 'Manufacturing M <span class="count">(%s)</span>', 'Manufacturing M <span class="count">(%s)</span>' ),
	) );
} );

// 2️⃣ Add it to WooCommerce status dropdowns (in admin & everywhere)
add_filter( 'wc_order_statuses', function( $statuses ) {
	// Insert after "processing"
	$new_statuses = [];

	foreach ( $statuses as $key => $label ) {
		$new_statuses[ $key ] = $label;

		if ( 'wc-processing' === $key ) {
			$new_statuses['wc-manufacturing'] = __( 'Manufacturing L', 'stonestomper_td' );
			$new_statuses['wc-manufacturing-m'] = __( 'Manufacturing M', 'stonestomper_td' );
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

/*
|-----------------------------------------
|  Save WooCommerce status change
|-----------------------------------------
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

add_filter('woocommerce_is_sold_individually', 'hide_quantity_for_specific_product', 10, 2);

function hide_quantity_for_specific_product($sold_individually, $product) {
    if ($product->get_id() == 545 || $product->get_id() == 712) {
        return true;
    }
    return $sold_individually;
}

add_filter('post_date_column_time', function($h_time, $post) {
    $date = mysql2date('d/m/Y', $post->post_date);
    $time = mysql2date('g:i a', $post->post_date);
    return $date . ' at ' . $time;
}, 10, 2);

/*
|-----------------------------------------
|  ORDER Meta data to customer CPT Post
|-----------------------------------------
*/

add_action('woocommerce_cart_calculate_fees', function ($cart) {

	if (is_admin() && !defined('DOING_AJAX')) {
		return;
	}

	$is_free_order = false;
	$discount_percent = 0;

	$subtotal = (float) $cart->get_subtotal();
	$discount = (float) $cart->get_discount_total();

	if ($subtotal > 0 && $discount > 0) {
		$discount_percent = ($discount / $subtotal) * 100;
	}

	if ($subtotal - $discount <= 0) {
		$is_free_order = true;
	}

	foreach ($cart->get_cart() as $cart_item) {

		if (empty($cart_item['sts_payload']) || !is_array($cart_item['sts_payload'])) {
			continue;
		}

		$data = $cart_item['sts_payload'];

		if (empty($data['product_type']) || (string) $data['product_type'] !== '545') {
			continue;
		}

		$barwidth = isset($data['barwidth_mm']) ? (float) $data['barwidth_mm'] : 0;
		$a_frame  = isset($data['a_frame_length_mm']) ? (float) $data['a_frame_length_mm'] : 0;

		$base_35  = 35;
		$base_100 = 100;

		if ($discount_percent > 0 && !$is_free_order) {
			$base_35  -= $base_35 * ($discount_percent / 100);
			$base_100 -= $base_100 * ($discount_percent / 100);
		}

		if ($is_free_order) {
			$base_35 = 0.01;
			$base_100 = 0.01;
		}

		if ($barwidth >= 1900 && $barwidth <= 2100) {
			$cart->add_fee('Extra Bar Width (1900–2100mm)', $base_35);
		} elseif ($barwidth > 2100) {
			$cart->add_fee('Extra Bar Width (>2100mm)', $base_100);
		}

		if ($a_frame >= 1800 && $a_frame <= 2300) {
			$cart->add_fee('Extra Mesh Length (1800–2300mm)', $base_35);
		} elseif ($a_frame > 2300) {
			$cart->add_fee('Extra Mesh Length (>2300mm)', $base_100);
		}

		if ($a_frame >= 1800 && (!empty($data['toolbox']) || !empty($data['factory_stoneguard']))) {

			$fit_fee = 35;

			if ($discount_percent > 0 && !$is_free_order) {
				$fit_fee -= $fit_fee * ($discount_percent / 100);
			}

			if ($is_free_order) {
				$fit_fee = 0.01;
			}

			$cart->add_fee('Fittings Charges', $fit_fee);
		}
	}

	if ($is_free_order) {
		$adjustment = 0;

		foreach ($cart->get_fees() as $fee) {
			if ((float) $fee->amount === 0.01) {
				$adjustment += 0.01;
			}
		}

		if ($adjustment > 0) {
			$cart->add_fee('Promotion Adjustment', -$adjustment);
		}
	}

}, 20);

add_filter( 'woocommerce_cart_item_name', 'sts_show_fees_only_in_mini_cart', 20, 3 );

function sts_show_fees_only_in_mini_cart( $name, $cart_item, $cart_item_key ) {

    if ( ! wp_doing_ajax() ) {
        return $name;
    }

    static $shown = false;

    if ( $shown ) {
        return $name;
    }

    $fees = WC()->cart->get_fees();
	$shipping_total = WC()->cart->get_shipping_total();

	if ( empty( $fees  ) ) {
        return $name;
    }

    $output = '<div class="mini-cart-extra-fees">';

    foreach ( $fees as $fee ) {
        $output .= '<div class="fee-row">';
        $output .= '<span>' . esc_html( $fee->name ) . '</span>';
        $output .= '<span>' . wc_price( $fee->amount ) . '</span>';
        $output .= '</div>';
    }

	if ( $shipping_total > 0 ) {
        $output .= '<div class="fee-row">';
        $output .= '<span>Delivery</span>';
        $output .= '<span>' . wc_price( $shipping_total ) . '</span>';
        $output .= '</div>';
    }

    $output .= '</div>';

    $shown = true;

    return $name . $output;
}

add_filter('woocommerce_add_cart_item_data', function ($cart_item_data, $product_id) {

	if (empty($_POST['payload']) || !is_array($_POST['payload'])) {
		return $cart_item_data;
	}

	if (
		empty($_POST['payload']['is_stone_stomper_order']) ||
		$_POST['payload']['is_stone_stomper_order'] !== 'yes'
	) {
		return $cart_item_data;
	}

	$cart_item_data['sts_payload'] = array_map('wc_clean', $_POST['payload']);

	$cart_item_data['unique_key'] = md5(microtime() . rand());

	return $cart_item_data;

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

add_action('woocommerce_checkout_create_order_line_item', function ($item, $cart_item_key, $values, $order) {
    // Get product ID from cart item
    $product_id = $values['product_id'];

    // Skip non–Stone Stomper products
    if (!has_term('stone-stomper', 'product_cat', $product_id)) {
        return;
    }

    // Stop if payload is missing for Stone Stomper
    if (empty($values['sts_payload']) || !is_array($values['sts_payload'])) {
        error_log('STS DEBUG: payload missing for Stone Stomper product');
        return;
    }

    // Save payload into order item meta
    $item->add_meta_data(
        '_sts_payload',
        wp_json_encode($values['sts_payload']),
        true
    );

}, 20, 4);

add_action('woocommerce_checkout_process', function () {

    foreach (WC()->cart->get_cart() as $item) {

        // Skip non–Stone Stomper products
        if (!has_term('stone-stomper', 'product_cat', $item['product_id'])) {
            continue;
        }

        // Block checkout if payload is missing
        if (empty($item['sts_payload'])) {
            wc_add_notice('Configuration data missing. Please refresh the page and try again.', 'error');
            return;
        }
    }
});

add_action(
    'woocommerce_get_cart_item_from_session',
    function ( $cart_item, $values ) {

        // Restore STS payload from session into cart item
        if ( isset( $values['sts_payload'] ) ) {
            $cart_item['sts_payload'] = $values['sts_payload'];
        }

        return $cart_item;
    },
    20,
    2
);

add_action(
    'woocommerce_order_status_processing',
    'sts_materialize_customer_cpt',
    20
);

function sts_materialize_customer_cpt( $order_id ) {

    $order = wc_get_order( $order_id );
    if ( ! $order ) {
        return;
    }

    if ( $order->get_meta( '_sts_customer_cpt_created' ) === 'yes' ) {
        return;
    }

    $data = null;

    foreach ( $order->get_items() as $item ) {
        $raw = $item->get_meta( '_sts_payload', true );
        if ( $raw ) {
            $decoded = json_decode( $raw, true );
            if ( is_array( $decoded ) ) {
                $data = $decoded;
                break;
            }
        }
    }

    if ( ! is_array( $data ) ) {
        $order->add_order_note( 'STS STOP: payload missing' );
        return;
    }

    if ( empty( $data['is_stone_stomper_order'] ) || $data['is_stone_stomper_order'] !== 'yes' ) {
        return;
    }

    $cust_first = sanitize_text_field( $data['customer_first_name'] ?? '' );
    $cust_last  = sanitize_text_field( $data['customer_last_name'] ?? '' );
    if ( $cust_first === '' || $cust_last === '' ) {
        $order->add_order_note( 'STS STOP: customer name missing' );
        return;
    }

    $cust_name = trim( $cust_first . ' ' . $cust_last );

    $post_id = wp_insert_post( [
        'post_type'   => 'customer',
        'post_status' => 'publish',
        'post_title'  => $cust_name,
    ] );

    if ( is_wp_error( $post_id ) || ! $post_id ) {
        return;
    }

    $product_type = (string) ( $data['product_type'] ?? '' ) === '712' ? 'Mesh Only' : 'Stone Stomper';

	// Contact Information
    update_post_meta( $post_id, 'order_id', $order_id );
    update_post_meta( $post_id, 'name', $cust_name );
    update_post_meta( $post_id, 'customer_phone', sanitize_text_field( $data['customer_phone'] ?? '' ) );
    update_post_meta( $post_id, 'email', sanitize_email( $data['customer_email'] ?? '' ) );
    update_post_meta( $post_id, 'delivery_address', sanitize_text_field( $data['customer_address'] ?? '' ) );
    update_post_meta( $post_id, 'subrubs', sanitize_text_field( $data['customer_suburb'] ?? '' ) );
    update_post_meta( $post_id, 'state', sanitize_text_field( $data['customer_state'] ?? '' ) );
    update_post_meta( $post_id, 'product_type', $product_type );

	// Vehicle Information
    update_post_meta( $post_id, 'vehicle_make', sanitize_text_field( $data['vehicle_make'] ?? $data['veh_make'] ?? '' ) );
    update_post_meta( $post_id, 'vehicle_model', sanitize_text_field( $data['vehicle_model'] ?? $data['veh_model'] ?? '' ) );
    update_post_meta( $post_id, 'year_of_manufacture', sanitize_text_field( $data['vehicle_year'] ?? $data['veh_year'] ?? '' ) );

	// Caravan Information
    update_post_meta( $post_id, 'caravan_make', sanitize_text_field( $data['caravan_make'] ?? $data['van_make'] ?? '' ) );
    update_post_meta( $post_id, 'caravan_model', sanitize_text_field( $data['caravan_model'] ?? $data['van_model'] ?? '' ) );

    $barwidth = floatval( $data['barwidth_mm'] ?? 0 );
    $caravan_width = floatval( $data['caravan_width_mm'] ?? 0 );
    $a_frame = floatval( $data['a_frame_length_mm'] ?? 0 );
    $hitch_measure = floatval( $data['additional_hitch_measurement'] ?? 0 );

    update_post_meta( $post_id, 'bar_width_mm', $barwidth );
    update_post_meta( $post_id, 'caravan_width_mm', $caravan_width );
    update_post_meta( $post_id, 'caravan_length_mm', $a_frame );

	// Images
	$hitch_images = [];
	$rear_images  = [];
	$front_images = [];

	if (!empty($data['hitch_ids'])) {
		$hitch_images = $data['hitch_ids'];
	}

	if (!empty($data['rear_ids'])) {
		$rear_images = $data['rear_ids'];
	}

	if (!empty($data['front_ids'])) {
		$front_images = $data['front_ids'];
	}

	if ($hitch_images) {
		update_post_meta($post_id, 'hitch_images', $hitch_images);
	}

	if ($rear_images) {
		update_post_meta($post_id, 'rear_images', $rear_images);
	}

	if ($front_images) {
		update_post_meta($post_id, 'front_images', $front_images);
	}

	// Standard Order Notes
	update_post_meta( $post_id, 'order_notes', sanitize_text_field( $data['order_notes'] ?? '' ) );

    if ( $caravan_width > 2450 || $a_frame > 2300 ) {
        update_post_meta( $post_id, 'sts_var_caravan_eyelet_tab', 'Yes' );
    }

    if ( $barwidth > 2150 ) {
        update_post_meta( $post_id, 'extra_bungee', 'Yes' );
    }

    if ( $hitch_measure > 0 ) {
        if ( $hitch_measure < 250 ) {
            update_post_meta( $post_id, 'sts_var_caravan_cut_out', 250 );
        } elseif ( $hitch_measure <= 350 ) {
            update_post_meta( $post_id, 'sts_var_caravan_cut_out', 350 );
        } else {
            update_post_meta( $post_id, 'sts_var_caravan_cut_out', 450 );
        }

        if ( $product_type === 'Stone Stomper' ) {
            update_post_meta( $post_id, 'sts_var_caravan_ss_length_adj', $hitch_measure - 140 );
        }
    }

   	$final_delivery  = sanitize_text_field( $data['final_delivery'] ?? '' );
	if ('move' === $final_delivery) {
	    update_post_meta( $post_id, 'sts_var_proposed_on_the_move', 'Yes' );
    } else {
		update_post_meta( $post_id, 'sts_var_proposed_on_the_move', 'No' );
	}

    update_post_meta( $post_id, 'vinyl_insert_width_mm', sanitize_text_field( $data['vinyl_width_mm'] ?? '' ) );
    update_post_meta( $post_id, 'vinyl_insert_height_mm', sanitize_text_field( $data['vinyl_length_mm'] ?? '' ) );

    $support_type = $data['input_1.3'] ?? '';

	update_post_meta( $post_id, 'support_pockets', 'No' );

	if ( $support_type === 'toolbox' ) {
		update_post_meta( $post_id, 'support_type', 'toolbox' );
		update_post_meta( $post_id, 'toolbox_width_mm', sanitize_text_field( $data['toolbox_width_mm'] ?? '' ) );
		update_post_meta( $post_id, 'toolbox_height_mm', sanitize_text_field( $data['toolbox_length_mm'] ?? '' ) );
		update_post_meta( $post_id, 'extra_fittings', 'Short Bolt plus D Shackles' );
	} elseif ( $support_type === 'factory-stoneguard' ) {
		update_post_meta( $post_id, 'support_type', 'factory_stoneguard' );
		update_post_meta( $post_id, 'factory_stoneguard_width', sanitize_text_field( $data['stoneguard_width_mm'] ?? '' ) );
		update_post_meta( $post_id, 'factory_stoneguard_height', sanitize_text_field( $data['stoneguard_length_mm'] ?? '' ) );
		update_post_meta( $post_id, 'extra_fittings', 'Long Bolt' );
	} elseif ( $support_type === 'support_pockets' ) {
		update_post_meta( $post_id, 'support_pockets', 'Yes' );
		update_post_meta( $post_id, 'support_pockets_measurement', sanitize_text_field( $data['support_pocket_length_mm'] ?? '' ) );
	}

    if ( $product_type === 'Mesh Only' ) {
        update_post_meta( $post_id, 'sts_var_caravan_mesh_only_measurement', sanitize_text_field( $data['meshmeasurment_mm'] ?? '' ) );
        update_post_meta( $post_id, 'caravan_length_mm', sanitize_text_field( $data['meshmeasurment_mm'] ?? '' ) );
        update_post_meta( $post_id, 'sts_var_caravan_ss_length_adj', '-120' );
    } else {
        update_post_meta( $post_id, 'sts_var_caravan_bar_option', sanitize_text_field( $data['bar_options'] ?? '' ) );
    }

	$sts_var_caravan_bar_option = sanitize_text_field( $data['bar_options'] ?? '' );
	$bar_option = trim( $sts_var_caravan_bar_option );

	if (
		$bar_option === 'Option 1 Large Angle' ||
		$bar_option === 'Cut Out Angle'
	) {
		update_post_meta( $post_id, 'hitch_measurement_field', 100 );
	} else {
		update_post_meta( $post_id, 'hitch_measurement_field', $hitch_measure );
	}


   	$sleeve = 'No';

	foreach ( $order->get_items() as $item ) {
		if ( (int) $item->get_product_id() === 532 ) {
			$sleeve = 'Yes';
			break;
		}
	}

	update_post_meta( $post_id, 'sleeve', $sleeve );

    if ( $order->get_user_id() ) {
        update_post_meta( $post_id, '_customer_user_id', $order->get_user_id() );
    }

    if ( $order->get_customer_note() ) {
        update_post_meta( $post_id, 'sts_var_order_notes', sanitize_textarea_field( $order->get_customer_note() ) );
    }

    $order->update_meta_data( '_sts_customer_cpt_created', 'yes' );
    $order->add_order_note( 'Customer CPT created' );
    $order->save();
}

add_filter('woocommerce_hidden_order_itemmeta', function ($hidden) {
    $hidden[] = '_sts_payload';
    return $hidden;
});

add_action('woocommerce_after_order_itemmeta', function ($item_id, $item, $product) {

    $raw = $item->get_meta('_sts_payload');
    if (!$raw) return;

    $data = json_decode($raw, true);
    if (!is_array($data)) return;

    echo '<div class="sts-admin-inline">';
   $sections = [
        'Customer' => [
            'customer_first_name' => 'First Name',
            'customer_last_name'  => 'Last Name',
            'customer_phone'      => 'Phone',
            'customer_email'      => 'Email',
            'customer_address'    => 'Address',
            'customer_suburb'     => 'Suburb',
            'customer_state'      => 'State',
        ],

        'Towing Vehicle Details' => [
            'vehicle_make'  => 'Make',
            'vehicle_model' => 'Model',
            'vehicle_year'  => 'Year',
        ],

        'Caravan Details' => [
            'caravan_make'  => 'Make',
            'caravan_model' => 'Model',
        ],

        'Bar Option' => [
            'bar_options'                  => 'Bar Option',
            'additional_hitch_measurement' => 'Hitch Measurement (mm)',
        ],

        'Measurements' => [
			'barwidth_mm'                  	=> 'Bar Width (mm)',
            'caravan_width_mm' 				=> 'Caravan Width (mm)',
            'a_frame_length_mm' 			=> 'A-Frame Length (mm)',
            'meshmeasurment_mm' 			=> 'Mesh Measurement (mm)',
        ],

        'Vinyl Inserts' => [
            'vinyl_width_mm'  => 'Vinyl Width (mm)',
            'vinyl_length_mm' => 'Vinyl Length (mm)',
        ],

        'Delivery & Notes' => [
            'final_delivery' => 'Delivery Type',
            'order_notes'    => 'Order Notes',
        ],
    ];

    foreach ($sections as $title => $fields) {

        $parts = [];

        foreach ($fields as $key => $label) {
            if (empty($data[$key])) continue;
            $parts[] = '<strong>' . esc_html($label) . ':</strong> ' . esc_html($data[$key]);
        }

        if ($parts) {
            echo '<p><em>' . esc_html($title) . '</em> — ' . implode(' | ', $parts) . '</p>';
        }
    }

	$support_type = $data['input_1.3'] ?? '';

	$parts = [];

	if ($support_type === 'support_pockets') {
		$parts[] = '<strong>Support Type:</strong> Support Pockets';
		if (!empty($data['support_pocket_length_mm'])) {
			$parts[] = '<strong>Pocket Length (mm):</strong> ' . esc_html($data['support_pocket_length_mm']);
		}
	}

	if ($support_type === 'factory-stoneguard') {
		$parts[] = '<strong>Support Type:</strong> Stone Guard';
		if (!empty($data['stoneguard_width_mm'])) {
			$parts[] = '<strong>Stoneguard Width (mm):</strong> ' . esc_html($data['stoneguard_width_mm']);
		}
		if (!empty($data['stoneguard_length_mm'])) {
			$parts[] = '<strong>Stoneguard Length (mm):</strong> ' . esc_html($data['stoneguard_length_mm']);
		}
	}

	if ($support_type === 'toolbox') {
		$parts[] = '<strong>Support Type:</strong> Toolbox';
		if (!empty($data['toolbox_width_mm'])) {
			$parts[] = '<strong>Toolbox Width (mm):</strong> ' . esc_html($data['toolbox_width_mm']);
		}
		if (!empty($data['toolbox_length_mm'])) {
			$parts[] = '<strong>Toolbox Length (mm):</strong> ' . esc_html($data['toolbox_length_mm']);
		}
	}

	if ($parts) {
		echo '<p><em>Support / Protection</em> — ' . implode(' | ', $parts) . '</p>';
	}

    echo '</div>';
}, 10, 3);

add_action('admin_head', function () {
    echo '
    <style>
        .sts-admin-inline {
            background: #f8f9fa;
            padding: 8px 10px;
            margin-top: 6px;
            border-left: 3px solid #2271b1;
            font-size: 13px;
        }

        .sts-admin-inline p {
            margin: 4px 0;
        }

        .sts-admin-inline em {
            font-style: normal;
            font-weight: 600;
			width: 180px;
			display: inline-block;
			font-size: 110%;
        }

        .sts-admin-inline img {
            margin: 2px;
            border: 1px solid #ddd;
        }
    </style>';
});

/*
|-----------------------------------------
|  Add just one order to cart of same type of the product
|-----------------------------------------
*/

define('STS_STONE_STOMPER_ID', 545);
define('STS_MESH_ONLY_ID', 712);

add_action('woocommerce_add_to_cart', function ($cart_item_key, $product_id) {

    if (!function_exists('WC') || !WC()->cart) return;

    $is_stone_stomper = ($product_id == STS_STONE_STOMPER_ID);
    $is_mesh_only     = ($product_id == STS_MESH_ONLY_ID);

    foreach (WC()->cart->get_cart() as $key => $item) {
        if ($key === $cart_item_key) continue;
        $existing_id = $item['product_id'];
        if ($is_stone_stomper && in_array($existing_id, [STS_STONE_STOMPER_ID, STS_MESH_ONLY_ID])) {
            WC()->cart->remove_cart_item($key);
        }
        if ($is_mesh_only && in_array($existing_id, [STS_MESH_ONLY_ID, STS_STONE_STOMPER_ID])) {
            WC()->cart->remove_cart_item($key);
        }
    }

    WC()->cart->set_session();
    WC()->cart->calculate_totals();

    wc_add_notice('Previous product replaced.', 'success');

}, 10, 2);

/*
|-----------------------------------------
|  Calculation for the SS length feilds with tab on back and the bar bend values | -30 wen tab on back "Yes" , + bar bend value of dropdown
|-----------------------------------------
*/

add_action('acf/save_post', function ($post_id) {

    if (get_post_type($post_id) !== 'customer') return;

    $product_type = get_post_meta($post_id, 'product_type', true);
    if ($product_type !== 'Stone Stomper') return;

    $hitch = floatval(get_post_meta($post_id, 'hitch_measurement_field', true));
    if ($hitch <= 0) return;

    $tab_on_back = get_post_meta($post_id, 'tab_on_back', true);
    $bar_bend    = floatval(get_post_meta($post_id, 'sts_var_caravan_bar_bend', true));

    $ss_length_adjustment = $hitch - 140;

    if (strtoupper(trim($tab_on_back)) === 'YES') {
        $ss_length_adjustment -= 30;
    }

    $ss_length_adjustment += $bar_bend;

    update_post_meta(
        $post_id,
        'sts_var_caravan_ss_length_adj',
        $ss_length_adjustment
    );

}, 20);

/*
 |--------------------------------------------------
 | Add "On the Move" status to the admin order screen
 |--------------------------------------------------
 */

add_action( 'woocommerce_admin_order_data_after_order_details', 'display_on_the_move_status_admin', 10, 1 );

function display_on_the_move_status_admin( $order ) {
    if ( ! is_object( $order ) ) {
        $order = wc_get_order( $order );
    }

    // Find the Customer CPT created for this order
    $customer_posts = get_posts( [
        'post_type'  => 'customer',
        'meta_key'   => 'order_id',
        'meta_value' => $order->get_id(),
        'numberposts'=> 1,
        'fields'     => 'ids',
    ] );

    if ( empty( $customer_posts ) ) {
        return;
    }

    $customer_post_id = $customer_posts[0];

    // Fetch the "On the Move" meta from the CPT
    $on_the_move = get_post_meta( $customer_post_id, 'sts_var_proposed_on_the_move', true );

    if ( 'Yes' === $on_the_move ) {
        echo '<div class="on-the-move-alert" style="margin-top: 20px; float: left; width:100%; margin-top:20px; padding:15px; box-sizing: border-box; background-color: #fff9c4; border: 1px solid #fbc02d; border-radius: 4px; display:block;">';
        echo '<strong style="color:#856404;"> ORDER NOTE:</strong><span style="color: #d32f2f; font-weight:bold;"> Customer is "On the Move"</span>';
        echo '</div>';
    }
}

/*
 |--------------------------------------------------
 | Authority to leave
 |--------------------------------------------------
 */


add_action( 'init', 'register_atl_block_extension' );

function register_atl_block_extension() {
    // 1. First, we still need to register the field server-side
    if ( function_exists( 'woocommerce_register_additional_checkout_field' ) ) {
        woocommerce_register_additional_checkout_field( array(
            'id'       => 'my-custom-atl/authority-to-leave',
            'label'    => 'Authority to Leave',
            'location' => 'order',
            'type'     => 'select',
            'options'  => [
                [ 'value' => 'no', 'label' => 'No' ],
                [ 'value' => 'yes', 'label' => 'Yes' ],
            ],
            'required' => true,
        ) );
    }

    // 2. Enqueue the JS file
    wp_enqueue_script(
        'atl-block-js',
        get_stylesheet_directory_uri() . '/assets/src/js/atl-block-field.js', // Adjust path
        array( 'wc-checkout', 'wc-blocks-registry', 'wp-element' ),
        '1.0',
        true
    );
}
