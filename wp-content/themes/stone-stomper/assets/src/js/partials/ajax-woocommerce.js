jQuery( document ).ready( function( jquery ) {
	jQuery( '#btn_cart' ).on( 'click', function( e ) {
		e.preventDefault();
		const shipping = jQuery( '#shipping' ).val();
	    const barwidth = jQuery( '#barwidth' ).val();
	    const vanwidth = jQuery( '#vanwidth' ).val();
	    const cleargap = jQuery( '#cleargap' ).val();
	    const vinyl = jQuery( '#vinyl' ).val();

		console.log( 'Selected shipping method:', shipping );
		jQuery.ajax( {
			type: 'POST',
			url: localVars.ajax_url,
			data: {
				action: 'woocommerce_ajax_add_to_cart',
				product_id: 62,
				quantity: 1,
				shipping,
				barwidth_mm: barwidth,
				caravan_width_mm: vanwidth,
				caravan_clearance_gap_mm: cleargap,
				vinyl_inserts: vinyl,
			},
			success( response ) {
				if ( response ) {
					window.location.href = '/cart';
				}
				// Reload the page after successful add
				// location.reload();
			},
			error() {
				alert( 'Something went wrong. Please try again.' );
			},
		} );
	} );
} );
