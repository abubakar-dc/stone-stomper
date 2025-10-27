/**
 * Sticky Header
 * Adds a class to header on scroll
 */
import slick from '../vendors/slick.min';
import Lity from '../vendors/lity.js';

jQuery( document ).on( 'scroll', function() {
	if ( jQuery( document ).scrollTop() > 0 ) {
		jQuery( 'header, body' ).addClass( 'shrink' );
	} else {
		jQuery( 'header, body' ).removeClass( 'shrink' );
	}
} );

jQuery( document ).ready( function() {
	jQuery( '#veh_make' ).on( 'change', function() {
		if ( jQuery( this ).val() === 'other' ) {
			const yearContainer = jQuery( '.veh_year_other' ); // assuming veh_year is inside a wrapper
			const vehMakeContainer = jQuery( '.veh_make_other' ); // assuming veh_year is inside a wrapper
			const vehModelContainer = jQuery( '.veh_model_other' ); // assuming veh_year is inside a wrapper

			jQuery( '#veh_model' ).hide();
			jQuery( '#veh_year' ).hide();

			vehMakeContainer.append(
				'<input placeholder="Other Make" id="veh_make_other" name="vehicle_make_other" type="text" />'
			);
			vehModelContainer.append(
				'<input style="margin-top:10px" placeholder="Vehicle Model" id="vehicle_model_other" name="vehicle_model" type="text" />'
			);
			// Append input if not already present
			yearContainer.append(
				'<input placeholder="Model Year" id="veh_year_other" name="vehicle_year" type="text" />'
			);
		} else {
			jQuery( '#veh_model' ).show();
			jQuery( '#veh_year' ).show();

			jQuery( '.veh_make_other input' ).remove();
			jQuery( '.veh_model_other input' ).remove();
			jQuery( '.veh_year_other input' ).remove();
		}
	} );

	jQuery( '#van_make' ).on( 'change', function() {
		if ( jQuery( this ).val() === 'other' ) {
			const vanModelContainer = jQuery( '.van_model_other' ); // assuming veh_year is inside a wrapper

			jQuery( '#van_model' ).hide();

			vanModelContainer.append(
				'<input placeholder="Caravan Model" id="van_model_other" name="caravan_model" type="text" />'
			);
		} else {
			jQuery( '#van_model' ).show();
			jQuery( '.van_model_other input' ).remove();
		}
	} );

	jQuery( '#van_model' ).on( 'change', function() {
		if ( jQuery( this ).val() === 'other' ) {
			const vanModelContainer = jQuery( '.van_model_other' ); // assuming veh_year is inside a wrapper
			vanModelContainer.append(
				'<input placeholder="Caravan Model" id="van_model_other" name="caravan_model" type="text" />'
			);
		} else {
			jQuery( '.van_model_other input' ).remove();
		}
	} );

	// Optional: trigger on page load in case "Other" is pre-selected
	jQuery( '#veh_make' ).trigger( 'change' );
} );

