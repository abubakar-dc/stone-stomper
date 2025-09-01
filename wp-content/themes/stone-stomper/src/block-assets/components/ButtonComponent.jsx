import { __ } from '@wordpress/i18n';
import { Button, Popover, TextControl } from '@wordpress/components';
import { LinkControl } from '@wordpress/block-editor';
import { useState, useEffect } from '@wordpress/element';

const ButtonComponent = ( { props, value, attr } ) => {
	const { setAttributes } = props;
	const [ isVisible, setIsVisible ] = useState( false );

	const openURLPopover = () => setIsVisible( true );
	const closeURLPopover = () => setIsVisible( false );

	const handleLinkChange = ( newValue ) => {
		setAttributes( {
			[ attr ]: {
				url: newValue.url,
				title: value.title || '',
				target: newValue.opensInNewTab || false,
			},
		} );
	};

	const handleTitleChange = ( newTitle ) => {
		setAttributes( {
			[ attr ]: {
				url: value.url,
				title: newTitle,
				target: value.target,
			},
		} );
	};

	return (
		<>
			{ /* Trigger button - show label or placeholder */ }
			<div className="wp-block-button is-style-with-arrow is-color-brown is-size-large">

				<Button className="wp-block-button__link wp-element-button" onClick={ openURLPopover }>
					{ value.title && value.title.trim() !== ''
						? value.title
						: __( 'Add Button Label' ) }
				</Button>
			</div>

			{ isVisible && (
				<Popover position="bottom center" onClose={ closeURLPopover }>
					{ /* Label input */ }
					<TextControl
						label={ __( 'Button Label' ) }
						value={ value.title || '' }
						onChange={ handleTitleChange }
						placeholder={ __( 'Enter button text…' ) }
					/>

					{ /* Link picker (URL + open in new tab) */ }
					<LinkControl
						value={ {
							url: value.url || '',
							opensInNewTab: value.target || false,
						} }
						onChange={ handleLinkChange }
						settings={ [
							{
								id: 'opensInNewTab',
								title: __( 'Open in new tab' ),
							},
						] }
					/>
				</Popover>
			) }
		</>
	);
};

export default ButtonComponent;
