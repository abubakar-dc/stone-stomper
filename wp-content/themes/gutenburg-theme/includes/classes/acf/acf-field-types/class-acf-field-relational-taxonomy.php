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
class Acf_Field_Relational_Taxonomy extends \acf_field {

	/**
	 * Vars
	 *
	 * @var $save_post_terms variable for post terms.
	 */
	public $save_post_terms = array();


	/**
	 *  __construct
	 *
	 *  This function will setup the field type data
	 *
	 *  @type    function
	 *  @date    5/03/2014
	 *  @since   5.0.0
	 */
	public function initialize() {

		// vars.
		$this->name          = 'relational-taxonomy';
		$this->label         = __( 'Relational Taxonomy', 'acf' );
		$this->category      = 'relational';
		$this->description   = __( 'Allows the selection of one or more taxonomy terms based on the criteria and options specified in the fields settings.', 'acf' );
		$this->preview_image = acf_get_url() . '/assets/images/field-type-previews/field-preview-taxonomy.png';
		$this->doc_url       = acf_add_url_utm_tags( 'https://www.advancedcustomfields.com/resources/taxonomy/', 'docs', 'field-type-selection' );
		$this->defaults      = array(
			'taxonomy'   => 'category',
			'post_type'  => 'post',
			'field_type' => 'checkbox',
			'multiple'   => 0,
			'allow_null' => 0,
		);

		// Register filter variations.
		acf_add_filter_variations( 'acf/fields/relational-taxonomy/query', array( 'name', 'key' ), 1 );
		acf_add_filter_variations( 'acf/fields/relational-taxonomy/result', array( 'name', 'key' ), 2 );

		// ajax.
		add_action( 'wp_ajax_acf/fields/relational-taxonomy/query', array( $this, 'ajax_query' ) );
		add_action( 'wp_ajax_nopriv_acf/fields/relational-taxonomy/query', array( $this, 'ajax_query' ) );

	}


	/**
	 *  Ajax_query
	 *
	 *  Description.
	 *
	 *  @type    function
	 *  @date    24/10/13
	 *  @since   5.0.0
	 */
	public function ajax_query() {

		// validate.
		if ( ! acf_verify_ajax() ) {
			die();
		}

		// get choices.
		// @codingStandardsIgnoreStart
		$response = $this->get_ajax_query( $_POST );
		// @codingStandardsIgnoreEnd
		// return.
		acf_send_ajax_results( $response );

	}


