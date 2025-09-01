<?php
/**
 * Functions for custom post types
 *
 * @link https://developer.wordpress.org/themes/basics/post-types/
 *
 * @package Base Theme Package
 * @since 1.0.0
 */

use BaseTheme\CPT\WP_Theme_CPT;

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
		'supports'     => array( 'title', 'editor', 'thumbnail', 'author', 'excerpt' ),
		'menu_icon'    => 'dashicons-format-quote',
		'public'       => false,
		'show_in_menu' => true,
		'show_ui'      => true,
		'taxonomies'   => array(
			array(
				'slug'          => 'testimonials',
				'register_key'  => 'testimonials', // if not given default is slug value.
				'name'          => 'Category',
				'singular_name' => 'Category',
				'plural_name'   => 'Categories',
			),
			array(
				'slug'          => 'types',
				// 'register_key'  => 'types', // if not given default is slug value.
				'name'          => 'Type',
				'singular_name' => 'Type',
				'plural_name'   => 'Types',
				// 'include_cpt'   => 'team',
			),
		),
	)
);
new WP_Theme_CPT(
	array(
		'labels'    => array(
			'singular_capital'   => 'Team Member',
			'plural_capital'     => 'Team Members',
			'singular_lowercase' => 'team member',
			'plural_lowercase'   => 'team members',
			// CPT Slug & Name.
			'register_key'       => 'team',
			'slug'               => 'team',
		),
		'supports'  => array( 'title', 'editor', 'thumbnail', 'author', 'excerpt' ),
		'menu_icon' => 'dashicons-businessperson',
		'public'    => false,
	)
);
new WP_Theme_CPT(
	array(
		'labels'    => array(
			'singular_capital'   => 'Resource',
			'plural_capital'     => 'Resources',
			'singular_lowercase' => 'resource',
			'plural_lowercase'   => 'resources',
			// CPT Slug & Name.
			'register_key'       => 'resource',
			'slug'               => 'resource',
		),
		'supports'  => array( 'title', 'editor', 'thumbnail', 'author', 'excerpt' ),
		'menu_icon' => 'dashicons-groups',
		'public'    => true,
	)
);
