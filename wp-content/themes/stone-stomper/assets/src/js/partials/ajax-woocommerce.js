jQuery( document ).ready( function() {
	let ids = [];
	let mainProductId = [];
	let upsellsProductId = [];
	mainProductId.push( jQuery( '#product_type option:first' ).val() );
	jQuery( document ).on( 'change', '#product_type', function() {
		mainProductId = [];
		const productId = jQuery( this ).val();
		mainProductId.push( productId );
		UpdateSummary();
	} );
	jQuery( '.acc-upsell' ).on( 'change', function() {
		upsellsProductId = [];
		jQuery( '.acc-upsell:checked' ).each( function() {
			const productId = jQuery( this ).data( 'product-id' );

			console.log(productId);
			if ( productId ) {
				upsellsProductId.push( productId );
			}
		} );
		UpdateSummary(); // Call your update function
	} );

	jQuery( '#btn_cart' ).on( 'click', function( e ) {
		e.preventDefault();
		AddToCart();
	} );

	function AddToCart() {
		const ids = jQuery.merge( [], mainProductId ); // Start with a copy of the first array
		jQuery.merge( ids, upsellsProductId ); // Add the second array into it

		jQuery.ajax( {
			type: 'POST',
			url: localVars.ajax_url,
			data: {
				action: 'woocommerce_ajax_add_to_cart',
				ids, // Serialize array
				quantity: 1,
				shipping: 'standard', // Replace with actual value
			},
			success( response ) {
				if ( response?.success && response?.data?.added ) {
					window.location.href = response.data.redirect || '/cart';
				} else {
					alert( 'Could not add to cart. Please try again.' );
				}
			},
			error( xhr ) {
				alert( 'Something went wrong. Please try again.' );
			},
		} );
	}

	function UpdateSummary() {
		ids = jQuery.merge( [], mainProductId ); // Start with a copy of the first array
		jQuery.merge( ids, upsellsProductId ); // Add the second array into it

		jQuery.ajax( {
			type: 'POST',
			url: localVars.ajax_url,
			data: {
				action: 'woocommerce_ajax_update_summary',
				ids, // Serialize array
				quantity: 1,
				shipping: 'standard', // Replace with actual value
			},
			success( response ) {
				if ( response.data.html ) {
					jQuery( '#order_summary' ).html( response.data.html );
				} else {
					jQuery( '#order_summary' ).html( response.data.html );
				}
			},
		} );
	}
} );
