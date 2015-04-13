<?php
require_once __DIR__.'/vendor/autoload.php';

use Heise\Shariff\Backend;

class Application
{
    public static function run()
    {
        header('Content-type: application/json');
		$services = array();
		if (strpos($_GET["service"],"g") !== false) {
			$services[] = "GooglePlus";
		}
		if (strpos($_GET["service"],"t") !== false) {
			$services[] = "Twitter";
		}
		if (strpos($_GET["service"],"f") !== false) {
			$services[] = "Facebook";
		}
		if (strpos($_GET["service"],"l") !== false) {
			$services[] = "LinkedIn";
		}
		if (strpos($_GET["service"],"p") !== false) {
			$services[] = "Pinterest";
		}
		if (strpos($_GET["service"],"x") !== false) {
			$services[] = "Xing";
		}
		if (strpos($_GET["service"],"r") !== false) {
			$services[] = "Reddit";
		}
		if (strpos($_GET["service"],"s") !== false) {
			$services[] = "StumbleUpon";
		}
		if ($_GET["temp"] != "/tmp" && !empty($_GET["temp"])) {
			$arrayconfig = Array ( "cache" => Array ( "ttl" => $_GET["ttl"], "cacheDir" => $_GET["temp"] ),"domain" => $_SERVER["HTTP_HOST"],"services" => $services);
		} else {
			$arrayconfig = Array ( "cache" => Array ( "ttl" => $_GET["ttl"]),"domain" => $_SERVER["HTTP_HOST"],"services" => $services);
		}
        $shariff = new Backend($arrayconfig);
        echo json_encode($shariff->get($_GET["url"]));
    }
}

Application::run();
