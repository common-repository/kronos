<?php
/**
 * File: class-setup.php
 *
 * @since 2024-08-09
 * @license GPL-3.0-or-later
 *
 * @package kronos/Controllers
 */

namespace kronos\App\Controllers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Setup
 *
 * Provides functionality for rendering and printing settings pages for the Kronos plugin.
 */
class Setup {

	/**
	 * Renders the settings page based on the active tab and mode.
	 *
	 * @param string      $active_tab The active tab on the settings page.
	 * @param string|null $mode The mode for the text resource form. Default is null.
	 * @return void
	 */
	public static function settings_page( string $active_tab, ?string $mode = null ) {
		require KRONOS_TEMPLATE_DIR . '/settings-tabs.php';

		switch ( $active_tab ) {
			case 'tab-common':
				self::print_form();
				break;

			case 'tab-text':
				self::print_text_ressource_form( $mode );
				break;

		}
	}

	/**
	 * Prints the text resource form based on the provided mode.
	 *
	 * @param string|null $mode The mode for the text resource form. Default is null.
	 * @return void
	 */
	public static function print_text_ressource_form( ?string $mode = null ) {
		if ( null === $mode || 'delete-abbreviation' === $mode ) {
			$mode = 'index';
		}
		require_once KRONOS_TEMPLATE_DIR . '/abbreviation-' . $mode . '.php';
	}

	/**
	 * Prints the form for the Kronos plugin.
	 *
	 * This method is responsible for printing the form for the Kronos plugin. It retrieves
	 * the plugin data, calendar categories, calendar URL, vacation URL, and enqueues the
	 * necessary stylesheets. Lastly, it includes the setup.php template file.
	 *
	 * @return void
	 */
	public static function print_form() {
		$plugin_data = get_plugin_data( KRONOS_PLUGIN_STARTUP_FILE );

		$categories = get_option(
			'kronos_calendar_categories',
			array(
				'yellow'  => '',
				'blue'    => '',
				'red'     => '',
				'green'   => '',
				'fuchsia' => '',
				'orange'  => '',
			)
		);

		$calendar_url = get_option( 'kronos_calendar_url', '' );

		wp_enqueue_style(
			'kronos-css',
			KRONOS_PLUGIN_URL . '/assets/stylesheets/frontend-calendar.css',
			array(),
			$plugin_data['Version']
		);
		require KRONOS_TEMPLATE_DIR . '/setup.php';
	}
}
