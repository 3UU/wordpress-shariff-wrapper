<?php
/**
 * Plugin Name: Shariff Wrapper
 * Plugin URI: http://www.3uu.org/plugins.htm
 * Description: This is a wrapper to Shariff. Enables shares in posts and/or themes with Twitter, Facebook, GooglePlus... with no harm for visitors privacy.
 * Version: 1.9.2
 * Author: 3UU
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

// prevent direct calls to shariff.php
if ( ! class_exists('WP') ) { die(); }
        
// the admin page
if ( is_admin() ){
  add_action( 'admin_menu', 'shariff3UU_add_admin_menu' );
  add_action( 'admin_init', 'shariff3UU_options_init' );
  add_action( 'init', 'shariff3UU_init_locale' );
}

// brachen wir eh sowohl im FE wie im Adminbereich, also koennen wir es auch gleich global EINMAL holen
// get options
$shariff3UU=get_option( 'shariff3UU' );

// Update function to perform tasks _once_ after an update, based on version number to work for automatic as well as manual updates
function shariff3UU_update() {

  /******************** VERSION ANPASSEN *******************************/
  $code_version = "1.9.2"; // Set code version - needs to be adjusted for every new version!
  /******************** VERSION ANPASSEN *******************************/

  $do_admin_notice=false;
  if(empty($GLOBALS["shariff3UU"]["version"]) || ( isset($GLOBALS["shariff3UU"]["version"]) && version_compare($GLOBALS["shariff3UU"]["version"], $code_version) == '-1') ) include(plugin_dir_path(__FILE__).'updates.php'); 

  /* Delete user meta entry shariff3UU_ignore_notice to display update message again after an update */
  if(!isset($wpdb)) { global $wpdb; }
  // check for multisite
  if (is_multisite() && $do_admin_notice==true) {
    $blogs = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A);
    if ($blogs) {
      foreach($blogs as $blog) {
        // switch to each blog
        switch_to_blog($blog['blog_id']);
        // delete user meta entry shariff3UU_ignore_notice
        $users = get_users('role=administrator');
          foreach ($users as $user) { if ( !get_user_meta($user, 'shariff3UU_ignore_notice' )) { delete_user_meta($user->ID, 'shariff3UU_ignore_notice'); } }
      }
      // switch back to main
      restore_current_blog();
    }
  } elseif($do_admin_notice==true) {
    $users = get_users('role=administrator');
    foreach ($users as $user) {       if ( !get_user_meta($user, 'shariff3UU_ignore_notice' )) { delete_user_meta($user->ID, 'shariff3UU_ignore_notice'); } }
  }

  /* End update procedures */
  $GLOBALS["shariff3UU"]["version"]=$code_version;

  // Remove empty elements and save to options table
  $shariff3UU = array_filter($GLOBALS["shariff3UU"]);
  update_option( 'shariff3UU', $shariff3UU );
}
add_action('admin_init', 'shariff3UU_update');

// Add settings link on plugin page
function shariff3UU_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=shariff3uu">' . __( 'Settings', 'shariff3UU' ) . '</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'shariff3UU_settings_link' );

