<?php
/**
 * CPT related functions
 *
 * @link
 *
 * @package Base Theme Package
 * @since 1.0.0
 */

namespace BaseTheme\CPT;

/**
 * Template Class For CPT
 *
 * Template Class
 *
 * @category Setting_Class
 * @package  Base Theme Package
 */
class WP_Theme_CPT {

	/**
	 * CPT singular String.
	 *
	 *  @var string $cpt_singular_capital CPT singular String.
	 */
	private $cpt_singular_capital = null;
	/**
	 * CPT plural_ String.
	 *
	 *  @var string $cpt_plural_capital CPT plural_ String.
	 */
	private $cpt_plural_capital = null;
	/**
	 * CPT singular String.
	 *
	 *  @var string $cpt_singular_lowercase CPT singular String.
	 */
	private $cpt_singular_lowercase = null;
	/**
	 * CPT plural String.
	 *
	 *  @var string $cpt_plural_lowercase CPT plural String.
	 */
	private $cpt_plural_lowercase = null;

	/**
	 * CPT register key String.
	 *
	 *  @var string $cpt_register_key CPT register key String.
	 */
	private $cpt_register_key = null;
	/**
	 * CPT slug String.
	 *
	 *  @var string $cpt_slug CPT slug String.
	 */
	private $cpt_slug = null;
	/**
	 * CPT Support
	 *
	 *  @var string $supports CPT slug String.
	 */
	private $supports = null;
	/**
	 * CPT menu icon
	 *
	 *  @var string $menu_icon CPT menu icon
	 */
	private $menu_icon = null;
	/**
	 * CPT is public or not
	 *
	 *  @var string $public CPT is public or not
	 */
	private $public = true;
	/**
	 * CPT option to show in menu
	 *
	 *  @var string $show_in_menu CPT option to show in menu
	 */
	private $show_in_menu = true;

	/**
	 * CPT option to show in menu
	 *
	 *  @var string $show_ui CPT option to show in menu
	 */
	private $show_ui = true;

	/**
	 * CPT array of terms to register
	 *
	 *  @var array $terms CPT array of terms to register
	 */
	private $taxonomies = array();


