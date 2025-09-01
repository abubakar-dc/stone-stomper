/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
import { registerBlockType, registerBlockVariation } from '@wordpress/blocks';
/**
 * Internal dependencies
 */
import metadata from './block.json';
import ContainerBlock, { ContainerBlockContent, customAttributes } from '../block-assets/components/ContainerBlock.jsx';
import icons from '../block-assets/icons/Icons.jsx';
import DesignPreview from '../block-assets/components/DesignPreview.jsx';
// import previewImage1 from '../block-assets/preview-images/preview-page-hero-v1.webp';
// import previewImage2 from '../block-assets/preview-images/preview-page-hero-v2.webp';
/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType( metadata.name, {
	/**
	 * @see ./edit.js
	 */
	attributes: {
		...metadata.attributes,
		...customAttributes,
	},
	icon: icons.pageHero,
	edit: Edit,
	save: Save,

	/**
	 * @see ./save.js
	 */
	save,
} );
function Edit( props ) {
	const { attributes, setAttributes } = props;

	const { preview, className, selected } = attributes;
	let myCustomClassName = '';
	if ( 'design-1' === selected ) {
		myCustomClassName = 'page-banner';
	} else {
		myCustomClassName = 'simple-page-banner';
	}

	const classes = [ myCustomClassName ].join( '' );
	const blockProps = useBlockProps( {} );
	if ( preview ) {
		return (
			<div className="block-preview">
				<img src={ previewImage } alt="Preview" />
			</div>
		);
	}
	if ( 'design-1' === selected ) {
		return (
			<>
				<BlockVariation
					blockName={ metadata.name }
					selected={ selected }
					onSelect={ ( nextVariation ) => {
						setAttributes( { selected: nextVariation.name } );
					} }
				></BlockVariation>
				<div { ...blockProps }>
					<ContainerBlock props={ props } customClass={ classes }>
						<div className="hero-default">
							<InnerBlocks
								allowedBlocks={ [ 'core/columns' ] }
								template={ [
									[ 'core/image', {} ],
									[ 'duxtonwaterpack/hero-inner' ],
								] }
							/>
						</div>
					</ContainerBlock>
				</div>
			</>
		);
	} else if ( 'design-2' === selected ) {
		return (
			<>
				<BlockVariation
					blockName={ metadata.name }
					selected={ attributes.selected }
					onSelect={ ( nextVariation ) => {
						setAttributes( { selected: nextVariation.name } );
					} }
				></BlockVariation>
				<div { ...blockProps }>
					<ContainerBlock props={ props } customClass={ classes }>
						<div className="hero-simple-image">
							<InnerBlocks
								allowedBlocks={ [ 'core/columns' ] }
								template={ [ [ 'core/image', {} ] ] }
							/>
						</div>
					</ContainerBlock>
				</div>
			</>
		);
	}

	return (
		<>
			<BlockVariation
				blockName={ metadata.name }
				selected={ selected }
				onSelect={ ( nextVariation ) => {
					setAttributes( { selected: nextVariation.name } );
				} }
			></BlockVariation>
		</>
	);
}

function Save( props ) {
	const { attributes } = props;
	const { className, selected } = attributes;
	let myCustomClassName = '';
	let myCustomDesignClass = '';
	if ( 'design-1' === selected ) {
		myCustomClassName = 'page-banner';
	} else {
		myCustomClassName = 'simple-page-banner';
	}
	if ( 'design-1' === selected ) {
		myCustomDesignClass = 'page-banner';
	} else {
		myCustomDesignClass = 'simple-page-banner';
	}

	const classes = [ myCustomClassName ].join( ' ' );

	return (
		<>
			<ContainerBlockContent props={ props } customClass={ classes }>
				<div className={ `${ myCustomDesignClass }` }>
					<InnerBlocks.Content />
				</div>
			</ContainerBlockContent>
		</>
	);
}

registerBlockVariation( metadata.name, {
	name: 'design-1',
	title: 'Design 1',
	description: '',
	icon: DesignPreview( previewImage2, 300, 200 ),
	scope: 'block',
} );

registerBlockVariation( metadata.name, {
	name: 'design-2',
	title: 'Design 2',
	description: '',
	icon: DesignPreview( previewImage1, 300, 200 ),
	scope: 'block',
} );
