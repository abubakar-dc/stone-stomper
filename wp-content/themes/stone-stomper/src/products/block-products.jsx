import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import {
	useBlockProps,
	RichText,
	InspectorControls,
} from '@wordpress/block-editor';
import {
	Panel,
	PanelBody,
	PanelRow,
	ButtonGroup,
	Button,
	TextControl,
} from '@wordpress/components';

import ButtonComponent from '../block-assets/components/ButtonComponent.jsx';

import ServerSideRender from '@wordpress/server-side-render';
import apiFetch from '@wordpress/api-fetch';
import { useState, useEffect } from '@wordpress/element';
import Select from 'react-select';
import icons from '../block-assets/icons/Icons.jsx';
// import previewImage from '../block-assets/preview-images/elevate-teaser.webp';

// Register block
registerBlockType( metadata.name, {
	icon: icons.products,

	edit( props ) {
		const { attributes, setAttributes } = props;
		const {
			preview,
			className,
			selectionMode = 'recent',
			productIds,
		} = attributes;

		const productOptions = [];
		apiFetch( { path: '/wp/v2/product?per_page=50' } ).then( ( posts ) => {
			for ( const key in posts ) {
				const element = posts[ key ];

				productOptions.push( {
					value: element.id,
					label: element.title.rendered,
				} );
			}
		} );

		if ( preview ) {
			return (
				<div className="block-preview">
					<img
						src={ previewImage }
						alt="Preview"
						style={ {
							width: '100%',
							height: '100%',
							objectFit: 'cover',
						} }
					/>
				</div>
			);
		}
		const blockProps = useBlockProps();

		return (
			<>
				<div { ...blockProps }>
					<InspectorControls>
						<Panel>
							<PanelBody
								title={ __( 'Settings' ) }
								initialOpen={ true }
							>
								<PanelRow>
									<ButtonGroup>
										<Button
											isPrimary={ selectionMode === 'recent' }
											isSecondary={ selectionMode !== 'recent' }
											onClick={ () => setAttributes( { selectionMode: 'recent' } ) }
										>
											Recent
										</Button>
										<Button
											isPrimary={ selectionMode === 'manual' }
											isSecondary={ selectionMode !== 'manual' }
											onClick={ () => setAttributes( { selectionMode: 'manual' } ) }
										>
											Manual
										</Button>

									</ButtonGroup>
								</PanelRow>
								<div className="dc-s20"></div>
								{ selectionMode === 'manual' && (
									<PanelRow>
										<div className="dp-s30"></div>
										<Select
											className="select-post-type"
											value={ attributes.productIds }
											onChange={ ( value ) =>
												setAttributes( {
													productIds: value,
												} )
											}
											options={ productOptions }
											isMulti="true"
										/>
									</PanelRow>
								) }
							</PanelBody>
						</Panel>
					</InspectorControls>

					<div className="post-selection-message">
						<div className="components-notice-list components-editor-notices__dismissible" style={ { marginBottom: '40px', border: '0px solid' } }>
							<div className="components-notice is-warning is-dismissible">
								<div className="components-notice__content" style={ { marginTop: '15px' } }>
									This is a dynamic block automatically displays Testimonials. To edit
									events content <a href="/wp-admin/edit.php?post_type=testimonial" target="_blank" rel="noopener noreferrer"> Click Here </a>
								</div>
							</div>
						</div>
					</div>
					<ServerSideRender
						block={ metadata.name }
						attributes={ attributes }
					/>
					<div className="slider-buttons flex">
						<div className="slider-button flex-center blog-button--prev" aria-label="Previous Slide">
							<svg className="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="m15 19-7-7 7-7" />
							</svg>
						</div>
						<div className="slider-button flex-center blog-button--next" aria-label="Next Slide">
							<svg className="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="m9 5 7 7-7 7" />
							</svg>
						</div>
					</div>
					<div className="post-selection-message">
						<div className="components-notice-list components-editor-notices__dismissible" style={ { margintop: '40px' } }>
							<div className="components-notice is-warning is-dismissible">
								<div className="components-notice__content" style={ { marginTop: '15px' } }>
									Scroll right and left to see selected posts
								</div>
							</div>
						</div>
					</div>
				</div>
			</>
		);
	},
} );
