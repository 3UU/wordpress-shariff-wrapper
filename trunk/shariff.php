<?php
/**
 * Plugin Name: Shariff for WordPress posts, pages, themes and as widget
 * Plugin URI: http://www.3uu.org/plugins.htm
 * Description: This is a wrapper to Shariff. Enables shares in posts and/or themes with Twitter, Facebook, GooglePlus... with no harm for visitors privacy.
 * Version: 1.7
 * Author: Ritze
 * Author URI: http://www.DatenVerwurstungsZentrale.com/
 * License: http://opensource.org/licenses/MIT
 * Donate link: http://folge.link/?bitcoin:1Ritz1iUaLaxuYcXhUCoFhkVRH6GWiMTP
 * Domain Path: /locale/
 * Text Domain: shariff3UU
 * 
 * ### Supported options ###
 *   services: [facebook|twitter|googleplus|whatsapp|mail|mailto|printer|pinterest|linkedin|xing|reddit|stumbleupon|info]
 *   info_url: http://ct.de/-2467514
 *   lang: de|en|fr
 *   theme: default|grey|white|round
 *   orientation: vertical
 *   twitter_via: screenname
 *   (see http://heiseonline.github.io/shariff/)
 *   style: CSS code that will be used in a DIV container arround shariff
 */

// the admin page
if ( is_admin() ){
  add_action( 'admin_menu', 'shariff3UU_add_admin_menu' );
  add_action( 'admin_init', 'shariff3UU_options_init' );
  add_action( 'init', 'shariff3UU_init_locale' );
}

// translations
function shariff3UU_init_locale() { if(function_exists('load_plugin_textdomain')) { load_plugin_textdomain('shariff3UU', false, dirname(plugin_basename(__FILE__)).'/locale' ); } }

# register shortcode
add_shortcode('shariff', 'RenderShariff' );

