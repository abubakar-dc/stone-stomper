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
 * Template Class For Acf Block Title Field
 *
 * Template Class
 *
 * @category Acf_Field
 * @package  Base Theme Package
 */
class Acf_Field_Advance_Video extends \acf_field {
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
		/**
		 * Field type reference used in PHP and JS code.
		 *
		 * No spaces. Underscores allowed.
		 */
		$this->name = 'advance_video';

		/**
		 * Field type label.
		 *
		 * For public-facing UI. May contain spaces.
		 */
		$this->label = __( 'Advance Video', 'basetheme_td' );

		/**
		 * The category the field appears within in the field type picker.
		 */
		$this->category = 'basic'; // basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME.
		$this->defaults = array(
			'instructions_title'     => '',
			'instructions_title_tag' => '',
			'is_h1'                  => 'no',
		);
		$this->l10n     = array( 'error' => __( 'Error! Please enter a higher value', 'basetheme_td' ) );

		$this->env = array(
			'url'     => site_url( str_replace( ABSPATH, '', __DIR__ ) ), // URL to the acf-FIELD-NAME directory.
			'version' => '1.0', // Replace this with your theme or plugin version constant.
		);

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
				'ID'                => 1,
				'key'               => 'field_653bfghc20e31',
				'label'             => 'Video Type',
				'name'              => 'video_type',
				'aria-label'        => '',
				'prefix'            => 'acf',
				'type'              => 'button_group',
				'value'             => null,
				'menu_order'        => 0,
				'instructions'      => 'Select Video Type',
				'required'          => '',
				'id'                => '',
				'class'             => '',
				'conditional_logic' => 0,
				'parent'            => '',
				'wrapper'           => array(
					'width' => '25',
					'class' => '',
					'id'    => '',
				),
				'return_format'     => 'value',
				'choices'           => array(
					'default' => 'Default',
					'popup'   => 'Popup',
				),
				'default_value'     => '',
			),
			array(
				'ID'                => 1,
				'key'               => 'field_653bkghc20e31',
				'label'             => 'Thumbnail',
				'name'              => 'thumbnail',
				'aria-label'        => '',
				'prefix'            => 'acf',
				'type'              => 'image',
				'value'             => null,
				'menu_order'        => 0,
				'instructions'      => 'Select Thumbnail',
				'required'          => '',
				'id'                => '',
				'class'             => '',
				'conditional_logic' => 0,
				'parent'            => '',
				'wrapper'           => array(
					'width' => '25',
					'class' => '',
					'id'    => '',
				),
				'return_format'     => 'id',
				'default_value'     => 'default',
				'preview_size'      => 'thumb_100',
			),
			array(
				'ID'                => 2,
				'key'               => 'field_65575c2e20b53',
				'label'             => '',
				'name'              => 'Video',
				'aria-label'        => '',
				'type'              => 'file',
				'instructions'      => 'Select a video file here',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '50',
					'class' => '',
					'id'    => '',
				),
				'return_format'     => 'id',
				'library'           => 'all',
				'min_size'          => '',
				'max_size'          => '',
				'mime_types'        => '.mp4',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_653bfghc20e31',
							'operator' => '==',
							'value'    => 'default',
						),
					),
				),
			),
			array(
				'ID'                => 2,
				'key'               => 'field_615hgea92f6395',
				'label'             => 'Video',
				'name'              => 'popup',
				'aria-label'        => '',
				'prefix'            => 'acf',
				'type'              => 'url',
				'new_lines'         => '',
				'value'             => null,
				'allow_null'        => 1,
				'multiple'          => 0,
				'menu_order'        => 0,
				'instructions'      => 'Add Youtube Code here',
				'required'          => '',
				'id'                => '',
				'class'             => '',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_653bfghc20e31',
							'operator' => '==',
							'value'    => 'popup',
						),
					),
				),
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
}
