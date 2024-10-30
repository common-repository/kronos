<?php
/**
 * File: uninstall.php
 *
 *
 * @since 2024-09-30
 * @license GPL-3.0-or-later
 *
 * @package kronos/Core
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

global $wpdb;

$options = array(
    'kronos_calendar_url',
    'kronos_state',
    'kronos_calendar_categories',
    'kronos_abbreviations',
);

foreach ( $options as $option ) {
    delete_option( $option );
}
