<?php
/**
 * File: class-icsinterface.php
 *
 * @since 2024-08-06
 * @license GPL-3.0-or-later
 *
 * @package kronos/Requests
 */

namespace kronos\App\Requests;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class ICSInterface
 *
 * This class provides the functionality to download an iCalendar file from a remote URL.
 *
 * @since 2024-08-06
 * @package kronos\Requests
 */
class ICSInterface {

	/**
	 * Download calendar data from a remote URL and store it in a transient.
	 *
	 * @param int  $caltype The type of calendar to download. Valid values are Icalendar::CALENDAR_TYPE_BACKGROUND and Icalendar::CALENDAR_TYPE_EVENTS.
	 * @param ?int $year The year the calendar needs to be loaded.
	 *
	 * @return void
	 */
	public static function download_from_remote( int $caltype, ?int $year = null ) {
		if (
			Icalendar::CALENDAR_TYPE_HOLIDAYS !== $caltype &&
			Icalendar::CALENDAR_TYPE_BACKGROUND !== $caltype &&
			Icalendar::CALENDAR_TYPE_EVENTS !== $caltype
		) {
			wp_die( 'Invalid calendar type called' );
		}

		$state = get_option( 'kronos_state', '' );

		if ( Icalendar::CALENDAR_TYPE_EVENTS === $caltype ) {

			$calendar_url = get_option( 'kronos_calendar_url', null );
			if ( null === $calendar_url ) {
				return;
			}
			$response = wp_remote_get( $calendar_url );
			if ( is_a( $response, 'WP_Error' ) ) {
				die( 'Fehler' );
			}

			if ( is_array( $response ) && isset( $response['body'] ) ) {
				delete_transient( 'kronos_update_cal_' . $caltype );
				delete_transient( 'kronos_cal_' . $caltype );
				set_transient( 'kronos_cal_' . $caltype, $response['body'], 2 * DAY_IN_SECONDS );
				set_transient( 'kronos_update_cal_' . $caltype, time(), 2 * DAY_IN_SECONDS );
			}
		} else {
			$type         = Icalendar::CALENDAR_TYPE_BACKGROUND === $caltype ? 'ferien' : 'feiertage';
			$calendar_url = KRONOS_VACTION_SERVER . '/' . $type . '_' . $state . '_' . $year . '.ics';

			$response = wp_remote_get( $calendar_url );

			if ( is_a( $response, 'WP_Error' ) ) {
				die( 'Fehler' );
			}

			if ( is_array( $response ) && isset( $response['body'] ) ) {
				delete_transient( 'kronos_update_cal_' . $caltype . '_' . $year );
				delete_transient( 'kronos_cal_' . $caltype . '_' . $year );
				set_transient( 'kronos_cal_' . $caltype . '_' . $year, $response['body'], 90 * DAY_IN_SECONDS );
				set_transient( 'kronos_update_cal_' . $caltype . '_' . $year, time(), 90 * DAY_IN_SECONDS );
			}
		}
	}
}
