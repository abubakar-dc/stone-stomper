
import { Button, Icon } from '@wordpress/components';
import { trash } from '@wordpress/icons';
import { Image } from './PlaceholderImage.jsx';
import apiFetch from '@wordpress/api-fetch';
import {
	MediaUpload,
	MediaUploadCheck,
} from '@wordpress/block-editor';
import './global.scss';
const PlaceholderSlidingImages = ( { props, valueImage, attrImage } ) => {
	const {
		setAttributes,
	} = props;
	return (
		<>

			<MediaUploadCheck>
				<MediaUpload
					onSelect={ ( media ) => {
						// console.log( media );
						const newMedia = [];
						media.map( ( item ) => {
							apiFetch( { path: 'wp/v2/media/' + item.id } ).then( ( data ) => {
								newMedia.push( data );
							} );
						} );
						setAttributes( { [ attrImage ]: newMedia } );
					} }
					allowedTypes={ [ 'image' ] }
					multiple={ true }
					gallery={ true }
					addToGallery={ true }
					value={ valueImage.length > 0 ? valueImage.map( ( item ) => item.id ) : false }
					render={ ( { open } ) => {
						if ( valueImage.length > 0 ) {
							return (
								<>
									<div className="image-box" onClick={ open }>
										<Images value={ valueImage } thumb="thumb_600" />
										<Button type="button" className="components-button is-primary" onClick={ open }>Change Images</Button>
										<Button type="button" className="components-button is-link is-destructive" onClick={ () => {
											setAttributes( {
												[ attrImage ]: [],
											} );
										} }><Icon icon={ trash } /></Button>
									</div>
									<br />
								</>
							);
						}
						return (
							<div className="image-box">
								<div className="image-sliding-placeholders" onClick={ open }>
									<div className="column-33">

										<div className="dc-placeholder-slider">
											<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60" preserveAspectRatio="none" className="components-placeholder__illustration" aria-hidden="true" focusable="false"><path vectorEffect="non-scaling-stroke" d="M60 60 0 0"></path></svg>
										</div>
										<div className="dc-placeholder-slider">
											<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60" preserveAspectRatio="none" className="components-placeholder__illustration" aria-hidden="true" focusable="false"><path vectorEffect="non-scaling-stroke" d="M60 60 0 0"></path></svg>
										</div>
										<div className="dc-placeholder-slider">
											<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60" preserveAspectRatio="none" className="components-placeholder__illustration" aria-hidden="true" focusable="false"><path vectorEffect="non-scaling-stroke" d="M60 60 0 0"></path></svg>
										</div>
									</div>
									<div className="column-33">

										<div className="dc-placeholder-slider">
											<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60" preserveAspectRatio="none" className="components-placeholder__illustration" aria-hidden="true" focusable="false"><path vectorEffect="non-scaling-stroke" d="M60 60 0 0"></path></svg>
										</div>
										<div className="dc-placeholder-slider">
											<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60" preserveAspectRatio="none" className="components-placeholder__illustration" aria-hidden="true" focusable="false"><path vectorEffect="non-scaling-stroke" d="M60 60 0 0"></path></svg>
										</div>
										<div className="dc-placeholder-slider">
											<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60" preserveAspectRatio="none" className="components-placeholder__illustration" aria-hidden="true" focusable="false"><path vectorEffect="non-scaling-stroke" d="M60 60 0 0"></path></svg>
										</div>
									</div>
									<div className="column-33">

										<div className="dc-placeholder-slider">
											<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60" preserveAspectRatio="none" className="components-placeholder__illustration" aria-hidden="true" focusable="false"><path vectorEffect="non-scaling-stroke" d="M60 60 0 0"></path></svg>
										</div>
										<div className="dc-placeholder-slider">
											<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60" preserveAspectRatio="none" className="components-placeholder__illustration" aria-hidden="true" focusable="false"><path vectorEffect="non-scaling-stroke" d="M60 60 0 0"></path></svg>
										</div>
										<div className="dc-placeholder-slider">
											<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60" preserveAspectRatio="none" className="components-placeholder__illustration" aria-hidden="true" focusable="false"><path vectorEffect="non-scaling-stroke" d="M60 60 0 0"></path></svg>
										</div>
									</div>

								</div>
								<Button type="button" className="is-primary" onClick={ open }>Add Images</Button>
							</div>
						);
					}
					}

				/>
			</MediaUploadCheck>

		</>
	);
};
const Images = ( { value, thumb } ) => {
	const imagesChunks = splitToNChunks( [ ...value ], 3 );
	const imagesOutput = imagesChunks.map( ( val, index ) => {
		const innerOutput = val.map( ( v, i ) => {
			return (
				<Image value={ v } thumb={ thumb } key={ i } />
			);
		} );
		return (
			<div className="column-33" key={ index }>
				{ innerOutput }
			</div>
		);
	} );
	return ( <>{ imagesOutput }</> );
};
function splitToNChunks( array, n ) {
	const result = [];
	for ( let i = n; i > 0; i-- ) {
		result.push( array.splice( 0, Math.ceil( array.length / i ) ) );
	}
	return result;
}

export default PlaceholderSlidingImages;
export { Images };
