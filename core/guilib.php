<?php
/**
 * File: guilib.php
 *
 * @since 2024-07-21
 * @license GPL-3.0-or-later
 *
 * @package kronos/Core
 */

use kronos\App\Routers\AjaxRouter;

/**
 * Loads Stylesheets and JavaScript to page
 *
 * @return void
 */
function kronos_enqueue_custom_scripts() {
	$plugin_data = get_plugin_data( KRONOS_PLUGIN_STARTUP_FILE );

	wp_enqueue_style(
		'kronos-dashboard-style',
		KRONOS_PLUGIN_URL . '/assets/stylesheets/dashboard.css',
		array(),
		$plugin_data['Version']
	);

	wp_enqueue_script(
		'kronos-ajax',
		KRONOS_PLUGIN_URL . '/assets/javascripts/ajax.js',
		array(),
		$plugin_data['Version'],
		array( 'in_footer' => false )
	);
}

/**
 * Initiates the Ajax component
 *
 * @return void
 */
function kronos_load_ajax_content() {
	AjaxRouter::execute();
	exit;
}

/**
 * Initialize plugin for localization.
 *
 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
 *
 * Locales found in:
 *     - WP_LANG_DIR/rank-math/kronos-LOCALE.mo
 *     - WP_LANG_DIR/plugins/kronos-LOCALE.mo
 */
function kronos_localization_setup() {
	$locale = get_user_locale();

	$locale = apply_filters( 'plugin_locale', $locale, KRONOS_PLUGIN_SLUG ); // phpcs:ignore

	unload_textdomain( KRONOS_PLUGIN_SLUG );
	if ( false === load_textdomain( KRONOS_PLUGIN_SLUG, WP_LANG_DIR . '/plugins/' . KRONOS_PLUGIN_SLUG . '-' . $locale . '.mo' ) ) {
		load_textdomain( KRONOS_PLUGIN_SLUG, WP_LANG_DIR . '/' . KRONOS_PLUGIN_SLUG . '/' . KRONOS_PLUGIN_SLUG . '-' . $locale . '.mo' );
	}

	load_textdomain( KRONOS_PLUGIN_SLUG, KRONOS_PLUGIN_DIR . '/languages/' . KRONOS_PLUGIN_SLUG . '-' . $locale . '.mo', $locale );
}
