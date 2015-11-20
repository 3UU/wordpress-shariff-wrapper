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
function rsearch( $folder, $pattern ) {
    $dir = new RecursiveDirectoryIterator( $folder );
	$iterator = new RecursiveIteratorIterator( $dir );
    $files = new RegexIterator( $iterator, $pattern, RegexIterator::GET_MATCH );
    $fileList = array();
    foreach( $files as $file ) {
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

// if a custom permalink structure is used, WordPress throws a 404 in every ajax call
header( "HTTP/1.1 200 OK" );

// make sure that the provided url matches the WordPress domain
$get_url = parse_url( esc_url( $_GET["url"] ) );
$wp_url = parse_url( esc_url( get_bloginfo('url') ) );
if ( $get_url['host'] != $wp_url['host'] ) {
   	echo 'Wrong domain!';
	return; 
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

	// adjust ttl based on the post age
	if ( isset ( $_GET["timestamp"] ) ) {
		// the timestamp represents the last time the post or page was modfied
		$post_time = intval( $_GET["timestamp"] );
		$current_time = current_time( 'timestamp', true );
		$post_age = round( abs( $current_time - $post_time ) );
		if ( $post_age > '0' ) {
			$post_age_days = round( $post_age / 60 / 60 / 24 );
			// make sure ttl base is not getting too high
			if ( $ttl > '300' ) $ttl = '300';
			$ttl = round( ( $ttl + $post_age_days * 3 ) * ( $post_age_days * 2 ) );
		}
		// set minimum ttl to 60 seconds and maxium ttl to one week
		if ( $ttl < '60' ) {
			$ttl = '60';
		}
		elseif ( $ttl > '604800' ) {
			$ttl = '604800';
		}
		// in case we get a timestamp older than 01.01.2000 or for example a 0, use a reasonable default value of five minutes
		if ( $post_time < '946684800' ) {
			$ttl = '300';
		} 
	}

	// Facebook
	if ( ! isset ( $shariff3UU_advanced["disable"]["facebook"] ) || ( isset ( $shariff3UU_advanced["disable"]["facebook"] ) && $shariff3UU_advanced["disable"]["facebook"] == 0 ) ) {
		include ( 'services/facebook.php' );
	}
#	// Twitter https://blog.twitter.com/2015/hard-decisions-for-a-sustainable-platform
#	if ( ! isset ( $shariff3UU_advanced["disable"]["twitter"] ) || ( isset ( $shariff3UU_advanced["disable"]["twitter"] ) && $shariff3UU_advanced["disable"]["twitter"] == 0 ) ) {
#		include ( 'services/twitter.php' );
#	}
	// GooglePlus
	if ( ! isset ( $shariff3UU_advanced["disable"]["googleplus"] ) || ( isset ( $shariff3UU_advanced["disable"]["googleplus"] ) && $shariff3UU_advanced["disable"]["googleplus"] == 0 ) ) {
		include ( 'services/googleplus.php' );
	}
	// Xing
	if ( ! isset ( $shariff3UU_advanced["disable"]["xing"] ) || ( isset ( $shariff3UU_advanced["disable"]["xing"] ) && $shariff3UU_advanced["disable"]["xing"] == 0 ) ) {
		include ( 'services/xing.php' );
	}
	// LinkedIn
	if ( ! isset ( $shariff3UU_advanced["disable"]["linkedin"] ) || ( isset ( $shariff3UU_advanced["disable"]["linkedin"] ) && $shariff3UU_advanced["disable"]["linkedin"] == 0 ) ) {
		include ( 'services/linkedin.php' );
	}
	// Pinterest
	if ( ! isset ( $shariff3UU_advanced["disable"]["pinterest"] ) || ( isset ( $shariff3UU_advanced["disable"]["pinterest"] ) && $shariff3UU_advanced["disable"]["pinterest"] == 0 ) ) {
		include ( 'services/pinterest.php' );
	}
	// Flattr
	if ( ! isset ( $shariff3UU_advanced["disable"]["flattr"] ) || ( isset ( $shariff3UU_advanced["disable"]["flattr"] ) && $shariff3UU_advanced["disable"]["flattr"] == 0 ) ) {
		// include ( 'services/flattr.php' ); // temporarily disabled due to ongoing problems with the flattr api
	}
	// Reddit
	if ( ! isset ( $shariff3UU_advanced["disable"]["reddit"] ) || ( isset ( $shariff3UU_advanced["disable"]["reddit"] ) && $shariff3UU_advanced["disable"]["reddit"] == 0 ) ) {
		include ( 'services/reddit.php' );
	}
	// StumbleUpon
	if ( ! isset ( $shariff3UU_advanced["disable"]["stumbleupon"] ) || ( isset ( $shariff3UU_advanced["disable"]["stumbleupon"] ) && $shariff3UU_advanced["disable"]["stumbleupon"] == 0 ) ) {
		include ( 'services/stumbleupon.php' );
	}
	// Tumblr
	if ( ! isset ( $shariff3UU_advanced["disable"]["tumblr"] ) || ( isset ( $shariff3UU_advanced["disable"]["tumblr"] ) && $shariff3UU_advanced["disable"]["tumblr"] == 0 ) ) {
		include ( 'services/tumblr.php' );
	}
	// AddThis
	if ( ! isset ( $shariff3UU_advanced["disable"]["addthis"] ) || ( isset ( $shariff3UU_advanced["disable"]["addthis"] ) && $shariff3UU_advanced["disable"]["addthis"] == 0 ) ) {
		include ( 'services/addthis.php' );
	}
	// VK
	if ( ! isset ( $shariff3UU_advanced["disable"]["vk"] ) || ( isset ( $shariff3UU_advanced["disable"]["vk"] ) && $shariff3UU_advanced["disable"]["vk"] == 0 ) ) {
		include ( 'services/vk.php' );
	}

	// save transient, if we have counts
	if ( isset( $share_counts ) && $share_counts != null ) {
		set_transient( $post_hash, $share_counts, $ttl );
	}
}

// draw results, if we have some
if ( isset( $share_counts ) && $share_counts != null ) {
	echo json_encode( $share_counts );
}

?>
