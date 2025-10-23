jQuery.fn.mediaBox = function() {
	return this.each( function() {
		const element = jQuery( this );
		element.find( '.acf-button-group label' ).off( 'click' ).on( 'click', function() {
			const value = jQuery( this ).find( 'input[type="radio"]' ).attr( 'value' );
			element.find( '.acf-field-inner' ).hide();
			element.find( '.acf-field-inner[data-name="' + value + '"]' ).show();
		} );
		element.find( '.acf-field-inner[data-name="image"]' ).find( 'a[data-name="add"],.acf-actions .-pencil.dark' ).on( 'click', function() {
			const el = jQuery( this ).parents( '.acf-field-inner[data-name="image"]' );
			const image = wp
				.media( {
					title: 'Upload Image',
					multiple: false,
					library: {
						type: 'image',
					},
				} )
				.open()
				.on( 'select', function() {
					const uploadedImage = image.state().get( 'selection' ).first();
					el.find( 'input[type="hidden"]' ).val( uploadedImage.id );
					el.find( 'input[type="hidden"]' ).trigger( 'change' );
					el.find( '.image-wrap' ).find( 'img' ).attr( 'src', uploadedImage.attributes.url );
					el.find( '.image-wrap' ).removeClass( 'show-if-value' );
					element.find( '.acf-image-uploader' ).addClass( 'has-value' );
				} );
		} );
		element.find( '.acf-field-inner[data-name="image"]' ).find( '.acf-actions .-cancel.dark' ).on( 'click', function() {
			const el = jQuery( this ).parents( '.acf-field-inner[data-name="image"]' );
			el.find( '.image-wrap' ).addClass( 'show-if-value' );
			el.find( '.image-wrap' ).find( 'img' ).attr( 'src', '' );
			el.find( 'input[type="hidden"]' ).val( '' );
			element.find( '.acf-image-uploader' ).removeClass( 'has-value' );
		} );
		element.find( '.acf-field-inner[data-name="video"]' ).find( 'a[data-name="add"],.acf-actions .-pencil.dark' ).on( 'click', function() {
			const el = jQuery( this ).parents( '.acf-field-inner[data-name="video"]' );
			const video = wp
				.media( {
					title: 'Upload Video',
					multiple: false,
					library: {
						type: 'video',
					},
				} )
				.open()
				.on( 'select', function() {
					const uploadedVideo = video.state().get( 'selection' ).first();
					el.find( 'input[type="hidden"]' ).val( uploadedVideo.id );
					el.find( 'input[type="hidden"]' ).trigger( 'change' );
					el.find( '.file-wrap' ).removeClass( 'show-if-value' );
					el.find( '.file-icon' ).find( 'img' ).attr( 'src', uploadedVideo.attributes.icon );
					el.find( '.file-info' ).find( 'strong[data-name="title"]' ).text( uploadedVideo.attributes.title );
					el.find( '.file-info' ).find( 'a[data-name="filename"]' ).text( uploadedVideo.attributes.filename );
					el.find( '.file-info' ).find( 'a[data-name="filename"]' ).attr( 'href', uploadedVideo.attributes.url );
					el.find( '.file-info' ).find( 'span[data-name="filesize"]' ).text( uploadedVideo.attributes.filesizeHumanReadable );
					element.find( '.acf-file-uploader' ).addClass( 'has-value' );
				} );
		} );
		element.find( '.acf-field-inner[data-name="video"]' ).find( '.acf-actions .-cancel.dark' ).on( 'click', function() {
			const el = jQuery( this ).parents( '.acf-field-inner[data-name="video"]' );
			el.find( 'input[type="hidden"]' ).val( '' );
			el.find( '.file-wrap' ).addClass( 'show-if-value' );
			el.find( '.file-icon' ).find( 'img' ).attr( 'src', '' );
			el.find( '.file-info' ).find( 'strong[data-name="title"]' ).text( '' );
			el.find( '.file-info' ).find( 'a[data-name="filename"]' ).text( '' );
			el.find( '.file-info' ).find( 'a[data-name="filename"]' ).attr( 'href', '' );
			el.find( '.file-info' ).find( 'span[data-name="filesize"]' ).text( '' );
			element.find( '.acf-file-uploader' ).removeClass( 'has-value' );
		} );
	} );
};
