import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import {
	Panel,
	PanelBody,
	PanelRow,
	SelectControl,
} from '@wordpress/components';
import DesignOption from './DesignOption.jsx';
const ContainerBlock = ( {
	children,
	props,
	customClass = '',
	hideWrapper = false,
} ) => {
	const { attributes, setAttributes } = props;
	const myCustomWidthClass = attributes.bgWidth
		? 'content-width-border ctn editor-' + attributes.bgWidth : '';

	const myCustomBorderClass = attributes.designBorder
		? 'editor-' + attributes.designBorder : '';

	const myCustomDesignClass = attributes.bgDesignType
		? 'editor-' + attributes.bgDesignType
		: '';

	const myCustomShapeClass = attributes.bgShape
		? 'editor-' + attributes.bgShape
		: '';
	const myCustomBoxClass = attributes.hasBoxedBackground
		? '' : 'without-boxed';
	const myCustomOverlapClass = attributes.hasOverlap
		? 'editor-block-overlap'
		: '';

	const ContainerClasses = [
		myCustomDesignClass,
		myCustomWidthClass,
		myCustomBorderClass,
		myCustomShapeClass,
		myCustomBoxClass,
		myCustomOverlapClass,
		customClass,
	].join( ' ' );

	return (
		<>
			<InspectorControls>
				<Panel>
					<PanelBody title={ __( 'Container' ) } initialOpen={ true }>
						<div className="theme-containers container-width">
							<PanelRow>
								<h2>Content Width</h2>
							</PanelRow>
							<PanelRow>
								<SelectControl
									value={ attributes.bgWidth }
									className="dc-sidebar-select"
									options={ [
										{
											label: '1410px (Default)',
											value: 'ctn',
										},
										{ label: '1920px', value: 'ctn-1920' },
										{ label: '1790px', value: 'ctn-1790' },
										{ label: '1300px', value: 'ctn-1300' },
										{ label: '1160px', value: 'ctn-1160' },
										{ label: '960px', value: 'ctn-960' },
										{ label: '690px', value: 'ctn-690' },
										{
											label: 'Full Width',
											value: 'ctn-full-width',
										},
									] }
									onChange={ ( value ) =>
										setAttributes( {
											bgWidth: value,
										} )
									}
								/>
							</PanelRow>
						</div>
						<hr />
						<div className="theme-containers container-design">
							<PanelRow>
								<h2>Background Color</h2>
							</PanelRow>
							<PanelRow>
								<DesignOption
									props={ props }
									value={ attributes.bgDesignType }
									DesignKey="bgDesignType"
									help=""
									options={ [
										{
											label: 'Light Pink ',
											value: 'ctn-light-pink',
											display: '#ffffff',
										},
										{
											label: 'Brown',
											value: 'ctn-brown',
											display: '#C3B8A6',
										},

									] }
								/>
							</PanelRow>
						</div>
						<hr />
						{ /* <div className="theme-containers container-design">
							<PanelRow>
								<h2>Border Option</h2>
							</PanelRow>

							<PanelRow>
								<SelectControl
									value={ attributes.designBorder }
									className="dc-sidebar-select"
									options={ [
										{
											label: 'No Border Default',
											value: 'no-border',
										},
										{
											label: 'Border Top',
											value: 'border-top',
										},
										{
											label: 'Border Bottom',
											value: 'border-bottom',
										},
										{
											label: 'Border Top/Bottom',
											value: 'border-top-bottom',
										},
									] }
									onChange={ ( value ) =>
										setAttributes( {
											designBorder: value,
										} )
									}
								/>
							</PanelRow>
						</div>
						<hr /> */ }

						<div className="theme-containers container-design">
							<PanelRow>
								<h2>Container Margin</h2>
							</PanelRow>
							<PanelRow>
								<SelectControl
									value={ attributes.marginTop }
									label="Top"
									className="dc-sidebar-select"
									options={ [
										{ label: '96px (Default)', value: '' },
										{ label: '2px', value: 'gl-s2' },
										{ label: '4px', value: 'gl-s4' },
										{ label: '6px', value: 'gl-s6' },
										{ label: '8px', value: 'gl-s8' },
										{ label: '12px', value: 'gl-s12' },
										{ label: '16px', value: 'gl-s16' },
										{ label: '20px', value: 'gl-s20' },
										{ label: '24px', value: 'gl-s24' },
										{ label: '30px', value: 'gl-s30' },
										{ label: '36px', value: 'gl-s36' },
										{ label: '48px', value: 'gl-s48' },
										{ label: '56px', value: 'gl-s56' },
										{ label: '60px', value: 'gl-s60' },
										{ label: '72px', value: 'gl-s72' },
										{ label: '80px', value: 'gl-s80' },
										{ label: '120px', value: 'gl-s120' },
										{ label: '128px', value: 'gl-s128' },
										{ label: '156px', value: 'gl-s156' },
										{ label: '200px', value: 'gl-s200' },
										{ label: '240px', value: 'gl-s240' },
									] }
									onChange={ ( value ) =>
										setAttributes( {
											marginTop: value,
										} )
									}
								/>
							</PanelRow>
							<PanelRow>
								<SelectControl
									value={ attributes.marginBottom }
									label="Bottom"
									className="dc-sidebar-select"
									options={ [
										{ label: '96px (Default)', value: '' },
										{ label: '2px', value: 'gl-s2' },
										{ label: '4px', value: 'gl-s4' },
										{ label: '6px', value: 'gl-s6' },
										{ label: '8px', value: 'gl-s8' },
										{ label: '12px', value: 'gl-s12' },
										{ label: '16px', value: 'gl-s16' },
										{ label: '20px', value: 'gl-s20' },
										{ label: '24px', value: 'gl-s24' },
										{ label: '30px', value: 'gl-s30' },
										{ label: '36px', value: 'gl-s36' },
										{ label: '48px', value: 'gl-s48' },
										{ label: '56px', value: 'gl-s56' },
										{ label: '60px', value: 'gl-s60' },
										{ label: '72px', value: 'gl-s72' },
										{ label: '80px', value: 'gl-s80' },
										{ label: '120px', value: 'gl-s120' },
										{ label: '128px', value: 'gl-s128' },
										{ label: '156px', value: 'gl-s156' },
										{ label: '200px', value: 'gl-s200' },
										{ label: '240px', value: 'gl-s240' },
									] }
									onChange={ ( value ) =>
										setAttributes( {
											marginBottom: value,
										} )
									}
								/>
							</PanelRow>
						</div>
					</PanelBody>
				</Panel>
			</InspectorControls>
			<div className={ 'dc-spacer ' + attributes.marginTop }></div>
			{ hideWrapper ? (
				<section className={ ContainerClasses }>{ children }</section>
			) : (
				<section className={ ContainerClasses }>
					<div className="wrapper">{ children }</div>
				</section>
			) }
			<div className={ 'dc-spacer ' + attributes.marginBottom }></div>
		</>
	);
};
const ContainerBlockContent = ( {
	children,
	props,
	customClass = '',
	hideWrapper = false,
} ) => {
	const { attributes } = props;
	const myCustomWidthClass = attributes.bgWidth ? attributes.bgWidth : 'ctn';
	const myCustomBorderClass = attributes.designBorder ? attributes.designBorder : ' ';
	const myCustomDesignClass = attributes.bgDesignType
		? attributes.bgDesignType
		: '';

	const myCustomShapeClass = attributes.bgShape ? attributes.bgShape : '';
	const myCustomBoxClass = attributes.hasBoxedBackground
		? ''
		: 'without-boxed';
	const myCustomOverlapClass = attributes.hasOverlap ? 'ctn-overlap' : '';

	const ContainerClasses = [
		myCustomDesignClass,
		myCustomWidthClass,
		myCustomBorderClass,
		myCustomShapeClass,
		myCustomBoxClass,
		myCustomOverlapClass,
		customClass,
	].join( ' ' );
	return (
		<>
			{ attributes.marginTop ? (
				<div className={ 'dc-spacer ' + attributes.marginTop }></div>
			) : (
				<></>
			) }
			{ hideWrapper ? (
				<section className={ ContainerClasses }>{ children }</section>
			) : (
				<section className={ 'ctn ' + ContainerClasses }>

					<div className="wrapper">{ children }</div>

				</section>
			) }
			{ attributes.marginBottom ? (
				<div
					className={ 'dc-spacer ' + attributes.marginBottom }
				></div>
			) : (
				<></>
			) }
		</>
	);
};

const customAttributes = {
	bgDesignType: {
		type: 'string',
		default: '',
	},
	bgWidth: {
		type: 'string',
		default: 'ctn',
	},
	designBorder: {
		type: 'string',
		default: '',
	},
	marginTop: {
		type: 'string',
		default: '',
	},
	marginBottom: {
		type: 'string',
		default: '',
	},
	hasBoxedBackground: {
		type: 'boolean',
		default: false,
	},
	hasOverlap: {
		type: 'boolean',
		default: false,
	},
};
export default ContainerBlock;
export { ContainerBlockContent, customAttributes };
