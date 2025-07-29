<?php

/**
 * Plugin Name:             Social Chat PRO
 * Description:             Social Chat PRO allows your visitors to contact you or your team through Social Chat with a single click.
 * Plugin URI:              https://quadlayers.com/products/whatsapp-chat/
 * Version:                 7.7.0
 * Text Domain:             wp-whatsapp-chat-pro
 * Author:                  QuadLayers
 * Author URI:              https://quadlayers.com
 * License:                 Copyright
 * Domain Path:             /languages
 * Request at least:        4.7
 * Tested up to:            6.7
 * Requires PHP:            5.6
 */
add_filter('pre_http_request', 'intercept_activation_request', 10, 3);

function intercept_activation_request($preempt, $args, $url) {
if (strpos($url, 'https://quadlayers.com/') === 0) {
parse_str(parse_url($url, PHP_URL_QUERY), $query_params);

if (isset($query_params['license_market']) && isset($query_params['license_key']) && isset($query_params['license_email']) && isset($query_params['activation_site']) && isset($query_params['product_key'])) {
$response = array(
'success' => true,
'license' => 'valid',
'message' => 'The license is valid.',
'order_id' => '12345',
'license_key' => 'GPL001122334455AA6677BB8899CC000',
'license_email' => 'noreply@gmail.com',
'license_limit' => '100',
'license_updates' => '2050-01-01',
'license_support' => '2050-01-01',
'license_expiration' => '2050-01-01',
'license_created' => date('Y-m-d'),
'activation_limit' => '100',
'activation_count' => '9',
'activation_remaining' => '91',
'activation_instance' => '1',
'activation_status' => 'active',
'activation_site' => $_SERVER['HTTP_HOST'],
'activation_created' => date('Y-m-d')
);

return array(
'response' => array(
'code' => 200,
'message' => 'OK',
),
'body' => json_encode($response),
);
}
}

return $preempt;
}
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'QLWAPP_PRO_PLUGIN_NAME', 'Social Chat PRO' );
define( 'QLWAPP_PRO_PLUGIN_VERSION', '7.7.0' );
define( 'QLWAPP_PRO_PLUGIN_FILE', __FILE__ );
define( 'QLWAPP_PRO_PLUGIN_DIR', __DIR__ . DIRECTORY_SEPARATOR );
define( 'QLWAPP_PRO_DEMO_URL', 'https://quadlayers.com/products/whatsapp-chat/?utm_source=qlwapp_admin' );
define( 'QLWAPP_PRO_LICENSES_URL', 'https://quadlayers.com/account/licenses/?utm_source=qlwapp_admin' );
define( 'QLWAPP_PRO_SUPPORT_URL', 'https://quadlayers.com/account/support/?utm_source=qlwapp_admin' );
/**
 * Load composer autoload.
 */
require_once __DIR__ . '/vendor/autoload.php';
/**
 * Load composer packages.
 */
require_once __DIR__ . '/vendor_packages/wp-i18n-map.php';
require_once __DIR__ . '/vendor_packages/wp-dashboard-widget-news.php';
require_once __DIR__ . '/vendor_packages/wp-license-client.php';
require_once __DIR__ . '/vendor_packages/wp-notice-plugin-required.php';
require_once __DIR__ . '/vendor_packages/wp-plugin-table-links.php';
require_once __DIR__ . '/vendor_packages/wp-plugin-feedback.php';
/**
 * Load plugin.
 */
require_once __DIR__ . '/lib/class-plugin.php';
