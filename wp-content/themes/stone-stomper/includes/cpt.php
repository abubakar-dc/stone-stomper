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
	)
);
new WP_Theme_CPT(
	array(
		'labels'    => array(
			'singular_capital'   => 'Customers',
			'plural_capital'     => 'Customers',
			'singular_lowercase' => 'customer',
			'plural_lowercase'   => 'customers',
			// CPT Slug & Name.
			'register_key'       => 'customer',
			'slug'               => 'customer',
		),
		'supports'  => array( 'title', 'editor', 'thumbnail' ),
		'menu_icon' => 'dashicons-groups',
		'public'    => false,
	)
);
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
