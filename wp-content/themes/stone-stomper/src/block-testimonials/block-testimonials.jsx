import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import icons from '../block-assets/icons/Icons.jsx';
import previewImage from '../block-assets/preview-images/default-preview-image.webp';
import { InnerBlocks, useBlockProps, useInnerBlocksProps, InspectorControls } from '@wordpress/block-editor';
import ContainerBlock, { ContainerBlockContent, customAttributes } from '../block-assets/components/ContainerBlock.jsx';

registerBlockType( metadata.name, {
	/**
	 * @see ./edit.js
	 */
	attributes: {
		...metadata.attributes,
		...customAttributes,
	},
	icon: icons.testimonials,
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
	if ( preview ) {
		return (
			<div className="block-preview">
				<img src={ previewImage } alt="Preview"
					style={ {
						width: '100%',
						height: '100%',
						objectFit: 'cover',
					} }
				/>
			</div>
		);
	}

	const { children } = useInnerBlocksProps( blockProps, {
		allowedBlocks: [ 'core/columns', 'core/paragraph', 'core/spacer', 'core/heading', 'core/list', 'core/buttons' ],
		template: [
			[ 'core/group', { className: 'section-head center-align' }, [
				[ 'core/paragraph', { content: '', className: 'kicker-text' } ],
				[ 'core/heading', { level: 2, content: '', className: '' } ],
			] ],
			[ 'stonestomperpack/testimonials', {} ],
		],
	} );

	return (
		<>
			<ContainerBlock props={ props } customClass={ classes }>
				<div className="testimonial-section">
					{ children }
				</div>
			</ContainerBlock>
		</>
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
			<ContainerBlockContent props={ props } customClass={ `${ classes }` }>
				<div className="testimonial-section">
					<InnerBlocks.Content />
				</div>
			</ContainerBlockContent>
		</>
	);
}
