<?php
/**
 * Custom related functions
 *
 * @link
 *
 * @package Stone Stomper
 * @since 1.0.0
 */

namespace StoneStomper\Custom;

/**
 * Template Class For Custom
 *
 * Template Class
 *
 * @category Setting_Class
 * @package  Stone Stomper
 */
class WP_Theme_Custom extends \Boilerplate {



	/**
	 * Define class Constructor
	 **/
	public function __construct() {

	}



	/**
	 * Helper function that builds button from ACF link object
	 *
	 * @param object $object is a acf button object.
	 * @param string $classes are the string of classes of acf button.
	 *
	 * @return string
	 */
	public static function button( $object, $classes = '' ) {
		if ( $object['url'] ) {
			$link  = '';
			$link  = "<a href='" . esc_url( $object['url'] ) . "'";
			$link .= " title='" . esc_html( $object['title'] ) . "'";
			$link .= " aria-label='" . esc_html( $object['title'] ) . "'";
			if ( '' !== $object['target'] && null !== $object['target'] ) {
				$link .= " target='" . $object['target'] . "'";
			}
			if ( '' !== $classes ) {
				$link .= " class='" . $classes . "'";
			}
			$link .= '>' . esc_html( $object['title'] ) . '</a>';
			return $link;
		}
		return null;
	}

	/**
	 * Excerpt Function
	 *
	 * @param number $count is a number of words needed in the excerpt
	 *
	 * Function used to create custom excerpt.
	 */
	public static function excerpt( $count ) {
		global $post;
		if ( has_excerpt() ) {

			$excerpt = get_the_excerpt();
			$excerpt = wp_strip_all_tags( $excerpt );
			$excerpt = substr( $excerpt, 0, $count );
			$excerpt = substr( $excerpt, 0, strripos( $excerpt, ' ' ) );
			$excerpt = $excerpt . ' ...';
			$excerpt = $excerpt;
			return $excerpt;
		}
		return '';
	}


	/**
	 * Excerpt with no read more option
	 *
	 * Function used to create custom excerpt.
	 *
	 * @param number $count is a number of words needed in the excerpt.
	 *
	 * @return string
	 */
	public static function excerpt_nomore( $count ) {
		global $post;
		if ( has_excerpt() ) {
			$excerpt = get_the_excerpt();
			$excerpt = wp_strip_all_tags( $excerpt );
			$excerpt = substr( $excerpt, 0, $count );
			$excerpt = substr( $excerpt, 0, strripos( $excerpt, ' ' ) );
			$excerpt = $excerpt;
			return $excerpt;
		}
		return '';
	}

	/**
	 * Youtube id handler.
	 *
	 * Possible Combination are following.
	 *
	 * https://youtu.be/osCyC2whgW8
	 * https://www.youtube.com/watch?v=osCyC2whgW8
	 * https://www.youtube.com/embed/osCyC2whgW8
	 *
	 * @param string $url facebook url.
	 *
	 * @return string
	 */
	public static function youtube_id( $url ) {
		if ( str_contains( $url, 'youtu.be' ) ) {
			$youtube_id = explode( '/', $url )[ count( explode( '/', $url ) ) - 1 ];
		} elseif ( str_contains( $url, 'youtube.com' ) && str_contains( $url, 'watch' ) ) {
			$youtube_id = explode( '=', $url )[1];
		} elseif ( str_contains( $url, 'youtube.com' ) && str_contains( $url, 'embed' ) ) {
			$youtube_id = explode( '/', $url )[ count( explode( '/', $url ) ) - 1 ];
		} else {
			$youtube_id = 0;
		}
		return $youtube_id;
	}

	/**
	 * Youtube url handler.
	 *
	 * Possible Combination are following.
	 *
	 * https://youtu.be/osCyC2whgW8
	 * https://www.youtube.com/watch?v=osCyC2whgW8
	 * https://www.youtube.com/embed/osCyC2whgW8
	 *
	 * @param string $url facebook url.
	 *
	 * @return string
	 */
	public static function youtube_url( $url ) {
		if ( 0 === self::youtube_id( $url ) ) {
			$youtube_url = 'Wrong Url';
		} else {
			$youtube_url = 'https://www.youtube.com/watch?v=' . self::youtube_id( $url );
		}
		return $youtube_url;
	}

