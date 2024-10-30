<?php
/**
 * File: class-frontendrouter.php
 *
 * @since 2024-07-21
 * @license GPL-3.0-or-later
 *
 * @package kronos/Routers
 */

namespace kronos\App\Routers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use kronos\App\Controllers\ICalendarParser;
use kronos\App\Controllers\Setup;
use kronos\App\Models\Abbreviation;
use kronos\App\Requests\Icalendar;
use kronos\App\Requests\ICSInterface;

if ( session_status() !== PHP_SESSION_ACTIVE ) {
	session_start();
}

/**
 * Router for requests from the frontend.
 */
class FrontendRouter {
	/**
	 * Executes the specified code based on the given attributes.
	 *
	 * @return string|null The output generated by the executed code, or null if no output is generated.
	 */
	public static function execute() {
		if ( isset( $_REQUEST['page'] ) ) {
			$page = sanitize_key( wp_unslash( $_REQUEST['page'] ) );

			if ( 'refresh-kronos-calendar' === $page ) {
				if ( isset( $_REQUEST['kronos-update'] ) ) {
					ICSInterface::download_from_remote( Icalendar::CALENDAR_TYPE_EVENTS );
					kronos_show_message( __( 'The calender is updated', 'kronos' ) );
				}

				$plugin_data = get_plugin_data( KRONOS_PLUGIN_STARTUP_FILE );

				wp_enqueue_style(
					'kronos-css',
					KRONOS_PLUGIN_URL . '/assets/stylesheets/frontend-calendar.css',
					array(),
					$plugin_data['Version']
				);

				require KRONOS_TEMPLATE_DIR . '/refresh.php';

				return;
			}

			if ( 'edit-kronos-settings' === $page ) {
				$active_tab = 'tab-common';
				if ( isset( $_REQUEST['tab'] ) ) {
					$active_tab = sanitize_key( wp_unslash( $_REQUEST['tab'] ) );
				}

				$mode = null;
				if ( isset( $_REQUEST['mode'] ) ) {
					$mode = sanitize_key( wp_unslash( $_REQUEST['mode'] ) );
				}

				if ( isset( $_REQUEST['nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_REQUEST['nonce'] ) ) ) ) {
					if (
						isset( $_REQUEST['save_new_abbreviation'] ) &&
						isset( $_REQUEST['event-name'] ) &&
						isset( $_REQUEST['event-abbreviation'] )
					) {
						Abbreviation::create_new(
							sanitize_text_field( wp_unslash( $_REQUEST['event-abbreviation'] ) ),
							sanitize_text_field( wp_unslash( $_REQUEST['event-name'] ) )
						);
						kronos_show_message( __( 'The abbreviation was created', 'kronos' ) );

					}

					if (
						isset( $_REQUEST['update_abbreviation'] ) &&
						isset( $_REQUEST['abbreviation-id'] ) &&
						isset( $_REQUEST['event-name'] ) &&
						isset( $_REQUEST['event-abbreviation'] )
					) {
						Abbreviation::update(
							(int) sanitize_text_field( wp_unslash( $_REQUEST['abbreviation-id'] ) ),
							sanitize_text_field( wp_unslash( $_REQUEST['event-abbreviation'] ) ),
							sanitize_text_field( wp_unslash( $_REQUEST['event-name'] ) )
						);
						kronos_show_message( __( 'The abbreviation was edited', 'kronos' ) );

					}

					if (
						'delete-abbreviation' === $mode &&
						isset( $_REQUEST['abbreviation-id'] )
					) {
						Abbreviation::delete( (int) sanitize_text_field( wp_unslash( $_REQUEST['abbreviation-id'] ) ) );
						kronos_show_message( __( 'The abbreviation was deleted', 'kronos' ) );

					}

					if ( isset( $_REQUEST['save_common'] ) ) {
						if (
							isset( $_REQUEST['category']['yellow'] ) &&
							isset( $_REQUEST['category']['blue'] ) &&
							isset( $_REQUEST['category']['red'] ) &&
							isset( $_REQUEST['category']['green'] ) &&
							isset( $_REQUEST['category']['fuchsia'] ) &&
							isset( $_REQUEST['category']['orange'] )
						) {
							$categories            = array();
							$categories['yellow']  = sanitize_text_field( wp_unslash( $_REQUEST['category']['yellow'] ) );
							$categories['blue']    = sanitize_text_field( wp_unslash( $_REQUEST['category']['blue'] ) );
							$categories['red']     = sanitize_text_field( wp_unslash( $_REQUEST['category']['red'] ) );
							$categories['green']   = sanitize_text_field( wp_unslash( $_REQUEST['category']['green'] ) );
							$categories['fuchsia'] = sanitize_text_field( wp_unslash( $_REQUEST['category']['fuchsia'] ) );
							$categories['orange']  = sanitize_text_field( wp_unslash( $_REQUEST['category']['orange'] ) );

							update_option( 'kronos_calendar_categories', $categories );
							if ( isset( $_REQUEST['calendar_url'] ) ) {
								update_option( 'kronos_calendar_url', sanitize_url( wp_unslash( $_REQUEST['calendar_url'] ) ) );
							}

							if ( isset( $_REQUEST['state'] ) ) {
								update_option( 'kronos_state', sanitize_text_field( wp_unslash( $_REQUEST['state'] ) ) );
							}

							kronos_show_message( __( 'The settings were saved', 'kronos' ) );
						}
					}
				}

				Setup::settings_page( $active_tab, $mode );
				return;
			}
		}
	}
}