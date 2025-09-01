import { MediaUpload } from '@wordpress/block-editor';
import { Button } from '@wordpress/components';
export default function Media( { props, help, valueImage, attrImage } ) {
	const {
		setAttributes,
	} = props;
	return (
		<MediaUpload
			onSelect={ ( media ) => {
				setAttributes( {
					[ attrImage ]: {
						title: media.title,
						filename: media.filename,
						url: media.url,
					},
				} );
			} }
			allowedTypes={ [ 'image' ] }
			value={ valueImage.id }
			multiple={ false }
			render={ ( { open } ) => (

				( valueImage.url ) ? <div className="bgImage-ctn">
					<Button
						className="image-btn"
						onClick={ open }
					>
						<img src={ valueImage.url } alt="" />
					</Button>
					<div className="dc-media-buttons gap-20">
						<Button onClick={ open } className="is-secondary">
							Replace Image
						</Button>
						<Button onClick={ () => {
							setAttributes( {
								[ attrImage ]: {
									title: '',
									filename: '',
									url: '',
								},
							} );
						} } className="is-link is-destructive">
							Remove image
						</Button>
					</div>
				</div> : <>

					<Button onClick={ open } className="is-primary">
						Upload Image
					</Button>
					<p className="components-base-control__help">{ help }</p>
				</>

			) }
		/>
	);
}
