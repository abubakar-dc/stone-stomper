/**
 * Sticky Header
 * Adds a class to header on scroll
 */
import magnificPopup from '../vendors/jquery-magnificpopup';
import organicTabs from '../vendors/organic-tab';
import slick from '../vendors/slick.min';
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
			jQuery( '#veh_make_other_wrap' ).removeClass( 'hidden' );
		} else {
			jQuery( '#veh_make_other_wrap' ).addClass( 'hidden' );
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
				jQuery( this ).css( '--ss_header-wrapper-default', jQuery( this ).outerHeight() + 'px' );
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
} );

function setupImageUpload( inputId, listId ) {
	const input = document.getElementById( inputId );
	const list = document.getElementById( listId );

	input.addEventListener( 'change', function() {
		showFiles( Array.from( input.files ), input, list );
	} );

	function showFiles( files, input, list ) {
		list.innerHTML = ''; // clear old list

		files.forEach( ( file, index ) => {
			// ✅ validate image type
			if ( ! file.type.startsWith( 'image/' ) ) {
				alert( file.name + ' is not an image file!' );
				return;
			}

			const li = document.createElement( 'li' );
			li.style.display = 'flex';
			li.style.alignItems = 'center';
			li.style.marginBottom = '8px';

			// preview
			const img = document.createElement( 'img' );
			img.src = URL.createObjectURL( file );
			img.style.width = '80px';
			img.style.height = '80px';
			img.style.objectFit = 'cover';
			img.style.marginRight = '10px';
			img.style.border = '1px solid #ccc';
			img.style.borderRadius = '6px';

			// file name
			const span = document.createElement( 'span' );
			span.textContent = file.name;

			// delete button
			const delBtn = document.createElement( 'button' );
			delBtn.type = 'button';
			delBtn.textContent = '❌';
			delBtn.style.marginLeft = '10px';
			delBtn.addEventListener( 'click', () => {
				removeFile( index, input, list );
			} );

			li.appendChild( img );
			li.appendChild( span );
			li.appendChild( delBtn );
			list.appendChild( li );
		} );
	}

	function removeFile( index, input, list ) {
		const dt = new DataTransfer();
		const files = Array.from( input.files );

		files.splice( index, 1 ); // remove selected file
		files.forEach( ( file ) => dt.items.add( file ) );

		input.files = dt.files;
		showFiles( Array.from( input.files ), input, list ); // refresh preview
	}
}

setupImageUpload( 'photo_hitch', 'list_hitch' );
setupImageUpload( 'photo_rear', 'list_rear' );
setupImageUpload( 'photo_front', 'list_front' );
