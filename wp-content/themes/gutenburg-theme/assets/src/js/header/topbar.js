jQuery.fn.topBar = function() {
	return this.each( function() {
		const element = jQuery( this );

		if ( 'undefined' !== typeof setWithExpiry ) {
			const topbar = getWithExpiry( 'topbar' );
			if ( null == topbar ) {
				jQuery( 'body' ).addClass( 'have-topbar' );
				element.show();
			} else {
				jQuery( 'body' ).removeClass( 'have-topbar' );
				element.hide();
			}
		} else {
			console.log(
				'Please add the local-data dependency for the code to work fully -> topbar lib'
			);
		}

		jQuery( 'body' ).css( '--gb_header-height', element.outerHeight() + 'px' );
		if ( jQuery( 'body' ).hasClass( 'logged-in' ) ) {
			jQuery( 'body' ).css( '--gb_header-top', ( parseFloat( element.outerHeight() ) - parseFloat( jQuery( '#wpadminbar' ).innerHeight() ) ) * -1 + 'px' );
			jQuery( 'body' ).css( '--gb_header-top-default', jQuery( '#wpadminbar' ).innerHeight() + 'px' );
		} else {
			jQuery( 'body' ).css( '--gb_header-top', '-' + element.outerHeight() + 'px' );
			jQuery( 'body' ).css( '--gb_header-top-default', '0px' );
		}

		jQuery( window ).resize( function() {
			jQuery( 'body' ).css( '--gb_header-height', element.outerHeight() + 'px' );
			if ( jQuery( 'body' ).hasClass( 'logged-in' ) ) {
				jQuery( 'body' ).css( '--gb_header-top', ( parseFloat( element.outerHeight() ) - parseFloat( jQuery( '#wpadminbar' ).innerHeight() ) ) * -1 + 'px' );
				jQuery( 'body' ).css( '--gb_header-top-default', jQuery( '#wpadminbar' ).innerHeight() + 'px' );
			} else {
				jQuery( 'body' ).css( '--gb_header-top', '-' + element.outerHeight() + 'px' );
				jQuery( 'body' ).css( '--gb_header-top-default', '0px' );
			}
		} );
		const toggleTopbar = () => {
			jQuery( 'body' ).addClass( 'hide-topbar' ).removeClass( 'have-topbar' );
			if ( typeof setWithExpiry !== 'undefined' ) {
				setWithExpiry( 'topbar', 'closed', 0, 86400 * 60 * 24 );
			}
		};

		element.find( '.top-bar-cross' ).on( 'click', toggleTopbar ).on( 'focus', function() {
			jQuery( this ).on( 'keydown', ( e ) => {
				if ( e.key === 'Enter' ) {
					toggleTopbar();
				}
			} );
		} );

		jQuery( document ).on( 'keydown', ( e ) => {
			if ( e.key === 'Escape' ) {
				toggleTopbar();
			}
		} );

		function getWithExpiry( key ) {
			const itemStr = localStorage.getItem( key );
			if ( ! itemStr ) {
				return null;
			}
			const item = JSON.parse( itemStr );
			const now = new Date();
			if ( now.getTime() > item.expiry ) {
				localStorage.removeItem( key );
				return null;
			}
			return item;
		}
		function setWithExpiry( key, value, discount = 0, ttl ) {
			const now = new Date();
			const item = {
				value,
				discount,
				expiry: now.getTime() + ttl,
			};
			localStorage.setItem( key, JSON.stringify( item ) );
		}
	} );
};
