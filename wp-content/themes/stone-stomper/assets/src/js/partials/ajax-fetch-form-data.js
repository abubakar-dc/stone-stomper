jQuery( document ).ready( function() {
	let restoringFromCookie = false;
	let cookieData = null;

	function getCookie( name ) {
		const match = document.cookie.match( new RegExp( '(^| )' + name + '=([^;]+)' ) );
		return match ? decodeURIComponent( match[ 2 ] ) : null;
	}

	function populateFieldsFromCookie() {
		const cookie = getCookie( 'order_form' );
		if ( ! cookie ) {
			return;
		}

		try {
			cookieData = JSON.parse( cookie );
			restoringFromCookie = true;
		} catch ( e ) {
			return;
		}

		if ( cookieData.veh_make ) {
			jQuery( '#veh_make' ).val( cookieData.veh_make ).trigger( 'change' );
		}
	}

	let carMake = '';
	let postID = '';
	let caravanPostID = '';
	let yearRequested = true;
	let caravan = true;
	let caravanMake = '';

	jQuery( '#van_make' ).on( 'change', function( e ) {
		if ( e.originalEvent ) {
			caravanMake = jQuery( this ).val();
			caravan = true;

			jQuery( '.van-model-group' ).addClass( 'loading' );
			setTimeout( () => {
				jQuery( '.van-model-group' ).removeClass( 'loading' );
			}, 1000 );
			// Call your function to fetch form data
			fetchCaravanData();
		}
	} );

	jQuery( '#van_model' ).on( 'change', function( e ) {
		if ( e.originalEvent ) {
			const selectedOption = jQuery( this ).find( ':selected' );
			caravanPostID = selectedOption.data( 'post-id' );
			caravan = false;

			// 🔹 Call your next function if needed
			fetchCaravanData();
		}
	} );

	jQuery( '#veh_make' ).on( 'change', function( e ) {
		if ( e.originalEvent || restoringFromCookie ) {
			carMake = jQuery( this ).val();
			yearRequested = true;
			postID = '';

			// Add loader
			jQuery( '.vehicle-model-group' ).addClass( 'loading' );
			setTimeout( () => {
				jQuery( '.vehicle-model-group' ).removeClass( 'loading' );
			}, 1000 );

			fetchFormData();
		}
	} );

	jQuery( '#veh_model' ).on( 'change', function( e ) {
		if ( e.originalEvent || restoringFromCookie ) {
			const selectedOption = jQuery( this ).find( ':selected' );
			postID = selectedOption.data( 'post-id' );
			yearRequested = false;

			jQuery( '.vehicle-year-group' ).addClass( 'loading' );
			setTimeout( () => {
				jQuery( '.vehicle-year-group' ).removeClass( 'loading' );
			}, 1000 );

			// 🔹 Call your next function if needed
			fetchFormData();
		}
	} );

	let selectedYear = '';
	jQuery( '#veh_year' ).on( 'change', function( e ) {
		if ( e.originalEvent || restoringFromCookie ) {
			selectedYear = jQuery( this ).val(); // store selected year
			console.log( selectedYear );
			yearRequested = false; // since user manually selected a year

			jQuery( '.loader-container' ).show();

			fetchFormData(); // fetch based on year
		}
	} );

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
				selectedYear, // actual selected year value ('' when none)
				yearRequested, // boolean that you already use to decide which UI to update
			},

			success( response ) {
				if ( response ) {
					if ( yearRequested ) {
						jQuery( '#veh_model' ).html( response.models );
						if ( restoringFromCookie && cookieData.veh_model ) {
							jQuery( '#veh_model' )
								.val( cookieData.veh_model )
								.trigger( 'change' );
						}
					}

					if ( response.barwidth ) {
						jQuery( '#barwidth' ).val( response.barwidth );
					}

					// Update year dropdown BUT preserve any user selection
					if ( response.year ) {
						// replace the options first
						jQuery( '#veh_year' ).html( response.year );
						if ( restoringFromCookie && cookieData.veh_year ) {
							jQuery( '#veh_year' ).val( cookieData.veh_year );
						}

						// if user already selected a year, restore it explicitly
						if ( selectedYear ) {
							// if the option exists, set it, otherwise clear selectedYear
							if ( jQuery( '#veh_year option[value="' + selectedYear + '"]' ).length ) {
								jQuery( '#veh_year' ).val( selectedYear );
							} else {
								// selected year no longer present in options
								selectedYear = '';
							}
						}
					}

					// other fields...
					if ( response.support_pockets ) {
						jQuery( '#support_pockets' ).html( response.support_pockets );
					}

					if ( response.vehicleImage ) {
						jQuery( '#towing-vehicle-image' ).html( response.vehicleImage );
					}
				}

				if ( restoringFromCookie ) {
					Object.keys( cookieData ).forEach( function( key ) {
						if ( [ 'veh_make', 'veh_model', 'veh_year' ].includes( key ) ) {
							return;
						}

						const field = jQuery( '[name="' + key + '"]' );
						if ( ! field.length ) {
							return;
						}

						if ( field.is( ':checkbox' ) ) {
							field.prop( 'checked', cookieData[ key ] === true || cookieData[ key ] === 'true' );
						} else if ( field.is( ':radio' ) ) {
							jQuery( '[name="' + key + '"][value="' + cookieData[ key ] + '"]' ).prop( 'checked', true );
						} else {
							field.val( cookieData[ key ] );
						}
					} );
					restoringFromCookie = false;
				}

				jQuery( '.loader-container' ).hide();
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
				if ( response ) {
					console.log( response );
					if ( caravan ) {
						jQuery( '#van_model' ).html( response.html );
						jQuery( '#van_model' ).append( '<option value="other">Other</option>' );
					}
					if ( response.barheight > 1800 ) {
						jQuery( '.extra-support' ).show();
					}

					if ( response.barwidth ) {
						jQuery( '#vanwidth' ).val( response.barwidth ).trigger( 'change' );
					}

					if ( response.barheight ) {
						jQuery( '#a_frame_length' ).val( response.barheight ).trigger( 'change' );
					}
					if ( response.stoneguard_width ) {
						jQuery( '#stoneguard_width' ).val( response.stoneguard_width ).trigger( 'change' );
					}
					if ( response.stoneguard_height ) {
						jQuery( '#stoneguard_length' ).val( response.stoneguard_height ).trigger( 'change' );
					}
					if ( response.toolbox_width ) {
						jQuery( '#toolbox_width' ).val( response.toolbox_width ).trigger( 'change' );
					}
					if ( response.toolbox_height ) {
						jQuery( '#toolbox_length' ).val( response.toolbox_height ).trigger( 'change' );
					}

					// Support Pocket Length
					if ( response.support_pocket_length ) {
						jQuery( '#support_pocket_length' ).val( response.support_pocket_length ).trigger( 'change' );
					}

					if ( response.vinyl_insert_width ) {
						jQuery( '#vinyl_width' ).val( response.vinyl_insert_width ).trigger( 'change' );
					}
					if ( response.vinyl_insert_height ) {
						jQuery( '#vinyl_length' ).val( response.vinyl_insert_height ).trigger( 'change' );
					}
					if ( response.stoneguard_image ) {
						jQuery( '#caravan-images' )
							.html( '<div class="vehicle-image">' + response.stoneguard_image + '</div>' )
							.trigger( 'change' );
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
	populateFieldsFromCookie();
} );
