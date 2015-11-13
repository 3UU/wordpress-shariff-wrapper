<?php
// prevent caching
header( "Expires: Sat, 24 Jan 1970 04:10:00 GMT" ); // date from the past
header( "Last-Modified: " . gmdate("D, d M Y H:i:s" ) . " GMT" ); // always changed
header( "Cache-Control: no-store, no-cache, must-revalidate" );
header( "Cache-Control: post-check=0, pre-check=0", false ); // just for MSIE 5
header( "Pragma: no-cache" );

// send correct headers
header('Content-type: application/json; charset=utf-8');

// exit if no url is provided
if ( ! isset( $_GET["url"] ) ) { 
	echo 'No URL provided!';
	return; 
}
	
// build the wp root path
$wp_root_path = dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) );

// if wp-blog-header.php doesn't exist at $wp_root_path, then try the constant
if( ! file_exists( $wp_root_path . '/wp-blog-header.php') ) {
	// get the shariff-config.php
	if( file_exists( 'shariff-config.php' ) ) {
		require ( 'shariff-config.php' );
	}
	// try the constant, if it was changed
	if ( defined( SHARIFF_WP_ROOT_PATH ) && ( SHARIFF_WP_ROOT_PATH != '/path/to/wordpress/' ) ) {
		$wp_root_path = SHARIFF_WP_ROOT_PATH;
		if( ! file_exists( $wp_root_path . '/wp-blog-header.php') ) {
			// search for it
			$wp_load = rsearch( $wp_root_path, '/wp-load.php/' );
			// set $wp_root_path to the location of wp-load.php
			$wp_root_path = $wp_load['path'];
			// save it to shariff-config.php
			configsave( $wp_root_path );
		}
	}
	else {
		// search for it
		$wp_load = rsearch( $wp_root_path, '/wp-load.php/' );
		// set $wp_root_path to the location of wp-load.php
		$wp_root_path = $wp_load['path'];
		// save it to shariff-config.php
		configsave( $wp_root_path );
	}
}

// search in the subfolders of $wp_root_path for a given file (regex)
function rsearch($folder, $pattern) {
    $dir = new RecursiveDirectoryIterator( $folder );
    $iterator = new RecursiveIteratorIterator( $dir, RecursiveIteratorIterator::CATCH_GET_CHILD );
    $files = new RegexIterator($iterator, $pattern, RegexIterator::GET_MATCH);
    $fileList = array();
    foreach($files as $file) {
      $fileList[] = array(
      	'file' => $file,
      	'path' => $iterator->getPath()
      );
    }
    // return only the first result
    return $fileList[0];
}

// save $wp_root_path in shariff-config.php
function configsave( $wp_root_path ) {
	$shariffconfig = fopen( "shariff-config.php", "w") or die( "Unable to create or open the shariff-config.php!" );
	$txt = "<?php\n\n// Here you can define the path to your WordPress installation in case you have changed the default directory structure\n// Replace /www/htdocs/w00a94e9/social-emotions.de with the path to your wp-blog-header.php\n\ndefine( 'SHARIFF_WP_ROOT_PATH', '" . $wp_root_path . "' );\n\n?>\n";
	fwrite( $shariffconfig, $txt );
	fclose( $shariffconfig );
}

// fire up WordPress without theme support
define('WP_USE_THEMES', false);
require ( $wp_root_path . '/wp-blog-header.php');

// make sure that the provided url matches the WordPress domain
$get_url = parse_url( esc_url( $_GET["url"] ) );
$wp_url = parse_url( esc_url( get_bloginfo('url') ) );
if ( $get_url['host'] != $wp_url['host'] ) {
   	echo 'Wrong domain!';
	return; 
}

// get shariff options (fb id, fb secret and ttl)
$shariff3UU_advanced = (array) get_option( 'shariff3UU_advanced' );
	
// if we have a constant for the ttl
if ( defined( 'SHARIFF_BACKEND_TTL' ) ) $ttl = SHARIFF_BACKEND_TTL;
// elseif check for option from the WordPress plugin, must be between 120 and 7200 seconds
elseif ( isset( $shariff3UU_advanced['ttl'] ) ) {
	$ttl = absint( $shariff3UU_advanced['ttl'] );
	// make sure ttl is a reasonable number
	if ( $ttl < '61' ) $ttl = '60';
	elseif ( $ttl > '7200' ) $ttl = '7200';
}
// else set it to default (60 seconds)
else {
	$ttl = '60';
}

// get url
$post_url  = urlencode( esc_url( $_GET["url"] ) );
$post_url2 = esc_url( $_GET["url"] );

// set transient name
// transient names can only contain 40 characters, therefore we use a hash (md5 always creeates a 32 character hash)
// we need a prefix so we can clean up on deinstallation and updates
$post_hash = 'shariff' . hash( "md5", $post_url );

// check if transient exist and is valid
if ( get_transient( $post_hash ) !== false ) {
	// use stored data
	$share_counts = get_transient( $post_hash );
}
// if transient doesn't exit or is outdated, we fetch all counts
else {
	// Facebook
	include ( 'services/facebook.php' );
	// Twitter
	include ( 'services/twitter.php' );
	// Google
	include ( 'services/google.php' );
	// Xing
	include ( 'services/xing.php' );
	// LinkedIn
	include ( 'services/linkedin.php' );
	// Pinterest
	include ( 'services/pinterest.php' );
	// Flattr
	include ( 'services/flattr.php' );
	// Reddit
	include ( 'services/reddit.php' );
	// StumbleUpon
	include ( 'services/stumbleupon.php' );
	// Tumblr
	include ( 'services/tumblr.php' );
	// AddThis
	include ( 'services/addthis.php' );
	// save transient if we have counts
	if ( isset( $share_counts ) && $share_counts != null ) {
		set_transient( $post_hash, $share_counts, $ttl );
	}
}

// draw results, if we have some
if ( isset( $share_counts ) && $share_counts != null ) {
	echo json_encode( $share_counts );
}

?>