	/**
	 * Advance Video field output
	 *
	 * Possible Combination are following.
	 *
	 * @param array $video facebook url.
	 *
	 * @return string
	 */
	public static function advance_video( $video ) {
		return '';
	}
	/**
	 * Media Box function
	 *
	 * @param array     $media media_array.
	 * @param array|int $media_thumb media thumb
	 */
	public static function media_box( $media, $media_thumb ) {
		$media_option = $media['media_option'] ?? null;
		$media_image  = $media['media_image'] ?? null;
		$media_video  = $media['media_video'] ?? null;
		if ( 'image' === $media_option ) {
			if ( $media_image ) {
				echo html_entity_decode( self::the_attachment_image( $media_image, $media_thumb ) );
			}
		} else {
			if ( $media_video ) {
				echo html_entity_decode( '<video loop src="' . esc_url( wp_get_attachment_url( $media_video ) ) . '" playsinline autoplay muted></video>' );
			}
		}
	}

	/**
	 * Output advance form acf field data.
	 *
	 * @param array $form facebook url.
	 *
	 * @return string
	 */
	public static function advance_form( $form ) {
		$sts_form_provider = $form['form_provider'] ?? null;
		if ( 'gform' === $form['form_provider'] ) {
			$sts_gform = $form['gform'] ?? null;
			if ( $sts_gform ) {
				echo do_shortcode( '[gravityform id="' . $sts_gform . '" title=false description=false ajax=true]' );
			}
		} else {
			$sts_hubspot = $form['hubspot'] ?? null;
			if ( $sts_hubspot ) {
				echo html_entity_decode( $sts_hubspot );
			}
		}
		return '';
	}
	/**
	 * Recursive sanitation for an array
	 *
	 * @param array $array array data.
	 *
	 * @return mixed
	 */
	public static function recursive_sanitize_text_field( $array ) {
		foreach ( $array as $key => &$value ) {
			if ( is_array( $value ) || is_object( $value ) ) {
				$value = recursive_sanitize_text_field( $value );
			} else {
				$value = sanitize_text_field( $value );
			}
		}

		return $array;
	}




	/**
	 * Return escaped string
	 *
	 * @param string $string string to decode.
	 *
	 * @return string
	 */
	public static function html_entity_remove( $string ) {
		return sanitize_text_field( html_entity_decode( $string ) );
	}

	/**
	 * Fallback function for menus
	 *
	 * @return void
	 */
	public static function nav_fallback() {

		if ( is_user_logged_in() ) {
			?>
			<ul>
				<li> <?php echo esc_html__( 'Go to admin area to create navigation menu', 'basetheme_td' ); ?></li>
			</ul>
			<?php
		}
	}




	/**
	 * A Function that check if post exist then print class;
	 *
	 * @param string $class post class.
	 *
	 * @return void
	 */
	public static function have_post_class( $class ) {
		if ( have_posts() ) {
			echo esc_html( $class );
		}
	}

