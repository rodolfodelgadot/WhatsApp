<?php

namespace QuadLayers\QLWAPP_PRO\Controllers;

use QuadLayers\QLWAPP\Controllers\New_Admin_Menu as Admin_Menu_Free;

class New_Admin_Menu extends Admin_Menu_Free {

	protected static $instance;

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );
	}

	public function enqueue_scripts() {

		global $wp_version;

		$menu_slug = self::get_menu_slug();

		add_submenu_page(
			$menu_slug,
			esc_html__( 'License', 'wp-whatsapp-chat-pro' ),
			esc_html__( 'License', 'wp-whatsapp-chat-pro' ),
			'manage_options',
			"{$menu_slug}&tab=license",
			'__return_null'
		);

		remove_submenu_page( $menu_slug, "$menu_slug&tab=premium" );

		if ( ! isset( $_GET['page'] ) || $_GET['page'] !== $menu_slug ) {
			return;
		}

		global $qlwapp_license_client;

		$routes_paths = array_map(
			function ( $route ) {
				return $route->get_rest_path();
			},
			! empty( $qlwapp_license_client->routes->get() ) ? $qlwapp_license_client->routes->get() : array()
		);

		$backend = include_once QLWAPP_PRO_PLUGIN_DIR . 'build/backend/js/index.asset.php';

		wp_enqueue_style(
			'qlwapp-pro-new-admin-menu',
			plugins_url( '/build/backend/css/style.css', QLWAPP_PRO_PLUGIN_FILE ),
			array(
				'media-views',
				'wp-components',
				'wp-editor',
				'qlwapp-icons',
			),
			QLWAPP_PRO_PLUGIN_VERSION
		);

		wp_enqueue_script(
			'qlwapp-pro-new-admin-menu',
			plugins_url( '/build/backend/js/index.js', QLWAPP_PRO_PLUGIN_FILE ),
			$backend['dependencies'],
			$backend['version'],
			true
		);

		wp_localize_script(
			'qlwapp-pro-new-admin-menu',
			'qlwappProApiAdminMenu',
			array(
				'WP_VERSION'                => $wp_version,
				'QLWAPP_PRO_LICENSE_ROUTES' => $routes_paths,
				'QLWAPP_PRO_DEMO_URL'       => QLWAPP_PRO_DEMO_URL,
				'QLWAPP_PRO_LICENSES_URL'   => QLWAPP_PRO_LICENSES_URL,
				'QLWAPP_PRO_SUPPORT_URL'    => QLWAPP_PRO_SUPPORT_URL,
			)
		);
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