// Admin-Menu hinzu
function shariff3UU_add_admin_menu(){ add_options_page( 'Shariff', 'Shariff', 'manage_options', 'shariff3uu', 'shariff3uu_options_page' ); }
// Optionen fuers Menu
function shariff3UU_options_init(){ 
  // Name fuer die Optionen registrieren
  register_setting( 'pluginPage', 'shariff3UU' );

  // Optionen holen
  $GLOBALS["shariff3UUoptions"]=get_option( 'shariff3UU' );

  // Migration < v 1.7
  if(empty($GLOBALS["shariff3UUoptions"]["version"]) || $GLOBALS["shariff3UUoptions"]["version"] < "1.7"){
    if($GLOBALS["shariff3UUoptions"]["add_all"]=='1'){ $GLOBALS["shariff3UUoptions"]["add_after_all_posts"]='1'; $GLOBALS["shariff3UUoptions"]["add_after_all_pages"]='1';
      unset($GLOBALS["shariff3UUoptions"]["add_all"]);
    }
    if($GLOBALS["shariff3UUoptions"]["add_before_all"]=='1'){ $GLOBALS["shariff3UUoptions"]["add_before_all_posts"]='1'; $GLOBALS["shariff3UUoptions"]["add_before_all_pages"]='1';
      unset($GLOBALS["shariff3UUoptions"]["add_before_all"]);
    } 
    $GLOBALS["shariff3UUoptions"]["version"]="1.7";
    update_option( 'shariff3UU', $GLOBALS["shariff3UUoptions"] );
  }
  // ENDE Migration < v 1.7
  
  add_settings_section( 'shariff3UU_pluginPage_section', __( 'Enable Shariff for all post and configure the options with these settings.', 'shariff3UU' ),
    'shariff3UU_options_section_callback', 'pluginPage'
  );
        
#  add_settings_field( 'shariff3UU_checkbox_add_all', __( 'Check to put Shariff at the end off all posts.', 'shariff3UU' ),
#    'shariff3UU_checkbox_add_all_render', 'pluginPage', 'shariff3UU_pluginPage_section'
#  );

  add_settings_field( 'shariff3UU_checkbox_add_after_all_posts', __( 'Check to put Shariff at the end off all posts.', 'shariff3UU' ),
    'shariff3UU_checkbox_add_after_all_posts_render', 'pluginPage', 'shariff3UU_pluginPage_section'
  );

  add_settings_field( 'shariff3UU_checkbox_add_after_all_pages', __( 'Check to put Shariff at the end off all pages.', 'shariff3UU' ),
    'shariff3UU_checkbox_add_after_all_pages_render', 'pluginPage', 'shariff3UU_pluginPage_section'
  );

  add_settings_field( 'shariff3UU_checkbox_add_after_all_overview', __( 'Check to put Shariff at the end off all posts on the overview page.', 'shariff3UU' ),
    'shariff3UU_checkbox_add_after_all_overview_render', 'pluginPage', 'shariff3UU_pluginPage_section'
  );

#  add_settings_field( 'shariff3UU_checkbox_add_before_all', __( 'Check to put Shariff at the begin off all posts.', 'shariff3UU' ),
#    'shariff3UU_checkbox_add_before_all_render', 'pluginPage', 'shariff3UU_pluginPage_section'
#  );

  add_settings_field( 'shariff3UU_checkbox_add_before_all_posts', __( 'Check to put Shariff at the beginning off all posts.', 'shariff3UU' ),
    'shariff3UU_checkbox_add_before_all_posts_render', 'pluginPage', 'shariff3UU_pluginPage_section'
  );

  add_settings_field( 'shariff3UU_checkbox_add_before_all_pages', __( 'Check to put Shariff at the beginning off all pages.', 'shariff3UU' ),
    'shariff3UU_checkbox_add_before_all_pages_render', 'pluginPage', 'shariff3UU_pluginPage_section'
  );
        
  add_settings_field( 'shariff3UU_checkbox_add_before_all_overview', __( 'Check to put Shariff at the beginning off all posts on the overview page.', 'shariff3UU' ),
    'shariff3UU_checkbox_add_before_all_overview_render', 'pluginPage', 'shariff3UU_pluginPage_section'
  );
        
  add_settings_field( 'shariff3UU_select_language', __( 'Select button language.', 'shariff3UU' ), 
    'shariff3UU_select_language_render', 'pluginPage', 'shariff3UU_pluginPage_section' 
  );

  add_settings_field( 'shariff3UU_radio_theme', __( 'Select theme (Shariff button design).', 'shariff3UU' ),
    'shariff3UU_radio_theme_render', 'pluginPage', 'shariff3UU_pluginPage_section'
  );

  add_settings_field( 'shariff3UU_checkbox_vertical', __( 'Check this to make orientation of buttons <b>vertical</b>.', 'shariff3UU' ), 
    'shariff3UU_checkbox_vertical_render', 'pluginPage', 'shariff3UU_pluginPage_section' 
  );

  add_settings_field( 'shariff3UU_text_services', 
    __( 'Put in the service do you want enable (<code>facebook|twitter|googleplus|whatsapp|mail|mailto|printer|pinterest|linkedin|xing|reddit|stumbleupon|info</code>). Use the pipe sign | between two or more services.', 'shariff3UU' ), 
    'shariff3UU_text_services_render', 'pluginPage', 'shariff3UU_pluginPage_section' 
  );

  add_settings_field( 'shariff3UU_checkbox_backend', __( 'Check this to show share statistic.', 'shariff3UU' ),
    'shariff3UU_checkbox_backend_render', 'pluginPage', 'shariff3UU_pluginPage_section'
  );
       
  add_settings_field(
    'shariff3UU_text_info_url', __( 'Change the default link of the "info" button to:', 'shariff3UU' ),
    'shariff3UU_text_info_url_render', 'pluginPage', 'shariff3UU_pluginPage_section'
  );
  
  add_settings_field(
    'shariff3UU_text_style', __( 'CSS style attributes for the CSS container _around_ Shariff', 'shariff3UU' ),
    'shariff3UU_text_style_render', 'pluginPage', 'shariff3UU_pluginPage_section'
  );
  
  add_settings_field(
    'shariff3UU_text_twitervia', __( 'Set the screen name for Twitter (via) to', 'shariff3UU' ),
    'shariff3UU_text_twittervia_render', 'pluginPage', 'shariff3UU_pluginPage_section'
  );
 
}

