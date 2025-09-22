jQuery( document ).ready( function( $ ) {
	let carMake = '';
	let postID = '';
	let caravanPostID = '';
	let yearRequested = true;
	let caravan = true;
	let caravanMake = '';

	jQuery( '#van_make' ).on( 'change', function() {
		caravanMake = jQuery( this ).val();
		console.log( 'Selected Caravan Make:', caravanMake );
		caravan = true;
		// Call your function to fetch form data
		fetchCaravanData();
	} );

	jQuery( '#van_model' )( 'change', function() {
		const selectedOption = jQuery( this ).find( ':selected' );
		caravanPostID = selectedOption.data( 'post-id' );
		console.log( 'Selected Caravan Model ID:', caravanPostID );
		caravan = false;
		// 🔹 Call your next function if needed
		fetchCaravanData();
	} );

	// Get Category Slug on Click Start.
	jQuery().on( '#veh_make', function() {
		carMake = jQuery( this ).val();
		console.log( 'Selected Car Make:', carMake );
		yearRequested = true;
		postID = '';
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
					if ( response.vehicleImage ) {
						jQuery( '#towing-vehicle-image' ).html( response.vehicleImage );
					}
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
					if ( response.barwidth ) {
						jQuery( '#vanwidth' ).val( response.barwidth + ' mm' );
					}
					if ( response.barheight ) {
						jQuery( '#a_frame_length' ).val( response.barheight + ' mm' );
					}
					if ( response.stoneguard_width ) {
						jQuery( '#stoneguard_width' ).val( response.stoneguard_width + ' mm' );
					}
					if ( response.stoneguard_height ) {
						jQuery( '#stoneguard_length' ).val( response.stoneguard_height + ' mm' );
					}
					if ( response.toolbox_width ) {
						jQuery( '#toolbox_width' ).val( response.toolbox_width + ' mm' );
					}
					if ( response.toolbox_height ) {
						jQuery( '#toolbox_length' ).val( response.toolbox_height + ' mm' );
					}
					if ( response.vinyl_insert_width ) {
						jQuery( '#vinyl_width' ).val( response.vinyl_insert_width + ' mm' );
					}
					if ( response.vinyl_insert_height ) {
						jQuery( '#vinyl_length' ).val( response.vinyl_insert_height + ' mm' );
					}
					if ( response.stoneguard_image ) {
						jQuery( '#caravan-images' ).html( response.stoneguard_image );
					}
					if ( response.toolbox_image ) {
						jQuery( '#caravan-images' ).html( response.toolbox_image );
					}
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
