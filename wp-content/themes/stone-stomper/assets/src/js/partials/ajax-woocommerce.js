jQuery( document ).ready( function( jquery ) {
	jQuery( '#btn_cart' ).on( 'click', function( e ) {
		e.preventDefault();

		const shipping = jQuery( '#shipping' ).val();
		const barwidth = jQuery( '#barwidth' ).val();
		const vanwidth = jQuery( '#vanwidth' ).val();
		const cleargap = jQuery( '#cleargap' ).val();
		const vinyl = jQuery( '#vinyl' ).val();
		console.log( shipping );

		// Hidden fields holding JSON ("[1,2]") or CSV ("1,2")
		const hitch_ids = jQuery( '#hitch_ids' ).val() || '[]';
		const rear_ids = jQuery( '#rear_ids' ).val() || '[]';
		const front_ids = jQuery( '#front_ids' ).val() || '[]';

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
				hitch_attachment_ids: hitch_ids,
				rear_attachment_ids: rear_ids,
				front_attachment_ids: front_ids,
			},
			success( response ) {
				if ( response && response.success && response.data && response.data.added ) {
					window.location.href = response.data.redirect || '/cart';
					return;
				}
				alert( 'Could not add to cart. Please try again.' );
				console.error( 'Add to cart unexpected response:', response );
			},
			error( xhr ) {
				alert( 'Something went wrong. Please try again.' );
				console.error( 'Add to cart error:', xhr );
			},
		} );
	} );
} );
