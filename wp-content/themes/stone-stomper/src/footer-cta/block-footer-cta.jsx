
import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import icons from '../block-assets/icons/Icons.jsx';
import previewImage from '../block-assets/preview-images/default-preview-image.webp';
import { InnerBlocks, useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';
import ContainerBlock, { ContainerBlockContent, customAttributes } from '../block-assets/components/ContainerBlock.jsx';

customAttributes.bgWidth.default = '';
customAttributes.bgDesignType.default = '';

registerBlockType( metadata.name, {
	attributes: {
		...metadata.attributes,
		...customAttributes,
	},
	icon: icons.themeStats,
	edit: Edit,
	save: Save,
} );

function Edit( props ) {
	const { attributes, setAttributes } = props;
	const { preview, className } = attributes;
	const myCustomClassName = className ? className : undefined;
	const classes = [ myCustomClassName ].join( ' ' );
	const allowedBlocks = [
		'core/columns',
		'core/column',
		'core/image',
		'core/heading',
		'core/group',
		'core/button',
		'core/spacer',
	];

	const TEMPLATE = [
		[
			'core/columns', { className: 'footer-cta-section' }, [
				[ 'core/column', { className: 'cta-image image-cover', style: { flexBasis: '65%' }, width:'65%', verticalAlignment: 'center' }, [
						[ 'core/image', { className: 'size-full' }],
					],
				],
				[ 'core/column', { className: 'cta-content', style: { flexBasis: '35%' }, width:'35%', verticalAlignment: 'stretch', backgroundColor: 'black-100', textColor: 'white' }, [
						[
							'core/heading',
							{ placeholder: 'Enter heading…', textAlign:'center', className: '', level: 2 },
						],
						[ 'core/buttons', { className: 'is-layout-flex', layout: { type: 'flex', justifyContent: 'center' }}, [
								[
									'core/button',
									{ className: '', text: '' },
								],
							],
						],
					],
				],

			],
		],
	];

	const blockProps = useBlockProps();

	const { children, ...innerBlocksProps } = useInnerBlocksProps( blockProps, {
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

		<div { ...blockProps }>
			<ContainerBlock props={ props } customClassName={ classes }>
				{ children }
			</ContainerBlock>
		</div>

	);
}

function Save( props ) {
	const { attributes } = props;
	const { className } = attributes;
	const myCustomClassName = className ? className : '';
	const classes = [ myCustomClassName ].join( ' ' );

	return (
		<ContainerBlockContent props={ props } customClassName={ classes }>
			<InnerBlocks.Content />
		</ContainerBlockContent>
	);
}

