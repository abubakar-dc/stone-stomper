<?php
/**
 * Custom walker settings.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Base Theme Package
 * @since 1.0.0
 */

namespace BaseTheme\Walker\Settings;

use \Walker_Nav_Menu_Checklist;
/**
 * Template Class For Walker ACF setting
 *
 * Template Class
 *
 * @category Walker_Class
 * @package  Base Theme Package
 */
class WP_Theme_Walker_Settings {
	/**
	 * Define class Constructor
	 **/
	public function __construct() {

		add_action( 'admin_head-nav-menus.php', array( $this, 'register_menu_metabox' ) );
		add_action( 'acf/init', array( $this, 'acf_init_location_types' ) );

		add_filter( 'wp_setup_nav_menu_item', array( $this, 'mitypes_setup_nav_menu_items' ) );
	}

	/**
	 * Register menu meta box
	 *
	 * @return void
	 */
	public function register_menu_metabox() {
		$custom_param = array( 0 => 'This param will be passed to my_render_menu_metabox' );

		add_meta_box( 'mega-menu-metabox', 'Mega Menu', array( $this, 'render_menu_metabox' ), 'nav-menus', 'side', 'default', $custom_param );
	}
	/**
	 * Displays a menu metabox
	 *
	 * @param string $object Not used.
	 * @param array  $args Parameters and arguments. If you passed custom params to add_meta_box(),
	 *  they will be in $args['args'].
	 */
	public function render_menu_metabox( $object, $args ) {
		global $nav_menu_selected_id;

		// Create an array of objects that imitate Post objects.
		$my_items  = array(

			(object) array(
				'ID'               => 1,
				'db_id'            => 0,
				'menu_item_parent' => 0,
				'object_id'        => 1,
				'post_parent'      => 0,
				'type'             => 'mega-menu-1',
				'object'           => 'mega-menu-1',
				'type_label'       => 'Menu Item 1',
				'title'            => 'Menu Item 1',
				'url'              => '',
				'target'           => '',
				'attr_title'       => 'Mega',
				'description'      => 'Mega',
				'classes'          => array(),
				'xfn'              => '',
			),
			(object) array(
				'ID'               => 2,
				'db_id'            => 0,
				'menu_item_parent' => 0,
				'object_id'        => 1,
				'post_parent'      => 0,
				'type'             => 'mega-menu-2',
				'object'           => 'mega-menu-2',
				'type_label'       => 'Menu Item 2',
				'title'            => 'Menu Item 2',
				'url'              => '',
				'target'           => '',
				'attr_title'       => 'Mega',
				'description'      => 'Mega',
				'classes'          => array(),
				'xfn'              => '',
			),
		);
		$db_fields = false;
		// If your links will be hierarchical, adjust the $db_fields array bellow.

		$db_fields = array(
			'parent' => 'parent',
			'id'     => 'post_parent',
		);

		$walker = new \Walker_Nav_Menu_Checklist( $db_fields );

		$removed_args = array(
			'action',
			'customlink-tab',
			'edit-menu-item',
			'menu-item',
			'page-tab',
			'_wpnonce',
		); ?>
		<div id="my-plugin-div">
			<div id="tabs-panel-my-plugin-all" class="tabs-panel tabs-panel-active">
			<ul id="my-plugin-checklist-pop" class="categorychecklist form-no-clear" >
				<?php echo walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $my_items ), 0, (object) array( 'walker' => $walker ) ); ?>
			</ul>

			<p class="button-controls">
				<span class="add-to-menu">
					<input type="submit"<?php wp_nav_menu_disabled_check( $nav_menu_selected_id ); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to Menu' ); ?>" name="add-my-plugin-menu-item" id="submit-my-plugin-div" />
					<span class="spinner"></span>
				</span>
			</p>
		</div>
		<?php
	}

	/**
	 * Change the meme type of the menu items.
	 *
	 * @param object $menu_item is a object of menu item.
	 *
	 * @return object
	 */
	public function mitypes_setup_nav_menu_items( $menu_item ) {
		if ( 'mega-menu-1' === $menu_item->type ) {
			update_post_meta( $menu_item->ID, '_custom_item_type', 'mega-menu-1' );
			$menu_item->type_label = 'Menu Item 1';
		}
		if ( 'mega-menu-2' === $menu_item->type ) {
			update_post_meta( $menu_item->ID, '_custom_item_type', 'mega-menu-2' );
			$menu_item->type_label = 'Menu Item 2';
		}
		return $menu_item;
	}
	/**
	 * Init WP_Theme_Walker_Acf_Settings Class.
	 *
	 * @return void
	 */
	public function acf_init_location_types() {

		// Check function exists, then include and register the custom location type class.
		if ( function_exists( 'acf_register_location_type' ) ) {
			acf_register_location_type( '\BaseTheme\Walker\Settings\ACF\WP_Theme_Walker_Acf_Settings' );
			if ( is_multisite() ) {
				acf_register_location_type( '\BaseTheme\Walker\Settings\ACF\WP_Theme_Acf_Multisite_Support' );
			}
		}
	}
}
new WP_Theme_Walker_Settings();
