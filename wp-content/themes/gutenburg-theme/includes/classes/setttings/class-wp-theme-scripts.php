<?php
/**
 * Setup function for the project
 *
 * @link https://developer.wordpress.org/themes/basics/including-css-javascript/
 *
 * @package Base Theme Package
 * @since 1.0.0
 */

namespace BaseTheme\Script;

use BaseTheme;
/**
 * Theme assets
 *
 * Define variable to store asset directory folder in it.
 *
 * That can be used afterward to call stylesheet / scripts etc
 */

// Time format for the_time().
define( 'BASETHEME_PROJECT_DTFORMAT', 'F j, Y' );
/**
 * Theme assets
 *
 * Enqueue and Dequeue required files
 */
class WP_Theme_Scripts {
	/**
	 * Define class Constructor
	 **/
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'theme_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );
	}
	/**
	 * Enqueue Frontend Assets
	 *
	 * @return void
	 */
	public function theme_assets() {
		// Enqueue theme styles.
		BaseTheme::enqueue_style( 'assets/build/styles.min.css' );
		if ( wp_is_mobile() ) {
			BaseTheme::enqueue_style( 'assets/build/mobile.min.css' );
		}
		// Eliminate the emoji script.
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		// Enqueue comments reply script on single post pages.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		if ( ! is_admin() && ! is_user_logged_in() ) {
			// Deregister dashicons on frontend.
			wp_deregister_style( 'dashicons' );
		}

		wp_enqueue_script( 'jquery' );
		// Register project scripts.
		BaseTheme::enqueue_script(
			'assets/build/scripts.min.js',
			array( 'jquery' ),
			'localVars',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => esc_html( wp_create_nonce( 'ajax_nonce' ) ),
			),
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
		BaseTheme::enqueue_script(
			'assets/build/header.min.js',
			array( 'jquery' ),
			args:array(
				'in_footer' => false,
				'strategy'  => 'defer',
			)
		);
	}
	/**
	 * Enqueue Backend Assets
	 *
	 * @return void
	 */
	public function admin_assets() {
		BaseTheme::enqueue_script(
			'assets/build/vendors/admin-scripts.js',
			array( 'jquery' ),
			'localVars',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => esc_html( wp_create_nonce( 'admin_ajax_nonce' ) ),
			)
		);
		BaseTheme::enqueue_style( 'assets/build/editor.min.css' );
	}
}
new WP_Theme_Scripts();

