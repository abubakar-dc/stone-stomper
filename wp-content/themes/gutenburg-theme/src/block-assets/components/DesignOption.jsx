
import { Tooltip } from '@wordpress/components';
import UseImage from './UseImage.jsx';

export default function DesignOption( { props, value, DesignKey, options, help } ) {
	const {
		setAttributes,
	} = props;

	const checkBoxEffect = ( event ) => {
		const button = event.target.parentNode;
		const dataValue = button.getAttribute( 'data-value' );
		// Remove the "selected" class from all other buttons
		const buttons = document.querySelectorAll( '.design-option-btn' );
		buttons.forEach( ( btn ) => {
			btn = btn.parentNode;
			if ( btn !== button ) {
				btn.classList.remove( 'selected' );
			}
		} );

		// Toggle the "selected" class for the clicked button
		button.classList.toggle( 'selected' );
		if ( button.classList.contains( ( 'selected' ) ) ) {
			setAttributes( { [ DesignKey ]: dataValue } );
		} else {
			setAttributes( { [ DesignKey ]: '' } );
		}
	};
	const getContrastingTextColor = ( bgColor ) => {
		// Convert the background color to RGB values
		let rgb = [];

		if ( /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test( bgColor ) ) {
			// Valid hex color value, convert to RGB
			rgb = bgColor.match( /\w{2}/g ).map( ( hex ) => parseInt( hex, 16 ) );
		} else if ( /^rgb\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*\)$/.test( bgColor ) ) {
			// Valid RGB color value
			rgb = bgColor.match( /\d+/g ).map( Number );
		}
		const brightness = ( rgb[ 0 ] * 299 + rgb[ 1 ] * 587 + rgb[ 2 ] * 114 ) / 1000; // Calculate brightness
		// Set the text color based on brightness threshold (you can adjust the threshold as needed)
		return brightness > 128 ? '#000000' : '#FFFFFF';
	};
	const Buttons = options.map( ( element, index ) => {
		let selected = 'design-option-item';
		if ( value === element.value ) {
			selected = 'design-option-item selected';
		}
		let myStyle = {};
		if ( element.display.startsWith( '#' ) ) {
			myStyle = {
				position: 'relative',
				'--bg_container_color': element.display,
				'--bg_container_circle_color': getContrastingTextColor( element.display ),
				fontSize: '20px',
			};
		} else {
			const { image } = UseImage( element.display );
			if ( image ) {
				myStyle = {
					backgroundImage: 'url(' + image + ')',
				};
			}
		}
		return (
			<>
				<Tooltip text={ element.label } position={ 'bottom' }>

					<div className={ selected } style={ myStyle } data-value={ element.value }>
						<button key={ index }
							onClick={ ( event ) => {
								checkBoxEffect( event );
							} }
							className="design-option-btn"
						>
							{ ( element.display.startsWith( '#' ) ) ? element.label : '' }
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="#fff" aria-hidden="true" focusable="false"><path d="M16.7 7.1l-6.3 8.5-3.3-2.5-.9 1.2 4.5 3.4L17.9 8z"></path></svg>
						</button>
					</div>
				</Tooltip>
			</>
		);
	} );
	return (
		<>
			<div className="dc-sidebar-help-text">
				<p className="components-base-control__help">{ help }</p>
			</div>
			<div className="design-option">
				{ Buttons }
			</div>
		</>
	);
}
