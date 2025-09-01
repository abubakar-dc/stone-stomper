/**
 * External Dependencies
 */
const path = require( 'path' );
const FixStyleOnlyEntriesPlugin = require( 'webpack-fix-style-only-entries' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const CopyPlugin = require( 'copy-webpack-plugin' );
const ImageminPlugin = require( 'imagemin-webpack-plugin' ).default;
const RemovePlugin = require( 'remove-files-webpack-plugin' );

const isProduction = process.env.NODE_ENV === 'production';
/**
 * WordPress Dependencies
 */
const defaultConfig = require( '@wordpress/scripts/config/webpack.config.js' );

module.exports = {
	...defaultConfig,
	...{
		entry: {
			styles: path.resolve( process.cwd(), 'assets/src/css', 'bundle.scss' ),
			editor: path.resolve( process.cwd(), 'assets/src/js', 'editor.jsx' ),
			scripts: path.resolve( process.cwd(), 'assets/src/js', 'bundle.jsx' ),
			header: path.resolve( process.cwd(), 'assets/src/js', 'header.jsx' ),
			html: path.resolve( process.cwd(), 'assets/src/js', 'html.jsx' ),
		},
		output: {
			filename: '[name].min.js',
			path: path.resolve( process.cwd(), 'assets/build' ),
			clean: true,
		},
	},
	module: {
		...defaultConfig.module,
		rules: [
			...defaultConfig.module.rules,
			{
				test: /\.(bmp|png|jpe?g|gif|webp)$/i,
				type: 'asset/resource',
				generator: {
					filename: isProduction ? 'images/[name].webp' : 'images/[name][ext]',
				},
			},
			{
				test: /\.(woff|woff2|eot|ttf|otf)$/i,
				type: 'asset/resource',
				generator: {
					filename: 'fonts/[name][ext]',
				},
			},
			{
				test: /\.html$/i,
				loader: 'html-loader',
				options: {
					sources: {
						// eslint-disable-next-line no-unused-vars
						urlFilter: ( attribute, value, resourcePath ) => {
							const upload = value.split( '/' );
							const extension = value.split( '.' ).pop();
							if ( extension === 'png' || extension === 'jpg' || extension === 'webp' || extension === 'jpeg' || extension === 'svg' ) {
								if ( upload.indexOf( 'uploads' ) === -1 ) {
									return true;
								}
							}
							return false;
						},
					},
				},
			},
			{
				test: /\.svg$/,
				issuer: /\.html$/,
				type: 'asset/resource',
				generator: {
					filename: 'images/[name][ext]',
				},
			},
		],
	},
	plugins: [
		...defaultConfig.plugins,
		new FixStyleOnlyEntriesPlugin(), //removes extra generated files.
		new MiniCssExtractPlugin( { filename: '[name].min.css' } ),
		// eslint-disable-next-line no-undef
		// isProduction ? new ImageminPlugin( {
		// 	test: /\.(jpe?g|png|gif|svg)$/i,
		// 	pngquant: {
		// 		quality: '65-90',
		// 		speed: 4,
		// 	},
		// 	svgo: {
		// 		plugins: [
		// 			{ removeViewBox: false },
		// 			{ removeUselessStrokeAndFill: false },
		// 			{ removeEmptyAttrs: false },
		// 		],
		// 	},
		// 	gifsicle: {
		// 		optimizationLevel: 7,
		// 		interlaced: false,
		// 	},
		// 	optipng: {
		// 		optimizationLevel: 7,
		// 		interlaced: false,
		// 	},
		// } ) : false,
		new CopyPlugin( {
			patterns: [
				{
					from: 'assets/src/js/vendors',
					to: 'vendors',
					noErrorOnMissing: true,

				},
				{
					from: 'assets/src/images/admin',
					to: 'images/admin',
					noErrorOnMissing: true,
				},
				{
					from: 'assets/src/images/pwa',
					to: 'images/pwa',
					noErrorOnMissing: true,
				},
				{
					from: 'assets/src/images/social-icons',
					to: 'images/social-icons',
					noErrorOnMissing: true,
				},
			],
		} ),
		new RemovePlugin( {
			after: {
				include: [
					'assets/build/html.min.js',
					'assets/build/html.min.js.map',
					'assets/build/html.min.asset.php',
					'assets/build/header.min.asset.php',
					'assets/build/editor.min.asset.php',
					'assets/build/scripts.min.asset.php',
					'assets/build/block.json',
				],
			},
		} ),
	].filter( Boolean ),
	performance: {
		assetFilter( assetFilename ) {
			return (
				assetFilename.endsWith( '.webp' ) ||
				assetFilename.endsWith( '.svg' ) ||
				assetFilename.endsWith( '.png' ) ||
				assetFilename.endsWith( '.jpg' ) ||
				assetFilename.endsWith( '.jpeg' )
			);
		},

		hints: 'warning',
		maxAssetSize: 100000,
	},
};
