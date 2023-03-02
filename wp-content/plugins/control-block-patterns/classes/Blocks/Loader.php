<?php
namespace ControlPatterns\Blocks;

class Loader {
	public function __construct() {
		add_filter( 'ctrlbp_meta_box_class_name', array( $this, 'meta_box_class_name' ), 10, 2 );
		add_filter( 'ctrlbp_meta_type', [ $this, 'change_meta_type' ], 10, 3 );
		add_action( 'init', [ $this, 'register_assets' ] );
	}

	/**
	 * Filter meta box class name.
	 *
	 * @param  string $class_name Meta box class name.
	 * @param  array  $meta_box   Meta box settings.
	 * @return string
	 */
	public function meta_box_class_name( $class_name, $meta_box ) {
		if ( empty( $meta_box['type'] ) || 'block' !== $meta_box['type'] ) {
			return $class_name;
		}
		return empty( $meta_box['storage_type'] ) ? __NAMESPACE__ . '\Block' : __NAMESPACE__ . '\BlockPostMeta';
	}

	/**
	 * Filter meta type from object type and object id.
	 *
	 * @param string $type        Meta type get from object type and object id.
	 * @param string $object_type Object type.
	 * @param string $object_id   Object ID.
	 *
	 * @return string
	 */
	public function change_meta_type( $type, $object_type, $object_id ) {
		return 'block' === $object_type ? $object_id : $type;
	}

	public function register_assets() {
		wp_register_style(
			'ctrlbp-blocks',
			CTRLBP_CSS_URI . 'blocks.css',
			[],
			CTRLBP_VER
		);
		wp_register_script(
			'ctrlbp-blocks',
			CTRLBP_JS_URI . 'blocks.js',
			['wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'wp-editor', 'wp-data', 'underscore', 'jquery'],
			CTRLBP_VER,
			true
		);
		wp_add_inline_script( 'ctrlbp-blocks', 'window.ctrlbp = window.ctrlbp || {}; ctrlbp.blocks = [];', 'before' );
		wp_localize_script( 'ctrlbp-blocks', 'CTRLBPBlocks', [
			'nonce' => wp_create_nonce( 'fetch' ),
		] );

	}
}