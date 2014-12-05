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

	// check, if user has set a tmp dir (backend use '/tmp' by default)
	if( empty($tmp[cache][cacheDir]) && !is_writable('/tmp') ){ 
          // than we us the $upload_tmp_dir
          $upload_tmp_dir=ini_get('upload_tmp_dir');
	  if( !empty($upload_tmp_dir) && is_writable($upload_tmp_dir) ) $tmp[cache][cacheDir]=$upload_tmp_dir;
	  else die('No usualble tmp dir found.');
	}

        $shariff = new Backend($tmp);
	// ENDERitze: dynamisch fuer WP

        echo json_encode($shariff->get($_GET["url"]));
    }
}

Application::run();
