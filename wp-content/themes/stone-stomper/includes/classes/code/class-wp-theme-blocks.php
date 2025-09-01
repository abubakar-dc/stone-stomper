<?php
/**
 * Blocks related functions
 *
 * @link
 *
 * @package Stone Stomper
 * @since 1.0.0
 */

namespace StoneStomper\Blocks;

use StoneStomper;

/**
 * Template Class For Blocks
 *
 * Template Class
 *
 * @category Setting_Class
 * @package  Stone Stomper
 */


 class WP_Theme_Blocks {
	/**
	 * Define class Constructor
	 **/
	public function __construct() {
		add_action( 'init', array( $this, 'register_theme_blocks' ) );
	}

	/**
	 * A function in which all acf blocks are registered
	 *
	 *  @return void
	 */
	public function register_theme_blocks() {
		foreach ( self::get_folder_name( BASETHEME_BLOCK_DIR ) as $sam_block ) {
			register_block_type( BASETHEME_BLOCK_DIR . '/' . $sam_block );
		}

		$child_dynamic_blocks = array(
			array(
				'name'       => 'stonestomperpack/testimonials-item',
				'attributes' => array(
					'itemId' => array(
						'type'    => 'string',
						'default' => '',
					),
				),
			),
			array(
				'name'       => 'stonestomperpack/service-teaser-item',
				'attributes' => array(
					'itemId' => array(
						'type'    => 'string',
						'default' => '',
					),
				),
			),
			array(
				'name'       => 'stonestomperpack/portfolio-teaser-item',
				'attributes' => array(
					'itemId' => array(
						'type'    => 'string',
						'default' => '',
					),

				),
			),
			array(
				'name'       => 'stonestomperpack/featured-posts-item',
				'attributes' => array(
					'itemId' => array(
						'type'    => 'string',
						'default' => '',
					),
					'index' => array(
						 'type' => 'number',
						 'default' => 0,
					),
				),
			),
			array(
				'name'       => 'stonestomperpack/location-facilities-item',
				'attributes' => array(
					'itemId' => array(
						'type'    => 'string',
						'default' => '',
					),
				),
			),
			array(
				'name'       => 'stonestomperpack/theme-stats-item',
				'attributes' => array(
					'lat' => array(
						'type'    => 'string',
						'default' => '',
					),
					'lng' => array(
						'type'    => 'string',
						'default' => '',
					),
				),
			),
		);
		foreach ( $child_dynamic_blocks as $child_dynamic_block ) {
			register_block_type(
				$child_dynamic_block['name'],
				array(
					'attributes'      => $child_dynamic_block['attributes'],
					'render_callback' => array( $this, 'block_plugin_render_block' ),
				)
			);
		}

	}
	function block_plugin_render_block( $attributes, $content, $block ) {
		ob_start();
		$folder_name = str_replace( '-item', '', explode( '/', $block->parsed_block['blockName'] )[1] ) ?? null;
		error_log("Folder name: " . $folder_name);

		if ( $folder_name ) {
			$folder_path = BASETHEME_BLOCK_DIR . '/' . $folder_name . '/render-child.php';
			if ( file_exists( $folder_path ) ) {
				include $folder_path;
			} else {
				error_log("Render file missing: " . $folder_path);
			}
		}

		return ob_get_clean();
	}
	/**
	 * A function which is used to register a block
	 *
	 * @param string $directory is the name of the block.
	 *
	 *  @return string
	 */
	public static function get_folder_name( $directory ) {
		$folder_names = array();

		// Check if the directory exists.
		if ( is_dir( $directory ) ) {
			// Scan the directory and get the list of items.
			$items = scandir( $directory );

			// Loop through each item.
			foreach ( $items as $item ) {
				// Check if it is a directory and not "." or "..".
				if ( is_dir( $directory . '/' . $item ) && $item != '.' && $item != '..' ) {
					$folder_names[] = $item;
				}
			}
		}

		return $folder_names;
	}
}
new WP_Theme_Blocks();