#function shariff3UU_checkbox_add_all_render(){ 
#  echo "<input type='checkbox' name='shariff3UU[add_all]' ".checked( $GLOBALS["shariff3UUoptions"]["add_all"], 1, 0 )." value='1'>";
#}

#function shariff3UU_checkbox_add_before_all_render(){
#  echo "<input type='checkbox' name='shariff3UU[add_before_all]' ". checked( $GLOBALS["shariff3UUoptions"]["add_before_all"], 1, 0 ) ." value='1'>";
#}

function shariff3UU_checkbox_add_after_all_posts_render(){
  echo "<input type='checkbox' name='shariff3UU[add_after_all_posts]' ". checked( $GLOBALS["shariff3UUoptions"]["add_after_all_posts"], 1, 0 ) ." value='1'>";
}

function shariff3UU_checkbox_add_before_all_posts_render(){
  echo "<input type='checkbox' name='shariff3UU[add_before_all_posts]' ". checked( $GLOBALS["shariff3UUoptions"]["add_before_all_posts"], 1, 0 ) ." value='1'>";
}

function shariff3UU_checkbox_add_after_all_overview_render(){
  echo "<input type='checkbox' name='shariff3UU[add_after_all_overview]' ". checked( $GLOBALS["shariff3UUoptions"]["add_after_all_overview"], 1, 0 ) ." value='1'>";
}

function shariff3UU_checkbox_add_before_all_overview_render(){
  echo "<input type='checkbox' name='shariff3UU[add_before_all_overview]' ". checked( $GLOBALS["shariff3UUoptions"]["add_before_all_overview"], 1, 0 ) ." value='1'>";
}

function shariff3UU_checkbox_add_after_all_pages_render(){
  echo "<input type='checkbox' name='shariff3UU[add_after_all_pages]' ". checked( $GLOBALS["shariff3UUoptions"]["add_after_all_pages"], 1, 0 ) ." value='1'>";
}

function shariff3UU_checkbox_add_before_all_pages_render(){
  echo "<input type='checkbox' name='shariff3UU[add_before_all_pages]' ". checked( $GLOBALS["shariff3UUoptions"]["add_before_all_pages"], 1, 0 ) ." value='1'>";
}

function shariff3UU_select_language_render(){
  $options = $GLOBALS["shariff3UUoptions"];
  echo "<select name='shariff3UU[language]'>
  <option value='' ".   selected( $options['language'], '', 0 ) .">". __( 'browser selected', 'shariff3UU') ."</option>
  <option value='en' ". selected( $options['language'], 'en', 0 ) .">English</option>
  <option value='de' ". selected( $options['language'], 'de', 0 ) .">Deutsch</option>
  <option value='fr' ". selected( $options['language'], 'fr', 0 ) .">Français</option>
  <option value='es' ". selected( $options['language'], 'es', 0 ) .">Español</option></select>";
}

function shariff3UU_radio_theme_render(){
  $options = $GLOBALS["shariff3UUoptions"];
#  $wpurl=site_url();
  echo "<table border='0'>
  <tr><td><input type='radio' name='shariff3UU[theme]' value='' ".      checked( $options['theme'], '',0 )      .">default</td><td><img src='".WP_CONTENT_URL."/plugins/shariff/pictos/defaultBtns.png'></td></tr>
  <tr><td><input type='radio' name='shariff3UU[theme]' value='grey' ".  checked( $options['theme'], 'grey',0 )  .">grey</td><td><img src='".WP_CONTENT_URL."/plugins/shariff/pictos/greyBtns.png'><br></td></tr>
  <tr><td><input type='radio' name='shariff3UU[theme]' value='white' ". checked( $options['theme'], 'white',0 ) .">white</td><td><img src='".WP_CONTENT_URL."/plugins/shariff/pictos/whiteBtns.png'><br></td></tr>
  <tr><td><input type='radio' name='shariff3UU[theme]' value='round' ". checked( $options['theme'], 'round',0 ) .">round</td><td><img src='".WP_CONTENT_URL."/plugins/shariff/pictos/roundBtns.png'><br></td></tr>
  </table>";
}

function shariff3UU_checkbox_vertical_render(){ 
  echo "<input type='checkbox' name='shariff3UU[vertical]' ". checked( $GLOBALS['shariff3UUoptions']['vertical'], 1,0 ) ." value='1'><img src='". WP_CONTENT_URL ."/plugins/shariff/pictos/verticalBtns.png' align='top'>";
}

