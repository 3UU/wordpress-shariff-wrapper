<?
/*
Plugin Name: Shariff
Plugin URI: http://www.3uu.org/plugins.htm
Description: A better way to use share buttons of Twitter, Facebook and GooglePlus. 
Version: 0.2
Author: Ritze
Author URI: http://www.DatenVerwurstungsZentrale.com/
Update Server: http://download.3uu.net/wp/
License: GPLv3License URI: http://www.gnu.org/licenses/gpl-3.0.html

### Supported options ###
  services: [twitter|facebook|googleplus]
  info-url: http://ct.de/-2467514
  lang: de|en
  theme: default|grey|white
  orientation: vertical

http://heiseonline.github.io/shariff/

*/

// if we want enable it on all posts
// Define it in wp-config.php with the shortcode that should added to all posts. Example: 
// define('SHARIFF_ALL_POSTS','[shariff services="facebook|twitter|googleplus" backend="on"]');
// This is a workaround as long as we do not have more options so that it should better get
// an own admin page. Therefor it is also not documented and perhaps will removed in later 
// versions. Use it on your own risc.
if(defined('SHARIFF_ALL_POSTS')) {
  add_filter('the_content', 'shariffPosts');
  function shariffPosts($content) {
    // add it to single posts view only
    if( is_single() ){ 
      // if we want see it as text - replace the slash
      if (strpos($content,'/hideshariff') == true) { $content=str_replace("/hideshariff","hideshariff",$content); }
      // but not, if the pure hidshariff sign is in the text  
      elseif(strpos($content,'hideshariff') == true) {
        // remove the sign
        $content=str_replace("hideshariff","",$content);
        // and return without adding Shariff
        return $content;
        }
      // add Shariff
      return $content.=SHARIFF_ALL_POSTS;
    }
   // do not add Shariff
   return $content;
  }
}

# register it
add_shortcode('shariff', 'RenderShariff' );

# start it
function RenderShariff( $atts , $content = null) {
  // avoid errors if no attributes are given
  if(!is_array($atts))$atts=array();

  // clean up WP converted quotes
  $atts = str_replace(array('&#8221;','&#8243;'), '', $atts);
  
  // the Styles/Fonts (We use a local copy of fonts because there is no
  // reason to send data to the hoster of the fonts. Am I paranoid? ;-)
  wp_enqueue_style('shariffcss',plugins_url('/shariff.min.local.css',__FILE__));
  // the JS 
  wp_enqueue_script('shariffjs', plugins_url('/shariff.min.js',__FILE__));

  $output='<div class="shariff"';
  // set options
  if(array_key_exists('info-url', $atts))    $output.=' data-info-url="'.$atts[info-url].'"';
  if(array_key_exists('orientation', $atts)) $output.=' data-orientation="'.$atts[orientation].'"';
  // rtzTodo: use geoip if possible
  if(array_key_exists('lang', $atts))        $output.=' data-lang="'.$atts[lang].'"';
  // if services are set do only use this
  if(array_key_exists('services', $atts)){
      // build an array
      $s=explode('|',$atts[services]);
      $output.=' data-services="[';
      // walk 
      while (list($key, $val) = each($s)){
        $strServices.='&quot;'.$val.'&quot;, ';
      }
      // remove the separator and add it to output
      $output.=substr($strServices, 0, -2);
      $output.=']"';
      }
  // enable share statistic request
  // Make sure u have set the domain of the blog in shariff/backend/shariff.json
  if($atts[backend]=='on') $output.=' data-backend-url="'.plugins_url('/backend/',__FILE__).'"';
  
  // close the container
  $output.='></div>';
  
  return $output;
}
?>
