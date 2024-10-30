<?php
/**
 * File: setup.php
 *
 * @since 2024-08-09
 * @license GPL-3.0-or-later
 *
 * @package kronos/Views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use kronos\App\Models\Abbreviation;
use kronos\App\Models\ICSCalendar;
use kronos\App\Requests\Icalendar;


/**
 * Renders a calendar year view with vacation and holiday events highlighted.
 *
 * @param int $year The year to display the calendar for.
 * @return string The HTML representation of the calendar year view.
 */
function kronos_render_calendar_year( $year ) {

	$ics_content_vacation = Icalendar::get_calendar( Icalendar::CALENDAR_TYPE_BACKGROUND, $year );
	$calendar_vacation    = new ICSCalendar( $ics_content_vacation );

	$ics_content_holidays = Icalendar::get_calendar( Icalendar::CALENDAR_TYPE_HOLIDAYS, $year );
	$calendar_holidays    = new ICSCalendar( $ics_content_holidays, true );

	$ics_content_events = Icalendar::get_calendar( Icalendar::CALENDAR_TYPE_EVENTS );
	$calendar_events    = new ICSCalendar( $ics_content_events );

	$months       = array(
		1  => 'Januar',
		2  => 'Februar',
		3  => 'März',
		4  => 'April',
		5  => 'Mai',
		6  => 'Juni',
		7  => 'Juli',
		8  => 'August',
		9  => 'September',
		10 => 'Oktober',
		11 => 'November',
		12 => 'Dezember',
	);
	$days_of_week = array( 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So' );

	$background_sunday   = '#FCC844';
	$background_saturday = '#FFFA84';
	$background_holiday  = '#FFA3B2';
	$background_vacation = '#b4edce';

	$output  = '<html lang="de" ><head>    <meta charset="UTF-8"><style>
@page {
   size: 29.7cm 21cm;
   margin-top: 0cm;
   margin-bottom: 0cm;
   border: 1px solid blue;
}

.kronos_cal_event_red,
.kronos_cal_event_yellow,
.kronos_cal_event_blue,
.kronos_cal_event_green,
.kronos_cal_event_grey,
.kronos_cal_event_fuchsia,
.kronos_cal_event_orange
{
	font-weight: bold;


overflow: hidden;
text-overflow: ellipsis;
	display: block;
	cursor: pointer;


}

.kronos_cal_event_blue {
background-color: #1e88e5;
color: #FFFFFF;
}

.kronos_cal_event_green {
background-color: #00d084;
color: #000000;
}

.kronos_cal_event_red
{
.kronos_cal_event_entry;
background-color: #e91e63;
color: #ffffff;
}

.kronos_cal_event_yellow
{
.kronos_cal_event_entry;
background-color: #eeee88;
color: #000000;
}

.kronos_cal_event_orange
{
.kronos_cal_event_entry;
background-color: #ffa747;
color: #000000;
}


.kronos_cal_event_grey
{
.kronos_cal_event_entry;
background-color: #D5D5D5;
color: #000000;
}

.kronos_cal_event_fuchsia
{
.kronos_cal_event_entry;
background-color: #FF6EFF;
color: #000000;
}





/** overall width = width+padding-right**/
div#calendar ul.dates li{
margin:0px;
padding:0px;
padding-left: 2px;
padding-top: 10px;
vertical-align:bottom;
float:left;
list-style-type:none;
width:15%;
height:100px;
font-size:12pt;
background-color: #fff;
color:#000;
border-style: solid;
border-width:1px;
border-color: #add8e6;
}

:focus{
outline:none;
}

div.clear{
clear:both;
}

#calendar_table {
	border-collapse: collapse; border-spacing: 0;   table-layout: fixed;width: 100%;
}

#calendar_table th {
	text-align: left;
}

#calendar_table td {
	border-style: solid;
	border-width: 1px;
	border-color: #e0e0e0;
	height: 150px;
	vertical-align: top;
}




#kronos-hider {
	display: none;
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	height: 2048px;
	z-index: 1;
	background-color: rgba(0, 0, 0, 0.53);
}

#kronos-event-infobox
{
	display: none;
	position: absolute;
	top: 250px;
	left: 250px;
	min-width: 800px;
	background-color:#ffffff;
	z-index: 10;
	border-radius: 10px;
}

#kronos-event-infobox-header
{
	background-color: #FFCB04;
	border-radius: 10px 10px 0 0;
	padding: 5px;
	color: #1d4899;
	font-weight: bold;
	display: flex;
}


#kronos-event-infobox-content{
	padding: 10px;
}

@media screen and (max-width: 700px) {
	#kronos-event-infobox {
		left: 0;
		top: 25px;
	}
}

.kronos-page {
	border-style: solid;
	border-width: 1px;
	border-color: #c0c0c0;
	box-shadow: 10px 10px 10px #e0e0e0;
	width: 90%;
	border-radius: 10px;
	margin: 30px auto;
	padding: 5px;
	background-color: #ffffff;
}

