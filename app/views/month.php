<?php
/**
 * File: month.php
 *
 * @since 2024-08-06
 * @license GPL-3.0-or-later
 *
 * @package kronos/Views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! isset( $year ) || ! isset( $month ) ) {
	wp_die( 'Invalid file call' );
}
?>
<div id="calendarContainer">
	<div id="calendar">
		<div class="box">
			<?php
			$next_month = 12 === intval( $month ) ? 1 : intval( $month ) + 1;
			$next_year  = 12 === intval( $month ) ? intval( $year ) + 1 : $year;
			$pre_month  = 1 === intval( $month ) ? 12 : intval( $month ) - 1;
			$pre_year   = 1 === intval( $month ) ? intval( $year ) - 1 : $year;
			?>
			<div class="kronos_header">
				<a class="prev"
					href="javascript:void(0);"
					onclick="kronos_load_calendar(<?php echo esc_html( $pre_month ); ?> , <?php echo esc_html( $pre_year ); ?>);">
						<< <?php echo esc_html__( 'Previous month', 'kronos' ); ?>
				</a>
				<span class="title"><?php echo esc_html( $month_labels[ (int) wp_date( 'm', strtotime( $year . '-' . $month . '-1' ) ) ] ); ?>
					<?php echo esc_html( wp_date( 'Y', strtotime( $year . '-' . $month . '-1' ) ) ); ?>
				</span>
				<a class="next"
					href="javascript:void(0);"
					onclick="kronos_load_calendar(<?php echo esc_html( $next_month ); ?> , <?php echo esc_html( $next_year ); ?>);">
					<?php echo esc_html__( 'Next month', 'kronos' ); ?> >>
				</a>
				</div>

		</div>
		<div class="box-content">
			<?php
			$days_of_week = array( 'Mon', 'Die', 'Mit', 'Don', 'Fre', 'Sam', 'Son' );

			$first_of_month = mktime( 0, 0, 0, $month, 1, $year );

			$number_days = wp_date( 't', $first_of_month );

			$date_components = getdate( $first_of_month );
			$day_of_week     = $date_components['wday'];

			if ( 0 === $day_of_week ) {
				$day_of_week = 6;
			} else {
				--$day_of_week;
			}

			?>
			<table id="calendar_table" >
				<tr>
					<?php

					foreach ( $days_of_week as $day ) {
						?>
							<th><?php echo esc_html( $day ); ?></th>
						<?php
					}

					?>
					</tr><tr>
						<?php
						if ( $day_of_week > 0 ) {
							for ( $k = 0; $k < $day_of_week; $k++ ) {
								?>
							<td></td>
								<?php
							}
						}

						$current_day = 1;
						while ( $current_day <= $number_days ) {
							if ( 7 === $day_of_week ) {
								$day_of_week = 0;
								?>
							</tr><tr>
								<?php
							}

							?>
							<td>
							<?php
							echo esc_html( $current_day );
							$events = $calendar->get_events_for_date( DateTime::createFromFormat( 'Y-m-d', $year . '-' . $month . '-' . $current_day ) );
							foreach ( $events as $event ) {
								?>
								<br />
								<label onclick="kronos_show_event_details(event, <?php echo esc_html( $event->id ); ?>)"
										class="<?php echo esc_html( $event->css_class ); ?>">
									<?php echo esc_html( $event->time ); ?>
									<?php echo esc_html__( "o'clock", 'kronos' ); ?>:<br />
									<?php echo esc_html( $event->name ); ?>
								</label>
								<?php
							}
							?>
							</td>
							<?php

							++$current_day;
							++$day_of_week;
						}

						if ( 7 !== $day_of_week ) {
							$remaining_days = 7 - $day_of_week;
							for ( $i = 0; $i < $remaining_days; $i++ ) {
								?>
							<td></td>
								<?php
							}
						}

						?>
				</tr>
			</table>
			<a href="#" onclick="kronos_load_ajax_nw('kronos-print-annual-calendar','year=<?php echo esc_html( $year ); ?>');" class="button button-primary">
				Drucken (<?php echo esc_html( $year ); ?>)
			</a>
		</div>

		<div id="kronos-hider" onclick="kronos_hide();"></div>
		<div id="kronos-event-infobox">
			<div id="kronos-event-infobox-header">
				<span id="kronos-event-infobox-header-text">
					<?php echo esc_html__( 'Event details', 'kronos' ); ?>
				</span>
			</div>
			<div id="kronos-event-infobox-content"></div>
		</div>