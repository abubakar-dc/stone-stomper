import { unregisterBlockStyle, registerBlockStyle } from '@wordpress/blocks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';

registerBlockStyle( 'core/button', {
	label: 'Arrow Button',
	name: 'with-arrow',
} );

function addCustomAttributes( settings, name ) {
	if ( name === 'core/button' ) {
		if ( settings.attributes ) {
			settings.attributes.colorVariation = { type: 'string', default: '' };
			settings.attributes.sizeVariation = { type: 'string', default: '' };
		}
	}
	return settings;
}

wp.hooks.addFilter(
	'blocks.registerBlockType',
	'custom/button-attributes',
	addCustomAttributes
);

const withCoreButtonControls = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		const { attributes, setAttributes, name } = props;

		if ( name === 'core/button' ) {
			unregisterBlockStyle( 'core/button', 'fill' );

			return (
				<>
					<BlockEdit { ...props } />
					<InspectorControls>
						<PanelBody title="Custom Button Settings" initialOpen={ true }>
							<SelectControl
								label="Color Variation"
								value={ attributes.colorVariation }
								options={ [
									{ label: 'Default', value: '' },
									{ label: 'Brown', value: 'brown' },
								] }
								onChange={ ( value ) => setAttributes( { colorVariation: value } ) }
							/>
							<SelectControl
								label="Size Variation"
								value={ attributes.sizeVariation }
								options={ [
									{ label: 'Default', value: '' },
									{ label: 'Large', value: 'large' },
								] }
								onChange={ ( value ) => setAttributes( { sizeVariation: value } ) }
							/>
						</PanelBody>
					</InspectorControls>
				</>
			);
		}

		return <BlockEdit { ...props } />;
	};
}, 'withCoreButtonControls' );

wp.hooks.addFilter(
	'editor.BlockEdit',
	'custom/button-enhancements',
	withCoreButtonControls
);

function applyExtraClass( extraProps, blockType, attributes ) {
	if ( blockType.name === 'core/button' ) {
		if ( attributes.colorVariation ) {
			extraProps.className = [ extraProps.className, `is-color-${ attributes.colorVariation }` ]
				.filter( Boolean )
				.join( ' ' );
		}
		if ( attributes.sizeVariation ) {
			extraProps.className = [ extraProps.className, `is-size-${ attributes.sizeVariation }` ]
				.filter( Boolean )
				.join( ' ' );
		}
	}
	return extraProps;
}

wp.hooks.addFilter(
	'blocks.getSaveContent.extraProps',
	'custom/add-variation-classes',
	applyExtraClass
);
