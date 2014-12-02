<?php

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * @package             Wordpress Admin Boilerplate
 * @category            Public
 * @author              NullLogic
 * @copyright           Copyright © 2014 NullLogic
 * @license             MIT
 */
class BoilerPlateAdmin {

	protected static $instance = null;

	protected $plugin_screen_hook_suffix = null;

	private function __construct() {


		// Get pub instance of plugin
		$plugin            = BoilerPlatePub::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		$this->plugin_screen_hook_suffix = 'appearance_page_' . $this->plugin_slug;

		$this->_register_actions();
		$this->_register_filters();

	}

	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function enqueue_admin_styles() {
		wp_enqueue_style( $this->plugin_slug . '-admin-styles', plugins_url( 'assets/css/sf-admin.css', __FILE__ ), array( 'dashicons' ), Plugin_Name::VERSION );

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @TODO:
	 *
	 * - Rename "Plugin_Name" to the name your plugin
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		wp_enqueue_script( 'common' );
		wp_enqueue_script( 'wp-lists' );
		wp_enqueue_script( 'postbox' );

		wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), Plugin_Name::VERSION );

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Page Title', $this->plugin_slug ),
			__( 'Menu Text', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}

	private function _register_actions() {

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );


		add_action( 'admin_menu', array( $this, 'add_to_admin_theme_submenu' ) );

	}

	public function _register_filters() {

		if ( is_admin() ) {
			\add_filter( 'plugin_row_meta', array( $this, '_add_custom_plugin_row_meta' ), 10, 2 );
		}

	}

	public function _add_custom_plugin_row_meta( $links, $file ) {

		if ( strpos( $file, $this->plugin_slug . '.php' ) !== false ) {
			$new_links = array(
				'<a href="#" target="_blank" title="SimpleFavicon helpdesk">Custom title for plugin</a>'
			);

			$links = array_merge( $links, $new_links );
		}

		return $links;
	}

	public function add_to_admin_theme_submenu() {
		$page = add_theme_page( 'Favicon settings', 'Favicon', 'manage_options', 'boilerplate', array(
			$this,
			'boilerplate_admin_page'
		) );
	}

	public function boilerplate_admin_page() {
		include_once( 'views/boilerplate-admin-view.php' );
	}

	private function _is_plugin_page() {
		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
	}

}