jQuery( function() {
	/**
	 * Header Wrapper Height Calculation for Navigation Overlay
	 */

	if ( jQuery( '.header-wrapper' ).length > 0 ) {
		function updateHeaderHeight() {
			jQuery( '.header-wrapper' ).each( function() {
				jQuery( 'body.woocommerce-account, body.woocommerce-checkout, body.woocommerce-cart, body.woocommerce-shop' ).css( '--ss_header-wrapper-default', jQuery( this ).outerHeight() + 'px' );
			} );
		}
		updateHeaderHeight();
		jQuery( window ).resize( updateHeaderHeight );
	}

	/**
	 * Toggle menu for mobile
	 */
	const navOverlay = jQuery( '.nav-overlay' );
	const htmlBody = jQuery( 'html, body' );

	jQuery( '.menu-btn' ).on( 'click', function() {
		jQuery( this ).toggleClass( 'active' );
		navOverlay.toggleClass( 'open' );
		htmlBody.toggleClass( 'no-overflow' );
		jQuery( '.header-section' ).toggleClass( 'menu-open' );
		jQuery( '.header-nav ul li.active' ).removeClass( 'active' );
		jQuery( '.header-nav ul.sub-menu' ).slideUp();
	} );

	/**
	 * Add span tag to multi-level accordion menu for mobile menus
	 */

	jQuery( '.menu-item-has-children > a:first-child' ).each( function() {
		jQuery( this ).after( '<span class="submenu-icon"></span>' );
	} );

	/**
	 * Slide Up/Down internal sub-menu when mobile menu arrow clicked
	 */

	jQuery( '.header-nav' ).on( 'click', '.submenu-icon', function() {
		const parentLi = jQuery( this ).closest( 'li' );

		parentLi.siblings( '.active' )
			.removeClass( 'active' )
			.find( 'ul' ).slideUp();

		parentLi.toggleClass( 'active' ).find( 'ul' ).stop( true, true ).slideToggle();
		parentLi.parents( 'ul' ).toggleClass( 'disabled-menu', parentLi.hasClass( 'active' ) );
	} );

	/**
	 *  Accessibility for Simple menu & Mega menu
	 */
	jQuery( '.menu-item-has-children > a' ).on( 'focus blur', function( event ) {
		jQuery( this ).siblings( '.sub-menu, .mega-menu' ).toggleClass( 'focused', event.type === 'focus' );
	} );

	jQuery( '.sub-menu a, .mega-menu a' ).on( 'focus blur', function( event ) {
		jQuery( this ).closest( '.sub-menu, .mega-menu' ).toggleClass( 'focused', event.type === 'focus' );
	} );

	/**
	 * Script for Accessibility of html Tags
	 */
	jQuery( 'h1, h2, h3, h4, h5, h6,p,li,blockquote,cite,strong,dt,dd,th,td,b,i,u,s,em,small,sup,del,ins,abbr,mark,details,pre,kbd,samp,var,address,code,q,figure,figcaption,caption,.top-bar-text,.top-bar-cross,.copy-right,.post-author-img,.post-author-name,.post-meta-date,.post-date' ).each( function() {
		jQuery( this ).attr( {
			tabindex: 0,
		} );
	} );
	jQuery( '.header-nav li, .blog-nav li, .footer-nav li, .legal-nav li' ).each( function() {
		const link = jQuery( this ).find( 'a' );
		if ( link.length > 0 ) {
			jQuery( this ).removeAttr( 'tabindex' );
		} else {
			jQuery( this ).attr( 'tabindex', '0' );
		}
	} );
	jQuery( 'form p' ).each( function() {
		jQuery( this ).removeAttr( 'tabindex' );
	} );

	jQuery( 'a,button:not([href])' ).each( function() {
		jQuery( this ).attr( {
			tabindex: 0,
		} );
	} );

	setTimeout( () => {
		jQuery( '#daextlwcnf-cookie-notice-button-1' ).attr( 'role', 'button' );
		jQuery( '#daextlwcnf-cookie-notice-button-2' ).attr( 'role', 'button' );
		jQuery( '#daextlwcnf-cookie-settings-button-1' ).attr( 'role', 'button' );
		jQuery( '#daextlwcnf-cookie-settings-button-2' ).attr( 'role', 'button' );
	}, 500 );

	autosize();
	function autosize() {
		const text = jQuery( 'textarea' );

		text.each( function() {
			jQuery( this ).attr( 'rows', 5 );
			resize( jQuery( this ) );
		} );

		text.on( 'input', function() {
			resize( jQuery( this ) );
		} );

		function resize( $text ) {
			$text.css( 'min-height', 'auto' );
			$text.css( 'min-height', $text[ 0 ].scrollHeight + 'px' );
		}
	}

	//Form Input

	jQuery( document ).ready( function() {
		function toggleFilledClass( el ) {
			if ( jQuery( el ).val() ) {
				jQuery( el ).addClass( 'filled' );
			} else {
				jQuery( el ).removeClass( 'filled' );
			}
		}

		const selector = 'input[type="text"],input[type="number"],input[type="email"],input[type="tel"],input[type="url"],input[type="search"],input[type="password"],input[type="time"],input[type="date"],input[type="datetime-local"],input[type="week"],input[type="month"],input[type="file"],input[type="range"],input[list],input[type="string"],select,textarea,.gform-text-input-reset';

		jQuery( document ).on( 'input change blur', selector, function() {
			const el = this;
			setTimeout( function() {
				toggleFilledClass( el );
			}, 150 );
		} );

		function scanAndAttach( context ) {
			jQuery( selector, context ).each( function() {
				toggleFilledClass( this );
			} );
		}

		scanAndAttach( document );

		const observer = new MutationObserver( function( mutations ) {
			mutations.forEach( function( mutation ) {
				if ( mutation.type === 'childList' && mutation.addedNodes.length ) {
					jQuery( mutation.addedNodes ).each( function() {
						if ( this.nodeType === 1 ) {
							scanAndAttach( this );
						}
					} );
				}
				if ( mutation.type === 'attributes' && ( mutation.attributeName === 'class' || mutation.attributeName === 'style' || mutation.attributeName === 'hidden' ) ) {
					if ( mutation.target && mutation.target.nodeType === 1 ) {
						scanAndAttach( mutation.target );
					}
				}
			} );
		} );

		observer.observe( document.body, { childList: true, subtree: true, attributes: true, attributeFilter: [ 'class', 'style', 'hidden' ] } );

		let tries = 0;
		var poll = setInterval( function() {
			scanAndAttach( document );
			tries++;
			if ( tries > 12 ) {
				clearInterval( poll );
			}
		}, 250 );
	} );

	// Slider

	if ( jQuery( '.form-image-slider' ).length ) {
		jQuery( '.form-image-slider' ).each( function() {
			const $slider = jQuery( this );
			$slider.slick( {
				slidesToShow: 1,
				slidesToScroll: 1,
				arrows: true,
				dots: true,
			} );
		} );
	}
	if ( jQuery( window ).width() < 1004 ) {
		if ( jQuery( '.mobile-form-image-slider' ).length ) {
			jQuery( '.mobile-form-image-slider' ).each( function() {
				const $slider = jQuery( this );
				$slider.slick( {
					slidesToShow: 1,
					slidesToScroll: 1,
					arrows: true,
					dots: true,
				} );
			} );
		}
	}
	if ( jQuery( '.products-teaser-slider' ).length ) {
		jQuery( '.products-teaser-slider' ).each( function() {
			const $slider = jQuery( this );
			$slider.slick( {
				slidesToShow: 4,
				slidesToScroll: 1,
				arrows: true,
				dots: true,
				touchThreshold: 200,
				responsive: [
					{
						breakpoint: 1200,
						settings: {
							slidesToShow: 3,
							slidesToScroll: 1,
						},
					},

					{
						breakpoint: 1003,
						settings: {
							slidesToShow: 2,
							slidesToScroll: 1,
						},
					},

					{
						breakpoint: 747,
						settings: {
							slidesToShow: 1,
							slidesToScroll: 1,
						},
					},
				],

			} );
		} );
	}
} );

