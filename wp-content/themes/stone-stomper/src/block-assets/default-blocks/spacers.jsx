const { createHigherOrderComponent } = wp.compose;
import {
	useSettings,
} from '@wordpress/block-editor';
const withCoreSpacer = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		const { attributes, setAttributes } = props;
		let { height } = attributes;
		let defaultValue = '';
		const spacingSizes = useSettings(
			'spacing.spacingSizes'
		);
		for ( const key in spacingSizes ) {
			const element = spacingSizes[ key ];
			if ( typeof element.default !== 'undefined' && element.default ) {
				defaultValue = element.slug;
			}
			break;
		}

		if ( height === '100px' ) {
			setAttributes( { height: 'var:preset|spacing|' + defaultValue } );
			height = 'var:preset|spacing|' + defaultValue;
		}
		return (
			<>
				<BlockEdit { ...props } />
			</>
		);
	};
}, 'withCoreSpacer' );

wp.hooks.addFilter(
	'editor.BlockEdit',
	'core/spacer',
	withCoreSpacer
);