	/**
	 * A Function that check if site is live of not
	 *
	 * @return boolean
	 */
	public static function if_live() {

		if ( 'local' === wp_get_environment_type() ) {
			return false;
		} elseif ( 'development' === wp_get_environment_type() ) {
			return false;
		} elseif ( 'staging' === wp_get_environment_type() ) {
			return false;
		} elseif ( 'production' === wp_get_environment_type() ) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * Defaults For Theme.
	 *
	 * @param string $item_id ID of the item.
	 * @return array Array of $post_id, $fields, $option_fields, $queried_object.
	 */
	public static function defaults( $item_id = null ) {
		if ( $item_id ) {
			$post_id = $item_id;
		} else {

			$post_id = get_the_ID();
			if ( is_home() ) {
				$post_id = get_option( 'page_for_posts' );
			}
		}
		$queried_object = get_queried_object();
		$option_fields  = self::get_fields_escaped( 'option' );
		$fields         = self::get_fields_escaped( $post_id );

		return array( $post_id, $fields, $option_fields, $queried_object );
	}



	/**
	 * Helper function to get escaped field from ACF
	 * and also normalize values.
	 *
	 * @param string $field_key is the acf key name.
	 * @param string $escape_method is the method of escaping html.
	 *
	 * @return mixed
	 */
	public static function get_fields_escaped( $field_key, $escape_method = 'esc_html' ) {
		if ( function_exists( 'get_fields' ) ) {
			$field = get_fields( $field_key );
		}
		/* Check for null and falsy values and always return space */
		if ( false === $field || null === $field ) {
			$field = '';
		}

		/* Handle arrays */
		if ( is_array( $field ) || is_object( $field ) ) {
			$field_escaped = array();
			foreach ( $field as $key => $value ) {
				if ( is_array( $value ) || is_object( $value ) ) {
					$field_escaped[ $key ] = self::get_sub_field_escaped( $value, $escape_method );
				} else {
					$field_escaped[ $key ] = self::if_exist( ( null === $escape_method ) ? $value : self::keep_types( $value, $escape_method( $value ) ) );
					// $field_escaped[$key] =   esc_html($value);
				}
			}
			return $field_escaped;
		} else {
			return self::if_exist( ( null === $escape_method ) ? $field : self::keep_types( $field, $escape_method( $field ) ) );
		}
	}

	/**
	 * Helper function to get escaped field for a sub-field from ACF inside a parent
	 * and also normalize values.
	 *
	 * @param string $parent is the acf key name.
	 * @param string $escape_method is the method of escaping html.
	 *
	 * @return mixed
	 */
	private static function get_sub_field_escaped( $parent = null, $escape_method = 'esc_html' ) {
		$field = $parent;
		/* Check for null and falsy values and always return space */
		if ( false === $field || null === $field ) {
			$field = '';
		}

		/* Handle arrays */
		if ( is_array( $field ) || is_object( $field ) ) {
			$field_escaped = array();
			foreach ( $field as $key => $value ) {
				if ( is_array( $value ) || is_object( $value ) ) {
					if ( is_object( $value ) ) {
						$obj = new \stdClass();

						foreach ( $value as $obj_k => $obj_v ) {

							$obj->$obj_k = self::if_exist( ( null === $escape_method ) ? $obj_v : self::keep_types( $obj_v, $escape_method( $obj_v ) ) );
						}
						$field_escaped[ $key ] = $obj;
					} else {
						$field_escaped[ $key ] = self::get_sub_field_escaped( $value, $escape_method );
					}
				} else {

					$field_escaped[ $key ] = self::if_exist( ( null === $escape_method ) ? $value : self::keep_types( $value, $escape_method( $value ) ) );
				}
			}
			return $field_escaped;
		} else {
			return self::if_exist( ( null === $escape_method ) ? $field : self::keep_types( $field, $escape_method( $field ) ) );
		}

	}

	/**
	 * Get Author data.
	 *
	 * @param int $post_id post id.
	 */
	public static function get_author_data( $post_id ) {
		$author_id = (int) get_post_field( 'post_author', $post_id );
		if ( function_exists( 'get_field' ) ) {
			$avatar = get_field( 'sts_var_author_avatar', 'user_' . $author_id );
		}
		if ( ! $avatar ) {
			$avatar = get_avatar_url( $author_id );
		}
		// Get author name with fallback to display name.
		if ( get_the_author_meta( 'first_name', $author_id ) || get_the_author_meta( 'last_name', $author_id ) ) {
			$author_name = get_the_author_meta( 'first_name', $author_id ) . ' ' . get_the_author_meta( 'last_name', $author_id );
		} elseif ( get_the_author_meta( 'display_name', $author_id ) ) {
			$author_name = get_the_author_meta( 'display_name', $author_id );
		}
		return array( $avatar, $author_name );
	}


	/**
	 * A Function that return text color based on bg color
	 *
	 * @param string $bg_color  background color.
	 *
	 * @return string
	 */
	public static function get_contrasting_text_color( $bg_color ) {
		// Remove the leading '#' if present.
		if ( $bg_color ) {

			if ( strpos( $bg_color, '#' ) === 0 ) {
				$bg_color = substr( $bg_color, 1 );
			}

			// Convert the hexadecimal color to RGB values.
			$r = hexdec( substr( $bg_color, 0, 2 ) );
			$g = hexdec( substr( $bg_color, 2, 2 ) );
			$b = hexdec( substr( $bg_color, 4, 2 ) );

			// Calculate the relative luminance of the color.
			$luminance = ( 0.299 * $r + 0.587 * $g + 0.114 * $b ) / 255;

			// Choose the text color based on the luminance threshold (adjust the threshold as needed).
			$text_color = $luminance > 0.5 ? '#000000' : '#ffffff';

			return $text_color;
		}
		return '#000000';
	}

	/**
	 * Register custom Gutenberg blocks category
	 *
	 *  @param array $block is a array of block styles.
	 *
	 *  @return string
	 */
	public static function convert_to_css( $block ) {
		$typography = ( isset( $block['style']['typography'] ) ) ? $block['style']['typography'] : null;
		$font_sizes = array(
			'small'   => '16px',
			'medium'  => '18px',
			'large'   => '20px',
			'x-large' => '26px',
		);
		$font_size  = ( isset( $block['fontSize'] ) ) ? $block['fontSize'] : null;
		$css        = '';
		if ( $typography ) {

			foreach ( $typography as $property => $value ) {
				$property = preg_replace( '/(?<!^)[A-Z]/', '-$0', $property );
				$css     .= $property . ': ' . $value . '; ';
			}
		}
		if ( $font_size ) {

			$css .= 'font-size:' . $font_sizes[ $font_size ];
		}
		return $css;
	}

	/**
	 * Check if value exist
	 *
	 * @param mixed $value value to be checked.
	 *
	 * @return string
	 */
	public static function if_exist( $value ) {
		return ( isset( $value ) && '' !== $value && $value ) ? $value : null;
	}

	/**
	 * Retain the type value
	 *
	 * @param mixed $item value to be checked.
	 * @param mixed $value actual value that will be returned.
	 *
	 * @return mixed
	 */
	public static function keep_types( $item, $value ) {
		$type = gettype( $item );
		settype( $value, $type );
		return $value;
	}

	/**
	 * Echo the image attachment Image
	 *
	 * @param int     $image_id contain image the id that be echoed.
	 * @param int     $thumb_size contain the size of image.
	 * @param array   $args extra args if needed.
	 * @param boolean $hide_backup hide the backup image.
	 *
	 * @return void
	 */
	public static function the_attachment_image( $image_id, $thumb_size, $args = array(), $hide_backup = false ) {
		$alt                = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
		$title              = get_the_title( $image_id );
		$desktop_thumb_size = 1000;
		$mobile_thumb_size  = 400;
		if ( is_array( $thumb_size ) ) {
			list($desktop_thumb_size,$mobile_thumb_size) = $thumb_size;
		} else {
			$desktop_thumb_size = $thumb_size;
			$mobile_thumb_size  = $thumb_size;
		}
		$desktop_thumb_size_with_prefix = 'thumb_' . $desktop_thumb_size;
		$mobile_thumb_size_with_prefix  = 'thumb_' . $mobile_thumb_size;

		$thumb_size_with_prefix = ( wp_is_mobile() ) ? $mobile_thumb_size_with_prefix : $desktop_thumb_size_with_prefix;

		$args_all = array(
			'alt'   => $alt,
			'title' => $title,
		);

		$args_all = array_merge( $args_all, $args );

		$attachment_image = wp_get_attachment_image( $image_id, $thumb_size_with_prefix, null, $args_all );
		// Check if the attachment image is empty and a backup image is provided.
		if ( empty( $attachment_image ) && ! $hide_backup ) {
			// Use the backup image.
			$option_fields    = self::get_fields_escaped( 'option' );
			$default_image_id = $option_fields['crm_var_theme_default_image'] ?? null;
			$default_image    = wp_get_attachment_image( $default_image_id, $thumb_size_with_prefix, null, $args_all );
			if ( $default_image_id ) {
				echo html_entity_decode( $default_image );
			} else {
				echo html_entity_decode( '<img class="is-default-image" loading="lazy" src="' . BASETHEME_DEFAULT_IMAGE . '" />' );
			}
		} else {
			// Output the attachment image.
			echo html_entity_decode( $attachment_image );
		}
	}
	/**
	 * Echo the Post attachment Image
	 *
	 * @param int     $post_id contain image the id that be echoed.
	 * @param int     $thumb_size contain the size of image.
	 * @param array   $args extra args if needed.
	 * @param boolean $hide_backup hide the backup image.
	 *
	 * @return void
	 */
	public static function the_featured_image( $post_id, $thumb_size, $args = array(), $hide_backup = false ) {
		$post_image_id = get_post_thumbnail_id( $post_id );

		if ( is_array( $thumb_size ) ) {
			list($desktop_thumb_size,$mobile_thumb_size) = $thumb_size;
		} else {
			$desktop_thumb_size = $thumb_size;
			$mobile_thumb_size  = $thumb_size;
		}
		$desktop_thumb_size_with_prefix = 'thumb_' . $desktop_thumb_size;
		$mobile_thumb_size_with_prefix  = 'thumb_' . $mobile_thumb_size;

		$thumb_size_with_prefix = ( wp_is_mobile() ) ? $mobile_thumb_size_with_prefix : $desktop_thumb_size_with_prefix;

		if ( $post_image_id ) {
			$args_all = array(
				'alt'   => get_post_meta( $post_image_id, '_wp_attachment_image_alt', true ),
				'title' => get_the_title( $post_image_id ),
			);

			$args_all = array_merge( $args_all, $args );

			$post_image = wp_get_attachment_image(
				$post_image_id,
				$thumb_size_with_prefix,
				false,
				$args_all
			);
		} else {
			if ( ! $hide_backup ) {
				$option_fields    = self::get_fields_escaped( 'option' );
				$default_image_id = $option_fields['crm_var_theme_default_image'] ?? null;
				$default_image    = wp_get_attachment_image( $default_image_id, $thumb_size_with_prefix, null );
				if ( $default_image_id ) {
					$post_image = $default_image;
				} else {
					$post_image = '<img class="is-default-image" loading="lazy" src="' . BASETHEME_DEFAULT_IMAGE . '" />';
				}
			}
		}

		// Output the image.
		echo html_entity_decode( $post_image );
	}
	/**
	 * Getting the Post Data
	 *
	 * @param array  $data title data.
	 * @param string $class_name optional class name.
	 *
	 * @return void
	 */
	public static function the_block_title( $data, $class_name = '' ) {
		if ( isset( $data['title'] ) ) {
			$title     = $data['title'];
			$title_tag = htmlspecialchars( $data['title_tag'] );

			if ( ! empty( $class_name ) ) {
				$sanitized_class_name = ' class="' . htmlspecialchars( $class_name ) . '"';
			} else {
				$sanitized_class_name = '';
			}
			$decoded_title = html_entity_decode( $title );

			echo html_entity_decode( "<{$title_tag}{$sanitized_class_name}>" . $decoded_title . "</{$title_tag}>" );
		}
	}
	/**
	 * Check if title is not empty
	 *
	 * @param array $data title data.
	 *
	 * @return boolean
	 */
	public static function is_block_title( $data ) {
		// Check if "title" key is set and not NULL.
		return ( '' !== $data['title'] && $data['title'] ) ? true : false;
	}

	/**
	 * Getting the Post Data
	 *
	 * @param array  $spacer spacer array.
	 * @param string $position spacer position.
	 *
	 * @return void
	 */
	public static function the_spacer( $spacer, $position = 'top' ) {
		// Check if the spacer array contains 'top_spacer' and 'bottom_spacer'.
		if ( isset( $spacer['top_spacer'] ) ) {
			$top_spacer = $spacer['top_spacer'];
		} else {
			$top_spacer = '';
		}

		if ( isset( $spacer['bottom_spacer'] ) ) {
			$bottom_spacer = $spacer['bottom_spacer'];
		} else {
			$bottom_spacer = '';
		}

		// Check the value of the $topBott parameter and echo the appropriate spacer.
		if ( 'top' === $position ) {
			echo html_entity_decode( "<div class='{$top_spacer}'></div>" );
		} elseif ( 'bottom' === $position ) {
			echo html_entity_decode( "<div class='{$bottom_spacer}'></div>" );
		} else {
			echo 'Invalid value for $topBott parameter. Use "top" or "bottom".';
		}
	}

	/**
	 * Output Social Icons.
	 *
	 * @param array $social_icons social icon array.
	 */
	public static function the_social_icons( $social_icons, $output_type = true ) {
		if ( $social_icons ) {
			foreach ( $social_icons as $social_icon ) {
				$acf_fc_layout = $social_icon['acf_fc_layout'];
				$svg_code      = self::get_social_svg( $acf_fc_layout );
				$svg_link      = self::get_social_link( $acf_fc_layout );
				$social_link   = $social_icon['social_link'];

				// Create a title and aria-label based on the social icon layout or link.
				$title      = 'Visit our ' . ucfirst( $acf_fc_layout ) . ' page';
				$aria_label = 'Link to our ' . ucfirst( $acf_fc_layout ) . ' page';

				if ( $social_link ) {
					echo '<a href="' . esc_url( $social_link ) . '" target="_blank" class="' . esc_html( $acf_fc_layout ) . ' flex-center" title="' . esc_attr( $title ) . '" aria-label="' . esc_attr( $aria_label ) . '">';
					if ( $output_type ) {

						echo html_entity_decode( $svg_code );
					} else {
						echo '<img height="20" width="20" src="' . esc_url( $svg_link ) . '" title="' . esc_attr( $title ) . '" alt="' . esc_attr( $title ) . '">';
					}
					echo '</a>';
				}
			}
		}
	}

	/**
	 * Output Social icon svg.
	 *
	 * @param string $key social icon array.
	 */
	public static function get_social_svg( $key ) {
		return file_get_contents( get_template_directory() . '/assets/build/images/social-icons/' . $key . '.svg' );
	}
	/**
	 * Output Social icon link.
	 *
	 * @param string $key social icon array.
	 */
	public static function get_social_link( $key ) {
		return get_template_directory_uri() . '/assets/build/images/social-icons/' . $key . '.svg';
	}
	/**
	 * Check if the taxonomy terms exist.
	 *
	 * @param mixed $taxonomy        Contains the taxonomy name.
	 * @param bool  $hide_empty      Flag to hide empty terms.
	 *
	 * @return bool                  Returns true if terms exist, false otherwise.
	 */
	public static function is_taxonomy_terms_exist( $taxonomy, $hide_empty = false ) {
		$taxonomies     = get_taxonomies();
		$taxonomy_exist = false;

		foreach ( $taxonomies as $reg_taxonomy ) {
			if ( $reg_taxonomy === $taxonomy ) {
				$taxonomy_exist = true;
				break;
			}
		}

		if ( $taxonomy_exist ) {
			$terms = get_terms(
				array(
					'taxonomy'   => $taxonomy,
					'hide_empty' => $hide_empty,
				)
			);

			return ! empty( $terms ); // Return true if terms exist, false otherwise.
		} else {
			return false; // Taxonomy doesn't exist.
		}
	}

	/**
	 * Getting the Post Read Time
	 *
	 * @param mixed $post_id post id.
	 *
	 * @return void
	 */
	public static function calculate_post_read_time( $post_id ) {
		// Get the post content.
		$post_content = get_post_field( 'post_content', $post_id );

		// Remove HTML tags and decode HTML entities.
		$stripped_content = wp_strip_all_tags( html_entity_decode( $post_content ) );

		// Calculate the number of words in the post.
		$word_count = str_word_count( $stripped_content );

		// Set an average reading speed (words per minute).
		$average_reading_speed = 200; // You can adjust this value.

		// Calculate the estimated reading time in minutes.
		$read_time = ceil( $word_count / $average_reading_speed );

		echo esc_html( $read_time );
	}
	/**
	 * Page by Template.
	 *
	 * @param string $template_name template name.
	 *
	 * @return id
	 */
	public static function page_by_template( $template_name ) {

		$template_name = 'templates/template-' . $template_name . '.php';

		// Get all pages.
		$pages = get_pages();

		// Loop through each page.
		foreach ( $pages as $page ) {
			// Get the template file name for the page.
			$template_slug = get_page_template_slug( $page->ID );

			// Check if the page is assigned to the specified template.
			if ( $template_slug === $template_name ) {
				return $page->ID;
			}
		}
	}
	/**
	 * Check if template is /blog.
	 */
	public static function is_blog() {
		// Get the current URL.
		$current_url = ( isset( $_SERVER['REQUEST_URI'] ) ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : null;
		if ( $current_url ) {

			// Define the pattern for "/blog/page/" followed by any number.
			$pattern = '/\/blog\/page\/\d+/';

			// Check if the pattern is present in the URL.
			if ( preg_match( $pattern, $current_url ) ) {
				return true;
			}
			return false;
		}
		return false;
	}
	/**
	 * Enqueue Script.
	 *
	 * @param string      $path path of the file.
	 * @param array       $dependencies file dependencies.
	 * @param string|null $local_vars local var name.
	 * @param array       $data local var data.
	 */
	public static function enqueue_script( $path, $dependencies = array( 'jquery' ), $local_vars = null, $data = array(), $args = array() ) {
		$version = filemtime( get_template_directory() . '/' . $path );
		$name    = explode( '.', basename( $path ) )[0] ?? basename( $path );
		$name    = 'wp-theme-' . $name;
		wp_register_script( $name, get_template_directory_uri() . '/' . $path, $dependencies, $version, $args );
		if ( $local_vars ) {
			wp_localize_script(
				$name,
				$local_vars,
				$data
			);
		}
		wp_enqueue_script( $name );
		return $name;
	}
	/**
	 * Enqueue Scripts.
	 *
	 * @param array $paths paths of the file.
	 */
	public static function enqueue_scripts( $paths ) {
		$dependencies = array( 'jquery' );
		foreach ( $paths as $path ) {
			$dependencies[] = self::enqueue_script( $path, $dependencies );
		}
		return $dependencies;
	}

	/**
	 * Enqueue Script.
	 *
	 * @param string      $path path of the file.
	 * @param array       $dependencies file dependencies.
	 * @param string|null $local_vars local var name.
	 * @param array       $data local var data.
	 */
	public static function register_script( $path, $dependencies = array( 'jquery' ), $local_vars = null, $data = array(), $args = array() ) {
		$version = filemtime( get_template_directory() . '/' . $path );
		$name    = explode( '.', basename( $path ) )[0] ?? basename( $path );
		$name    = 'wp-theme-' . $name;
		wp_register_script( $name, get_template_directory_uri() . '/' . $path, $dependencies, $version, $args );
		if ( $local_vars ) {
			wp_localize_script(
				$name,
				$local_vars,
				$data
			);
		}
		return $name;
	}
	/**
	 * Register Scripts.
	 *
	 * @param array $paths paths of the file.
	 */
	public static function register_scripts( $paths ) {
		$dependencies = array( 'jquery' );
		foreach ( $paths as $path ) {
			$dependencies[] = self::register_script( $path, $dependencies );
		}
		return $dependencies;
	}
	/**\
	 * Enqueue Style.
	 *
	 * @param string $path path of the file.
	 */
	public static function enqueue_style( $path ) {
		$version = filemtime( get_template_directory() . '/' . $path );
		$name    = explode( '.', basename( $path ) )[0] ?? basename( $path );
		wp_enqueue_style( 'wp-theme-' . $name, get_template_directory_uri() . '/' . $path, false, $version );
	}


}

class_alias( 'StoneStomper\Custom\WP_Theme_Custom', 'StoneStomper' );
