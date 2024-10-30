<?php
/**
 * File: pdfhandler.php
 *
 * @since 2024-07-25
 * @license GPL-3.0-or-later
 *
 * @package kronos/Libs
 */

use Dompdf\Dompdf;

/**
 * Creates a PDF from HTML content.
 *
 * @param string $content The HTML content to be converted into a PDF.
 * @param string $filename The name of the file to be used when downloading the PDF.
 * @param string $format The paper orientation, either 'portrait' or 'landscape' (default: 'portrait').
 * @param bool   $force_download Whether to force the PDF to download (default: true).
 * @return string|null The PDF content as a string if $force_download is false, otherwise null.
 */
function kronos_create_pdf( string $content, string $filename, string $format = 'portrait', bool $force_download = true ): ?string {

	$dompdf = new Dompdf();
	$dompdf->setPaper( 'A4', $format );
	$dompdf->loadHtml( $content );
	$dompdf->render();

	if ( ! $force_download ) {
		return $dompdf->output();
	}

	$dompdf->stream( $filename );
	return null;
}
