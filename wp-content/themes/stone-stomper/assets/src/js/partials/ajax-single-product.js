jQuery( function( $ ) {
	$( '.add-upsell-to-cart' ).on( 'click', function( e ) {
		e.preventDefault();

		const main_id = $( this ).data( 'main-product' );
		const upsells = [];

		$( '.upsell-checkbox:checked' ).each( function() {
			upsells.push( $( this ).val() );
		} );

		$.ajax( {
			type: 'POST',
			url: localVars.ajax_url,
			data: {
				action: 'mytheme_add_upsell_products',
				main_id,
				upsells,
			},
			success( response ) {
				if ( response?.success && response?.data?.added ) {
					window.location.href = response.data.redirect || '/cart';
				} else {
					alert( 'Could not add to cart. Please try again.' );
				}
			},
		} );
	} );
} );
