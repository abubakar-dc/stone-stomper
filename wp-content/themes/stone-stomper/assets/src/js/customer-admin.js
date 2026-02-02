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
	// Clone SVG so we can safely modify it
	const svgClone = svg.cloneNode( true );

	// Get actual drawing bounds
	const bbox = svg.getBBox();

	// Set tight viewBox around content
	svgClone.setAttribute(
		'viewBox',
		`${ bbox.x } ${ bbox.y } ${ bbox.width } ${ bbox.height }`
	);

	// Normalize size
	svgClone.setAttribute( 'width', bbox.width );
	svgClone.setAttribute( 'height', bbox.height );

	const svgData = new XMLSerializer().serializeToString( svgClone );
	const svgBlob = new Blob( [ svgData ], { type: 'image/svg+xml;charset=utf-8' } );
	const url = URL.createObjectURL( svgBlob );

	const img = new Image();
	const SCALE = 2.5; // print sharpness

	img.onload = function() {
		const canvas = document.createElement( 'canvas' );
		canvas.width = img.width * SCALE;
		canvas.height = img.height * SCALE;

		const ctx = canvas.getContext( '2d' );
		ctx.scale( 4.4, 4.4 );

		ctx.fillStyle = '#ffffff';
		ctx.fillRect( 0, 0, canvas.width, canvas.height );

		// Draw perfectly cropped SVG
		ctx.drawImage( img, 0, 0 );

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
