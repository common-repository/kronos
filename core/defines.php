<?php
/**
 * File: defines.php
 *
 * @since 2024-07-21
 * @license GPL-3.0-or-later
 *
 * @package kronos/Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'KRONOS_PLUGIN_SLUG', 'kronos' );
define( 'KRONOS_PLUGIN_STARTUP_FILE', WP_PLUGIN_DIR . '/' . KRONOS_PLUGIN_SLUG . '/' . KRONOS_PLUGIN_SLUG . '.php' );

define( 'KRONOS_PLUGIN_DIR', plugin_dir_path( KRONOS_PLUGIN_STARTUP_FILE ) );
define( 'KRONOS_PLUGIN_URL', plugin_dir_url( KRONOS_PLUGIN_STARTUP_FILE ) );
define( 'KRONOS_TEMPLATE_DIR', KRONOS_PLUGIN_DIR . '/app/views/' );

define( 'KRONOS_WP_FS_CHMOD_FILE', ( fileperms( ABSPATH . 'index.php' ) & 0777 | 0644 ) );
define( 'KRONOS_WP_FS_CHMOD_DIRECTORY', ( fileperms( ABSPATH . 'index.php' ) & 0777 | 0755 ) );

define( 'KRONOS_VACTION_SERVER', 'https://repos.contelli.de/calendar/' );
