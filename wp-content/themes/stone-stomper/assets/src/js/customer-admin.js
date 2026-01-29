jQuery( document ).on( 'click', '.email-to-manufacturer', function( e ) {
	e.preventDefault();

	const post_id = jQuery( '#post_ID' ).val();

	jQuery( this ).text( 'Sending...' ).prop( 'disabled', true );

	jQuery.ajax( {
		url: ajaxurl,
		type: 'POST',
		data: {
			action: 'email_to_manufacturer',
			post_id,
		},
		success( res ) {
			alert( 'Email Sent Successfully!' );
			location.reload();
		},
		error() {
			alert( 'Error Sending Email' );
		},
	} );
} );

function convertSvgToPng( svg, callback ) {
	const svgData = new XMLSerializer().serializeToString( svg );
	const svgBlob = new Blob( [ svgData ], { type: 'image/svg+xml;charset=utf-8' } );
	const url = URL.createObjectURL( svgBlob );
	const img = new Image();
	const OFFSET_X = -100;
	const OFFSET_Y = -45;
	img.onload = function() {
		const canvas = document.createElement( 'canvas' );
		canvas.width = img.width * 2;
		canvas.height = img.height * 2;
		const ctx = canvas.getContext( '2d' );
		ctx.scale( 1.6, 1.6 );
		ctx.fillStyle = '#ffffff';
		ctx.fillRect( 0, 0, canvas.width, canvas.height );
		ctx.drawImage( img, OFFSET_X, OFFSET_Y );
		const pngBase64 = canvas.toDataURL( 'image/png' );
		URL.revokeObjectURL( url );
		callback( pngBase64 );
	};
	img.src = url;
}

jQuery( '.generate-word-doc' ).on( 'click', function( e ) {
	e.preventDefault();

	const post_id = jQuery( '#post_ID' ).val();
	console.log( 'Post ID:', post_id );

	const svg = document.getElementById( 'towing-diagram-svg' );
	console.log( 'SVG found:', !! svg );

	if ( ! svg ) {
		alert( 'Diagram not found' );
		return;
	}

	convertSvgToPng( svg, function( pngBase64 ) {
		console.log( 'PNG generated:', pngBase64 ? 'YES' : 'NO' );
		console.log( 'PNG length:', pngBase64.length );

		jQuery.post( stoneStomper.ajaxurl, {
			action: 'store_diagram_png',
			post_id,
			diagram_png: pngBase64,
			_ajax_nonce: stoneStomper.nonce,
		} ).done( function( res ) {
			console.log( 'AJAX success', res );

			window.location.href =
                stoneStomper.ajaxurl +
                '?action=download_customer_word&post_id=' +
                post_id;
		} ).fail( function( err ) {
			console.error( 'AJAX failed', err );
		} );
	} );
} );