.kronos-page h3 {
	background-color: #ffffff;
	border-style: solid;
	border-width: 1px;
	border-color: #e5e7eb;
	border-radius: 10px;
	padding: 0 60px 5px 20px;
	border-left-width: 40px;
	font-size: 12pt;
	position: relative;
	left: 25px;
	width: 40%;
	top: -30px;
}


.kronos-update-button {
	background-color: #ffffff !important;
	color: #0a4b78 !important;
}

.kronos-update-button:hover {
	color: #ffffff !important;
	background-color: #0a4b78 !important;
}





</style>

</head><body>';
	$output .= '<table style="border-spacing: 0px; font-size: 7pt;">';
	$output .= '<tr>';
	foreach ( $months as $month_num => $month_name ) {
		$output .= "<th colspan='3'>$month_name $year</th>";
	}
	$output .= '</tr><tr></tr>';

	$max_days = 31;
	for ( $day = 1; $day <= $max_days; $day++ ) {
		$output .= '<tr>';
		foreach ( $months as $month_num => $month_name ) {
			$date_string = sprintf( '%04d-%02d-%02d', $year, $month_num, $day );
			$date_time   = DateTime::createFromFormat( 'Y-m-d', $date_string );

			if ( $date_time && (string) $date_time->format( 'j' ) === (string) $day ) {
				$day_of_week = $days_of_week[ $date_time->format( 'N' ) - 1 ];
				$background  = '#ffffff';
				if ( 'So' === $day_of_week ) {
					$background = $background_sunday;
				} elseif ( 'Sa' === $day_of_week ) {
					$background = $background_saturday;
				} elseif ( kronos_is_holiday( $calendar_holidays, $date_string ) ) {
					$background = $background_holiday;
				} elseif ( kronos_is_vacation( $calendar_vacation, $date_string ) ) {
					$background = $background_vacation;
				}

				$output .= '<td style="font-weight: bold; height: 20px !important;border-style: solid none solid solid; border-width: 1px 0 1px 1px;
                            background-color: ' . esc_html( $background ) . '">' . esc_html( $day ) . '</td>' .
					'<td style="border-style: solid none solid none; border-width: 1px 0 1px 0;
                    background-color: ' . esc_html( $background ) . '">' . esc_html( $day_of_week ) . '</td>' .
					'<td style="hyphens: auto !important; max-width: 60px; border-style: solid solid solid none; border-width: 1px;
                            width: 55px; background-color: ' . esc_html( $background ) . '; font-size: 6pt;">';

					$entry = kronos_get_event_for_annual_calendar( $calendar_events, $date_string );
				foreach ( $entry as $event ) {
					$output .= '<label class="' . esc_html( $event['class'] ) . '">' . esc_Html( $event['name'] ) . '</label>';
				}

					$output .= '</td>';
			} else {
				$output .= '<td style="border-style: solid none solid solid; border-width: 1px;">
                </td>

                <td style="border-style: solid none solid none; border-width: 1px;">
                </td>

                <td style="border-style: solid solid solid none; border-width: 1px;">

                </td>';
			}
		}
		$output .= '</tr>';
	}
	$output .= '</table>';

	return $output;
}

/**
 * Checks if there are any holiday events in the given ICSCalendar for the specified date.
 *
 * @param ICSCalendar $source The ICSCalendar object that contains the holiday events.
 * @param string      $date The date to check for holiday events. It should be in the format 'Y-m-d'.
 * @return bool Returns true if there are any holiday events for the specified date, false otherwise.
 */
function kronos_is_holiday( ICSCalendar $source, string $date ): bool {
	return ( count( $source->get_events_for_date( DateTime::createFromFormat( 'Y-m-d', $date ) ) ) > 0 );}

/**
 * Checks if there are any vacation events in the given ICSCalendar for the specified date.
 *
 * @param ICSCalendar $source The ICSCalendar object that contains the vacation events.
 * @param string      $date The date to check for vacation events. It should be in the format 'Y-m-d'.
 * @return bool Returns true if there are any vacation events for the specified date, false otherwise.
 */
function kronos_is_vacation( ICSCalendar $source, string $date ): bool {
	return ( count( $source->get_events_for_date( DateTime::createFromFormat( 'Y-m-d', $date ) ) ) > 0 );
}

/**
 * Retrieve events for a specific date from an annual calendar.
 *
 * @param ICSCalendar $source The annual calendar object.
 * @param string      $date The date in 'Y-m-d' format.
 * @return array Returns an array of events for the specified date.
 */
function kronos_get_event_for_annual_calendar( ICSCalendar $source, string $date ): array {
	$events      = $source->get_events_for_date( DateTime::createFromFormat( 'Y-m-d', $date ) );
	$event_array = array();
	foreach ( $events as $event ) {
		$event_name = Abbreviation::get_for_event_name( $event->name );

		if ( strlen( $event_name ) > 14 ) {
			$event->name = substr( $event_name, 0, 11 ) . '...';
		}

		$event_array[] = array(
			'class' => $event->css_class,
			'name'  => $event_name,
		);
	}

	return $event_array;
}

kronos_create_pdf( kronos_render_calendar_year( $year ), 'kalender-' . $year . '.pdf', 'Landscape' );
