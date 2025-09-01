import { Tooltip } from '@wordpress/components';
import UseImage from './UseImage.jsx';

export default function ShapeOption( { props, value, DesignKey, options, help } ) {
	const {
		setAttributes,
	} = props;

	const checkBoxEffect = ( event ) => {
		const button = event.target.parentNode;
		const dataValue = button.getAttribute( 'data-value' );
		let selectedValues = Array.isArray(value) ? [...value] : [];

		// Toggle selection
		if (selectedValues.includes(dataValue)) {
			selectedValues = selectedValues.filter(val => val !== dataValue);
			button.classList.remove('selected');
		} else {
			selectedValues.push(dataValue);
			button.classList.add('selected');
		}

		setAttributes({ [DesignKey]: selectedValues });
	};

	const getContrastingTextColor = ( bgColor ) => {
		let rgb = [];
		if ( /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test( bgColor ) ) {
			rgb = bgColor.match( /\w{2}/g ).map( ( hex ) => parseInt( hex, 16 ) );
		} else if ( /^rgb\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*\)$/.test( bgColor ) ) {
			rgb = bgColor.match( /\d+/g ).map( Number );
		}
		const brightness = ( rgb[ 0 ] * 299 + rgb[ 1 ] * 587 + rgb[ 2 ] * 114 ) / 1000;
		return brightness > 128 ? '#000000' : '#FFFFFF';
	};

	const Buttons = options.map( ( element, index ) => {
		let selected = 'design-option-item';
		if ( Array.isArray(value) && value.includes(element.value) ) {
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
