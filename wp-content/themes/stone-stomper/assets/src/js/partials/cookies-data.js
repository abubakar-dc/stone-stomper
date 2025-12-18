
jQuery( function() {
	const days = 7,
		chunkSize = 3000,
		prefix = 'order_form';
	function setC( n, v, d ) {
		document.cookie = n + '=' + encodeURIComponent( v ) + ';path=/;max-age=' + ( d * 86400 );
	}
	function getC( n ) {
		const m = document.cookie.match( new RegExp( '(?:^|; )' + n.replace( /([.$?*|{}()\[\]\\\/\+^])/g, '\\$1' ) + '=([^;]*)' ) ); return m ? decodeURIComponent( m[ 1 ] ) : null;
	}
	function delC( n ) {
		document.cookie = n + '=;path=/;max-age=0';
	}
	function saveData( data ) {
		const json = JSON.stringify( data );
		delC( prefix ); const parts = parseInt( getC( prefix + '_parts' ) || '0', 10 ); if ( parts > 0 ) {
			for ( var i = 0; i < parts; i++ ) {
				delC( prefix + '_' + i );
			} delC( prefix + '_parts' );
		}
		if ( json.length <= chunkSize ) {
			setC( prefix, json, days ); return;
		}
		const count = Math.ceil( json.length / chunkSize );
		setC( prefix + '_parts', String( count ), days );
		for ( var i = 0; i < count; i++ ) {
			setC( prefix + '_' + i, json.slice( i * chunkSize, ( i + 1 ) * chunkSize ), days );
		}
	}
	function loadData() {
		let json = getC( prefix );
		if ( ! json ) {
			const parts = parseInt( getC( prefix + '_parts' ) || '0', 10 ); if ( parts > 0 ) {
				let s = ''; for ( let i = 0; i < parts; i++ ) {
					s += getC( prefix + '_' + i ) || '';
				} json = s;
			}
		}
		if ( ! json ) {
			return null;
		}
		try {
			return JSON.parse( json );
		} catch ( e ) {
			return null;
		}
	}
	function collect() {
		const data = {};
		jQuery( '#orderForm' ).find( ':input[name],input,select,textarea,text' ).each( function() {
			const el = jQuery( this ),
				name = el.attr( 'name' ) || el.attr( 'id' ); if ( ! name ) {
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
				} else {
					data[ name ] = this.checked ? '1' : '0';
				}
			} else {
				data[ name ] = el.val();
			}
		} );
		return data;
	}
	function autosave() {
		saveData( collect() );
	}
	jQuery( '#orderForm' ).on( 'input change', 'input,select,textarea', autosave );
	jQuery( '#orderForm' ).on( 'submit', function() {
		autosave();
	} );

	function restoreAllFields( data ) {
		Object.keys( data ).forEach( function( key ) {
			const el = jQuery( '[name="' + key + '"], #' + key );

			if ( ! el.length ) {
				return;
			}

			const type = ( el.attr( 'type' ) || '' ).toLowerCase();

			if ( type === 'checkbox' ) {
				el.prop( 'checked', data[ key ] === '1' ).trigger( 'change' );
			} else if ( type === 'radio' ) {
				jQuery( '[name="' + key + '"][value="' + data[ key ] + '"]' )
					.prop( 'checked', true )
					.trigger( 'change' );
			} else {
				el.val( data[ key ] ).trigger( 'change' );
			}
		} );
	}

	function removeSectionDisable() {
		jQuery( '.section-disable' ).each( function() {
			jQuery( this )
				.removeClass( 'section-disable' )
				.find( 'input, select, textarea, button' )
				.prop( 'disabled', false );
		} );
	}

	function addSectionDisable() {
		jQuery( '[data-section-lock="true"]' ).each( function() {
			jQuery( this )
				.addClass( 'section-disable' )
				.find( 'input, select, textarea, button' )
				.prop( 'disabled', false );
		} );
	}

	const savedData = loadData();
	if ( savedData ) {
		restoreAllFields( savedData );

		if ( savedData.support_pockets === true ) {
			jQuery( '#support_pockets' ).prop( 'checked', true ).trigger( 'change' );
		} else if ( savedData.toolbox === true ) {
			jQuery( '#toolbox' ).prop( 'checked', true ).trigger( 'change' );
		} else if ( savedData.factory_stoneguard === true ) {
			jQuery( '#factory_stoneguard' ).prop( 'checked', true ).trigger( 'change' );
		}
		jQuery( '#product_type' ).trigger( 'change' );

		setTimeout( function() {
			removeSectionDisable();
		}, 400 );
	} else {
		setTimeout( function() {
			addSectionDisable();
		}, 400 );
	}

	// restore();
} );

