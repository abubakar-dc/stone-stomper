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
class Acf_Field_Block_Title extends \acf_field {
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
		$this->name = 'block_title';

		/**
		 * Field type label.
		 *
		 * For public-facing UI. May contain spaces.
		 */
		$this->label = __( 'Block Title', 'basetheme_td' );

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
				'label'        => __( 'Instructions (Block Title)', 'basetheme_td' ),
				'instructions' => __( 'Customize the Block Title input instructions', 'basetheme_td' ),
				'type'         => 'text',
				'name'         => 'instructions_title',
				'append'       => '',
				'ui'           => 2,
			)
		);
		acf_render_field_setting(
			$field,
			array(
				'label'        => __( 'Instructions ( HTML Tag)', 'basetheme_td' ),
				'instructions' => __( 'Customize the HTML tag input instructions', 'basetheme_td' ),
				'type'         => 'text',
				'name'         => 'instructions_title_tag',
				'append'       => '',
			)
		);
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

		// To render field settings on other tabs in ACF 6.0+:.
		// https://www.advancedcustomfields.com/resources/adding-custom-settings-fields/#moving-field-setting.
	}
	/**
	 *  Render Fields
	 *
	 *  Create the HTML interface for your field
	 *
	 *  @param object $field - an array holding all the field's data.
	 */
	public function render_field( $field ) {
		$field_key              = $field['key'];
		$field_value            = $field['value'];
		$field_save_name        = $field['name'];
		$is_h1                  = $field['is_h1'];
		$instructions_title     = $field['instructions_title'];
		$instructions_title_tag = $field['instructions_title_tag'];

		$field_title     = $field_value['title'] ?? '';
		$field_title_tag = $field_value['title_tag'] ?? '';

		?>
		<div class="acf-input">
			<div class="acf-clone-fields acf-fields">
				<div class="acf-field acf-field-text -r0" style="width: 70%; min-height: 113px;" data-name="title" data-width="70">
					<div class="acf-label">
						<label for=""><?php esc_html_e( 'Block Title', 'basetheme_td' ); ?></label>
						<?php if ( $instructions_title ) { ?>
							<p class="description"><?php echo html_entity_decode( $instructions_title ); ?></p>
						<?php } ?>
					</div>
					<div class="acf-input">
						<div class="acf-input-wrap"><input type="text" id="" value="<?php echo esc_html( $field_title ); ?>" name="<?php echo esc_html( $field_save_name . '[title]' ); ?>"></div>
					</div>
				</div>
				<div class="acf-field acf-field-headings is-padding -r0" style="width: 30%; min-height: 113px;" data-name="title_tag"  data-width="30">
					<div class="acf-label">
						<label for=""><?php esc_html_e( 'HTML Tag', 'basetheme_td' ); ?></label>
						<?php if ( $instructions_title_tag ) { ?>
							<p class="description"><?php echo html_entity_decode( $instructions_title ); ?></p>
						<?php } ?>
					</div>
					<div class="acf-input">
						<select name="<?php echo esc_html( $field_save_name . '[title_tag]' ); ?>">
						<?php if ( 'yes' === $is_h1 ) { ?>
							<option <?php echo html_entity_decode( ( 'h1' === $field_title_tag ? 'selected="selected"' : '' ) ); ?> value="h1"><?php esc_html_e( 'h1', 'basetheme_td' ); ?></option>
						<?php } ?>
						<option <?php echo html_entity_decode( ( 'h2' === $field_title_tag ) ? 'selected="selected"' : '' ); ?> value="h2"><?php esc_html_e( 'h2', 'basetheme_td' ); ?></option>
						<option <?php echo html_entity_decode( ( 'h3' === $field_title_tag ) ? 'selected="selected"' : '' ); ?> value="h3"><?php esc_html_e( 'h3', 'basetheme_td' ); ?></option>
						<option <?php echo html_entity_decode( ( 'h4' === $field_title_tag ) ? 'selected="selected"' : '' ); ?> value="h4"><?php esc_html_e( 'h4', 'basetheme_td' ); ?></option>
						<option <?php echo html_entity_decode( ( 'h5' === $field_title_tag ) ? 'selected="selected"' : '' ); ?> value="h5"><?php esc_html_e( 'h5', 'basetheme_td' ); ?></option>
						<option <?php echo html_entity_decode( ( 'h6' === $field_title_tag ) ? 'selected="selected"' : '' ); ?> value="h6"><?php esc_html_e( 'h6', 'basetheme_td' ); ?></option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<?php

	}
}
