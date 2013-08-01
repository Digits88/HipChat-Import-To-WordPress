<?php
/*
Plugin Name: WDS HipChat

Plugin URI: http://webdevstudios.com/
Description: HipChat interface for wdschat.webdevstudios.com
Author URI: http://webdevstudios.com
Version: 1.0.0
*/

class _WDS_HipChat {

	protected $admin_slug = 'wds-hipchat-settings';
	protected $admin_name = 'WDS HipChat Settings';

	function __construct() {

		define( '_WDS_HipChat_PATH', plugin_dir_path( __FILE__ ) );
		define( '_WDS_HipChat_URL', plugins_url('/', __FILE__ ) );

		add_action( 'init', array( $this, 'hooks' ) );
		add_action( 'admin_init', array( $this, 'admin_hooks' ) );
		add_action( 'admin_menu', array( $this, 'settings' ) );
		// filter cmb url path
		add_filter( 'cmb_meta_box_url', array( $this, 'update_cmb_url' )  );
		add_filter( 'wds_cpt_core_url', array( $this, 'update_cpt_tax_url' )  );
	}

	public function lib_url( $url ) {
		return trailingslashit( _WDS_HipChat_URL.'lib/'. $url );
	}

	public function update_cpt_tax_url( $url ) {
		return $this->lib_url( 'cpt_tax' );
	}

	public function update_cmb_url( $url ) {
		return $this->lib_url( 'cmb' );
	}

	public function hooks() {
	}

	public function admin_hooks() {
	}

	/**
	 * Sets up our custom settings page
	 * @since  1.0.0
	 */
	public function settings() {
		// create admin page
		$this->settings_page = add_submenu_page( 'options-general.php', $this->admin_name, $this->admin_name, 'manage_network_options', $this->admin_slug, array( $this, 'settings_page' ) );
	}

	/**
	 * Our admin page
	 * @since  1.0.0
	 */
	public function settings_page() {
		require_once( 'hipchat-settings.php' );
	}

}
$GLOBALS['_WDS_HipChat'] = new _WDS_HipChat;