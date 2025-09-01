<?php
/**
 * Custom functions added to all projects
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Base Theme Package
 * @since 1.0.0
 */

namespace BaseTheme\Settings;

/**
 * Template Class For Theme Settings
 *
 * Template Class
 *
 * @category Setting_Class
 * @package  Base Theme Package
 */
class WP_Theme_Settings {
	/**
	 * Define class Constructor
	 **/
	public function __construct() {
		add_filter( 'upload_mimes', array( $this, 'svg_upload_support' ) );
		add_filter( 'login_headerurl', array( $this, 'login_logo_url' ) );
		// add_filter( 'gform_tabindex', array( $this, 'change_tabindex' ), 10, 2 );
		add_filter( 'wp_nav_menu_objects', array( $this, 'first_last_menu_classes' ) );
		add_filter( 'get_the_archive_title', array( $this, 'theme_archive_title' ) );

		add_action( 'login_head', array( $this, 'login_logo' ) );
		add_action( 'admin_head', array( $this, 'theme_favicon' ) );
		add_action( 'wp_head', array( $this, 'viewport' ) );

		add_filter( 'body_class', array( $this, 'add_custom_body_class' ) );

		add_filter( 'post_thumbnail_html', array( $this, 'post_thumbnail_fallback' ), 15, 5 );
		add_filter( 'wp_get_attachment_image', array( $this, 'wp_get_attachment_image_callback' ), 15, 5 );

		add_action( 'wp_footer', array( $this, 'inject_custom_scroll_code_frontend' ) );
		add_action( 'admin_footer', array( $this, 'inject_custom_scroll_code_backend' ) );

	}


	/**
	 * Function include a var to frontend.
	 */
	public function inject_custom_scroll_code_frontend() {
		?>
		<script type="text/javascript">const wpbox=window;</script>
		<?php
	}
	/**
	 * Function include a var to backend.
	 */
	public function inject_custom_scroll_code_backend() {
		?>
		<script type="text/javascript">const wpbox= '.interface-navigable-region';</script>
		<?php
	}

	/**
	 * Add Svg to default mime types
	 *
	 * @param array $mimes is the mime type in WordPress.
	 *
	 * @return array
	 */
	public function svg_upload_support( $mimes ) {
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}

	/**
	 * Remove default WordPress login logo link & set it to homepage of site
	 *
	 * @param string $url is logo url.
	 *
	 * @return url
	 */
	public function login_logo_url( $url ) {
		return '"' . home_url() . '"';
	}

	/**
	 * Add viewport meta tag in head
	 *
	 *  @return void
	 */
	public function viewport() {
		echo '
			<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		';
	}

	/**
	 * Set Tabindex For Gravity Form
	 *
	 *  @param string $tabindex .
	 *  @param string $form .
	 *
	 *  @return number
	 */
	public function change_tabindex( $tabindex, $form ) {
		return 10;
	}

	/**
	 * First and last menu item classes
	 *
	 *  @param string $items is menu item.
	 *
	 *  @return object
	 */
	public function first_last_menu_classes( $items ) {
		if ( $items ) {
			$items[1]->classes[]                 = 'first-menu-item';
			$items[ count( $items ) ]->classes[] = 'last-menu-item';
			return $items;
		}
		return $items;
	}

	/**
	 * Set favicon of dashboard
	 *
	 *  @return void
	 */
	public function theme_favicon() {
		$favicon_path = get_template_directory_uri() . '/assets/build/images/pwa/favicon.ico';

		echo '<link rel="shortcut icon" href="' . esc_url( $favicon_path ) . '" />';
	}

	/**
	 * Custom logo for WordPress login screen
	 *
	 * This function replaces the default WordPress logo on the login with website logo.
	 */
	public function login_logo() {
		echo '
			<style type="text/css">
				.login h1 a {
					background-image: url(' . esc_url( get_stylesheet_directory_uri() ) . '/assets/build/images/site-logo.svg) !important;
					background-position: center center;
					color:rgba(0, 0, 0, 0);
					background-size: contain;
					height: 80px;
					width: 80%;
					outline: 0;
				}
			</style>
		';
	}

	/**
	 * Function to remove the starting words from the_archive_title()
	 *
	 * E.g. from Category : Dallas Neighborhoods => Dallas Neighborhoods
	 *
	 * @param string $title is the title of template.
	 *
	 *  @return string
	 */
	public function theme_archive_title( $title ) {
		if ( is_category() ) {
			$title = single_cat_title( '', false );
		} elseif ( is_tag() ) {
			$title = single_tag_title( '', false );
		} elseif ( is_author() ) {
			$title = get_the_author_meta( 'display_name' );
		} elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );
		} elseif ( is_tax() ) {
			$title = single_term_title( '', false );
		}

		return $title;
	}

	/**
	 * Add custom class to single based on post type for styling.
	 *
	 * @param array $classes list of body classes.
	 *
	 * @return array
	 */
	public function add_custom_body_class( $classes ) {
		if ( is_single() ) {
			$classes[] = get_post_type() . '-detail-single';
		}
		return $classes;
	}

	/**
	 * Function to make size full a warning.
	 *
	 * @param string $html HTML of the image tag.
	 * @param int    $post_thumbnail_id thumbnail id.
	 * @param string $size thumbnail size.
	 * @param string $icon thumbnail icon.
	 * @param array  $attr array of image attributes.
	 *
	 * @return string
	 */
	public function wp_get_attachment_image_callback( $html, $post_thumbnail_id, $size, $icon, $attr ) {
		if ( 'full' === $size ) {
			trigger_error( 'You cannot use full as a size', E_USER_WARNING );
		}
		return $html;
	}
	/**
	 * Function to make size full a warning.
	 *
	 * @param string $html HTML of the image tag.
	 * @param int    $post_id thumbnail icon.
	 * @param int    $post_thumbnail_id thumbnail id.
	 * @param string $size thumbnail size.
	 * @param array  $attr array of image attributes.
	 *
	 * @return string
	 */
	public function post_thumbnail_fallback( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
		if ( 'full' === $size ) {
			trigger_error( 'You cannot use full as a size', E_USER_WARNING );
		}
		return $html;
	}

}
new WP_Theme_Settings();
