
jQuery( function() {
	const storageKey = 'order_form_v2';
	const ttlMs = 48 * 60 * 60 * 1000; // 48 hours
	const restorePollMs = 200;
	const restoreMaxTries = 25;
	let isRestoring = false;

	// Make isRestoring accessible globally for other scripts
	window.isRestoringFormData = false;

	function saveData( data ) {
		const payload = { ts: Date.now(), data };
		try {
			localStorage.setItem( storageKey, JSON.stringify( payload ) );
		} catch ( e ) {
			// ignore storage errors (quota / private mode)
		}
	}
	function loadData() {
		try {
			const raw = localStorage.getItem( storageKey );
			if ( ! raw ) {
				return null;
			}
			const parsed = JSON.parse( raw );
			if ( ! parsed || ! parsed.data || ! parsed.ts ) {
				return null;
			}
			if ( Date.now() - parsed.ts > ttlMs ) {
				localStorage.removeItem( storageKey );
				return null;
			}
			return parsed.data;
		} catch ( e ) {
			return null;
		}
	}
	function collect() {
		const data = {};
		jQuery( '#orderForm' )
			.find( ':input[name],input,select,textarea,text' )
			.each( function() {
				const el = jQuery( this );
				const name = el.attr( 'name' ) || el.attr( 'id' );
				if ( ! name ) {
					return;
				}
				const t = ( el.attr( 'type' ) || '' ).toLowerCase();
				if ( t === 'file' ) {
					return;
				}
				if ( t === 'checkbox' ) {
					if ( el.hasClass( 'acc-upsell' ) ) {
						if ( ! Array.isArray( data.acc_upsells ) ) {
							data.acc_upsells = [];
						}
						if ( this.checked ) {
							data.acc_upsells.push( String( el.data( 'product-id' ) ) );
						}
					} else if ( name === 'support_pockets' ) {
						data.support_pockets_checkbox = this.checked ? '1' : '0';
					} else {
						data[ name ] = this.checked ? '1' : '0';
					}
				} else if ( t === 'radio' ) {
					if ( el.closest( '.stone-stomper-supports' ).length ) {
						if ( typeof data.support_pockets === 'undefined' ) {
							data.support_pockets = false;
							data.toolbox = false;
							data.factory_stoneguard = false;
						}
						if ( el.is( ':checked' ) ) {
							const val = el.val();
							if ( val === 'support_pockets' ) {
								data.support_pockets = true;
							}
							if ( val === 'toolbox' ) {
								data.toolbox = true;
							}
							if ( val === 'factory-stoneguard' ) {
								data.factory_stoneguard = true;
							}
						}
					} else if ( this.checked ) {
						data[ name ] = el.val();
					}
				} else {
					data[ name ] = el.val();
				}
			} );
		return data;
	}
	function autosave() {
		if ( isRestoring ) {
			return;
		}
		saveData( collect() );
	}
	function setValueForField( name, value ) {
		const $elements = jQuery( '[name="' + name + '"], #' + name );
		if ( ! $elements.length ) {
			return;
		}
		$elements.each( function() {
			const $el = jQuery( this );
			const t = ( $el.attr( 'type' ) || '' ).toLowerCase();
			if ( t === 'file' ) {
				return;
			}
			if ( t === 'checkbox' ) {
				if ( $el.hasClass( 'acc-upsell' ) ) {
					if ( Array.isArray( value ) ) {
						const pid = String( $el.data( 'product-id' ) );
						$el.prop( 'checked', value.includes( pid ) );
					}
				} else {
					$el.prop( 'checked', value === true || value === '1' || value === 1 ).trigger( 'change' );
				}
				return;
			}
			if ( t === 'radio' ) {
				if ( String( $el.val() ) === String( value ) ) {
					$el.prop( 'checked', true ).trigger( 'change' );
				}
				return;
			}
			if ( $el.is( 'select' ) ) {
				if ( $el.find( 'option[value="' + value + '"]' ).length ) {
					$el.val( value ).trigger( 'change' );
				}
				return;
			}
			$el.val( value );
		} );
	}
	function parseJsonArray( value ) {
		if ( Array.isArray( value ) ) {
			return value;
		}
		if ( typeof value !== 'string' ) {
			return [];
		}
		try {
			const parsed = JSON.parse( value );
			return Array.isArray( parsed ) ? parsed : [];
		} catch ( e ) {
			return [];
		}
	}
	function restorePhotoList( slot, savedData ) {
		const fieldMap = { hitch: 'hitch_ids', rear: 'rear_ids', front: 'front_ids' };
		const listMap = { hitch: 'list_hitch', rear: 'list_rear', front: 'list_front' };
		const fieldName = fieldMap[ slot ];
		const listId = listMap[ slot ];
		if ( ! fieldName || ! listId ) {
			return;
		}
		const urls = parseJsonArray( savedData[ fieldName ] );
		if ( ! urls.length ) {
			return;
		}
		const list = document.getElementById( listId );
		if ( ! list ) {
			return;
		}
		list.innerHTML = '';
		urls.forEach( ( url ) => {
			const li = document.createElement( 'li' );
			li.style.display = 'flex';
			li.style.alignItems = 'center';
			li.style.marginBottom = '8px';

			const img = document.createElement( 'img' );
			img.src = url;
			img.style.width = '80px';
			img.style.height = '80px';
			img.style.objectFit = 'cover';
			img.style.marginRight = '10px';
			img.style.border = '1px solid #ccc';
			img.style.borderRadius = '6px';

			const span = document.createElement( 'span' );
			span.textContent = url.split( '/' ).pop() || 'Uploaded image';

			const status = document.createElement( 'em' );
			status.style.marginLeft = '8px';
			status.textContent = 'Uploaded';

			li.appendChild( img );
			li.appendChild( span );
			li.appendChild( status );
			list.appendChild( li );
		} );
	}
	function reapplyMeasurements( savedData ) {
		const keys = [
			'barwidth_mm',
			'a_frame_length_mm',
			'toolbox_width_mm',
			'toolbox_length_mm',
			'stoneguard_width_mm',
			'stoneguard_length_mm',
			'support_pocket_length_mm',
			'vinyl_width_mm',
			'vinyl_length_mm',
			'additional_hitch_measurement',
		];
		keys.forEach( ( key ) => {
			if ( typeof savedData[ key ] !== 'undefined' && savedData[ key ] !== '' ) {
				setValueForField( key, savedData[ key ] );
			}
		} );
	}
	function waitForOptionAndSet( selector, value, done ) {
		let tries = 0;
		const timer = setInterval( () => {
			tries += 1;
			const $select = jQuery( selector );
			if ( ! $select.length ) {
				if ( tries >= restoreMaxTries ) {
					clearInterval( timer );
					if ( done ) {
						done();
					}
				}
				return;
			}
			const directMatch = $select.find( 'option[value="' + value + '"]' );
			if ( directMatch.length ) {
				$select.val( value ).trigger( 'change' );
				clearInterval( timer );
				if ( done ) {
					done();
				}
				return;
			}
			// Fallback: match by data-post-id (AJAX-populated options)
			const dataMatch = $select.find( 'option[data-post-id="' + value + '"]' );
			if ( dataMatch.length ) {
				$select.val( dataMatch.first().val() ).trigger( 'change' );
				clearInterval( timer );
				if ( done ) {
					done();
				}
				return;
			}
			// Fallback: match by option text if values differ
			const valueText = String( value ).trim().toLowerCase();
			let matchedValue = null;
			$select.find( 'option' ).each( function() {
				const optText = jQuery( this ).text().trim().toLowerCase();
				if ( optText && optText === valueText ) {
					matchedValue = jQuery( this ).val();
					return false;
				}
				return true;
			} );
			if ( matchedValue !== null ) {
				$select.val( matchedValue ).trigger( 'change' );
				clearInterval( timer );
				if ( done ) {
					done();
				}
				return;
			}
			if ( tries >= restoreMaxTries ) {
				clearInterval( timer );
				if ( done ) {
					done();
				}
			}
		}, restorePollMs );
	}
	function waitForOptionPromise( selector, value ) {
		return new Promise( ( resolve ) => {
			waitForOptionAndSet( selector, value, resolve );
		} );
	}
	function restoreDependentSelects( savedData, done ) {
		const tasks = [];

		if ( savedData.product_type ) {
			tasks.push( waitForOptionPromise( '#product_type', savedData.product_type ) );
		}

		if ( savedData.vehicle_make ) {
			tasks.push(
				waitForOptionPromise( '#veh_make', savedData.vehicle_make ).then( () => {
					if ( savedData.vehicle_model ) {
						return waitForOptionPromise( '#veh_model', savedData.vehicle_model ).then( () => {
							if ( savedData.vehicle_year ) {
								return waitForOptionPromise( '#veh_year', savedData.vehicle_year );
							}
							return null;
						} );
					}
					return null;
				} )
			);
		}

		if ( savedData.caravan_make ) {
			tasks.push(
				waitForOptionPromise( '#van_make', savedData.caravan_make ).then( () => {
					if ( savedData.caravan_model ) {
						return waitForOptionPromise( '#van_model', savedData.caravan_model );
					}
					return null;
				} )
			);
		}

		if ( tasks.length === 0 ) {
			if ( done ) {
				done();
			}
			return;
		}

		Promise.all( tasks ).then( () => {
			if ( done ) {
				done();
			}
		} );
	}
	function restoreSupportRadios( savedData ) {
		if ( savedData.support_pockets === true ) {
			jQuery( 'input[type="radio"]#support_pockets' ).prop( 'checked', true ).trigger( 'change' );
		} else if ( savedData.toolbox === true ) {
			jQuery( 'input[type="radio"]#toolbox' ).prop( 'checked', true ).trigger( 'change' );
		} else if ( savedData.factory_stoneguard === true ) {
			jQuery( 'input[type="radio"]#factory_stoneguard' ).prop( 'checked', true ).trigger( 'change' );
		}
	}
	function restoreAll() {
		const savedData = loadData();
		if ( ! savedData ) {
			return;
		}
		isRestoring = true;
		window.isRestoringFormData = true;
		Object.keys( savedData ).forEach( ( key ) => {
			if ( key === 'acc_upsells' || key === 'support_pockets' || key === 'toolbox' || key === 'factory_stoneguard' ) {
				return;
			}
			if ( key === 'vehicle_make' || key === 'vehicle_model' || key === 'vehicle_year' || key === 'caravan_make' || key === 'caravan_model' || key === 'product_type' ) {
				return;
			}
			setValueForField( key, savedData[ key ] );
		} );
		if ( Array.isArray( savedData.acc_upsells ) ) {
			jQuery( '.acc-upsell' ).each( function() {
				const pid = String( jQuery( this ).data( 'product-id' ) );
				jQuery( this ).prop( 'checked', savedData.acc_upsells.includes( pid ) ).trigger( 'change' );
			} );
		}
		if ( typeof savedData.support_pockets_checkbox !== 'undefined' ) {
			jQuery( 'input[type="checkbox"][name="support_pockets"]' ).prop( 'checked', savedData.support_pockets_checkbox === '1' );
		}
		restoreSupportRadios( savedData );
		restorePhotoList( 'hitch', savedData );
		restorePhotoList( 'rear', savedData );
		restorePhotoList( 'front', savedData );

		restoreDependentSelects( savedData, () => {
			setTimeout( () => {
				reapplyMeasurements( savedData );
				jQuery( '#caravan-details input, #caravan-details select, #caravan-details textarea' ).trigger( 'change' );
				jQuery( '#bar-options-section input, #bar-options-section select, #bar-options-section textarea' ).trigger( 'change' );
				isRestoring = false;
				window.isRestoringFormData = false;
				jQuery( '#barwidth, #a_frame_length' ).trigger( 'change' );
				autosave();
			}, 800 );
		} );
	}

	jQuery( '#orderForm' ).on( 'input change', 'input,select,textarea', autosave );
	jQuery( '#orderForm' ).on( 'submit', function() {
		autosave();
	} );
	jQuery( '#clear-order-form' ).on( 'click', function() {
		try {
			localStorage.removeItem( storageKey );
		} catch ( e ) {
			// ignore
		}
		const form = document.getElementById( 'orderForm' );
		if ( form ) {
			form.reset();
		}
		jQuery( '.veh_make_other, .veh_model_other, .veh_year_other, .van_model_other' ).empty();
		jQuery( '#veh_model, #veh_year, #van_model' ).show().prop( 'required', true );
		jQuery( '#list_hitch, #list_rear, #list_front' ).empty();
		jQuery( '#hitch_ids, #rear_ids, #front_ids' ).val( '' ).trigger( 'change' );
		jQuery( '#caravan-details input, #caravan-details select, #caravan-details textarea' ).trigger( 'change' );
		jQuery( '#bar-options-section input, #bar-options-section select, #bar-options-section textarea' ).trigger( 'change' );
		jQuery( '#photographs-details, #final-measurements, #final-summary' ).removeClass( 'section-disable' );
	} );

	restoreAll();
} );
