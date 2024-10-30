<?php
/**
 * File: abbreviation-edit-form.php
 *
 * @since 2024-09-30
 * @license GPL-3.0-or-later
 *
 * @package kronos/Views
 */

use kronos\App\Models\Abbreviation;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$abbreviation = null;

if ( isset( $_REQUEST['abbreviation-id'] ) && isset( $_REQUEST['kronos_form_nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_REQUEST['kronos_form_nonce'] ) ) ) ) {
	$abbreviation_id = (int) sanitize_key( wp_unslash( $_REQUEST['abbreviation-id'] ) );
	$abbreviation    = Abbreviation::get_by_id( $abbreviation_id );
}

if ( null === $abbreviation ) {
	wp_die( 'Invalid script call' );
}

?>
<h2>
	<?php echo esc_html__( 'Edit abbreviation for annual calendar', 'kronos' ); ?>
</h2>

<form method="post" action="<?php echo esc_url( admin_url( 'options-general.php?page=edit-kronos-settings&tab=tab-text' ) ); ?>">
	<input type="hidden" name="update_abbreviation" value="1" />
	<input type="hidden" name="abbreviation-id" value="<?php echo esc_html( $abbreviation->id ); ?>" />
	<input type="hidden" name="nonce" value="<?php echo esc_html( wp_create_nonce() ); ?>" />
	<p>

		<?php echo esc_html__( 'Event name', 'kronos' ); ?><br />
		<input type="text" name="event-name" value="<?php echo esc_html( $abbreviation->event ); ?>" /><br /><br />

		<?php echo esc_html__( 'Event abbreviation', 'kronos' ); ?><br />
		<input type="text" name="event-abbreviation"value="<?php echo esc_html( $abbreviation->abbreviation ); ?>" maxlength="10" /><br /><br />
		<input type="submit" class="button-primary" value="<?php echo esc_html__( 'Save', 'kronos' ); ?>" />
	</p>
</form>