function shariff3UU_text_services_render(){ 
  echo "<input type='text' name='shariff3UU[services]' value='". $GLOBALS['shariff3UUoptions']['services'] ."' size='50' placeholder='twitter|facebook|googleplus|info'>";
}

function shariff3UU_checkbox_backend_render(){
  // To check that backend works well
  // http://[your_host]/wp-content/plugins/shariff/backend/?url=http%3A%2F%2F[your_host]
  // should give an array or "[ ]" 

  // check that PHP version is okay
  if (version_compare(PHP_VERSION, '5.4.0') < 1) echo "PHP version 5.4 or better is needed to enable the backend. ";
  echo "<input type='checkbox' name='shariff3UU[backend]' ". checked( $GLOBALS['shariff3UUoptions']['backend'], 1,0 ) ." value='1'>";
}

function shariff3UU_text_info_url_render(){
  echo "<input type='text' name='shariff3UU[info_url]' value='". $GLOBALS['shariff3UUoptions']['info_url'] ."' size='50' placeholder='http://ct.de/-2467514'>";
}

function shariff3UU_text_style_render(){
  echo "<input type='text' name='shariff3UU[style]' value='". $GLOBALS['shariff3UUoptions']['style'] ."' size='50' placeholder='please read about it in the FAQ'>";
}

function shariff3UU_text_twittervia_render(){
  echo "<input type='text' name='shariff3UU[twitter_via]' value='". $GLOBALS['shariff3UUoptions']['twitter_via'] ."' size='50' placeholder='screenname'>";
}
                        
function shariff3UU_options_section_callback(){
  echo __( 'This configures the default behavior of Shariff for your blog. You can overwrite this in single posts with the options within the <code>[shariff]</code> shorttag.', 'shariff3UU' );
}

function shariff3UU_options_page(){ 
  /* The <div> with the class "wrap" makes sure that messages are displayed below the title and not above */
  echo '<div class="wrap"><h2>Shariff</h2><form action="options.php" method="post">';
  settings_fields( 'pluginPage' );
  do_settings_sections( 'pluginPage' );
  submit_button();
  echo '</form>';
    
  // give a hint if the backend will not work
  // if we have a constant for the tmp-dir
  if(defined(SHARIFF_BACKEND_TMPDIR))$tmp["cache"]["cacheDir"]=SHARIFF_BACKEND_TMPDIR;

  // if we do not have a tmp-dir, we use the content dir of WP
  if( empty($tmp["cache"]["cacheDir"]) ){
    // to avoid conficts with other plugins and actual uploads we use a fixed date in the past
    // month of my birthday would be great ;-) The wp_upload_dir() create the dir if not exists.
    $upload_dir = @wp_upload_dir('1970/01');
    $tmp["cache"]["cacheDir"]=$upload_dir['basedir'].'/1970/01';
  }

  // final check that temp dir is usuable
  if(!is_writable($tmp["cache"]["cacheDir"])) echo ("<h2>No usable tmp dir found. Please check ". $tmp["cache"]["cacheDir"]) ." and read about the backend server configuration in the FAQ.</h2> </div>";
  else echo "Backend tmp directory ". $tmp["cache"]["cacheDir"] ." is usuable. To change it please read about the backend server configuration in the FAQ. </div>" ;
}
// END the admin page