// helpers to read/write hidden ids (JSON array in a hidden input)
function getIdsField( slot ) {
	const map = { hitch: 'hitch_ids', rear: 'rear_ids', front: 'front_ids' };
	return document.getElementById( map[ slot ] );
}
function readIds( slot ) {
	try {
		return JSON.parse( getIdsField( slot ).value || '[]' );
	} catch ( e ) {
		return [];
	}
}
function writeIds( slot, ids ) {
	getIdsField( slot ).value = JSON.stringify( ids );
}

function setupImageUpload( inputId, listId, slot ) {
	const input = document.getElementById( inputId );
	const list = document.getElementById( listId );

	input.addEventListener( 'change', function() {
		const files = Array.from( input.files );
		showFiles( files, input, list, slot ); // preview
		autoUpload( files, slot, list ); // 🔥 upload immediately
	} );

	function showFiles( files, input, list, slot ) {
		list.innerHTML = '';
		files.forEach( ( file, index ) => {
			if ( ! file.type.startsWith( 'image/' ) ) {
				alert( file.name + ' is not an image file!' );
				return;
			}

			const li = document.createElement( 'li' );
			li.style.display = 'flex';
			li.style.alignItems = 'center';
			li.style.marginBottom = '8px';

			const img = document.createElement( 'img' );
			img.src = URL.createObjectURL( file );
			img.style.width = '80px'; img.style.height = '80px'; img.style.objectFit = 'cover';
			img.style.marginRight = '10px'; img.style.border = '1px solid #ccc'; img.style.borderRadius = '6px';

			const span = document.createElement( 'span' );
			span.textContent = file.name;

			const status = document.createElement( 'em' ); // upload status
			status.style.marginLeft = '8px';
			status.textContent = ' – pending…';

			const delBtn = document.createElement( 'button' );
			delBtn.type = 'button'; delBtn.textContent = '❌'; delBtn.style.marginLeft = '10px';
			delBtn.addEventListener( 'click', () => {
				removeFile( index, input, list, slot );
			} );

			li.appendChild( img ); li.appendChild( span ); li.appendChild( status ); li.appendChild( delBtn );
			list.appendChild( li );
		} );
	}

	function removeFile( index, input, list, slot ) {
		const dt = new DataTransfer();
		const files = Array.from( input.files );
		files.splice( index, 1 );
		files.forEach( ( f ) => dt.items.add( f ) );
		input.files = dt.files;
		showFiles( Array.from( input.files ), input, list, slot );
		// NOTE: We’re not deleting uploaded media from the server here (needs auth/cap).
		// If you want to also remove uploaded IDs when removing from preview, clear the hidden field and re-upload remaining files:
		writeIds( slot, [] );
		autoUpload( Array.from( input.files ), slot, list );
	}

	function autoUpload( files, slot, list ) {
		if ( ! files.length ) {
			list.querySelectorAll( 'em' ).forEach( ( e ) => e.textContent = '' ); return;
		}

		const fd = new FormData();
		fd.append( 'action', 'bst_handle_upload_order_photos' );
		// fd.append( '_ajax_nonce', bstUpload.nonce );
		// send only this slot’s files so PHP can bucket them correctly
		files.forEach( ( file ) => fd.append( slot + '[]', file, file.name ) );

		jQuery.ajax( {
			url: localVars.ajax_url,
			method: 'POST',
			data: fd,
			processData: false,
			contentType: false,
			xhr() {
				const xhr = new window.XMLHttpRequest();
				xhr.upload.addEventListener( 'progress', function( e ) {
					if ( e.lengthComputable ) {
						const pct = Math.round( ( e.loaded / e.total ) * 100 );
						list.querySelectorAll( 'em' ).forEach( ( e ) => e.textContent = ' – uploading ' + pct + '%' );
					}
				} );
				return xhr;
			},
			success( resp ) {
				if ( resp && resp.success ) {
					// resp.data[slot] => array of {id, url}
					const ids = ( resp.data && resp.data[ slot ] ) ? resp.data[ slot ].map( ( x ) => x.id ) : [];
					writeIds( slot, ids );
					list.querySelectorAll( 'em' ).forEach( ( e ) => e.textContent = ' – uploaded' );
				} else {
					const msg = ( resp && resp.data && resp.data.message ) ? resp.data.message : 'Upload failed.';
					list.querySelectorAll( 'em' ).forEach( ( e ) => e.textContent = ' – ' + msg );
					alert( msg );
				}
			},
			error() {
				list.querySelectorAll( 'em' ).forEach( ( e ) => e.textContent = ' – network error' );
				alert( 'Network error during upload.' );
			},
		} );
	}
}

