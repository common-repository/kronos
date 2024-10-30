<?php
/**
 * File class-displaycalendar.php
 *
 * DESCRIPTION
 *
 * @since 2024-09-25
 * @license GPL-3.0-or-later
 *
 * @package kronos/Controllers
 */

namespace kronos\App\Controllers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use kronos\App\Requests\Icalendar;

/**
 * The DisplayCalendar class is responsible for printing and executing the calendar display logic.
 */
class DisplayCalendar {

	/**
	 * Prints the annual calendar for the given year.
	 *
	 * @param string $year The year for which to print the calendar.
	 *
	 * @return void
	 */
	public static function print_annual_calendar( string $year ) {
		$calendar = new ICalendarParser( Icalendar::CALENDAR_TYPE_EVENTS );
		$calendar->print_calendar_year( $year );
	}

	/**
	 * Executes the calendar display logic and returns the generated output.
	 *
	 * @return string The HTML output of the calendar.
	 */
	public static function execute() {
		$calendar = new ICalendarParser( Icalendar::CALENDAR_TYPE_EVENTS );

		ob_start();
		$plugin_data = get_plugin_data( KRONOS_PLUGIN_STARTUP_FILE );

		wp_enqueue_style(
			'kronos-css',
			KRONOS_PLUGIN_URL . '/assets/stylesheets/frontend-calendar.css',
			array(),
			$plugin_data['Version']
		);

		wp_enqueue_script(
			'kronos-ajax',
			KRONOS_PLUGIN_URL . '/assets/javascripts/ajax.js',
			array(),
			$plugin_data['Version'],
			array( 'in_footer' => false )
		);

		wp_localize_script(
			'kronos-ajax',
			'data_stock',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => esc_html( wp_create_nonce() ),
			)
		);

		$calendar->print_calendar_month( wp_date( 'm' ), wp_date( 'Y' ) );
		return ob_get_clean();
	}
}
