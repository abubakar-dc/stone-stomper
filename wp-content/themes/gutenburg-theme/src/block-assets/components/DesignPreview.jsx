
const DesignPreview = ( icon, height = '20', width = '20' ) => {
	return (
		<img src={ icon } height={ height } width={ width } alt="icon" className="svg-icon" />
	);
};

export default DesignPreview;