// init (note the added 3rd arg = slot key)
setupImageUpload( 'photo_hitch', 'list_hitch', 'hitch' );
setupImageUpload( 'photo_rear', 'list_rear', 'rear' );
setupImageUpload( 'photo_front', 'list_front', 'front' );

jQuery( function() {
	jQuery( '.stone-stomper-supports #factory_stoneguard' ).on( 'change', function() {
		if ( jQuery( this ).is( ':checked' ) ) {
		  jQuery( '.toolbox-support' ).slideUp();
		  jQuery( '.factory_stoneguard' ).slideDown();
		} else {
		  jQuery( '.factory_stoneguard' ).slideUp();
		}
	} );

	jQuery( '.stone-stomper-supports #toolbox' ).on( 'change', function() {
		if ( jQuery( this ).is( ':checked' ) ) {
		  jQuery( '.factory_stoneguard' ).slideUp();
		  jQuery( '.toolbox-support' ).slideDown();
		} else {
		  jQuery( '.toolbox-support' ).slideUp();
		}
	} );

	jQuery( '.stone-stomper-supports #support_pockets' ).on( 'change', function() {
		if ( jQuery( this ).is( ':checked' ) ) {
		  jQuery( '.factory_stoneguard' ).slideUp();
		  jQuery( '.toolbox-support' ).slideUp();
		}
	} );

	jQuery( '#a_frame_length' ).on( 'input change', function() {
	// Get the input value and extract only the number part
		const val = jQuery( this ).val().replace( /[^0-9]/g, '' );
		const num = parseInt( val, 10 );

		if ( ! isNaN( num ) && num >= 1900 ) {
	  jQuery( '.ss-support-options' ).slideDown();
		} else {
	  jQuery( '.ss-support-options' ).slideUp();
		}
	} );

  	jQuery( '#final_address' ).on( 'change', function() {
		if ( jQuery( this ).val() === 'move' ) {
			jQuery( '#move_note' ).show();
		} else {
			jQuery( '#move_note' ).hide();
		}
	} );

  	jQuery( '#shipping' ).on( 'change', function() {
		if ( jQuery( this ).val() === 'flat_rate:5' ) {
			jQuery( '#express_delivery_note' ).show();
		} else {
			jQuery( '#express_delivery_note' ).hide();
		}
	} );
} );
