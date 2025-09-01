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
class Acf_Field_Advance_Form extends \acf_field {
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
		$this->name = 'advance_form';

		/**
		 * Field type label.
		 *
		 * For public-facing UI. May contain spaces.
		 */
		$this->label = __( 'Advance Form', 'basetheme_td' );

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
	 *  Render Fields
	 *
	 *  Create the HTML interface for your field
	 *
	 *  @param object $field - an array holding all the field's data.
	 */
	public function render_field( $field ) {
		$field_key       = $field['key'];
		$field_value     = $field['value'];
		$field_save_name = $field['name'];
		$forms           = \GFAPI::get_forms();

		$field_form_provider = $field_value['form_provider'] ?? 'gform';
		$field_gform         = $field_value['gform'] ?? '';
		$field_hubspot       = $field_value['hubspot'] ?? '';
		?>
		<div class="acf-input">
			<div class="acf-clone-fields acf-fields">
				<div class="acf-field acf-field-button-group -r0" style="width: 40%;" data-name="option" data-width="40">
					<div class="acf-label">
						<label for=""><?php esc_html_e( 'Form Provider', 'basetheme_td' ); ?></label>
						<p class="description"><?php esc_html_e( 'Select From Provider', 'basetheme_td' ); ?></p>
					</div>
					<div class="acf-input">

						<div class="acf-button-group">
						<label <?php echo ( 'gform' === $field_form_provider ) ? 'class="selected"' : ''; ?>><input type="radio" name="<?php echo esc_html( $field_save_name . '[form_provider]' ); ?>" value="gform" checked="checked"> <?php esc_html_e( 'Gravity Form', 'basetheme_td' ); ?></label>
						<label <?php echo ( 'hubspot' === $field_form_provider ) ? 'class="selected"' : ''; ?>><input type="radio" name="<?php echo esc_html( $field_save_name . '[form_provider]' ); ?>" value="hubspot"> <?php esc_html_e( 'Hubspot Form', 'basetheme_td' ); ?></label></div>
					</div>
				</div>
				<div class="acf-field acf-field-inner acf-field-forms -r0" style="width: 60%;<?php echo ( 'gform' === $field_form_provider ) ? '' : 'display:none;'; ?>" data-name="gform" data-width="60">
					<div class="acf-label">
						<label for=""><?php esc_html_e( 'Gravity Form', 'basetheme_td' ); ?></label>
						<p class="description"><?php esc_html_e( 'Select gravity form', 'basetheme_td' ); ?></p>
					</div>
					<div class="acf-input">
						<select id="" name="<?php echo esc_html( $field_save_name . '[gform]' ); ?>">
						<option value=""><?php esc_html_e( '- Select a form -', 'basetheme_td' ); ?></option>
						<?php
						foreach ( $forms as $form ) {
							echo html_entity_decode( '<option ' . ( (int) $field_gform === $form['id'] ? 'selected="selected"' : '' ) . ' value="' . $form['id'] . '">' . $form['title'] . '</option>' );
						}
						?>
						</select>
					</div>
				</div>
				<div class="acf-field acf-field-inner acf-field-textarea -r0" style="width: 60%;<?php echo ( 'hubspot' === $field_form_provider ) ? '' : 'display:none;'; ?>" data-name="hubspot" data-width="60">
					<div class="acf-label">
						<label for=""><?php esc_html_e( 'Hubspot Form', 'basetheme_td' ); ?></label>
						<p class="description"><?php esc_html_e( 'Add your hubspot code here', 'basetheme_td' ); ?></p>
					</div>
					<div class="acf-input">
						<textarea id="" name="<?php echo esc_html( $field_save_name . '[hubspot]' ); ?>" rows="8"><?php echo esc_html( $field_hubspot ); ?></textarea>
					</div>
				</div>
			</div>
			</div>

		<script>
			jQuery(document).ready(function(){
				jQuery('.acf-<?php echo esc_html( str_replace( '_', '-', $field_key ) ); ?>').find( '.acf-button-group label' ).off( 'click' ).on( 'click', function() {
					const value = jQuery( this ).find( 'input[type="radio"]' ).attr( 'value' );
					jQuery('.acf-<?php echo esc_html( str_replace( '_', '-', $field_key ) ); ?>').find( '.acf-field-inner' ).hide();
					jQuery('.acf-<?php echo esc_html( str_replace( '_', '-', $field_key ) ); ?>').find( '.acf-field-inner[data-name="' + value + '"]' ).show();
				} );
			});
		</script>
		<?php

	}


}
