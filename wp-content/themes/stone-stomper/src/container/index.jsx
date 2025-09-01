import { registerBlockType } from '@wordpress/blocks';
import './style.scss';
import './editor.scss';
import metadata from './block.json';
import icons from '../block-assets/icons/Icons.jsx';
import previewImage from '../block-assets/preview-images/default-preview-image.webp';
import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import ContainerBlock, { ContainerBlockContent, customAttributes } from '../block-assets/components/ContainerBlock.jsx';

import '../block-assets/default-blocks/buttons.jsx';
import '../block-assets/default-blocks/spacers.jsx';
registerBlockType( metadata.name, {
	/**
	 * @see ./edit.js
	 */
	attributes: {
		...metadata.attributes,
		...customAttributes,
	},
	icon: icons.container,
	edit: Edit,

	/**
	 * @see ./save.js
	 */
	save: Save,
} );
function Edit( props ) {
	const {
		attributes,
		setAttributes,
	} = props;
	const { preview, className } = attributes;
	const myCustomClassName = className ? className : undefined;
	const classes = [ myCustomClassName ].join( ' ' );
	const blockProps = useBlockProps();
	// block preview
	// if ( preview ) {
	// 	return (
	// 		<div className="block-preview">
	// 			<img src={ previewImage } alt="Preview" />
	// 		</div>
	// 	);
	// }
	return (

		<div { ...blockProps }>
			<ContainerBlock props={ props } customClass={ classes }>
				<InnerBlocks />
			</ContainerBlock>
		</div>
	);
}
function Save( props ) {
	const {
		attributes,
	} = props;
	const { className } = attributes;
	const myCustomClassName = className ? className : '';
	const classes = [ myCustomClassName ].join( ' ' );
	return (
		<>
			<ContainerBlockContent props={ props } customClass={ classes }>
				<InnerBlocks.Content />
			</ContainerBlockContent>
		</>
	);
}
