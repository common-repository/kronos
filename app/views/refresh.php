<?php
/**
 * File: refresh.php
 *
 * @since 2024-08-10
 * @license GPL-3.0-or-later
 *
 * @package kronos/Views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="kronos-page">
	<h3><?php echo esc_html__( 'Refresh calendar', 'kronos' ); ?></h3>
	<p>
		<?php echo esc_html__( 'The calendar is automatically updated every 48 hours. If there are important changes to the calendar, the update can also be initiated manually.', 'kronos' ); ?>
	</p>
	<p>
		<?php echo esc_html__( 'Last update', 'kronos' ); ?>:
		<?php
			$last_update = get_transient( 'kronos_update_cal_' . \kronos\App\Requests\Icalendar::CALENDAR_TYPE_EVENTS );
		if ( false === $last_update ) {
			echo esc_html__( 'N/A', 'kronos' );
		} else {
			echo esc_html( wp_date( 'd.m.Y H:i', $last_update ) );
		}
		?>
	</p>

	<p style="text-align: center;">
		<a href="<?php echo esc_url( admin_url( 'tools.php?page=refresh-kronos-calendar&kronos-update=true' ) ); ?>"
			class="button kronos-update-button"><?php echo esc_html__( 'Update now', 'kronos' ); ?></a>
	</p>
</div>

