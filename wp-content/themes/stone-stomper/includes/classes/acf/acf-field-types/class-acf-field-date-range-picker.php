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
 * Template Class For Acf Block Title Field
 *
 * Template Class
 *
 * @category Acf_Field
 * @package  Stone Stomper
 */
class Acf_Field_Date_Range_Picker extends \acf_field {
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
		$this->name = 'date_range_picker';

		/**
		 * Field type label.
		 *
		 * For public-facing UI. May contain spaces.
		 */
		$this->label = __( 'Date Range Picker', 'basetheme_td' );

		/**
		 * The category the field appears within in the field type picker.
		 */
		$this->category = 'advanced'; // basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME.
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
	/**\
	 * Admin Scripts
	 */
	public function input_admin_enqueue_scripts() {
		\StoneStomper::enqueue_scripts( array( 'assets/build/vendors/moment.min.js', 'assets/build/vendors/daterangepicker.min.js' ) );
	}



	/**
	 *  Render Fields
	 *
	 *  Create the HTML interface for your field
	 *
	 *  @param object $field - an array holding all the field's data.
	 */
	public function render_field( $field ) {

		$text_input = array(
			'id'    => $field['id'],
			'name'  => $field['name'],
			'class' => $field['class'] . ' input',
			'value' => $field['value'],
		);
		?>
		<div>
			<?php acf_text_input( $text_input ); ?>
		</div>
		<script>
			jQuery(function() {
				jQuery('#<?php echo esc_html( $field['id'] ); ?>').daterangepicker({
					opens: 'left',
					minYear: 2024,
					locale: {
						format: 'MMMM D, YYYY'
					}
				});
			});
		</script>
			<?php

	}
}
