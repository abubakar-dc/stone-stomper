<?php
/**
 * Functions for editor styles
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Base Theme Package
 * @since 1.0.0
 */

namespace BaseTheme\Editor;

/**
 * Template Class For Acf Settings
 *
 * Template Class
 *
 * @category Setting_Class
 * @package  Base Theme Package
 */
class WP_Theme_Editor {
	/**
	 * Define class Constructor
	 **/
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'editor_css_support' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'custom_editor_assets' ) );
	}

	/**
	 * Add support for editor styles
	 *
	 * @return void
	 */
	public function editor_css_support() {
		add_theme_support( 'editor-styles' ); // if you don't add this line, your stylesheet won't be added.
	}

	/**
	 * Add CSS to Gutenberg Editor and the Blocks
	 *
	 * @return void
	 */
	public function custom_editor_assets() {

		// Custom block styles.
		$version = filemtime( get_template_directory() . '/assets/build/editor.min.js' );
		wp_enqueue_script( 'editor-js', esc_url( get_stylesheet_directory_uri() ) . '/assets/build/editor.min.js', array( 'jquery' ), $version, true );
	}

}
new WP_Theme_Editor();
