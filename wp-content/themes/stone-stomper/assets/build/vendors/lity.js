/*! Lity - v2.4.1 - 2020-04-26
* http://sorgalla.com/lity/
* Copyright (c) 2015-2020 Jan Sorgalla; Licensed MIT */
( function( window, factory ) {
	if ( typeof define === 'function' && define.amd ) {
		define( [ 'jquery' ], function( $ ) {
			return factory( window, $ );
		} );
	} else if ( typeof module === 'object' && typeof module.exports === 'object' ) {
		module.exports = factory( window, require( 'jquery' ) );
	} else {
		window.lity = factory( window, window.jQuery || window.Zepto );
	}
}( typeof window !== 'undefined' ? window : this, function( window, $ ) {
	'use strict';

	const document = window.document;

	const _win = $( window );
	const _deferred = $.Deferred;
	const _html = $( 'html' );
	let _instances = [];

	const _attrAriaHidden = 'aria-hidden';
	const _dataAriaHidden = 'lity-' + _attrAriaHidden;

	const _focusableElementsSelector = 'a[href],area[href],input:not([disabled]),select:not([disabled]),textarea:not([disabled]),button:not([disabled]),iframe,object,embed,[contenteditable],[tabindex]:not([tabindex^="-"])';

	const _defaultOptions = {
		esc: true,
		handler: null,
		handlers: {
			image: imageHandler,
			inline: inlineHandler,
			youtube: youtubeHandler,
			vimeo: vimeoHandler,
			googlemaps: googlemapsHandler,
			facebookvideo: facebookvideoHandler,
			iframe: iframeHandler,
		},
		template: '<div class="lity" role="dialog" aria-label="Dialog Window (Press escape to close)" tabindex="-1"><div class="lity-wrap" data-lity-close role="document"><div class="lity-loader" aria-hidden="true">Loading...</div><div class="lity-container"><div class="lity-content"></div><button class="lity-close" type="button" aria-label="Close (Press escape to close)" data-lity-close></button></div></div></div>',
	};

	const _imageRegexp = /(^data:image\/)|(\.(png|jpe?g|gif|svg|webp|bmp|ico|tiff?)(\?\S*)?$)/i;
	const _youtubeRegex = /(youtube(-nocookie)?\.com|youtu\.be)\/(watch\?v=|v\/|u\/|embed\/?)?([\w-]{11})(.*)?/i;
	const _vimeoRegex = /(vimeo(pro)?.com)\/(?:[^\d]+)?(\d+)\??(.*)?$/;
	const _googlemapsRegex = /((maps|www)\.)?google\.([^\/\?]+)\/?((maps\/?)?\?)(.*)/i;
	const _facebookvideoRegex = /(facebook\.com)\/([a-z0-9_-]*)\/videos\/([0-9]*)(.*)?$/i;

	const _transitionEndEvent = ( function() {
		const el = document.createElement( 'div' );

		const transEndEventNames = {
			WebkitTransition: 'webkitTransitionEnd',
			MozTransition: 'transitionend',
			OTransition: 'oTransitionEnd otransitionend',
			transition: 'transitionend',
		};

		for ( const name in transEndEventNames ) {
			if ( el.style[ name ] !== undefined ) {
				return transEndEventNames[ name ];
			}
		}

		return false;
	}() );

	function transitionEnd( element ) {
		const deferred = _deferred();

		if ( ! _transitionEndEvent || ! element.length ) {
			deferred.resolve();
		} else {
			element.one( _transitionEndEvent, deferred.resolve );
			setTimeout( deferred.resolve, 500 );
		}

		return deferred.promise();
	}

	function settings( currSettings, key, value ) {
		if ( arguments.length === 1 ) {
			return $.extend( {}, currSettings );
		}

		if ( typeof key === 'string' ) {
			if ( typeof value === 'undefined' ) {
				return typeof currSettings[ key ] === 'undefined'
					? null
					: currSettings[ key ];
			}

			currSettings[ key ] = value;
		} else {
			$.extend( currSettings, key );
		}

		return this;
	}

	function parseQueryParams( params ) {
		const pairs = decodeURI( params.split( '#' )[ 0 ] ).split( '&' );
		let obj = {},
			p;

		for ( let i = 0, n = pairs.length; i < n; i++ ) {
			if ( ! pairs[ i ] ) {
				continue;
			}

			p = pairs[ i ].split( '=' );
			obj[ p[ 0 ] ] = p[ 1 ];
		}

		return obj;
	}

	function appendQueryParams( url, params ) {
		return url + ( url.indexOf( '?' ) > -1 ? '&' : '?' ) + $.param( params );
	}

	function transferHash( originalUrl, newUrl ) {
		const pos = originalUrl.indexOf( '#' );

		if ( -1 === pos ) {
			return newUrl;
		}

		if ( pos > 0 ) {
			originalUrl = originalUrl.substr( pos );
		}

		return newUrl + originalUrl;
	}

	function error( msg ) {
		return $( '<span class="lity-error"></span>' ).append( msg );
	}

	function imageHandler( target, instance ) {
		const desc = ( instance.opener() && instance.opener().data( 'lity-desc' ) ) || 'Image with no description';
		const img = $( '<img src="' + target + '" alt="' + desc + '"/>' );
		const deferred = _deferred();
		const failed = function() {
			deferred.reject( error( 'Failed loading image' ) );
		};

		img
			.on( 'load', function() {
				if ( this.naturalWidth === 0 ) {
					return failed();
				}

				deferred.resolve( img );
			} )
			.on( 'error', failed )
		;

		return deferred.promise();
	}

	imageHandler.test = function( target ) {
		return _imageRegexp.test( target );
	};

	function inlineHandler( target, instance ) {
		let el, placeholder, hasHideClass;

		try {
			el = $( target );
		} catch ( e ) {
			return false;
		}

		if ( ! el.length ) {
			return false;
		}

		placeholder = $( '<i style="display:none !important"></i>' );
		hasHideClass = el.hasClass( 'lity-hide' );

		instance
			.element()
			.one( 'lity:remove', function() {
				placeholder
					.before( el )
					.remove()
				;

				if ( hasHideClass && ! el.closest( '.lity-content' ).length ) {
					el.addClass( 'lity-hide' );
				}
			} )
		;

		return el
			.removeClass( 'lity-hide' )
			.after( placeholder )
		;
	}

	function youtubeHandler( target ) {
		const matches = _youtubeRegex.exec( target );

		if ( ! matches ) {
			return false;
		}

		return iframeHandler(
			transferHash(
				target,
				appendQueryParams(
					'https://www.youtube' + ( matches[ 2 ] || '' ) + '.com/embed/' + matches[ 4 ],
					$.extend(
						{
							autoplay: 1,
						},
						parseQueryParams( matches[ 5 ] || '' )
					)
				)
			)
		);
	}

	function vimeoHandler( target ) {
		const matches = _vimeoRegex.exec( target );

		if ( ! matches ) {
			return false;
		}

		return iframeHandler(
			transferHash(
				target,
				appendQueryParams(
					'https://player.vimeo.com/video/' + matches[ 3 ],
					$.extend(
						{
							autoplay: 1,
						},
						parseQueryParams( matches[ 4 ] || '' )
					)
				)
			)
		);
	}

	function facebookvideoHandler( target ) {
		const matches = _facebookvideoRegex.exec( target );

		if ( ! matches ) {
			return false;
		}

		if ( 0 !== target.indexOf( 'http' ) ) {
			target = 'https:' + target;
		}

		return iframeHandler(
			transferHash(
				target,
				appendQueryParams(
					'https://www.facebook.com/plugins/video.php?href=' + target,
					$.extend(
						{
							autoplay: 1,
						},
						parseQueryParams( matches[ 4 ] || '' )
					)
				)
			)
		);
	}

	function googlemapsHandler( target ) {
		const matches = _googlemapsRegex.exec( target );

		if ( ! matches ) {
			return false;
		}

		return iframeHandler(
			transferHash(
				target,
				appendQueryParams(
					'https://www.google.' + matches[ 3 ] + '/maps?' + matches[ 6 ],
					{
						output: matches[ 6 ].indexOf( 'layer=c' ) > 0 ? 'svembed' : 'embed',
					}
				)
			)
		);
	}

	function iframeHandler( target ) {
		return '<div class="lity-iframe-container"><iframe frameborder="0" allowfullscreen allow="autoplay; fullscreen" src="' + target + '"/></div>';
	}

	function winHeight() {
		return document.documentElement.clientHeight
			? document.documentElement.clientHeight
			: Math.round( _win.height() );
	}

	function keydown( e ) {
		const current = currentInstance();

		if ( ! current ) {
			return;
		}

		// ESC key
		if ( e.keyCode === 27 && !! current.options( 'esc' ) ) {
			current.close();
		}

		// TAB key
		if ( e.keyCode === 9 ) {
			handleTabKey( e, current );
		}
	}

	function handleTabKey( e, instance ) {
		const focusableElements = instance.element().find( _focusableElementsSelector );
		const focusedIndex = focusableElements.index( document.activeElement );

		if ( e.shiftKey && focusedIndex <= 0 ) {
			focusableElements.get( focusableElements.length - 1 ).focus();
			e.preventDefault();
		} else if ( ! e.shiftKey && focusedIndex === focusableElements.length - 1 ) {
			focusableElements.get( 0 ).focus();
			e.preventDefault();
		}
	}

	function resize() {
		$.each( _instances, function( i, instance ) {
			instance.resize();
		} );
	}

	function registerInstance( instanceToRegister ) {
		if ( 1 === _instances.unshift( instanceToRegister ) ) {
			_html.addClass( 'lity-active' );

			_win
				.on( {
					resize,
					keydown,
				} )
			;
		}

		$( 'body > *' ).not( instanceToRegister.element() )
			.addClass( 'lity-hidden' )
			.each( function() {
				const el = $( this );

				if ( undefined !== el.data( _dataAriaHidden ) ) {
					return;
				}

				el.data( _dataAriaHidden, el.attr( _attrAriaHidden ) || null );
			} )
			.attr( _attrAriaHidden, 'true' )
		;
	}

	function removeInstance( instanceToRemove ) {
		let show;

		instanceToRemove
			.element()
			.attr( _attrAriaHidden, 'true' )
		;

		if ( 1 === _instances.length ) {
			_html.removeClass( 'lity-active' );

			_win
				.off( {
					resize,
					keydown,
				} )
			;
		}

		_instances = $.grep( _instances, function( instance ) {
			return instanceToRemove !== instance;
		} );

		if ( !! _instances.length ) {
			show = _instances[ 0 ].element();
		} else {
			show = $( '.lity-hidden' );
		}

		show
			.removeClass( 'lity-hidden' )
			.each( function() {
				const el = $( this ),
					oldAttr = el.data( _dataAriaHidden );

				if ( ! oldAttr ) {
					el.removeAttr( _attrAriaHidden );
				} else {
					el.attr( _attrAriaHidden, oldAttr );
				}

				el.removeData( _dataAriaHidden );
			} )
		;
	}

	function currentInstance() {
		if ( 0 === _instances.length ) {
			return null;
		}

		return _instances[ 0 ];
	}

	function factory( target, instance, handlers, preferredHandler ) {
		let handler = 'inline',
			content;

		const currentHandlers = $.extend( {}, handlers );

		if ( preferredHandler && currentHandlers[ preferredHandler ] ) {
			content = currentHandlers[ preferredHandler ]( target, instance );
			handler = preferredHandler;
		} else {
			// Run inline and iframe handlers after all other handlers
			$.each( [ 'inline', 'iframe' ], function( i, name ) {
				delete currentHandlers[ name ];

				currentHandlers[ name ] = handlers[ name ];
			} );

			$.each( currentHandlers, function( name, currentHandler ) {
				// Handler might be "removed" by setting callback to null
				if ( ! currentHandler ) {
					return true;
				}

				if (
					currentHandler.test &&
                    ! currentHandler.test( target, instance )
				) {
					return true;
				}

				content = currentHandler( target, instance );

				if ( false !== content ) {
					handler = name;
					return false;
				}
			} );
		}

		return { handler, content: content || '' };
	}

	function Lity( target, options, opener, activeElement ) {
		const self = this;
		let result;
		let isReady = false;
		let isClosed = false;
		let element;
		let content;

		options = $.extend(
			{},
			_defaultOptions,
			options
		);

		element = $( options.template );

		// -- API --

		self.element = function() {
			return element;
		};

		self.opener = function() {
			return opener;
		};

		self.options = $.proxy( settings, self, options );
		self.handlers = $.proxy( settings, self, options.handlers );

		self.resize = function() {
			if ( ! isReady || isClosed ) {
				return;
			}

			content
				.css( 'max-height', winHeight() + 'px' )
				.trigger( 'lity:resize', [ self ] )
			;
		};

		self.close = function() {
			if ( ! isReady || isClosed ) {
				return;
			}

			isClosed = true;

			removeInstance( self );

			const deferred = _deferred();

			// We return focus only if the current focus is inside this instance
			if (
				activeElement &&
                (
                	document.activeElement === element[ 0 ] ||
                    $.contains( element[ 0 ], document.activeElement )
                )
			) {
				try {
					activeElement.focus();
				} catch ( e ) {
					// Ignore exceptions, eg. for SVG elements which can't be
					// focused in IE11
				}
			}

			content.trigger( 'lity:close', [ self ] );

			element
				.removeClass( 'lity-opened' )
				.addClass( 'lity-closed' )
			;

			transitionEnd( content.add( element ) )
				.always( function() {
					content.trigger( 'lity:remove', [ self ] );
					element.remove();
					element = undefined;
					deferred.resolve();
				} )
			;

			return deferred.promise();
		};

		// -- Initialization --

		result = factory( target, self, options.handlers, options.handler );

		element
			.attr( _attrAriaHidden, 'false' )
			.addClass( 'lity-loading lity-opened lity-' + result.handler )
			.appendTo( 'body' )
			.focus()
			.on( 'click', '[data-lity-close]', function( e ) {
				if ( $( e.target ).is( '[data-lity-close]' ) ) {
					self.close();
				}
			} )
			.trigger( 'lity:open', [ self ] )
		;

		registerInstance( self );

		$.when( result.content )
			.always( ready )
		;

		function ready( result ) {
			content = $( result )
				.css( 'max-height', winHeight() + 'px' )
			;

			element
				.find( '.lity-loader' )
				.each( function() {
					const loader = $( this );

					transitionEnd( loader )
						.always( function() {
							loader.remove();
						} )
					;
				} )
			;

			element
				.removeClass( 'lity-loading' )
				.find( '.lity-content' )
				.empty()
				.append( content )
			;

			isReady = true;

			content
				.trigger( 'lity:ready', [ self ] )
			;
		}
	}

	function lity( target, options, opener ) {
		if ( ! target.preventDefault ) {
			opener = $( opener );
		} else {
			target.preventDefault();
			opener = $( this );
			target = opener.data( 'lity-target' ) || opener.attr( 'href' ) || opener.attr( 'src' );
		}

		const instance = new Lity(
			target,
			$.extend(
				{},
				opener.data( 'lity-options' ) || opener.data( 'lity' ),
				options
			),
			opener,
			document.activeElement
		);

		if ( ! target.preventDefault ) {
			return instance;
		}
	}

	lity.version = '2.4.1';
	lity.options = $.proxy( settings, lity, _defaultOptions );
	lity.handlers = $.proxy( settings, lity, _defaultOptions.handlers );
	lity.current = currentInstance;

	$( document ).on( 'click.lity', '[data-lity]', lity );

	return lity;
} ) );
