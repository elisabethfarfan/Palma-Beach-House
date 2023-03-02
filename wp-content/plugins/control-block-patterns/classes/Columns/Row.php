<?php
/**
 * Add row, column markup to a set of fields in one row.
 */

namespace ControlPatterns\Columns;

class Row {
	/**
	 * List of fields.
	 *
	 * @var array
	 */
	protected $fields;

	/**
	 * Store the meta box's columns data.
	 *
	 * @var array
	 */
	protected $columns = array();

	/**
	 * Store the total column of a row.
	 *
	 * @var int
	 */
	protected $total_columns;

	/**
	 * Track current column.
	 *
	 * @var string
	 */
	protected $current_column = '';

	/**
	 * Track current field.
	 *
	 * @var array
	 */
	protected $current_field;

	/**
	 * Add hooks to meta box
	 *
	 * @param array $columns List of columns.
	 * @param array $fields  List of fields.
	 */
	public function __construct( array $columns, $fields ) {
		$this->columns = $columns;
		$this->fields  = $fields;
	}

	/**
	 * Process all fields to add column markup to each one.
	 */
	public function process() {
		$index = 0;
		$count = count( $this->fields ) - 1;

		foreach ( $this->fields as &$field ) {

			if ( empty( $field['column'] ) ) {
				continue;
			}

			$this->process_field( $field );

			if ( $count === $index ) {
				$this->process_last_field( $field );
			}

			if ( isset( $field['fields'] ) ) {
				$row = new self( $this->columns, $field['fields'] );
				$row->process();
				$field['fields'] = $row->get_fields();
			}

			$this->current_column = $field['column'];
			$this->current_field  = &$field;

			$index ++;
		}
	}

	/**
	 * Get all processed fields.
	 *
	 * @return array
	 */
	public function get_fields() {
		return $this->fields;
	}

	/**
	 * Process a field in the middle.
	 *
	 * @param array $field Field settings.
	 */
	public function process_field( &$field ) {
		if ( ! $this->is_start_column( $field ) ) {
			return;
		}
		if ( ! isset( $this->columns[ $field['column'] ] ) ) {
			return;
		}

		$column = $this->columns[ $field['column'] ];
		$before = '';
		$after  = '';

		$after .= '</div><!-- .ctrlbp-col -->';

		if ( $this->is_start_row( $field ) ) {
			$after  .= '</div><!-- .ctrlbp-row -->';
			$before .= '<div class="ctrlbp-row ctrlbp-has-columns">';

			$this->total_columns = 0;
		}

		$before .= sprintf( '<div class="ctrlbp-field-col ctrlbp-col-12 ctrlbp-col-md-%s %s">', absint( $column['size'] ), esc_attr( $column['class'] ) );

		$this->total_columns += $column['size'];

		if ( $this->current_field ) {
			$this->current_field['after'] .= $after;
		}
		$field['before'] = $before . $field['before'];
	}

	/**
	 * Process the last field.
	 *
	 * @param array $field Field settings.
	 */
	protected function process_last_field( &$field ) {
		$after          = '</div><!-- .ctrlbp-col -->';
		$after          .= '</div><!-- .ctrlbp-row -->';
		$field['after'] .= $after;
	}

	/**
	 * Check if this field starts a column.
	 *
	 * @param  array $field Field settings.
	 *
	 * @return bool
	 */
	protected function is_start_column( $field ) {
		return $field['column'] !== $this->current_column;
	}

	/**
	 * Check if this field starts a row.
	 *
	 * @param  array $field Field settings.
	 *
	 * @return bool
	 */
	protected function is_start_row( $field ) {
		return ! $this->current_column || $this->total_columns + $this->columns[ $field['column'] ]['size'] > 12;
	}
}
