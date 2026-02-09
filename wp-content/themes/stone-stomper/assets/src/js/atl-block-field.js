jQuery( function( jQuery ) {
	const toggleATL = () => {
		const $atl = jQuery( '.wc-block-components-select-input-my-custom-atl-authority-to-leave' );
		if ( ! $atl.length ) {
			return;
		}

		const isPickup = jQuery( '.wc-block-checkout__shipping-method-option--selected' )
			.find( '.wc-block-checkout__shipping-method-option-title' )
			.text()
			.toLowerCase()
			.includes( 'local pickup' );

		$atl.toggle( ! isPickup );
	};

	setInterval( toggleATL, 200 );
} );
