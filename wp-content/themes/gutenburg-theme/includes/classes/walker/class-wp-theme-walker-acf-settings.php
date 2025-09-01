<?php
/**
 * Custom acf walker settings.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Base Theme Package
 * @since 1.0.0
 */

namespace BaseTheme\Walker\Settings\ACF;

if ( ! class_exists( 'ACF_Location' ) ) {
	return;
}

use \ACF_Location;
/**
 * Template Class For Walker ACF setting
 *
 * Template Class
 *
 * @category Walker_Class
 * @package  Base Theme Package
 */
class WP_Theme_Walker_Acf_Settings extends \ACF_Location {
	/**
	 * Initialize the basic acf values
	 *
	 * @return void
	 */
	public function initialize() {
		$this->name     = 'menu-item-type';
		$this->label    = __( 'Menu Item Types', '' );
		$this->category = 'forms';
	}

	/**
	 * Starts the list before the elements are added.
	 *
	 * @param array $rule is a array with acf rule values.
	 *
	 * @return array
	 */
	public function get_values( $rule ) {

		$choices = array();

		$choices['mega-menu-1'] = 'Menu Item 1';
		$choices['mega-menu-2'] = 'Menu Item 2';

		return $choices;
	}

	/**
	 * Starts the list before the elements are added.
	 *
	 * @param array $rule is a array with acf rule values.
	 * @param array $screen is a location where the field will show.
	 * @param array $field_group is the group of the field.
	 *
	 * @return array
	 */
	public function match( $rule, $screen, $field_group ) {
		if ( isset( $screen['nav_menu_item'] ) && ( 'mega-menu-1' === $screen['nav_menu_item'] || 'mega-menu-2' === $screen['nav_menu_item'] ) ) {

			$custom_item_type = get_post_meta( $screen['nav_menu_item_id'], '_custom_item_type', true );

			if ( 'menu-item-type' !== $rule['param'] ) {
				return false; }

			if ( ! isset( $custom_item_type ) ) {
				return false; }

			$result = ( $custom_item_type === $rule['value'] );

			if ( '!=' === $rule['operator'] ) {
				return ! $result;
			}

			return $result;
		} else {
			return false;
		}

	}
}
