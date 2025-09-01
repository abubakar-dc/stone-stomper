<?php
/**
 * Functions for advanced custom fields plugin
 *
 * @link https://www.advancedcustomfields.com/resources/
 *
 * @package Stone Stomper
 * @since 1.0.0
 */

namespace StoneStomper\Acf\Acf_Fields;

/**
 * Template Class For Acf Block Field Headings
 *
 * Template Class
 *
 * @category Acf_Field
 * @package  Stone Stomper
 */
class Acf_Field_Spacer extends \acf_field {
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
		$this->name = 'spacer';

		/**
		 * Field type label.
		 *
		 * For public-facing UI. May contain spaces.
		 */
		$this->label = __( 'Spacer', 'basetheme_td' );

		/**
		 * The category the field appears within in the field type picker.
		 */
		$this->category = 'basic'; // basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME.

		/**
		 * Defaults for your custom user-facing settings for this field type.
		 */
		$this->defaults = array( 'default_value' => '' );

		/**
		 * Strings used in JavaScript code.
		 *
		 * Allows JS strings to be translated in PHP and loaded in JS via:
		 *
		 * ```js
		 * const errorMessage = acf._e("spacer", "error");
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
				'label'        => __( 'Default Value', 'basetheme_td' ),
				'instructions' => __( 'Customize the input default Value', 'basetheme_td' ),
				'type'         => 'text',
				'name'         => 'default_value',
				'append'       => '',
			)
		);

		// To render field settings on other tabs in ACF 6.0+:
		// https://www.advancedcustomfields.com/resources/adding-custom-settings-fields/#moving-field-setting.
	}

	/**
	 * HTML content to show when a publisher edits the field on the edit screen.
	 *
	 * @param array $field The field settings and values.
	 * @return void
	 */
	public function render_field( $field ) {
		// Debug output to show what field data is available.
		$option_html = '';

		$options = array(
			's-0'    => 'No Margin',
			'gl-s20'  => '20 Pixels',
			'gl-s30'  => '30 Pixels',
			'gl-s36'  => '36 Pixels',
			'gl-s48' => '48 Pixels',
			'gl-s72' => '72 Pixels',
			'gl-s96' => '96 Pixels',
			'gl-s128' => '128 Pixels',
			'gl-s156' => '156 Pixels',
			'gl-s200' => '200 Pixels',
			'gl-s236' => '236 Pixels',
		);

		if ( $options ) {
			foreach ( $options as $key => $option ) {
				if ( esc_attr( $field['value'] ) === $key ) {
					$selected = 'selected';
				} else {
					$selected = '';
				}
				$option_html .= '<option ' . $selected . ' value="' . $key . '">' . $option . '</option>';
			}
			?>
		<select name="<?php echo esc_attr( $field['name'] ); ?>">
			<?php echo html_entity_decode( $option_html ); ?>
		</select>
			<?php
		} else {
			echo 'please set default spacer values';
		}
	}


}
