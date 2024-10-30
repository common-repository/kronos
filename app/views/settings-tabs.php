<?php
/**
 * File: settings-tabs.php
 *
 * @since 2024-09-30
 * @license GPL-3.0-or-later
 *
 * @package kronos/Views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$nonce = esc_html( wp_create_nonce() );
?>

<div class="wrap">
	<hr class="wp-header-end">

	<h1><?php echo esc_html__( 'kronos settings', 'kronos' ); ?></h1>

	<h2 class="nav-tab-wrapper" >
		<a
			href="<?php echo esc_url( admin_url( 'options-general.php?nonce=' . $nonce . '&page=edit-kronos-settings&tab=tab-common' ) ); ?>"
			class="nav-tab <?php echo 'tab-common' === $active_tab ? esc_html( 'nav-tab-active' ) : ''; ?> "
		>
			<?php echo esc_html__( 'Common settings', 'kronos' ); ?>
		</a>


		<a
			href="<?php echo esc_url( admin_url( 'options-general.php?nonce=' . $nonce . '&page=edit-kronos-settings&tab=tab-text' ) ); ?>"
			class="nav-tab <?php echo 'tab-text' === $active_tab ? esc_html( 'nav-tab-active' ) : ''; ?> "
		>
			<?php echo esc_html__( 'Annual calendar settings', 'mareike' ); ?>
		</a>

	</h2>
	<div class="tab-content">
