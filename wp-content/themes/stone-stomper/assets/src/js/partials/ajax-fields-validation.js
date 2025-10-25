jQuery( document ).ready( function( $ ) {
	function formValidationOne() {
		let isValid = true;
		const fieldData = {};

		jQuery( '#details-section' ).find( 'input[required], select[required]' ).each( function() {
			const $field = jQuery( this );
			const value = $field.val()?.trim();
			const fieldName = $field.attr( 'name' );

			if ( ! value ) {
				isValid = false;
				$field.addClass( 'field-error' );
			} else {
				$field.removeClass( 'field-error' );
				fieldData[ fieldName ] = value;
			}
		} );

		if ( isValid ) {
			jQuery( '#vehicle-details' ).removeClass( 'section-disable' );
			// console.log( '✅ All fields filled.' );
			// console.log( '📝 Filled field data:', fieldData );
		} else {
			console.log( '❌ Some required fields are still empty.' );
		}
	}

	// Listen for input and change events on required fields
	jQuery( '#details-section' ).on( 'input change', 'input[required], select[required]', function() {
		formValidationOne();
	} );

	function validateVehicleSection() {
		let isValid = true;
		const fieldData = {};

		jQuery( '#blk-vehicle' ).find( 'input[required], select[required]' ).each( function() {
			const $field = jQuery( this );
			const value = $field.val()?.trim();
			const fieldName = $field.attr( 'name' );

			if ( ! value ) {
				isValid = false;
				$field.addClass( 'field-error' );
			} else {
				$field.removeClass( 'field-error' );
				fieldData[ fieldName ] = value;
			}
		} );

		if ( isValid ) {
			jQuery( '#caravan-details' ).removeClass( 'section-disable' );
			// console.log( '✅ All vehicle fields are filled.' );
			// console.log( '🚗 Vehicle data:', fieldData );
		} else {
			console.log( '❌ Some required vehicle fields are still empty.' );
		}
	}

	// Run validation live when any input/select changes in #blk-vehicle
	jQuery( '#blk-vehicle' ).on( 'input change', 'input[required], select[required]', function() {
		validateVehicleSection();
	} );

	function validateCaravanSection() {
		let isValid = true;
		const fieldData = {};

		// Check all required fields in the caravan section
		jQuery( '#blk-caravan' ).find( 'select[required], input[required]' ).each( function() {
			const $field = jQuery( this );
			const value = $field.val()?.trim();
			const fieldName = $field.attr( 'name' );

			if ( ! value ) {
				isValid = false;
				$field.addClass( 'field-error' );
			} else {
				$field.removeClass( 'field-error' );
				fieldData[ fieldName ] = value;
			}
		} );

		if ( isValid ) {
			jQuery( '#photographs-details' ).removeClass( 'section-disable' );
			jQuery( '#final-measurements' ).removeClass( 'section-disable' );
			console.log( '✅ All caravan fields are filled.' );
			console.log( '🏕️ Caravan data:', fieldData );
		} else {
			console.log( '❌ Some required caravan fields are still empty.' );
		}
	}

	// Auto-validate when any required input/select changes
	jQuery( '#blk-caravan' ).on( 'input change', 'input[required], select[required]', function() {
		validateCaravanSection();
	} );

function validatePhotoUploads() {
	let allUploaded = true; // ✅ will only stay true if all 3 images are uploaded
	const photoData = {};

	const fields = [
		{ id: 'hitch_ids', label: 'Hitch Photograph' },
		{ id: 'rear_ids', label: 'Towing Vehicle Rear Photograph' },
		{ id: 'front_ids', label: 'Front of Caravan Photograph' },
	];

	fields.forEach((field) => {
		const $input = jQuery('#' + field.id);
		const value = $input.val()?.trim();

		// Debugging (optional)
		console.log(`${field.label}:`, value);

		// Check if the field is empty or still has []
		if (!value || value === '[]') {
			allUploaded = false; // ❌ mark as incomplete
			$input.addClass('field-error');
		} else {
			$input.removeClass('field-error');

			// Try to parse uploaded IDs
			try {
				const parsedValue = JSON.parse(value);
				if (!Array.isArray(parsedValue) || parsedValue.length === 0) {
					allUploaded = false;
					$input.addClass('field-error');
				} else {
					photoData[field.id] = parsedValue;
				}
			} catch (e) {
				allUploaded = false;
				$input.addClass('field-error');
				console.warn(`⚠️ Invalid JSON for ${field.label}`);
			}
		}
	});

	// ✅ If all 3 uploaded, enable final sections
	if (allUploaded) {
		jQuery('#final-measurements').removeClass('section-disable');
		jQuery('#final-summary').removeClass('section-disable');
		console.log('✅ All 3 photos uploaded successfully!');
		console.log('🖼️ Photo data:', photoData);
	} else {
		// ❌ If even one missing, keep section disabled
		jQuery('#final-measurements').addClass('section-disable');
		jQuery('#final-summary').addClass('section-disable');
		console.log('❌ One or more required photographs missing.');
	}
}


// Run validation when photo upload completes
jQuery('#blk-photos').on('change', 'input[type="file"]', function() {
	setTimeout(validatePhotoUploads, 1500); // delay to allow upload script to update hidden fields
});

// Also revalidate if hidden inputs change (e.g. after upload AJAX)
jQuery('#blk-photos').on('change', 'input[type="hidden"]', function() {
	validatePhotoUploads();
});
} );
