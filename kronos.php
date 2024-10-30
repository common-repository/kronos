<?php
/**
 * Plugin Name:  kronos
 * Description: A plugin that renders an ICS calendaer.
 * Version: 1.1
 * Tags:calendar ics synchronisation pdf export rendering
 * Requires at least: 6.0
 * Requires PHP: 8.2
 * Author: Thomas Günther
 * Author URI: https://www.contelli.de
 * Text Domain: kronos
 *  License: GPLv3
 *  License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package kronos
 *
 *  Copyright  2024 by Thomas Günther (tidschi)
 *
 *  Licenced under the GNU GPL:
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

if ( ! defined( 'ABSPATH' ) ) exit;

require_once 'core/init.php';

add_shortcode( 'kronos-print-calendar', array( 'kronos\App\Controllers\DisplayCalendar', 'execute' ) );
add_action( 'admin_enqueue_scripts', 'kronos_admin_setup' );
add_action( 'admin_menu', 'kronos_add_menu' );
add_action( 'wp_ajax_kronos_show_ajax', 'kronos_load_ajax_content' );
add_action( 'wp_ajax_nopriv_kronos_show_ajax', 'kronos_load_ajax_content' );


add_action( 'plugins_loaded', 'kronos_localization_setup' );
add_filter( 'admin_enqueue_scripts', 'kronos_enqueue_custom_scripts', 10 );

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'kronos_action_links' );

/**
 * Adds the Kronos settings link to the action links on a plugin's settings page.
 *
 * @param array $links An array of action links.
 * @return array The modified action links with the Kronos settings link added to the beginning.
 */
function kronos_action_links( $links ) {
	$settings_link = '<a href="' . admin_url( 'options-general.php?page=edit-kronos-settings' ) . '">' . __( 'Settings', 'kronos' ) . '</a>';
	array_unshift( $links, $settings_link );
	return $links;
}
