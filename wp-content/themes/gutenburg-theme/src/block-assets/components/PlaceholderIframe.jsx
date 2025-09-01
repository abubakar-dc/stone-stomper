
import { Button, Modal, TextControl, Icon } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { trash } from '@wordpress/icons';
import './global.scss';
const PlaceholderIframe = ( { props, valueUrl, attrUrl } ) => {
	const {
		setAttributes,
	} = props;
	const [ isOpen, setOpen ] = useState( false );
	const [ url, setUrl ] = useState( valueUrl ?? '' );
	const openModal = () => setOpen( true );
	const closeModal = () => setOpen( false );
	if ( valueUrl ) {
		return (
			<>

				<div className="image-box">
					<iframe src={ valueUrl } frameBorder="0" title="hero-iframe"></iframe>
					<div className="dc-control-buttons">
						<Button type="button" className="is-primary" onClick={ openModal }> Replace Video</Button>
						<Button type="button" className="is-link is-destructive" onClick={ () => {
							setAttributes( {
								[ attrUrl ]: '',
							} );
						} }><Icon icon={ trash } /></Button>
					</div>
				</div>
				{ isOpen && (
					<Modal isFullScreen={ false } title="Video Iframe" onRequestClose={ closeModal } className="dc-popup dc-design-shapes">
						<TextControl
							value={ url }
							label="Add Video Iframe URL"
							onChange={ ( value ) => {
								setUrl( value );
							} }
						/>
						<Button type="button" className="is-primary" onClick={ () => {
							closeModal();
							setAttributes( {
								[ attrUrl ]: url,
							} );
						} }>Save</Button>
					</Modal>
				) }

			</>

		);
	}
	return (
		<>
			<div className="image-box">
				<div className="dc-placeholder-iframe" onClick={ openModal }>
					<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60" preserveAspectRatio="none" className="components-placeholder__illustration" aria-hidden="true" focusable="false"><path vectorEffect="non-scaling-stroke" d="M60 60 0 0"></path></svg>
				</div>
				<Button type="button" className="is-primary" onClick={ openModal }> Add Video</Button>

			</div>
			{ isOpen && (
				<Modal isFullScreen={ false } title="Video Iframe" onRequestClose={ closeModal } className="dc-popup dc-design-shapes">
					<TextControl
						value={ url }
						label="Add Video Iframe URL"
						onChange={ ( value ) => {
							setUrl( value );
						} }
					/>
					<Button type="button" className="is-primary" onClick={ () => {
						closeModal();
						setAttributes( {
							[ attrUrl ]: url,
						} );
					} }>Save</Button>
				</Modal>
			) }

		</>
	);
};

export default PlaceholderIframe;