// helper function to create the WP representation of 
// the shorttag by the sidewide configured options
function buildShariffShorttag(){
  // get options
  $shariff3UU = get_option( 'shariff3UU' );
  
  // menu configured option over old constant
  // however backward compatible to: 
  // Define it in wp-config.php with the shortcode that should added to all posts. Example:
  // define('SHARIFF_ALL_POSTS','[shariff services="facebook|twitter|googleplus" backend="on"]');
  // This is a workaround as long as we did not have more options with an own admin page.
  // Therefor it is not documented and will be removed with next major release.
  if( defined('SHARIFF_ALL_POSTS') ) { return SHARIFF_ALL_POSTS; }

  // build the shorttag
  $shorttag='[shariff';

  // *** orientation ***
  if($shariff3UU["vertical"]=='1') $shorttag.=' orientation="vertical"';

  // *** theme ***
  if(!empty($shariff3UU["theme"])) $shorttag.=' theme="'.$shariff3UU["theme"].'"';

  // *** lang ***
  if(!empty($shariff3UU["language"])) $shorttag.=' lang="'.$shariff3UU["language"].'"';

  //*** services ***
  if(!empty($shariff3UU["services"])) $shorttag.=' services="'.$shariff3UU["services"].'"';

  // *** backend ***
  if($shariff3UU["backend"]=='on' || $shariff3UU["backend"]=='1') $shorttag.=' backend="on"';

  // *** info-url ***
  // rtzTodo: data-info-url + check that info is in the services
  if(!empty($shariff3UU["info_url"])) $shorttag.=' info_url="'.$shariff3UU["info_url"].'"';

  // *** style ***
  if(!empty($shariff3UU["style"])) $shorttag.=' style="'.$shariff3UU["style"].'"';

  // close the shorttag
  $shorttag.=']';
  
  return $shorttag;
}

// add shorttag to posts
add_filter('the_content', 'shariffPosts');

// add shorttag to the post
function shariffPosts($content) {
  $shariff3UU = get_option( 'shariff3UU' );

  // conditional to make it functional compatible to the hack in yanniks plugin
  // if we want see it as text - replace the slash
  if (strpos($content,'/hideshariff') == true) { $content=str_replace("/hideshariff","hideshariff",$content); }
  // but not, if the hidshariff sign is in the text |or| if a special formed "[shariff..."  shortcut is found
  elseif( (strpos($content,'hideshariff') == true) ) {
    // remove the sign
    $content=str_replace("hideshariff","",$content);
    // and return without adding Shariff
    return $content;
  }
  
  // add it to single posts view only
  // expact the new values for adds on overview are set
  if( !is_singular() && $shariff3UU["add_after_all_overview"]!='1' && $shariff3UU["add_before_all_overview"]!='1' ) return $content;

  // now add Shariff
  if( !is_singular() ) {
    // auf der Uebersichtsseite
    if($shariff3UU["add_before_all_overview"]=='1') $content=buildShariffShorttag().$content;
    if($shariff3UU["add_after_all_overview"]=='1') $content.=buildShariffShorttag();
  }elseif( is_singular( 'post' ) ){
    // ab version 1.7. Die zweite Bedingung kann eigentlich raus. Vorsichtshalber in der Version
    // mal trotzdem pruefen, damit wir bei nem Update-Problem nicht doppelt anzeigen.
    if($shariff3UU["add_before_all_posts"]=='1' && $shariff3UU["add_before_all"]!='1') $content=buildShariffShorttag().$content;
    if($shariff3UU["add_after_all_posts"]=='1' && $shariff3UU["add_all"]!='1') $content.=buildShariffShorttag();
  } elseif ( is_singular( 'page' ) ) {
    // ab version 1.7. Die zweite Bedingung kann eigentlich raus. Vorsichtshalber in der Version
    // mal trotzdem pruefen, damit wir bei nem Update-Problem nicht doppelt anzeigen. Fliegt dann
    // zusammen mit dem else-Zweig bei der naechsten Version raus.
    if($shariff3UU["add_before_all_pages"]=='1' && $shariff3UU["add_before_all"]!='1') $content=buildShariffShorttag().$content;
    if($shariff3UU["add_after_all_pages"]=='1' && $shariff3UU["add__all"]!='1') $content.=buildShariffShorttag();
  }else{
    // auf allen Einzelseiten
    // vor version 1.7
    if($shariff3UU["add_before_all"]=='1') $content=buildShariffShorttag().$content;
    if($shariff3UU["add_all"]=='1') $content.=buildShariffShorttag();  
  }

  // altes verhalten
  if (defined('SHARIFF_ALL_POSTS')) $content.=SHARIFF_ALL_POSTS;

  return $content;
}

