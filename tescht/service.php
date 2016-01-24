<? 

// ersetzungen, die dann aus WP kommen
$title='Titel';
$getReferrerTrack='getReferrerTrack';
$url = $_SERVER[HTTP_HOST];


$string = file_get_contents("service.js");

// definition von shareUrl holen


$shareUrl = '"mailto:?body=" + url + shariff.getReferrerTrack() + "&subject=" + encodeURIComponent(title); ';
// explodieren am +
// trimmen
// ersetzen
// zusammensetzen


echo '<pre>';
// suche die Zeile mit $shereUrl

# $shareUrl = "mailto:?body=" + url + shariff.getReferrerTrack() + "&subject=" + encodeURIComponent(title);

// entferne alles bis zum return, damit wir nicht versehentlich Klammern aus
// irgendwelchen JS-Funktionen mit reinziehen
$string=strstr($string,'return');
// nimm nur den Teil in {}
$j=0;
for($i = 0; $i < strlen($string); $i++) {
	// starte, sobald wir die erste Klammer finden beziehungsweise bei
	// verschachtelten zaehle hoch
	if($string[$i]=='{')$j++;
    
	// baue das JSON zusamemn
	if ($j>=1) $json.=$string[$i]; 
	
	// wenn die Klammer geschlossen wird, zaehle runter...
	if($string[$i]=='}')$j--;
}

// ersetze alle einfachen Hochkommata
// rtzrtz: Kann uns auf die Fuesse fallen, wenn irgendwo im Text doch mal
// nen einfaches gebraucht wird. Aber erstmal gut so.
#$json=str_replace('\'','"',$json);

#$tmp=explode('":',$json);
#echo $tmp['shareUrl'];
#var_dump($tmp);
//

// finde die Zeile mit shareUrl

#$json=str_replace("'mailto:?body=' + url + shariff.getReferrerTrack() + '&subject=' + encodeURIComponent(title)",'blubber',$json);
$json=str_replace('$shareUrl','"$shareUrl"',$json);


echo $json;

$test=json_decode($json,true);
// die shareUrl wieder rein
$test[shareUrl] = $shareUrl;
echo $test[shareUrl];

?> <pre> <?
print_r($test);

die();

echo $_SERVER[REMOTE_ADDR]; 
if( ( $_SERVER[REMOTE_ADDR] == gethostbyname('huerth.3uu.net') ) || ($_SERVER[REMOTE_ADDR] == '78.35.43.68') || ($_SERVER[REMOTE_ADDR] == '78.35.43.66') ) { 
#	var_dump($_ENV);
echo ini_get('upload_tmp_dir');
	phpinfo(); 
	}
?>
<script language="JavaScript">
//alert(typeof(window.document.ontouchstart));
</script>