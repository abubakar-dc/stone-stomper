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
	const barAndBracketProduct = '2366';

	jQuery( document ).on(
		'change blur',
		'#vehicle-details input, #vehicle-details select, #vehicle-details textarea',
		function() {
			if ( jQuery( '#product_type' ).val() !== barAndBracketProduct ) {
				return;
			}

			if ( allRequiredFilled( '#vehicle-details' ) ) {
				const section = jQuery( '#bar-options-section' );
				if ( section.hasClass( 'section-disable' ) ) {
					section.removeClass( 'section-disable' );
				}
				scrollToSection( '#bar-options-section' );
			}
		}
	);

	function scrollToSection( id ) {
		const target = document.querySelector( id );
		if ( target ) {
			requestAnimationFrame( function() {
				target.scrollIntoView( { behavior: 'smooth', block: 'start' } );
			} );
		}
	}

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

			if ( selectedProduct === barAndBracketProduct ) {
				const section = jQuery( '#bar-options-section' );
				if ( section.hasClass( 'section-disable' ) ) {
					section.removeClass( 'section-disable' );
				}
				scrollToSection( '#bar-options-section' );
				return;
			}

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

	let finalSectionsUnlocked = false;

	function revealFinalSections() {
		if ( ! finalSectionsUnlocked && allRequiredFilled( '#bar-options-section' ) ) {
			finalSectionsUnlocked = true;
			const sections = jQuery( '#photographs-details, #final-measurements, #final-summary' );
			sections.removeClass( 'section-disable' );
			setTimeout( function() {
				scrollToSection( '#photographs-details' );
			}, 300 );
		}
	}

	jQuery( document ).on( 'change blur', '#bar-options-section input, #bar-options-section select, #bar-options-section textarea', revealFinalSections );

	jQuery( '.form-section-right #btn_cart' ).on( 'click', function() {
		jQuery( this ).addClass( 'btn-loading' );
	} );

	function forceHideOnBarAndBracketValues() {
		jQuery( '.hide-on-bar-and-bracket' )
			.find( 'input, select, textarea' )
			.each( function() {
				jQuery( this )
					.val( '' )
					.prop( 'required', false );
			} );
	}

	jQuery( document ).on( 'change', '#product_type', function() {
		const selectedProduct = jQuery( this ).val();
		const caravanFields = '#caravan-details input, #caravan-details select, #caravan-details textarea';
		const supportMeasurementFields =
		'#final-measurements .ss-support-options input,' +
		'#final-measurements .ss-support-options select,' +
		'#final-measurements .ss-support-options textarea';
		const alwaysHiddenRequired = '#vinyl_width, #vinyl_length';
		if ( selectedProduct === barAndBracketProduct ) {
			jQuery( '#details-section' ).addClass( 'bar-and-bracket-selected' );
			jQuery( '#caravan-details' ).hide();
			jQuery( '#final-measurements' ).find( '.ss-support-options' ).addClass( 'hide-on-bar-and-bracket' );
			jQuery( '#final-measurements' ).find( '.hide-on-bar-and-bracket' ).hide();
			jQuery( '#final-measurements' ).find( '.bar-length-only' ).hide();
			jQuery( '#final-measurements .ss-support-options' ).addClass( 'hide-on-bar-and-bracket' ).hide();
			jQuery( '.bar-length-only' ).addClass( 'hide-on-bar-and-bracket' ).hide();
			jQuery( caravanFields ).removeAttr( 'required' );
			jQuery( supportMeasurementFields ).removeAttr( 'required' );
			jQuery( alwaysHiddenRequired ).removeAttr( 'required' );
			jQuery( '#caravan-details input, #caravan-details select, #caravan-details textarea' ).val( '0' );
			forceHideOnBarAndBracketValues();
		} else {
			jQuery( '#details-section' ).removeClass( 'bar-and-bracket-selected' );
			jQuery( '#caravan-details' ).show();
			jQuery( '#final-measurements .hide-on-bar-and-bracket' ).show();
			jQuery( '.bar-length-only' ).hide();
			jQuery( '.bar-length-only' ).addClass( 'hide-on-bar-and-bracket' ).show();
		}
	} );

	jQuery( document ).on(
		'input change',
		'.hide-on-bar-and-bracket input, .hide-on-bar-and-bracket select, .hide-on-bar-and-bracket textarea',
		function() {
			if ( jQuery( '#details-section' ).hasClass( 'bar-and-bracket-selected' ) ) {
				jQuery( this ).val( '0' );
			}
		}
	);

	jQuery( document ).on( 'change', '#bar_options', function() {
		if ( jQuery( '#details-section' ).hasClass( 'bar-and-bracket-selected' ) ) {
			forceHideOnBarAndBracketValues();
		}
	} );
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
	getIdsField( slot ).value = JSON.stringify( ids );
}