# Render the shorttag to the HTML shorttag of Shariff
function RenderShariff( $atts , $content = null) {
  $shariff3UU = get_option( 'shariff3UU' );
  // avoid errors if no attributes are given
  // use the old set of services to make it backward compatible
  if(empty($shariff3UU["services"]))$shariff3UU["services"]="twitter|facebook|googleplus|info";

  if (!is_array($atts)) {
    $atts=array("services"=>$shariff3UU["services"]);
    if(!empty($shariff3UU[style]))	$atts[style]=$shariff3UU["style"];
    if(!empty($shariff3UU[theme]))	$atts[theme]=$shariff3UU["theme"];
    if(!empty($shariff3UU[language]))	$atts[language]=$shariff3UU["language"];
    if(!empty($shariff3UU[info_url]))	$atts[info_url]=$shariff3UU["info_url"];
    if(!empty($shariff3UU[twitter_via]))$atts[twitter_via]=$shariff3UU["twitter_via"];
    if($shariff3UU["vertical"]=='1') 	$atts[orientation]="vertical";
    if($shariff3UU["backend"]=='1') 	$atts[backend]="on";
  }

  // the Styles/Fonts (We use a local copy of fonts because there is no
  // reason to send data to the hoster of the fonts. Am I paranoid? ;-)
  wp_enqueue_style('shariffcss',plugins_url('/shariff.min.local.css',__FILE__));
  // the JS must be loaded at footer. Make sure that wp_footer() is present in yout theme!
  wp_enqueue_script('shariffjs', plugins_url('/shariff.js',__FILE__),$deps,$ver,true);
  
  // if we have a style attribute
  if(array_key_exists('style', $atts))$output.='<div class="ShariffSC" style="'.$atts[style].'">';
#echo '<pre>';var_dump($atts);
  $output.='<div class=\'shariff\'';
  $output.=' data-title=\''.get_the_title().'\'';

  // set a url attribute. Usefull e.g. in widgets that should point to the domain instead of page
  if(array_key_exists('url', $atts)) $output.=' data-url=\''.$atts[url].'\'';
  else $output.=' data-url=\''.get_permalink().'\'';
  
  // set options
  if(array_key_exists('info_url', $atts))    $output.=" data-info-url='$atts[info_url]'";
  if(array_key_exists('orientation', $atts)) $output.=" data-orientation='$atts[orientation]'";
  if(array_key_exists('theme', $atts))       $output.=" data-theme='$atts[theme]'";
  // rtzTodo: use geoip if possible
  if(array_key_exists('lang', $atts))        $output.=" data-lang='$atts[lang]'";
  if(array_key_exists('image', $atts))       $output.=" data-image='$atts[image]'";
  if(array_key_exists('media', $atts))       $output.=" data-media='$atts[media]'";
  if(array_key_exists('twitter_via', $atts)) $output.=" data-twitter-via='$atts[twitter_via]'";
  // if we dont have once, make sure that an image with hints will used
  if(!array_key_exists('media', $atts)&&!array_key_exists('image', $atts))$output.=" data-media='".plugins_url('/pictos/defaultHint.jpg',__FILE__)."'";
  
  // if services are set do only use this
  if(array_key_exists('services', $atts)){
      // build an array
      $s=explode('|',$atts[services]);
      $output.=' data-services=\'[';
      // walk 
      while (list($key, $val) = each($s)){
        $strServices.='"'.$val.'", ';
      }
      // remove the separator and add it to output
      $output.=substr($strServices, 0, -2);
      $output.=']\'';
      }
  // enable share statistic request
  // Make sure u have set the domain of the blog in shariff/backend/shariff.json
  if($atts[backend]=='on') $output.=" data-backend-url='".plugins_url('/backend/',__FILE__)."'";
  
  // close the container
  $output.='></div>';
  // if we had have a style attribute too
  if(array_key_exists('style', $atts))$output.='</div>';
  
  return $output;
}

// Widget
class ShariffWidget extends WP_Widget {
  public function __construct() {
    // translations
    if(function_exists('load_plugin_textdomain')) { load_plugin_textdomain('shariff3UU', false, dirname(plugin_basename(__FILE__)).'/locale' ); }

    $widget_options = array(
      'classname' => 'Shariff',
      'description' => __('Add Shariff as configured in the admin menue.', 'shariff3UU')
      );

    $control_options = array();
    $this->WP_Widget('Shariff', 'Shariff', $widget_options, $control_options);
  } // END __construct()

