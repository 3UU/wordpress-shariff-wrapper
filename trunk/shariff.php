<?
/**
 * Plugin Name: Shariff
 * Plugin URI: http://www.3uu.org/plugins.htm
 * Description: A better way to use share buttons of Twitter, Facebook, GooglePlus...
 * Version: 1.0.2
 * Author: Ritze
 * Author URI: http://www.DatenVerwurstungsZentrale.com/
 * Update Server: http://download.3uu.net/wp/
 * License: http://opensource.org/licenses/MIT
 * Donate link: http://folge.link/?bitcoin:1Ritz1iUaLaxuYcXhUCoFhkVRH6GWiMTP
 * Domain Path: /locale/
 * Text Domain: shariff3UU
 * 
 * ### Supported options ###
 *   services: [twitter|facebook|googleplus|info]
 *   info-url: http://ct.de/-2467514
 *   lang: de|en
 *   theme: default|grey|white
 *   orientation: vertical
 *   (see http://heiseonline.github.io/shariff/)
 */

// the admin page
if ( is_admin() ){
  add_action( 'admin_menu', 'shariff3UU_add_admin_menu' );
  add_action( 'admin_init', 'shariff3UU_options_init' );
  add_action( 'init', 'shariff3UU_init_locale' );
}

// Sprache
function shariff3UU_init_locale() { load_plugin_textdomain('shariff3UU', false, dirname(plugin_basename(__FILE__)).'/locale' ); }
// Admin-Menu hinzu
function shariff3UU_add_admin_menu(){ add_options_page( 'Shariff', 'Shariff', 'manage_options', 'shariff3uu', 'shariff3uu_options_page' ); }
// Optionen fuers Menu
function shariff3UU_options_init(){ 
  // Name fuer die Optionen registrieren
  register_setting( 'pluginPage', 'shariff3UU' );

  add_settings_section( 'shariff3UU_pluginPage_section', __( 'Enable Shariff for all post and configure the options with these settings.', 'shariff3UU' ), 
    'shariff3UU_options_section_callback', 'pluginPage'
  );

  add_settings_field( 'shariff3UU_checkbox_add_all', __( 'Check to put Shariff at the end off all posts.', 'shariff3UU' ), 
    'shariff3UU_checkbox_add_all_render', 'pluginPage', 'shariff3UU_pluginPage_section' 
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
    __( 'Put in the service do you want enable (<code>twitter|facebook|googleplus|whatsapp|info|mail</code>). Use the pipe sign | between two or more services.', 'shariff3UU' ), 
    'shariff3UU_text_services_render', 'pluginPage', 'shariff3UU_pluginPage_section' 
  );

  add_settings_field( 'shariff3UU_checkbox_backend', __( 'Check this to show share statistic.', 'shariff3UU' ),
    'shariff3UU_checkbox_backend_render', 'pluginPage', 'shariff3UU_pluginPage_section'
  );
       
  add_settings_field(
    'shariff3UU_text_info_url', __( 'Change the default link of the "info" button to:', 'shariff3UU' ),
    'shariff3UU_text_info_url_render', 'pluginPage', 'shariff3UU_pluginPage_section'
  );
}

function shariff3UU_checkbox_add_all_render(){ 
	$options = get_option( 'shariff3UU' );
	?><input type='checkbox' name='shariff3UU[add_all]' <? checked( $options['add_all'], 1 ); ?> value='1'><?
}

function shariff3UU_select_language_render(){ 
	$options = get_option( 'shariff3UU' );
	?>
	<select name='shariff3UU[language]'>
		<option value='' <? selected( $options['language'], '' ) ?>><?=__( 'browser selected', 'shariff3UU')?></option>
		<option value='en' <? selected( $options['language'], 'en' ) ?>>English</option>
		<option value='de' <? selected( $options['language'], 'de' ) ?>>Deutsch</option>
	</select>
<?php
}

function shariff3UU_radio_theme_render(){
  $options = get_option( 'shariff3UU' );
  ?><table border="0">
  <tr><td><input type='radio' name='shariff3UU[theme]' value='' <? checked( $options['theme'], '' ) ?>>default</td><td><img src="<? bloginfo('wpurl') ?>/wp-content/plugins/shariff/pictos/defaultBtns.png"></td></tr>
  <tr><td><input type='radio' name='shariff3UU[theme]' value='grey' <? checked( $options['theme'], 'grey' ) ?>>grey</td><td><img src="<? bloginfo('wpurl') ?>/wp-content/plugins/shariff/pictos/greyBtns.png"><br></td></tr>
  <tr><td><input type='radio' name='shariff3UU[theme]' value='white' <? checked( $options['theme'], 'white' ) ?>>white</td><td><img src="<? bloginfo('wpurl') ?>/wp-content/plugins/shariff/pictos/whiteBtns.png"><br></td></tr>
  </table>
<?php
}

