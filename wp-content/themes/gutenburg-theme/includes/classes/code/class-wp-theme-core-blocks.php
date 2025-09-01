<?php
/**
 * Code Blocks Related Changes
 *
 * @link https://codex.wordpress.org/AJAX#Ajax_in_WordPress
 *
 * @package Base Theme Package
 * @since 1.0.0
 */

namespace BaseTheme\Core;

/**
 * Template Class For Ajax
 *
 * Template Class
 *
 * @category Setting_Class
 * @package  Base Theme Package
 */
class WP_Core_Blocks {
	/**
	 * Define class Constructor
	 **/
	public function __construct() {
		add_filter( 'render_block', array( $this, 'core_button' ), 10, 2 );
	}

	/**
	 * Core Block HtML update function.
	 *
	 * @param string $block_content block html.
	 * @param object $block block object.
	 */
	public function core_button( $block_content, $block ) {
		if ( 'core/button' === $block['blockName'] ) {
			$processor  = new \WP_HTML_Tag_Processor( $block_content );
			$new_html = $processor->get_updated_html();
			return $new_html;
		}
		return $block_content;
	}

}
new WP_Core_Blocks();