	/**
	 * Define class Constructor
	 *
	 * @param array $args constructor arguments.
	 **/
	public function __construct( $args ) {

		$this->cpt_singular_capital   = $args['labels']['singular_capital'];
		$this->cpt_plural_capital     = $args['labels']['plural_capital'];
		$this->cpt_singular_lowercase = $args['labels']['singular_lowercase'];
		$this->cpt_plural_lowercase   = $args['labels']['plural_lowercase'];
		$this->cpt_register_key       = $args['labels']['register_key'];
		$this->cpt_slug               = $args['labels']['slug'];

		$this->supports     = ( isset( $args['supports'] ) ) ? $args['supports'] : true;
		$this->menu_icon    = ( isset( $args['menu_icon'] ) ) ? $args['menu_icon'] : 'dashicons-groups';
		$this->public       = ( isset( $args['public'] ) ) ? $args['public'] : true;
		$this->show_in_menu = ( isset( $args['show_in_menu'] ) ) ? $args['show_in_menu'] : true;
		$this->show_ui      = ( isset( $args['show_ui'] ) ) ? $args['show_ui'] : true;
		$this->taxonomies   = ( isset( $args['taxonomies'] ) ) ? $args['taxonomies'] : array();

		add_action( 'init', array( $this, 'register_cpt_testimonials' ) );
		add_action( 'init', array( $this, 'register_term' ), 0 );
		add_action( 'admin_init', array( $this, 'admin_init_fields' ) );
	}
	/**
	 * Register custom cpt Testimonials
	 *
	 *  @return void
	 */
	public function register_cpt_testimonials() {
		// CPT Labels.
		$cpt_singular_capital   = $this->cpt_singular_capital; // Name of the post type shown in the menu.
		$cpt_plural_capital     = $this->cpt_plural_capital;
		$cpt_singular_lowercase = $this->cpt_singular_lowercase;
		$cpt_plural_lowercase   = $this->cpt_plural_lowercase;

		// CPT Slug & Name.
		$cpt_register_key = $this->cpt_register_key;  // This is the registering name of the single CPT post. (Try to keep it singular).
		$cpt_slug         = $this->cpt_slug;  // This is the permalink slug of single CPT post. (Try to keep it singular).
		// The slug will become - www.website.com/testimonial/single-testimonial-name.

		$labels = array(
			'name'                  => sprintf( /* translators: %s. */ _x( '%s', 'Post type general name', 'basetheme_td' ), $cpt_plural_capital ),
			'singular_name'         => sprintf( /* translators: %s. */ _x( '%s', 'Post type singular name', 'basetheme_td' ), $cpt_singular_capital ),
			'add_new'               => sprintf( /* translators: %s. */ _x( 'Add New %s', 'basetheme_td' ), $cpt_singular_capital ),
			'add_new_item'          => sprintf( /* translators: %s. */ _x( 'Add New %s', 'basetheme_td' ), $cpt_singular_capital ),
			'new_item'              => sprintf( /* translators: %s. */ _x( 'New %s', 'basetheme_td' ), $cpt_singular_capital ),
			'edit_item'             => sprintf( /* translators: %s. */ _x( 'Edit %s', 'basetheme_td' ), $cpt_singular_capital ),
			'update_item'           => sprintf( /* translators: %s. */ _x( 'Update %s', 'basetheme_td' ), $cpt_singular_capital ),
			'view_item'             => sprintf( /* translators: %s. */ _x( 'View  %s', 'basetheme_td' ), $cpt_singular_capital ),
			'view_items'            => sprintf( /* translators: %s. */ _x( 'View  %s', 'basetheme_td' ), $cpt_plural_capital ),
			'all_items'             => sprintf( /* translators: %s. */ _x( 'All %s', 'basetheme_td' ), $cpt_plural_capital ),
			'search_items'          => sprintf( /* translators: %s. */ _x( 'Search %s', 'basetheme_td' ), $cpt_plural_capital ),
			'parent_item_colon'     => sprintf( /* translators: %s. */ _x( 'Parent: %s', 'basetheme_td' ), $cpt_singular_capital ),
			'not_found'             => sprintf( /* translators: %s. */ _x( 'No %s found.', 'basetheme_td' ), $cpt_plural_lowercase ),
			'not_found_in_trash'    => sprintf( /* translators: %s. */ _x( 'No %s found in Trash.', 'basetheme_td' ), $cpt_plural_lowercase ),
			'featured_image'        => sprintf( /* translators: %s. */ _x( '%s Featured Image', 'Overrides the “Featured Image” phrase.', 'basetheme_td' ), $cpt_singular_capital ),
			'set_featured_image'    => sprintf( /* translators: %s. */ _x( 'Set featured image', 'Overrides the “Set featured image” phrase.', 'basetheme_td' ) ),
			'remove_featured_image' => sprintf( /* translators: %s. */ _x( 'Remove %s image', 'Overrides the “Remove featured image” phrase.', 'basetheme_td' ), $cpt_singular_lowercase ),
			'use_featured_image'    => sprintf( /* translators: %s. */ _x( 'Use as %s image', 'Overrides the “Use as featured image” phrase.', 'basetheme_td' ), $cpt_singular_lowercase ),
			'archives'              => sprintf( /* translators: %s. */ _x( '%s archives', 'The post type archive label used in nav menus.', 'basetheme_td' ), $cpt_singular_capital ),
			'attributes'            => sprintf( /* translators: %s. */ _x( '%s attributes', 'The post type attributes label.', 'basetheme_td' ), $cpt_singular_capital ),
			'insert_into_item'      => sprintf( /* translators: %s. */ _x( 'Insert into %s', 'Overrides the “Insert into post” phrase.', 'basetheme_td' ), $cpt_singular_lowercase ),
			'uploaded_to_this_item' => sprintf( /* translators: %s. */ _x( 'Uploaded to this %s', 'Overrides the “Uploaded to this post” phrase.', 'basetheme_td' ), $cpt_singular_lowercase ),
			'filter_items_list'     => sprintf( /* translators: %s. */ _x( 'Filter %s list', 'Screen reader text for the filter links.', 'basetheme_td' ), $cpt_plural_lowercase ),
			'items_list_navigation' => sprintf( /* translators: %s. */ _x( '%s list navigation', 'Screen reader text for the pagination.', 'basetheme_td' ), $cpt_plural_capital ),
			'items_list'            => sprintf( /* translators: %s. */ _x( '%s list', 'Screen reader text for the items list.', 'basetheme_td' ), $cpt_plural_capital ),
		);

		$args = array(
			'labels'             => apply_filters( 'bst_cpt_labels', $labels ),
			'public'             => $this->public,
			'publicly_queryable' => $this->public,
			'show_ui'            => $this->show_ui,
			'show_in_menu'       => $this->show_in_menu,
			'query_var'          => true,
			'menu_position'      => null,
			'map_meta_cap'       => true,
			'show_in_rest'       => true,
			'supports'           => $this->supports,
			'capability_type'    => 'page', // Set this value for each CPT.
			'has_archive'        => false, // Set this value for each CPT.
			'hierarchical'       => true, // Set this value for each CPT.
			'menu_icon'          => $this->menu_icon, // Set this value for each CPT.
			'rewrite'            => array(
				'slug'       => $cpt_slug,
				'with_front' => true, // If required only then set this value for each CPT.
			),
		);
		register_post_type( $cpt_register_key, $args );
	}

