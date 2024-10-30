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

?>

<form method="post" action="<?php echo esc_url( admin_url( 'options-general.php?page=edit-kronos-settings' ) ); ?>">
	<input type="hidden" name="save_common" value="1" />
	<input type="hidden" name="nonce" value="<?php echo esc_html( wp_create_nonce() ); ?>" />
	<table class="form-table" style="width: 80%;">
		<tr>
			<th>
				<?php echo esc_html__( 'Calendar URL', 'kronos' ); ?>:</th>
			<td colspan="3"><input style="width: 100%;" type="text" name="calendar_url" value="<?php echo esc_url( $calendar_url ); ?>" /></td>
		</tr>

		<tr>
			<th>
				<?php echo esc_html__( 'State', 'kronos' ); ?>:</th>
			<td colspan="3">
				<select name="state" style="width: 100%;">
					<option
						<?php
						if ( get_option( 'kronos_state', '' ) === 'sachsen-anhalt' ) {
							echo esc_html( ' selected' ); }
						?>
							value="sachsen-anhalt">Sachsen-Anhalt</option>
					<option
						<?php
						if ( get_option( 'kronos_state', '' ) === 'sachsen' ) {
							echo esc_html( ' selected' ); }
						?>
							value="sachsen">Sachsen</option>
				</select>
			</td>
		</tr>

		<tr>
			<th style="width: 200px" scope="row"></th>
			<th></th>
			<td style="width: 50px;"></td>
			<td></td>
		</tr>
		<tr>
			<th style="width: 210px;" scope="row"><?php echo esc_html__( 'Yellow', 'kronos' ); ?>:</th>
			<th><input type="text" name="category[yellow]" value="<?php echo esc_html( $categories['yellow'] ); ?>" /></th>
			<td style="width: 50px;" class="kronos_cal_event_yellow"></td>

		</tr>

		<tr>
			<th style="width: 210px;" scope="row"><?php echo esc_html__( 'Blue', 'kronos' ); ?>:</th>
			<th><input type="text" name="category[blue]" value="<?php echo esc_html( $categories['blue'] ); ?>" /></th>
			<td class="kronos_cal_event_blue"></td>
		</tr>

		<tr>
			<th style="width: 210px;" scope="row"><?php echo esc_html__( 'Red', 'kronos' ); ?>:</th>
			<th><input type="text" name="category[red]" value="<?php echo esc_html( $categories['red'] ); ?>" /></th>
			<td class="kronos_cal_event_red"></td>
		</tr>

		<tr>
			<th style="width: 210px;" scope="row"><?php echo esc_html__( 'Green', 'kronos' ); ?>:</th>
			<th><input type="text" name="category[green]" value="<?php echo esc_html( $categories['green'] ); ?>" /></th>
			<td class="kronos_cal_event_green"></td>
		</tr>

		<tr>
			<th style="width: 210px;" scope="row"><?php echo esc_html__( 'Fuchsia', 'kronos' ); ?>:</th>
			<th><input type="text" name="category[fuchsia]" value="<?php echo esc_html( $categories['fuchsia'] ); ?>" /></th>
			<td class="kronos_cal_event_fuchsia"></td>
		</tr>

		<tr>
			<th style="width: 210px;" scope="row"><?php echo esc_html__( 'Orange', 'kronos' ); ?>:</th>
			<th><input type="text" name="category[orange]" value="<?php echo esc_html( $categories['orange'] ); ?>" /></th>
			<td class="kronos_cal_event_orange"></td>
		</tr>
		<tr>
			<th style="width: 210px;" scope="row"><?php echo esc_html__( 'Grey', 'kronos' ); ?>:</th>
			<th>Standard</th>
			<td class="kronos_cal_event_grey"></td>
		</tr>
	</table>

	<input type="submit" class="button" value="<?php echo esc_html__( 'Save', 'kronose' ); ?>">
</form>

