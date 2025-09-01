const fs = require( 'fs' );
const path = require( 'path' );

const importModulesFromFolder = ( folderPath ) => {
	const modulePath = path.join( __dirname, folderPath );

	// Read the contents of the folder
	const items = fs.readdirSync( modulePath );

	// Filter out directories
	const files = items.filter( ( item ) => fs.statSync( path.join( modulePath, item ) ).isFile() );

	// Import each module dynamically
	const modules = files.map( ( file ) => require( path.join( modulePath, file ) ) );

	return modules;
};

// Dynamically import modules from all folders in the project-root
const modulesFromFolders = [];

// Read the contents of the project-root
const projectRootItems = fs.readdirSync( __dirname );

// Filter out directories
const folders = projectRootItems.filter( ( item ) => fs.statSync( path.join( __dirname, item ) ).isDirectory() );

// Import modules from each folder
folders.forEach( ( folder ) => {
	const folderModules = importModulesFromFolder( folder );
	modulesFromFolders.push( { folder, modules: folderModules } );
} );

// Use the imported modules
console.log( modulesFromFolders );
