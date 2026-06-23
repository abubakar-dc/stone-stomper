/**
 * Sticky Header
 * Adds a class to header on scroll
 */
import slick from '../vendors/slick.min';
import Lity from '../vendors/lity.js';

let finalSectionsUnlocked = false;

function scrollToSection( id ) {
	// Skip scrolling if form data is being restored from storage
	if ( window.isRestoringFormData ) {
		return;
	}
	const target = document.querySelector( id );
	if ( target ) {
		requestAnimationFrame( function() {
			target.scrollIntoView( { behavior: 'smooth', block: 'start' } );
		} );
	}
}

jQuery( document ).on( 'scroll', function() {
	if ( jQuery( document ).scrollTop() > 0 ) {
		jQuery( 'header, body' ).addClass( 'shrink' );
	} else {
		jQuery( 'header, body' ).removeClass( 'shrink' );
	}
} );

jQuery( document ).ready( function() {
	jQuery( '#blk-caravan select' ).on( 'change', function() {
		if ( jQuery( this ).val() !== '' ) {
			jQuery( '#caravan-notice-bar' ).css( 'display', 'block' );
		}
	} );

	jQuery( '#blk-vehicle select' ).on( 'change', function() {
		if ( jQuery( this ).val() !== '' ) {
			jQuery( '#vehicle-notice-bar' ).css( 'display', 'block' );
		}
	} );
} );

