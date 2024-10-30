<?php
/**
 * Contains the setup for menu structure
 *
 * @package kronos/Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use kronos\App\models\Event;

/**
 * Adds the menu structure in dashboard
 *
 * @return void
 */
function kronos_add_menu() {
	$_SESSION['kronos_nonce'] = esc_html( wp_create_nonce() );
	add_submenu_page(
		'options-general.php',
		__( 'kronos settings', 'kronos' ),
		__( 'kronos settings', 'kronos' ),
		'manage_options',
		'edit-kronos-settings',
		array( 'kronos\App\Routers\FrontendRouter', 'execute' ),
	);

	if ( null !== get_option( 'kronos_calendar_url', null ) ) {
		add_submenu_page(
			'tools.php',
			__( 'Refresh calendar', 'kronos' ),
			__( 'Refresh calendar', 'kronos' ),
			'manage_options',
			'refresh-kronos-calendar',
			array( 'kronos\App\Routers\FrontendRouter', 'execute' ),
		);
	}
}
