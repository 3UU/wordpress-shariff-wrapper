<?php
define( 'WP_DEBUG', false );

// prevent caching
header( "Expires: Sat, 24 Jan 1970 04:10:00 GMT" ); // date from the past
header( "Last-Modified: " . gmdate("D, d M Y H:i:s" ) . " GMT" ); // always changed
header( "Cache-Control: no-store, no-cache, must-revalidate" );
header( "Cache-Control: post-check=0, pre-check=0", false ); // just for MSIE 5
header( "Pragma: no-cache" );

require_once __DIR__ . '/vendor/autoload.php';

use Heise\Shariff\Backend;
use Zend\Config\Reader\Json;

class Application {
  public static function run() {
	header('Content-type: application/json');
	// exit if no url is provided
	if ( ! isset( $_GET["url"] ) ) { 
		echo 'No URL provided!';
		return; 
	}

	// if we have a json config file
	if ( is_readable( dirname( __FILE__ ) . '/shariff.json' ) ) {
	  $reader = new \Zend\Config\Reader\Json();
	  $tmp = $reader->fromFile( dirname( __FILE__ ) . '/shariff.json' ); 
	}
	   
	// check, if user has changed it to his domain
	if ( ( $tmp['domain'] == 'www.example.com' ) || ( $tmp['domain'] == 'www.heise.de' ) || empty( $tmp['domain'] ) ) $tmp['domain'] = $_SERVER['HTTP_HOST'];
	
	// check mandatory services array
	if ( ! is_array( $tmp["services"] ) ) $tmp["services"] = array("0"=>"GooglePlus", 
														"1"=>"Twitter", 
														"2"=>"Facebook",
														"3"=>"LinkedIn",
														"4"=>"Reddit",
														"5"=>"Flattr",
														"6"=>"StumbleUpon",
														"7"=>"Pinterest",
														"8"=>"Xing");
	
	// force a short init because we only need WP core
	define( 'SHORTINIT', true );
	
	// build the wp-load/-config.php path
	$wp_root_path = dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) );
	
	// include the config
	include ( $wp_root_path . '/wp-config.php' );         
	
	// include wp-load.php file (that loads wp-config.php and bootstraps WP)
	require ( $wp_root_path . '/wp-load.php' );
	
	// include for needed untrailingslashit()
	require ( $wp_root_path . '/wp-includes/formatting.php' );

	// get fb app id and secret and ttl
	$shariff3UU_advanced = (array) get_option( 'shariff3UU_advanced' );

	// set fb api and secret
	if ( isset( $shariff3UU_advanced['fb_id'] ) && isset( $shariff3UU_advanced['fb_secret'] ) ) {
			$tmp["Facebook"]["app_id"] = absint( $shariff3UU_advanced['fb_id'] );
			$tmp["Facebook"]["secret"] = sanitize_text_field( $shariff3UU_advanced['fb_secret'] );
		}
	
	// if we have a constant for the ttl (default is 60 seconds)
	if ( defined( 'SHARIFF_BACKEND_TTL' ) ) $tmp["cache"]["ttl"] = SHARIFF_BACKEND_TTL;
	// elseif check for option from the WordPress plugin, must be between 60 and 7200 seconds
	else {
		if ( isset( $shariff3UU_advanced['ttl'] ) ) {
			$ttl = absint( $shariff3UU_advanced['ttl'] );
			if ( $ttl < '61' ) $ttl = '60';
			elseif ( $ttl > '7200' ) $ttl = '7200';
			else $tmp["cache"]["ttl"] = $ttl;
		}
	}

	// if we have a constant for the tmp-dir
	if ( defined( 'SHARIFF_BACKEND_TMPDIR' ) ) $tmp["cache"]["cacheDir"] = SHARIFF_BACKEND_TMPDIR;
	
	// if we do not have a tmp-dir, we use the content dir of WP
	if ( empty( $tmp["cache"]["cacheDir"] ) ) {
		$upload_dir = wp_upload_dir('/');
		$cache_dir = $upload_dir['basedir'] . '/shariff3uu_cache';
		// if it doesn't exit, try to create it
		if( ! file_exists( $cache_dir ) ) {
			wp_mkdir_p( $cache_dir );
		}
		$tmp["cache"]["cacheDir"] = $cache_dir;
	}

	// final check that temp dir is usuable
	if ( ! is_writable( $tmp["cache"]["cacheDir"] ) ) die( "No usable tmp dir found. Please check " . $tmp["cache"]["cacheDir"] );

	// start backend
	$shariff = new Backend( $tmp );

	// draw results, if we have some
	$share_counts = $shariff->get( $_GET["url"] );
	if ( isset( $share_counts ) && $share_counts != null ) {
		echo json_encode( $share_counts );
	}
	else {
		// it's actually just a guess, but very likely
		echo 'Invalid URL (e.g. wrong domain)! ';
		// in case we have some usefull information, but most likley will be "null"
		echo 'Message: ' . json_encode( $share_counts );
	}
  }
}

Application::run();
