<?php
/**
 * File: abbreviation-index.php
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
<h2>
	<?php echo esc_html__( 'Abbreviation for annual calendar', 'kronos' ); ?>
</h2>
<table class="wp-list-table widefat fixed striped table-view-list">
	<tr>
		<th><?php echo esc_html__( 'Event name', 'kronos' ); ?></th>
		<th><?php echo esc_html__( 'Abbreviation', 'kronos' ); ?></th>
		<th><?php echo esc_html__( 'Actions', 'kronos' ); ?></th>
	</tr>
	<?php

	$abbreviations = get_option( 'kronos_abbreviations', array() );


	foreach ( $abbreviations as $abbreviation ) {
		?>
		<tr>
			<td>
				<?php echo esc_html( $abbreviation['event'] ); ?>
			</td>
			<td>
				<?php echo esc_html( $abbreviation['abbreviation'] ); ?>
			</td>

			<td>
				<a href="
						<?php
						echo esc_url( admin_url( 'options-general.php?kronos_form_nonce=' . $nonce . '&page=edit-kronos-settings&tab=tab-text&mode=edit-abbreviation-form&abbreviation-id=' . $abbreviation['id'] ) );
						?>
						">
					<?php echo esc_html__( 'Edit', 'kronos' ); ?>
				</a><br />
				<a href="
						<?php
						echo esc_url( admin_url( 'options-general.php?nonce=' . $nonce . '&page=edit-kronos-settings&tab=tab-text&mode=delete-abbreviation&abbreviation-id=' . $abbreviation['id'] ) );
						?>
						">
					<?php echo esc_html__( 'Delete', 'kronos' ); ?>
				</a>
			</td>
		</tr>
		<?php
	}

	?>
</table><br /><br />
<a href="<?php echo esc_url( admin_url( 'options-general.php?page=edit-kronos-settings&tab=tab-text&mode=new-form' ) ); ?>"
	class="button"><?php echo esc_html__( 'Add abbreviation', 'kronos' ); ?></a>
