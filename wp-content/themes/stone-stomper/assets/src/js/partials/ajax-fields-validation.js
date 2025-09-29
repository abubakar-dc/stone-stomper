jQuery( document ).ready( function( $ ) {
	function formValidationOne() {
		let isValid = true;
		const fieldData = {};

		$( '#details-section' ).find( 'input[required], select[required]' ).each( function() {
			const $field = $( this );
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
			$( '#vehicle-details' ).removeClass( 'section-disable' );
			// console.log( '✅ All fields filled.' );
			// console.log( '📝 Filled field data:', fieldData );
		} else {
			console.log( '❌ Some required fields are still empty.' );
		}
	}

	// Listen for input and change events on required fields
	$( '#details-section' ).on( 'input change', 'input[required], select[required]', function() {
		formValidationOne();
	} );

	function validateVehicleSection() {
		let isValid = true;
		const fieldData = {};

		$( '#blk-vehicle' ).find( 'input[required], select[required]' ).each( function() {
			const $field = $( this );
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
	$( '#blk-vehicle' ).on( 'input change', 'input[required], select[required]', function() {
		validateVehicleSection();
	} );

	function validateCaravanSection() {
		let isValid = true;
		const fieldData = {};

		// Check all required fields in the caravan section
		$( '#blk-caravan' ).find( 'select[required], input[required]' ).each( function() {
			const $field = $( this );
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
			console.log( '✅ All caravan fields are filled.' );
			console.log( '🏕️ Caravan data:', fieldData );
		} else {
			console.log( '❌ Some required caravan fields are still empty.' );
		}
	}

	// Auto-validate when any required input/select changes
	$( '#blk-caravan' ).on( 'input change', 'input[required], select[required]', function() {
		validateCaravanSection();
	} );

	   function validatePhotoUploads() {
		let isValid = true;
		const photoData = {};

		const fields = [
			{ id: 'hitch_ids', label: 'Hitch Photograph' },
			{ id: 'rear_ids', label: 'Towing Vehicle Rear Photograph' },
			{ id: 'front_ids', label: 'Front of Caravan Photograph' },
		];

		fields.forEach( ( field ) => {
			const $input = $( '#' + field.id );
			const value = $input.val()?.trim();

			if ( ! value || value === '[]' ) {
				isValid = false;
				$input.addClass( 'field-error' );
				console.warn( `❌ Missing: ${ field.label }` );
			} else {
				$input.removeClass( 'field-error' );
				photoData[ field.id ] = JSON.parse( value );
			}
		} );

		if ( isValid ) {
			jQuery( '#final-measurements' ).removeClass( 'section-disable' );
			jQuery( '#final-summary' ).removeClass( 'section-disable' );

			// console.log( '✅ All required photographs uploaded.' );
			// console.log( '🖼️ Uploaded photo IDs:', photoData );
		} else {
			console.log( '❌ Some required photographs are still missing.' );
		}
	}

	// Run validation when file inputs change or hidden input values update
	$( '#blk-photos' ).on( 'change', 'input[type="file"]', function() {
		setTimeout( validatePhotoUploads, 1000 ); // Delay to allow upload script to update hidden fields
	} );

	// Optional: Revalidate if hidden fields change via JS
	$( '#blk-photos' ).on( 'change', 'input[type="hidden"]', function() {
		validatePhotoUploads();
	} );
} );
