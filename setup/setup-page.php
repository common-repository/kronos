<?php
/**
 * File: setup-page.php
 *
 * @since 2024-07-21
 * @license GPL-3.0-or-later
 *
 * @package kronos/Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Set up the "Available events" page.
 * If the page doesn't exist, it creates a new page with the title "Available events" and a specific content.
 * If the permalink structure is empty, it updates the permalink structure and flushes the rewrite rules.
 *
 * @return void
 */
function kronos_setup_page() {
	$page_name       = __( 'Calendar', 'kronos' );
	$page_name_check = str_replace( ' ', '-', $page_name );

	$page_name_check = str_replace( 'ü', 'ue', $page_name_check );
	$page_exists     = get_page_by_path( $page_name_check );

	if ( null === $page_exists ) {
		$page_id = wp_insert_post(
			array(
				'post_title'   => $page_name,
				'post_content' =>
					'<!-- wp:shortcode -->' . PHP_EOL .
					'[kronos-print-calendar]' . PHP_EOL .
					'<!-- /wp:shortcode -->',
				'post_status'  => 'publish',
				'post_type'    => 'page',
			)
		);
	}

	if ( get_option( 'permalink_structure' ) === '' ) {
		update_option( 'permalink_structure', '/%postname%/' );
		flush_rewrite_rules();
	}
}
