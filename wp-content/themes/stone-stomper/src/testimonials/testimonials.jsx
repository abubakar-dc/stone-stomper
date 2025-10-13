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
	icon: icons.elevateTeaser,

	edit( props ) {
		const { attributes, setAttributes } = props;
		const {
			preview,
			className,
			selectionMode = 'recent',
			testimonialIds,
			stayIds,
		} = attributes;

		const testimonialOptions = [];
		apiFetch( { path: '/wp/v2/testimonial?per_page=9' } ).then( ( posts ) => {
			for ( const key in posts ) {
				const element = posts[ key ];

				testimonialOptions.push( {
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

		return (
			<>
				<div>
					<InspectorControls>
						<Panel>
							<PanelBody
								title={ __( 'Testimonials Settings' ) }
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
											value={ attributes.testimonialIds }
											onChange={ ( value ) =>
												setAttributes( {
													testimonialIds: value,
												} )
											}
											options={ testimonialOptions }
											isMulti="true"
										/>
									</PanelRow>
								) }
							</PanelBody>
						</Panel>
					</InspectorControls>

					<ServerSideRender
						block={ metadata.name }
						attributes={ attributes }
					/>
				</div>
			</>
		);
	},
} );