	/**
	 * Register Term
	 */
	public function register_term() {
		if ( $this->taxonomies ) {
			foreach ( $this->taxonomies as $taxonomy ) {
				// CPT Slug & Name.
				$tax_parent       = $this->cpt_register_key; // This is registering name of respective CPT.
				$tax_register_key = ( isset( $taxonomy['register_key'] ) ) ? $taxonomy['register_key'] : $taxonomy['slug'];  // This is the registering name of the taxonomy (Try to keep it plural).
				$tax_slug         = $taxonomy['slug']; // This is the permalink slug of taxonomy archive (Try to keep it plural).
				// The slug will become - www.website.com/testimonials/single-testimonial-category.

				$include_cpt = $taxonomy['include_cpt'] ?? null;
				$tax_public  = $taxonomy['public'] ?? true;

				$labels = array(
					'name'                       => sprintf( /* translators: %s. */ _x( '%s', 'Taxonomy General Name', 'basetheme_td' ), $taxonomy['name'] ),
					'singular_name'              => sprintf( /* translators: %s. */ _x( '%s', 'Taxonomy Singular Name', 'basetheme_td' ), $taxonomy['singular_name'] ),
					'menu_name'                  => sprintf( /* translators: %s. */ _x( '%s', 'Taxonomy Menu Name', 'basetheme_td' ), $taxonomy['plural_name'] ),
					'all_items'                  => sprintf( /* translators: %s. */ _x( 'All %s', 'Taxonomy Menu Name', 'basetheme_td' ), $taxonomy['plural_name'] ),
					'parent_item'                => sprintf( /* translators: %s. */ _x( 'Parent %s', 'basetheme_td' ), $taxonomy['singular_name'] ),
					'parent_item_colon'          => sprintf( /* translators: %s. */ _x( 'Parent %s:', 'basetheme_td' ), $taxonomy['singular_name'] ),
					'new_item_name'              => sprintf( /* translators: %s. */ _x( 'New %s Name', 'basetheme_td' ), $taxonomy['singular_name'] ),
					'add_new_item'               => sprintf( /* translators: %s. */ _x( 'Add New %s', 'basetheme_td' ), $taxonomy['singular_name'] ),
					'edit_item'                  => sprintf( /* translators: %s. */ _x( 'Edit %s', 'basetheme_td' ), $taxonomy['singular_name'] ),
					'update_item'                => sprintf( /* translators: %s. */ _x( 'Update %s', 'basetheme_td' ), $taxonomy['singular_name'] ),
					'view_item'                  => sprintf( /* translators: %s. */ _x( 'View %s', 'basetheme_td' ), $taxonomy['singular_name'] ),
					'separate_items_with_commas' => sprintf( /* translators: %s. */ _x( 'Separate %s with commas', 'basetheme_td' ), $taxonomy['plural_name'] ),
					'add_or_remove_items'        => sprintf( /* translators: %s. */ _x( 'Add or remove %s', 'basetheme_td' ), $taxonomy['plural_name'] ),
					'choose_from_most_used'      => _x( 'Choose from the most used', 'basetheme_td' ),
					'popular_items'              => sprintf( /* translators: %s. */ _x( 'Popular %s', 'basetheme_td' ), $taxonomy['plural_name'] ),
					'search_items'               => sprintf( /* translators: %s. */ _x( 'Search %s', 'basetheme_td' ), $taxonomy['plural_name'] ),
					'not_found'                  => _x( 'Not Found', 'basetheme_td' ),
					'no_terms'                   => sprintf( /* translators: %s. */ _x( 'No %s', 'basetheme_td' ), $taxonomy['plural_name'] ),
					'items_list'                 => sprintf( /* translators: %s. */ _x( '%s list', 'basetheme_td' ), $taxonomy['plural_name'] ),
					'items_list_navigation'      => sprintf( /* translators: %s. */ _x( '%s list navigation', 'basetheme_td' ), $taxonomy['plural_name'] ),
				);

				$args = array(
					'labels'            => apply_filters( 'bst_term_labels', $labels ),
					'hierarchical'      => true,
					'public'            => $tax_public,
					'show_ui'           => true,
					'show_in_rest'      => true,
					'show_admin_column' => true,
					'show_in_nav_menus' => true,
					'query_var'         => true,
					'rewrite'           => array(
						'slug'       => $tax_slug,
						'with_front' => false, // If required only then set this for each taxonomy.
					),
				);
				if ( $include_cpt ) {
					register_taxonomy( $tax_register_key, array( $tax_parent, $include_cpt ), $args );

					add_filter(
						$tax_slug . '_row_actions',
						function ( $actions, $tag ) {
							// Customize the "View" link as needed.
							$view_url        = get_term_link( $tag );
							$post_type       = ( isset( $_GET['post_type'] ) ) ? $_GET['post_type'] : '';
							$actions['view'] = '<a href="' . esc_url( $view_url ) . '?post-type=' . $post_type . '" target="_blank">View</a>';

							return $actions;
						},
						10,
						2
					);
				} else {
					register_taxonomy( $tax_register_key, array( $tax_parent ), $args );
				}
			}
		}

	}
	/**
	 * Fix labels.
	 */
	public function admin_init_fields() {

		// register our setting.
		$args = array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => null,
		);
		register_setting(
			'reading', // option group "reading", default WP group.
			$this->cpt_register_key . '_per_page', // option name.
			$args
		);

		// add our new setting.
		add_settings_field(
			$this->cpt_register_key . '_per_page', // ID.
			"{$this->cpt_singular_capital} - post per page", // Title.
			array( $this, 'setting_callback_function' ), // Callback.
			'reading', // page.
			'default', // section.
			array( 'label_for' => $this->cpt_register_key . '_per_page' )
		);
		$per_page = get_option( $this->cpt_register_key . '_per_page' );
		if ( ! $per_page ) {
			update_option( $this->cpt_register_key . '_per_page', 10 );
		}
	}
	/**
	 * Fix labels.
	 */
	public function setting_callback_function() {
		// get saved project page ID.
		$per_page = get_option( $this->cpt_register_key . '_per_page' );
		echo html_entity_decode( '<input name="' . $this->cpt_register_key . '_per_page" type="number" step="1" min="1" id="' . $this->cpt_register_key . '_per_page" value="' . $per_page . '" class="small-text"> ' . $this->cpt_register_key );
	}
}


