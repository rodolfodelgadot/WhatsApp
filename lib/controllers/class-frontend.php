<?php

namespace QuadLayers\QLWAPP_PRO\Controllers;

use QuadLayers\QLWAPP\Models\Box as Models_Box;
use QuadLayers\QLWAPP\Models\Button as Models_Button;
use QuadLayers\QLWAPP\Models\Display as Models_Display;
use QuadLayers\QLWAPP\Models\Contacts as Models_Contacts;
use QuadLayers\QLWAPP\Models\Settings as Models_Settings;
use QuadLayers\QLWAPP\Models\Scheme as Models_Scheme;
use QuadLayers\QLWAPP_PRO\Services\Entity_Visibility;

class Frontend {

	protected static $instance;

	private function __construct() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'register_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'register_scripts' ) );

		add_action(
			'qlwapp_load',
			function () {
				remove_action( 'wp_enqueue_scripts', array( 'QuadLayers\QLWAPP\Controllers\Frontend', 'add_assets' ), 200 );
				remove_action( 'wp_footer', array( 'QuadLayers\QLWAPP\Controllers\Frontend', 'add_app' ) );
				add_action( 'wp_enqueue_scripts', array( __CLASS__, 'add_assets' ) );
				add_action( 'wp_footer', array( __CLASS__, 'add_app' ) );
			},
			10
		);
	}

	public static function register_scripts() {
		wp_register_style(
			'qlwapp-icons',
			plugins_url( '/assets/qlwapp-icons.min.css', QLWAPP_PRO_PLUGIN_FILE ),
			null,
			QLWAPP_PRO_PLUGIN_VERSION,
			'all'
		);
	}

	public static function add_assets() {

		$settings = Models_Settings::instance()->get();
		$button   = Models_Button::instance()->get();

		if ( isset( $button['icon'] ) && $button['icon'] !== 'qlwapp-whatsapp-icon' ) {
			wp_enqueue_style( 'qlwapp-icons' );
		}

		if ( empty( $settings['googleAnalytics'] ) || 'disable' === $settings['googleAnalytics'] ) {
			return;
		}

		$ga_key      = trim( $settings['googleAnalyticsV4Id'] );
		$ga_category = $settings['googleAnalyticsCategory'] ? trim( $settings['googleAnalyticsCategory'] ) : 'Quadlayers Social Chat';
		$ga_label    = $settings['googleAnalyticsLabel'] ? trim( $settings['googleAnalyticsLabel'] ) : 'Quadlayers Social Chat';

		if ( 'yes' === $settings['googleAnalyticsScript'] ) {
			wp_enqueue_script( 'qlwapp-analytics', sprintf( 'https://www.googletagmanager.com/gtag/js?id=%s', esc_attr( $ga_key ) ), null, null );
		} else {
			wp_register_script( 'qlwapp-analytics', '', null, '', true );
			wp_enqueue_script( 'qlwapp-analytics' );
		}

		wp_add_inline_script(
			'qlwapp-analytics',
			sprintf(
				'( function() {
						window.dataLayer = window.dataLayer || [];

						function gtag() {
							dataLayer.push(arguments);
						}
	
						gtag("js", new Date());
						gtag("config", "%1$s");
	
						function ga_events(events) {
	
							const {
								ga_action,
								ga_category,
								ga_label,
							} = events;
	
							if (typeof gtag !== "undefined") {
								gtag("event", ga_action, {
									"event_category": ga_category,
									"event_label": ga_label,
								});
							} else 
							if (typeof ga !== "undefined" && typeof ga.getAll !== "undefined") {
								var tracker = ga.getAll();
								tracker[0].send("event", ga_category, ga_action, ga_label);
							} else 
							if (typeof __gaTracker !== "undefined") {
								__gaTracker("send", "event", ga_category, ga_action, ga_label);
							}
	
							if (typeof dataLayer !== "undefined") {
								dataLayer.push({
									"event": ga_action,
									"event_action": ga_action,
									"event_category": ga_category,
									"event_label": ga_label,
								});
							}
						}
	
						window.addEventListener("qlwapp.click", function() {
							ga_events({
								ga_action: "click:quadlayers_social_chat",
								ga_category: "%2$s",
								ga_label: "%3$s",
							});
						});
					} ) ();',
				$ga_key,
				$ga_category,
				$ga_label
			)
		);
	}

	public static function add_app() {
		$button  = Models_Button::instance()->get();
		$display = Models_Display::instance()->get();
		$box     = Models_Box::instance()->get();
		$scheme  = Models_Scheme::instance()->get();

		$is_visible = Entity_Visibility::instance()->is_show_view( $display );

		if ( ! $is_visible ) {
			return;
		}

		// Filter the contacts based on the display settings.
		$contacts = array_values(
			array_filter(
				Models_Contacts::instance()->get_contacts_reorder(),
				function ( $contact ) {
					if ( ! isset( $contact['display'] ) ) {
						return true;
					}
					$is_visible = Entity_Visibility::instance()->is_show_view( $contact['display'] );
					return $is_visible;
				}
			)
		);

		$style  = self::get_scheme_css_properties( $scheme );
		$style .= self::get_button_css_properties( $button );

		$contacts_json = wp_json_encode( $contacts );
		$display_json  = wp_json_encode( $display );
		$button_json   = wp_json_encode( $button );
		$box_json      = wp_json_encode( $box );
		$scheme_json   = wp_json_encode( $scheme );

		?>
		<div 
			class="qlwapp"
			style="<?php echo esc_attr( $style ); ?>"
			data-contacts="<?php echo esc_attr( $contacts_json ); ?>"
			data-display="<?php echo esc_attr( $display_json ); ?>"
			data-button="<?php echo esc_attr( $button_json ); ?>"
			data-box="<?php echo esc_attr( $box_json ); ?>"
			data-scheme="<?php echo esc_attr( $scheme_json ); ?>"
		>
			<?php if ( ! empty( $box['footer'] ) ) : ?>
				<div class="qlwapp-footer">
					<?php echo wpautop( wp_kses_post( $box['footer'] ) ); ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	public static function get_button_css_properties( $button ) {
		$style = '';
		foreach ( $button as $key => $value ) {
			if ( '' !== $value ) {
				if ( ! str_contains( $key, 'animation' ) ) {
					continue;
				}
				if ( str_contains( $key, 'animation_delay' ) ) {
					$value = "{$value}s";
				}
				$style .= sprintf( '--%s-button-%s:%s;', QLWAPP_DOMAIN, esc_attr( str_replace( '_', '-', $key ) ), esc_attr( $value ) );
			}
		}
		return $style;
	}

	public static function get_scheme_css_properties( $scheme ) {
		$style = '';
		foreach ( $scheme as $key => $value ) {
			if ( is_numeric( $value ) ) {
				$value = "{$value}px";
			}
			if ( '' !== $value ) {
				$style .= sprintf( '--%s-scheme-%s:%s;', QLWAPP_DOMAIN, esc_attr( str_replace( '_', '-', $key ) ), esc_attr( $value ) );
			}
		}
		return $style;
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
