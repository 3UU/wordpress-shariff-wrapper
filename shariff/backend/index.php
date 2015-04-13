<?php
require_once __DIR__.'/vendor/autoload.php';

use Heise\Shariff\Backend;
use Zend\Config\Reader\Json;

class Application{
  public static function run(){
    header('Content-type: application/json');
    // exit if now host is given
    if (!isset($_GET["url"])) { echo json_encode(null); return; }

    // if we have a json config file
    if(is_readable(dirname( __FILE__ ).'/shariff.json')){
      $reader = new \Zend\Config\Reader\Json();
      $tmp=$reader->fromFile(dirname( __FILE__ ).'/shariff.json'); 
    }
       
    // check, if user has changed it to his domain
    if(($tmp['domain']=='www.example.com') || ($tmp['domain']=='www.heise.de') || empty($tmp['domain'])) $tmp['domain']=$_SERVER['HTTP_HOST'];
    // check mandatory services array
    if(!is_array($tmp["services"])) $tmp["services"]=array("0"=>"GooglePlus", 
                                                        "1"=>"Twitter", 
                                                        "2"=>"Facebook",
                                                        "3"=>"LinkedIn",
                                                        "4"=>"Reddit",
                                                        "5"=>"Flattr",
                                                        "6"=>"StumbleUpon",
                                                        "7"=>"Pinterest",
                                                        "8"=>"Xing");
    // if we have a constant for the ttl (default is 60 s)
    if(defined('SHARIFF_BACKEND_TTL'))$tmp["cache"]["ttl"]=SHARIFF_BACKEND_TTL;
    // if we have a constant for the tmp-dir
    if(defined('SHARIFF_BACKEND_TMPDIR'))$tmp["cache"]["cacheDir"]=SHARIFF_BACKEND_TMPDIR;
    // if we do not have a tmp-dir, we use the content dir of WP
    if( empty($tmp["cache"]["cacheDir"]) ){
      // force a short init because we only need WP core
      define( 'SHORTINIT', true );
      // build the wp-load/-config.php path
      $wp_root_path = dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) );
      // include the config
      include ( $wp_root_path . '/wp-config.php' );         
      // include wp-load.php file (that loads wp-config.php and bootstraps WP)
      require( $wp_root_path . '/wp-load.php' );
      // include for needed untrailingslashit()
      require( $wp_root_path . '/wp-includes/formatting.php');
      // to avoid conficts with other plugins and actual uploads we use a fixed date in the past
      // month of my birthday would be great ;-) The wp_upload_dir() create the dir if not exists.
      $upload_dir = @wp_upload_dir('1970/01');
      $tmp["cache"]["cacheDir"]=$upload_dir['basedir'].'/1970/01';
    }

    // final check that temp dir is usuable
    if(!is_writable($tmp["cache"]["cacheDir"]))die("No usable tmp dir found. Please check ". $tmp["cache"]["cacheDir"]);

    // start backend
    $shariff = new Backend($tmp);
    // draw result
    echo json_encode($shariff->get($_GET["url"]));
  }
}

Application::run();