function setupImageUpload( inputId, listId, slot ) {
	const input = document.getElementById( inputId );
	const list = document.getElementById( listId );
	if ( ! input || ! list ) {
		return;
	}

	let allFiles = []; // store all selected files

	input.addEventListener( 'change', function() {
		const newFiles = Array.from( input.files );
		allFiles = allFiles.concat( newFiles ); // merge old and new
		showFiles( allFiles, list );
		autoUpload( allFiles, slot, list );
	} );

	function showFiles( files, list ) {
		list.innerHTML = '';
		files.forEach( ( file, index ) => {
			if ( ! file.type.startsWith( 'image/' ) ) {
				const error = document.createElement( 'p' );
				error.classList.add( 'file-not-uploaded' );
				error.style.color = 'red';
				error.style.margin = '0 0 5px 0';
				error.textContent = file.name + ' is not an image file!';
				list.innerHTML = '';
				list.appendChild( error );
				return;
			}
			const li = document.createElement( 'li' );
			li.style.display = 'flex';
			li.style.alignItems = 'center';
			li.style.marginBottom = '8px';

			const img = document.createElement( 'img' );
			img.src = URL.createObjectURL( file );
			img.style.width = '80px';
			img.style.height = '80px';
			img.style.objectFit = 'cover';
			img.style.marginRight = '10px';
			img.style.border = '1px solid #ccc';
			img.style.borderRadius = '6px';

			const span = document.createElement( 'span' );
			span.textContent = file.name;

			const status = document.createElement( 'em' );
			status.style.marginLeft = '8px';
			status.textContent = ' Pending…';

			const delBtn = document.createElement( 'button' );
			delBtn.type = 'button';
			delBtn.textContent = '❌';
			delBtn.style.marginLeft = '10px';
			delBtn.addEventListener( 'click', () => {
				allFiles.splice( index, 1 ); // remove from allFiles
				showFiles( allFiles, list );
				autoUpload( allFiles, slot, list );
			} );

			li.appendChild( img );
			li.appendChild( span );
			li.appendChild( status );
			li.appendChild( delBtn );
			list.appendChild( li );
		} );
	}

	function autoUpload( files, slot, list ) {
		if ( ! files.length ) {
			list.querySelectorAll( 'em' ).forEach( ( e ) => e.textContent = '' );
			return;
		}

		const fd = new FormData();
		fd.append( 'action', 'bst_handle_upload_order_photos' );
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
						list.querySelectorAll( 'em' ).forEach( ( e ) => e.textContent = ' Uploading ' + pct + '%' );
					}
				} );
				return xhr;
			},
			success( resp ) {
				if ( resp && resp.success ) {
					const data = resp.data || resp;
					const urls = data && data[ slot ] ? data[ slot ].map( ( x ) => x.url ) : [];
					writeIds( slot, urls );
					list.querySelectorAll( 'em' ).forEach( ( e ) => e.textContent = 'Uploaded' );
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

	jQuery( '#bar_options' ).on( 'change', function() {
		const selected = jQuery( this ).val();
		const optionTwo = 'Option 2 Standard Post';
		const optionThree = [
			'Option 3 Standard Shank',
			'Option 3 Adjustable Shank',
		];

		jQuery( '.option-two-description, .option-three-description' ).hide();

		if ( selected === optionTwo ) {
			jQuery( '.hitch_measurement_dropdown' ).slideDown();
			jQuery( '#option-two-description' ).slideDown();
		} else if ( optionThree.includes( selected ) ) {
			jQuery( '.hitch_measurement_dropdown' ).slideDown();
			jQuery( '#option-three-description' ).slideDown();
		} else {
			jQuery( '.hitch_measurement_dropdown' ).slideUp();
			jQuery( '#additional_hitch_measurement' ).val( '' );
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

// Form

// jQuery( document ).ready( function() {
// 	jQuery( '.gfield-choice-input' ).on( 'change', function() {
// 		jQuery( '.toolbox-support, .factory_stoneguard' ).hide().find( 'input[type="text"]' ).val( '' );
// 		if ( jQuery( this ).is( '#toolbox' ) ) {
// 			jQuery( '.toolbox-support' ).show();
// 		}
// 		if ( jQuery( this ).is( '#factory_stoneguard' ) ) {
// 			jQuery( '.factory_stoneguard' ).show();
// 		}
// 	} );
// } );

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

