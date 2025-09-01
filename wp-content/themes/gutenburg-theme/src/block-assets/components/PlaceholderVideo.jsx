import { Button, Icon } from '@wordpress/components';
import { trash } from '@wordpress/icons';
import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import './global.scss';

const PlaceholderVideo = ({ props, valueVideoInline, attrVideoInline, valueVideo, attrVideo }) => {
	const { setAttributes } = props;

	return (
		<>
			<div className="image-box">
				<MediaUploadCheck>
					<MediaUpload
						onSelect={(media) => setAttributes({ [attrVideoInline]: media })}
						allowedTypes={['video']}
						multiple={false}
						gallery={false}
						addToGallery={false}
						value={valueVideoInline.id}
						render={({ open }) => {
							if (valueVideoInline.url) {
								return (
									<>
										<video
											muted
											autoPlay
											loop
											width="100%"
											src={valueVideoInline.url}
											controls={false} // Disable controls for inline video
										/>
										<Button className="is-primary" onClick={open}>
											Replace Video
										</Button>
										<Button
											className="is-link is-destructive"
											onClick={() => setAttributes({ [attrVideoInline]: {} })}
										>
											<Icon icon={trash} />
										</Button>
									</>
								);
							}
							return (
								<>
									<div className="dc-placeholder-video-with-popup" onClick={open}>
										<svg
											fill="none"
											xmlns="http://www.w3.org/2000/svg"
											viewBox="0 0 60 60"
											preserveAspectRatio="none"
											className="components-placeholder__illustration"
											aria-hidden="true"
											focusable="false"
										>
											<path vectorEffect="non-scaling-stroke" d="M60 60 0 0"></path>
										</svg>
									</div>
									<Button className="is-primary" onClick={open}>
										Add Video
									</Button>
								</>
							);
						}}
					/>
				</MediaUploadCheck>
			</div>
		</>
	);
};

export default PlaceholderVideo;
