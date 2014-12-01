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
	$tmp=$reader->fromFile('shariff.json');
	if($tmp[domain]=='www.example.com') $tmp[domain]=$_SERVER[HTTP_HOST];
        $shariff = new Backend($tmp);
	// ENDERitze: dynamisch fuer WP

        echo json_encode($shariff->get($_GET["url"]));
    }
}

Application::run();
