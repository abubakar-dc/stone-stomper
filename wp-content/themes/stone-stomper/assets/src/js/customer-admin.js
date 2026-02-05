jQuery( document ).ready( function() {
	jQuery( document ).on( 'click', '.email-to-manufacturer', function( e ) {
		e.preventDefault();

		const post_id = jQuery( '#post_ID' ).val();
		const svg = document.getElementById( 'towing-diagram-svg' );

		if ( ! svg ) {
			alert( 'Diagram not found' );
			return;
		}

		const button = jQuery( this );
		button.text( 'Sending...' ).prop( 'disabled', true );

		convertSvgToPng( svg, function( pngBase64 ) {
			jQuery.post( stoneStomper.ajaxurl, {
				action: 'store_diagram_png',
				post_id,
				diagram_png: pngBase64,
				_ajax_nonce: stoneStomper.nonce,
			} ).done( function() {
				jQuery.post( ajaxurl, {
					action: 'email_to_manufacturer',
					post_id,
				} ).done( function() {
					alert( 'Email Sent Successfully!' );
					location.reload();
				} ).fail( function() {
					alert( 'Error Sending Email' );
					button.prop( 'disabled', false ).text( 'Email to Manufacturer' );
				} );
			} );
		} );
	} );

	function convertSvgToPng( svg, callback ) {
		const svgClone = svg.cloneNode( true );
		const OUTPUT_WIDTH = 2200;
		const OUTPUT_HEIGHT = 1400;
		svgClone.setAttribute( 'width', OUTPUT_WIDTH );
		svgClone.setAttribute( 'height', OUTPUT_HEIGHT );
		const svgData = new XMLSerializer().serializeToString( svgClone );
		const svgBlob = new Blob( [ svgData ], { type: 'image/svg+xml;charset=utf-8' } );
		const url = URL.createObjectURL( svgBlob );
		const img = new Image();
		const SCALE = 5;
		const ZOOM = 1.6; // slight zoom
		img.onload = function() {
			const canvas = document.createElement( 'canvas' );
			canvas.width = img.width * SCALE;
			canvas.height = img.height * SCALE;
			const ctx = canvas.getContext( '2d' );
			const drawScale = SCALE * ZOOM;
			const offsetX = ( canvas.width / drawScale - img.width ) / 1.8;
			const offsetY = ( canvas.height / drawScale - img.height ) / 2.4;
			ctx.scale( drawScale, drawScale );
			ctx.fillStyle = '#ffffff';
			ctx.fillRect( 0, 0, canvas.width, canvas.height );
			ctx.drawImage( img, offsetX, offsetY );
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
} );