  // widget form - see WP_Widget::form()
  public function form($instance) {
    // widgets defaults
    $instance = wp_parse_args((array) $instance, array(
                 'shariff-title' => '',
                 'shariff-tag' => '[shariff]',
               ));
    // set title
    echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('Title', 'shariff3UU') . '</strong></p>';
    // set title
    echo '<p><input id="'. $this->get_field_id('shariff-title') .'" name="'. $this->get_field_name('shariff-title') 
    .'" type="text" value="'. $instance['shariff-title'] .'" />(optional)</p>';
    // set shorttag
    echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>Shorttag</strong></p>';
    // set shorttag
    echo '<p><input id="'. $this->get_field_id('shariff-tag') .'" name="' . $this->get_field_name('shariff-tag') 
         . '" type="text" value=\''. str_replace('\'','"',$instance['shariff-tag']) .'\' size="30" />(optional)</p>';
    
    echo '<p style="clear:both;"></p>';
  } // END form($instance)

  // save widget configuration
  public function update($new_instance, $old_instance) {
    $instance = $old_instance;
    // widget conf defaults
    $new_instance = wp_parse_args((array) $new_instance, array( 'shariff-title' => '', 'shariff-tag' => '[shariff]') );
    // check input values
    $instance['shariff-title'] = (string) strip_tags($new_instance['shariff-title']);
    $instance['shariff-tag'] = (string) strip_tags($new_instance['shariff-tag']);

    // save config
    return $instance;
  } 
  
  // draw widget
  public function widget($args, $instance) {
    extract($args);
    // Container
    echo $before_widget;
    // print title
    $title = (empty($instance['shariff-title'])) ? '' : apply_filters('shariff-title', $instance['shariff-title']);
    if(!empty($title)) { echo $before_title . $title . $after_title; }
    // print shorttag
    // if is not configured, use the global options from admin menu
    if ($instance['shariff-tag']=='[shariff]') $shorttag=buildShariffShorttag();
    else $shorttag=$instance['shariff-tag'];
    // process the shortcode
    echo do_shortcode($shorttag);
    // close Container
    echo $after_widget;
  } // END widget($args, $instance)
} // END class ShariffWidget
// register Widget 
add_action('widgets_init', create_function('', 'return register_widget("ShariffWidget");'));

// Admin info needed on version 1.7 because of changed behavior of prior options
/* Delete the shariff_ignore_notice meta entry upon deactivation - we dont want to leave anything behind! This also resets the entry after an update. */
function shariff3UU_deactivate() {
  $users = get_users('role=administrator');
  foreach ($users as $user) { delete_user_meta($user->ID, 'shariff3UU_ignore_notice'); }
}
register_deactivation_hook( __FILE__, 'shariff3UU_deactivate' );

/* Display an update notice that can be dismissed */
function shariff3UU_admin_notice() {
  global $current_user ;
  /* Check that the user hasn't already clicked to ignore the message and can access options */
  if ( !get_user_meta($current_user->ID, 'shariff3UU_ignore_notice') && current_user_can( 'manage_options' ) ) {
    echo '<div class="updated"><p>';
    printf(__('<span>' . __('Please check your ', 'shariff3UU') . '<a href="' .
    get_bloginfo('wpurl') . '/wp-admin/options-general.php?page=shariff3uu">' .
    __('Shariff-Settings</a> - We have new detailed options where shariff buttons can be displayed! Also please read the FAQ about planed changes of the mail/mailto service functionality.', 'shariff3UU') .
    '</span><span style="float:right; margin-top: -8px; font-size:1.8em; font-weight:bold;"><a href="%1$s" style="color#000">&times;</a></span>'), '?shariff3UU_nag_ignore=0');
    echo '</p></div>';
  }
}
add_action('admin_notices', 'shariff3UU_admin_notice');

/* Helper function for shariff3UU_admin_notice() */
function shariff3UU_nag_ignore() {
  global $current_user;
  /* If user clicks to ignore the notice, add that to their user meta */
  if ( isset($_GET['shariff3UU_nag_ignore']) && $_GET['shariff3UU_nag_ignore'] == '0' ) { add_user_meta($current_user->ID, 'shariff3UU_ignore_notice', 'true', true); }
}
add_action('admin_init', 'shariff3UU_nag_ignore');

?>
