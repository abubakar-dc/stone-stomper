( function() {
	// Wait for the window to have the necessary WC objects
	const registerField = () => {
		if ( window.wp && window.wp.woocommerce_blocks && window.wp.woocommerce_checkout ) {
			const { registerBlockExtensionValue } = window.wp.woocommerce_blocks;

			// Register the value so the API saves it
			registerBlockExtensionValue( {
				extensionId: 'custom-atl-field',
				fieldId: 'authority_to_leave',
			} );

			console.log( 'ATL Field Registered Successfully' );
		} else {
			// If not ready yet, check again in 100ms
			setTimeout( registerField, 100 );
		}
	};

	registerField();
}() );
