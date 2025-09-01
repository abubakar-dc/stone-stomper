import { useEffect, useState } from 'react';

const UseImage = ( fileName ) => {
	const [ loading, setLoading ] = useState( true );
	const [ error, setError ] = useState( null );
	const [ image, setImage ] = useState( null );

	useEffect( () => {
		const fetchImage = async () => {
			try {
				const response = await require( `../images/${ fileName }` ); // change relative path to suit your needs
				console.log( response );
				setImage( response );
			} catch ( err ) {
				setError( err );
			} finally {
				setLoading( false );
			}
		};

		fetchImage();
	}, [ fileName ] );

	return {
		loading,
		error,
		image,
	};
};

export default UseImage;
