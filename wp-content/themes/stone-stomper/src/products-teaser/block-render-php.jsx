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
			elevateIds,
			stayIds,
		} = attributes;

		const elevateOptions = [];
		apiFetch( { path: '/wp/v2/post?per_page=50' } ).then( ( posts ) => {
			for ( const key in posts ) {
				const element = posts[ key ];

				elevateOptions.push( {
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
								title={ __( 'Elevates Settings' ) }
								initialOpen={ true }
							>

								<div className="dc-s20"></div>
								<PanelRow>
									<div className="dp-s30"></div>
									<Select
										className="select-post-type"
										value={ attributes.elevateIds }
										onChange={ ( value ) =>
											setAttributes( {
												elevateIds: value,
											} )
										}
										options={ elevateOptions }
										isMulti="true"
									/>
								</PanelRow>

							</PanelBody>
						</Panel>
					</InspectorControls>

					<div className="section-head">
						<div className="section-head-left">
							<RichText
								tagName="h2"
								value={ attributes.heading }
								onChange={ ( value ) => setAttributes( { heading: value } ) }
								placeholder="Heading"
								className="section-heading"
							/>
							<RichText
								tagName="p"
								value={ attributes.description }
								onChange={ ( value ) => setAttributes( { description: value } ) }
								placeholder="Description"
								className="section-description"
							/>
						</div>

						<div className="section-head-right">
							<ButtonComponent
								props={ props }
								value={ attributes.linkData }
								attr="linkData"
							/>
						</div>
					</div>
					<ServerSideRender
						block={ metadata.name }
						attributes={ attributes }
					/>
				</div>
			</>
		);
	},
} );