// css for admin e.g. info notice
function admin_style() {
    wp_enqueue_style('admin_css', plugins_url('admin.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'admin_style');
    
// translations
function shariff3UU_init_locale() { if(function_exists('load_plugin_textdomain')) { load_plugin_textdomain('shariff3UU', false, dirname(plugin_basename(__FILE__)).'/locale' ); } }

// register shortcode
add_shortcode('shariff', 'RenderShariff' );

// add admin menu
function shariff3UU_add_admin_menu(){ add_options_page( 'Shariff', 'Shariff', 'manage_options', 'shariff3uu', 'shariff3uu_options_page' ); }
// menu options
function shariff3UU_options_init(){ 
  // register settings and call sanitize function
  register_setting( 'pluginPage', 'shariff3UU', 'shariff3UU_options_sanitize' );

  add_settings_section( 'shariff3UU_pluginPage_section', __( 'Enable Shariff for all post and configure the options with these settings.', 'shariff3UU' ),
    'shariff3UU_options_section_callback', 'pluginPage'
  );
        
  add_settings_field( 'shariff3UU_checkbox_add_after_all_posts', __( 'Check to put Shariff at the end off all posts.', 'shariff3UU' ),
    'shariff3UU_checkbox_add_after_all_posts_render', 'pluginPage', 'shariff3UU_pluginPage_section'
  );

  add_settings_field( 'shariff3UU_checkbox_add_after_all_pages', __( 'Check to put Shariff at the end off all pages.', 'shariff3UU' ),
    'shariff3UU_checkbox_add_after_all_pages_render', 'pluginPage', 'shariff3UU_pluginPage_section'
  );

  add_settings_field( 'shariff3UU_checkbox_add_after_all_overview', __( 'Check to put Shariff at the end off all posts on the overview page.', 'shariff3UU' ),
    'shariff3UU_checkbox_add_after_all_overview_render', 'pluginPage', 'shariff3UU_pluginPage_section'
  );

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
    __( 'Put in the service do you want enable (<code>facebook|twitter|googleplus|whatsapp|mail|mailto|printer| pinterest|linkedin|xing|reddit|stumbleupon|flattr|info</code>). Use the pipe sign | between two or more services.', 'shariff3UU' ), 
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
    'shariff3UU_text_twittervia', __( 'Set the screen name for Twitter (via) to', 'shariff3UU' ),
    'shariff3UU_text_twittervia_render', 'pluginPage', 'shariff3UU_pluginPage_section'
  );

  add_settings_field(
    'shariff3UU_text_flattruser', __( 'Set the username for Flattr to', 'shariff3UU' ),
    'shariff3UU_text_flattruser_render', 'pluginPage', 'shariff3UU_pluginPage_section'
  );

  add_settings_field(
    'shariff3UU_text_style', __( 'CSS style attributes for the CSS container _around_ Shariff', 'shariff3UU' ),
    'shariff3UU_text_style_render', 'pluginPage', 'shariff3UU_pluginPage_section'
  );
              
  // New alignment option
  add_settings_field( 'shariff3UU_radio_align', __( 'Select the alignment of the Shariff buttons', 'shariff3UU' ),
    'shariff3UU_radio_align_render', 'pluginPage', 'shariff3UU_pluginPage_section'
  );
  
  // New alignment option for the widget
  add_settings_field( 'shariff3UU_radio_align_widget', __( 'Select the alignment of the Shariff buttons in the widget', 'shariff3UU' ),
    'shariff3UU_radio_align_widget_render', 'pluginPage', 'shariff3UU_pluginPage_section'
  );
 
}

// sanitize input from the settings page
function shariff3UU_options_sanitize( $input ){
  $valid = array();
  if(isset($input["version"]))             	$valid["version"]                  	= sanitize_text_field( $input["version"] );
  if(isset($input["add_after_all_posts"])) 	$valid["add_after_all_posts"] 		= 	  $input["add_after_all_posts"];
  if(isset($input["add_before_all_posts"])) 	$valid["add_before_all_posts"] 		= absint( $input["add_before_all_posts"] );
  if(isset($input["add_after_all_overview"])) 	$valid["add_after_all_overview"]	= absint( $input["add_after_all_overview"] );
  if(isset($input["add_before_all_overview"])) 	$valid["add_before_all_overview"] 	= absint( $input["add_before_all_overview"] );
  if(isset($input["add_after_all_pages"])) 	$valid["add_after_all_pages"] 		= absint( $input["add_after_all_pages"] );
  if(isset($input["add_before_all_pages"])) 	$valid["add_before_all_pages"] 		= absint( $input["add_before_all_pages"] );
  if(isset($input["language"])) 		$valid["language"] 			= sanitize_text_field( $input["language"] );
  if(isset($input["theme"])) 			$valid["theme"] 			= sanitize_text_field( $input["theme"] );
  if(isset($input["vertical"])) 		$valid["vertical"] 			= absint( $input["vertical"] );
  if(isset($input["services"])) 		$valid["services"] 			= str_replace(' ', '',sanitize_text_field( $input["services"] ));
  if(isset($input["backend"])) 			$valid["backend"] 			= absint( $input["backend"] );
  if(isset($input["twitter_via"])) 		$valid["twitter_via"] 			= str_replace('@', '', sanitize_text_field( $input["twitter_via"] ));
  if(isset($input["flattruser"]))    		$valid["flattruser"]       		= str_replace('@', '', sanitize_text_field( $input["flattruser"] ));
  // waiting for fix https://core.trac.wordpress.org/ticket/28015 in order to use esc_url_raw instead for info_url
  if(isset($input["info_url"])) 		$valid["info_url"] 			= sanitize_text_field( $input["info_url"] );
  if(isset($input["style"])) 			$valid["style"] 			= sanitize_text_field( $input["style"] );
  if(isset($input["align"])) 			$valid["align"] 			= sanitize_text_field( $input["align"] );
  if(isset($input["align_widget"])) 		$valid["align_widget"] 			= sanitize_text_field( $input["align_widget"] );
  return $valid;
}

// render admin options: use isset() to prevent errors while debug mode is on 
function shariff3UU_checkbox_add_after_all_posts_render(){
  echo "<input type='checkbox' name='shariff3UU[add_after_all_posts]' ";
  if(isset($GLOBALS["shariff3UU"]["add_after_all_posts"])) echo  checked( $GLOBALS["shariff3UU"]["add_after_all_posts"], 1, 0 );
  echo " value='1'>";
}

function shariff3UU_checkbox_add_before_all_posts_render(){
  echo "<input type='checkbox' name='shariff3UU[add_before_all_posts]' ";
  if(isset($GLOBALS["shariff3UU"]["add_before_all_posts"])) echo checked( $GLOBALS["shariff3UU"]["add_before_all_posts"], 1, 0 );
  echo " value='1'>";
}

function shariff3UU_checkbox_add_after_all_overview_render(){
  echo "<input type='checkbox' name='shariff3UU[add_after_all_overview]' ";
  if(isset($GLOBALS["shariff3UU"]["add_after_all_overview"])) echo checked( $GLOBALS["shariff3UU"]["add_after_all_overview"], 1, 0 );
  echo " value='1'>";
}

function shariff3UU_checkbox_add_before_all_overview_render(){
  echo "<input type='checkbox' name='shariff3UU[add_before_all_overview]' ";
  if(isset($GLOBALS["shariff3UU"]["add_before_all_overview"])) echo checked( $GLOBALS["shariff3UU"]["add_before_all_overview"], 1, 0 );
  echo " value='1'>";
}

function shariff3UU_checkbox_add_after_all_pages_render(){
  echo "<input type='checkbox' name='shariff3UU[add_after_all_pages]' ";
  if(isset($GLOBALS["shariff3UU"]["add_after_all_pages"])) echo checked( $GLOBALS["shariff3UU"]["add_after_all_pages"], 1, 0 );
  echo " value='1'>";
}

function shariff3UU_checkbox_add_before_all_pages_render(){
  echo "<input type='checkbox' name='shariff3UU[add_before_all_pages]' ";
  if(isset($GLOBALS["shariff3UU"]["add_before_all_pages"])) echo checked( $GLOBALS["shariff3UU"]["add_before_all_pages"], 1, 0 );
  echo " value='1'>";
}

function shariff3UU_select_language_render(){
  $options = $GLOBALS["shariff3UU"]; if(!isset($options["language"]))$options["language"]='';
  echo "<select name='shariff3UU[language]'>
  <option value='' ".   selected( $options['language'], '', 0 ) .">". __( 'browser selected', 'shariff3UU') ."</option>
  <option value='en' ". selected( $options['language'], 'en', 0 ) .">English</option>
  <option value='de' ". selected( $options['language'], 'de', 0 ) .">Deutsch</option>
  <option value='fr' ". selected( $options['language'], 'fr', 0 ) .">Français</option>
  <option value='es' ". selected( $options['language'], 'es', 0 ) .">Español</option></select>";
}

function shariff3UU_radio_theme_render(){
  $options = $GLOBALS["shariff3UU"]; if(!isset($options["theme"]))$options["theme"]='';
#  $wpurl=site_url();
  echo "<table border='0'>
  <tr><td><input type='radio' name='shariff3UU[theme]' value='' ".      checked( $options['theme'], '',0 )      .">default</td><td><img src='".WP_CONTENT_URL."/plugins/shariff/pictos/defaultBtns.png'></td></tr>
  <tr><td><input type='radio' name='shariff3UU[theme]' value='color' ". checked( $options['theme'], 'color',0 ) .">color</td><td><img src='".WP_CONTENT_URL."/plugins/shariff/pictos/colorBtns.png'><br></td></tr>
  <tr><td><input type='radio' name='shariff3UU[theme]' value='grey' ".  checked( $options['theme'], 'grey',0 )  .">grey</td><td><img src='".WP_CONTENT_URL."/plugins/shariff/pictos/greyBtns.png'><br></td></tr>
  <tr><td><input type='radio' name='shariff3UU[theme]' value='white' ". checked( $options['theme'], 'white',0 ) .">white</td><td><img src='".WP_CONTENT_URL."/plugins/shariff/pictos/whiteBtns.png'><br></td></tr>
  <tr><td><input type='radio' name='shariff3UU[theme]' value='round' ". checked( $options['theme'], 'round',0 ) .">round</td><td><img src='".WP_CONTENT_URL."/plugins/shariff/pictos/roundBtns.png'><br></td></tr>
  </table>";
}

function shariff3UU_checkbox_vertical_render(){
  echo "<input type='checkbox' name='shariff3UU[vertical]' ";
  if(isset($GLOBALS['shariff3UU']['vertical'])) echo checked( $GLOBALS['shariff3UU']['vertical'], 1,0 );
  echo " value='1'><img src='". WP_CONTENT_URL ."/plugins/shariff/pictos/verticalBtns.png' align='top'>";
}

function shariff3UU_text_services_render(){ 
  (isset($GLOBALS["shariff3UU"]["services"])) ? $services = $GLOBALS["shariff3UU"]["services"] : $services = '';
  echo "<input type='text' name='shariff3UU[services]' value='". esc_html($services) ."' size='50' placeholder='twitter|facebook|googleplus|info'>";
}

function shariff3UU_checkbox_backend_render(){
  // To check that backend works well
  // http://[your_host]/wp-content/plugins/shariff/backend/?url=http%3A%2F%2F[your_host]
  // should give an array or "[ ]" 

  // check that PHP version is okay
  if (version_compare(PHP_VERSION, '5.4.0') < 1) echo "PHP version 5.4 or better is needed to enable the backend. ";
  echo "<input type='checkbox' name='shariff3UU[backend]' ";
  if(isset($GLOBALS['shariff3UU']['backend'])) echo checked( $GLOBALS['shariff3UU']['backend'], 1,0 );
  echo " value='1'>";
}

function shariff3UU_text_info_url_render(){
  (isset($GLOBALS['shariff3UU']['info_url'])) ? $info_url = $GLOBALS['shariff3UU']['info_url'] : $info_url = '';  
  echo "<input type='text' name='shariff3UU[info_url]' value='". esc_html($info_url) ."' size='50' placeholder='http://ct.de/-2467514'>";
}

function shariff3UU_text_twittervia_render(){
  (isset($GLOBALS['shariff3UU']['twitter_via'])) ? $twitter_via = $GLOBALS['shariff3UU']['twitter_via'] : $twitter_via = '';
  echo "<input type='text' name='shariff3UU[twitter_via]' value='". $twitter_via ."' size='50' placeholder='screenname'>";
}

function shariff3UU_text_flattruser_render(){
  (isset($GLOBALS['shariff3UU']['flattruser'])) ? $flattruser = $GLOBALS['shariff3UU']['flattruser'] : $flattruser = '';
  echo "<input type='text' name='shariff3UU[flattruser]' value='". $flattruser ."' size='50' placeholder='username'>";
}

function shariff3UU_text_style_render(){
  (isset($GLOBALS['shariff3UU']['style'])) ? $style = $GLOBALS['shariff3UU']['style'] : $style = '';
  echo "<input type='text' name='shariff3UU[style]' value='". esc_html($style) ."' size='50' placeholder='".__( 'please read about it in the FAQ', 'shariff3UU' )."'>";
}

function shariff3UU_radio_align_render(){
  $options = $GLOBALS["shariff3UU"]; if(!isset($options["align"]))$options["align"]='flex-start';
  echo "<table border='0'><tr>
  <td><input type='radio' name='shariff3UU[align]' value='flex-start' ". checked( $options['align'], 'flex-start',0 ) .">".__( 'left', 'shariff3UU' )."</td>
  <td><input type='radio' name='shariff3UU[align]' value='center' ".     checked( $options['align'], 'center',0 )     .">".__( 'center', 'shariff3UU' )."</td>
  <td><input type='radio' name='shariff3UU[align]' value='flex-end' ".   checked( $options['align'], 'flex-end',0 )   .">".__( 'right', 'shariff3UU' )."</td>
  </tr></table>";
}

function shariff3UU_radio_align_widget_render(){
  $options = $GLOBALS["shariff3UU"]; if(!isset($options["align_widget"]))$options["align_widget"]='flex-start';
  echo "<table border='0'><tr>
  <td><input type='radio' name='shariff3UU[align_widget]' value='flex-start' ".	checked( $options['align_widget'], 'flex-start',0 ) .">".__( 'left', 'shariff3UU' )."</td>
  <td><input type='radio' name='shariff3UU[align_widget]' value='center' ". 	checked( $options['align_widget'], 'center',0 )     .">".__( 'center', 'shariff3UU' )."</td>
  <td><input type='radio' name='shariff3UU[align_widget]' value='flex-end' ". 	checked( $options['align_widget'], 'flex-end',0 )   .">".__( 'right', 'shariff3UU' )."</td>
  </tr></table>";
}
                        
function shariff3UU_options_section_callback(){
  echo __( 'This configures the default behavior of Shariff for your blog. You can overwrite this in single posts or pages with the options within the <code>[shariff]</code> shorttag.', 'shariff3UU' );
}

function shariff3UU_options_page(){ 
  /* The <div> with the class "wrap" makes sure that messages are displayed below the title and not above */
  echo '<div class="wrap"><h2>Shariff ' . $GLOBALS["shariff3UU"]["version"] . '</h2><form action="options.php" method="post">';
  echo '<input type="hidden" name="shariff3UU[version]" value="'. $GLOBALS["shariff3UU"]["version"] .'">';
  settings_fields( 'pluginPage' );
  do_settings_sections( 'pluginPage' );
  submit_button();
  echo '</form>';
    
  // give a hint if the backend will not work
  // if we have a constant for the tmp-dir
  if(defined('SHARIFF_BACKEND_TMPDIR'))$tmp["cache"]["cacheDir"]=SHARIFF_BACKEND_TMPDIR;

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
  $shariff3UU = $GLOBALS["shariff3UU"];
  
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
  if(isset($shariff3UU["vertical"])) 	if($shariff3UU["vertical"]=='1') $shorttag.=' orientation="vertical"';
  // *** theme ***
  if(!empty($shariff3UU["theme"])) 	$shorttag.=' theme="'.$shariff3UU["theme"].'"';
  // *** lang ***
  if(!empty($shariff3UU["language"])) 	$shorttag.=' lang="'.$shariff3UU["language"].'"';
  //*** services ***
  if(!empty($shariff3UU["services"])) 	$shorttag.=' services="'.$shariff3UU["services"].'"';
  // *** backend ***
  if(isset($shariff3UU["backend"]))	if($shariff3UU["backend"]=='on' || $shariff3UU["backend"]=='1') $shorttag.=' backend="on"';
  // *** info-url ***
  // rtzTodo: data-info-url + check that info is in the services
  if(!empty($shariff3UU["info_url"])) $shorttag.=' info_url="'.$shariff3UU["info_url"].'"';
  // *** style ***
  if(!empty($shariff3UU["style"])) $shorttag.=' style="'.$shariff3UU["style"].'"';
  // *** twitter-via ***
  if(!empty($shariff3UU["twitter_via"])) $shorttag.=' twitter_via="'.$shariff3UU["twitter_via"].'"';

  // *** flatter-username ***
  if(!empty($shariff3UU["flattruser"])) $shorttag.=' flattruser="'.$shariff3UU["flattruser"].'"';

  // close the shorttag
  $shorttag.=']';
  
  return $shorttag;
}

// add mail from if view=mail
function sharif3UUaddMailForm($content){

if(WP_DEBUG==TRUE)echo limitRemoteUser(); 

 $mailform='<form action="'.get_permalink().'" method="GET">';
 $mailform.='<input type="hidden" name="act" value="sendMail">';
 $mailform.='<div id="mail_formular" style="background: none repeat scroll 0% 0% #EEE; font-size: 90%; padding: 0.2em 1em;">';
 $mailform.='<p><strong>Diesen Beitrag per E-Mail versenden:</strong></p>';
 $mailform.='<p><em>E-Mail-Adresse(n) Empf&auml;nger (maximal 5)</em><br /><input type="email" name="mailto" value="" placeholder="to@example.com"></p>';
 $mailform.='<p><em>E-Mail-Adresse des Absenders</em><br /><input type="email" name="from" value="" placeholder="from@example.com"></p>';
 $mailform.='<p><em>Name des Absenders (optional)</em><br /><input type="text" name="sender" value=""></p>';
 $mailform.='<p><em>Zusatztext</em><br /><textarea name="mail_comment" rows="2" cols="45"></textarea></p>';
 $mailform.='<p><input type="submit" value="E-Mail abschicken" /></p>';
 $mailform.='<p>Die hier eingegebenen Daten werden nur dazu verwendet, die E-Mail in Ihrem Namen zu versenden. Es erfolgt keine Weitergabe an Dritte oder eine Analyse zu Marketing-Zwecken.</p>';
 $mailform.='</form></div>';
 return $mailform.$content;
}

// send mail
function sharif3UUprocSentMail(){
  // Der Zusatztext darf keine Links enthalten, sonst zu verlockend fuer Spamer
  // optional robinson einbauen
  // optional auf eingeloggte User beschraenken, dann aber auch nicht allgemein anzeigen

   $wait=limitRemoteUser();
   if($wait > '5'){ echo 'Please wait '. $wait .' sec until your next mail. '; die('Ooops!!!'); }       

  // build the array with recipients
  $arr=explode(',',$_REQUEST["mailto"]);
  if($arr==FALSE)$arr=array($_REQUEST["mailto"]);
  // max 5
  for($i=0;$i<count($arr);$i++){ 
    if($i=='4') break;
    $mailto[]=sanitize_email($arr[$i]); 
  }
  
  $subject='Shariff share '.get_permalink();
  $message='Jemand moechte Dir die Seite \r\n';
  $message.=get_permalink();
  $message.='empfehlen.\r\n';
  $message.='Du erhaelst diese Email, weil der Betreiber der Seite das Plugin ';
  $message.='Shariff Wrapper auf seinem Blog aktiviert hat, dass entwickelt wurde, ';
  $message.='die Seite moeglichst anonym zu nutzen. Der Seitenbetreiber hat daher ';
  $message.='auch keine Moeglichkeit, naehere Informationen ueber den Absender ';
  $message.='zu geben. Du kannst Dich aber selbst auf eine Robinson-Liste setzen ';
  $message.='und wirst dann nie wieder Emails von diesem Plugin auf dem Blog erhalten.';

  // falls mail uebergeben, setze als return-path
  if(isset($_REQUEST["from"])) $headers='Return-Path: <'.sanitize_email($_REQUEST["from"]).'>\r\n';

  echo $subject.'<br>';
  echo $message.'<br>';
#  echo $headers.'<br>';

  if(empty($mailto['0'])) echo ('Ooops, no usuable email address found.');
  else wp_mail( $mailto, $subject, $message, $headers); // The function is available after the hook 'plugins_loaded'.
}

// set a timeout until new mails are possible                                  
function limitRemoteUser(){
  global $shariff3UU;

  //rtzrtz: aumgeschiebene aus dem DOS-Blocker. Nochmal gruebeln, ob wir das ohne memcache mit der Performance schaffen. Daher auch nur Grundfunktionalitaet.

  if(!isset($shariff3UU['REMOTEHOSTS'])) $shariff3UU['REMOTEHOSTS']='';
  $HOSTS=json_decode($shariff3UU['REMOTEHOSTS'],true);
  // Wartezeit in sekunden
  $wait=1;
  if($HOSTS[$_SERVER['REMOTE_ADDR']]-time()+$wait > 0){ if($HOSTS[$_SERVER['REMOTE_ADDR']]-time() < 86400) $wait=($HOSTS[$_SERVER['REMOTE_ADDR']]-time()+$wait)*2; }
  $HOSTS[$_SERVER['REMOTE_ADDR']]=time()+$wait;
  // Etwas Muellentsorgung
  if(count($HOSTS)%10 == 0 ){ while (list($key, $value) = each($HOSTS)){ if($value-time()+$wait < 0) { unset($HOSTS[$key]); update_option( 'shariff3UU', $shariff3UU ); } } }
  $REMOTEHOSTS=json_encode($HOSTS); 
  $shariff3UU['REMOTEHOSTS']=$REMOTEHOSTS; 
  // update nur, wenn wir nicht unter heftigen DOS liegen
  if($HOSTS[$_SERVER['REMOTE_ADDR']]-time() < '60') update_option( 'shariff3UU', $shariff3UU );
  return $HOSTS[$_SERVER['REMOTE_ADDR']]-time();
}

// add shorttag to posts
function shariffPosts($content) {
  $shariff3UU = $GLOBALS["shariff3UU"];

  // prepend the mail form
  if(isset($_REQUEST['view']) && $_REQUEST['view']=='mail')$content=sharif3UUaddMailForm($content);
  if(isset($_REQUEST['act'])  && $_REQUEST['act']=='sendMail') sharif3UUprocSentMail();

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
  if( !is_singular() && isset($shariff3UU["add_after_all_overview"]) && isset($shariff3UU["add_before_all_overview"])){
    if($shariff3UU["add_after_all_overview"]!='1' && $shariff3UU["add_before_all_overview"]!='1' ) return $content;
  }

  // now add Shariff
  if( !is_singular() ) {
    // auf der Uebersichtsseite
    if(isset($shariff3UU["add_before_all_overview"]))	if($shariff3UU["add_before_all_overview"]=='1')	$content=buildShariffShorttag().$content;
    if(isset($shariff3UU["add_after_all_overview"]))	if($shariff3UU["add_after_all_overview"]=='1') 	$content.=buildShariffShorttag();
    // einzelner Beitrag
  }elseif( is_singular( 'post' ) ){
    if(isset($shariff3UU["add_before_all_posts"]) && $shariff3UU["add_before_all_posts"]=='1' )	$content=buildShariffShorttag().$content;
    if(isset($shariff3UU["add_after_all_posts"])  && $shariff3UU["add_after_all_posts"]=='1' )	$content.=buildShariffShorttag();
    // Seite
  } elseif ( is_singular( 'page' ) ) {
    if(isset($shariff3UU["add_before_all_pages"]) && $shariff3UU["add_before_all_pages"]=='1' )	$content=buildShariffShorttag().$content;
    if(isset($shariff3UU["add_after_all_pages"]) && $shariff3UU["add_after_all_pages"]=='1' )	$content.=buildShariffShorttag();
  }

  // altes verhalten
  if (defined('SHARIFF_ALL_POSTS')) $content.=SHARIFF_ALL_POSTS;

  return $content;
}
add_filter('the_content', 'shariffPosts');

// add the align-style option to the css file
function shariff3UU_align_styles() {
  $shariff3UU = $GLOBALS["shariff3UU"];
  $custom_css = '';
  wp_enqueue_style('shariffcss', plugins_url('/shariff.min.local.css',__FILE__));

  if(isset($shariff3UU["align"]) && $shariff3UU["align"]!='none') {
     $align = $shariff3UU["align"];
     $custom_css .= "
       .shariff { justify-content: {$align} }
       .shariff { -webkit-justify-content: {$align} }
       .shariff { -ms-flex-pack: {$align} }
       .shariff ul { justify-content: {$align} }
       .shariff ul { -webkit-justify-content: {$align} }
       .shariff ul { -ms-flex-pack: {$align} }
       ";
  }

  if(isset($shariff3UU["align_widget"]) && $shariff3UU["align_widget"]!='none') {
     $align_widget = $shariff3UU["align_widget"];
     $custom_css .= "
       .widget .shariff { justify-content: {$align_widget} } 
       .widget .shariff { -webkit-justify-content: {$align_widget} }
       .widget .shariff { -ms-flex-pack: {$align_widget} }
       .widget .shariff ul { justify-content: {$align_widget} }
       .widget .shariff ul { -webkit-justify-content: {$align_widget} }
       .widget .shariff ul { -ms-flex-pack: {$align_widget} }
       ";
  }

  if($custom_css != '') wp_add_inline_style( 'shariffcss', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'shariff3UU_align_styles' );

// Render the shorttag to the HTML shorttag of Shariff
function RenderShariff( $atts , $content = null) {
  // get options
  $shariff3UU = $GLOBALS["shariff3UU"];

  // avoid errors if no attributes are given - use the old set of services to make it backward compatible
  if(empty($shariff3UU["services"]))$shariff3UU["services"]="twitter|facebook|googleplus|info";

  // use the backend option for every option that is not set in the shorttag
  $backend_options = $shariff3UU;
  if(isset($shariff3UU["vertical"]))  if($shariff3UU["vertical"]=='1')    $backend_options["vertical"]='vertical';
  if(isset($shariff3UU["backend"]))   if($shariff3UU["backend"]=='1')     $backend_options["backend"]='on';
  if ( empty($atts) ) $atts = $backend_options;
  else $atts = array_merge($backend_options,$atts);

  // remove empty elements (no need to write data-something="" to html)
  $atts = array_filter($atts);
 
  // make sure that use default WP jquery is loaded
  wp_enqueue_script('jquery');

  // the JS must be loaded at footer. Make sure that wp_footer() is present in yout theme!
  wp_enqueue_script('shariffjs', plugins_url('/shariff.js',__FILE__),'','',true);
  
  // prevent an error notice while debug mode is on, because of "undefined variable" when using .=
  $output='';
  
  // if we have a style attribute
  if(array_key_exists('style', $atts))$output.='<div class="ShariffSC" style="'. esc_html($atts['style']) .'">';
  $output.='<div class=\'shariff\'';

  // set a url attribute. Usefull e.g. in widgets that should point to the domain instead of page
  if(array_key_exists('url', $atts)) $output.=' data-url=\''.esc_url($atts['url']).'\'';
  else $output.=' data-url=\''.esc_url(get_permalink()).'\'';
      
  // same for the title attribute
  if(array_key_exists('title', $atts)) $output.=" data-title='".esc_html($atts['title'])."'";
  else $output.=' data-title=\''.get_the_title().'\'';
      
  // set options
  if(array_key_exists('info_url', $atts))    $output.=" data-info-url='".	esc_html($atts['info_url'])."'";
  if(array_key_exists('orientation', $atts)) $output.=" data-orientation='".	esc_html($atts['orientation'])."'";
  if(array_key_exists('theme', $atts))       $output.=" data-theme='".		esc_html($atts['theme'])."'";
  // rtzTodo: use geoip if possible
  if(array_key_exists('lang', $atts))        $output.=" data-lang='".		esc_html($atts['lang'])."'";
  if(array_key_exists('image', $atts))       $output.=" data-image='".		esc_html($atts['image'])."'";
  if(array_key_exists('media', $atts))       $output.=" data-media='".		esc_html($atts['media'])."'";
  if(array_key_exists('twitter_via', $atts)) $output.=" data-twitter-via='".	esc_html($atts['twitter_via'])."'";
  if(array_key_exists('flattruser', $atts)) $output.=" data-flattruser='".  esc_html($atts['flattruser'])."'";
  
  // if services are set do only use this
  if(array_key_exists('services', $atts)){
    // build an array
    $s=explode('|',$atts["services"]);
    $output.=' data-services=\'[';
    // prevent error while debug mode is on
    $strServices='';
    $flattr_error='';
    // walk 
    while (list($key, $val) = each($s)) { 
      // check if flattr-username is set if flattr is selected
      if($val!='flattr') $strServices.='"'.$val.'", ';
      elseif (array_key_exists('flattruser', $atts)) $strServices.='"'.$val.'", ';
      else $flattr_error='1';
    }
    // remove the separator and add it to output
    $output.=substr($strServices, 0, -2);
    $output.=']\'';
  }

  // if we dont have an image for pinterest, make sure that an image with hints will be used
  if(array_key_exists('services', $atts)) if( strstr($atts["services"], 'pinterest') && !array_key_exists('media', $atts)&&!array_key_exists('image', $atts))$output.=" data-media='".plugins_url('/pictos/defaultHint.jpg',__FILE__)."'";

  // enable share statistic request
  // Make sure u have set the domain of the blog in shariff/backend/shariff.json
  if(array_key_exists('backend', $atts)) if($atts['backend']=="on") $output.=" data-backend-url='".esc_url(plugins_url('/backend/',__FILE__))."'";
  
  // close the container
  $output.='></div>';

  // display warning to admins if flattr is set, but no flattrusername is provided
  if($flattr_error=='1' && current_user_can( 'manage_options' )) $output.='<div style="background-color:#ff0000;color:#fff;font-size:20px;font-weight:bold;padding:10px;text-align:center;margin:0 auto;line-height:1.5;">Username for Flattr is missing!</div>';

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

    // set url to current page to prevent sharing the first or last post on pages with multiple posts e.g. the overview page
    $wpurl = get_bloginfo('wpurl');
    $siteurl = get_bloginfo('url');
    // for "normal" installations
    $page_url = $wpurl . esc_url_raw($_SERVER['REQUEST_URI']);
    // if wordpress is installed in a subdirectory, but links are mapped to the main domain
    if ($wpurl != $siteurl) {
      $subdir = str_replace ( $siteurl , '' , $wpurl );
      $page_url = str_replace ( $subdir , '' , $page_url );
    }

    // same for title
    $wp_title = wp_title( '', false);
    if(!empty($wp_title)) $page_title = ltrim($wp_title); // wp_title for all pages that have it
    else $page_title = get_bloginfo('name'); // the site name for static start pages where wp_title is not set
    $shorttag=substr($shorttag,0,-1)." title='".$page_title."' url='".$page_url."']"; // add url and title to the shorttag

    // process the shortcode
    echo do_shortcode($shorttag);
    // close Container
    echo $after_widget;
  } // END widget($args, $instance)
} // END class ShariffWidget
// register Widget 
add_action('widgets_init', create_function('', 'return register_widget("ShariffWidget");'));

// Admin info needed on version 1.7 because of changed behavior of prior options
/* Display an update notice that can be dismissed */
function shariff3UU_admin_notice() {
  global $current_user;
  $user_id = $current_user->ID;
  // Check that the user hasn't already clicked to ignore the message and can access options
  if ( !get_user_meta($user_id, 'shariff3UU_ignore_notice') && current_user_can( 'manage_options' ) ) {
    $link = add_query_arg( 'shariff3UU_nag_ignore', '0', esc_url_raw($_SERVER['REQUEST_URI']));
    echo "<div class='updated'><a href='" . esc_url($link) . "' class='shariff_admininfo_cross'><div class='shariff_cross_icon'></div></a><p>" . __('Please check your ', 'shariff3UU') . "<a href='" . get_bloginfo('wpurl') . "/wp-admin/options-general.php?page=shariff3uu'>" . __('Shariff-Settings</a> - We have new detailed options where shariff buttons can be displayed! Also please read the FAQ about planed changes of the mail/mailto service functionality.', 'shariff3UU') . "</span></p></div>";
  }
}
add_action('admin_notices', 'shariff3UU_admin_notice');

/* Helper function for shariff3UU_admin_notice() */
function shariff3UU_nag_ignore() {
  global $current_user;
  $user_id = $current_user->ID;
  // If user clicks to ignore the notice, add that to their user meta
  if ( isset($_GET['shariff3UU_nag_ignore']) && sanitize_text_field($_GET['shariff3UU_nag_ignore'])=='0' ) {
    add_user_meta($user_id, 'shariff3UU_ignore_notice', 'true', true);
  }
}
add_action('admin_init', 'shariff3UU_nag_ignore');

/* Display an info notice if flattr is set as a service, but no username is entered */
function shariff3UU_flattr_notice() {
  if(isset($GLOBALS["shariff3UU"]["services"]) &&  (strpos($GLOBALS["shariff3UU"]["services"], 'flattr') != false) && empty($GLOBALS["shariff3UU"]["flattruser"]) && current_user_can( 'manage_options' )) {
    echo "<div class='error'><p>" . __('Please check your ', 'shariff3UU') . "<a href='" . get_bloginfo('wpurl') . "/wp-admin/options-general.php?page=shariff3uu'>" . __('Shariff-Settings</a> - Flattr was selected, but no username was provided! Please enter your <strong>Flattr username</strong> in the shariff options!', 'shariff3UU') . "</span></p></div>";
  }
}
add_action('admin_notices', 'shariff3UU_flattr_notice');

/* Delete the shariff3UU_ignore_notice meta entry upon deactivation as well as the cache */
function shariff3UU_deactivate() {
// check for multisite
if (is_multisite()) {
  global $wpdb;
  $blogs = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A);
  if ($blogs) {
    foreach($blogs as $blog) {
      // switch to each blog
      switch_to_blog($blog['blog_id']);
      // delete options from options table
      delete_option( $option_name );
      // delete user meta entry shariff3UU_ignore_notice
      $users = get_users('role=administrator');
      foreach ($users as $user) { if ( !get_user_meta($user, 'shariff3UU_ignore_notice' )) { delete_user_meta($user->ID, 'shariff3UU_ignore_notice'); } }
#      // delete cache dir
#      __shariff3UU_rrmdir( wp_upload_dir('1970/01') );
    }
    // switch back to main
    restore_current_blog();
  }
} else {
  // delete user meta entry shariff3UU_ignore_notice
  $users = get_users('role=administrator');
  foreach ($users as $user) { if ( !get_user_meta($user, 'shariff3UU_ignore_notice' )) { delete_user_meta($user->ID, 'shariff3UU_ignore_notice'); } }
#  // delete cache dir
#  $upload_dir = wp_upload_dir();
#  $cache_dir = $upload_dir['basedir'].'/1970';
#  shariff_removecachedir( $cache_dir );
  }
}
register_deactivation_hook( __FILE__, 'shariff3UU_deactivate' );

/* Helper function to delete cache directory */
function shariff_removecachedir($directory){
#rtzrtz ritze: Zu gefaehrlich. Erstmal enstchaerft.
return;
  foreach(glob("{$directory}/*") as $file) {
    if(is_dir($file)) shariff_removecachedir($file);
    else @unlink($file);
  }
  @rmdir($directory);
}
?>
