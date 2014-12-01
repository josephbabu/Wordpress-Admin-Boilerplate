<?php
/**
 * Plugin Name:       Admin Boilerplate
 * Plugin URI:        http://www.nulllab.net/development/adminboilerplate
 * Description:       Manage favicon icons for your Wordpress website easily and with pleasure
 * Version:           0.0.1
 * Author:            @nulllogic
 * Author URI:        http://www.nulllogic.net/
 * Text Domain:       adminboilerplate
 * License:           MIT
 * License URI:       http://opensource.org/licenses/MIT
 * Domain Path:       /i18n/languages
 */


// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// If you want to use this plugin inside you theme and not to list this in Wordpress plugin listing.
if( ! defined ('USE_PLUGIN_INSIDE_THEME')) {

	require_once( plugin_dir_path( __FILE__ ) . 'public/boilerplate-pub.php' );

	register_activation_hook( __FILE__, array( 'BoilerPlatePub', 'activate' ) );
	register_deactivation_hook( __FILE__, array( 'BoilerPlatePub', 'deactivate' ) );

	add_action( 'plugins_loaded', array( 'BoilerPlatePub', 'get_instance' ) );

	if ( is_admin() ) {

		require_once( plugin_dir_path( __FILE__ ) . 'admin/boilerplate-admin.php' );
		add_action( 'plugins_loaded', array( 'BoilerPlateAdmin', 'get_instance' ) );

	}

} else {



}

