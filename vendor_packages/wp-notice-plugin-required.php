<?php

namespace QuadLayers\WP_Notice_Plugin_Required;

if ( class_exists( 'QuadLayers\\WP_Notice_Plugin_Required\\Load' ) ) {
	new \QuadLayers\WP_Notice_Plugin_Required\Load(
		QLWAPP_PRO_PLUGIN_NAME,
		array(
			array(
				'slug' => 'wp-whatsapp-chat',
				'name' => 'Social Chat',
			),
		)
	);
}
