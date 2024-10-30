<?php
/**
 * File: details.php
 *
 * @since 2024-08-09
 * @license GPL-3.0-or-later
 *
 * @package kronos/Views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<table>
	<tr>
		<td><?php echo esc_html__( 'Event', 'kronos' ); ?>:</td>
		<td>
			<?php echo esc_html( $event->name ); ?>
		</td>
	</tr>

	<tr>
		<td><?php echo esc_html__( 'Begin', 'kronos' ); ?>:</td>
		<td>
			<?php echo esc_html( DateTime::createFromFormat( 'Y-m-d H:i:s', $event->begin )->format( 'H:i' ) ); ?>
			<?php echo esc_html__( "o'clock", 'kronos' ); ?>
		</td>
	</tr>

	<tr>
		<td><?php echo esc_html__( 'End', 'kronos' ); ?>:</td>
		<td>
			<?php echo esc_html( DateTime::createFromFormat( 'Y-m-d H:i:s', $event->end )->format( 'H:i' ) ); ?>
			<?php echo esc_html__( "o'clock", 'kronos' ); ?>
		</td>
	</tr>

	<?php if ( '' !== $event->location ) { ?>
	<tr>
		<td><?php echo esc_html__( 'Location', 'kronos' ); ?>:</td>
		<td>
			<?php
					echo esc_html( $event->location );
			?>
		</td>
	</tr> <?php } ?>

	<?php if ( '' !== $event->description ) { ?>
	<tr>
		<td><?php echo esc_html__( 'Description', 'kronos' ); ?>:</td>
		<td>
			<?php
				echo esc_html( $event->description );

			?>
		</td>
	</tr> <?php } ?>

	<?php if ( '' !== $event->url ) { ?>
		<tr>
			<td><?php echo esc_html__( 'URL', 'kronos' ); ?>:</td>
			<td><a href="<?php echo esc_url( $event->url ); ?>" target="_blank">
					<?php echo esc_html__( 'View', 'kronos' ); ?>
					<span class="dashicons dashicons-migrate"></span>
				</a>
			</td>
		</tr>
	<?php } ?>
</table>