jQuery( document ).ready( function() {
	const meshOnlyProduct = '712';

	function allRequiredFilled( section ) {
		let filled = true;
		jQuery( section )
			.find( 'input:visible[required], select:visible[required], textarea:visible[required]' )
			.each( function() {
				const el = jQuery( this );
				const value = el.val();
				if ( ! value || value.trim() === '' ) {
					filled = false;
					return false;
				}
				if (
					el.is( 'select' ) &&
					( value === '' ||
						value === '0' ||
						el.find( 'option:selected' ).text().trim().toLowerCase().includes( 'select' ) )
				) {
					filled = false;
					return false;
				}
			} );

		// Special check for measurement field in bar-options-section
		if ( section === '#bar-options-section' ) {
			const $measurementFieldset = jQuery( '.hitch_measurement_dropdown' );
			const $measurementInput = jQuery( '#additional_hitch_measurement' );
			const shank41 = jQuery( '#bar_q_shank_41' ).val();
			const adjustable = jQuery( '#bar_q_adjustable_hitch' ).val();

			// If Q1=yes OR Q3=yes, then measurement field is required
			if ( shank41 === 'yes' || adjustable === 'yes' ) {
				const measurementValue = $measurementInput.val();
				console.log( 'Measurement validation - Value:', measurementValue, 'Fieldset visible:', $measurementFieldset.is( ':visible' ) );

				if ( ! measurementValue || measurementValue.trim() === '' ) {
					console.log( 'Measurement field is empty - blocking progression' );
					filled = false;
				}
			}
		}

		return filled;
	}

	function handleFormSection( currentSection, nextSection ) {
		const postCheckDelay = 300;

		function attemptMove() {
			if ( allRequiredFilled( currentSection ) ) {
				setTimeout( function() {
					const selectedProduct = jQuery( '#product_type' ).val();

					if ( nextSection === '#bar-options-section' && selectedProduct === meshOnlyProduct ) {
						jQuery( '#bar-options-section' ).addClass( 'section-disable' );
						jQuery( '#photographs-details, #final-measurements, #final-summary' ).removeClass( 'section-disable' );
						scrollToSection( '#photographs-details' );
						return;
					}

					// Skip auto-scroll for bar options - users will click Continue button
					if ( currentSection === '#bar-options-section' ) {
						const section = jQuery( nextSection );
						if ( section.hasClass( 'section-disable' ) ) {
							section.removeClass( 'section-disable' );
						}
						return;
					}

					const section = jQuery( nextSection );
					if ( section.hasClass( 'section-disable' ) ) {
						section.removeClass( 'section-disable' );
						scrollToSection( nextSection );
					} else {
						scrollToSection( nextSection );
					}
				}, postCheckDelay );
			}
		}

		jQuery( document ).on( 'change', currentSection + ' select', function() {
			attemptMove();
		} );

		jQuery( document ).on(
			'blur',
			currentSection + ' input[required], ' + currentSection + ' textarea[required]',
			function() {
				attemptMove();
			}
		);

		return function( e ) {
			if ( e && e.target ) {
				attemptMove();
			}
		};
	}

	const validateJump01 = handleFormSection( '#jump-01', '#vehicle-details' );
	const validateVehicleDetails = handleFormSection( '#vehicle-details', '#caravan-details' );
	const validateCaravanDetails = handleFormSection( '#caravan-details', '#bar-options-section' );
	const validateBarOptions = handleFormSection( '#bar-options-section', '#photographs-details' );

	jQuery( '#product_type' ).on( 'change', function() {
		const val = jQuery( this ).val();

		if ( val === meshOnlyProduct ) {
			jQuery( '#bar-options-section' ).addClass( 'section-disable' );
			jQuery( '#photographs-details, #final-measurements, #final-summary' ).addClass( 'section-disable' );
		}

		if ( val ) {
			jQuery( '#jump-01' ).removeClass( 'section-disable' );
			setTimeout( function() {
				scrollToSection( '#jump-01' );
			}, 400 );
		}
	} );

	function checkCaravanFinalSections() {
		setTimeout( function() {
			const selectedProduct = jQuery( '#product_type' ).val();

			if ( selectedProduct === meshOnlyProduct ) {
				if ( allRequiredFilled( '#caravan-details' ) ) {
					jQuery( '#bar-options-section' ).addClass( 'section-disable' );
					jQuery( '#photographs-details, #final-measurements, #final-summary' ).removeClass( 'section-disable' );
					scrollToSection( '#photographs-details' );
				} else {
					jQuery( '#photographs-details, #final-measurements, #final-summary' ).addClass( 'section-disable' );
				}
				return;
			}

			if ( allRequiredFilled( '#caravan-details' ) ) {
				const section = jQuery( '#bar-options-section' );
				if ( section.hasClass( 'section-disable' ) ) {
					section.removeClass( 'section-disable' );
				}
				scrollToSection( '#bar-options-section' );
			}
		}, 100 );
	}

	jQuery( document ).on( 'change blur', '#caravan-details input, #caravan-details select, #caravan-details textarea', checkCaravanFinalSections );

	jQuery( '#veh_make' ).on( 'change', function() {
		const yearContainer = jQuery( '.veh_year_other' );
		const vehMakeContainer = jQuery( '.veh_make_other' );
		const vehModelContainer = jQuery( '.veh_model_other' );

		if ( jQuery( this ).val() === 'other' ) {
			jQuery( '#barwidth' ).val( '1800' ).trigger( 'change' );
			jQuery( '#veh_model, #veh_year' ).hide().prop( 'required', false );
			vehMakeContainer.html( '<input placeholder="Other Make" id="veh_make_other" name="vehicle_make_other" required type="text" />' );
			vehModelContainer.html( '<input style="margin-top:30px" placeholder="Vehicle Model" id="vehicle_model_other" name="vehicle_model" required type="text" />' );
			yearContainer.html( '<input style="margin-top:0px" placeholder="Model Year" id="veh_year_other" name="vehicle_year" required type="text" />' );
		} else {
			jQuery( '#veh_model, #veh_year' ).show().prop( 'required', true );
			vehMakeContainer.empty();
			vehModelContainer.empty();
			yearContainer.empty();
		}
		validateVehicleDetails( { target: jQuery( '#veh_make' )[ 0 ] } );
	} );

	jQuery( '#veh_model' ).on( 'change', function() {
		const yearContainer = jQuery( '.veh_year_other' );
		const vehModelContainer = jQuery( '.veh_model_other' );

		if ( jQuery( this ).val() === 'other' ) {
			jQuery( '#barwidth' ).val( '1800' ).trigger( 'change' );
			jQuery( '#veh_year' ).hide().prop( 'required', false );
			vehModelContainer.html( '<input style="margin-top:30px" placeholder="Vehicle Model" id="vehicle_model_other" name="vehicle_model" required type="text" />' );
			yearContainer.html( '<input style="margin-top:0px" placeholder="Model Year" id="veh_year_other" name="vehicle_year" required type="text" />' );
		} else {
			jQuery( '#veh_year' ).show().prop( 'required', true );
			vehModelContainer.empty();
			yearContainer.empty();
		}
		validateVehicleDetails( { target: jQuery( '#veh_model' )[ 0 ] } );
	} );

	jQuery( '#veh_year' ).on( 'change', function() {
		const yearContainer = jQuery( '.veh_year_other' );

		if ( jQuery( this ).val() === 'other' ) {
			jQuery( '#barwidth' ).val( '1800' ).trigger( 'change' );
			jQuery( '#veh_year' ).prop( 'required', false );
			yearContainer.html( '<input style="margin-top:30px" placeholder="Model Year" id="veh_year_other" name="vehicle_year" required type="text" />' );
		} else {
			jQuery( '#veh_year' ).prop( 'required', true );
			yearContainer.empty();
		}
		validateVehicleDetails( { target: jQuery( '#veh_year' )[ 0 ] } );
	} );

	jQuery( '#van_make' ).on( 'change', function() {
		const vanModelContainer = jQuery( '.van_model_other' );

		if ( jQuery( this ).val() === 'other' ) {
			jQuery( '#van_model' ).hide().prop( 'required', false );
			vanModelContainer.html( '<input placeholder="Caravan Model" id="van_model_other" name="caravan_model" required type="text" />' );
		} else {
			jQuery( '#van_model' ).show().prop( 'required', true );
			vanModelContainer.empty();
		}
		validateCaravanDetails( { target: jQuery( '#van_make' )[ 0 ] } );
	} );

	jQuery( '#van_model' ).on( 'change', function() {
		const vanModelContainer = jQuery( '.van_model_other' );

		if ( jQuery( this ).val() === 'other' ) {
			jQuery( '#van_model' ).prop( 'required', false );
			vanModelContainer.html( '<input placeholder="Caravan Model" id="van_model_other" name="caravan_model" required type="text" />' );
		} else {
			jQuery( '#van_model' ).prop( 'required', true );
			vanModelContainer.empty();
		}
		validateCaravanDetails( { target: jQuery( '#van_model' )[ 0 ] } );
	} );

	jQuery( '#veh_make' ).trigger( 'change' );
	jQuery( '#van_make' ).trigger( 'change' );

	validateJump01( { target: document.querySelector( '#jump-01' ) } );
	validateVehicleDetails( { target: document.querySelector( '#vehicle-details' ) } );
	validateCaravanDetails( { target: document.querySelector( '#caravan-details' ) } );
	validateBarOptions( { target: document.querySelector( '#bar-options-section' ) } );

	function revealFinalSections() {
		const isFilled = allRequiredFilled( '#bar-options-section' );
		console.log( 'revealFinalSections called - Filled:', isFilled, 'Already unlocked:', finalSectionsUnlocked );

		if ( ! finalSectionsUnlocked && isFilled ) {
			finalSectionsUnlocked = true;
			console.log( 'All sections filled - unlocking photographs section' );
			const sections = jQuery( '#photographs-details, #final-measurements, #final-summary' );
			sections.removeClass( 'section-disable' );
			// Skip auto-scroll for bar options - users will click Continue button
		} else if ( ! isFilled ) {
			console.log( 'Form not complete - blocking progression' );
		}
	}

	jQuery( document ).on( 'change blur', '#bar-options-section input, #bar-options-section select, #bar-options-section textarea', revealFinalSections );

	// Explicit handler for measurement field to ensure it triggers validation
	jQuery( '#additional_hitch_measurement' ).on( 'input change blur', revealFinalSections );
} );

