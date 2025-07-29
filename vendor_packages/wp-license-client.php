<?php

add_action('qlwapp_init', function() {
	global $qlwapp_license_client;

	if ( ! isset( $qlwapp_license_client ) ) {

		$qlwapp_license_client = new QuadLayers\WP_License_Client\Load(
			array(
				'api_url'           => 'https://quadlayers.com/wp-json/wc/wlm/',
				'product_key'       => '4c3b7745ace5a4648fe6b434964955b6',
				'parent_menu_slug'  => 'wp-whatsapp-chat',
				'license_menu_slug' => false,
				'license_url'       => admin_url( 'admin.php?page=wp-whatsapp-chat&tab=license' ),
				'rest_namespace'   => 'qlwapp',
				'license_key_url'   => QLWAPP_PRO_LICENSES_URL,
				'support_url'       => QLWAPP_PRO_SUPPORT_URL,
				'plugin_file'       => QLWAPP_PRO_PLUGIN_FILE,
			)
		);
	}

	return $qlwapp_license_client;
});
