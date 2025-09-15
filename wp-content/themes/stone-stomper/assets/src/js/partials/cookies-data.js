
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
		jQuery( '#orderForm' ).find( 'input,select,textarea' ).each( function() {
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
		console.log( 'collected', data );
		return data;
	}
	// function applyValueLater( setter, tries ) {
	// 	let left = tries || 12; function tick() {
	// 		if ( setter() ) {
	// 			return;
	// 		} if ( --left <= 0 ) {
	// 			return;
	// 		} setTimeout( tick, 400 );
	// 	} tick();
	// }
	// function restore() {
	// 	const data = loadData(); if ( ! data ) {
	// 		return;
	// 	}
	// 	Object.keys( data ).forEach( function( k ) {
	// 		const val = data[ k ];
	// 		if ( k === 'acc_upsells' ) {
	// 			const ids = Array.isArray( val ) ? val : [];
	// 			jQuery( '.acc-upsell' ).each( function() {
	// 				this.checked = ids.indexOf( String( jQuery( this ).data( 'product-id' ) ) ) !== -1;
	// 			} );
	// 			return;
	// 		}
	// 		const els = jQuery( '#orderForm [name="' + k + '"], #orderForm #' + k );
	// 		if ( ! els.length ) {
	// 			return;
	// 		}
	// 		els.each( function() {
	// 			const el = jQuery( this ),
	// 				type = ( el.attr( 'type' ) || '' ).toLowerCase();
	// 			if ( type === 'checkbox' ) {
	// 				this.checked = ( val === '1' || val === 1 || val === true || val === 'true' );
	// 			} else if ( this.tagName === 'SELECT' ) {
	// 				const setSelect = function() {
	// 					if ( el.find( 'option[value="' + val + '"]' ).length ) {
	// 						el.val( val ).trigger( 'change' ); return true;
	// 					} return false;
	// 				};
	// 				if ( ! setSelect() ) {
	// 					applyValueLater( setSelect, 12 );
	// 				}
	// 			} else {
	// 				el.val( val );
	// 			}
	// 		} );
	// 	} );
	// }
	function autosave() {
		saveData( collect() );
		console.log( 'sava' );
	}
	jQuery( '#orderForm' ).on( 'input change', 'input,select,textarea', autosave );
	jQuery( '#orderForm' ).on( 'submit', function() {
		autosave();
	} );

	// restore();
} );

