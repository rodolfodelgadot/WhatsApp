<?php

if ( class_exists( 'QuadLayers\\WP_Plugin_Table_Links\\Load' ) ) {
	add_action('init', function() {
		new \QuadLayers\WP_Plugin_Table_Links\Load(
			QLWAPP_PRO_PLUGIN_FILE,
			array(
				array(
					'text' => esc_html__( 'Support', 'wp-whatsapp-chat-pro' ),
					'url'  => QLWAPP_PRO_SUPPORT_URL,
				),
				array(
					'text' => esc_html__( 'License', 'wp-whatsapp-chat-pro' ),
					'url'  => QLWAPP_PRO_LICENSES_URL,
				),
			)
		);
	});
}
