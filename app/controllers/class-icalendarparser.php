<?php
/**
 * File: class-icalendarparser.php
 *
 * @since 2024-08-06
 * @license GPL-3.0-or-later
 *
 * @package kronos/Controllers
 */

namespace kronos\App\Controllers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Calendar;
use ICal\ICal;
use kronos\App\Models\ICSCalendar;
use kronos\App\Requests\Icalendar;


/**
 * Class ICalendarParser
 *
 * This class is responsible for parsing and printing calendar information.
 *
 * @package kronos\App\Controllers
 */
class ICalendarParser {

	/**
	 * Prints the calendar for the specified month and year.
	 *
	 * @param int $month The month to print (1-12).
	 * @param int $year The year to print.
	 * @return string The printed calendar HTML.
	 */
	public function print_calendar_month( int $month, int $year ) {
		$content = $this->render( $month, $year );
		return $content;
	}

	/**
	 * Prints the calendar for the specified month and year using AJAX.
	 *
	 * @param int $month The month to print (1-12).
	 * @param int $year The year to print.
	 */
	public function print_month_ajax( $month, $year ) {
		$this->render( $month, $year );
	}

	/**
	 * Renders the calendar for the specified month and year.
	 *
	 * @param int $month The month to render (1-12).
	 * @param int $year The year to render.
	 * @return string The rendered calendar HTML.
	 */
	private function render( $month, $year ) {
		if ( null === $month || null === $year ) {
			wp_die( 'An internal error occured.' );
		}

		$ics_content = Icalendar::get_calendar( 1 );
		$calendar    = new ICSCalendar( $ics_content );

		$month_labels = array( '', 'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember' );

		require KRONOS_TEMPLATE_DIR . '/month.php';
		return '';
	}

	/**
	 * Print the details of an event.
	 *
	 * This method retrieves the calendar content, creates a calendar object,
	 * and fetches the event details with the provided ID. If the event is not
	 * found, it displays an error message and terminates the script.
	 *
	 * @param int $id The ID of the event to print the details for.
	 *
	 * @return void
	 */
	public function print_event_details( int $id ) {
		$ics_content = Icalendar::get_calendar( Icalendar::CALENDAR_TYPE_EVENTS );
		$calendar    = new ICSCalendar( $ics_content );
		$event       = $calendar->get_event( $id );
		if ( null === $event ) {
			wp_die( 'Invalid AJAX call' );
		}

		require KRONOS_TEMPLATE_DIR . '/details.php';
	}

	/**
	 * Prints the calendar year.
	 *
	 * @param int $year The year to print the calendar for.
	 *
	 * @return void
	 */
	public function print_calendar_year( int $year ) {
		if ( null === $year ) {
			wp_die( 'An internal error occured.' );
		}

		require KRONOS_TEMPLATE_DIR . '/year.php';
		exit;
	}
}
