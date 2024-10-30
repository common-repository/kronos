function kronos_generate_ajax_url(ajaxmethode, params) {
	return data_stock.ajaxurl + '?nonce=' + data_stock.nonce + '&action=kronos_show_ajax&&method=' + ajaxmethode + '&' + params;
}
function kronos_load_ajax_nw(ajaxmethode, params='') {
	ajaxurl = kronos_generate_ajax_url( ajaxmethode, params );
	window.open( ajaxurl );
}

function kronos_load_ajax_div( ajaxmethode, targetid, params = '') {
	var xhr                = new XMLHttpRequest();
	xhr.onreadystatechange = function () {
		if (xhr.readyState === XMLHttpRequest.DONE) {
			if (xhr.status === 200) {
				document.getElementById( targetid ).innerHTML = xhr.responseText;
			};
		}
	}
		var scriptcall     = kronos_generate_ajax_url( ajaxmethode, params );
		xhr.open( 'GET', scriptcall, true );
		xhr.send();

}

// Funktion zum Laden des Kalenders über AJAX
function kronos_load_calendar(month, year) {
	kronos_load_ajax_div(
		'kronos-load-calendar-month',
		'calendarContainer',
		'month=' + month + '&year=' + year
	);
}



function kronos_show_event_details(event, id) {
	// Erhalte die Position des Klicks
	const x = event.clientX - 350;
	const y = event.clientY;

	// Lade den Inhalt via AJAX
	kronos_load_ajax_div( 'kronos-print-event-details', 'kronos-event-infobox-content', 'id=' + id );

	// Zeige das Infobox-Element
	const infobox         = document.getElementById( 'kronos-event-infobox' );
	infobox.style.display = 'block';

	// Positioniere die Infobox an der Klickposition
	infobox.style.position = 'absolute';
	infobox.style.left     = `${x}px`;
	infobox.style.top      = `${y}px`;

	// Stelle sicher, dass der Hider auch sichtbar ist
	document.getElementById( 'kronos-hider' ).style.display = 'block';
}

function kronos_hide()
{
	document.getElementById( 'kronos-hider' ).style.display         = 'none';
	document.getElementById( 'kronos-event-infobox' ).style.display = 'none';
}