function shariff3UU_checkbox_vertical_render(){ 
	$options = get_option( 'shariff3UU' );
	?><input type='checkbox' name='shariff3UU[vertical]' <? checked( $options['vertical'], 1 ) ?> value='1'><img src="<? bloginfo('wpurl') ?>/wp-content/plugins/shariff/pictos/verticalBtns.png" align="top"><?
}

function shariff3UU_text_services_render(){ 
	$options = get_option( 'shariff3UU' );
	?><input type='text' name='shariff3UU[services]' value='<? echo $options['services'] ?>' size='50'><?
}

function shariff3UU_checkbox_backend_render(){
        $options = get_option( 'shariff3UU' );
        if (version_compare(PHP_VERSION, '5.4.0') < 1) echo "PHP version 5.4 or better is needed to enable the backend. ";
        ?><input type='checkbox' name='shariff3UU[backend]' <? checked( $options['backend'], 1 ) ?> value='1'><?
}

function shariff3UU_text_info_url_render(){
        $options = get_option( 'shariff3UU' );
        ?><input type='text' name='shariff3UU[info_url]' value='<?php echo $options['info_url']; ?>' size='50' placeholder="http://ct.de/-2467514"><?
}

function shariff3UU_options_section_callback(){
        echo __( 'This configures the default behavior of Shariff for your blog. You can overwrite this in single posts with the options within the <code>[shariff]</code> shorttag.', 'shariff3UU' );
}

function shariff3UU_options_page(){ 
	?>
	<form action='options.php' method='post'>
		
		<h2>Shariff</h2>
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
	</form>
	<?php
}
// END the admin page

$shariff3UU = get_option( 'shariff3UU' );

// Define it in wp-config.php with the shortcode that should added to all posts. Example: 
// define('SHARIFF_ALL_POSTS','[shariff services="facebook|twitter|googleplus" backend="on"]');
// This is a workaround as long as we did not have more options with an own admin page. 
// Therefor it is not documented and will be removed with next major release. 
if(defined('SHARIFF_ALL_POSTS') || $shariff3UU["add_all"]=='1' ) {
  // if we do not have the old style constant, built the shorttag 
  if($shariff3UU["add_all"]!='1') $shorttag=SHARIFF_ALL_POSTS;
  else{
    // build the shorttag
    $shorttag='[shariff';

    // *** orientation *** 
    if($shariff3UU["vertical"]=='1') $shorttag.=' orientation="vertical"';
    
    // *** theme ***
    if(!empty($shariff3UU["theme"])) $shorttag.=' theme="'.$shariff3UU[theme].'"';
    
    // *** lang ***
    if(!empty($shariff3UU["language"])) $shorttag.=' lang="'.$shariff3UU["language"].'"';

    //*** services ***
    if(!empty($shariff3UU["services"])) $shorttag.=' services="'.$shariff3UU["services"].'"';
    
    // *** backend *** 
    if($shariff3UU["backend"]=='1') $shorttag.=' backend="on"';
    
    // *** info-url ***
    // rtzTodo: data-info-url + check that info is in the services
    if(!empty($shariff3UU["info_url"])) $shorttag.=' info_url="'.$shariff3UU["info_url"].'"';
        
    // close the shorttag
    $shorttag.=']';
  }
  // END build the shorttag

  // do the magic
  add_filter('the_content', 'shariffPosts');

}

// add shorttag to the post
function shariffPosts($content) {
  global $shorttag;
  // add it to single posts view only
  if( is_single() ){
    // if we want see it as text - replace the slash
    if (strpos($content,'/hideshariff') == true) { $content=str_replace("/hideshariff","hideshariff",$content); }
    // but not, if the hidshariff sign is in the text |or| if a special formed "[shariff..."  shortcut is found
    elseif( (strpos($content,'hideshariff') == true) || (strpos($content,'[shariff') == true) ) {
      // remove the sign
      $content=str_replace("hideshariff","",$content);
      // and return without adding Shariff
      return $content;
    }
    // add Shariff
    return $content.=$shorttag;
  }else{
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
  $output.=' data-url="'.get_permalink().'"';
  // set options
  if(array_key_exists('info_url', $atts))    $output.=' data-info-url="'.$atts[info_url].'"';
  if(array_key_exists('orientation', $atts)) $output.=' data-orientation="'.$atts[orientation].'"';
  if(array_key_exists('theme', $atts))       $output.=' data-theme="'.$atts[theme].'"';
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
