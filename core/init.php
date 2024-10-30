<?php
/**
 * File: init.php
 *
 * @since 2024-07-21
 * @license GPL-3.0-or-later
 *
 * @package kronos/Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use kronos\Libs\UpdateChecker;

require_once __DIR__ . '/defines.php';
require_once ABSPATH . '/wp-admin/includes/plugin.php';
require_once ABSPATH . '/wp-admin/includes/class-wp-filesystem-base.php';
require_once ABSPATH . '/wp-admin/includes/class-wp-filesystem-direct.php';
require_once ABSPATH . '/wp-includes/pluggable.php';
require_once ABSPATH . '/wp-includes/capabilities.php';
require_once ABSPATH . '/wp-admin/includes/template.php';
require_once ABSPATH . '/wp-admin/includes/file.php';
require_once ABSPATH . '/wp-admin/includes/upgrade.php';

require KRONOS_PLUGIN_DIR . '/vendor/autoload.php';

$directories = array(
	'models/',
	'routers/',
	'routers/',
	'controllers/',
	'requests/',
);

require_once KRONOS_PLUGIN_DIR . '/libs/statusmessage.php';
require_once KRONOS_PLUGIN_DIR . '/libs/pdfhandler.php';

foreach ( $directories as $directory ) {
	$directory = KRONOS_PLUGIN_DIR . '/app/' . $directory;

	$handle = opendir( $directory );
	while ( $entry = readdir( $handle ) ) {
		if ( '.' !== $entry && '..' !== $entry ) {
			$file_path = $directory . DIRECTORY_SEPARATOR . $entry;
			if ( is_file( $file_path ) && pathinfo( $file_path, PATHINFO_EXTENSION ) === 'php' ) {
				require_once $file_path;
			} elseif ( is_dir( $file_path ) ) {
				$sub_handle = opendir( $file_path );

				while ( $sub_entry = readdir( $sub_handle ) ) {
					if ( '.' !== $sub_entry && '..' !== $sub_entry ) {
						$sub_file_path = $file_path . DIRECTORY_SEPARATOR . $sub_entry;

						if ( is_file( $sub_file_path ) && pathinfo( $sub_file_path, PATHINFO_EXTENSION ) === 'php' ) {
							require_once $sub_file_path;
						}
					}
				}

				closedir( $sub_handle );
			}
		}
	}

	closedir( $handle );
}


require_once KRONOS_PLUGIN_DIR . 'setup/setup.php';
require_once KRONOS_PLUGIN_DIR . 'core/guilib.php';
