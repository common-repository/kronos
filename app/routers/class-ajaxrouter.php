<?php
/**
 * File: class-ajaxrouter.php
 *
 * @since 2024-08-09
 * @license GPL-3.0-or-later
 *
 * @package kronos/Routers
 */

namespace kronos\App\Routers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use kronos\App\Controllers\DisplayCalendar;
use kronos\App\Controllers\ICalendarParser;
use kronos\App\Requests\Icalendar;

/**
 * Class AjaxRouter
 *
 * This class handles the ajax requests and routes them to the appropriate methods.
 */
class AjaxRouter {

	/**
	 * Handles the ajax requests
	 *
	 * @return void
	 */
	public static function execute() {
		if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_REQUEST['nonce'] ) ) ) ) {
			wp_die( 'Invalid AJAX call' );
		}

		if ( ! isset( $_REQUEST['method'] ) ) {
			wp_die( 'Invalid AJAX call' );
		}

		$method   = sanitize_key( wp_unslash( $_REQUEST['method'] ) );
		$calendar = new ICalendarParser( Icalendar::CALENDAR_TYPE_EVENTS );

		switch ( $method ) {
			case 'kronos-print-annual-calendar':
				if ( ! isset( $_REQUEST['year'] ) ) {
					wp_die( 'Invalid AJAX call' );
				}

				DisplayCalendar::print_annual_calendar( sanitize_key( wp_unslash( $_REQUEST['year'] ) ) );
				return;

			case 'kronos-load-calendar-month':
				if ( ! isset( $_REQUEST['month'] ) || ! isset( $_REQUEST['year'] ) ) {
					wp_die( 'Invalid AJAX call' );
				}

				$calendar->print_month_ajax(
					sanitize_key( wp_unslash( $_REQUEST['month'] ) ),
					sanitize_key( wp_unslash( $_REQUEST['year'] ) )
				);
				return;

			case 'kronos-print-event-details':
				if ( ! isset( $_REQUEST['id'] ) ) {
					wp_die( 'Invalid AJAX call' );
				}

				$calendar->print_event_details( sanitize_key( wp_unslash( $_REQUEST['id'] ) ) );
				return;
		}
	}
}
