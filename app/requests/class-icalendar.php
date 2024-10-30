<?php
/**
 * File: class-icalendar.php
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
 * Icalendar Class
 *
 * This class provides methods to retrieve calendars of specific types.
 *
 * @since 2024-08-06
 * @package kronos/Requests
 */
class Icalendar {

	public const CALENDAR_TYPE_BACKGROUND = 0;

	public const CALENDAR_TYPE_EVENTS = 1;

	public const CALENDAR_TYPE_HOLIDAYS = 2;

	/**
	 * Retrieves the calendar data based on the provided calendar type.
	 *
	 * @param int  $caltype The calendar type to retrieve. Valid values are CALENDAR_TYPE_BACKGROUND or CALENDAR_TYPE_EVENTS.
	 * @param ?int $year    The year the calendar needs to be loaded.
	 *
	 * @return string|false The calendar data as a string if it exists, otherwise false.
	 */
	public static function get_calendar( int $caltype, ?int $year = null ): string|false {
		if (
			self::CALENDAR_TYPE_HOLIDAYS !== $caltype &&
			self::CALENDAR_TYPE_BACKGROUND !== $caltype &&
			self::CALENDAR_TYPE_EVENTS !== $caltype
		) {
			wp_die( 'Invalid calendar type called' );
		}

		$transient = 'kronos_cal_' . $caltype;
		if ( null !== $year ) {
			$transient .= '_' . $year;
		}

		$data = get_transient( $transient );
		$data = false;
		if ( false === $data ) {
			ICSInterface::download_from_remote( $caltype, $year );
			return get_transient( $transient );
		}

		return $data;
	}
}
