
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

	// 🟢 Support Pockets restore on load (and other radios)
	jQuery( function() {
		const days = 7,
			chunkSize = 3000,
			prefix = 'order_form';

		function setC( n, v, d ) {
			document.cookie = n + '=' + encodeURIComponent( v ) + ';path=/;max-age=' + ( d * 86400 );
		}
		function getC( n ) {
			const m = document.cookie.match( new RegExp( '(?:^|; )' + n.replace( /([.$?*|{}()\[\]\\\/\+^])/g, '\\$1' ) + '=([^;]*)' ) );
			return m ? decodeURIComponent( m[ 1 ] ) : null;
		}
		function delC( n ) {
			document.cookie = n + '=;path=/;max-age=0';
		}
		function saveData( data ) {
			const json = JSON.stringify( data );
			delC( prefix );
			const parts = parseInt( getC( prefix + '_parts' ) || '0', 10 );
			if ( parts > 0 ) {
				for ( var i = 0; i < parts; i++ ) {
					delC( prefix + '_' + i );
				}
				delC( prefix + '_parts' );
			}
			if ( json.length <= chunkSize ) {
				setC( prefix, json, days );
				return;
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
				const parts = parseInt( getC( prefix + '_parts' ) || '0', 10 );
				if ( parts > 0 ) {
					let s = '';
					for ( let i = 0; i < parts; i++ ) {
						s += getC( prefix + '_' + i ) || '';
					}
					json = s;
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
			jQuery( '#orderForm' )
				.find( ':input[name],input,select,textarea,text' )
				.each( function() {
					const el = jQuery( this ),
						name = el.attr( 'name' ) || el.attr( 'id' );
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
						} else {
							data[ name ] = this.checked ? '1' : '0';
						}
					}
					// 🟢 Handle radio buttons, including support options
					else if ( t === 'radio' ) {
						if ( el.closest('.stone-stomper-supports').length ) {
							// Initialize only once per collect()
							if (typeof data.support_pockets === 'undefined') {
								data.support_pockets = false;
								data.toolbox = false;
								data.factory_stoneguard = false;
							}

							if (el.is(':checked')) {
								const val = el.val(); // e.g. support_pockets, toolbox, factory-stoneguard
								if (val === 'support_pockets') data.support_pockets = true;
								if (val === 'toolbox') data.toolbox = true;
								if (val === 'factory-stoneguard') data.factory_stoneguard = true;
							}
						} else if (this.checked) {
							data[name] = el.val();
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

		// 🟢 Restore support radio selections (true/false) on reload
		const savedData = loadData();
		console.log(savedData);
		if ( savedData ) {
		// Support Pockets Radios
			if ( savedData.support_pockets === true ) {
				jQuery( '#support_pockets' ).prop( 'checked', true ).trigger( 'change' );
			} else if ( savedData.toolbox === true ) {
				jQuery( '#toolbox' ).prop( 'checked', true ).trigger( 'change' );
			} else if ( savedData.factory_stoneguard === true ) {
				jQuery( '#factory_stoneguard' ).prop( 'checked', true ).trigger( 'change' );
			}
		}
	} );

	// restore();
} );

