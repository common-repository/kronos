<?php
/**
 * File: class-icscalendar.php
 *
 * @since 2024-08-06
 * @license GPL-3.0-or-later
 *
 * @package kronos/Models
 */

namespace kronos\App\Models;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use DateTime;

/**
 * Class ICSCalendar
 *
 * Represents an ICSCalendar object that can parse iCalendar data and provide event information.
 */
class ICSCalendar {

	/**
	 * Contains parsed events
	 *
	 * @var array $events An array to store event data
	 */
	private $events = array();

	/**
	 * Determines if the background calendar feature is enabled or disabled
	 *
	 * @var bool $background_calendar Determines if the background calendar feature is enabled or disabled.
	 */
	private $background_calendar = false;


	/**
	 * Constructs a new instance of the class.
	 *
	 * @param string $ics_content The ICS content to parse.
	 * @param bool   $background_calendar Optional. Whether the calendar is a background calendar. Defaults to false.
	 *
	 * @return void
	 */
	public function __construct( $ics_content, $background_calendar = false ) {
		$this->background_calendar = $background_calendar;
		$this->parse_ics( $ics_content );
	}

	/**
	 * Retrieves an event by its ID.
	 *
	 * @param int $id The ID of the event to retrieve.
	 * @return \stdClass|null The event object if found, or null if not found.
	 */
	public function get_event( int $id ): ?\stdClass {
		foreach ( $this->events as $event ) {
			if ( $event->id === $id ) {
				return $event;
			}
		}

		return null;
	}

	/**
	 * Parses the ICS content and extracts event information.
	 *
	 * @param string $ics_content The ICS content to be parsed.
	 * @return void
	 */
	private function parse_ics( $ics_content ) {
		$id    = 1;
		$lines = explode( "\n", $ics_content );
		$event = null;
		foreach ( $lines as $line ) {
			$line = trim( $line );
			$line = trim( $line );
			if ( 'BEGIN:VEVENT' === $line ) {
				$event = new \stdClass();
			} elseif ( 'END:VEVENT' === $line ) {
				if ( $event ) {
					if ( ! isset( $event->url ) ) {
						$event->url = '';
					}

					if ( ! isset( $event->description ) ) {
						$event->description = '';
					}

					if ( ! isset( $event->location ) ) {
						$event->location = '';
					}

					$event->id      = $id;
					$this->events[] = $event;
					$event          = null;
					++$id;
				}
			} elseif ( $event ) {
				switch ( true ) {
					case str_starts_with( $line, 'SUMMARY:' ):
						$event->name = substr( $line, 8 );
						break;
					case str_starts_with( $line, 'DTSTART;' ):
						$value = explode( 'VALUE=DATE:', $line );
						if ( ! isset( $value[1] ) ) {
							$tmp   = $value[0];
							$value = explode( ':', $tmp );
						}
						$event->begin = $this->parse_date( $value[1] );
						$event->time  = substr( $event->begin, 11, 5 );
						break;
					case str_starts_with( $line, 'DTEND' ):
						$value = explode( 'VALUE=DATE:', $line );
						if ( ! isset( $value[1] ) ) {
							$tmp   = $value[0];
							$value = explode( ':', $tmp );
						}

						$event->end = $this->parse_date( $value[1] );
						if ( $this->background_calendar ) {
							$interval   = date_interval_create_from_date_string( '1 day' );
							$event->end = date_sub( DateTime::createFromFormat( 'Y-m-d H:i:s', $event->end ), $interval )->format( 'Y-m-d ' );
						}

						break;
					case str_starts_with( $line, 'CATEGORIES' ):
						$event->category  = substr( $line, 11 );
						$event->css_class = $this->get_category_css_class( $event->category );

						break;

					case str_starts_with( $line, 'LOCATION' ):
						$event->location = substr( $line, 9 );
						break;

					case str_starts_with( $line, 'URL' ):
						$event->url = str_replace( ' ', '', substr( $line, 4 ) );
						break;

					case str_starts_with( $line, 'DESCRIPTION' ):
						$event->description = substr( $line, 12 );
						break;

					case str_starts_with( $line, 'RRULE:' ):
						$event->rrule = $this->parse_rrule( substr( $line, 6 ) );
						break;

				}
			}
		}
	}

