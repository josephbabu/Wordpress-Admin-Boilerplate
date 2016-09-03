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

	protected $_pages = array();
	protected $fs = null;

	private function __construct() {

		// Get pub instance of plugin
		$plugin            = BoilerPlatePub::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();


		$this->fs = $this->_init_filesystem();

		$this->_get_configuration();

		$this->_register_actions();
		$this->_register_filters();

	}

	private function _init_filesystem() {

		global $wp_filesystem;

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			include_once ABSPATH . 'wp-admin/includes/file.php';
		}
		if ( ! $wp_filesystem ) {
			WP_Filesystem();
		}

		return $wp_filesystem;
	}

	private function _get_configuration() {
		$config = $this->fs->get_contents( dirname( __FILE__ ) . '/includes/admin_boilerplate_config.json' );
		if ( ! $config ) {
			$this->set_option_helper( true, 'config_file_read_error', $this->plugin_slug . '_errors' );
		}
	}

	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function get_option_helper( $option, $section, $default = '' ) {

		$options = get_option( $section );

		if ( isset( $options[ $option ] ) ) {
			return $options[ $option ];
		}

		return $default;
	}

	public function set_option_helper( $value = '', $option, $section ) {

		$options            = get_option( $section );
		$options[ $option ] = $value;

		update_option( $section, $options );

	}

	public function enqueue_admin_styles() {

		//* FOR USER:: @TODO Better to change the name of file
		wp_enqueue_style( $this->plugin_slug . '-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array( 'dashicons' ), BoilerPlatePub::VERSION );
	}

	public function enqueue_admin_scripts() {

		wp_enqueue_script( 'common' );
		wp_enqueue_script( 'wp-lists' );
		wp_enqueue_script( 'postbox' );

		wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), BoilerPlatePub::VERSION );

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
		\add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		\add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		\add_action( 'admin_init', array( $this, 'register_notices' ) );

		\add_action( 'admin_menu', array( $this, 'add_to_admin_theme_submenu' ) );

	}

	public function _register_filters() {

		if ( is_admin() ) {
			\add_filter( 'plugin_row_meta', array( $this, '_add_row_plugin_meta' ), 10, 2 );
			\add_filter( 'plugin_action_links', array( $this, '_add_row_plugin_action_links' ) );
		}

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

	public function _add_row_plugin_meta( $links ) {
		return array_merge( array(
			'<a href="' . admin_url( 'admin.php?page=jigoshop_settings' ) . '">' . __( 'Settings', 'jigoshop' ) . '</a>'
		), $links );
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

	public function _add_row_plugin_action_links( $links ) {
		return array_merge( array(
			'<a href="' . admin_url( 'admin.php?page=jigoshop_settings' ) . '">' . __( 'Settings', 'jigoshop' ) . '</a>'
		), $links );
	}

	/**
	 *  Determines whether the current admin page is this plugin admin page.
	 *
	 *  Only works after the `wp_loaded` hook, & most effective
	 *  starting on `admin_menu` hook.
	 *
	 * @since 1.0
	 * @return bool true if this is plugin admin page.
	 */
	public function _is_plugin_page() {

		if ( ! is_admin() || ! did_action( 'wp_loaded' ) ) {
			return false;
		}

		if ( ! isset( $_GET['page'] ) ) {
			return false;
		}

		$page = isset( $_GET['page'] ) ? $_GET['page'] : '';

		//return in_array( $page, $this->_pages );
		return true;
	}

	private function _is_disabled_by_error( $error_name = '' ) {

		if ( $this->get_option_helper( $error_name, $this->plugin_slug . '_errors' ) === true &&
		     $this->get_option_helper( $error_name, $this->plugin_slug . '_errors' ) !== ''
		) {
			return true;
		} else {
			return false;
		}
	}

	public function register_notices() {

		if ( $this->_is_plugin_page() ) {
			add_action( 'admin_notices', array( $this, 'add_notices' ) ); //register notification
		}

	}

	public function add_notices() {

		$this->_create_notice_box( 'config_file_read_error' );

	}

	private function _create_notice_box( $notice_name ) {

		if ( (bool) $this->_is_disabled_by_error( $notice_name ) ) {
			wp_localize_script( 'postbox', $this->plugin_slug . '_errors', array( $notice_name => true ) );
			if ( (bool) ! $this->_is_disabled_by_error( 'custom_uploads_dir_error_notice' ) ) {
				print '<div class="error" id="custom_uploads_dir_error_notice"><p><span class="text"><strong>' . __( 'SimpleFavicon issue:', $this->plugin_slug ) . '</strong> ' . __( 'can\'t create files in directory or directory itself ', $this->plugin_slug
					) . ' "' . ABSPATH . 'wp-content/uploads/' . $this->plugin_slug . '/". ' . __( 'Some functionality is affected now.', $this->plugin_slug ) . '</span> <a href="javascript:process_notice_option(\'custom_uploads_dir_error_notice\', \'' . esc_js( wp_create_nonce( $this->plugin_slug . '-custom_uploads_dir_error_notice' ) ) . '\');" class="button" style="float:right;">' . __( 'Don\'t warn me.', $this->plugin_slug ) . '</a><br style="clear:both;" /></p></div>';
			}
		}

	}
}