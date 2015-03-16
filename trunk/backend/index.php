<?php
require_once __DIR__.'/vendor/autoload.php';

use Heise\Shariff\Backend;
use Zend\Config\Reader\Json;

class Application
{
    public static function run()
    {
        header('Content-type: application/json');

        if (!isset($_GET["url"])) {
            echo json_encode(null);
            return;
        }

        $reader = new \Zend\Config\Reader\Json();

	// Ritze: dynamisch fuer WP
	// I know this looks stupid. But I need I for tests on command line. So plz dont blame me ;-)
	$tmp=$reader->fromFile('shariff.json');

	// check, if user has changed it to his domain
	if($tmp[domain]=='www.example.com') $tmp[domain]=$_SERVER[HTTP_HOST];
	if($tmp[domain]=='www.heise.de') $tmp[domain]=$_SERVER[HTTP_HOST];

        // build the wp-load/-config.php path
        $wp_root_path = dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) );
        // include the config
        include ( $wp_root_path . '/wp-config.php' );
        // set TTL (default is 60 s)
        // stupid cp of constant to var because of "Commit blocked by pre-commit hook"
        if(!empty($SHARIFF_BACKEND_TTL))$tmp[cache][ttl]=$SHARIFF_BACKEND_TTL;
                    
	// check, if user has set a tmp dir (backend use '/tmp' by default)
	// however we will set it in the array for later check that it is writable
	if( empty($tmp[cache][cacheDir]) ){ 
          $upload_tmp_dir=ini_get('upload_tmp_dir');
          if(!empty($SHARIFF_BACKEND_TMPDIR))$tmp[cache][cacheDir]=$SHARIFF_BACKEND_TMPDIR;
          // else check, that /tmp is usuable
	  elseif(@is_writable('/tmp'))$tmp[cache][cacheDir]='/tmp';
          // than we try to us the upload_tmp_dir
	  elseif( !empty($upload_tmp_dir) ) $tmp[cache][cacheDir]=$upload_tmp_dir; 
	  else {
	    // at least the the WP own upload dir should work...
	    // force a short init because we only need WP core
	    define( 'SHORTINIT', true );
	    // include wp-load.php file (that loads wp-config.php and bootstraps WP)
	    require( $wp_root_path . '/wp-load.php' );
	    // include for needed untrailingslashit()
	    require( $wp_root_path . '/wp-includes/formatting.php');
	    // to avoid conficts with other plugins and actual uploads we use a fixed date in the past
	    // month of my birthday would be great ;-)
	    // wp_upload_dir() create the dir if not exists
	    $upload_dir = @wp_upload_dir('1970/01');
	    $tmp[cache][cacheDir]=$upload_dir['basedir'].'/1970/01';
	  }
	}

	// final check that temp dir is usuable
	if(!is_writable($tmp[cache][cacheDir]))die("No usable tmp dir found. Please check ". $tmp[cache][cacheDir]);

        $shariff = new Backend($tmp);
	// ENDERitze: dynamisch fuer WP

        echo json_encode($shariff->get($_GET["url"]));
    }
}

Application::run();
