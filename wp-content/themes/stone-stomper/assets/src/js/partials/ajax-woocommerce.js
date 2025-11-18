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
	const ids = jQuery.merge([], mainProductId);
	jQuery.merge(ids, upsellsProductId);

	const barwidth = jQuery('#barwidth').val();
	const aFrameLength = jQuery('#a_frame_length').val();

	jQuery.ajax({
		type: 'POST',
		url: localVars.ajax_url,
		data: {
			action: 'woocommerce_ajax_add_to_cart',
			ids,
			quantity: 1,
			barwidth: barwidth,
			a_frame_length: aFrameLength,
			shipping: 'standard',
		},
		success(response) {
			if (response?.success && response?.data?.added) {
				window.location.href = response.data.redirect || '/cart';
			} else {
				alert('Could not add to cart. Please try again.');
			}
		},
		error() {
			alert('Something went wrong. Please try again.');
		},
	});
}

// function UpdateSummary() {

//   const productType = jQuery('#product_type').val(); // stone-stomper OR mesh-only

//   jQuery.ajax({
//     type: 'POST',
//     url: localVars.ajax_url,
//     data: {
//       action: 'woocommerce_ajax_update_summary',
//       ids,
//       product_type: productType,
//       barwidth: jQuery('#vanwidth').val(),
//       a_frame_length: jQuery('#a_frame_length').val(),
//       shipping: 'standard',
//     },
//     success(response) {
//       if (response?.data?.html) {
//         jQuery('#order_summary').html(response.data.html);
//       }
//     },
//     error(err) {
//       console.log("UpdateSummary error", err.responseText);
//     }
//   });
// }


	function UpdateSummary() {
	    const barwidth = jQuery('#barwidth').val();
	    const aFrame = jQuery('#a_frame_length').val();
	    const support_option = jQuery('input[name="input_1.3"]:checked').val(); // 🔥 FIXED

	    console.log('➡️ UpdateSummary triggered', { ids, barwidth, aFrame, support_option });

	    const productType = jQuery('#product_type').val();

	    ids = jQuery.merge([], mainProductId);
	    jQuery.merge(ids, upsellsProductId);

	    jQuery.ajax({
	        type: 'POST',
	        url: localVars.ajax_url,
	        data: {
	            action: 'woocommerce_ajax_update_summary',
	            ids: ids,
	            product_type: productType,
	            quantity: 1,
	            shipping: 'standard',
	            barwidth: barwidth,
	            a_frame_length: aFrame,
	            support_option: support_option, // 🔥 sends support option to backend
	        },
	        success(response) {
	            console.log('✅ UpdateSummary Response:', response);
	            if (response?.data?.html) {
	                jQuery('#order_summary').html(response.data.html);
	            } else {
	                console.warn('⚠️ No HTML returned');
	            }
	        },
	        error(xhr) {
	            console.error('❌ UpdateSummary failed:', xhr.responseText);
	        },
	    });
	}


	// 🔹 Trigger live update on measurement input changes
	jQuery(document).on('input change', '#barwidth, input[name="input_1.3"], #a_frame_length', function() {
		console.log("update input values");
		UpdateSummary(); // Recalculate instantly on any change
	});



} );
