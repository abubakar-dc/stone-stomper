jQuery( document ).ready( function( $ ) {
	let carMake = '';
	let postID = '';
	let caravanPostID = '';
	let yearRequested = true;
	let caravan = true;
	let caravanMake = '';

	jQuery( document ).on( 'change', '#van_make', function() {
		caravanMake = jQuery( this ).val();
		console.log( 'Selected Caravan Make:', caravanMake );
		caravan = true;
		// Call your function to fetch form data
		fetchCaravanData();
	} );

	jQuery( document ).on( 'change', '#van_model', function() {
		const selectedOption = jQuery( this ).find( ':selected' );
		caravanPostID = selectedOption.data( 'post-id' );
		console.log( 'Selected Caravan Model ID:', caravanPostID );
		caravan = false;
		// 🔹 Call your next function if needed
		fetchCaravanData();
	} );

	// Get Category Slug on Click Start.
	jQuery( document ).on( 'change', '#veh_make', function() {
		carMake = jQuery( this ).val();
		console.log( 'Selected Car Make:', carMake );
		yearRequested = true;
		// Call your function to fetch form data
		fetchFormData();
	} );

	jQuery( document ).on( 'change', '#veh_model', function() {
		const selectedOption = jQuery( this ).find( ':selected' );
		postID = selectedOption.data( 'post-id' );
		console.log( 'Selected Car Model ID:', postID );
		yearRequested = false;
		// 🔹 Call your next function if needed
		fetchFormData();
	} );

	// Getting News
	function fetchFormData() {
		jQuery( '.loader-container' ).show();
		jQuery.ajax( {
			url: localVars.ajax_url,
			type: 'POST',
			data: {
				action: 'fetch_form_data',
				nonce: localVars.nonce,
				carMake,
				postID,
			},
			success( response ) {
				// console.log( response.args );
				if ( response ) {
					// console.log( response.models );
					if ( yearRequested ) {
						jQuery( '#veh_model' ).html( response.models );
					}
					if ( response.barwidth ) {
						jQuery( '#barwidth' ).val( response.barwidth + ' mm' );
					}
					jQuery( '#veh_year' ).html( response.year );
					jQuery( '.loader-container' ).hide();
				}
				// selectModel();
			},
			error() {
				const htmlTag = jQuery( "<h2 class='center-align heading-5'>An error occurred while processing your request.😢</h2>" );
				jQuery( '#news-post-container' ).html( htmlTag );
				jQuery( '.loader-container' ).hide();
			},
		} );
	}

	function fetchCaravanData() {
		jQuery( '.loader-container' ).show();
		jQuery.ajax( {
			url: localVars.ajax_url,
			type: 'POST',
			data: {
				action: 'fetch_caravan_data',
				nonce: localVars.nonce,
				caravanMake,
				caravanPostID,
			},
			success( response ) {
				// console.log( response.args );
				if ( response ) {
					// console.log( response.models );
					if ( caravan ) {
						jQuery( '#van_model' ).html( response.html );
					}
					jQuery( '#vanwidth' ).val( response.caravanbarwidth + ' mm' );
					jQuery( '#vinyl' ).val( response.vinylinsert + ' mm' );
					jQuery( '.loader-container' ).hide();
				}
				// selectModel();
			},
			error() {
				const htmlTag = jQuery( "<h2 class='center-align heading-5'>An error occurred while processing your request.😢</h2>" );
				jQuery( '#news-post-container' ).html( htmlTag );
				jQuery( '.loader-container' ).hide();
			},
		} );
	}
} );
