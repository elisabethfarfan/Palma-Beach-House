<?php
namespace ControlPatterns;
use ControlPatterns\Helpers\String_Type as Helpers_String;
/**
 * The plugin core class which initialize plugin's code.
 * 
 * The Control Block Patterns core class.
 * @package ControlBlockPatterns
 *
 */


class Core {
	
	public function __construct() {
		$this->init();
	}
	/**
	 * Initialization.
	 */
	public function init() {
		load_plugin_textdomain( 'control-block-patterns', false, plugin_basename( CTRLBP_DIR ) . '/assets/languages/' );

		add_filter( 'plugin_action_links_control-block-patterns/control-block-patterns.php', array( $this, 'plugin_links' ), 20 );

		// Uses priority 20 to support custom port types registered using the default priority.
		add_action( 'init', array( $this, 'register_meta_boxes' ), 20 );
		add_action( 'edit_page_form', array( $this, 'fix_page_template' ) );
		$this->add_context_hooks();
	}

	/**
	 * Add links to Documentation and Extensions in plugin's list of action links.
	 *
	 * @since 4.3.11
	 *
	 * @param array $links Array of plugin links.
	 *
	 * @return array
	 */
	public function plugin_links( $links ) {
		$links[] = '<a href="https://controlpatterns.net/docs">' . esc_html__( 'Docs', 'control-block-patterns' ) . '</a>';
		return $links;
	}

	/**
	 * Register meta boxes.
	 * Advantages:
	 * - prevents incorrect hook.
	 * - no need to check for class existences.
	 */
	public function register_meta_boxes() {
		$configs = ctrlbp_meta_boxes();
		if( current_theme_supports( 'control-block-patterns' ) ){
			$configs  = apply_filters( 'ctrlbp_meta_boxes', $configs );	
			$configs  = apply_filters( 'ctrlbp_responsive_config', $configs );
		}
		
		$registry = ctrlbp_get_registry( 'meta_box' );

		foreach ( $configs as $config ) {
			$meta_box = $registry->make( $config );
			$meta_box->register_fields();
		}
	}

	public static function get_registry( $type ) {
		static $data = array();
		$class = Helpers_String::title_case( $type ) . '_Registry';
		$class = __NAMESPACE__."\\$class";
		if ( ! isset( $data[ $type ] ) ) {
			$data[ $type ] = new $class();
		}

		return $data[ $type ];
	}

	/**
	 * WordPress will prevent post data saving if a page template has been selected that does not exist.
	 * This is especially a problem when switching to our theme, and old page templates are in the post data.
	 * Unset the page template if the page does not exist to allow the post to save.
	 *
	 * @param WP_Post $post Post object.
	 *
	 * @since 4.3.10
	 */
	public function fix_page_template( \WP_Post $post ) {
		$template       = get_post_meta( $post->ID, '_wp_page_template', true );
		$page_templates = wp_get_theme()->get_page_templates();

		// If the template doesn't exists, remove the data to allow WordPress to save.
		if ( ! isset( $page_templates[ $template ] ) ) {
			delete_post_meta( $post->ID, '_wp_page_template' );
		}
	}

	/**
	 * Get registered meta boxes via a filter.
	 *
	 * @deprecated No longer used. Keep for backward-compatibility with extensions.
	 *
	 * @return array
	 */
	public static function get_meta_boxes() {
		$meta_boxes = ctrlbp_get_registry( 'meta_box' )->all();
		return wp_list_pluck( $meta_boxes, 'meta_box' );
	}

	/**
	 * Add hooks for extra contexts.
	 */
	public function add_context_hooks() {
		$hooks = array(
			'edit_form_top',
			'edit_form_after_title',
			'edit_form_after_editor',
			'edit_form_before_permalink',
		);

		foreach ( $hooks as $hook ) {
			add_action( $hook, array( $this, 'add_context' ) );
		}
	}

	/**
	 * Add new meta box context.
	 *
	 * @param WP_Post $post The current post object.
	 */
	public function add_context( $post ) {
		$hook    = current_filter();
		$context = 'edit_form_top' === $hook ? 'form_top' : substr( $hook, 10 );
		do_meta_boxes( null, $context, $post );
	}
}
