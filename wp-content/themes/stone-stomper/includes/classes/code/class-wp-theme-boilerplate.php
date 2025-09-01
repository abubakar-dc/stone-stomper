<?php
/**
 * Boilerplate Class.
 *
 * @link
 *
 * @package Stone Stomper
 * @since 1.0.0
 */

namespace StoneStomper\Boilerplate;

use StoneStomper;
/**
 * Template Class For Custom
 *
 * Template Class
 *
 * @category Setting_Class
 * @package  Stone Stomper
 */
class WP_Theme_Boilerplate {

	/**
	 * Block Render Boilerplate.
	 *
	 * @param array $block block data.
	 * @param mixed $callback_function callback function.
	 */
	public static function block( $block, $callback_function ) {
		list( $sts_block_id, $sts_block_fields,$sts_option_fields ) = StoneStomper::defaults( $block['id'] );

		// Set the block name for it's ID & class from it's file name.
		$sts_block_name   = $block['name'];
		$sts_block_name   = str_replace( 'acf/', '', $sts_block_name );
		$sts_block_styles = StoneStomper::convert_to_css( $block );
		// Set the preview thumbnail for this block for gutenberg editor view.
		if ( isset( $block['data']['preview'] ) && 'preview' === $block['mode'] && isset( $block['data']['preview'] ) ) {
			$version = filemtime( get_template_directory() . '/blocks/' . $sts_block_name . '/' . $block['data']['preview'] );
			echo '<img src="' . esc_url( get_template_directory_uri() . '/blocks/' . $sts_block_name . '/' . $block['data']['preview'] ) . '?v=' . esc_html( $version ) . '" style="width:100%; height:auto;">';
		}

		// create align class ("alignwide") from block setting ("wide").
		$sts_var_align_class = $block['align'] ? 'align' . $block['align'] : '';

		// Get the class name for the block to be used for it.
		$sts_var_class_name = ( isset( $block['className'] ) ) ? $block['className'] : null;

		// Making the unique ID for the block.
		$sts_block_html_id = 'block-' . $sts_block_name . '-' . $block['id'];

		// Making the unique ID for the block.
		if ( $block['name'] ) {
			$sts_block_name = $block['name'];
			$sts_block_name = str_replace( '/', '-', $sts_block_name );
			$sts_var_name   = 'block-' . $sts_block_name;
		}
		echo '<div id="' . esc_html( $sts_block_html_id ) . '" class="' . esc_html( $sts_var_align_class . ' ' . $sts_var_class_name . ' ' . $sts_var_name ) . ' block-' . esc_html( $sts_block_name ) . '" style="' . esc_html( $sts_block_styles ) . '"> ';

		$spacers = self::get_spacer( $sts_block_fields );
		StoneStomper::the_spacer( $spacers, 'top' );
		$callback_function( $sts_block_id, $sts_block_name, $sts_block_fields, $sts_option_fields );
		StoneStomper::the_spacer( $spacers, 'bottom' );
		echo '</div>';
		wp_enqueue_script( 'wp-theme-' . str_replace( 'acf-', '', $sts_block_name ) );
	}
	/**
	 * Query Boilerplate.
	 *
	 * @param array $args Arguments for the WP_Query.
	 * @param array $display_args Display arguments for the WP_Query.
	 * @param array $html_args HTML arguments for the WP_Query.
	 * @param array $pagination_fun HTML arguments for the WP_Query.
	 */
	public static function query( $args = array(), $display_args = array(), $html_args = array(), $pagination_fun = 'pagination' ) {
		if ( $args ) {
			return self::custom_query( $args, $display_args, $html_args, $pagination_fun );
		} else {
			global $wp_query;
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();
					// Include specific template for the content.
					if ( is_search() || is_archive() ) {
						get_template_part( 'partials/content-archive', get_post_type() );
					} elseif ( is_single() ) {
						get_template_part( 'partials/content', get_post_type() );
					} else {
						get_template_part( 'partials/content', 'page' );
					}
				}
			} else {
				// If no content, include the "No posts found" template.
				get_template_part( 'partials/content', 'none' );
			}
			return $wp_query;
		}
		wp_reset_postdata();
	}

	/**
	 * Pagination Function
	 *
	 * The pagination function to display pagination on any archive page
	 *
	 * @param number $pages are total number of pages.
	 * @param array  $args is a array of pagination settings.
	 *
	 * @return void|string
	 */
	public static function pagination( $pages = '', $args = array() ) {
		$showitems  = $args['showitems'] ?? 5;
		$first_last = $args['first_last'] ?? true;
		$prev_next  = $args['prev_next'] ?? true;
		$show_range = $args['show_range'] ?? true;
		$has_dotted = $args['has_dotted'] ?? false;
		$is_ajax    = $args['is_ajax'] ?? false;

		$post_type                 = get_post_type();
		$pagination_unique_classes = 'pagination-' . $post_type;

		$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;

		if ( '' === $pages ) {
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if ( ! $pages ) {
				$pages = 1;
			}
		}

		if ( $is_ajax ) {
			$html = '';
			if ( 1 !== $pages ) {
				$pagination_unique_classes .= ' ajax-pagination-' . $post_type;

				$html .= '<div class="pagination">';
				if ( $show_range ) {
					$html .= '<div class="' . esc_html( $pagination_unique_classes ) . ' range-text">Page ' . esc_html( $paged ) . ' of ' . esc_html( $pages ) . '</div>';
				}
				if ( $first_last ) {
					$html .= '<span role="button" data-value="1" class="' . esc_html( $pagination_unique_classes ) . ' pagination-first" >&laquo; First</span>';
				}
				if ( $prev_next ) {
					$html .= '<span role="button" data-value="' . $paged - 1 . '" class="' . esc_html( $pagination_unique_classes ) . ' pagination-prev">&lsaquo; Previous</span>';
				}
				if ( $has_dotted ) {
					$is_event = ( 0 === $showitems % 2 ) ? true : false;
					if ( $is_event ) {
						$half_value = (int) floor( $showitems / 2 );
					} else {
						$half_value = (int) ceil( $showitems / 2 );
					}
					$half_value_other = $showitems - $half_value;
					$range_one        = array_merge( range( 0, $half_value - 1 ) );
					$range_two        = array_merge( range( 0, $half_value_other - 1 ) );

					$page_arr = array();
					for ( $x = $paged;$x <= $pages;$x++ ) {
						$page_arr[] = $x;
					}

					foreach ( $range_one as $idx ) {
						if ( empty( $page_arr[ $idx ] ) ) {
							continue;
						}
						if ( $page_arr[ $idx ] === $pages ) {
							$buffer[] = $page_arr[ $idx ];
						}
						if ( $page_arr[ $idx ] === $paged ) {
							$html .= '<span class="' . esc_html( $pagination_unique_classes ) . ' current">' . esc_html( $page_arr[ $idx ] ) . '</span>';
						} else {
							$html .= '<span role="button" data-value="' . $page_arr[ $idx ] . '" rel="prev" class="' . esc_html( $pagination_unique_classes ) . ' inactive">' . esc_html( $page_arr[ $idx ] ) . '</span>';
						}
						$buffer[] = $page_arr[ $idx ];

					}
					$html .= '<div class="' . esc_html( $pagination_unique_classes ) . ' pagination-dots">...</div>';

					$page_arr_2 = array();
					if ( $pages >= 2 ) {
						for ( $y = $pages - 2;$y <= $pages;$y++ ) {
							$page_arr_2[] = $y;
						}
						foreach ( $range_two as $idx ) {
							if ( in_array( $page_arr_2[ $idx ], $buffer ) ) {
								$html .= '';
							} else {
								if ( $page_arr_2[ $idx ] > $paged ) {
									$html .= '<span role="button" href="' . $page_arr_2[ $idx ] . '" rel="prev" class="' . esc_html( $pagination_unique_classes ) . ' inactive">' . esc_html( $page_arr_2[ $idx ] ) . '</span>';
								}
							}
						}
					}
				} else {
					for ( $i = $paged; $i <= $pages; $i++ ) {
						if ( 1 !== $pages && $i <= $showitems + ( $paged - 1 ) ) {
							if ( $paged > $i ) {
								$html .= ( $paged === $i ) ? '<span role="button" class="' . esc_html( $pagination_unique_classes ) . ' current">' . esc_html( $i ) . '</span>' : '<span role="button" data-value="' . $i . '" rel="prev" class="' . esc_html( $pagination_unique_classes ) . ' inactive">' . esc_html( $i ) . '</span>';
							} else {
								$html .= ( $paged === $i ) ? '<span role="button" class="' . esc_html( $pagination_unique_classes ) . ' current">' . esc_html( $i ) . '</span>' : '<span role="button" data-value="' . $i . '" rel="next" class="' . esc_html( $pagination_unique_classes ) . ' inactive">' . esc_html( $i ) . '</span>';
							}
						}
					}
				}
				if ( $prev_next ) {
					$html .= '<span role="button" data-value="' . $paged + 1 . '" class="' . esc_html( $pagination_unique_classes ) . ' pagination-next">Next &rsaquo;</span>';
				}
				if ( $first_last ) {
					$html .= '<span role="button" data-value="' . $pages . '" class="' . esc_html( $pagination_unique_classes ) . ' pagination-last">Last &raquo;</span>';
				}
				$html .= '<div class="clear"></div></div>';
			}
			return $html;
		} else {
			if ( 1 !== $pages ) {
				echo '<div class="pagination">';
				if ( $show_range ) {
					echo '<div class="' . esc_html( $pagination_unique_classes ) . ' range-text">Page ' . esc_html( $paged ) . ' of ' . esc_html( $pages ) . '</div>';
				}
				if ( $first_last ) {
					echo '<a href="' . esc_url( get_pagenum_link( 1 ) ) . '" class="' . esc_html( $pagination_unique_classes ) . ' pagination-first" >&laquo; First</a>';
				}
				if ( $prev_next ) {
					echo '<a href="' . esc_url( get_pagenum_link( $paged - 1 ) ) . '" class="' . esc_html( $pagination_unique_classes ) . ' pagination-prev">&lsaquo; Previous</a>';
				}
				if ( $has_dotted ) {
					$is_event = ( 0 === $showitems % 2 ) ? true : false;
					if ( $is_event ) {
						$half_value = (int) floor( $showitems / 2 );
					} else {
						$half_value = (int) ceil( $showitems / 2 );
					}
					$half_value_other = $showitems - $half_value;
					$range_one        = array_merge( range( 0, $half_value - 1 ) );
					$range_two        = array_merge( range( 0, $half_value_other - 1 ) );

					$page_arr = array();
					for ( $x = $paged;$x <= $pages;$x++ ) {
						$page_arr[] = $x;
					}

					foreach ( $range_one as $idx ) {
						if ( empty( $page_arr[ $idx ] ) ) {
							continue;
						}
						if ( $page_arr[ $idx ] === $pages ) {
							$buffer[] = $page_arr[ $idx ];
						}
						if ( $page_arr[ $idx ] === $paged ) {
							echo '<span role="button" class="' . esc_html( $pagination_unique_classes ) . ' current">' . esc_html( $page_arr[ $idx ] ) . '</span>';
						} else {
							echo '<a href="' . esc_url( get_pagenum_link( $page_arr[ $idx ] ) ) . '" rel="prev" class="' . esc_html( $pagination_unique_classes ) . ' inactive">' . esc_html( $page_arr[ $idx ] ) . '</a>';
						}
						$buffer[] = $page_arr[ $idx ];

					}
					echo '<div class="' . esc_html( $pagination_unique_classes ) . ' pagination-dots">...</div>';

					$page_arr_2 = array();
					if ( $pages >= 2 ) {
						for ( $y = $pages - 2;$y <= $pages;$y++ ) {
							$page_arr_2[] = $y;
						}
						foreach ( $range_two as $idx ) {
							if ( in_array( $page_arr_2[ $idx ], $buffer ) ) {
								echo '';
							} else {
								if ( $page_arr_2[ $idx ] > $paged ) {
									echo '<a href="' . esc_url( get_pagenum_link( $page_arr_2[ $idx ] ) ) . '" rel="prev" class="' . esc_html( $pagination_unique_classes ) . ' inactive">' . esc_html( $page_arr_2[ $idx ] ) . '</a>';
								}
							}
						}
					}
				} else {
					for ( $i = $paged; $i <= $pages; $i++ ) {
						if ( 1 !== $pages && $i <= $showitems + ( $paged - 1 ) ) {
							if ( $paged > $i ) {
								echo ( $paged === $i ) ? '<span role="button" class="' . esc_html( $pagination_unique_classes ) . ' current">' . esc_html( $i ) . '</span>' : '<a href="' . esc_url( get_pagenum_link( $i ) ) . '" rel="prev" class="' . esc_html( $pagination_unique_classes ) . ' inactive">' . esc_html( $i ) . '</a>';
							} else {
								echo ( $paged === $i ) ? '<span role="button" class="' . esc_html( $pagination_unique_classes ) . ' current">' . esc_html( $i ) . '</span>' : '<a href="' . esc_url( get_pagenum_link( $i ) ) . '" rel="next" class="' . esc_html( $pagination_unique_classes ) . ' inactive">' . esc_html( $i ) . '</a>';
							}
						}
					}
				}
				if ( $prev_next ) {
					echo '<a href="' . esc_url( get_pagenum_link( $paged + 1 ) ) . '" class="' . esc_html( $pagination_unique_classes ) . ' pagination-next">Next &rsaquo;</a>';
				}
				if ( $first_last ) {
					echo '<a href="' . esc_url( get_pagenum_link( $pages ) ) . '" class="' . esc_html( $pagination_unique_classes ) . ' pagination-last">Last &raquo;</a>';
				}
				echo '<div class="clear"></div></div>';
			}
		}

	}

	/**
	 * Query Boilerplate.
	 *
	 * @param array $args Arguments for the WP_Query.
	 * @param array $display_args Display arguments for the WP_Query.
	 * @param array $html_args HTML arguments for the WP_Query.
	 * @param array $pagination_fun HTML arguments for the WP_Query.
	 */
	private static function custom_query( $args, $display_args = array(), $html_args = array(), $pagination_fun = 'pagination' ) {
		$post_type = $args['post_type'] ?? null;
		if ( $post_type ) {

			$args['posts_per_page'] = ( isset( $args['posts_per_page'] ) && -1 === $args['posts_per_page'] ) ? $args['posts_per_page'] : ( ( 'post' === $post_type ) ? get_option( 'posts_per_page' ) : get_option( $post_type . '_per_page' ) );
			$args['paged']          = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
			$pagination             = true;
			if ( $display_args ) {

				list($type,$option,$pagination) = $display_args;

				if ( 'random' === $type ) {
					$args['orderby']        = 'rand';
					$args['order']          = 'DESC';
					$args['posts_per_page'] = $option;
				}
				if ( 'recent' === $type ) {
					$args['orderby']        = 'date';
					$args['order']          = 'DESC';
					$args['posts_per_page'] = $option;
				}
				if ( 'custom' === $type ) {
					$args['post__in'] = $option;
				}
				if ( 'menu_order' === $type ) {
					$args['orderby']        = 'menu_order';
					$args['order']          = 'ASC';
					$args['posts_per_page'] = $option;
				}
			}

			$columns        = 3;
			$classes        = '';
			$number_formate = new \NumberFormatter( 'en', \NumberFormatter::SPELLOUT );
			if ( $html_args ) {
				list($columns,$classes) = $html_args;
			}
			$columns  = $number_formate->format( $columns );
			$classes .= ' ' . $post_type . '-archive ' . $columns . '-columns';
			echo '<div class="' . esc_html( $classes ) . '">';
			$render_fun    = $args['render'] ?? null;
			$template      = $args['template'] ?? null;
			$template_none = $args['template_none'] ?? 'partials/content-none';
			unset( $args['render'] );
			unset( $args['template'] );
			unset( $args['template_none'] );
			// The Query.
			$query = new \WP_Query( $args );

			// The Loop.
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					$sts_var_post_id = get_the_ID();

					list( $sts_var_post_id, $sts_fields, $sts_option_fields ) = StoneStomper::defaults( $sts_var_post_id );
					// Include specific template for the content.
					if ( isset( $template ) ) {
						get_template_part( 'partials/content', $template );
					} else {
						$render_fun( $sts_var_post_id, $sts_fields, $sts_option_fields );
					}
				}
			} else {
				// If no content, include the "No posts found" template.
				get_template_part( 'partials/content', $template_none );
			}
			echo '</div>';
			if ( $query->have_posts() && $query->max_num_pages > 1 && $pagination ) {
				echo '<div class="ts-40"></div><div class="center-align">';
				StoneStomper::$pagination_fun( $query->max_num_pages );
				echo '</div>';
			}
			return $query;
		}
		return null;
	}

	/**
	 * Helper Function
	 *
	 * @param mixed $sts_block_fields block fields data.
	 *
	 * @return array
	 */
	private static function get_spacer( $sts_block_fields ) {
		if ( $sts_block_fields ) {
			foreach ( $sts_block_fields as $sts_block_field ) {
				if ( is_array( $sts_block_field ) ) {
					foreach ( $sts_block_field as $sts_key => $sts_block_sub_field ) {
						if ( 'top_spacer' === $sts_key || 'bottom_spacer' === $sts_key ) {
							return $sts_block_field;
						}
					}
				}
			}
		}
	}
}


class_alias( 'StoneStomper\Boilerplate\WP_Theme_Boilerplate', 'Boilerplate' );
