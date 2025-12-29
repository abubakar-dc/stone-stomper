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
