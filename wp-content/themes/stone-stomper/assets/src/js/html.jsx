
requireAll( require.context( '../../../html-files/', true, /\.html$/ ) );
function requireAll( r ) {
	r.keys().forEach( r );
}
