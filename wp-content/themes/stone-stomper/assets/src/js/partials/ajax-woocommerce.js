jQuery( document ).ready( function() {
	jQuery( '#btn_cart' ).on( 'click', function( e ) {
		e.preventDefault();

		const ids = [];

		jQuery( '.acc-upsell:checked' ).each( function() {
			const productId = jQuery( this ).data( 'product-id' );
			if ( productId ) {
				ids.push( productId );
			}
		} );

		// Optional: Add static product ID only once
		ids.unshift( 62 ); // Adds 62 at the beginning

		jQuery.ajax( {
			type: 'POST',
			url: localVars.ajax_url,
			data: {
				action: 'woocommerce_ajax_add_to_cart',
				ids: JSON.stringify( ids ), // Serialize array
				quantity: 1,
				shipping: 'standard', // Replace with actual value
			},
			success( response ) {
				if ( response?.success && response?.data?.added ) {
					window.location.href = response.data.redirect || '/cart';
				} else {
					alert( 'Could not add to cart. Please try again.' );
					console.error( 'Add to cart unexpected response:', response );
				}
			},
			error( xhr ) {
				alert( 'Something went wrong. Please try again.' );
				console.error( 'Add to cart error:', xhr );
			},
		} );
	} );
} );
