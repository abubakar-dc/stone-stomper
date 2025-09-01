<?php
/**
 * Functions for advanced custom fields plugin
 *
 * @link https://www.advancedcustomfields.com/resources/
 *
 * @package Base Theme Package
 * @since 1.0.0
 */

namespace BaseTheme\Acf\Acf_Fields;

/**
 * Template Class For Acf Block Field Reusable
 *
 * Template Class
 *
 * @category Acf_Field
 * @package  Base Theme Package
 */
class Acf_Field_Spacers extends \acf_field {
	/**
	 * Controls field type visibility in REST requests.
	 *
	 * @var bool
	 */
	public $show_in_rest = true;

	/**
	 * Environment values relating to the theme or plugin.
	 *
	 * @var array $env Plugin or theme context such as 'url' and 'version'.
	 */
	private $env;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->name      = 'spacers';
		$this->label     = _x( 'Spacers', 'noun', 'acf' );
		$this->category  = 'layout';
		$this->pro       = true;
		$this->defaults  = array(
			'clone'               => '',
			'prefix_label'        => 0,
			'prefix_name'         => 0,
			'instructions_top'    => '',
			'instructions_bottom' => '',
		);
		$this->cloning   = array();
		$this->have_rows = 'single';

		add_filter( 'acf/prepare_field', array( $this, 'hide_label_prepare_field' ) );
		parent::__construct();
	}



	/**
	 *  Load Fields.
	 *
	 *  This filter is appied to the $field after it is loaded from the database
	 *
	 *  @param object $field - the field array holding all the field options.
	 *
	 *  @return  $field - the field array holding all the field options
	 */
	public function load_field( $field ) {

		$name       = $field['name'];
		$sub_fields = array(
			array(
				'ID'                => 3,
				'key'               => 'field_6458ea92f6396',
				'label'             => 'Margin From Top',
				'name'              => 'top_spacer',
				'aria-label'        => '',
				'prefix'            => 'acf',
				'type'              => 'spacer',
				'value'             => null,
				'menu_order'        => 0,
				'instructions'      => ( $field['instructions_top'] ) ? $field['instructions_top'] : '',
				'required'          => '',
				'id'                => '',
				'class'             => '',
				'conditional_logic' => 0,
				'parent'            => '',
				'wrapper'           => array(
					'width' => '50',
					'class' => '',
					'id'    => '',
				),
				'default_value'     => '',
			),
			array(
				'ID'                => 4,
				'key'               => 'field_6458ea92f6397',
				'label'             => 'Margin From Bottom',
				'name'              => 'bottom_spacer',
				'aria-label'        => '',
				'prefix'            => 'acf',
				'type'              => 'spacer',
				'value'             => null,
				'menu_order'        => 0,
				'instructions'      => ( $field['instructions_bottom'] ) ? $field['instructions_bottom'] : '',
				'required'          => '',
				'id'                => '',
				'class'             => '',
				'conditional_logic' => 0,
				'parent'            => '',
				'wrapper'           => array(
					'width' => '50',
					'class' => '',
					'id'    => '',
				),
				'default_value'     => '',
			),
		);
		// load sub fields.
		$field['sub_fields'] = $sub_fields;

		// return.
		return $field;

	}


	/**
	 *  Load Value.
	 *
	 *  This filter is applied to the $value after it is loaded from the db
	 *
	 *  @param object                  $value (mixed) the value found in the database.
	 *  @param int post_id (mixed) the $post_id from which the value was loaded.
	 *  @param object                  $field (array) the field array holding all the field options.
	 *  @return  $value
	 */
	public function load_value( $value, $post_id, $field ) {

		// bail early if no sub fields.
		if ( empty( $field['sub_fields'] ) ) {
			return $value;
		}

		// load sub fields.
		$value = array();

		// loop.
		foreach ( $field['sub_fields'] as $sub_field ) {
			if ( 'field_label' === $sub_field['name'] ) {
				continue;
			}
			// add value.
			$value[ $sub_field['key'] ] = acf_get_value( $post_id, $sub_field );

		}

		// return.
		return $value;

	}


	/**
	 * Formate Value.
	 *
	 *  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
	 *
	 *  @param object                  $value (mixed) the value found in the database.
	 *  @param int post_id (mixed) the $post_id from which the value was loaded.
	 *  @param object                  $field (array) the field array holding all the field options.
	 *
	 *  @return  $value (mixed) the modified value
	 */
	public function format_value( $value, $post_id, $field ) {

		// bail early if no value.
		if ( empty( $value ) ) {
			return false;
		}

		// loop.
		foreach ( $field['sub_fields'] as $sub_field ) {
			if ( 'field_label' === $sub_field['name'] ) {
				continue;
			}
			// extract value.
			$sub_value = acf_extract_var( $value, $sub_field['key'] );

			// format value.
			$sub_value = acf_format_value( $sub_value, $post_id, $sub_field );

			// append to $row.
			$value[ $sub_field['name'] ] = $sub_value;

		}

		// return.
		return $value;

	}
	/**
	 * Render Acf Field
	 *
	 *  @param array $field is a array of theme categories.
	 *
	 *  @return void
	 */
	public function render_field_settings( $field ) {
		/*
		 * Repeat for each setting you wish to display for this field type.
		 */
		acf_render_field_setting(
			$field,
			array(
				'label'        => __( 'Instructions (Margin From Top)', 'basetheme_td' ),
				'instructions' => __( 'Customize the Margin From Top input instructions', 'basetheme_td' ),
				'type'         => 'text',
				'name'         => 'instructions_top',
				'append'       => '',
				'ui'           => 2,
			)
		);
		acf_render_field_setting(
			$field,
			array(
				'label'        => __( 'Instructions ( Margin From Bottom)', 'basetheme_td' ),
				'instructions' => __( 'Customize the Margin From Bottom input instructions', 'basetheme_td' ),
				'type'         => 'text',
				'name'         => 'instructions_bottom',
				'append'       => '',
			)
		);

		// To render field settings on other tabs in ACF 6.0+:.
		// https://www.advancedcustomfields.com/resources/adding-custom-settings-fields/#moving-field-setting.
	}


	/**
	 *  Update Value.
	 *
	 *  This filter is appied to the $value before it is updated in the db
	 *
	 *  @param object                  $value (mixed) the value found in the database.
	 *  @param int post_id (mixed) the $post_id from which the value was loaded.
	 *  @param object                  $field (array) the field array holding all the field options.
	 *
	 *  @return  $value - the modified value
	 */
	public function update_value( $value, $post_id, $field ) {
		// bail early if no value.
		if ( ! acf_is_array( $value ) ) {
			return null;
		}

		// bail early if no sub fields.
		if ( empty( $field['sub_fields'] ) ) {
			return null;
		}

		// loop.
		foreach ( $field['sub_fields'] as $sub_field ) {
			// vars.
			if ( 'field_label' === $sub_field['name'] ) {
				continue;
			}
			$v = false;

			$v = $value[ $sub_field['key'] ];
			// update value.
			acf_update_value( $v, $post_id, $sub_field );

		}

		// return.
		return '';

	}


	/**
	 *  Render Fields
	 *
	 *  Create the HTML interface for your field
	 *
	 *  @param object $field - an array holding all the field's data.
	 */
	public function render_field( $field ) {

		// bail early if no sub fields.
		if ( empty( $field['sub_fields'] ) ) {
			return;
		}

		// load values.
		foreach ( $field['sub_fields'] as &$sub_field ) {

			// add value.
			if ( isset( $field['value'][ $sub_field['key'] ] ) ) {

				// this is a normal value.
				$sub_field['value'] = $field['value'][ $sub_field['key'] ];

			} elseif ( isset( $sub_field['default_value'] ) ) {

				// no value, but this sub field has a default value.
				$sub_field['value'] = $sub_field['default_value'];

			}

			// update prefix to allow for nested values.
			$sub_field['prefix'] = $field['name'];

			// restore label.
			$sub_field['label'] = $sub_field['label'];

			// restore required.
			if ( $field['required'] ) {
				$sub_field['required'] = 0;
			}
		}

		$this->render_field_block( $field );

	}


	/**
	 *  Render Field Block.
	 *
	 *  @param object $field - an array holding all the field's data.
	 */
	public function render_field_block( $field ) {

		echo '<div class="acf-clone-fields acf-fields">';

		foreach ( $field['sub_fields'] as $sub_field ) {

			acf_render_field_wrap( $sub_field );

		}

		echo '</div>';

	}

	/**
	 *  Hide label.
	 *
	 *  @param object $field - an array holding all the field's data.
	 */
	public function hide_label_prepare_field( $field ) {
		if ( 'reusable' === $field['type'] ) {

			echo '
			<style type="text/css">
			.acf-field-', esc_html( substr( $field['key'], 6 ) ),' > .acf-label {display: none;}
			</style>';
		}
		return $field;
	}


}
