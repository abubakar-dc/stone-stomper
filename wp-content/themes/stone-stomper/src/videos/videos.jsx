import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import icons from '../block-assets/icons/Icons.jsx';
import previewImage from '../block-assets/preview-images/default-preview-image.webp';
import { InnerBlocks, useBlockProps, useInnerBlocksProps, InspectorControls } from '@wordpress/block-editor';
import ContainerBlock, { ContainerBlockContent, customAttributes } from '../block-assets/components/ContainerBlock.jsx';
import PlaceholderVideo from '../block-assets/components/PlaceholderVideo.jsx';
import { Panel, PanelBody, PanelRow, ButtonGroup, Button, TextControl} from '@wordpress/components';
import PlaceholderImage from '../block-assets/components/PlaceholderImage.jsx';

customAttributes.bgWidth.default = 'ctn-1200';
customAttributes.bgDesignType.default = 'ctn-dark-gray';

registerBlockType( metadata.name, {
	/**
	 * @see ./edit.js
	 */
	attributes: {
		...metadata.attributes,
		...customAttributes,
		video: {
			type: 'object',
			default: {},
		},
		videoInline: {
			type: 'object',
			default: {},
		},
		videoUrl: {
			type: 'string',
			default: '',
		},
		title: {
			type: 'string',
			default: '',
		},
		selectionMode: {
			type: 'string',
			default: 'url',
		},
		image: { type: 'object', default: {} },

	},
	icon: icons.imageWithText,
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
	const { preview, className, title, selectionMode } = attributes;
	const myCustomClassName = className ? className : undefined;
	const classes = [ myCustomClassName ].join( ' ' );
	const blockProps = useBlockProps();

	// block preview
	if ( preview ) {
		return (
			<div className="block-preview">
				<img src={ previewImage } alt="Preview"
				 style={{
                        width: '100%',
                        height: '100%',
                        objectFit: 'cover'
                    }}
				/>
			</div>
		);
	}

	const { children } = useInnerBlocksProps( blockProps, {
		allowedBlocks: [ 'core/columns', 'core/paragraph', 'core/spacer', 'core/heading', 'core/list', 'core/buttons' ],
		template: [
			[ metadata.name + '-item' ],
			[ metadata.name + '-item' ],
			[ metadata.name + '-item' ],
			[ metadata.name + '-item' ],
		],
	} );

	return (
		<div { ...blockProps }>
			<div className="video-grid video-three-columns">
				{ children }
			</div>
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
			<div className="video-grid video-three-columns">
				<InnerBlocks.Content />
			</div>
		</>
	);
}


registerBlockType(metadata.name + '-item', {
    apiVersion: 3,
    title: 'Video Tile',
    parent: [metadata.name],
    category: 'theme-blocks',
    icon: icons.item,
    attributes: {
		imageUrl: { type: "string" },
		imageAlt: { type: "string", default: "" },
		videoUrl: { type: "string" },
		description: { type: "string" },
		image: { type: 'object', default: {} },
		video: {
			type: 'object',
			default: {},
		},
		videoInline: {
			type: 'object',
			default: {},
		},
		title: {
			type: 'string',
			default: '',
		},
			selectionMode: {
			type: 'string',
			default: 'url',
		},
  	},

    edit(props) {
        const { attributes, setAttributes } = props;
        const { title,selectionMode } = attributes;

		const blockProps = useBlockProps();

		const { children } = useInnerBlocksProps( blockProps, {
			allowedBlocks: [ 'core/columns', 'core/paragraph', 'core/spacer', 'core/heading', 'core/list', 'core/buttons' ],
			template: [
				[ 'core/heading', { level: 3, content: '', className:'' } ],
			],
		} );

        return (
			<>
			<div { ...blockProps }>

				<div className="video-item">
					<InspectorControls>
						<Panel>
							<PanelBody title="Video Settings" initialOpen={ true }>
								<PanelRow>
									<ButtonGroup>
										<Button
											isPrimary={ selectionMode === 'url' }
											isSecondary={ selectionMode !== 'url' }
											onClick={ () => setAttributes( { selectionMode: 'url' } ) }
										>
											Video Url
										</Button>
										<Button
											isPrimary={ selectionMode === 'upload' }
											isSecondary={ selectionMode !== 'upload' }
											onClick={ () => setAttributes( { selectionMode: 'upload' } ) }
										>
											Upload
										</Button>

									</ButtonGroup>
								</PanelRow>
								{ selectionMode === 'url' && (
									<PanelRow>
										<TextControl
											label="Video URL(Youtube/Vimeo)"
											value={ title }
											onChange={(value) => setAttributes({ title: value })}
										/>
									</PanelRow>
								) }

								{ selectionMode === 'upload' && (
									<PanelRow>
										<PlaceholderVideo
											props={ props }
											valueVideoInline={ attributes.videoInline }
											attrVideoInline="videoInline"
											valueVideo={ attributes.video }
											attrVideo="video"
										/>
									</PanelRow>

								) }
							</PanelBody>
						</Panel>
					</InspectorControls>

					<div className="video-section image-cover">
						{ selectionMode === 'url' || selectionMode === 'upload' && (
							<div className="play-icon">
								<a href="#" data-lity className="play-btn-icon">
								</a>
							</div>
						) }
						<PlaceholderImage props={props} valueImage={attributes.image} attrImage="image" />
					</div>
					<div className="video-description">
						{ children }
					</div>
				</div>
			</div>

			</>
        );
    },

    save({ attributes }) {
		const { className, video, videoUrl, title, videoInline, image, selectionMode } = attributes;
		const myCustomClassName = className ? className : '';
		const classes = [ myCustomClassName ].join( ' ' );

        return (
			<>
				<div className="video-item">
					<div className="video-section image-cover">
						{image?.source_url && (
							<img src={image.source_url} alt={image.alt || 'Stat Background'} />
						)}
							{ selectionMode === 'url' && title &&  (
							<div className="play-icon">
								<a href={title} data-lity="true" className="play-btn-icon"></a>
							</div>
							) }
							{ selectionMode === 'upload' && videoInline?.url && (
							<div className="play-icon">
								<a href={videoInline.url.replace(/^http:/, 'https:')} data-lity="true" className="play-btn-icon"></a>
							</div>
							) }
					</div>
					<div className="video-description">
						<InnerBlocks.Content />
					</div>
				</div>
			</>
        );
    },
});
