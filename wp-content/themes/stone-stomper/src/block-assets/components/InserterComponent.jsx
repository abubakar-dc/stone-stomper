import { Button, Icon } from '@wordpress/components';
import { plus } from '@wordpress/icons';
import { BlockControls, Inserter } from '@wordpress/block-editor';

const InserterComponent = ( { clientId, label, style = 'default', onClick } ) => {
	function AddSlide( { rootClientId } ) {
		return (
			<Inserter
				rootClientId={ rootClientId }
				renderToggle={ ( { onToggle, disabled } ) => (
					<Button
						className="inserter-button"
						onClick={ () => {
							onToggle();
							if ( onClick ) {
								onClick(); // Call the onClick handler
							}
						} }
						disabled={ disabled }
						label="Inserted Button"
					>
						<Icon icon={ plus } /> <span> { label } </span>
					</Button>
				) }
				isAppender
			/>
		);
	}

	if ( style === 'default' ) {
		return (
			<BlockControls>
				<AddSlide rootClientId={ clientId } />
			</BlockControls>
		);
	}

	return <AddSlide rootClientId={ clientId } />;
};

export default InserterComponent;
