<?php

if ( class_exists( 'QuadLayers\\PluginFeedback\\Load' ) ) {
	\QuadLayers\PluginFeedback\Load::instance()->add(
		QLWAPP_PRO_PLUGIN_FILE,
		array(
			'support_link' => QLWAPP_PRO_SUPPORT_URL,
		)
	);
}