jQuery( function() {
	/**
	 * Header Wrapper Height Calculation for Navigation Overlay
	 */

	if ( jQuery( '.header-wrapper' ).length > 0 ) {
		function updateHeaderHeight() {
			jQuery( '.header-wrapper' ).each( function() {
				jQuery(
					'body.woocommerce-account, body.woocommerce-checkout, body.woocommerce-cart, body.woocommerce-shop',
				).css(
					'--ss_header-wrapper-default',
					jQuery( this ).outerHeight() + 'px',
				);
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

		parentLi.siblings( '.active' ).removeClass( 'active' ).find( 'ul' ).slideUp();

		parentLi
			.toggleClass( 'active' )
			.find( 'ul' )
			.stop( true, true )
			.slideToggle();
		parentLi
			.parents( 'ul' )
			.toggleClass( 'disabled-menu', parentLi.hasClass( 'active' ) );
	} );

	/**
	 *  Accessibility for Simple menu & Mega menu
	 */

	jQuery( '.menu-item-has-children > a' ).on( 'focus blur', function( event ) {
		jQuery( this )
			.siblings( '.sub-menu, .mega-menu' )
			.toggleClass( 'focused', event.type === 'focus' );
	} );

	jQuery( '.sub-menu a, .mega-menu a' ).on( 'focus blur', function( event ) {
		jQuery( this )
			.closest( '.sub-menu, .mega-menu' )
			.toggleClass( 'focused', event.type === 'focus' );
	} );

	/**
	 * Script for Accessibility of html Tags
	 */

	jQuery(
		'h1, h2, h3, h4, h5, h6,p,li,blockquote,cite,strong,dt,dd,th,td,b,i,u,s,em,small,sup,del,ins,abbr,mark,details,pre,kbd,samp,var,address,code,q,figure,figcaption,caption,.top-bar-text,.top-bar-cross,.copy-right,.post-author-img,.post-author-name,.post-meta-date,.post-date',
	).each( function() {
		jQuery( this ).attr( {
			tabindex: 0,
		} );
	} );

	jQuery( '.header-nav li, .blog-nav li, .footer-nav li, .legal-nav li' ).each(
		function() {
			const link = jQuery( this ).find( 'a' );
			if ( link.length > 0 ) {
				jQuery( this ).removeAttr( 'tabindex' );
			} else {
				jQuery( this ).attr( 'tabindex', '0' );
			}
		},
	);

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

		const selector =
			'input[type="text"],input[type="number"],input[type="email"],input[type="tel"],input[type="url"],input[type="search"],input[type="password"],input[type="time"],input[type="date"],input[type="datetime-local"],input[type="week"],input[type="month"],input[type="file"],input[type="range"],input[list],input[type="string"],select,textarea,.gform-text-input-reset';

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
				if (
					mutation.type === 'childList' &&
					mutation.addedNodes.length
				) {
					jQuery( mutation.addedNodes ).each( function() {
						if ( this.nodeType === 1 ) {
							scanAndAttach( this );
						}
					} );
				}
				if (
					mutation.type === 'attributes' &&
					( mutation.attributeName === 'class' ||
						mutation.attributeName === 'style' ||
						mutation.attributeName === 'hidden' )
				) {
					if ( mutation.target && mutation.target.nodeType === 1 ) {
						scanAndAttach( mutation.target );
					}
				}
			} );
		} );

		observer.observe( document.body, {
			childList: true,
			subtree: true,
			attributes: true,
			attributeFilter: [ 'class', 'style', 'hidden' ],
		} );

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

	jQuery( document ).ready( function() {
		function initSlider( selector ) {
			if ( jQuery( selector ).length ) {
				jQuery( selector ).each( function() {
					const $slider = jQuery( this );
					$slider.slick( {
						slidesToShow: 1,
						slidesToScroll: 1,
						adaptiveHeight: true,
						arrows: true,
						dots: true,
					} );

					// Recalculate height after all images are loaded
					$slider.find( 'img' ).each( function() {
						if ( ! this.complete ) {
							jQuery( this ).on( 'load', function() {
								$slider.slick( 'setPosition' );
							} );
						}
					} );

					// Force initial recalculation
					$slider.slick( 'setPosition' );
				} );
			}
		}

		initSlider( '.form-image-slider' );

		if ( jQuery( window ).width() < 1004 ) {
			initSlider( '.mobile-form-image-slider' );
		}
	} );

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
	const map = { hitch: 'hitch_ids', rear: 'rear_ids', front: 'front_ids' }; return document.getElementById( map[ slot ] );
}

function readIds( slot ) {
	try {
		return JSON.parse( getIdsField( slot ).value || '[]' );
	} catch ( e ) {
		return [];
	}
}

function writeIds( slot, ids ) {
	const field = getIdsField( slot );
	if ( ! field ) {
		return;
	}
	field.value = JSON.stringify( ids );
	// Trigger change so autosave captures uploaded image IDs
	if ( typeof jQuery !== 'undefined' ) {
		jQuery( field ).trigger( 'change' );
	} else {
		const evt = new Event( 'change', { bubbles: true } );
		field.dispatchEvent( evt );
	}
}

