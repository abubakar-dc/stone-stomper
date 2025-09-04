
import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import icons from '../block-assets/icons/Icons.jsx';
import previewImage from '../block-assets/preview-images/default-preview-image.webp';
import { InnerBlocks, useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';
import ContainerBlock, { ContainerBlockContent, customAttributes } from '../block-assets/components/ContainerBlock.jsx';

customAttributes.bgWidth.default = 'ctn-full-width';

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
	];

	const TEMPLATE = [
		[ 'core/cover', { className: '', contentPosition: 'center left', dimRatio: 30, isUserOverlayColor: true, gradient: 'bottom-darker' }, [
			[ 'core/group', { className: 'hero-content' }, [
				[ 'core/paragraph', { className: 'kicker-text', content: '', placeholder: 'Kicker Text...' } ],
				[ 'core/heading', { level: 1 } ],
				[ 'core/paragraph', { className: '', content: '', placeholder: 'Hero Content...' } ],
				[ 'core/buttons', {}, [
					[ 'core/button', { text: '', className: '' } ],
				] ],
			] ],
		] ],
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
		<div { ...blockProps }>

			<ContainerBlock props={ props } className={ classes }>
				<div className="hero-home hero-home-section">
					<div className="hero-inner">
						<div { ...innerBlocksProps } />
					</div>
				</div>
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
		<ContainerBlockContent props={ props } className={ classes }>
			<div className="hero-home hero-home-section">
				<div className="hero-inner">
					<InnerBlocks.Content />
				</div>
			</div>
		</ContainerBlockContent>
	);
}

