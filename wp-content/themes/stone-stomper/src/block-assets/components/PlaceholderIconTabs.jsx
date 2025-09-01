
import { Button, Icon } from '@wordpress/components';
import { plus, trash } from '@wordpress/icons';

import {

	MediaUpload,
	MediaUploadCheck,
} from '@wordpress/block-editor';
import './global.scss';
const PlaceholderIconTabs = ( { props, clientId, valueImage, attrImage, state } ) => {
	const { setAttributes } = props;
	return (
		<>
			<div className="image-box">
				<MediaUploadCheck>
					<MediaUpload
						onSelect={ ( media ) => {
							wp.data.dispatch( 'core/block-editor' ).updateBlockAttributes( clientId, { [ attrImage ]: media } );
							setAttributes( { state: ! state } );
						} }
						allowedTypes={ [ 'image' ] }
						multiple={ false }
						gallery={ false }
						addToGallery={ false }
						value={ valueImage.id }
						render={ ( { open } ) => {
							if ( valueImage.url ) {
								return (
									<>
										<img src={ valueImage.url } alt="" className="image" onClick={ open } />
									</>
								);
							}
							return (
								<>
									<div className="dc-placeholder-icon" onClick={ open }>
										<Icon icon={ plus } />
									</div>

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

export default PlaceholderIconTabs;