function setupImageUpload( inputId, listId, slot ) {
	const input = document.getElementById( inputId );
	const list = document.getElementById( listId );
	const maxFiles = 2;
	if ( ! input || ! list ) {
		return;
	}

	input.multiple = true;

	let entries = readIds( slot ).slice( 0, maxFiles ).map( ( url ) => ( { url } ) );
	showEntries( entries, list );

	input.addEventListener( 'change', function() {
		const newFiles = Array.from( input.files );
		const availableSlots = maxFiles - entries.length;

		if ( availableSlots <= 0 ) {
			alert( 'Only ' + maxFiles + ' images are allowed for this field. Remove one before adding another.' );
			input.value = '';
			return;
		}

		const filesToAdd = newFiles.slice( 0, availableSlots );

		if ( newFiles.length > availableSlots ) {
			alert( 'Only ' + maxFiles + ' images are allowed for this field. The first ' + availableSlots + ' new image(s) will be uploaded.' );
		}

		entries = entries.concat( filesToAdd.map( ( file ) => ( { file } ) ) );
		input.value = '';

		showEntries( entries, list );
		autoUpload( slot, list );
	} );

	function showEntries( items, list ) {
		list.innerHTML = '';

		if ( ! items.length ) {
			return;
		}

		items.forEach( ( item, index ) => {
			const file = item.file;
			const url = item.url;

			if ( file && ! file.type.startsWith( 'image/' ) ) {
				const error = document.createElement( 'p' );
				error.classList.add( 'file-not-uploaded' );
				error.style.color = 'red';
				error.style.margin = '0 0 5px 0';
				error.textContent = file.name + ' is not an image file!';
				list.appendChild( error );
				return;
			}

			const li = document.createElement( 'li' );
			li.style.display = 'flex';
			li.style.alignItems = 'center';
			li.style.marginBottom = '8px';

			const img = document.createElement( 'img' );
			img.src = url || URL.createObjectURL( file );
			img.style.width = '80px';
			img.style.height = '80px';
			img.style.objectFit = 'cover';
			img.style.marginRight = '10px';
			img.style.border = '1px solid #ccc';
			img.style.borderRadius = '6px';

			const span = document.createElement( 'span' );
			span.textContent = file ? file.name : ( url.split( '/' ).pop() || 'Uploaded image' );

			const status = document.createElement( 'em' );
			status.style.marginLeft = '8px';
			status.textContent = url ? 'Uploaded' : ' Pending...';

			const delBtn = document.createElement( 'button' );
			delBtn.type = 'button';
			delBtn.textContent = 'X';
			delBtn.style.setProperty( 'margin-left', '10px', 'important' );
			delBtn.style.setProperty( 'color', '#ff0000', 'important' );
			delBtn.style.setProperty( 'border', 'none', 'important' );
			delBtn.style.setProperty( 'background', 'transparent', 'important' );
			delBtn.style.setProperty( 'cursor', 'pointer', 'important' );
			delBtn.style.setProperty( 'font-size', '18px', 'important' );
			delBtn.style.setProperty( 'font-weight', '700', 'important' );
			delBtn.style.setProperty( 'line-height', '1', 'important' );
			delBtn.style.setProperty( 'padding', '0', 'important' );
			delBtn.addEventListener( 'click', () => {
				entries.splice( index, 1 );
				input.value = '';
				writeIds( slot, entries.map( ( entry ) => entry.url ).filter( Boolean ).slice( 0, maxFiles ) );
				showEntries( entries, list );
			} );

			li.appendChild( img );
			li.appendChild( span );
			li.appendChild( status );
			li.appendChild( delBtn );
			list.appendChild( li );
		} );
	}

	function autoUpload( slot, list ) {
		if ( ! entries.length ) {
			list.querySelectorAll( 'em' ).forEach( ( e ) => e.textContent = '' );
			writeIds( slot, [] );
			return;
		}

		const fileEntries = entries.filter( ( entry ) => entry.file && entry.file.type.startsWith( 'image/' ) );
		const imageFiles = fileEntries.map( ( entry ) => entry.file );
		if ( ! imageFiles.length ) {
			writeIds( slot, entries.map( ( entry ) => entry.url ).filter( Boolean ).slice( 0, maxFiles ) );
			return;
		}

		const fd = new FormData();
		fd.append( 'action', 'bst_handle_upload_order_photos' );
		imageFiles.forEach( ( file ) => {
			fd.append( slot + '[]', file, file.name );
		} );

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
						list.querySelectorAll( 'em' ).forEach( ( e ) => e.textContent = ' Uploading ' + pct + '%' );
					}
				} );
				return xhr;
			},
			success( resp ) {
				if ( resp && resp.success ) {
					const data = resp.data || resp;
					const urls = data && data[ slot ] ? data[ slot ].map( ( x ) => x.url ).slice( 0, fileEntries.length ) : [];
					fileEntries.forEach( ( entry, index ) => {
						entry.url = urls[ index ] || '';
						delete entry.file;
					} );
					entries = entries.filter( ( entry ) => entry.url ).slice( 0, maxFiles );
					writeIds( slot, entries.map( ( entry ) => entry.url ) );
					showEntries( entries, list );
				} else {
					alert( 'Upload failed' );
				}
			},
			error() {
				list.querySelectorAll( 'em' ).forEach( ( e ) => e.textContent = ' – network error' );
				alert( 'Network error during upload.' );
			},
		} );
	}
}

setupImageUpload( 'photo_hitch', 'list_hitch', 'hitch' );
setupImageUpload( 'photo_rear', 'list_rear', 'rear' );
setupImageUpload( 'photo_front', 'list_front', 'front' );

