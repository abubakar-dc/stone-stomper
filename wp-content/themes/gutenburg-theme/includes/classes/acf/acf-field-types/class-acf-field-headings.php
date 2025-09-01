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
 * Template Class For Acf Block Field Headings
 *
 * Template Class
 *
 * @category Acf_Field
 * @package  Base Theme Package
 */
class Acf_Field_Headings extends \acf_field {
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
		$this->name = 'headings';

		/**
		 * Field type label.
		 *
		 * For public-facing UI. May contain spaces.
		 */
		$this->label = __( 'Headings', 'basetheme_td' );

		/**
		 * The category the field appears within in the field type picker.
		 */
		$this->category = 'basic'; // basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME.

		/**
		 * Defaults for your custom user-facing settings for this field type.
		 */
		$this->defaults = array( 'font_size' => 14 );

		/**
		 * Strings used in JavaScript code.
		 *
		 * Allows JS strings to be translated in PHP and loaded in JS via:
		 *
		 * ```js
		 * const errorMessage = acf._e("headings", "error");
		 * ```
		 */
		$this->l10n = array( 'error' => __( 'Error! Please enter a higher value', 'basetheme_td' ) );

		$this->env = array(
			'url'     => site_url( str_replace( ABSPATH, '', __DIR__ ) ), // URL to the acf-FIELD-NAME directory.
			'version' => '1.0', // Replace this with your theme or plugin version constant.
		);

		parent::__construct();
	}


	/**
	 * HTML content to show when a publisher edits the field on the edit screen.
	 *
	 * @param array $field The field settings and values.
	 * @return void
	 */
	public function render_field( $field ) {
		?>
		<select name="<?php echo esc_attr( $field['name'] ); ?>">
			<?php if ( isset( $field['is_h1'] ) && 'yes' === $field['is_h1'] ) { ?>
					<option <?php echo ( esc_attr( $field['value'] ) === 'h1' ) ? 'selected' : ''; ?>value="h1">h1</option>
			<?php } ?>
			<option <?php echo ( esc_attr( $field['value'] ) === 'h2' ) ? 'selected' : ''; ?> value="h2">h2</option>
			<option <?php echo ( esc_attr( $field['value'] ) === 'h3' ) ? 'selected' : ''; ?> value="h3">h3</option>
			<option <?php echo ( esc_attr( $field['value'] ) === 'h4' ) ? 'selected' : ''; ?> value="h4">h4</option>
			<option <?php echo ( esc_attr( $field['value'] ) === 'h5' ) ? 'selected' : ''; ?> value="h5">h5</option>
			<option <?php echo ( esc_attr( $field['value'] ) === 'h6' ) ? 'selected' : ''; ?> value="h6">h6</option>
		</select>
		<?php
	}

	/**
	 *  Render_field_settings().
	 *
	 *  Create extra options for your field. This is rendered when editing a field.
	 *  The value of $field['name'] can be used (like bellow) to save extra data to the $field
	 *
	 *  @type    action
	 *  @since   3.6
	 *  @date    23/01/13
	 *
	 *  @param array $field  - an array holding all the field's data.
	 */
	public function render_field_settings( $field ) {
		acf_render_field_setting(
			$field,
			array(
				'label'        => __( 'Include H1', 'acf' ),
				'instructions' => __( 'Select the include h1', 'acf' ),
				'type'         => 'select',
				'name'         => 'is_h1',
				'choices'      => array(
					'no'  => 'NO',
					'yes' => 'YES',
				),
			)
		);

	}

}
