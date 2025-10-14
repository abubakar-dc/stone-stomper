jQuery( document ).ready( function() {
	let carMake = '';
	let postID = '';
	let caravanPostID = '';
	let yearRequested = true;
	let caravan = true;
	let caravanMake = '';

	jQuery( '#van_make' ).on( 'change', function( e ) {
		if ( e.originalEvent ) {
			caravanMake = jQuery( this ).val();
			// console.log( 'Selected Caravan Make:', caravanMake );
			caravan = true;
			// Call your function to fetch form data
			fetchCaravanData();
		}
	} );

	jQuery( '#van_model' ).on( 'change', function( e ) {
		if ( e.originalEvent ) {
			const selectedOption = jQuery( this ).find( ':selected' );
			caravanPostID = selectedOption.data( 'post-id' );
			// console.log( 'Selected Caravan Model ID:', caravanPostID );
			caravan = false;
			// 🔹 Call your next function if needed
			fetchCaravanData();
		}
	} );

	// Get Category Slug on Click Start.
	jQuery( '#veh_make' ).on( 'change', function( e ) {
		if ( e.originalEvent ) {
			carMake = jQuery( this ).val();
			// console.log( 'Selected Car Make:', carMake );
			yearRequested = true;
			postID = '';
			// Call your function to fetch form data
			fetchFormData();
		}
	} );

	jQuery( '#veh_model' ).on( 'change', function( e ) {
		if ( e.originalEvent ) {
			const selectedOption = jQuery( this ).find( ':selected' );
			postID = selectedOption.data( 'post-id' );
			console.log( 'Selected Car Model ID:', postID );
			yearRequested = false;
			// 🔹 Call your next function if needed
			fetchFormData();
		}
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
					if ( response.vehicleImage !== null ) {
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
					console.log( response.barheight );
					if ( response.barheight > 1800 ) {
						jQuery( '.extra-support' ).show();
					}
					if ( response.barwidth ) {
						jQuery( '#vanwidth' ).val( response.barwidth + ' mm' ).trigger( 'change' );
					}
					if ( response.barheight ) {
						jQuery( '#a_frame_length' ).val( response.barheight + ' mm' ).trigger( 'change' );
					}
					if ( response.stoneguard_width ) {
						jQuery( '#stoneguard_width' ).val( response.stoneguard_width + ' mm' ).trigger( 'change' );
					}
					if ( response.stoneguard_height ) {
						jQuery( '#stoneguard_length' ).val( response.stoneguard_height + ' mm' ).trigger( 'change' );
					}
					if ( response.toolbox_width ) {
						jQuery( '#toolbox_width' ).val( response.toolbox_width + ' mm' ).trigger( 'change' );
					}
					if ( response.toolbox_height ) {
						jQuery( '#toolbox_length' ).val( response.toolbox_height + ' mm' ).trigger( 'change' );
					}
					if ( response.vinyl_insert_width ) {
						jQuery( '#vinyl_width' ).val( response.vinyl_insert_width + ' mm' ).trigger( 'change' );
					}
					if ( response.vinyl_insert_height ) {
						jQuery( '#vinyl_length' ).val( response.vinyl_insert_height + ' mm' ).trigger( 'change' );
					}
					if (response.stoneguard_image) {
						jQuery('#caravan-images')
							.html('<div class="vehicle-image">' + response.stoneguard_image + '</div>')
							.trigger('change');
					}

					if (response.toolbox_image) {
						jQuery('#caravan-images')
							.append('<div class="vehicle-image">' + response.toolbox_image + '</div>')
							.trigger('change');
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