jQuery( function() {
	// 🟦 ADD THIS CODE
	const meshOnlyProduct = '712'; // <-- yahan apna Mesh Only product ka ID laga dena

	jQuery( document ).on( 'change', '#product_type', function() {
	    const selectedProd = jQuery( this ).val();

	    if ( selectedProd === meshOnlyProduct ) {
	        // Hide full Bar Option section
	        jQuery( '#bar-options-section' ).hide();
	        jQuery( '.mesh-only-field' ).show();
	    } else {
	        // Show Bar Option section
	        jQuery( '#bar-options-section' ).show();
	        jQuery( '.mesh-only-field' ).hide();
	    }
	} );

	jQuery( '.stone-stomper-supports #factory_stoneguard' ).on(
		'change',
		function() {
			if ( jQuery( this ).is( ':checked' ) ) {
				jQuery( '.toolbox-support' ).slideUp();
				jQuery( '.support_pockets' ).slideUp();
				jQuery( '.factory_stoneguard' ).slideDown();
			} else {
				jQuery( '.factory_stoneguard' ).slideUp();
			}
		},
	);

	jQuery( '.stone-stomper-supports #toolbox' ).on( 'change', function() {
		if ( jQuery( this ).is( ':checked' ) ) {
			jQuery( '.factory_stoneguard' ).slideUp();
			jQuery( '.support_pockets' ).slideUp();
			jQuery( '.toolbox-support' ).slideDown();
		} else {
			jQuery( '.toolbox-support' ).slideUp();
		}
	} );

	jQuery( '.stone-stomper-supports #support_pockets' ).on(
		'change',
		function() {
			if ( jQuery( this ).is( ':checked' ) ) {
				jQuery( '.factory_stoneguard' ).slideUp();
				jQuery( '.toolbox-support' ).slideUp();
				jQuery( '.support_pockets' ).slideDown();
			} else {
				jQuery( '.support_pockets' ).slideUp();
			}
		},
	);

	jQuery( '#a_frame_length' ).on( 'input change', function() {
		// Get the input value and extract only the number part
		const val = jQuery( this )
			.val()
			.replace( /[^0-9]/g, '' );
		const num = parseInt( val, 10 );

		if ( ! isNaN( num ) && num >= 1800 ) {
			jQuery( '.ss-support-options' ).slideDown();
		} else {
			jQuery( '.ss-support-options' ).slideUp();
		}
	} );

	const normalizeBarText = ( text ) => String( text || '' ).toLowerCase().replace( /\s+/g, ' ' ).trim();

	const setMeasurementVisibility = ( mode ) => {
		const showOptionTwo = mode === 'option2';
		const showOptionThree = mode === 'option3';

		jQuery( '.option-two-description, .option-three-description, .additional-measurement-description, .no-option-message' ).hide();

		if ( showOptionTwo || showOptionThree ) {
			jQuery( '.hitch_measurement_dropdown' ).slideDown();
			if ( showOptionTwo ) {
				jQuery( '#option-two-description' ).slideDown();
			}
			if ( showOptionThree ) {
				jQuery( '#option-three-description' ).slideDown();
			}
			// Show additional measurement description if it exists
			jQuery( '#additional-measurement-description' ).slideDown();
		} else {
			jQuery( '.hitch_measurement_dropdown' ).slideUp();
			jQuery( '#additional_hitch_measurement' ).val( '' ).attr( 'placeholder', 'eg. 300 mm' );
		}
	};

	const detectMeasurementMode = ( selectedText ) => {
		const text = normalizeBarText( selectedText );

		if ( text.includes( 'option 2' ) && text.includes( 'post' ) ) {
			return 'option2';
		}

		if ( text.includes( 'option 3' ) ) {
			return 'option3';
		}

		return '';
	};

	const resolveBarOptionByOutcome = ( outcome ) => {
		const outcomesMap = {
			adjustable_option_3: [ [ 'option 3', 'adjust' ], [ 'option 3' ] ],
			adjustable_option_2_post: [ [ 'option 2', 'post' ], [ 'option 2' ] ],
			option_1_angle_large: [ [ 'option 1', 'angle', 'large' ], [ 'option 1' ] ],
			cut_out_angle: [ [ 'cut out', 'angle' ], [ 'cut out' ] ],
		};

		const patterns = outcomesMap[ outcome ] || [];
		const $options = jQuery( '#bar_options option' ).filter( function() {
			return jQuery( this ).val() !== '';
		} );

		for ( let i = 0; i < patterns.length; i++ ) {
			const terms = patterns[ i ];
			const matched = $options.filter( function() {
				const txt = normalizeBarText( jQuery( this ).text() );
				return terms.every( ( term ) => txt.includes( term ) );
			} ).first();

			if ( matched.length ) {
				return matched;
			}
		}

		return jQuery();
	};

	const setBarOutcome = ( outcome, label, barOptionValue ) => {
		const $result = jQuery( '#bar-options-result' );
		let $matchedOption = jQuery();
		// Try outcome-based matching first (for backward compatibility)
		$matchedOption = resolveBarOptionByOutcome( outcome );
		// If no match found by outcome, try to match by barOptionValue
		if ( ! $matchedOption.length && barOptionValue ) {
			$matchedOption = jQuery( '#bar_options option' ).filter( function() {
				const optionValue = jQuery( this ).val();
				const dataValue = jQuery( this ).data( 'value' );
				// Match either by option value or data-value attribute
				return optionValue === barOptionValue || dataValue === barOptionValue || optionValue.includes( barOptionValue ) || ( dataValue && dataValue.toString().includes( barOptionValue.toString() ) );
			} ).first();
		}

		// Show success message with the bar option value (from question metadata)
		// The barOptionValue is the actual option selected from ACF question config
		if ( barOptionValue ) {
			$result.html( 'Bracket Option Selected: ' + '<strong>' + barOptionValue + '</strong>' ).show();
		}

		// If we found a matching dropdown option, select it
		if ( $matchedOption.length ) {
			jQuery( '#bar_options' ).val( $matchedOption.val() ).trigger( 'change' );
		} else {
			jQuery( '#bar_options' ).val( '' ).trigger( 'change' );
		}

		// Save the question's bar option value to form field (this is the critical part)
		if ( barOptionValue ) {
			jQuery( '#question_bar_option_value' ).val( barOptionValue );
			console.log( 'Set #question_bar_option_value from decision tree:', barOptionValue );
		}

		setMeasurementVisibility( '' );
	};

	// Show/hide hitch measurement field based on Q1 or Q3 answers
	const updateHitchMeasurementVisibility = () => {
		const shank41 = jQuery( '#bar_q_shank_41' ).val();
		const adjustable = jQuery( '#bar_q_adjustable_hitch' ).val();
		const $measurementField = jQuery( '.hitch_measurement_dropdown' );
		const $input = jQuery( '#additional_hitch_measurement' );

		// Show field if Q1=yes OR Q3=yes
		if ( shank41 === 'yes' || adjustable === 'yes' ) {
			$measurementField.slideDown();
			$input.prop( 'required', true ); // Required when visible
		} else {
			$measurementField.slideUp();
			$input.prop( 'required', false ).val( '' ).attr( 'placeholder', 'eg. 300 mm' ); // Clear value when hidden
		}
	};

	// Show/hide description notices based on question answers
	const updateDescriptionNoticesVisibility = () => {
		const shank41 = jQuery( '#bar_q_shank_41' ).val();
		const do35 = jQuery( '#bar_q_do35_do45' ).val();
		const adjustable = jQuery( '#bar_q_adjustable_hitch' ).val();
		const tongue85 = jQuery( '#bar_q_tongue_85' ).val();

		// Show option-two-description when Q1 = yes
		if ( shank41 === 'yes' ) {
			jQuery( '#option-two-description' ).slideDown();
		} else {
			jQuery( '#option-two-description' ).slideUp();
		}

		// Show option-three-description when Q3 = yes OR Q4 = yes
		if ( adjustable === 'yes' || ( shank41 === 'no' && do35 === 'yes' && tongue85 === 'yes' ) ) {
			jQuery( '#option-three-description' ).slideDown();
		} else {
			jQuery( '#option-three-description' ).slideUp();
		}
	};

	const toggleQuestion = ( selector, shouldShow ) => {
		const $field = jQuery( selector );
		const $select = $field.find( 'select' );

		if ( shouldShow ) {
			$field.show();
			$select.prop( 'required', true );
			return;
		}

		$field.hide();
		$select.prop( 'required', false ).val( '' );
	};

	const evaluateBarDecisionTree = () => {
		const shank41 = jQuery( '#bar_q_shank_41' ).val();
		const do35 = jQuery( '#bar_q_do35_do45' ).val();
		const adjustable = jQuery( '#bar_q_adjustable_hitch' ).val();
		const tongue85 = jQuery( '#bar_q_tongue_85' ).val();

		// Update question visibility
		toggleQuestion( '.bar-q-do35', shank41 === 'no' );
		toggleQuestion( '.bar-q-adjustable', shank41 === 'no' && do35 === 'no' );
		toggleQuestion( '.bar-q-tongue', shank41 === 'no' && do35 === 'yes' );

		// Update corresponding question images
		const $container = jQuery( '#bar-question-image-container' );
		if ( $container.length ) {
			// Hide all figures first
			$container.find( '.bar-q-figure' ).addClass( 'bar-q-image--hidden' );

			// Check if we need to show measurement image for scenarios that require additional measurement
			// Q1=Yes shows Q1's measurement image
			// Q1=No, Q2=No, Q3=Yes shows Q3's measurement image
			const showMeasurementImageQ1 = ( shank41 === 'yes' );
			const showMeasurementImageQ3 = ( shank41 === 'no' && do35 === 'no' && adjustable === 'yes' );

			if ( showMeasurementImageQ1 ) {
				// Show Q1's measurement image
				$container.find( '#measurement-figure-q1' ).removeClass( 'bar-q-image--hidden' );
			} else if ( showMeasurementImageQ3 ) {
				// Show Q3's measurement image
				$container.find( '#measurement-figure-q3' ).removeClass( 'bar-q-image--hidden' );
			} else {
				// For question scenarios: hide measurement and show appropriate question figure
				$container.find( '#measurement-figure-q1, #measurement-figure-q3' ).addClass( 'bar-q-image--hidden' );

				// Show the appropriate question figure
				let figureId = '#bar-q-figure-1'; // default
				if ( shank41 === 'no' && do35 === 'yes' ) {
					figureId = '#bar-q-figure-4';
				} else if ( shank41 === 'no' && do35 === 'no' && adjustable !== '' ) {
					figureId = '#bar-q-figure-3';
				} else if ( shank41 === 'no' && do35 === 'no' ) {
					figureId = '#bar-q-figure-3';
				} else if ( shank41 === 'no' && do35 === '' ) {
					figureId = '#bar-q-figure-2';
				}
				$container.find( figureId ).removeClass( 'bar-q-image--hidden' );
			}
		}

		// Always hide Q4 notice by default — only the specific branch below will re-show it
		jQuery( '#question-4-bar-value-notice' ).slideUp();

		if ( shank41 === 'yes' ) {
			// Get bar option value and hitch measurement from Question 1
			const q1BarOptionValue = jQuery( '#bar_q_shank_41' ).data( 'bar-option-value' );
			const q1HitchMeasurement = jQuery( '#bar_q_shank_41' ).data( 'hitch-measurement' );
			const q1Label = jQuery( '#bar_q_shank_41 option:selected' ).text() || 'Shank Option';
			jQuery( '#question_bar_option_value' ).val( q1BarOptionValue );
			jQuery( '#question_hitch_measurement' ).val( q1HitchMeasurement );
			jQuery( '#additional_hitch_measurement' ).val( '' ).attr( 'placeholder', q1HitchMeasurement );
			setBarOutcome( 'question_1_option', q1Label, q1BarOptionValue );
			updateHitchMeasurementVisibility(); // Ensure measurement field shows for Q1=Yes
			return;
		}

		if ( shank41 !== 'no' ) {
			jQuery( '#bar_options' ).val( '' ).trigger( 'change' );
			setMeasurementVisibility( '' );
			return;
		}

		if ( do35 === 'yes' && tongue85 === 'yes' ) {
			// Get bar option value and hitch measurement from Question 4
			// For Q4, bar option value is stored on the selected option element
			const q4BarOptionValue = jQuery( '#bar_q_tongue_85 option:selected' ).data( 'bar-option-value' );
			const q4HitchMeasurement = jQuery( '#bar_q_tongue_85' ).data( 'hitch-measurement' );
			const q4Label = jQuery( '#bar_q_tongue_85 option:selected' ).text() || 'Tongue Option';
			jQuery( '#question_bar_option_value' ).val( q4BarOptionValue );
			jQuery( '#question_hitch_measurement' ).val( q4HitchMeasurement );
			jQuery( '#additional_hitch_measurement' ).val( '' ).attr( 'placeholder', q4HitchMeasurement );
			setBarOutcome( 'question_4_option', q4Label, q4BarOptionValue );
			// Show Q4 notice — #bar-options-result is inside .hitch_measurement_dropdown which is hidden
			// for this path (Q1=No, Q3=No), so use the dedicated Q4 notice instead
			if ( q4BarOptionValue ) {
				jQuery( '#question-4-bar-value-display' ).text( q4BarOptionValue );
				jQuery( '#question-4-bar-value-notice' ).slideDown();
			} else {
				jQuery( '#question-4-bar-value-notice' ).slideUp();
			}
			updateHitchMeasurementVisibility(); // Ensure measurement field shows
			return;
		}

		if ( do35 === 'yes' && tongue85 === 'no' ) {
			// Get bar option value and hitch measurement from Question 2
			const q2BarOptionValue = jQuery( '#bar_q_do35_do45' ).data( 'bar-option-value' );
			const q2HitchMeasurement = jQuery( '#bar_q_do35_do45' ).data( 'hitch-measurement' );
			const q2Label = jQuery( '#bar_q_do35_do45 option:selected' ).text() || 'DO35 Option';
			// Q4=No also has its own bar option value — prefer it if set
			const q4NoBarOptionValue = jQuery( '#bar_q_tongue_85 option:selected' ).data( 'bar-option-value' );
			const finalBarOptionValue = q4NoBarOptionValue || q2BarOptionValue;
			// Hitch measurement should come from Q4 when it is the final answered question (fallback to Q2 if Q4 has none).
			const q4HitchMeasurement = jQuery( '#bar_q_tongue_85' ).data( 'hitch-measurement' );
			const finalHitchMeasurement = q4HitchMeasurement || q2HitchMeasurement;
			jQuery( '#question_bar_option_value' ).val( finalBarOptionValue );
			jQuery( '#question_hitch_measurement' ).val( finalHitchMeasurement );
			jQuery( '#additional_hitch_measurement' ).val( '' ).attr( 'placeholder', finalHitchMeasurement || 'eg. 300 mm' );
			setBarOutcome( 'question_2_option', q2Label, finalBarOptionValue );
			// Show Q4 notice with the No value
			if ( finalBarOptionValue ) {
				jQuery( '#question-4-bar-value-display' ).text( finalBarOptionValue );
				jQuery( '#question-4-bar-value-notice' ).slideDown();
			} else {
				jQuery( '#question-4-bar-value-notice' ).slideUp();
			}
			updateHitchMeasurementVisibility(); // Ensure measurement field shows
			return;
		}

		if ( do35 === 'no' && adjustable === 'yes' ) {
			// Get bar option value and hitch measurement from Question 3
			const q3BarOptionValue = jQuery( '#bar_q_adjustable_hitch' ).data( 'bar-option-value' );
			const q3HitchMeasurement = jQuery( '#bar_q_adjustable_hitch' ).data( 'hitch-measurement' );
			const q3Label = jQuery( '#bar_q_adjustable_hitch option:selected' ).text() || 'Adjustable Option';
			jQuery( '#question_bar_option_value' ).val( q3BarOptionValue );
			jQuery( '#question_hitch_measurement' ).val( q3HitchMeasurement );
			jQuery( '#additional_hitch_measurement' ).val( '' ).attr( 'placeholder', q3HitchMeasurement );
			setBarOutcome( 'question_3_option', q3Label, q3BarOptionValue );
			updateHitchMeasurementVisibility(); // Ensure measurement field shows for Q3=Yes
			return;
		}

		if ( do35 === 'no' && adjustable === 'no' ) {
			console.log( 'No bar option - showing no-option-message. do35:', do35, 'adjustable:', adjustable );
			jQuery( '#bar_options' ).val( '' ).trigger( 'change' );
			// Set the hidden bar option value field to indicate no option was selected
			jQuery( '#question_bar_option_value' ).val( 'Confirm Bracket Option' );
			// Hide the measurement field fieldset when no option matches
			jQuery( '.hitch_measurement_dropdown' ).slideUp();
			jQuery( '#additional_hitch_measurement' ).val( '' ).attr( 'placeholder', 'eg. 300 mm' ).prop( 'required', false );
			// Hide any description messages
			jQuery( '.option-two-description, .option-three-description' ).slideUp();
			// Show the no-option message
			jQuery( '#no-option-message' ).slideDown();
			return;
		}

		jQuery( '#bar_options' ).val( '' ).trigger( 'change' );
		setMeasurementVisibility( '' );

		// Always ensure measurement field visibility is correct based on Q1/Q3
		// This needs to run at the end to override any hiding done by setMeasurementVisibility
		updateHitchMeasurementVisibility();
	};

	jQuery( '#bar_options' ).on( 'change', function() {
		const $select = jQuery( this );
		const selectedText = $select.val();
		const selectedOption = $select.find( 'option:selected' );
		const fixedValue = parseInt( selectedOption.data( 'value' ) ) || 0;
		const mode = detectMeasurementMode( selectedText );
		jQuery( '#bar_option_value' ).val( fixedValue || '' );
		jQuery( '#sts_var_caravan_bar_option' ).val( selectedText || '' );
		setMeasurementVisibility( mode );
	} );

	jQuery( '#bar_q_shank_41, #bar_q_do35_do45, #bar_q_adjustable_hitch, #bar_q_tongue_85' ).on( 'change', function() {
		evaluateBarDecisionTree();
		updateHitchMeasurementVisibility();
		updateDescriptionNoticesVisibility();
		updateContinueButtonVisibility();
		// Reset the unlock flag when any question changes so validation is re-checked
		finalSectionsUnlocked = false;
		console.log( 'Question changed - resetting finalSectionsUnlocked' );
	} );
	evaluateBarDecisionTree();
	updateHitchMeasurementVisibility();
	updateDescriptionNoticesVisibility();

	// Determine if Continue button should be visible based on decision tree
	const updateContinueButtonVisibility = () => {
		const shank41 = jQuery( '#bar_q_shank_41' ).val();
		const do35 = jQuery( '#bar_q_do35_do45' ).val();
		const adjustable = jQuery( '#bar_q_adjustable_hitch' ).val();
		const tongue85 = jQuery( '#bar_q_tongue_85' ).val();

		let shouldShowButton = false;

		// Scenario 1: Q1 = Yes (final answer)
		if ( shank41 === 'yes' ) {
			shouldShowButton = true;
		}
		// Scenario 2-3: Q1 = No, Q2 = Yes, Q4 has value (final answer)
		else if ( shank41 === 'no' && do35 === 'yes' && tongue85 !== '' ) {
			shouldShowButton = true;
		}
		// Scenario 4-5: Q1 = No, Q2 = No, Q3 has value (final answer)
		else if ( shank41 === 'no' && do35 === 'no' && adjustable !== '' ) {
			shouldShowButton = true;
		}

		if ( shouldShowButton ) {
			jQuery( '#bar-options-continue-wrapper' ).slideDown();
		} else {
			jQuery( '#bar-options-continue-wrapper' ).slideUp();
		}
	};

	updateContinueButtonVisibility();

	// Handle Continue button click for bar options section
	jQuery( '#bar-options-continue-btn' ).on( 'click', function() {
		scrollToSection( '#photographs-details' );
	} );

	// Auto-populate hitch measurement from selected question
	const handleHitchMeasurementAutoPopulation = () => {
		jQuery( '#bar_q_shank_41, #bar_q_do35_do45, #bar_q_adjustable_hitch, #bar_q_tongue_85' ).on( 'change', function() {
			const $selected = jQuery( this );
			const selectedValue = $selected.val();

			// Only populate if a value is selected
			if ( selectedValue ) {
				const hitchMeasureValue = $selected.data( 'hitch-measurement' );
				if ( hitchMeasureValue ) {
					jQuery( '#additional_hitch_measurement' ).val( '' ).attr( 'placeholder', hitchMeasureValue );
				}
			}
		} );
	};

	handleHitchMeasurementAutoPopulation();

	// Note: Bar option value is now handled entirely by evaluateBarDecisionTree()
	// which evaluates the full decision path and sets the value appropriately.
	// Removed handleBarOptionValueAutoPopulation() as it was conflicting with the decision tree logic.

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

	jQuery( '#veh_make' ).on( 'change', function() {
		if ( jQuery( this ).val() === 'other' ) {
			jQuery( '#is_vehicle_make_other' ).val( 'yes' );
		} else {
			jQuery( '#is_vehicle_make_other' ).val( 'no' );
		}
	} );

	jQuery( '#veh_model' ).on( 'change', function() {
		if ( jQuery( this ).val() === 'other' ) {
			jQuery( '#is_vehicle_model_other' ).val( 'yes' );
		} else {
			jQuery( '#is_vehicle_model_other' ).val( 'no' );
		}
	} );

	jQuery( '#veh_year' ).on( 'change', function() {
		if ( jQuery( this ).val() === 'other' ) {
			jQuery( '#is_vehicle_year_other' ).val( 'yes' );
		} else {
			jQuery( '#is_vehicle_year_other' ).val( 'no' );
		}
	} );

	jQuery( '#van_make' ).on( 'change', function() {
		if ( jQuery( this ).val() === 'other' ) {
			jQuery( '#is_caravan_make_other' ).val( 'yes' );
		} else {
			jQuery( '#is_caravan_make_other' ).val( 'no' );
		}
	} );

	jQuery( '#van_model' ).on( 'change', function() {
		if ( jQuery( this ).val() === 'other' ) {
			jQuery( '#is_caravan_model_other' ).val( 'yes' );
		} else {
			jQuery( '#is_caravan_model_other' ).val( 'no' );
		}
	} );
} );

