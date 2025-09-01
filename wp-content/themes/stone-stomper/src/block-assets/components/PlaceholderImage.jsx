
import { Button, Icon } from '@wordpress/components';
import { plus, trash } from '@wordpress/icons';
import {
	MediaUpload,
	MediaUploadCheck,
} from '@wordpress/block-editor';
import apiFetch from '@wordpress/api-fetch';
const PlaceholderImage = ( { props, valueImage, attrImage, style = 'default' } ) => {

	const {
		setAttributes,
	} = props;
	return (
		<>
			<div className="image-box">
				<MediaUploadCheck>
					<MediaUpload
						onSelect={ ( media ) => {
							if (media && media.id) {
								apiFetch({ path: `wp/v2/media/${media.id}` })
									.then((data) => {
										setAttributes({ [attrImage]: data });
									})
									.catch(() => {
										setAttributes({ [attrImage]: {} });
									});
							} else {
								setAttributes({ [attrImage]: {} });
							}
						}}
						allowedTypes={ [ 'image' ] }
						multiple={ false }
						gallery={ false }
						addToGallery={ false }
						value={ valueImage?.id || '' }
						render={ ( { open } ) => {
							console.log(valueImage);

								if ( valueImage && valueImage.source_url ) {


								return (
									<>

										{
											( style === 'default' ) ? <>
												<img src={ valueImage.source_url } alt="" className="image" />
												<Button type="button" className="is-primary" onClick={ open }> Replace Image</Button>
												<Button type="button" className="is-link is-destructive" onClick={ () => {
													setAttributes( {
														[ attrImage ]: {},
													} );
												} }><Icon icon={ trash } /></Button>
											</> : <>
												<img src={ valueImage.source_url } alt="" className="image" onClick={ () => {
													setAttributes( {
														[ attrImage ]: {},
													} );
												} } />
											</>
										}

									</>
								);
							}
							return (
								<>
									{
										( style === 'default' ) ? <>
											<div className="dc-placeholder-image" onClick={ open }>
												<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60" preserveAspectRatio="none" className="components-placeholder__illustration" aria-hidden="true" focusable="false"><path vectorEffect="non-scaling-stroke" d="M60 60 0 0"></path></svg>
											</div>
											<Button type="button" className="is-primary" onClick={ open }>Add Image</Button>
										</> : <div className="dc-placeholder-icon" onClick={ open }>
											<Icon icon={ plus } />
										</div>
									}

								</>
							);
						}
						}

					/>
				</MediaUploadCheck>

			</div>

		</>
	);


};
const Image = ( { value, thumb } ) => {
	// console.log( value.media_details );
	if ( value.media_details ) {
		const image = value.media_details.sizes[ thumb ] ?? null;
		// console.log( image );
		if ( image ) {
			return (
				<>
					{ image.source_url ? <img src={ image.source_url } alt={ value.alt_text } width={ image.width } height={ image.height } title={ value.title.rendered } /> : <></> }
				</>
			);
		} else if ( value.source_url ) {
			return (
				<>
					{ value.source_url ? <img src={ value.source_url } alt={ value.alt_text } width={ value.width } height={ value.height } title={ value.title.rendered } /> : <></> }
				</>
			);
		}
		return ( <></> );
	}
	return ( <></> );
};
export default PlaceholderImage;
export { Image };

