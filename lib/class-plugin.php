<?php

namespace QuadLayers\QLWAPP_PRO;

final class Plugin {

	protected static $instance;

	private function __construct() {

		/**
		 * Load plugin textdomain
		 */
		add_action( 'init', array( $this, 'load_textdomain' ) );

		add_action(
			'qlwapp_init',
			function () {
				/**
				 * Check if free version exists
				 */
				if ( ! class_exists( 'QuadLayers\\QLWAPP\\Plugin', false ) ) {
					return;
				}
				/**
				 * Remove premium css
				 */
				remove_action( 'admin_footer', array( 'QuadLayers\QLWAPP\Plugin', 'add_premium_css' ) );
				/**
				 * Load classes
				 */

				Controllers\Frontend::instance();
				
				global $wp_version;

				if ( version_compare( $wp_version, '6.2', '<' ) ) {
					Controllers\Admin_Menu::instance();
				} elseif ( class_exists( 'QuadLayers\\QLWAPP\\Controllers\\New_Admin_Menu', false ) ) {
					Controllers\New_Admin_Menu::instance();
				}
			}
		);
	}

	/**
	 * Load plugin textdomain
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'wp-whatsapp-chat-pro', false, QLWAPP_PRO_PLUGIN_DIR . '/languages/' );
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}

Plugin::instance();
