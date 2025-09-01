
import { Button, Icon } from '@wordpress/components';
import { trash } from '@wordpress/icons';

import {

	MediaUpload,
	MediaUploadCheck,
} from '@wordpress/block-editor';
import './global.scss';
const PlaceholderSlider = ( { props, valueImage, attrImage, className, ParentClassName } ) => {
	const {
		setAttributes,
	} = props;
	let innerImages;
	if ( valueImage.length > 0 ) {
		innerImages = valueImage.map( ( el, i ) => {
			const classNameNew = className + ' image-box';
			return (
				<div className={ classNameNew } key={ i }>
					<img src={ el.url } alt="" className="home-page-image" />
				</div>

			);
		} );
	}
	return (
		<>

			<MediaUploadCheck>
				<MediaUpload
					onSelect={ ( media ) =>
						setAttributes( { [ attrImage ]: media } )
					}
					allowedTypes={ [ 'image' ] }
					multiple={ true }
					gallery={ true }
					addToGallery={ true }
					value={ valueImage.length > 0 ? valueImage.map( ( item ) => item.id ) : false }
					render={ ( { open } ) => {
						if ( valueImage.length > 0 ) {
							return (
								<>
									<div className={ ParentClassName }>
										{ innerImages }
									</div>
									<div className="fixed-controls">
										<Button type="button" className="is-primary" onClick={ open }>Change Images</Button>
										<Button type="button" className="is-link is-destructive" onClick={ () => {
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
								<div className="image-slider-placeholders" onClick={ open }>
									<div className="dc-placeholder-slider placeholder-left">
										<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60" preserveAspectRatio="none" className="components-placeholder__illustration" aria-hidden="true" focusable="false"><path vectorEffect="non-scaling-stroke" d="M60 60 0 0"></path></svg>
									</div>
									<div className="dc-placeholder-slider placeholder-center">
										<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60" preserveAspectRatio="none" className="components-placeholder__illustration" aria-hidden="true" focusable="false"><path vectorEffect="non-scaling-stroke" d="M60 60 0 0"></path></svg>
									</div>
									<div className="dc-placeholder-slider placeholder-right">
										<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60" preserveAspectRatio="none" className="components-placeholder__illustration" aria-hidden="true" focusable="false"><path vectorEffect="non-scaling-stroke" d="M60 60 0 0"></path></svg>
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

export default PlaceholderSlider;