	/**
	 * Parses a given RRULE string and returns an associative array.
	 *
	 * @param string $rrule_string The RRULE string to be parsed.
	 * @return array An associative array representing the parsed RRULE.
	 */
	private function parse_rrule( string $rrule_string ) {
		$rrule = array();
		$parts = explode( ';', $rrule_string );
		foreach ( $parts as $part ) {
			list($key, $value) = explode( '=', $part );
			$rrule[ $key ]     = $value;
		}
		return $rrule;
	}

	/**
	 * Retrieves the CSS class for a given event category.
	 *
	 * @param string $event_category The event category to retrieve CSS class for.
	 * @return string The CSS class for the event category. Returns 'kronos_cal_event_{category}' if a matching category is found,
	 *               otherwise returns 'kronos_cal_event_grey'.
	 */
	private function get_category_css_class( string $event_category ): string {
		foreach ( get_option( 'kronos_calendar_categories', array() ) as $category => $description ) {
			if ( strtolower( $event_category ) === strtolower( $description ) ) {
				return 'kronos_cal_event_' . $category;
			}
		}

		return 'kronos_cal_event_grey';
	}

	/**
	 * Parses a date string and returns it in the format 'Y-m-d H:i:s'.
	 *
	 * @param string $date_string The date string to be parsed.
	 *
	 * @return string|null The parsed date string in the format 'Y-m-d H:i:s', or null if the string could not be parsed.
	 */
	private function parse_date( string $date_string ) {

		$date_time = \DateTime::createFromFormat( 'Ymd\THis', $date_string );
		if ( ! $date_time ) {
			$date_time = \DateTime::createFromFormat( 'Ymd', $date_string );
		}

		return $date_time ? $date_time->format( 'Y-m-d H:i:s' ) : null;
	}


	/**
	 * Retrieves events for a given date.
	 *
	 * @param \DateTime $date The date for which to retrieve events.
	 * @return array The events for the given date.
	 */
	public function get_events_for_date( \DateTime $date ) {
		$events_for_date = array();
		foreach ( $this->events as $event ) {
			$event->begin = substr( $event->begin, 0, 10 );
			$event->end   = substr( $event->end, 0, 10 );

			$date_begin = \DateTime::createFromFormat( 'Y-m-d', $event->begin );
			$date_end   = \DateTime::createFromFormat( 'Y-m-d', $event->end );
			if ( $date->getTimestamp() === $date_begin->getTimestamp() ) {
				$events_for_date[] = $event;
			} elseif ( $date_begin->getTimestamp() < $date->getTimestamp() && $date_end->getTimestamp() >= $date->getTimestamp() ) {
				$events_for_date[] = $event;
			} elseif ( isset( $event->rrule ) ) {
				$recurrences = $this->get_recurrences( $event, $date );
				foreach ( $recurrences as $recurrence ) {
					if ( $recurrence->format( 'Y-m-d' ) === $date->format( 'Y-m-d' ) ) {
						$events_for_date[] = $event;
					}
				}
			}
		}
		return $events_for_date;
	}

	/**
	 * Get the occurrences of an event based on its recurrence rule up until a given date.
	 *
	 * @param object   $event The event object containing the recurrence rule.
	 * @param DateTime $date The date to get the occurrences up until.
	 * @return array An array of DateTime objects representing the occurrences.
	 */
	private function get_recurrences( $event, $date ) {
		$recurrences  = array();
		$start_date   = new DateTime( substr( $event->begin, 0, 10 ) );
		$current_date = clone $start_date;
		$rrule        = $event->rrule;
		$until_date   = isset( $rrule['UNTIL'] ) ? new DateTime( $rrule['UNTIL'] ) : null;

		if ( isset( $rrule['FREQ'] ) ) {
			$interval = isset( $rrule['INTERVAL'] ) ? $rrule['INTERVAL'] : 1;
			while ( $current_date <= $date ) {
				if ( $until_date && $current_date > $until_date ) {
					break;
				}

					$recurrences[] = clone $current_date;
				switch ( $rrule['FREQ'] ) {
					case 'DAILY':
						$current_date->modify( "+{$interval} day" );
						break;
					case 'WEEKLY':
						$current_date->modify( "+{$interval} week" );
						break;
					case 'MONTHLY':
						$current_date->modify( "+{$interval} month" );
						break;
					case 'YEARLY':
						$current_date->modify( "+{$interval} year" );
						break;
				}
			}
		}

		return $recurrences;
	}
}
