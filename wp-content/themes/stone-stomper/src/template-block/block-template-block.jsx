
import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import icons from '../block-assets/icons/Icons.jsx';
// import previewImage from '../block-assets/preview-images/section-head.webp';
import { InnerBlocks, useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';
import ContainerBlock, { ContainerBlockContent, customAttributes } from '../block-assets/components/ContainerBlock.jsx';

customAttributes.bgWidth.default = 'ctn-690';
customAttributes.bgDesignType.default = 'ctn-cream';

registerBlockType( metadata.name, {
	attributes: {
		...metadata.attributes,
		...customAttributes,
	},
	icon: icons.sectionHead,
	edit: Edit,
	save: Save,
} );

function Edit( props ) {
	const { attributes, setAttributes } = props;
	const { preview, className } = attributes;
	const myCustomClassName = className ? className : undefined;
	const classes = [ myCustomClassName ].join( ' ' );
	const allowedBlocks = [
		'core/heading',
		'core/group',
		'core/buttons',
		'core/paragraph',
	];

	const TEMPLATE = [
		[
			'core/group', { className: 'section-head' }, [
				[ 'core/heading', { level: 2, className: '', placeholder: 'Heading…', textAlign: 'center' } ],
				[ 'core/paragraph', { placeholder: 'Paragraph...', align: 'center' } ],
				[ 'core/buttons', { className: 'is-layout-flex', layout: { type: 'flex', justifyContent: 'center' } }, [
					[ 'core/button', { className: 'is-style-with-arrow', sizeVariation: 'large', colorVariation: 'brown', placeholder: 'Button' } ],
				] ],
			],
		],
	];

	const blockProps = useBlockProps();

	const innerBlocksProps = useInnerBlocksProps( blockProps, {
		template: TEMPLATE,
		templateLock: false, // set 'all' if you want to lock structure
		allowedBlocks,
	} );

	// Block preview
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

	return (

		<ContainerBlock props={ props } customClassName={ classes }>
			<div { ...innerBlocksProps } />
		</ContainerBlock>

	);
}

function Save( props ) {
	const { attributes } = props;
	const { className, title, blocktext } = attributes;
	const myCustomClassName = className ? className : '';
	const classes = [ myCustomClassName ].join( ' ' );

	return (
		<ContainerBlockContent props={ props } customClassName={ classes }>
			<InnerBlocks.Content />
		</ContainerBlockContent>
	);
}