	/**
	 *  Get_ajax_query.
	 *
	 *  This function will return an array of data formatted for use in a select2 AJAX response.
	 *
	 *  @type    function
	 *  @date    15/10/2014
	 *  @since   5.0.9
	 *
	 *  @param array $options (array).
	 *  @return  (array)
	 */
	public function get_ajax_query( $options = array() ) {

		// defaults.
		$options = acf_parse_args(
			$options,
			array(
				'post_id'   => 0,
				's'         => '',
				'field_key' => '',
				'paged'     => 0,
			)
		);

		// load field.
		$field = acf_get_field( $options['field_key'] );
		if ( ! $field ) {
			return false;
		}

		// bail early if taxonomy does not exist.
		if ( ! taxonomy_exists( $field['taxonomy'] ) ) {
			return false;
		}

		// vars.
		$results         = array();
		$is_hierarchical = is_taxonomy_hierarchical( $field['taxonomy'] );
		$is_pagination   = ( $options['paged'] > 0 );
		$is_search       = false;
		$limit           = 20;
		$offset          = 20 * ( $options['paged'] - 1 );

		// args.
		$args = array(
			'taxonomy'   => $field['taxonomy'],
			'hide_empty' => false,
		);

		// pagination
		// - don't bother for hierarchial terms, we will need to load all terms anyway.
		if ( $is_pagination && ! $is_hierarchical ) {

			$args['number'] = $limit;
			$args['offset'] = $offset;

		}

		// search.
		if ( '' !== $options['s'] ) {

			// strip slashes (search may be integer).
			$s = wp_unslash( strval( $options['s'] ) );

			// update vars.
			$args['search'] = $s;
			$is_search      = true;

		}

		// filters.
		// @codingStandardsIgnoreStart
		$args = apply_filters( 'acf/fields/taxonomy/query', $args, $field, $options['post_id'] );
		// @codingStandardsIgnoreEnd
		// get terms.
		$terms = acf_get_terms( $args );

		// sort into hierachial order!.

			// update vars.
		$limit       = acf_maybe_get( $args, 'number', $limit );
			$options = array();
		$post_type   = $field['post_type'];
		$args        = array(
			'post_type'      => $post_type,
			'posts_per_page' => -1,
			'post_status'    => array( 'publish' ),
			'orderby'        => 'date',
			'order'          => 'DESC',
		);
		$query       = new \WP_Query( $args );
		// The Loop.
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$attorneys_testimonials = get_the_terms( get_the_ID(), 'attorneys_testimonials' );
				$slug                   = basename( get_permalink() );
				if ( $attorneys_testimonials ) {
					foreach ( $attorneys_testimonials as $attorneys_testimonial ) {

						$options[ $slug . ',' . $attorneys_testimonial->slug ] = get_the_title() . '( ' . $attorneys_testimonial->name . ' )';
					}
				}
			}
		}
		// vars.
		$response = array(
			'results' => $options,
			'limit'   => $limit,
		);

		// return.
		return $response;

	}


	/**
	 *  Get_terms.
	 *
	 *  This function will return an array of terms for a given field value.
	 *
	 *  @type    function
	 *  @date    13/06/2014
	 *  @since   5.0.0
	 *
	 * @param array  $value (array).
	 * @param string $taxonomy (taxonomy).
	 *
	 *  @return  $value
	 */
	public function get_terms( $value, $taxonomy = 'category' ) {

		// load terms in 1 query to save multiple DB calls from following code.
		if ( count( $value ) > 1 ) {

			$terms = acf_get_terms(
				array(
					'taxonomy'   => $taxonomy,
					'include'    => $value,
					'hide_empty' => false,
				)
			);

		}

		// update value to include $post.
		foreach ( array_keys( $value ) as $i ) {

			$value[ $i ] = get_term( $value[ $i ], $taxonomy );

		}

		// filter out null values.
		$value = array_filter( $value );

		// return.
		return $value;
	}


	/**
	 *  Update_value().
	 *
	 *  This filter is appied to the $value before it is updated in the db.
	 *
	 *  @type    filter
	 *  @since   3.6
	 *  @date    23/01/13
	 *
	 *  @param string $value - the value which will be saved in the database.
	 *  @param int    $post_id of which the value will be saved.
	 *  @param array  $field - the field array holding all the field options.
	 *
	 *  @return  $value - the modified value
	 */
	public function update_value( $value, $post_id, $field ) {

		// vars.
		if ( is_array( $value ) ) {

			$value = array_filter( $value );

		}

		// save_terms.
		if ( $field['save_terms'] ) {

			// vars.
			$taxonomy = $field['taxonomy'];

			// force value to array.
			$term_ids = $value;

			// get existing term id's (from a previously saved field).
			$old_term_ids = isset( $this->save_post_terms[ $taxonomy ] ) ? $this->save_post_terms[ $taxonomy ] : array();

			// append.
			$this->save_post_terms[ $taxonomy ] = array_merge( $old_term_ids, $term_ids );

			// if called directly from frontend update_field().
			if ( ! did_action( 'acf/save_post' ) ) {

				$this->save_post( $bst_var_post_id );

				return $value;

			}
		}

		// return.
		return $value;

	}




	/**
	 *  Render_field()
	 *
	 *  Create the HTML interface for your field
	 *
	 *  @type    action
	 *  @since   3.6
	 *  @date    23/01/13
	 *
	 *  @param array $field - an array holding all the field's data.
	 */
	public function render_field( $field ) {

		// force value to array.
		$field['value'] = acf_get_array( $field['value'] );

		// vars.
		$div = array(
			'class'           => 'acf-field acf-field-select',
			'data-save'       => '',
			'data-type'       => 'select',
			'data-allow_null' => $field['allow_null'],
			'data-key'        => $field['key'],
			'data-name'       => $field['label'],
		);

		// get taxonomy.
		$taxonomy  = get_taxonomy( $field['taxonomy'] );
		$post_type = get_post_type( $field['taxonomy'] );

		// bail early if taxonomy does not exist.
		if ( ! $taxonomy ) {
			return;
		}

		?>
		<div <?php echo esc_html( acf_esc_attrs( $div ) ); ?> >

		<?php

		if ( 'select' === $field['field_type'] ) {

			$field['multiple'] = 0;

			$this->render_field_select( $field );

		} elseif ( 'multi_select' === $field['field_type'] ) {

			$field['multiple'] = 1;

			$this->render_field_select( $field );

		} elseif ( 'radio' === $field['field_type'] ) {

			$this->render_field_checkbox( $field );

		} elseif ( 'checkbox' === $field['field_type'] ) {

			$this->render_field_checkbox( $field );

		}

		?>
</div>
		<?php

	}


	/**
	 *  Render_field_select().
	 *
	 *  Create the HTML interface for your field.
	 *
	 *  @type    action
	 *  @since   3.6
	 *  @date    23/01/13
	 *
	 *  @param array $field - an array holding all the field's data.
	 */
	public function render_field_select( $field ) {

		// Change Field into a select.
		$field['type']    = 'select';
		$field['ui']      = 1;
		$field['ajax']    = 0;
		$field['choices'] = array();

		// value.
		$options   = array();
		$post_type = $field['post_type'];
		$args      = array(
			'post_type'      => $post_type,
			'posts_per_page' => -1,
			'post_status'    => array( 'publish' ),
			'orderby'        => 'date',
			'order'          => 'DESC',
		);
		$query     = new \WP_Query( $args );
		// The Loop.
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$attorneys_testimonials = get_the_terms( get_the_ID(), 'attorneys_testimonials' );
				$slug                   = basename( get_permalink() );
				if ( $attorneys_testimonials ) {
					foreach ( $attorneys_testimonials as $attorneys_testimonial ) {

						$field['choices'][ $slug . ',' . $attorneys_testimonial->slug ] = get_the_title() . '( ' . $attorneys_testimonial->name . ' )';
					}
				}
			}
		}
		// render select.
		acf_render_field( $field );

	}


	/**
	 *  Create the HTML interface for your field
	 *
	 *  @since   3.6
	 *
	 *  @param array $field an array holding all the field's data.
	 */
	public function render_field_checkbox( $field ) {

		// hidden input.
		acf_hidden_input(
			array(
				'type' => 'hidden',
				'name' => $field['name'],
			)
		);
		// checkbox saves an array.
		if ( 'checkbox' === $field['field_type'] ) {
			$field['name'] .= '[]';
		}
		$options   = array();
		$post_type = $field['post_type'];
		$args      = array(
			'post_type'      => $post_type,
			'posts_per_page' => -1,
			'post_status'    => array( 'publish' ),
			'orderby'        => 'date',
			'order'          => 'DESC',
		);
		$query     = new \WP_Query( $args );
		// The Loop.
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$attorneys_testimonials = get_the_terms( get_the_ID(), 'attorneys_testimonials' );
				$slug                   = basename( get_permalink() );
				if ( $attorneys_testimonials ) {
					foreach ( $attorneys_testimonials as $attorneys_testimonial ) {

						$options[ $slug . ',' . $attorneys_testimonial->slug ] = get_the_title() . '( ' . $attorneys_testimonial->name . ' )';
					}
				}
			}
		}

		?>
			<div class="categorychecklist-holder">
				<ul class="acf-checkbox-list acf-bl">
					<?php foreach ( $options as $key => $option ) { ?>
						<li data-id="<?php echo esc_html( $key ); ?>">
							<label>
								<input type="<?php echo esc_html( $field['field_type'] ); ?>" <?php echo ( in_array( $key, $field['value'], true ) ) ? 'checked="checked"' : ''; ?> name="<?php echo esc_html( $field['name'] ); ?>" value="<?php echo esc_html( $key ); ?>">
								<span><?php echo esc_html( $option ); ?></span>
							</label>
						</li>
						<?php } ?>
				</ul>

			</div>
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
				'label'        => __( 'Taxonomy', 'acf' ),
				'instructions' => __( 'Select the taxonomy to be displayed', 'acf' ),
				'type'         => 'select',
				'name'         => 'taxonomy',
				'choices'      => acf_get_taxonomy_labels(),
			)
		);

		acf_render_field_setting(
			$field,
			array(
				'label'        => __( 'Post Type', 'acf' ),
				'instructions' => __( 'Select the post to be displayed', 'acf' ),
				'type'         => 'select',
				'name'         => 'post_type',
				'choices'      => get_post_types(),
			)
		);

		acf_render_field_setting(
			$field,
			array(
				'label'        => __( 'Appearance', 'acf' ),
				'instructions' => __( 'Select the appearance of this field', 'acf' ),
				'type'         => 'select',
				'name'         => 'field_type',
				'optgroup'     => true,
				'choices'      => array(
					__( 'Multiple Values', 'acf' ) => array(
						'checkbox'     => __( 'Checkbox', 'acf' ),
						'multi_select' => __( 'Multi Select', 'acf' ),
					),
					__( 'Single Value', 'acf' )    => array(
						'radio'  => __( 'Radio Buttons', 'acf' ),
						'select' => _x( 'Select', 'noun', 'acf' ),
					),
				),
			)
		);

		acf_render_field_setting(
			$field,
			array(
				'label'        => __( 'Allow Null', 'acf' ),
				'instructions' => '',
				'name'         => 'allow_null',
				'type'         => 'true_false',
				'ui'           => 1,
				'conditions'   => array(
					'field'    => 'field_type',
					'operator' => '!=',
					'value'    => 'checkbox',
				),
			)
		);
	}





}
?>
