<?php
namespace ControlPatterns;
use ControlPatterns\Helpers\Array_Type as Helpers_Array;
use ControlPatterns\Helpers\String_Type as Helpers_String;
use ControlPatterns\Helpers\Field_Type as Helpers_Field;
use ControlPatterns\Helpers\Value_Type as Helpers_Value;

/**
 * The field base class.
 * This is the parent class of all custom fields defined by the plugin, which defines all the common methods.
 * Fields must inherit this class and overwrite methods with its own.
 *
 * @package ControlPatterns
 */

/**
 * The field base class.
 */
abstract class Field {
	/**
	 * Add actions.
	 */
	public static function add_actions() {
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public static function admin_enqueue_scripts() {

	}

	/**
	 * Show field HTML
	 * Filters are put inside this method, not inside methods such as "meta", "html", "begin_html", etc.
	 * That ensures the returned value are always been applied filters.
	 * This method is not meant to be overwritten in specific fields.
	 *
	 * @param array $field   Field parameters.
	 * @param bool  $saved   Whether the meta box is saved at least once.
	 * @param int   $post_id Post ID.
	 */
	public static function show( $field, $saved, $post_id = 0 ) {
		$meta = self::call( $field, 'meta', $post_id, $saved );
		$meta = self::filter( 'field_meta', $meta, $field, $saved );

		$begin = self::call( $field, 'begin_html', $meta );
		$begin = self::filter( 'begin_html', $begin, $field, $meta );

		// Separate code for cloneable and non-cloneable fields to make easy to maintain.
		if ( $field['clone'] ) {
			$field_html = Cloner::html( $meta, $field );
		} else {
			// Call separated methods for displaying each type of field.
			$field_html = self::call( $field, 'html', $meta );
			$field_html = self::filter( 'html', $field_html, $field, $meta );
		}

		$end = self::call( $field, 'end_html', $meta );
		$end = self::filter( 'end_html', $end, $field, $meta );

		$html = self::filter( 'wrapper_html', "$begin$field_html$end", $field, $meta );

		// Display label and input in DIV and allow user-defined classes to be appended.
		$classes = "ctrlbp-field ctrlbp-row ctrlbp-{$field['type']}-wrapper " . $field['class'];
		if ( ! empty( $field['required'] ) ) {
			$classes .= ' required';
		}

		$classes .= ( $field['name'] != '' )? ' ctrlbp-field-has-label' : ' ctrlbp-field-no-label';

		$outer_html = sprintf(
			$field['before'] . '<div class="%s">%s</div>' . $field['after'],
			esc_attr( trim( $classes ) ),
			$html
		);
		$outer_html = self::filter( 'outer_html', $outer_html, $field, $meta );

		echo $outer_html; // WPCS: XSS OK.
	}

	/**
	 * Get field HTML.
	 *
	 * @param mixed $meta  Meta value.
	 * @param array $field Field parameters.
	 *
	 * @return string
	 */
	public static function html( $meta, $field ) {
		return '';
	}

	/**
	 * Show begin HTML markup for fields.
	 *
	 * @param mixed $meta  Meta value.
	 * @param array $field Field parameters.
	 *
	 * @return string
	 */
	public static function begin_html( $meta, $field ) {
		$field_label = '';
		if ( $field['name'] ) {
			$field_label = sprintf(
				'<div class="ctrlbp-label ctrlbp-col-12 ctrlbp-col-md-3">
					<label for="%s">%s%s</label>
					%s
				</div>',
				esc_attr( $field['id'] ),
				$field['name'],
				$field['required'] || ! empty( $field['attributes']['required'] ) ? '<span class="ctrlbp-required">*</span>' : '',
				self::label_description( $field )
			);
		}

		$data_max_clone = is_numeric( $field['max_clone'] ) && $field['max_clone'] > 1 ? ' data-max-clone=' . $field['max_clone'] : '';

		$input_open = sprintf(
			'<div class="ctrlbp-input ctrlbp-col-12 ctrlbp-col-md-12"%s>',
			$data_max_clone
		);

		return $field_label . $input_open;
	}

	/**
	 * Show end HTML markup for fields.
	 *
	 * @param mixed $meta  Meta value.
	 * @param array $field Field parameters.
	 *
	 * @return string
	 */
	public static function end_html( $meta, $field ) {
		return Cloner::add_clone_button( $field ) . self::call( 'input_description', $field ) . '</div>';
	}

	/**
	 * Display field label description.
	 *
	 * @param array $field Field parameters.
	 * @return string
	 */
	protected static function label_description( $field ) {
		$id = $field['id'] ? ' id="' . esc_attr( $field['id'] ) . '-label-description"' : '';
		return $field['label_description'] ? "<p{$id} class='description'>{$field['label_description']}</p>" : '';
	}

	/**
	 * Display field description.
	 *
	 * @param array $field Field parameters.
	 * @return string
	 */
	protected static function input_description( $field ) {
		$id = $field['id'] ? ' id="' . esc_attr( $field['id'] ) . '-description"' : '';
		return $field['desc'] ? "<p{$id} class='description'>{$field['desc']}</p>" : '';
	}

	/**
	 * Get raw meta value.
	 *
	 * @param int   $object_id Object ID.
	 * @param array $field     Field parameters.
	 * @param array $args      Arguments of {@see ctrlbp_meta()} helper.
	 *
	 * @return mixed
	 */
	public static function raw_meta( $object_id, $field, $args = array() ) {
		if ( empty( $field['id'] ) ) {
			return '';
		}

		if ( isset( $field['storage'] ) ) {
			$storage = $field['storage'];
		} elseif ( isset( $args['object_type'] ) ) {
			$storage = self::get_storage( $args['object_type'] );
		} else {
			$storage = self::get_storage( 'post' );
		}

		if ( ! isset( $args['single'] ) ) {
			$args['single'] = $field['clone'] || ! $field['multiple'];
		}

		if ( $field['clone'] && $field['clone_as_multiple'] ) {
			$args['single'] = false;
		}

		$value = $storage->get( $object_id, $field['id'], $args );
		$value = self::filter( 'raw_meta', $value, $field, $object_id, $args );
		return $value;
	}

	public static function get_storage( $object_type, $meta_box = null ) {
		$object_type = Helpers_String::title_case( $object_type );
		$class   = $object_type . '_Storage';
		$class   = __NAMESPACE__."\\". $object_type . '_Storage';
		$class   = class_exists( $class ) ? $class : 'ControlPatterns\Storages\Post';
		$storage = ctrlbp_get_registry( 'storage' )->get( $class );

		return apply_filters( 'ctrlbp_get_storage', $storage, $object_type, $meta_box );
	}

	

	/**
	 * Get meta value.
	 *
	 * @param int   $post_id Post ID.
	 * @param bool  $saved   Whether the meta box is saved at least once.
	 * @param array $field   Field parameters.
	 *
	 * @return mixed
	 */
	public static function meta( $post_id, $saved, $field ) {
		/**
		 * For special fields like 'divider', 'heading' which don't have ID, just return empty string
		 * to prevent notice error when displaying fields.
		 */
		if ( empty( $field['id'] ) ) {
			return '';
		}

		// Get raw meta.
		$meta = self::call( $field, 'raw_meta', $post_id );

		// Use $field['std'] only when the meta box hasn't been saved (i.e. the first time we run).
		$meta = ! $saved || ! $field['save_field'] ? $field['std'] : $meta;

		if ( $field['clone'] ) {
			$meta = Helpers_Array::ensure( $meta );
			

			// Ensure $meta is an array with values so that the foreach loop in self::show() runs properly.
			if ( empty( $meta ) ) {
				$meta = array( '' );
			}

			if ( $field['multiple'] ) {
				$first = reset( $meta );

				// If users set std for a cloneable checkbox list field in the Builder, they can only set [value1, value2]. We need to transform it to [[value1, value2]].
				// In other cases, make sure each value is an array.
				$meta = is_array( $first ) ? array_map( 'ControlPatterns\Helpers\Array_Type::ensure', $meta ) : array( $meta );
			}
		} elseif ( $field['multiple'] ) {
			$meta = Helpers_Array::ensure( $meta );
		}

		return $meta;
	}

	/**
	 * Process the submitted value before saving into the database.
	 *
	 * @param mixed $value     The submitted value.
	 * @param int   $object_id The object ID.
	 * @param array $field     The field settings.
	 */
	public static function process_value( $value, $object_id, $field ) {
		$old_value = self::call( $field, 'raw_meta', $object_id );

		// Allow field class change the value.
		if ( $field['clone'] ) {
			$value = Cloner::value( $value, $old_value, $object_id, $field );
		} else {
			$value = self::call( $field, 'value', $value, $old_value, $object_id );
			$value = self::filter( 'sanitize', $value, $field, $old_value, $object_id );
		}
		$value = self::filter( 'value', $value, $field, $old_value, $object_id );

		return $value;
	}

	/**
	 * Set value of meta before saving into database.
	 *
	 * @param mixed $new     The submitted meta value.
	 * @param mixed $old     The existing meta value.
	 * @param int   $post_id The post ID.
	 * @param array $field   The field parameters.
	 *
	 * @return mixed
	 */
	public static function value( $new, $old, $post_id, $field ) {
		return $new;
	}

	/**
	 * Save meta value.
	 *
	 * @param mixed $new     The submitted meta value.
	 * @param mixed $old     The existing meta value.
	 * @param int   $post_id The post ID.
	 * @param array $field   The field parameters.
	 */
	public static function save( $new, $old, $post_id, $field ) {
		if ( empty( $field['id'] ) || ! $field['save_field'] ) {
			return;
		}
		$name    = $field['id'];
		$storage = $field['storage'];

		// Remove post meta if it's empty.
		if ( ! Helpers_Value::is_valid_for_field( $new ) ) {
			$storage->delete( $post_id, $name );
			return;
		}

		// If field is cloneable AND not force to save as multiple rows, value is saved as a single row in the database.
		if ( $field['clone'] && ! $field['clone_as_multiple'] ) {
			$storage->update( $post_id, $name, $new );
			return;
		}

		// Save cloned fields as multiple values instead serialized array.
		if ( ( $field['clone'] && $field['clone_as_multiple'] ) || $field['multiple'] ) {
			$storage->delete( $post_id, $name );
			$new = (array) $new;
			foreach ( $new as $new_value ) {
				$storage->add( $post_id, $name, $new_value, false );
			}
			return;
		}

		// Default: just update post meta.
		$storage->update( $post_id, $name, $new );
	}

	/**
	 * Normalize parameters for field.
	 *
	 * @param array|string $field Field settings.
	 * @return array
	 */
	public static function normalize( $field ) {
		// Quick define text fields with "name" attribute only.
		if ( is_string( $field ) ) {
			$field = array(
				'name' => $field,
				'id'   => sanitize_key( $field ),
			);
		}
		$field = wp_parse_args(
			$field,
			array(
				'id'                => '',
				'name'              => '',
				'label'              => '',
				'type'              => 'text',
				'label_description' => '',
				'multiple'          => false,
				'std'               => '',
				'desc'              => '',
				'format'            => '',
				'before'            => '',
				'after'             => '',
				'field_name'        => isset( $field['id'] ) ? $field['id'] : '',
				'placeholder'       => '',
				'save_field'        => true,

				'clone'             => false,
				'max_clone'         => 0,
				'sort_clone'        => false,
				'add_button'        => __( '+ Add more', 'control-block-patterns' ),
				'clone_default'     => false,
				'clone_as_multiple' => false,

				'class'             => '',
				'disabled'          => false,
				'required'          => false,
				'autofocus'         => false,
				'attributes'        => array(),

				'sanitize_callback' => null,
			)
		);

		// Store the original ID to run correct filters for the clonable field.
		if ( $field['clone'] ) {
			$field['_original_id'] = $field['id'];
		}

		if ( $field['clone_default'] ) {
			$field['attributes'] = wp_parse_args(
				$field['attributes'],
				array(
					'data-default'       => $field['std'],
					'data-clone-default' => 'true',
				)
			);
		}

		if ( 1 === $field['max_clone'] ) {
			$field['clone'] = false;
		}

		return $field;
	}

	/**
	 * Get the attributes for a field.
	 *
	 * @param array $field Field parameters.
	 * @param mixed $value Meta value.
	 *
	 * @return array
	 */
	public static function get_attributes( $field, $value = null ) {
		$attributes = wp_parse_args(
			$field['attributes'],
			array(
				'disabled'  => $field['disabled'],
				'autofocus' => $field['autofocus'],
				'required'  => $field['required'],
				'id'        => $field['id'],
				'class'     => '',
				'name'      => $field['field_name'],
			)
		);

		$attributes['class'] = trim( implode( ' ', array_merge( array( "ctrlbp-{$field['type']}" ), (array) $attributes['class'] ) ) );

		return $attributes;
	}

	/**
	 * Renders an attribute array into an html attributes string.
	 *
	 * @param array $attributes HTML attributes.
	 *
	 * @return string
	 */
	public static function render_attributes( $attributes ) {
		$output = '';

		

		$attributes = array_filter( $attributes, __NAMESPACE__.'\\Helpers\\Value_Type::is_valid_for_attribute' );
		foreach ( $attributes as $key => $value ) {
			if ( is_array( $value ) ) {
				$value = \wp_json_encode( $value );
			}

			$output .= sprintf( ' %s="%s"', $key, esc_attr( $value ) );
		}

		return $output;
	}

	

	/**
	 * Get the field value.
	 * The difference between this function and 'meta' function is 'meta' function always returns the escaped value
	 * of the field saved in the database, while this function returns more meaningful value of the field, for ex.:
	 * for file/image: return array of file/image information instead of file/image IDs.
	 *
	 * Each field can extend this function and add more data to the returned value.
	 * See specific field classes for details.
	 *
	 * @param  array    $field   Field parameters.
	 * @param  array    $args    Additional arguments. Rarely used. See specific fields for details.
	 * @param  int|null $post_id Post ID. null for current post. Optional.
	 *
	 * @return mixed Field value
	 */
	public static function get_value( $field, $args = array(), $post_id = null ) {
		// Some fields does not have ID like heading, custom HTML, etc.
		if ( empty( $field['id'] ) ) {
			return '';
		}

		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		// Get raw meta value in the database, no escape.
		$value = self::call( $field, 'raw_meta', $post_id, $args );

		// Make sure meta value is an array for cloneable and multiple fields.
		if ( $field['clone'] || $field['multiple'] ) {
			$value = is_array( $value ) && $value ? $value : array();
		}

		return $value;
	}

	/**
	 * Output the field value.
	 * Depends on field value and field types, each field can extend this method to output its value in its own way
	 * See specific field classes for details.
	 *
	 * Note: we don't echo the field value directly. We return the output HTML of field, which will be used in
	 * ctrlbp_the_field function later.
	 *
	 * @use self::get_value()
	 * @see ctrlbp_the_value()
	 *
	 * @param  array    $field   Field parameters.
	 * @param  array    $args    Additional arguments. Rarely used. See specific fields for details.
	 * @param  int|null $post_id Post ID. null for current post. Optional.
	 *
	 * @return string HTML output of the field
	 */
	public static function the_value( $field, $args = array(), $post_id = null ) {
		$value = self::call( 'get_value', $field, $args, $post_id );

		if ( false === $value ) {
			return '';
		}

		return self::call( 'format_value', $field, $value, $args, $post_id );
	}

	/**
	 * Format value for the helper functions.
	 *
	 * @param array        $field   Field parameters.
	 * @param string|array $value   The field meta value.
	 * @param array        $args    Additional arguments. Rarely used. See specific fields for details.
	 * @param int|null     $post_id Post ID. null for current post. Optional.
	 *
	 * @return string
	 */
	public static function format_value( $field, $value, $args, $post_id ) {
		if ( ! $field['clone'] ) {
			return self::call( 'format_clone_value', $field, $value, $args, $post_id );
		}
		$output = '<ul>';
		foreach ( $value as $clone ) {
			$output .= '<li>' . self::call( 'format_clone_value', $field, $clone, $args, $post_id ) . '</li>';
		}
		$output .= '</ul>';
		return $output;
	}

	/**
	 * Format value for a clone.
	 *
	 * @param array        $field   Field parameters.
	 * @param string|array $value   The field meta value.
	 * @param array        $args    Additional arguments. Rarely used. See specific fields for details.
	 * @param int|null     $post_id Post ID. null for current post. Optional.
	 *
	 * @return string
	 */
	public static function format_clone_value( $field, $value, $args, $post_id ) {
		if ( ! $field['multiple'] ) {
			return self::call( 'format_single_value', $field, $value, $args, $post_id );
		}
		$output = '<ul>';
		foreach ( $value as $single ) {
			$output .= '<li>' . self::call( 'format_single_value', $field, $single, $args, $post_id ) . '</li>';
		}
		$output .= '</ul>';
		return $output;
	}

	/**
	 * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
	 *
	 * @param array    $field   Field parameters.
	 * @param string   $value   The value.
	 * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
	 * @param int|null $post_id Post ID. null for current post. Optional.
	 *
	 * @return string
	 */
	public static function format_single_value( $field, $value, $args, $post_id ) {
		return $value;
	}

	/**
	 * Call a method of a field.
	 * This should be replaced by static::$method( $args ) in PHP 5.3.
	 *
	 * @return mixed
	 */
	public static function call() {
		$args = func_get_args();

		$check = reset( $args );

		// Params: method name, field, other params.
		if ( is_string( $check ) ) {
			$method = array_shift( $args );
			$field  = reset( $args ); // Keep field as 1st param.
		} else {
			$field  = array_shift( $args );
			$method = array_shift( $args );

			if ( 'raw_meta' === $method ) {
				// Add field param after object id.
				array_splice( $args, 1, 0, array( $field ) );
			} else {
				$args[] = $field; // Add field as last param.
			}
		}

		return call_user_func_array( array( Helpers_Field::get_class( $field ), $method ), $args );
	}

	/**
	 * Apply various filters based on field type, id.
	 * Filters:
	 * - ctrlbp_{$name}
	 * - ctrlbp_{$field['type']}_{$name}
	 * - ctrlbp_{$field['id']}_{$name}
	 *
	 * @return mixed
	 */
	public static function filter() {
		$args = func_get_args();

		// 3 first params must be: filter name, value, field. Other params will be used for filters.
		$name  = array_shift( $args );
		$value = array_shift( $args );
		$field = array_shift( $args );

		// List of filters.
		$filters = array(
			'ctrlbp_' . $name,
			'ctrlbp_' . $field['type'] . '_' . $name,
		);
		if ( $field['id'] ) {
			$field_id  = $field['clone'] ? $field['_original_id'] : $field['id'];
			$filters[] = 'ctrlbp_' . $field_id . '_' . $name;
		}

		// Filter params: value, field, other params. Note: value is changed after each run.
		array_unshift( $args, $field );
		foreach ( $filters as $filter ) {
			$filter_args = $args;
			array_unshift( $filter_args, $value );
			$value = apply_filters_ref_array( $filter, $filter_args );
		}

		return $value;
	}
}