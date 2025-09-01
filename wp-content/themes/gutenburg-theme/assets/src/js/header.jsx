
requireAll( require.context( './header/', true, /\.js$/ ) );

function requireAll( r ) {
	r.keys().forEach( r );
}
