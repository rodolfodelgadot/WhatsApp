<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die( '-1' );
}

if ( ! is_multisite() ) {
	delete_option( 'wp-whatsapp-chat-pro_activation' );
}
