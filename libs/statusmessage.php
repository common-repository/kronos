<?php
/**
 * File: statusmessage.php
 *
 * @since 2024-07-25
 * @license GPL-3.0-or-later
 *
 * @package kronos/Libs
 */

/**
 * Function to display status message box
 *
 * @param string $message   The message to display.
 * @param bool   $succeeded   Show if a success message (error message otherwise).
 *
 * @return void
 */
function kronos_show_message( string $message, bool $succeeded = true ) {
	echo '<div class="notice notice-' . ( $succeeded ? 'success' : 'error' ) . '" style="padding: 5px 10px;">';
	echo nl2br( esc_html( $message ) );
	echo '</div>';
}
