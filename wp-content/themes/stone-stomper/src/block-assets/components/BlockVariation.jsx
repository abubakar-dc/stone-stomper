
import { __ } from '@wordpress/i18n';
import { store as blocksStore } from '@wordpress/blocks';
import { useSelect } from '@wordpress/data';
import { __experimentalBlockVariationPicker as BlockVariationPicker } from '@wordpress/block-editor';
const BlockVariation = ( { children, blockName, selected, icon, onSelect } ) => {
	const { variations } = useSelect(
		( select ) => {
			const {
				getBlockVariations,
				getBlockType,
				getDefaultBlockVariation,
			} = select( blocksStore );

			return {
				blockType: getBlockType( blockName ),
				defaultVariation: getDefaultBlockVariation( blockName, 'block' ),
				variations: getBlockVariations( blockName, 'block' ),
			};
		},
		[ blockName ]
	);
	return (
		<>
			{
				selected !== '' ? <>{ children }</> : <div className="dc-variation-selector">
					<BlockVariationPicker
						icon={ icon }
						label={ __( ' Choose Design' ) }
						instructions={ __( 'Select a variation to start with.' ) }
						onSelect={ onSelect }
						variations={ variations }
					/>
				</div>
			}
		</>
	);
};

export default BlockVariation;

