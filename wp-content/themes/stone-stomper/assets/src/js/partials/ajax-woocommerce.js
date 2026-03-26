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

			console.log( productId );
			if ( productId ) {
				upsellsProductId.push( productId );
			}
		} );
		UpdateSummary(); // Call your update function
	} );

	jQuery( '.form-section-right #btn_cart' ).on( 'click', function( e ) {
		e.preventDefault();

		const checkbox = jQuery( '#a_frame_tick' );
		const btn = jQuery( this );

		if ( ! checkbox.is( ':checked' ) ) {
			jQuery( '.error-notes-checkbox' ).show();

			jQuery( 'html, body' ).animate( {
				scrollTop: checkbox.offset().top - 150,
			}, 500 );

			return;
		}

		jQuery( '.error-notes-checkbox' ).hide();
		btn.addClass( 'btn-loading' );
		AddToCart();
	} );

	jQuery( '#a_frame_tick' ).on( 'change', function() {
		if ( jQuery( this ).is( ':checked' ) ) {
			jQuery( '.error-notes-checkbox' ).hide();
		}
	} );

	function AddToCart() {
		const ids = jQuery.merge( [], mainProductId );
		jQuery.merge( ids, upsellsProductId );

		const formData = jQuery( '#orderForm' ).serializeArray();
		const payload = {};

		jQuery.each( formData, function( _, field ) {
			payload[ field.name ] = field.value;
		} );

		if ( window.caravanPostID ) {
			payload.caravan_id = window.caravanPostID;
		}

		jQuery.ajax( {
			type: 'POST',
			url: localVars.ajax_url,
			data: {
				action: 'woocommerce_ajax_add_to_cart',
				ids,
				quantity: 1,
				payload,
			},
			success( response ) {
				if ( response && response.success && response.data && response.data.added ) {
					clearSavedOrderForm();
					window.location.href = response.data.redirect || '/cart';
				} else {
					alert( 'Could not add to cart. Please try again.' );
				}
			},
			error() {
				alert( 'Something went wrong. Please try again.' );
			},
		} );
	}

	function clearSavedOrderForm() {
		const storageKey = 'order_form_v2';
		try {
			localStorage.removeItem( storageKey );
		} catch ( e ) {
			// ignore
		}
	}

	function UpdateSummary() {
	    const barwidth = jQuery( '#barwidth' ).val();
	    const aFrame = jQuery( '#a_frame_length' ).val();
	    const support_option = jQuery( 'input[name="input_1.3"]:checked' ).val(); // 🔥 FIXED

	    console.log( '➡️ UpdateSummary triggered', { ids, barwidth, aFrame, support_option } );

	    const productType = jQuery( '#product_type' ).val();

	    ids = jQuery.merge( [], mainProductId );
	    jQuery.merge( ids, upsellsProductId );

	    jQuery.ajax( {
	        type: 'POST',
	        url: localVars.ajax_url,
	        data: {
	            action: 'woocommerce_ajax_update_summary',
	            ids,
	            product_type: productType,
	            quantity: 1,
	            shipping: 'standard',
	            barwidth,
	            a_frame_length: aFrame,
	            support_option, // 🔥 sends support option to backend
	        },
	        success( response ) {
	            console.log( '✅ UpdateSummary Response:', response );
	            if ( response?.data?.html ) {
	                jQuery( '#order_summary' ).html( response.data.html );
	            } else {
	                console.warn( '⚠️ No HTML returned' );
	            }
	        },
	        error( xhr ) {
	            console.error( '❌ UpdateSummary failed:', xhr.responseText );
	        },
	    } );
	}

	// 🔹 Trigger live update on measurement input changes
	jQuery( document ).on( 'input change', '#barwidth, input[name="input_1.3"], #a_frame_length', function() {
		console.log( 'update input values' );
		UpdateSummary(); // Recalculate instantly on any change
	} );
} );
