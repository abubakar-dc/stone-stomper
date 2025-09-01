/**
 * External Dependencies
 */
const path = require( 'path' );
const CopyWebpackPlugin = require( 'copy-webpack-plugin' ); // Import copy-webpack-plugin

/**
 * WordPress Dependencies
 */
const defaultConfig = require( '@wordpress/scripts/config/webpack.config.js' );

module.exports = {
	...defaultConfig,
	...{
		output: {
			path: path.resolve( process.cwd(), 'blocks' ), // Output folder
		},
		stats: { warnings: false },
		plugins: [
			// Preserve existing plugins from defaultConfig
			...( defaultConfig.plugins || [] ),

			// Add CopyWebpackPlugin to move render-child.php
			// new CopyWebpackPlugin( {
			// 	patterns: [
			// 		{
			// 			from: './src/**/render.php', // Match render.php in any subdirectory under src
			// 			to: ( { context, absoluteFilename } ) => {
			// 				// Extract the folder structure dynamically
			// 				const folderPath = absoluteFilename.split( 'src/' )[ 1 ].split( '/render.php' )[ 0 ];
			// 				// Copy file to the same dynamic folder inside blocks directory
			// 				return `${ folderPath }/render.php`;
			// 			},
			// 		},
			// 	],
			// } ),
		],
	},
};
