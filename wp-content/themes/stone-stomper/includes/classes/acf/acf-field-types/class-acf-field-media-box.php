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
class Acf_Media_Box extends \acf_field {
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
		$this->name = 'media-box';

		/**
		 * Field type label.
		 *
		 * For public-facing UI. May contain spaces.
		 */
		$this->label = __( 'Media Box', 'basetheme_td' );

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
	 * Get video data by id;
	 *
	 * @param number $attachment_id video id.
	 */
	public function get_video_details_by_id( $attachment_id ) {

		$attachment_url = wp_get_attachment_url( $attachment_id );

		$attachment_metadata = wp_get_attachment_metadata( $attachment_id );
		$attachment_post     = get_post( $attachment_id );
		$file_path           = get_attached_file( $attachment_id );
		$file_size_bytes     = filesize( $file_path );
		$human_readable_size = size_format( $file_size_bytes );
		$file_name           = basename( $file_path );
		$media_icon          = wp_mime_type_icon( $attachment_id );

		return array(
			'url'        => $attachment_url,
			'file_name'  => $file_name,
			'file_label' => $attachment_post->post_title,
			'file_size'  => $human_readable_size,
			'media_icon' => $media_icon,
		);
	}
	/**\
	 * Admin Scripts
	 */
	public function input_admin_enqueue_scripts() {
		\StoneStomper::enqueue_scripts( array( 'assets/build/vendors/media-box.js' ) );
	}


	/**
	 * HTML content to show when a publisher edits the field on the edit screen.
	 *
	 * @param array $field The field settings and values.
	 * @return void
	 */
	public function render_field( $field ) {
		$field_id        = $field['id'];
		$field_key       = $field['key'];
		$field_name      = $field['_name'];
		$field_save_name = $field['name'];
		$field_label     = $field['label'];
		$field_value     = $field['value'];

		$field_media_option     = $field_value['media_option'] ?? 'image';
		$field_media_image      = $field_value['media_image'] ?? '';
		$field_media_video      = $field_value['media_video'] ?? '';
		$field_media_video_data = array();
		if ( $field_media_video ) {
			$field_media_video_data = $this->get_video_details_by_id( $field_media_video );
		}
		?>
		<div class="acf-fields -top -border">
			<div class="acf-field acf-field-button-group -r0" style="width: 50%; min-height: 92px;" data-name="media_option" data-type="button_group" data-key="" data-width="50">
				<div class="acf-label">
				<label for=""><?php esc_html_e( 'Media Option', 'basetheme_td' ); ?></label>
				</div>
				<div class="acf-input">
					<div class="acf-button-group">
						<label <?php echo ( 'image' === $field_media_option ) ? 'class="selected"' : ''; ?>><input type="radio" name="<?php echo esc_html( $field_save_name . '[media_option]' ); ?>" value="image"  <?php echo ( 'image' === $field_media_option ) ? 'checked="checked"' : ''; ?>>Image</label>
						<label <?php echo ( 'video' === $field_media_option ) ? 'class="selected"' : ''; ?>><input type="radio" name="<?php echo esc_html( $field_save_name . '[media_option]' ); ?>" value="video" <?php echo ( 'video' === $field_media_option ) ? 'checked="checked"' : ''; ?>> Video</label>
					</div>
				</div>
			</div>
			<div class="acf-field acf-field-inner acf-field-image -r0" style="width: 50%; min-height: 92px;<?php echo ( 'image' === $field_media_option ) ? '' : 'display:none;'; ?>" data-name="image" data-width="50">
				<div class="acf-label">
					<label for=""><?php esc_html_e( 'Image', 'basetheme_td' ); ?></label>
				</div>
				<div class="acf-input">
					<div class="acf-image-uploader <?php echo ( '' !== $field_media_image ) ? 'has-value' : ''; ?>">
						<input type="hidden" name="<?php echo esc_html( $field_save_name . '[media_image]' ); ?>" value="<?php echo esc_html( $field_media_image ); ?>">
						<div class="image-wrap <?php echo ( '' !== $field_media_image ) ? '' : 'show-if-value'; ?>" style="max-width: 300px">
							<img src="<?php echo esc_url( wp_get_attachment_image_url( $field_media_image ) ); ?>" alt="" data-name="image" style="max-height: 300px;">
							<div class="acf-actions -hover">
								<a class="acf-icon -pencil dark" data-name="edit" href="#" title="Edit"></a>
								<a class="acf-icon -cancel dark" data-name="remove" href="#" title="Remove"></a>
							</div>
						</div>
						<div class="hide-if-value">
							<p><?php esc_html_e( 'No image selected ', 'basetheme_td' ); ?> <a data-name="add" class="acf-button button" href="#"><?php esc_html_e( 'Add Image', 'basetheme_td' ); ?></a></p>
						</div>
					</div>
				</div>
			</div>

			<div class="acf-field acf-field-inner acf-field-file -r0" style="width:50%;<?php echo ( 'video' === $field_media_option ) ? '' : 'display:none;'; ?>" data-name="video" data-width="50">
				<div class="acf-label">
				<label for=""><?php esc_html_e( 'Video', 'basetheme_td' ); ?></label>
				</div>
				<div class="acf-input">
				<div class="acf-file-uploader <?php echo ( '' !== $field_media_video ) ? 'has-value' : ''; ?>">
					<input type="hidden" name="<?php echo esc_html( $field_save_name . '[media_video]' ); ?>" value="<?php echo esc_html( $field_media_video ); ?>">
					<div class="show-if-value file-wrap <?php echo ( '' !== $field_media_video ) ? '' : 'show-if-value'; ?>">
						<div class="file-icon">
							<img data-name="icon" src="<?php echo esc_url( $field_media_video_data['media_icon'] ?? '' ); ?>" alt="">
						</div>
						<div class="file-info">
							<p>
							<strong data-name="title"><?php echo esc_html( $field_media_video_data['file_label'] ?? '' ); ?></strong>
							</p>
							<p>
							<strong><?php esc_html_e( 'File name:', 'basetheme_td' ); ?></strong>
							<a data-name="filename" href="<?php echo esc_url( $field_media_video_data['url'] ?? '' ); ?>" target="_blank"><?php echo esc_html( $field_media_video_data['file_name'] ?? '' ); ?></a>
							</p>
							<p>
							<strong><?php esc_html_e( 'File size:', 'basetheme_td' ); ?></strong>
							<span data-name="filesize"><?php echo esc_html( $field_media_video_data['file_size'] ?? '' ); ?></span>
							</p>
						</div>
						<div class="acf-actions -hover">
							<a class="acf-icon -pencil dark" data-name="edit" href="#" title="Edit"></a>
							<a class="acf-icon -cancel dark" data-name="remove" href="#" title="Remove"></a>
						</div>
					</div>
					<div class="hide-if-value">
						<p><?php esc_html_e( 'No video selected', 'basetheme_td' ); ?> <a data-name="add" class="acf-button button" href="#"><?php esc_html_e( 'Add Video', 'basetheme_td' ); ?></a></p>
					</div>
				</div>
				</div>
			</div>
		</div>
		<script>
			jQuery(document).ready(function(){
				jQuery('.acf-<?php echo esc_html( str_replace( '_', '-', $field_key ) ); ?>').mediaBox();
			});
		</script>
		<?php
	}

}
