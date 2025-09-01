import { Button, Modal, Tooltip } from '@wordpress/components';
import { useState } from '@wordpress/element';

export default function Popup( { props, PopupKey, PopupValue, options, ButtonText, help } ) {
	const [ isOpen, setOpen ] = useState( false );
	const openModal = () => setOpen( true );
	const closeModal = () => setOpen( false );

	const {
		setAttributes,
	} = props;
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
	const checkBoxEffect = ( event ) => {
		const button = event.target.parentNode;
		let dataValue = button.getAttribute( 'data-value' );
		if ( typeof dataValue === 'undefined' ) {
			dataValue = '';
		}
		// Remove the "selected" class from all other buttons
		const buttons = document.querySelectorAll( '.design-option-btn-popup' );
		buttons.forEach( ( btn ) => {
			btn = btn.parentNode;
			if ( btn !== button ) {
				btn.classList.remove( 'selected' );
			}
		} );

		// Toggle the "selected" class for the clicked button
		button.classList.toggle( 'selected' );
		if ( button.classList.contains( ( 'selected' ) ) ) {
			setAttributes( { [ PopupKey ]: dataValue } );
		} else {
			setAttributes( { [ PopupKey ]: '' } );
		}
		closeModal();
	};
	let savedDisplay = '';
	let savedLabel = '';
	const Buttons = options.map( ( element, index ) => {
		let selected = 'design-option-item';
		if ( PopupValue === element.value ) {
			selected = 'design-option-item selected';
			savedDisplay = element.display;
			savedLabel = element.label;
		}
		let myStyle = {};
		if ( element.display.startsWith( '#' ) ) {
			myStyle = {
				backgroundColor: element.display,
				color: getContrastingTextColor( element.display ),
				fontSize: '20px',
			};
		} else {
			myStyle = {
				backgroundImage: 'url(' + require( '../images/' + element.display ) + ')',
			};
		}
		return (
			<>
				<Tooltip text={ element.label } position={ 'bottom' }>
					<div className={ selected } data-value={ element.value }>
						<button key={ index } style={ myStyle }

							onClick={ ( event ) => {
								checkBoxEffect( event );
							} }
							className="design-option-btn-popup"
						>
							{ ( element.display.startsWith( '#' ) ) ? element.label : '' }
						</button>
					</div>
				</Tooltip>
			</>
		);
	} );

	let mySavedStyle = {};
	if ( savedDisplay ) {
		if ( savedDisplay.startsWith( '#' ) ) {
			mySavedStyle = {
				backgroundColor: savedDisplay,
				color: getContrastingTextColor( savedDisplay ),
				fontSize: '20px',
			};
		} else {
			mySavedStyle = {
				backgroundImage: 'url(' + require( '../images/' + savedDisplay ) + ')',
			};
		}
	}
	return (
		<>
			{ savedDisplay === '' || typeof savedDisplay === 'undefined' ? (
				<Button variant="secondary" onClick={ openModal } data-color="default">
					{ ButtonText }
				</Button>
			) : (
				<>
					<Tooltip text={ savedLabel } position={ 'bottom' }>

						<div className="popup-output" >
							<Button variant="secondary" className="popup-btn" onClick={ openModal } style={ mySavedStyle }>
								{ ( savedDisplay.startsWith( '#' ) ) ? savedLabel : '' }
							</Button>
						</div>
					</Tooltip>

					<div className="dc-popup-buttons">
						<Button onClick={ openModal } className="is-secondary">
							Replace Option
						</Button>
						<Button onClick={ () => {
							setAttributes( { [ PopupKey ]: '' } );
						} } className="is-link is-destructive">
							Remove Option
						</Button>
					</div>
					<p className="components-base-control__help">{ help }</p>
				</>
			) }

			{ isOpen && (
				<Modal isFullScreen={ true } title="Shapes" onRequestClose={ closeModal } className="dc-popup dc-design-shapes">
					<div className="design-option">
						{ Buttons }
					</div>
				</Modal>
			) }
		</>
	);
}
