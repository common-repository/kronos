<?php
/**
 * File: abbreviation-new-form.php
 *
 * @since 2024-09-30
 * @license GPL-3.0-or-later
 *
 * @package kronos/Views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<h2>
	<?php echo esc_html__( 'New abbreviation for annual calendar', 'kronos' ); ?>
</h2>

<form method="post" action="<?php echo esc_url( admin_url( 'options-general.php?page=edit-kronos-settings&tab=tab-text' ) ); ?>">
	<input type="hidden" name="save_new_abbreviation" value="1" />
	<input type="hidden" name="nonce" value="<?php echo esc_html( wp_create_nonce() ); ?>" />
	<p>

	<?php echo esc_html__( 'Event name', 'kronos' ); ?><br />
	<input type="text" name="event-name" /><br /><br />

	<?php echo esc_html__( 'Event abbreviation', 'kronos' ); ?><br />
	<input type="text" name="event-abbreviation" maxlength="10" /><br /><br />
		<input type="submit" class="button-primary" value="<?php echo esc_html__( 'Save', 'kronos' ); ?>" />
	</p>
</form>
