<?php
/**
 * File setup.php
 *
 * Basic site setup for the kronos plugin.
 *
 * This file includes various setup functions for the plugin,
 * including object setup, page setup, role setup, and menu setup.
 *
 * @package kronos/Setup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once KRONOS_PLUGIN_DIR . '/setup/setup-page.php';
require_once KRONOS_PLUGIN_DIR . '/setup/setup-menus.php';

/**
 * Basic setup calls
 *
 * @return void
 */
function kronos_admin_setup() {
	kronos_setup_page();
}