jQuery( document ).ready( function() {
	jQuery( '.account-icon' ).each( function() {
		const $el = jQuery( this );
		if ( $el.text().trim() === '' && ! $el.attr( 'aria-label' ) && ! $el.attr( 'title' ) ) {
			$el.attr( 'aria-label', 'My account' );
			$el.attr( 'title', 'My account' );
		}
	} );
} );

jQuery( document ).ready( function() {
	jQuery( '.product-image a' ).each( function() {
		const $link = jQuery( this );
		const $img = $link.find( 'img' );
		const altText = $img.attr( 'alt' ) || '';
		const titleText = $img.attr( 'title' ) || '';
		let label = altText || titleText;

		if ( ! label ) {
			const src = $img.attr( 'src' );
			if ( src ) {
				label = src.split( '/' ).pop().split( '.' )[ 0 ].replace( /[-_]/g, ' ' );
			}
		}

		if ( $link.text().trim() === '' && label ) {
			$link.attr( 'aria-label', label );
			$link.attr( 'title', label );
		}
	} );
} );

jQuery( document ).ready( function() {
	jQuery( '.play-icon a' ).each( function() {
		const $link = jQuery( this );

		if ( $link.text().trim() === '' && ! $link.attr( 'aria-label' ) ) {
			$link.attr( 'aria-label', 'Play video' );
			$link.attr( 'title', 'Play video' );
		}
	} );

	document.addEventListener( 'input', function( e ) {
		if ( e.target.matches( 'input[inputmode="numeric"]' ) ) {
			e.target.value = e.target.value.replace( /[^0-9]/g, '' );
		}
	} );
} );

jQuery( document ).on( 'click ajaxComplete', function() {
	const $fees = jQuery( '.mini-cart-extra-fees' );

	if ( ! $fees.length ) {
		return;
	}

	const $totalWrap = jQuery( '.whmc-cart-total-wrap' );

	if ( ! $totalWrap.length ) {
		return;
	}

	if ( jQuery( '.whmc-bottom-part .mini-cart-extra-fees' ).length ) {
		return;
	}

	$totalWrap.before( $fees );
} );
