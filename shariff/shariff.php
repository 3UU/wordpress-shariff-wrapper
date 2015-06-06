<?php
/**
 * Plugin Name: Shariff Wrapper
 * Plugin URI: http://www.3uu.org/plugins.htm
 * Description: This is a wrapper to Shariff. It enables shares with Twitter, Facebook ... on posts, pages and themes with no harm for visitors privacy.
 * Version: 2.3.0
 * Author: 3UU, JP
 * Author URI: http://www.DatenVerwurstungsZentrale.com/
 * License: http://opensource.org/licenses/MIT
 * Donate link: http://folge.link/?bitcoin:1Ritz1iUaLaxuYcXhUCoFhkVRH6GWiMTP
 * Domain Path: /locale/
 * Text Domain: shariff3UU
 * 
 * ### Supported options ###
 *   services: [facebook|twitter|googleplus|whatsapp|pinterest|linkedin|xing|reddit|stumbleupon|mailform|mailto|printer|info]
 *   info_url: http://ct.de/-2467514
 *   lang: de|en
 *   theme: default|color|grey|white|round
 *   orientation: vertical
 *   twitter_via: screenname
 *   flattr_user: username
 *   style: CSS code that will be used in a DIV container around shariff
 */

// prevent direct calls to shariff.php
if ( ! class_exists('WP') ) { die(); }
				
// the admin page
if ( is_admin() ){
	add_action( 'admin_menu', 'shariff3UU_add_admin_menu' );
	add_action( 'admin_init', 'shariff3UU_options_init' );
	add_action( 'init', 'shariff3UU_init_locale' );
}

// get options (needed for front- and backend)
if ( ! get_option( 'shariff3UU_basic' ) ) {
	// version < 2.3
	$shariff3UU = get_option( 'shariff3UU' );
	$shariff3UU_basic = array();
	$shariff3UU_design = array();
	$shariff3UU_advanced = array();
	$shariff3UU_mailform = array();
}
else {
	// version >= 2.3
	$shariff3UU_basic = (array) get_option( 'shariff3UU_basic' );
	$shariff3UU_design = (array) get_option( 'shariff3UU_design' );
	$shariff3UU_advanced = (array) get_option( 'shariff3UU_advanced' );
	$shariff3UU_mailform = (array) get_option( 'shariff3UU_mailform' );
	$shariff3UU = array_merge( $shariff3UU_basic, $shariff3UU_design, $shariff3UU_advanced, $shariff3UU_mailform );
}

// allowed tags for headline
	$allowed_tags = array(
		// direct formatting e.g. <strong>
		'strong' => array(),
		'em'     => array(),
		'b'      => array(),
		'i'      => array(),
		'br'     => array(),
		// elements that can be formatted via CSS
		'span' => array 
			(
				'class' => array(),
				'style' => array(),
				'id' => array()
			),
		'p' => array
			(
				'class' => array(),
				'style' => array(),
				'id' => array()
			),
		'h1' => array
			(
				'class' => array(),
				'style' => array(),
				'id' => array()
			),
		'h2' => array
			(
				'class' => array(),
				'style' => array(),
				'id' => array()
			),
		'hr' => array
			(
				'class' => array(),
				'style' => array(),
				'id' => array()
			)
	);

// update function to perform tasks _once_ after an update, based on version number to work for automatic as well as manual updates
function shariff3UU_update() {

	/******************** ADJUST VERSION ********************/
	$code_version = "2.3.0"; // set code version - needs to be adjusted for every new version!
	/******************** ADJUST VERSION ********************/

	// do we want to display an admin notice after the update?
	$do_admin_notice = true;

	// check if the installed version is older than the code version
	if( empty( $GLOBALS["shariff3UU"]["version"] ) || ( isset( $GLOBALS["shariff3UU"]["version"] ) && version_compare( $GLOBALS["shariff3UU"]["version"], $code_version ) == '-1' ) ) {

		// include update file
		include( plugin_dir_path( __FILE__ ) . 'updates.php' ); 

		// make sure we have the $wpdb class ready
		if( ! isset($wpdb) ) { global $wpdb; }

		// Delete user meta entry shariff3UU_ignore_notice to display update message again after an update (check for multisite)
		if ( is_multisite() && $do_admin_notice == true ) {
			$blogs = $wpdb -> get_results( 'SELECT blog_id FROM {$wpdb->blogs}', ARRAY_A );
			if ( $blogs ) {
				foreach( $blogs as $blog ) {
					// switch to each blog
					switch_to_blog( $blog['blog_id'] );
					// delete user meta entry shariff3UU_ignore_notice
					$users = get_users( 'role=administrator' );
					foreach ( $users as $user ) { 
						if ( ! get_user_meta($user, 'shariff3UU_ignore_notice' ) ) { 
							delete_user_meta( $user -> ID, 'shariff3UU_ignore_notice' ); 
						} 
					}
					// switch back to main
					restore_current_blog();
				}
			}
		} elseif( $do_admin_notice == true ) {
			$users = get_users( 'role=administrator' );
			foreach ( $users as $user ) { 
				if ( ! get_user_meta( $user, 'shariff3UU_ignore_notice' ) ) { 
					delete_user_meta( $user -> ID, 'shariff3UU_ignore_notice' ); 
				} 
			}
		}

		// clear cache directory (check for multisite)
		if ( is_multisite() ) {
			$current_blog_id = get_current_blog_id();
			$blogs = $wpdb -> get_results('SELECT blog_id FROM { $wpdb -> blogs }', ARRAY_A);
			if ( $blogs ) {
				foreach( $blogs as $blog ) {
					// switch to each blog
					switch_to_blog( $blog['blog_id'] );
					// delete cache dir
					shariff_removecachedir();
					// switch back to main
					restore_current_blog();
				}
			}
		} else {
			// delete cache dir
			shariff_removecachedir();
		}

		// set new version
		$GLOBALS["shariff3UU"]["version"] = $code_version;
		$GLOBALS["shariff3UU_basic"]["version"] = $code_version;

		// remove empty elements and save to options table

		// basic
		$shariff3UU_basic = array_filter( $GLOBALS['shariff3UU_basic'] );
		update_option( 'shariff3UU_basic', $shariff3UU_basic );
		// design
		$shariff3UU_design = array_filter( $GLOBALS['shariff3UU_design'] );
		update_option( 'shariff3UU_design', $shariff3UU_design );
		// advanced
		$shariff3UU_advanced = array_filter( $GLOBALS['shariff3UU_advanced'] );
		update_option( 'shariff3UU_advanced', $shariff3UU_advanced );
		// mailform
		$shariff3UU_mailform = array_filter( $GLOBALS['shariff3UU_mailform'] );
		update_option( 'shariff3UU_mailform', $shariff3UU_mailform );
	}
}
add_action( 'admin_init', 'shariff3UU_update' );

// add settings link on plugin page
function shariff3UU_settings_link( $links ) { 
	$settings_link = '<a href="options-general.php?page=shariff3uu">' . __( 'Settings', 'shariff3UU' ) . '</a>'; 
	array_unshift( $links, $settings_link ); 
	return $links; 
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'shariff3UU_settings_link' );

// scripts and styles for admin pages e.g. info notice
function shariff3UU_admin_style( $hook ) {
	// styles for admin notice - needed on _ALL_ admin pages
	wp_enqueue_style( 'shariff_admin-notice', plugins_url( '/css/shariff_admin-notice.css', __FILE__ ) );
	// styles and scripts only needed on our plugin options page - no need to load them on _ALL_ admin pages
	if ( $hook == 'settings_page_shariff3uu' ) {
		// styles for our plugin options page
		wp_enqueue_style( 'shariff_options', plugins_url( '/css/shariff_options.css', __FILE__ ) );
		// scripts for pinterest default image media uploader
		wp_enqueue_script( 'jquery' ); // just in case
		wp_enqueue_media();
	}
}
add_action( 'admin_enqueue_scripts', 'shariff3UU_admin_style' );
		
// translations
function shariff3UU_init_locale() { 
	if ( function_exists( 'load_plugin_textdomain' ) ) { 
		load_plugin_textdomain( 'shariff3UU', false, dirname( plugin_basename( __FILE__ ) ) . '/locale' );
	}
}

// register shortcode
add_shortcode( 'shariff', 'Render3UUShariff' );

// add admin menu
function shariff3UU_add_admin_menu() { 
	add_options_page( 'Shariff', 'Shariff', 'manage_options', 'shariff3uu', 'shariff3UU_options_page' ); 
}

// plugin options page
function shariff3UU_options_init(){ 

	// first tab - basic

	// register first tab (basic) settings and call sanitize function
	register_setting( 'basic', 'shariff3UU_basic', 'shariff3UU_basic_sanitize' );

	// first tab - basic options
	add_settings_section( 'shariff3UU_basic_section', __( 'Basic options', 'shariff3UU' ),
		'shariff3UU_basic_section_callback', 'basic' );

	// services
	add_settings_field( 'shariff3UU_text_services', '<div class="shariff_status-col">' . __( 'Enable the following services in the provided order:', 'shariff3UU' ) . '</div>',
		'shariff3UU_text_services_render', 'basic', 'shariff3UU_basic_section' );
	
	// add after		
	add_settings_field( 'shariff3UU_multiplecheckbox_add_after', __( 'Add the Shariff buttons <u>after</u> all:', 'shariff3UU' ),
		'shariff3UU_multiplecheckbox_add_after_render', 'basic', 'shariff3UU_basic_section' );

	// add before
	add_settings_field( 'shariff3UU_checkbox_add_before', __( 'Add the Shariff buttons <u>before</u> all:', 'shariff3UU' ),
		'shariff3UU_multiplecheckbox_add_before_render', 'basic', 'shariff3UU_basic_section' );

	// disable on protected posts
	add_settings_field( 'shariff3UU_checkbox_disable_on_protected', __( 'Disable the Shariff buttons on password protected posts.', 'shariff3UU' ),
		'shariff3UU_checkbox_disable_on_protected_render', 'basic', 'shariff3UU_basic_section' );

	// share counts
	add_settings_field( 'shariff3UU_checkbox_backend', __( 'Enable share counts (statistic).', 'shariff3UU' ),
		'shariff3UU_checkbox_backend_render', 'basic', 'shariff3UU_basic_section' );

	// service status section
	add_settings_section( 'shariff3UU_status_section', __( 'Status', 'shariff3UU' ),
		'shariff3UU_status_section_callback', 'status' );

	// second tab - design

	// register second tab (design) settings and call sanitize function
	register_setting( 'design', 'shariff3UU_design', 'shariff3UU_design_sanitize' );

	// second tab - design options
	add_settings_section( 'shariff3UU_design_section', __( 'Design options', 'shariff3UU' ),
		'shariff3UU_design_section_callback', 'design' );

	// button language
	add_settings_field( 'shariff3UU_select_language', '<div class="shariff_status-col">' . __( 'Shariff button language:', 'shariff3UU' ) . '</div>', 
		'shariff3UU_select_language_render', 'design', 'shariff3UU_design_section' );

	// theme
	add_settings_field( 'shariff3UU_radio_theme', __( 'Shariff button design:', 'shariff3UU' ),
		'shariff3UU_radio_theme_render', 'design', 'shariff3UU_design_section' );

	// button size
	add_settings_field( 'shariff3UU_checkbox_buttonsize', __( 'Reduce button size by 30%.', 'shariff3UU' ), 
		'shariff3UU_checkbox_buttonsize_render', 'design', 'shariff3UU_design_section' );

	// button stretch
	add_settings_field( 'shariff3UU_checkbox_buttonsstretch', __( 'Stretch buttons horizontally to full width.', 'shariff3UU' ), 
		'shariff3UU_checkbox_buttonstretch_render', 'design', 'shariff3UU_design_section' );

	// vertical
	add_settings_field( 'shariff3UU_checkbox_vertical', __( 'Shariff button orientation <b>vertical</b>.', 'shariff3UU' ), 
		'shariff3UU_checkbox_vertical_render', 'design', 'shariff3UU_design_section' );

	// alignment option
	add_settings_field( 'shariff3UU_radio_align', __( 'Alignment of the Shariff buttons:', 'shariff3UU' ),
		'shariff3UU_radio_align_render', 'design', 'shariff3UU_design_section' );
	
	// alignment option for the widget
	add_settings_field( 'shariff3UU_radio_align_widget', __( 'Alignment of the Shariff buttons in the widget:', 'shariff3UU' ),
		'shariff3UU_radio_align_widget_render', 'design', 'shariff3UU_design_section' );

	// headline
	add_settings_field( 'shariff3UU_text_headline', __( 'Headline above the Shariff buttons:', 'shariff3UU' ),
		'shariff3UU_text_headline_render', 'design', 'shariff3UU_design_section' );

	// custom css
	add_settings_field( 'shariff3UU_text_style', __( 'CSS attributes for the container <span style="text-decoration: underline;">around</span> Shariff:', 'shariff3UU' ),
		'shariff3UU_text_style_render', 'design', 'shariff3UU_design_section' );

	// third tab - advanced

	// register third tab (advanced) settings and call sanitize function
	register_setting( 'advanced', 'shariff3UU_advanced', 'shariff3UU_advanced_sanitize' );

	// third tab - advanced options
	add_settings_section( 'shariff3UU_advanced_section', __( 'Advanced options', 'shariff3UU' ),
		'shariff3UU_advanced_section_callback', 'advanced' );

	// info url
	add_settings_field(
		'shariff3UU_text_info_url', '<div class="shariff_status-col">' . __( 'Custom link for the info button:', 'shariff3UU' ) . '</div>',
		'shariff3UU_text_info_url_render', 'advanced', 'shariff3UU_advanced_section' );
	
	// twitter via
	add_settings_field(
		'shariff3UU_text_twittervia', __( 'Twitter username for the via tag:', 'shariff3UU' ),
		'shariff3UU_text_twittervia_render', 'advanced', 'shariff3UU_advanced_section' );

	// flattr username
	add_settings_field(
		'shariff3UU_text_flattruser', __( 'Flattr username:', 'shariff3UU' ),
		'shariff3UU_text_flattruser_render', 'advanced', 'shariff3UU_advanced_section' );
	
	// default image for pinterest
	add_settings_field( 'shariff3UU_text_default_pinterest', __( 'Default image for Pinterest:', 'shariff3UU' ),
		'shariff3UU_text_default_pinterest_render', 'advanced', 'shariff3UU_advanced_section' );

	// Facebook App ID
	add_settings_field( 'shariff3UU_text_fb_id', __( 'Facebook App ID:', 'shariff3UU' ),
		'shariff3UU_text_fb_id_render', 'advanced', 'shariff3UU_advanced_section' );

	// Facebook App Secret
	add_settings_field( 'shariff3UU_text_fb_secret', __( 'Facebook App Secret:', 'shariff3UU' ),
		'shariff3UU_text_fb_secret_render', 'advanced', 'shariff3UU_advanced_section' );

	// ttl
	add_settings_field( 'shariff3UU_number_ttl', __( 'Cache TTL in seconds (60 - 7200):', 'shariff3UU' ),
		'shariff3UU_number_ttl_render', 'advanced', 'shariff3UU_advanced_section' );

	// fourth tab - mailform

	// register fourth tab (mailform) settings and call sanitize function
	register_setting( 'mailform', 'shariff3UU_mailform', 'shariff3UU_mailform_sanitize' );

	// fourth tab - mailform options
	add_settings_section( 'shariff3UU_mailform_section', __( 'Mail form options', 'shariff3UU' ),
		'shariff3UU_mailform_section_callback', 'mailform' );

	// disable mailform
	add_settings_field( 
		'shariff3UU_checkbox_disable_mailform', '<div class="shariff_status-col">' . __( 'Disable the mail form functionality.', 'shariff3UU' ) .'</div>',
		'shariff3UU_checkbox_disable_mailform_render', 'mailform', 'shariff3UU_mailform_section' );

	// require sender e-mail address
	add_settings_field( 
		'shariff3UU_checkbox_require_sender', '<div class="shariff_status-col">' . __( 'Require sender e-mail address.', 'shariff3UU' ) .'</div>',
		'shariff3UU_checkbox_require_sender_render', 'mailform', 'shariff3UU_mailform_section' );

	// mailform language
	add_settings_field( 
		'shariff3UU_select_mailform_language', '<div class="shariff_status-col">' . __( 'Mailform language:', 'shariff3UU' ) .'</div>',
		'shariff3UU_select_mailform_language_render', 'mailform', 'shariff3UU_mailform_section' );

	// add content of the post to e-mails
	add_settings_field( 
		'shariff3UU_checkbox_mail_add_post_content', '<div class="shariff_status-col">' . __( 'Add the post content to the e-mail body.', 'shariff3UU' ) .'</div>',
		'shariff3UU_checkbox_mail_add_post_content_render', 'mailform', 'shariff3UU_mailform_section' );

	// mail sender name
	add_settings_field( 
		'shariff3UU_text_mail_sender_name', __( 'Default sender name:', 'shariff3UU' ),
		'shariff3UU_text_mail_sender_name_render', 'mailform', 'shariff3UU_mailform_section' );

	// mail sender address
	add_settings_field( 
		'shariff3UU_text_mail_sender_from', __( 'Default sender e-mail address:', 'shariff3UU' ),
		'shariff3UU_text_mail_sender_from_render', 'mailform', 'shariff3UU_mailform_section' );

	// fifth tab - help

	// fifth tab - help
	add_settings_section( 'shariff3UU_help_section', __( 'Shariff Help', 'shariff3UU' ),
		'shariff3UU_help_section_callback', 'help' );
}

// sanitize input from the basic settings page
function shariff3UU_basic_sanitize( $input ) {
	// create array
	$valid = array();

	if ( isset( $input["version"] ) )				$valid["version"]				= sanitize_text_field( $input["version"] );
	if ( isset( $input["services"] ) )				$valid["services"]				= str_replace( ' ', '', sanitize_text_field( $input["services"] ) );
	if ( isset( $input["add_after"] ) )				$valid["add_after"]				= sani_add_arrays( $input["add_after"] );
	if ( isset( $input["add_before"] ) )			$valid["add_before"]			= sani_add_arrays( $input["add_before"] );
	if ( isset( $input["disable_on_protected"] ) )	$valid["disable_on_protected"]	= absint( $input["disable_on_protected"] );
	if ( isset( $input["backend"] ) ) 				$valid["backend"] 				= absint( $input["backend"] );

	// remove empty elements
	$valid = array_filter($valid);

	return $valid;
}

// sanitize input from the design settings page
function shariff3UU_design_sanitize( $input ) {
	// create array
	$valid = array();

	if ( isset( $input["language"] ) ) 				$valid["language"] 				= sanitize_text_field( $input["language"] );
	if ( isset( $input["theme"] ) ) 				$valid["theme"] 				= sanitize_text_field( $input["theme"] );
	if ( isset( $input["buttonsize"] ) )			$valid["buttonsize"]			= absint( $input["buttonsize"] );
	if ( isset( $input["buttonstretch"] ) )			$valid["buttonstretch"]			= absint( $input["buttonstretch"] );
	if ( isset( $input["vertical"] ) ) 				$valid["vertical"] 				= absint( $input["vertical"] );
	if ( isset( $input["align"] ) ) 				$valid["align"] 				= sanitize_text_field( $input["align"] );
	if ( isset( $input["align_widget"] ) ) 			$valid["align_widget"] 			= sanitize_text_field( $input["align_widget"] );
	if ( isset( $input["style"] ) ) 				$valid["style"] 				= sanitize_text_field( $input["style"] );
	if ( isset( $input["headline"] ) ) 				$valid["headline"] 				= wp_kses( $input["headline"], $GLOBALS["allowed_tags"] );

	// remove empty elements
	$valid = array_filter($valid);

	return $valid;
}

// sanitize input from the advanced settings page
function shariff3UU_advanced_sanitize( $input ) {
	// create array
	$valid = array();

	// waiting for fix https://core.trac.wordpress.org/ticket/28015 in order to use esc_url_raw instead for info_url
	if ( isset($input["info_url"] ) ) 				$valid["info_url"] 				= sanitize_text_field( $input["info_url"] );
	if ( isset($input["twitter_via"] ) ) 			$valid["twitter_via"] 			= str_replace( '@', '', sanitize_text_field( $input["twitter_via"] ) );
	if ( isset($input["flattruser"] ) )    			$valid["flattruser"]       		= str_replace( '@', '', sanitize_text_field( $input["flattruser"] ) );
	if ( isset($input["default_pinterest"] ) ) 	    $valid["default_pinterest"]		= sanitize_text_field( $input["default_pinterest"] );
	if ( isset($input["fb_id"] ) ) 	    			$valid["fb_id"]					= sanitize_text_field( $input["fb_id"] );
	if ( isset($input["fb_secret"] ) ) 	    		$valid["fb_secret"]				= sanitize_text_field( $input["fb_secret"] );
	if ( isset($input["ttl"] ) ) 	    			$valid["ttl"]					= absint( $input["ttl"] );
	// protect users from themselfs
	if ( isset( $valid["ttl"] ) && $valid["ttl"] < '60' ) $valid["ttl"] = '';
	elseif ( isset( $valid["ttl"] ) && $valid["ttl"] > '7200' ) $valid["ttl"] = '7200';

	// remove empty elements
	$valid = array_filter($valid);

	return $valid;
}

// sanitize input from the mailform settings page
function shariff3UU_mailform_sanitize( $input ) {
	// create array
	$valid = array();

	if ( isset( $input["disable_mailform"] ) )		$valid["disable_mailform"]		= absint( $input["disable_mailform"] );
	if ( isset( $input["require_sender"] ) )		$valid["require_sender"]		= absint( $input["require_sender"] );
	if ( isset( $input["mailform_language"] ) )		$valid["mailform_language"]		= sanitize_text_field( $input["mailform_language"] );
	if ( isset( $input["mail_add_post_content"] ) )	$valid["mail_add_post_content"]	= absint( $input["mail_add_post_content"] );
	if ( isset( $input["mail_sender_name"] ) )		$valid["mail_sender_name"]		= sanitize_text_field( $input["mail_sender_name"] );
	if ( isset( $input["mail_sender_from"] ) && is_email( $input["mail_sender_from"] ) != false ) $valid["mail_sender_from"] = sanitize_email( $input["mail_sender_from"] );

	// remove empty elements
	$valid = array_filter($valid);

	return $valid;
}

// helper function to sanitize add arrays
function sani_add_arrays( $data = array() ) {
	if ( ! is_array($data) || ! count( $data ) ) {
		return array();
	}
	foreach ( $data as $k => $v ) {
		if ( ! is_array( $v ) && ! is_object( $v ) ) {
			$data[ $k ] = absint( trim( $v ) );
		}
		if ( is_array( $v ) ) {
			$data[ $k ] = sani_add_arrays( $v );
		}
	}
	return $data;
}

// render admin options: use isset() to prevent errors while debug mode is on

// basic options

// description basic options
function shariff3UU_basic_section_callback(){
	echo __( "Select the desired services in the order you want them to be displayed, where the Shariff buttons should be included automatically and if you want the share counts to be shown.", "shariff3UU" );
}

// services
function shariff3UU_text_services_render(){ 
	if ( isset( $GLOBALS["shariff3UU_basic"]["services"] ) ) {
		$services = $GLOBALS["shariff3UU_basic"]["services"];
	}
	else {
		$services = '';
	}
	echo '<input type="text" name="shariff3UU_basic[services]" value="' . esc_html($services) . '" size="50" placeholder="twitter|facebook|googleplus|info">';
	echo '<p><code>facebook|twitter|googleplus|whatsapp|pinterest|xing|linkedin|reddit|stumbleupon|flattr|mailform|mailto|printer|info</code></p>'; 
	echo '<p>' . __( 'Use the pipe sign | (Alt Gr + &lt; or &#8997; + 7) between two or more services.', 'shariff3UU' ) . '</p>';
}

// add after
function shariff3UU_multiplecheckbox_add_after_render() {
	// add after all posts
	echo '<p><input type="checkbox" name="shariff3UU_basic[add_after][posts]" ';
	if ( isset( $GLOBALS['shariff3UU_basic']['add_after']['posts'] ) ) echo checked( $GLOBALS['shariff3UU_basic']['add_after']['posts'], 1, 0 );
	echo ' value="1">' . __('Posts', 'shariff3UU') . '</p>';
	
	// add after all posts (blog page)
	echo '<p><input type="checkbox" name="shariff3UU_basic[add_after][posts_blogpage]" ';
	if ( isset( $GLOBALS["shariff3UU_basic"]["add_after"]["posts_blogpage"] ) ) echo checked( $GLOBALS["shariff3UU_basic"]["add_after"]["posts_blogpage"], 1, 0 );
	echo ' value="1">' . __('Posts (blog page)', 'shariff3UU') . '</p>';
	
	// add after all pages
	echo '<p><input type="checkbox" name="shariff3UU_basic[add_after][pages]" ';
	if ( isset( $GLOBALS["shariff3UU_basic"]["add_after"]["pages"] ) ) echo checked( $GLOBALS["shariff3UU_basic"]["add_after"]["pages"], 1, 0 );
	echo ' value="1">' . __('Pages', 'shariff3UU') . '</p>';
	
	// add after all custom type (e.g. product pages)
	echo '<p><input type="checkbox" name="shariff3UU_basic[add_after][custom_type]" ';
	if ( isset( $GLOBALS["shariff3UU_basic"]["add_after"]["custom_type"] ) ) echo checked( $GLOBALS["shariff3UU_basic"]["add_after"]["custom_type"], 1, 0 );
	echo ' value="1">' . __('Extension pages (e.g. product sites)', 'shariff3UU') . '</p>';
}

// add before
function shariff3UU_multiplecheckbox_add_before_render() {
	// Add before all posts
	echo '<p><input type="checkbox" name="shariff3UU_basic[add_before][posts]" ';
	if ( isset( $GLOBALS['shariff3UU_basic']['add_before']['posts'] ) ) echo checked( $GLOBALS['shariff3UU_basic']['add_before']['posts'], 1, 0 );
	echo ' value="1">' . __('Posts', 'shariff3UU') . '</p>';
	
	// Add before all posts (blog page)
	echo '<p><input type="checkbox" name="shariff3UU_basic[add_before][posts_blogpage]" ';
	if ( isset( $GLOBALS["shariff3UU_basic"]["add_before"]["posts_blogpage"] ) ) echo checked( $GLOBALS["shariff3UU_basic"]["add_before"]["posts_blogpage"], 1, 0 );
	echo ' value="1">' . __('Posts (blog page)', 'shariff3UU') . '</p>';
	
	// Add before all pages
	echo '<p><input type="checkbox" name="shariff3UU_basic[add_before][pages]" ';
	if ( isset( $GLOBALS["shariff3UU_basic"]["add_before"]["pages"] ) ) echo checked( $GLOBALS["shariff3UU_basic"]["add_before"]["pages"], 1, 0 );
	echo ' value="1">' . __('Pages', 'shariff3UU') . '</p>';
}

// disable on password protected posts
function shariff3UU_checkbox_disable_on_protected_render() {
	echo '<input type="checkbox" name="shariff3UU_basic[disable_on_protected]" ';
	if ( isset( $GLOBALS["shariff3UU_basic"]["disable_on_protected"] ) ) echo checked( $GLOBALS["shariff3UU_basic"]["disable_on_protected"], 1, 0 );
	echo ' value="1">';
}

// share counts
function shariff3UU_checkbox_backend_render() {
	// to check that the backend works
	// http://[your_host]/wp-content/plugins/shariff/backend/index.php?url=http%3A%2F%2F[your_host]
	// should give an array or "[ ]"

	// check PHP version
	if ( version_compare( PHP_VERSION, '5.4.0' ) < 1 ) {
		echo __( 'PHP-Version 5.4 or better is needed to enable the statistic functionality.', 'shariff3UU');
	}
	else {
		echo '<input type="checkbox" name="shariff3UU_basic[backend]" ';
		if ( isset( $GLOBALS['shariff3UU_basic']['backend'] ) ) {
			echo checked( $GLOBALS['shariff3UU_basic']['backend'], 1, 0 );
		}
		echo ' value="1">';
	}
}

// design options

// description design options
function shariff3UU_design_section_callback(){
	echo __( 'This configures the default design of the Shariff buttons. Most options can be overwritten for single posts or pages with the options within the <code>[shariff]</code> shorttag. For more information have a look at the ', 'shariff3UU');
	echo '<a href="' . get_bloginfo('wpurl') . '/wp-admin/options-general.php?page=shariff3uu&tab=help">';
	echo __( 'Help Section</a> and the ', 'shariff3UU' );
	echo '<a href="https://wordpress.org/support/plugin/shariff/" target="_blank">';
	echo __( 'Support Forum</a>.', 'shariff3UU' );
}

// language
function shariff3UU_select_language_render() {
	$options = $GLOBALS["shariff3UU_design"]; 
	if ( ! isset( $options["language"] ) ) $options["language"] = '';
	echo '<select name="shariff3UU_design[language]">
	<option value="" ' .   selected( $options["language"], "", 0 ) . '>' . __( "auto", "shariff3UU") . '</option>
	<option value="en" ' . selected( $options["language"], "en", 0 ) . '>English</option>
	<option value="de" ' . selected( $options["language"], "de", 0 ) . '>Deutsch</option>
	<option value="fr" ' . selected( $options["language"], "fr", 0 ) . '>Français</option>
	<option value="es" ' . selected( $options["language"], "es", 0 ) . '>Español</option>
	<option value="zh" ' . selected( $options["language"], "zh", 0 ) . '>Chinese</option>
	<option value="hr" ' . selected( $options["language"], "hr", 0 ) . '>Croatian</option>
	<option value="da" ' . selected( $options["language"], "da", 0 ) . '>Danish</option>
	<option value="nl" ' . selected( $options["language"], "nl", 0 ) . '>Dutch</option>
	<option value="fi" ' . selected( $options["language"], "fi", 0 ) . '>Finnish</option>
	<option value="it" ' . selected( $options["language"], "it", 0 ) . '>Italian</option>
	<option value="ja" ' . selected( $options["language"], "ja", 0 ) . '>Japanese</option>
	<option value="ko" ' . selected( $options["language"], "ko", 0 ) . '>Korean</option>
	<option value="no" ' . selected( $options["language"], "no", 0 ) . '>Norwegian</option>
	<option value="pl" ' . selected( $options["language"], "pl", 0 ) . '>Polish</option>
	<option value="pt" ' . selected( $options["language"], "pt", 0 ) . '>Portuguese</option>
	<option value="ro" ' . selected( $options["language"], "ro", 0 ) . '>Romanian</option>
	<option value="ru" ' . selected( $options["language"], "ru", 0 ) . '>Russian</option>
	<option value="sk" ' . selected( $options["language"], "sk", 0 ) . '>Slovak</option>
	<option value="sl" ' . selected( $options["language"], "sl", 0 ) . '>Slovene</option>
	<option value="sr" ' . selected( $options["language"], "sr", 0 ) . '>Serbian</option>
	<option value="sv" ' . selected( $options["language"], "sv", 0 ) . '>Swedish</option>
	<option value="tr" ' . selected( $options["language"], "tr", 0 ) . '>Turkish</option>
	</select>';
}

// theme
function shariff3UU_radio_theme_render() {
	$options = $GLOBALS["shariff3UU_design"]; 
	if ( ! isset( $options["theme"] ) ) $options["theme"] = "";
	$plugins_url = plugins_url();
	echo '<div class="shariff_options-table">
	<div class="shariff_options-row"><div class="shariff_options-cell"><input type="radio" name="shariff3UU_design[theme]" value="" ' .      checked( $options["theme"], "", 0 ) .      '>default</div><div class="shariff_options-cell"><img src="' . $plugins_url . '/shariff/pictos/defaultBtns.png"></div></div>
	<div class="shariff_options-row"><div class="shariff_options-cell"><input type="radio" name="shariff3UU_design[theme]" value="color" ' .  checked( $options["theme"], "color", 0 ) . '>color</div><div class="shariff_options-cell"><img src="' .    $plugins_url . '/shariff/pictos/colorBtns.png"></div></div>
	<div class="shariff_options-row"><div class="shariff_options-cell"><input type="radio" name="shariff3UU_design[theme]" value="grey" ' .  checked( $options["theme"], "grey", 0 )  . '>grey</div><div class="shariff_options-cell"><img src="' .    $plugins_url . '/shariff/pictos/greyBtns.png"></div></div>
	<div class="shariff_options-row"><div class="shariff_options-cell"><input type="radio" name="shariff3UU_design[theme]" value="white" ' . checked( $options["theme"], "white", 0 ) . '>white</div><div class="shariff_options-cell"><img src="' .    $plugins_url . '/shariff/pictos/whiteBtns.png"></div></div>
	<div class="shariff_options-row"><div class="shariff_options-cell"><input type="radio" name="shariff3UU_design[theme]" value="round" ' . checked( $options["theme"], "round", 0 ) . '>round</div><div class="shariff_options-cell"><img src="' .   $plugins_url . '/shariff/pictos/roundBtns.png"></div></div>
	</div>';
}

// button size
function shariff3UU_checkbox_buttonsize_render() {
	$plugins_url = plugins_url();
	echo '<input type="checkbox" name="shariff3UU_design[buttonsize]" ';
	if ( isset( $GLOBALS["shariff3UU_design"]["buttonsize"] ) ) echo checked( $GLOBALS["shariff3UU_design"]["buttonsize"], 1, 0 );
	echo ' value="1"><img src="'. $plugins_url .'/shariff/pictos/smallBtns.png" align="middle">';
}

// button stretch
function shariff3UU_checkbox_buttonstretch_render() {
	$plugins_url = plugins_url();
	echo '<input type="checkbox" name="shariff3UU_design[buttonstretch]" ';
	if ( isset( $GLOBALS["shariff3UU_design"]["buttonstretch"] ) ) echo checked( $GLOBALS["shariff3UU_design"]["buttonstretch"], 1, 0 );
	echo ' value="1">';
}

// vertical
function shariff3UU_checkbox_vertical_render() {
	$plugins_url = plugins_url();
	echo '<input type="checkbox" name="shariff3UU_design[vertical]" ';
	if ( isset( $GLOBALS["shariff3UU_design"]["vertical"] ) ) echo checked( $GLOBALS["shariff3UU_design"]["vertical"], 1, 0 );
	echo ' value="1"><img src="'. $plugins_url .'/shariff/pictos/verticalBtns.png" align="top">';
}

// alignment
function shariff3UU_radio_align_render() {
	$options = $GLOBALS['shariff3UU_design']; 
	if ( ! isset( $options['align'] ) ) $options['align'] = 'flex-start';
	echo '<div class="shariff_options-table"><div class="shariff_options-row">
	<div class="shariff_options-cell"><input type="radio" name="shariff3UU_design[align]" value="flex-start" ' . checked( $options["align"], "flex-start", 0 ) . '>' . __( "left", "shariff3UU" ) . '</div>
	<div class="shariff_options-cell"><input type="radio" name="shariff3UU_design[align]" value="center" ' .     checked( $options["align"], "center", 0 )     . '>' . __( "center", "shariff3UU" ) . '</div>
	<div class="shariff_options-cell"><input type="radio" name="shariff3UU_design[align]" value="flex-end" ' .   checked( $options["align"], "flex-end", 0 )   . '>' . __( "right", "shariff3UU" ) . '</div>
	</div></div>';
}

// alignment widget
function shariff3UU_radio_align_widget_render() {
	$options = $GLOBALS['shariff3UU_design']; 
	if ( ! isset( $options['align_widget'] ) ) $options['align_widget'] = 'flex-start';
	echo '<div class="shariff_options-table"><div class="shariff_options-row">
	<div class="shariff_options-cell"><input type="radio" name="shariff3UU_design[align_widget]" value="flex-start" ' . checked( $options["align_widget"], "flex-start", 0 ) . '>' . __( "left", "shariff3UU" ) . '</div>
	<div class="shariff_options-cell"><input type="radio" name="shariff3UU_design[align_widget]" value="center" ' .     checked( $options["align_widget"], "center", 0 )     . '>' . __( "center", "shariff3UU" ) . '</div>
	<div class="shariff_options-cell"><input type="radio" name="shariff3UU_design[align_widget]" value="flex-end" ' .   checked( $options["align_widget"], "flex-end", 0 )   . '>' . __( "right", "shariff3UU" ) . '</div>
	</div></div>';
}

// headline
function shariff3UU_text_headline_render() {
	if ( isset( $GLOBALS["shariff3UU_design"]["headline"] ) ) {
		$headline = $GLOBALS["shariff3UU_design"]["headline"];
	}
	else {
		$headline = '';
	}
	echo '<input type="text" name="shariff3UU_design[headline]" value="' . esc_html( $headline ) . '" size="50" placeholder="' . __( "Share this post", "shariff3UU" ) . '">';
	echo __( '<p>Basic HTML as well as style and class attributes are allowed - e.g. <code>&lt;h1 class="shariff_headline"&gt;Share this post&lt;/h1&gt;</code></p>', "shariff3UU" );
}

// custom css
function shariff3UU_text_style_render() {
	if ( isset( $GLOBALS["shariff3UU_design"]["style"] ) ) {
		$style = $GLOBALS["shariff3UU_design"]["style"];
	}
	else {
		$style = '';
	}
	echo '<input type="text" name="shariff3UU_design[style]" value="' . esc_html($style) . '" size="50" placeholder="' . __( "More information in the FAQ.", "shariff3UU" ) . '">';
}

// advanced options

// description advanced options
function shariff3UU_advanced_section_callback(){
	echo __( 'This configures the advanced options of Shariff regarding specific services. If you are unsure about an option, take a look at the ', 'shariff3UU' );
	echo '<a href="' . get_bloginfo('wpurl') . '/wp-admin/options-general.php?page=shariff3uu&tab=help">';
	echo __( 'Help Section</a> and the ', 'shariff3UU' );
	echo '<a href="https://wordpress.org/support/plugin/shariff/" target="_blank">';
	echo __( 'Support Forum</a>.', 'shariff3UU' );
}

// info url
function shariff3UU_text_info_url_render() {
	if ( isset( $GLOBALS["shariff3UU_advanced"]["info_url"] ) ) {
		$info_url = $GLOBALS["shariff3UU_advanced"]["info_url"];
	}
	else {
		$info_url = '';  
	}
	echo '<input type="text" name="shariff3UU_advanced[info_url]" value="'. esc_html($info_url) .'" size="50" placeholder="http://ct.de/-2467514">';
}

// twitter via
function shariff3UU_text_twittervia_render() {
	if ( isset( $GLOBALS["shariff3UU_advanced"]["twitter_via"] ) ) {
		$twitter_via = $GLOBALS["shariff3UU_advanced"]["twitter_via"];
	}
	else {
		$twitter_via = '';
	}
	echo '<input type="text" name="shariff3UU_advanced[twitter_via]" value="' . $twitter_via . '" size="50" placeholder="' . __( 'username', 'shariff3UU' ) . '">';
}

// flattr username
function shariff3UU_text_flattruser_render() {
	if ( isset($GLOBALS["shariff3UU_advanced"]["flattruser"]) ) {
		$flattruser = $GLOBALS["shariff3UU_advanced"]["flattruser"];
	}
	else { 
		$flattruser = '';
	}
	echo '<input type="text" name="shariff3UU_advanced[flattruser]" value="'. $flattruser .'" size="50" placeholder="' . __( 'username', 'shariff3UU' ) . '">';
}

// pinterest default image
function shariff3UU_text_default_pinterest_render() {
	$options = $GLOBALS["shariff3UU_advanced"]; 
	if ( ! isset( $options["default_pinterest"] ) ) $options["default_pinterest"] = '';
	echo '<div><input type="text" name="shariff3UU_advanced[default_pinterest]" value="' . $options["default_pinterest"] . '" id="image_url" class="regular-text"><input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="' . __( 'Choose image', 'shariff3UU' ) . '"></div>';
	echo '<script type="text/javascript">
	jQuery(document).ready(function($){
		$("#upload-btn").click(function(e) {
			e.preventDefault();
			var image = wp.media({ 
				title: "Choose image",
				// mutiple: true if you want to upload multiple files at once
				multiple: false
			}).open()
			.on("select", function(e){
				// This will return the selected image from the Media Uploader, the result is an object
				var uploaded_image = image.state().get("selection").first();
				// We convert uploaded_image to a JSON object to make accessing it easier
				// Output to the console uploaded_image
				console.log(uploaded_image);
				var image_url = uploaded_image.toJSON().url;
				// Let"s assign the url value to the input field
				$("#image_url").val(image_url);
			});
		});
	});
	</script>';
}

// Facebook App ID
function shariff3UU_text_fb_id_render() {
	if ( isset($GLOBALS["shariff3UU_advanced"]["fb_id"]) ) {
		$fb_id = $GLOBALS["shariff3UU_advanced"]["fb_id"];
	}
	else { 
		$fb_id = '';
	}
	echo '<input type="text" name="shariff3UU_advanced[fb_id]" value="'. $fb_id .'" size="50" placeholder="1234567891234567">';
}

// Facebook App Secret
function shariff3UU_text_fb_secret_render() {
	if ( isset($GLOBALS["shariff3UU_advanced"]["fb_secret"]) ) {
		$fb_secret = $GLOBALS["shariff3UU_advanced"]["fb_secret"];
	}
	else { 
		$fb_secret = '';
	}
	echo '<input type="text" name="shariff3UU_advanced[fb_secret]" value="'. $fb_secret .'" size="50" placeholder="123abc456def789123456789ghi12345">';
}

// ttl
function shariff3UU_number_ttl_render() {
	if ( isset($GLOBALS["shariff3UU_advanced"]["ttl"]) ) {
		$ttl = $GLOBALS["shariff3UU_advanced"]["ttl"];
	}
	else { 
		$ttl = '';
	}
	echo '<input type="number" name="shariff3UU_advanced[ttl]" value="'. $ttl .'" maxlength="4" min="60" max="7200" placeholder="60" style="width: 75px">';
}

// mailform options

// description advanced options
function shariff3UU_mailform_section_callback() {
	echo __( "The mail form can be completely disabled, if not needed. Otherwise, it is recommended to configure a default sender e-mail address from <u>your domain</u> that actually exists, to prevent spam filters from blocking the e-mails.", "shariff3UU" );
}

// disable mailform
function shariff3UU_checkbox_disable_mailform_render() {
	echo '<input type="checkbox" name="shariff3UU_mailform[disable_mailform]" ';
	if ( isset( $GLOBALS["shariff3UU_mailform"]["disable_mailform"] ) ) echo checked( $GLOBALS["shariff3UU_mailform"]["disable_mailform"], 1, 0 );
	echo ' value="1">';
}

// require sender e-mail address
function shariff3UU_checkbox_require_sender_render() {
	echo '<input type="checkbox" name="shariff3UU_mailform[require_sender]" ';
	if ( isset( $GLOBALS["shariff3UU_mailform"]["require_sender"] ) ) echo checked( $GLOBALS["shariff3UU_mailform"]["require_sender"], 1, 0 );
	echo ' value="1">';
}

// mailform language
function shariff3UU_select_mailform_language_render() {
	$options = $GLOBALS["shariff3UU_mailform"]; 
	if ( ! isset( $options["mailform_language"] ) ) $options["mailform_language"] = 'auto';
	echo '<select name="shariff3UU_mailform[mailform_language]" style="min-width:110px">
	<option value="auto" ' . selected( $options["mailform_language"], "auto", 0 ) . '>' . __( "auto", "shariff3UU") . '</option>
	<option value="EN" ' . selected( $options["mailform_language"], "EN", 0 ) . '>English</option>
	<option value="DE" ' . selected( $options["mailform_language"], "DE", 0 ) . '>Deutsch</option></select>';
}

// add post content
function shariff3UU_checkbox_mail_add_post_content_render() {
	echo '<input type="checkbox" name="shariff3UU_mailform[mail_add_post_content]" ';
	if ( isset( $GLOBALS["shariff3UU_mailform"]["mail_add_post_content"] ) ) echo checked( $GLOBALS["shariff3UU_mailform"]["mail_add_post_content"], 1, 0 );
	echo ' value="1">';
}
	
// sender name		
function shariff3UU_text_mail_sender_name_render() {
	if ( isset( $GLOBALS["shariff3UU_mailform"]["mail_sender_name"] ) ) {
		$mail_sender_name = $GLOBALS["shariff3UU_mailform"]["mail_sender_name"];
	}
	else {
		$mail_sender_name = "";
	}
	// get blog title
	$blog_title = get_bloginfo( 'name' );
	echo '<input type="text" name="shariff3UU_mailform[mail_sender_name]" value="' . esc_html($mail_sender_name) . '" size="50" placeholder="' . $blog_title . '">';
}

// sender address
function shariff3UU_text_mail_sender_from_render() {
	if ( isset( $GLOBALS["shariff3UU_mailform"]["mail_sender_from"] ) ) {
		$mail_sender_from = $GLOBALS["shariff3UU_mailform"]["mail_sender_from"];
	}
	else {
		$mail_sender_from = "";
	}
	// get blog domain
	$blog_domain = get_bloginfo( 'url');
	// in case scheme relative URI is passed, e.g., //www.google.com/
	$input = trim($blog_domain, '/');
	// If scheme not included, prepend it
	if ( ! preg_match( '#^http(s)?://#', $input ) ) {
    	$input = 'http://' . $input;
	}
	$urlParts = parse_url($input);
	// remove www
	$domain = preg_replace('/^www\./', '', $urlParts['host']);
	echo '<input type="email" name="shariff3UU_mailform[mail_sender_from]" value="' . esc_html($mail_sender_from) . '" size="50" placeholder="wordpress@' . $domain .'">';
}

// help section

// description advanced options
function shariff3UU_help_section_callback() {
	echo __( '<p>The WordPress plugin "Shariff Wrapper" has been developed by <a href="http://www.datenverwurstungszentrale.com" target="_blank">3UU</a> and <a href="https://twitter.com/jplambeck" target=_blank">JP</a> in order to help protect the privacy of your visitors. It is based on the original Shariff buttons developed by the German computer magazin <a href="http://ct.de/shariff" target="_blank">c\'t</a> that fullfill the strict data protection laws in Germany. If you need any help with the plugin, take a look at the <a href="https://wordpress.org/plugins/shariff/faq/" target="_blank">Frequently Asked Questions (FAQ)</a> and the <a href="https://wordpress.org/support/plugin/shariff" target="_blank">Support Forum</a>. For up to date news about the plugin you can also follow <a href="https://twitter.com/jplambeck" target=_blank">@jplambeck</a> on Twitter.</p>', 'shariff3UU' );
	echo __( '<p>If you contact us about a problem with the share counts, please <u>always</u> include the information provided in the', 'shariff3UU' );
	echo ' <a href="options-general.php?page=shariff3uu&tab=basic">';
	echo __( 'status section</a>! This will help to speed up the process.</p>', 'shariff3UU' );

	echo __( '<p>This is a list of all available options for the <code>[shariff]</code> shortcode:</p>', 'shariff3UU' );
	// shortcode table
	echo '<div class="shariff_shortcode_table">';
		// head
		echo '<div class="shariff_shortcode_row_head">';
			echo '<div class="shariff_shortcode_cell_name-option">' . __( 'Name', 'shariff3UU' ) . '</div>';
			echo '<div class="shariff_shortcode_cell_name-option">' . __( 'Options', 'shariff3UU' ) . '</div>';
			echo '<div class="shariff_shortcode_cell_default">' . __( 'Default', 'shariff3UU' ) . '</div>';
			echo '<div class="shariff_shortcode_cell_example">' . __( 'Example', 'shariff3UU' ) . '</div>';
			echo '<div class="shariff_shortcode_cell_description">' . __( 'Description', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// services
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">services</div>';
			echo '<div class="shariff_shortcode_cell">facebook<br>twitter<br>googleplus<br>whatsapp<br>pinterest<br>xing<br>linkedin<br>reddit<br>stumbleupon<br>flattr<br>mailform<br>mailto<br>printer<br>info</div>';
			echo '<div class="shariff_shortcode_cell">twitter|facebook|googleplus|info</div>';
			echo '<div class="shariff_shortcode_cell">[shariff theme="facebook|twitter|mailform"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Determines which buttons to show and in which order.', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// backend
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">backend</div>';
			echo '<div class="shariff_shortcode_cell">on<br>off</div>';
			echo '<div class="shariff_shortcode_cell">off</div>';
			echo '<div class="shariff_shortcode_cell">[shariff backend="on"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Enables share counts on the buttons.', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// theme
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">theme</div>';
			echo '<div class="shariff_shortcode_cell">default<br>color<br>grey<br>white<br>round</div>';
			echo '<div class="shariff_shortcode_cell">default</div>';
			echo '<div class="shariff_shortcode_cell">[shariff theme="round"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Determines the main design of the buttons.', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// button size
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">buttonsize</div>';
			echo '<div class="shariff_shortcode_cell">big<br>small</div>';
			echo '<div class="shariff_shortcode_cell">big</div>';
			echo '<div class="shariff_shortcode_cell">[shariff buttonsize="small"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Small reduces the size of all buttons by 30%, regardless of theme.', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// orientation
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">orientation</div>';
			echo '<div class="shariff_shortcode_cell">horizontal<br>vertical</div>';
			echo '<div class="shariff_shortcode_cell">horizontal</div>';
			echo '<div class="shariff_shortcode_cell">[shariff orientation="vertical"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Changes the orientation of the buttons.', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// language
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">lang</div>';
			echo '<div class="shariff_shortcode_cell">da, de, en, es, fi, fr, hr, hu, it, ja, ko, nl, no, pl, pt, ro, ru, sk, sl, sr, sv, tr, zh</div>';
			echo '<div class="shariff_shortcode_cell">automatically selected by the browser</div>';
			echo '<div class="shariff_shortcode_cell">[shariff lang="de"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Changes the language of the share buttons.', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// headline
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">headline</div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell">[shariff headline="&lt;hr style=\'margin:20px 0\'&gt;&lt;p&gt;' . __( 'Please share this post:', 'shariff3UU' ) . '&lt;/p&gt;"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Adds a headline above the Shariff buttons. Basic HTML as well as style and class attributes can be used. To remove a headline set on the plugins options page use headline="".', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// twitter_via
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">twitter_via</div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell">[shariff twitter_via="your_twittername"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Sets the Twitter via tag.', 'shariff3UU' ) . '</div>';
		echo '</div>';	
		// flattruser
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">flattruser</div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell">[shariff flattruser="your_username"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Sets the Flattr username.', 'shariff3UU' ) . '</div>';
		echo '</div>';		
		// media
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">media</div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'The post featured image or the first image of the post.</div>', 'shariff3UU' );
			echo '<div class="shariff_shortcode_cell">[shariff media="http://www.mydomain.com/image.jpg"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Determines the default image to share for Pinterest, if no other usable image is found.', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// info_url
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">info_url</div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell">http://ct.de/-2467514</div>';
			echo '<div class="shariff_shortcode_cell">[shariff info_url="http://www.mydomain.com/shariff-buttons"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Sets a custom link for the info button.', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// url
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">url</div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'The url of the current post or page.</div>', 'shariff3UU' );
			echo '<div class="shariff_shortcode_cell">[shariff url="http://www.mydomain.com"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Changes the url to share. Only for special use cases.', 'shariff3UU' ) . '</div>';
		echo '</div>';
		// title
		echo '<div class="shariff_shortcode_row">';
			echo '<div class="shariff_shortcode_cell">title</div>';
			echo '<div class="shariff_shortcode_cell"></div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'The title of the current post or page.</div>', 'shariff3UU' );
			echo '<div class="shariff_shortcode_cell">[shariff title="My WordPress Blog"]</div>';
			echo '<div class="shariff_shortcode_cell">' . __( 'Changes the title to share. Only for special use cases.', 'shariff3UU' ) . '</div>';
		echo '</div>';

	echo '</div>';
}

// status section

// statistic status on fist tab (basic) only
function shariff3UU_status_section_callback() {
	// status table
	echo '<div class="shariff_status-main-table">';
	// statistic row
	echo '<div class="shariff_status-row">';
	echo '<div class="shariff_status-first-cell">' . __( 'Statistic:', 'shariff3UU' ) . '</div>';
	// check if statistic is enabled
	if( ! isset( $GLOBALS['shariff3UU_basic']['backend'] ) ) {
		// statistic disabled message
		echo '<div class="shariff_status-cell"><span class="shariff_status-disabled">' . __( 'Disabled', 'shariff3UU' ) . '</span></div>';
		// end statistic row, if statistic is disabled
		echo '</div>';
	}
	else {
		// check if constant for the cache dir is defined in wp-config.php
		if ( defined( 'SHARIFF_BACKEND_TMPDIR' ) ) {
			$cache_dir = SHARIFF_BACKEND_TMPDIR;
		}
		// if constant is not set, we use the upload dir of WP
		if ( empty( $cache_dir ) ) {
			$upload_dir = wp_upload_dir();
			$cache_dir = $upload_dir['basedir'] . '/shariff3uu_cache';
			// if it doesn't exit, try to create it
			if( ! file_exists( $cache_dir ) ) {
				wp_mkdir_p( $cache_dir );
			}
		}
		// check if cache dir is usuable and backend shows results
		$wp_url = get_bloginfo('url');
		$wp_url = preg_replace('#^https?://#', '', $wp_url);
		$backend_testurl = plugin_dir_url( __FILE__ ) . 'backend/index.php?url=http%3A%2F%2F' . $wp_url;
		$backend_output = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( $backend_testurl ) ) );
		$backend_output_json = json_decode( $backend_output, true );
		if ( is_writable( $cache_dir ) && ( isset( $backend_output_json['googleplus'] ) && $backend_output_json['googleplus'] >= '0' ) || ( isset( $backend_output_json['facebook'] ) && $backend_output_json['facebook'] >= '0' ) ) {
			// statistic working message
			echo '<div class="shariff_status-cell">';
				// working message table
				echo '<div class="shariff_status-table">';
				echo '<div class="shariff_status-row"><div class="shariff_status-cell"><span class="shariff_status-ok">' . __( 'OK', 'shariff3UU' ) . '</span></div></div>';
				echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Cache directory is writable.', 'shariff3UU' ) . '</div></div>';
				echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Using the following directory: ', 'shariff3UU' ) . $cache_dir . '</div></div>';
				echo '</div>';
			echo '</div>';
			// end statistic row, if working correctly
			echo '</div>';
			// Facebook row
			echo '<div class="shariff_status-row">';
			echo '<div class="shariff_status-cell">' . __( 'Facebook:', 'shariff3UU' ) . '</div>';
			// check if Facebook is responding correctly (no rate limits actice, etc.)
			$blog_url = urlencode( esc_url( get_bloginfo('url') ) );
			$facebook = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://graph.facebook.com/fql?q=SELECT%20share_count%20FROM%20link_stat%20WHERE%20url="' . $blog_url . '"' ) ) );
			$facebook = json_decode( $facebook, true );
			if ( isset( $facebook['data']['0']['share_count'] ) ) {
				// Facebook working message
				echo '<div class="shariff_status-cell">';
					// working message table
					echo '<div style="display: table">';
					echo '<div class="shariff_status-row"><div class="shariff_status-cell"><span class="shariff_status-ok">' . __( 'OK', 'shariff3UU' ) . '</span></div></div>';
					echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Current share count for ', 'shariff3UU' ) . urldecode( $blog_url ) . ': ' . absint( $facebook['data']['0']['share_count'] ) . '</div></div>';
					echo '</div>';
				echo '</div>';
				// end Facebook row, if working correctly
				echo '</div>';
			}
			elseif ( isset( $facebook['error']['message'] ) ) {
				// Facebook API error message
				echo '<div class="shariff_status-cell">';
					// error message table
					echo '<div class="shariff_status-table">';
					echo '<div class="shariff_status-row"><div class="shariff_status-cell"><span class="shariff_status-error">' . __( 'Error', 'shariff3UU' ) . '</span></div></div>';
					echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Message:', 'shariff3UU' ) . '</div><div style="display: table-cell">' . esc_html( $facebook['error']['message'] ) . '</div></div>';
					echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Type:', 'shariff3UU' ) . '</div><div class="shariff_status-cell">' . esc_html( $facebook['error']['type'] ) . '</div></div>';
					echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Code:', 'shariff3UU' ) . '</div><div class="shariff_status-cell">' . esc_html( $facebook['error']['code'] ) . '</div></div>';
					echo '</div>';
				echo '</div>';
				// end Facebook row, if not working correctly
				echo '</div>';
			}
			// Facebook Graph API ID row
			echo '<div class="shariff_status-row">';
			echo '<div class="shariff_status-cell">' . __( 'Facebook API (ID):', 'shariff3UU' ) . '</div>';
			// credentials provided?
			if ( ! isset( $GLOBALS['shariff3UU_advanced']['fb_id'] ) || ! isset( $GLOBALS['shariff3UU_advanced']['fb_secret'] ) ) {
				// no credentials
				echo '<div class="shariff_status-cell">';
					echo '<div class="shariff_status-table">';
					echo '<div class="shariff_status-row"><div class="shariff_status-cell"><span class="shariff_status-disabled">' . __( 'Not configured', 'shariff3UU' ) . '</span></div></div>';
					echo '</div>';
				echo '</div>';
				// end Graph API ID row, if not configured
				echo '</div>';
			}
			else {
				// app_id and secret
				$fb_app_id = $GLOBALS['shariff3UU_advanced']['fb_id'];
				$fb_app_secret = $GLOBALS['shariff3UU_advanced']['fb_secret'];
				// check if Facebook Graph API ID is responding correctly (no rate limits actice, credentials ok, etc.)
				$blog_url = urlencode( esc_url( get_bloginfo('url') ) );
				// get fb access token
				$fb_token = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://graph.facebook.com/oauth/access_token?client_id=' .  $fb_app_id . '&client_secret=' . $fb_app_secret . '&grant_type=client_credentials' ) ) );
				// use token to get share counts
				$facebookID = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://graph.facebook.com/v2.2/?id=' . $blog_url . '&' . $fb_token ) ) );
				$facebookID = json_decode( $facebookID, true );
				$fb_token = json_decode( $fb_token, true );
				// is it working?
				if ( isset( $facebookID['share']['share_count'] ) ) {
					// Facebook Graph API ID working message
					echo '<div class="shariff_status-cell">';
						// working message table
						echo '<div style="display: table">';
						echo '<div class="shariff_status-row"><div class="shariff_status-cell"><span class="shariff_status-ok">' . __( 'OK', 'shariff3UU' ) . '</span></div></div>';
						echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Current share count for ', 'shariff3UU' ) . urldecode( $blog_url ) . ': ' . absint( $facebookID['share']['share_count'] ) . '</div></div>';
						echo '</div>';
					echo '</div>';
					// end Facebook Graph API ID row, if working correctly
					echo '</div>';
				}
				elseif ( isset( $facebookID['error']['message'] ) ) {
					// Facebook Graph API ID error message
					echo '<div class="shariff_status-cell">';
						// error message table
						echo '<div class="shariff_status-table">';
						echo '<div class="shariff_status-row"><div class="shariff_status-cell"><span class="shariff_status-error">' . __( 'Error', 'shariff3UU' ) . '</span></div></div>';
						echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Message:', 'shariff3UU' ) . '</div><div style="display: table-cell">' . esc_html( $facebookID['error']['message'] ) . '</div></div>';
						echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Type:', 'shariff3UU' ) . '</div><div class="shariff_status-cell">' . esc_html( $facebookID['error']['type'] ) . '</div></div>';
						echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Code:', 'shariff3UU' ) . '</div><div class="shariff_status-cell">' . esc_html( $facebookID['error']['code'] ) . '</div></div>';
						echo '</div>';
					echo '</div>';
					// end Facebook Graph API ID row, if not working correctly
					echo '</div>';
				}
				elseif ( isset( $fb_token['error']['message'] ) ) {
					// Facebook Graph API ID auth error message
					echo '<div class="shariff_status-cell">';
						// error message table
						echo '<div class="shariff_status-table">';
						echo '<div class="shariff_status-row"><div class="shariff_status-cell"><span class="shariff_status-error">' . __( 'Error', 'shariff3UU' ) . '</span></div></div>';
						echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Message:', 'shariff3UU' ) . '</div><div style="display: table-cell">' . esc_html( $fb_token['error']['message'] ) . '</div></div>';
						echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Type:', 'shariff3UU' ) . '</div><div class="shariff_status-cell">' . esc_html( $fb_token['error']['type'] ) . '</div></div>';
						echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Code:', 'shariff3UU' ) . '</div><div class="shariff_status-cell">' . esc_html( $fb_token['error']['code'] ) . '</div></div>';
						echo '</div>';
					echo '</div>';
					// end Facebook Graph API ID row, if not working correctly bc of auth error
					echo '</div>';
				}

			}
		}
		else {
			// statistic error message
			echo '<div style="display: table-cell">';
				// error message table
				echo '<div class="shariff_status-table">';
				echo '<div class="shariff_status-row"><div class="shariff_status-cell"><span class="shariff_status-error">' . __( 'Error', 'shariff3UU' ) . '</span></div></div>';
				if ( ! is_writable( $cache_dir ) ) {
					echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Cache directory is not writable or cannot be found.', 'shariff3UU' ) . '</div></div>';
					echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Tried using the following directory: ', 'shariff3UU' ) . $cache_dir . '</div></div>';
				}
				elseif ( ! isset( $backend_output_json['googleplus'] ) || ( isset( $backend_output_json['googleplus'] ) && $backend_output_json['googleplus'] >= '0' ) || ( ! isset( $backend_output_json['facebook'] ) || ( isset( $backend_output_json['facebook'] ) && $backend_output_json['facebook'] >= '0' ) ) ) {
					echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . __( 'Backend error.', 'shariff3UU' ) . '</div></div>';
					echo '<div class="shariff_status-row"><div class="shariff_status-cell">' . esc_html( $backend_output ) . '</div></div>';
				}

				echo '</div>';
			echo '</div>';
			// end statistic row, if not working correctly
			echo '</div>';
		}
	}
	// end status table
	echo '</div>';
}

// render the plugin option page
function shariff3UU_options_page() { 
	// the <div> with the class "wrap" makes sure that admin messages are displayed below the title and not above
	echo '<div class="wrap">';

	// title
	echo '<h2>Shariff ' . $GLOBALS["shariff3UU_basic"]["version"] . '</h2>';
	
	// start the form
	echo '<form class="shariff" action="options.php" method="post">';
	
	// hidden version entry, so it will get saved upon updating the options
	echo '<input type="hidden" name="shariff3UU_basic[version]" value="'. $GLOBALS["shariff3UU_basic"]["version"] .'">';
	
	// determine active tab
	if ( isset( $_GET['tab'] ) ) {
		$active_tab = $_GET['tab'];
	}
	else {
		$active_tab = 'basic';
	}
	
	// tabs
	echo '<h2 class="nav-tab-wrapper">';
		// basic
		echo '<a href="?page=shariff3uu&tab=basic" class="nav-tab ';
		if ( $active_tab == 'basic' ) echo 'nav-tab-active';
		echo '">' . __( 'Basic', 'shariff3UU' ) . '</a>';
		// design
		echo '<a href="?page=shariff3uu&tab=design" class="nav-tab ';
		if ( $active_tab == 'design' ) echo 'nav-tab-active';
		echo '">' . __( 'Design', 'shariff3UU' ) . '</a>';
		// advanced
		echo '<a href="?page=shariff3uu&tab=advanced" class="nav-tab ';
		if ( $active_tab == 'advanced' ) echo 'nav-tab-active';
		echo '">' . __( 'Advanced', 'shariff3UU' ) . '</a>';
		// mailform
		echo '<a href="?page=shariff3uu&tab=mailform" class="nav-tab ';
		if ( $active_tab == 'mailform' ) echo 'nav-tab-active';
		echo '">' . __( 'Mail Form', 'shariff3UU' ) . '</a>';
		// help
		echo '<a href="?page=shariff3uu&tab=help" class="nav-tab ';
		if ( $active_tab == 'help' ) echo 'nav-tab-active';
		echo '">' . __( 'Help', 'shariff3UU' ) . '</a>';
	echo '</h2>';

	// content of tabs
	if ( $active_tab == 'basic' ) {
		settings_fields( 'basic' );
		do_settings_sections( 'basic' );
		submit_button();
		do_settings_sections( 'status' );
	}
	elseif ( $active_tab == 'design' ) {
		settings_fields( 'design' );
		do_settings_sections( 'design' );
		submit_button();
	}
	elseif ( $active_tab == 'advanced' ) {
		settings_fields( 'advanced' );
		do_settings_sections( 'advanced' );
		submit_button();
	}
	elseif ( $active_tab == 'mailform' ) {
		settings_fields( 'mailform' );
		do_settings_sections( 'mailform' );
		submit_button();
	}
	elseif ( $active_tab == 'help' ) {
		settings_fields( 'help' );
		do_settings_sections( 'help' );
	}	

	// end of form
	echo '</form>';
} // end of plugin option page

// helper function to create the WP representation of the shorttag by the sidewide configured options
function buildShariffShorttag() {
	// get options
	$shariff3UU = $GLOBALS["shariff3UU"];
	
	// build the shorttag
	$shorttag = '[shariff';

	// orientation
	if ( isset($shariff3UU["vertical"] ) )		if ( $shariff3UU["vertical"] == '1' ) $shorttag .= ' orientation="vertical"';
	// theme
	if ( ! empty($shariff3UU["theme"] ) )		$shorttag .= ' theme="' . $shariff3UU["theme"] . '"';
	// buttonsize
	if ( isset($shariff3UU["buttonsize"] ) )	if ( $shariff3UU["buttonsize"] == '1' ) $shorttag .= ' buttonsize="small"';
	// lang
	if ( ! empty($shariff3UU["lang"] ) ) 		$shorttag .= ' lang="' . $shariff3UU["lang"] . '"';
	// services
	if ( ! empty($shariff3UU["services"] ) ) 	$shorttag .= ' services="' . $shariff3UU["services"] . '"';
	// backend
	if ( isset($shariff3UU["backend"] ) )		if ( $shariff3UU["backend"] == 'on' || $shariff3UU["backend"] == '1' ) $shorttag .= ' backend="on"';
	// info-url
	// rtzTodo: data-info-url + check that info is in the services
	if ( ! empty($shariff3UU["info_url"] ) ) 	$shorttag .= ' info_url="' . $shariff3UU["info_url"] . '"';
	// style
	if ( ! empty($shariff3UU["style"] ) ) 		$shorttag .= ' style="' . $shariff3UU["style"] . '"';
	// twitter-via
	if ( ! empty($shariff3UU["twitter_via"] ) )	$shorttag .= ' twitter_via="' . $shariff3UU["twitter_via"] . '"';
	// flatter-username
	if ( ! empty($shariff3UU["flattruser"] ) )	$shorttag .= ' flattruser="' . $shariff3UU["flattruser"] . '"';

	// close the shorttag
	$shorttag .= ']';
	
	return $shorttag;
}

// add mail form if view=mail
function shariff3UUaddMailForm( $content, $error ) {
	// check if mailform is disabled
	if ( isset( $GLOBALS["shariff3UU_mailform"]["disable_mailform"] ) && $GLOBALS["shariff3UU_mailform"]["disable_mailform"] == '1' ) {
		echo '<div id="shariff_mailform" class="shariff_mailform"><div class="shariff_mailform_disabled">';
		echo __( 'Mail form disabled.', 'shariff3UU' );
		echo '</div></div>';
		$mailform = '';
	}
	else {
		// set default language to English as fallback
		$lang = 'EN';

		// available languages
		$available_lang = array( 'EN', 'DE' );

		// check plugin options
		if ( isset( $GLOBALS["shariff3UU_mailform"]["mailform_language"] ) && $GLOBALS["shariff3UU_mailform"]["mailform_language"] != 'auto' ) {
			$lang = $GLOBALS["shariff3UU_mailform"]["mailform_language"];
		}
		// if language is set to automatic try geoip
		// http://datenverwurstungszentrale.com/stadt-und-land-mittels-geoip-ermitteln-268.htm
		elseif ( function_exists('geoip_country_code_by_name') ) {
			if ( WP_DEBUG == TRUE ) echo '<div>Currently using the following country code: ' . geoip_country_code_by_name( $_SERVER[REMOTE_ADDR] ) . '</div>';
			switch ( @geoip_country_code_by_name( $_SERVER[REMOTE_ADDR] ) ) { 
				case 'DE': $lang = 'DE'; 
				break; 
				case 'AT': $lang = 'DE'; 
				break; 
				case 'CH': $lang = 'DE'; 
				break; 
				default: $lang = 'EN';
			}
		}
		// if no geoip try http_negotiate_language
		elseif ( function_exists('http_negotiate_language') ) {
			$lang = http_negotiate_language( $available_lang );
		}

		// sonst per "WP-Plugin GeoIP Detection"
		// siehe https://wordpress.org/plugins/geoip-detect/
		// rtzrtz: erstmal raus, weil nicht mit WPMU https://wordpress.org/support/topic/will-this-work-with-multisite-2?replies=6
	#	elseif(function_exists("geoip_detect2_get_info_from_ip")){
	#		if(WP_DEBUG==TRUE)echo '<br>nutze gerade geoip_detect2_get_info_from_ip<br>';
	#      	$record = geoip_detect2_get_info_from_ip($_SERVER["REMOTE_ADDR"]);
	#    	switch($record->country->isoCode){case 'DE': $lang='DE'; break; case 'AT': $lang='DE'; break; case 'CH': $lang='DE'; break; default: $lang='EN';}
	#	}

		// German
		$mf_headline['DE']	= 'Diesen Beitrag per E-Mail versenden';
		$mf_headinfo['DE']	= 'Sie können maximal fünf Empf&auml;nger angeben. Diese bitte durch Kommas trennen.';
		$mf_rcpt['DE']		= 'Empf&auml;nger E-Mail-Adresse(n)';
		$mf_rcpt_ph['DE']	= 'empf&auml;nger@domain.de';
		$mf_from['DE']		= 'Absender E-Mail-Adresse';
		$mf_from_ph['DE']	= 'absender@domain.de';
		$mf_name['DE']		= 'Name des Absenders (optional)';
		$mf_name_ph['DE']	= 'Ihr Name';
		$mf_comment['DE']	= 'Zusatztext (optional)';
		$mf_send['DE']		= 'E-Mail senden';
		$mf_info['DE']		= 'Die hier eingegebenen Daten werden nur dazu verwendet, die E-Mail in Ihrem Namen zu versenden. Sie werden nicht gespeichert und es erfolgt keine Weitergabe an Dritte oder eine Analyse zu Marketing-Zwecken.';
		$mf_optional['DE']	= ' (optional)';
		$mf_wait1['DE']		= 'Spam-Schutz: Bitte in ';
		$mf_wait2['DE']		= ' Sekunden noch einmal versuchen!';
		$mf_to_error['DE']	= 'Ungültige Empf&auml;nger-Adresse(n)!';
		$mf_from_error['DE']= 'Ungültige Absender-Adresse!';

		// English
		$mf_headline['EN']	= 'Share this post by e-mail';
		$mf_headinfo['EN']	= 'You can enter up to five recipients. Seperate them with a comma.';
		$mf_rcpt['EN']		= 'E-mail address of the recipient(s)';
		$mf_rcpt_ph['EN']	= 'recipient@domain.com';
		$mf_from['EN']		= 'E-mail address of the sender';
		$mf_from_ph['EN']	= 'sender@domain.com';
		$mf_name['EN']		= 'Name of the sender (optional)';
		$mf_name_ph['EN']	= 'Your name';
		$mf_comment['EN']	= 'Additional text (optional)';
		$mf_send['EN']		= 'Send e-mail';
		$mf_info['EN']		= 'The provided data in this form is only used to send the e-mail in your name. They will not be stored and not be distributed to any third party or used for marketing purposes.';
		$mf_optional['EN']	= ' (optional)';
		$mf_wait1['EN']		= 'Spam protection: Please try again in ';
		$mf_wait2['EN']		= ' seconds!';
		$mf_to_error['EN']	= 'Invalid e-mail address(es)!';
		$mf_from_error['EN']= 'Invalid e-mail address!';
		
		// use wp_nonce_url / wp_verify_nonce to prevent automated spam by url
		$submit_link = wp_nonce_url( get_permalink(), 'shariff3UU_send_mail', 'shariff_mf_nonce' ) . '#shariff_mailform';

		// sender address optional?
		$mf_optional_text = '';
		$mf_sender_required = '';
		if ( isset( $GLOBALS["shariff3UU_mailform"]["require_sender"] ) && $GLOBALS["shariff3UU_mailform"]["require_sender"] == '1' ) {
			// does not work in Safari, but nice to have in all other cases, because less requests
			$mf_sender_required = ' required';
		}
		else {
			$mf_optional_text = $mf_optional[$lang];
		}

		// field content to prefill fields in case of an error
		if ( isset( $error['mf_content_mailto'] ) ) $mf_content_mailto = $error['mf_content_mailto'];
		else $mf_content_mailto = '';
		if ( isset( $error['mf_content_from'] ) ) $mf_content_from = $error['mf_content_from'];
		else $mf_content_from = '';
		if ( isset( $error['mf_content_sender'] ) ) $mf_content_sender = $error['mf_content_sender'];
		else $mf_content_sender = '';
		if ( isset( $error['mf_content_mail_comment'] ) ) $mf_content_mail_comment = $error['mf_content_mail_comment'];
		else $mf_content_mail_comment = '';

		// create the form
		$mailform = '<div id="shariff_mailform" class="shariff_mailform">';
		// wait error
		if ( ! empty ( $error['wait'] ) ) {
			$mailform .= '<div class="shariff_mailform_error">' . $mf_wait1[$lang] . $error['wait'] . $mf_wait2[$lang] . '</div>';
		}
		// no to address error
		$mf_to_error_html = '';
		if ( ! empty ( $error['to'] ) && $error['to'] == '1' ) {
			$mf_to_error_html = '<span class="shariff_mailform_error"> ' . $mf_to_error[$lang] . '</span>';
		}
		// no from address error
		$mf_from_error_html = '';
		if ( ! empty ( $error['from'] ) && $error['from'] == '1' ) {
			$mf_from_error_html = '<span class="shariff_mailform_error"> ' . $mf_from_error[$lang] . '</span>';
		}
		$mailform .= '<form action="' . $submit_link . '" method="POST">
						<fieldset>
							<div class="shariff_mailform_headline"><legend>' . $mf_headline[$lang] . '</legend></div>' . $mf_headinfo[$lang] . '
							<input type="hidden" name="act" value="sendMail">
							<input type="hidden" name="lang" value="' . $lang . '">
							<p><label for="mailto">' . $mf_rcpt[$lang] . '</label><br>
							<input type="text" name="mailto" id="mailto" value="' . $mf_content_mailto . '" size="27" placeholder="' . $mf_rcpt_ph[$lang] . '" required>' . $mf_to_error_html . '</p>
							<p><label for="from">' . $mf_from[$lang] . $mf_optional_text . '</label><br>
							<input type="email" name="from" if="from" value="' . $mf_content_from . '" size="27" placeholder="' . $mf_from_ph[$lang] . '" ' . $mf_sender_required .'>' . $mf_from_error_html . '</p>
							<p><label for="name">' . $mf_name[$lang] . '</label><br>
							<input type="text" name="sender" id="sender" value="' . $mf_content_sender . '" size="27" placeholder="' . $mf_name_ph[$lang] . '"></p>
							<p><label for="mail_comment">' . $mf_comment[$lang] . '</label><br>
							<textarea name="mail_comment" rows="4">' . $mf_content_mail_comment . '</textarea></p>
						</fieldset>
						<p><input type="submit" value="' . $mf_send[$lang].'" /></p>
						<p>' . $mf_info[$lang] . '</p>
						</form>
					</div>';
	}
	return $mailform . $content;
}

// helper functions to make it work with PHP < 5.3
// better would be: add_filter( 'wp_mail_from_name', function( $name ) { return sanitize_text_field( $_REQUEST['sender'] ); };
function set_wp_mail_from_name( $name ) { return sanitize_text_field( $_REQUEST['sender'] ); }
function set2_wp_mail_from_name( $name ) { return sanitize_text_field( $_REQUEST['from'] ); }
function set3_wp_mail_from_name( $name ) { return sanitize_text_field( $GLOBALS["shariff3UU"]["mail_sender_name"] ); }
function set4_wp_mail_from_name( $name ) { return sanitize_text_field( get_bloginfo('name') ); }
function set_wp_mail_from( $email ) { return sanitize_text_field( $GLOBALS["shariff3UU"]["mail_sender_from"] ); }

// send mail
function sharif3UUprocSentMail( $content ) {
	// Der Zusatztext darf keine Links enthalten, sonst zu verlockend fuer Spamer
	// optional robinson einbauen
	// optional auf eingeloggte User beschraenken, dann aber auch nicht allgemein anzeigen

	// get vars from from
	$mf_nonce   = sanitize_text_field( $_REQUEST['shariff_mf_nonce'] );
	$mf_mailto  = sanitize_text_field( $_REQUEST['mailto'] );
	$mf_from    = sanitize_text_field( $_REQUEST['from'] );
	$mf_sender  = sanitize_text_field( $_REQUEST['sender'] );
	$mf_lang    = sanitize_text_field( $_REQUEST['lang'] );

	// clean up comments
	$mf_comment = $_REQUEST['mail_comment'] ;
	// falls zauberhaft alte Serverkonfiguration, erstmal die Slashes entfernen...
	if ( get_magic_quotes_gpc() == 1 ) $mf_comment = stripslashes( $mf_comment );
	// ...denn sonst kan wp_kses den content nicht entschaerfen
	$mf_comment = wp_kses( $mf_comment, '', '' );

	// check if nonce is valid
	if ( isset( $mf_nonce ) && wp_verify_nonce( $mf_nonce, 'shariff3UU_send_mail' ) ) {
		// field content to prefill forms in case of an error
		$error['mf_content_mailto']       = $mf_mailto;
		$error['mf_content_from']         = $mf_from;
		$error['mf_content_sender']       = $mf_sender;
		$error['mf_content_mail_comment'] = $mf_comment;

		// rate limiter
		$wait = limitRemoteUser();
		if ( $wait > '5') {
			$error['error'] = '1';
			$error['wait'] = $wait;
		}
		else {
			 // Nicer sender name and adress
			 if ( ! empty( $mf_sender ) ) {
				 add_filter( 'wp_mail_from_name', 'set_wp_mail_from_name' );
			 }
			 elseif ( ! empty( $mf_from ) ) {
				 add_filter( 'wp_mail_from_name', 'set2_wp_mail_from_name' );
			 }
			 elseif ( ! empty( $GLOBALS["shariff3UU_mailform"]["mail_sender_name"] ) ) {
				 add_filter( 'wp_mail_from_name', 'set3_wp_mail_from_name' );
			 }
			 else {
			 	 add_filter( 'wp_mail_from_name', 'set4_wp_mail_from_name' );
			 }

			 // Achtung: NICHT die Absenderadresse selber umschreiben! 
			 // Das fuehrt bei allen sauber aufgesetzten Absender-MTAs zu Problemen mit SPF und/oder DKIM.

			 // default sender address
			 if ( ! empty( $GLOBALS["shariff3UU"]["mail_sender_from"] ) ) {
				 add_filter( 'wp_mail_from', 'set_wp_mail_from' );
			 }

			// build the array with recipients
			$arr = explode( ',', $mf_mailto );
			if ( $arr == FALSE ) $arr = array( $mf_mailto );
			// max 5
			for ( $i = 0; $i < count($arr); $i++ ) { 
				if ( $i == '5' ) break;
				$tmp_mail = sanitize_email( $arr[$i] );
				// no need to add invalid stuff to the array
				if ( is_email( $tmp_mail ) != false ) {
					$mailto[] = $tmp_mail; 
				}
			}
			
			// set langugage from form
			if ( ! empty( $mf_lang ) ) {
				$lang = $mf_lang;
			}
			else {
				$lang ='EN';
			}

			if ( $lang != 'DE' ) {
				$lang = 'EN';
			}
			
			$subject = html_entity_decode( get_the_title() );

			$message['DE'] = 'Der folgende Beitrag wurde Ihnen von ';
			$message['EN'] = 'The following post was suggested to you by ';

			if ( ! empty( $mf_sender ) ) {
				$message[ $lang ] .= $mf_sender;
			}
			elseif ( ! empty( $mf_from ) ) {
				$message[ $lang ] .= sanitize_text_field( $mf_from );
			}
			else {
				$message['DE'] .= 'jemanden';
				$message['EN'] .= 'somebody';
			}
			$message['DE'] .= ' empfohlen:';

			$message[ $lang ] .= " \r\n\r\n";
			$message[ $lang ] .= get_permalink() . "\r\n\r\n";
	
			// add comment
			if ( ! empty( $mf_comment ) ) {
				$message[ $lang ] .= $mf_comment . "\r\n\r\n";
			}
			
			// post content
			if ( isset( $GLOBALS["shariff3UU_mailform"]["mail_add_post_content"] ) && $GLOBALS["shariff3UU_mailform"]["mail_add_post_content"] == '1') {
				// strip all html tags
				$post_content = wordwrap( strip_tags( get_the_content() ), 72, "\r\n" );
				// strip shariff shortcodes
				$post_content = html_entity_decode( preg_replace( "#\[shariff.*?\]#s", "", $post_content ) );
				$message[ $lang ] .= $post_content;
				$message[ $lang ] .= " \r\n";
			}

			$message[ $lang ] .= "\r\n-- \r\n";

			$message['DE'] .= "Diese E-Mail wurde ueber das WordPress Plugin \"Shariff Wrapper\" versendet.\r\n";
			$message['DE'] .= "Es wurde entwickelt, um die Privatsphaere der Webseitenbesucher zu schuetzen.\r\n";
			$message['DE'] .= "Der Seitenbetreiber hat daher keine Moeglichkeit, naehere Angaben zum\r\n";
			$message['DE'] .= "tatsaechlichen Absender dieser E-Mail zu geben.\r\n";

			$message['EN'] .= "This e-mail was send using the WordPress plugin \"Shariff Wrapper\".\r\n";
			$message['EN'] .= "It was developed to protect the privacy of the website visitors.\r\n";
			$message['EN'] .= "Therefore the owner of the site cannot provide any more information\r\n";
			$message['EN'] .= "about the actual sender of this e-mail.\r\n";

			// To-Do: Hinweis auf Robinson-Liste

			// avoid auto-responder
			$headers = "Precedence: bulk\r\n";
									
			// if sender address provided, set as return-path, elseif sender required set error
			if ( ! empty( $mf_from ) && is_email( $mf_from ) != false ) {
				$headers .= "Reply-To: <" . $mf_from . ">\r\n";
			}
			elseif ( isset( $GLOBALS["shariff3UU_mailform"]["require_sender"] ) && $GLOBALS["shariff3UU_mailform"]["require_sender"] == '1' ) {
				$error['error'] = '1';
				$error['from'] = '1';
			}

			// set error, if no usuable recipient e-mail
			if ( empty( $mailto['0'] ) ) {
				$error['error'] = '1';
				$error['to'] = '1';
			}
		}
		// if we have errors provide the mailform again with error message
		if ( isset( $error['error'] ) && $error['error'] == '1' ) {
			$content = shariff3UUaddMailForm( $content, $error );
		}
		// if everything is fine, send the e-mail
		else {
			wp_mail( $mailto, $subject, $message["$lang"], $headers ); // The function is available after the hook 'plugins_loaded'.
			$mf_mail_send["DE"] = 'Die E-Mail wurde erfolgreich gesendet an:';
			$mf_mail_send["EN"] = 'The e-mail was successfully send to:';
			$mailnotice = '<div id="shariff_mailform" class="shariff_mailform">';
			$mailnotice .= '<div class="shariff_mailform_headline">' . $mf_mail_send[ $lang ] . '</div>';
			if ( is_array( $mailto ) ) {
				foreach ( $mailto as $rcpt ) $mailnotice .= $rcpt . '<br>';
			}
			else $mailnotice .= $mailto;
			$mailnotice .= '</div>';
			// add to content
			$content = $mailnotice . $content;
		}
	}
	return $content;
}

// set a timeout until new mails are possible                                  
function limitRemoteUser() {
	$shariff3UU_mailform = $GLOBALS["shariff3UU_mailform"];
	//rtzrtz: umgeschrieben aus dem DOS-Blocker. Nochmal gruebeln, ob wir das ohne memcache mit der Performance schaffen. Daher auch nur Grundfunktionalitaet.
	if ( ! isset( $shariff3UU_mailform['REMOTEHOSTS'] ) ) $shariff3UU_mailform['REMOTEHOSTS'] = '';
	$HOSTS = json_decode( $shariff3UU_mailform['REMOTEHOSTS'], true );
	// wartezeit in sekunden
	$wait = '1';
	if ( $HOSTS[$_SERVER['REMOTE_ADDR']]-time()+$wait > 0 ) {
		if ( $HOSTS[$_SERVER['REMOTE_ADDR']]-time() < 86400 ) {
			$wait = ($HOSTS[$_SERVER['REMOTE_ADDR']]-time()+$wait)*2; 
		}
  	}
  	$HOSTS[$_SERVER['REMOTE_ADDR']] = time()+$wait;
  	// etwas Muellentsorgung
  	if ( count( $HOSTS )%10 == 0 ) {
  		while ( list( $key, $value ) = each( $HOSTS ) ) {
  			if ( $value-time()+$wait < 0 ) {
  				unset( $HOSTS[$key] );
  				update_option( 'shariff3UU_mailform', $shariff3UU_mailform ); 
  			} 
  		} 
  	}
	$REMOTEHOSTS = json_encode( $HOSTS ); 
	$shariff3UU_mailform['REMOTEHOSTS'] = $REMOTEHOSTS; 
	// update nur, wenn wir nicht unter heftigen DOS liegen
  	if ( $HOSTS[$_SERVER['REMOTE_ADDR']]-time() < '60' ) {
  		update_option( 'shariff3UU_mailform', $shariff3UU_mailform );
  	}
  	return $HOSTS[$_SERVER['REMOTE_ADDR']]-time();
}
	 
// add shorttag to posts
function shariffPosts( $content ) {
	$shariff3UU = $GLOBALS["shariff3UU"];

	// disable share buttons on password protected posts if configured in the admin menu
	if ( ( post_password_required( get_the_ID() ) == '1' || ! empty( $GLOBALS["post"]->post_password ) ) && isset( $shariff3UU["disable_on_protected"] ) && $shariff3UU["disable_on_protected"] == '1') {
		$shariff3UU["add_before"]["posts"] = '0';
		$shariff3UU["add_before"]["posts_blogpage"] = '0';
		$shariff3UU["add_before"]["pages"] = '0';
		$shariff3UU["add_after"]["posts"] = '0';
		$shariff3UU["add_after"]["posts_blogpage"] = '0';
		$shariff3UU["add_after"]["pages"] = '0';
		$shariff3UU["add_after"]["custom_type"] = '0';
	}

	// prepend the mail form
	if ( isset( $_REQUEST['view'] ) && $_REQUEST['view'] == 'mail' ) {
		// only add to single posts view
		if ( is_singular() ) $content = shariff3UUaddMailForm( $content, '0' );
	}
	// send the email
	if ( isset( $_REQUEST['act'] ) && $_REQUEST['act'] == 'sendMail' ) $content = sharif3UUprocSentMail( $content );

	// if we want see it as text - replace the slash
	if ( strpos( $content,'/hideshariff' ) == true ) { 
		$content = str_replace( "/hideshariff", "hideshariff", $content ); 
	}
	// but not, if the hidshariff sign is in the text |or| if a special formed "[shariff..."  shortcut is found
	elseif( ( strpos( $content, 'hideshariff' ) == true) ) {
		// remove the sign
		$content = str_replace( "hideshariff", "", $content);
		// and return without adding Shariff
		return $content;
	}

	// now add Shariff
	if ( ! is_singular() ) {
		// on blog page
		if( isset( $shariff3UU["add_before"]["posts_blogpage"] ) && $shariff3UU["add_before"]["posts_blogpage"] == '1')	$content = buildShariffShorttag() . $content;
		if( isset( $shariff3UU["add_after"]["posts_blogpage"] ) && $shariff3UU["add_after"]["posts_blogpage"] == '1' )	$content .= buildShariffShorttag();
	} elseif ( is_singular( 'post' ) ) {
		// on single post
		if ( isset( $shariff3UU["add_before"]["posts"] ) && $shariff3UU["add_before"]["posts"] == '1' )	$content = buildShariffShorttag() . $content;
		if ( isset( $shariff3UU["add_after"]["posts"] ) && $shariff3UU["add_after"]["posts"] == '1' )	$content .= buildShariffShorttag();
	} elseif ( is_singular( 'page' ) ) {
		// on pages
		if ( isset( $shariff3UU["add_before"]["pages"] ) && $shariff3UU["add_before"]["pages"] == '1' )	$content = buildShariffShorttag() . $content;
		if ( isset( $shariff3UU["add_after"]["pages"] ) && $shariff3UU["add_after"]["pages"] == '1' )	$content .= buildShariffShorttag();
	} else {
		// on custom_post_types
		$all_custom_post_types = get_post_types( array ( '_builtin' => FALSE ) );
		if ( is_array( $all_custom_post_types ) ) {
			$custom_types = array_keys( $all_custom_post_types );
			// type of current post
			$current_post_type = get_post_type();
			// add shariff, if custom type and option checked in the admin menu
			if ( in_array( $current_post_type, $custom_types ) && isset( $shariff3UU["add_after"]["custom_type"] ) && $shariff3UU["add_after"]["custom_type"] == '1' ) $content .= buildShariffShorttag(); 
		}
	}

	return $content;
}
add_filter( 'the_content', 'shariffPosts' );

// add the align-style options to the css file and the button stretch
function shariff3UU_align_styles() {
	$shariff3UU_design = $GLOBALS["shariff3UU_design"];
	$custom_css = '';
	wp_enqueue_style('shariffcss', plugins_url('/css/shariff.min.local.css',__FILE__));

	// align option
	if ( isset( $shariff3UU_design["align"] ) && $shariff3UU_design["align"] != 'none' ) {
		 $align = $shariff3UU_design["align"];
		 $custom_css .= "
			 .shariff { justify-content: {$align} }
			 .shariff { -webkit-justify-content: {$align} }
			 .shariff { -ms-flex-pack: {$align} }
			 .shariff ul { justify-content: {$align} }
			 .shariff ul { -webkit-justify-content: {$align} }
			 .shariff ul { -ms-flex-pack: {$align} }
			 .shariff ul { -webkit-align-items: {$align} }
			 .shariff ul { align-items: {$align} }
			 ";
	}

	// align option for widget
	if ( isset( $shariff3UU_design["align_widget"] ) && $shariff3UU_design["align_widget"] != 'none' ) {
		 $align_widget = $shariff3UU_design["align_widget"];
		 $custom_css .= "
			 .widget .shariff { justify-content: {$align_widget} } 
			 .widget .shariff { -webkit-justify-content: {$align_widget} }
			 .widget .shariff { -ms-flex-pack: {$align_widget} }
			 .widget .shariff ul { justify-content: {$align_widget} }
			 .widget .shariff ul { -webkit-justify-content: {$align_widget} }
			 .widget .shariff ul { -ms-flex-pack: {$align_widget} }
			 .widget .shariff ul { -webkit-align-items: {$align} }
			 .widget .shariff ul { align-items: {$align} }
			 ";
	}

	// button stretch
	if ( isset( $shariff3UU_design["buttonstretch"] ) && $shariff3UU_design["buttonstretch"] == '1' ) {
		 $buttonstretch = $shariff3UU_design["buttonstretch"];
		 $custom_css .= "
			 .shariff ul { flex: {$buttonstretch} 0 auto !important }
			 .shariff ul { -webkit-flex: {$buttonstretch} 0 auto !important }
			 .shariff .orientation-horizontal li { flex: {$buttonstretch} 0 auto !important }
			 .shariff .orientation-horizontal li { -webkit-flex: {$buttonstretch} 0 auto !important }
			 .shariff .orientation-vertical { flex: {$buttonstretch} 0 auto !important }
			 .shariff .orientation-vertical { -webkit-flex: {$buttonstretch} 0 auto !important }
			 .shariff .orientation-vertical li { flex: {$buttonstretch} 0 auto !important }
			 .shariff .orientation-vertical li { -webkit-flex: {$buttonstretch} 0 auto !important }
			 .shariff .orientation-vertical li { width: 100% !important }
			 ";
	}

	// if not empty, add it to our plugin css
	if ( $custom_css != '') wp_add_inline_style( 'shariffcss', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'shariff3UU_align_styles' );

// render the shorttag to the HTML shorttag of Shariff
function Render3UUShariff( $atts, $content = null ) {
	// get options
	$shariff3UU = $GLOBALS["shariff3UU"];

	// avoid errors if no attributes are given - instead use the old set of services to make it backward compatible
	if ( empty( $shariff3UU["services"] ) ) $shariff3UU["services"] = "twitter|facebook|googleplus|info";

	// use the backend option for every option that is not set in the shorttag
	$backend_options = $shariff3UU;
	if ( isset( $shariff3UU["vertical"] ) )		if($shariff3UU["vertical"] == '1' )		$backend_options["orientation"] = 'vertical';
	if ( isset( $shariff3UU["backend"] ) )		if($shariff3UU["backend"] == '1' )		$backend_options["backend"] = 'on';
	if ( isset( $shariff3UU["buttonsize"] ) )	if($shariff3UU["buttonsize"] == '1' )	$backend_options["buttonsize"] = 'small';
	if ( empty( $atts ) ) $atts = $backend_options;
	else $atts = array_merge( $backend_options, $atts );

	// remove empty elements (no need to write data-something="" to html)
	$atts = array_filter( $atts );
 
	// make sure that default WP jquery is loaded
	wp_enqueue_script( 'jquery' );

	// the JS must be loaded at footer. Make sure that wp_footer() is present in your theme!
	wp_enqueue_script( 'shariffjs', plugins_url( '/shariff.js', __FILE__ ), '', '', true );

	// clean up headline in case it was used in a shorttag
	if ( array_key_exists( 'headline', $atts ) ) {
		$atts['headline'] = wp_kses( $atts['headline'], $GLOBALS["allowed_tags"] );
	}
	
	// prevent an error notice while debug mode is on, because of "undefined variable" when using .=
	$output = '';
	
	// if we have a style attribute and / or a headline, add it
	if ( array_key_exists( 'style', $atts ) || array_key_exists( 'headline', $atts ) ) {
		// container
		$output .= '<div class="ShariffSC"';
		// style attributes
		if ( array_key_exists( 'style', $atts ) ) {
			$output .= ' style="' . esc_html( $atts['style'] ) . '">';
		}
		else {
			$output .= '>';
		}
		// headline
		if ( array_key_exists( 'headline', $atts ) ) {
			$output .= '<div class="ShariffHeadline">' . $atts['headline'] . '</div>';
		}
	}

	// start output of actual shorttag
	$output .= '<div class="shariff"';

	// set the url attribute. Usefull e.g. in widgets that should point main page instead of a single post
	if ( array_key_exists( 'url', $atts ) ) $output .= ' data-url="' . esc_url( $atts['url'] ) . '"';
	else $output .= ' data-url="' . esc_url( get_permalink() ) . '"';
			
	// same for the title attribute
	if ( array_key_exists( 'title', $atts ) ) $output .= ' data-title="' . esc_html($atts['title']) . '"';
	else $output .= ' data-title="' . get_the_title() . '"';
			
	// set the options
	if ( array_key_exists( 'info_url', $atts ) )    $output .= ' data-info-url="' .		esc_html( $atts['info_url'] ) . '"';
	if ( array_key_exists( 'orientation', $atts ) ) $output .= ' data-orientation="' .	esc_html( $atts['orientation'] ) . '"';
	if ( array_key_exists( 'theme', $atts ) )       $output .= ' data-theme="' .		esc_html( $atts['theme'] ) . '"';
	// rtzTodo: use geoip if possible
	if ( array_key_exists( 'language', $atts ) )    $output .= ' data-lang="' .			esc_html( $atts['language']) . '"';
	if ( array_key_exists( 'twitter_via', $atts ) ) $output .= ' data-twitter-via="' .	esc_html( $atts['twitter_via']) . '"';
	if ( array_key_exists( 'flattruser', $atts ) )  $output .= ' data-flattruser="' .	esc_html( $atts['flattruser']) . '"';
	if ( array_key_exists( 'buttonsize', $atts ) )  $output .= ' data-buttonsize="' .	esc_html( $atts['buttonsize']) . '"';

	// if services are set only use these
	if ( array_key_exists( 'services', $atts ) ) {
		// build an array
		$s = explode( '|', $atts["services"] );
		$output .= ' data-services=\'[';
		// prevent error while debug mode is on
		$strServices = '';
		$flattr_error = '';
		// walk
		while ( list( $key, $val ) = each( $s ) ) { 
			// check if flattr-username is set and flattr is selected
			if ( $val != 'flattr' ) $strServices .= '"' . $val . '", ';
			elseif ( array_key_exists( 'flattruser', $atts ) ) $strServices .= '"' . $val . '", ';
			else $flattr_error = '1';
		}
		// remove the separator and add it to output
		$output .= substr( $strServices, 0, -2 );
		$output .= ']\'';
	}

	// get an image for pinterest (attribut -> featured image -> first image -> default image -> shariff hint)
	if ( array_key_exists( 'services', $atts ) ) if ( strstr( $atts["services"], 'pinterest') ) {
		if ( array_key_exists( 'media', $atts ) ) $output .= " data-media='" . esc_html( $atts['media'] ) . "'";
		else {
			$feat_image = wp_get_attachment_url( get_post_thumbnail_id() );
			if ( ! empty( $feat_image ) ) $output .= " data-media='" . esc_html( $feat_image ) . "'";
			else {
				$first_image = catch_image();
				if ( ! empty( $first_image ) ) $output .= " data-media='" . esc_html( $first_image ) . "'";
				else {
					if ( isset( $shariff3UU["default_pinterest"] ) ) $output .= " data-media='" . $shariff3UU["default_pinterest"] . "'";
					else $output .= " data-media='" . plugins_url( '/pictos/defaultHint.png', __FILE__ ) . "'";
				}
			}
		}
	}

	// enable share counts
	if ( array_key_exists( 'backend', $atts ) ) if ( $atts['backend'] == "on" ) $output .= " data-backend-url='" . esc_url( plugins_url( '/backend/', __FILE__ ) ) . "'";

	// close the container
	$output .= '></div>';

	// display warning to admins if flattr is set, but no flattrusername is provided
	if ( $flattr_error == '1' && current_user_can( 'manage_options' ) ) {
		$output .= '<div class="flattr_warning">' . __('Username for Flattr is missing!', 'shariff3UU') . '</div>';
	}

	// if we had a style attribute or a headline, close that too
	if ( array_key_exists( 'style', $atts ) || array_key_exists( 'headline', $atts ) ) $output .= '</div>';
	
	return $output;
}

// helper function to get the first image 
function catch_image() {
	$files = get_children( 'post_parent=' . get_the_ID() . '&post_type=attachment&post_mime_type=image' );
	if ( $files ) {
		$keys = array_reverse( array_keys( $files ) );
		$num = $keys[0];
		$imageurl = wp_get_attachment_url( $num );
		return $imageurl;
	}
}

// widget
class ShariffWidget extends WP_Widget {
	public function __construct() {
		// translations
		if(function_exists('load_plugin_textdomain')) { load_plugin_textdomain('shariff3UU', false, dirname(plugin_basename(__FILE__)).'/locale' ); }

		$widget_options = array(
			'classname' => 'Shariff',
			'description' => __('Add Shariff as configured on the plugin options page.', 'shariff3UU')
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
		$new_instance = wp_parse_args( (array) $new_instance, array( 'shariff-title' => '', 'shariff-tag' => '[shariff]') );

		// check input values
		$instance['shariff-title'] = (string) strip_tags( $new_instance['shariff-title'] );
		$instance['shariff-tag'] = (string) wp_kses( $new_instance['shariff-tag'], $GLOBALS["allowed_tags"] );

		// save config
		return $instance;
	} 
	
	// draw widget
	public function widget( $args, $instance ) {
		// extract $args
		extract( $args );
		
		// get options
		$shariff3UU = $GLOBALS["shariff3UU"];
		
		// container
		echo $before_widget;

		// print title of widget, if provided
		if ( empty( $instance['shariff-title'] ) ) {
			$title = '';
		}
		else {
			apply_filters( 'shariff-title', $instance['shariff-title'] );
			$title = $instance['shariff-title'];
		}
		if ( ! empty( $title ) ) { 
			echo $before_title . $title . $after_title;
		}
		
		// print shorttag

		// keep original shorttag for further reference
		$original_shorttag = $instance['shariff-tag'];

		// if nothing is configured, use the global options from admin menu
		if ( $instance['shariff-tag'] == '[shariff]' ) {
			$shorttag = buildShariffShorttag();
		}
		else {
			$shorttag = $instance['shariff-tag'];
		}

		// set url to current page to prevent sharing the first or last post on pages with multiple posts e.g. the blog page
		// ofc only when no manual url is provided in the shorttag
		$page_url = '';
		if ( strpos( $original_shorttag, ' url=' ) === false ) {
			$wpurl = get_bloginfo( 'wpurl' );
			$siteurl = get_bloginfo( 'url' );
			// for "normal" installations
			$page_url = $wpurl . esc_url_raw( $_SERVER['REQUEST_URI'] );
			// kill ?view=mail etc. if pressed a second time
			$page_url = strtok($page_url, '?');
			// if wordpress is installed in a subdirectory, but links are mapped to the main domain
			if ( $wpurl != $siteurl ) {
				$subdir = str_replace ( $siteurl , '' , $wpurl );
				$page_url = str_replace ( $subdir , '' , $page_url );
			}
			$page_url = ' url="' . $page_url;
			$page_url .= '"';
		}

		// same for title
		$page_title = '';
		if ( strpos( $original_shorttag, 'title=' ) === false ) {
			$wp_title = wp_title( '', false );
			// wp_title for all pages that have it
			if ( ! empty( $wp_title ) ) {
				$page_title = ltrim($wp_title);
			}
			// the site name for static start pages where wp_title is not set
			else {
				$page_title = get_bloginfo('name');
			}
			$page_title = ' title="' . $page_title;
			$page_title .= '"';
		}
			
		// same for media
		$media = '';
		if ( array_key_exists( 'services', $shariff3UU ) && strstr( $shariff3UU["services"], 'pinterest' ) && ( strpos( $original_shorttag,'media=' ) === false ) ) {
			$feat_image = wp_get_attachment_url( get_post_thumbnail_id() );
			if ( ! empty( $feat_image ) ) $media = ' media="' . esc_html( $feat_image ) . '"';
			else {
				$first_image = catch_image();
				if ( ! empty( $first_image ) ) $media = ' media="' . esc_html( $first_image ) . '"';
				else {
					if ( isset( $shariff3UU["default_pinterest"] ) ) $media = ' media="' . $shariff3UU["default_pinterest"] . '"';
					else $media = ' media="' . plugins_url( '/pictos/defaultHint.jpg', __FILE__ ) . '"';
				}
			}
		}
		
		// build shorttag and add url, title and media if necessary
		$shorttag = substr($shorttag,0,-1) . $page_title . $page_url . $media . ']';
		
		// replace mailform with mailto if on blog page to avoid broken button
		if ( ! is_singular() ) {
			$shorttag = str_replace( 'mailform' , 'mailto' , $shorttag );
		}
	
		// process the shortcode
		// but only if it is not password protected |or| "disable on password protected posts" is not set in the options
		if ( post_password_required( get_the_ID() ) != '1' || ( isset( $shariff3UU["disable_on_protected"] ) && $shariff3UU["disable_on_protected"] != '1' ) ) {
			echo do_shortcode( $shorttag );
		}

		// close Container
		echo $after_widget;
	} // END widget( $args, $instance )
} // END class ShariffWidget
add_action( 'widgets_init', create_function( '', 'return register_widget("ShariffWidget");' ) );

// admin info needed on version 1.7 and 2.3 because of changed behavior of prior mail option

// display an update notice that can be dismissed
function shariff3UU_admin_notice() {
	global $current_user;
	$user_id = $current_user->ID;
	// check that the user hasn't already clicked to ignore the message and can access options
	if ( ! get_user_meta( $user_id, 'shariff3UU_ignore_notice' ) && current_user_can( 'manage_options' ) ) {
		$link = add_query_arg( 'shariff3UU_nag_ignore', '0', esc_url_raw( $_SERVER['REQUEST_URI'] ) );
		echo "<div class='updated'><a href='" . esc_url( $link ) . "' class='shariff_admininfo_cross'><div class='shariff_cross_icon'></div></a><p>" . __( 'Please check your ', 'shariff3UU' ) . "<a href='" . get_bloginfo( 'wpurl' ) . "/wp-admin/options-general.php?page=shariff3uu'>" . __( 'Shariff-Settings</a> - Mail was split into mailform and mailto. Manual shorttags may need to be adjusted accordingly.', 'shariff3UU' ) . "</span></p></div>";
	}
}
add_action( 'admin_notices', 'shariff3UU_admin_notice' );

// helper function for shariff3UU_admin_notice()
function shariff3UU_nag_ignore() {
	global $current_user;
	$user_id = $current_user->ID;
	// If user clicks to ignore the notice, add that to their user meta
	if ( isset( $_GET['shariff3UU_nag_ignore'] ) && sanitize_text_field($_GET['shariff3UU_nag_ignore'] ) == '0' ) {
		add_user_meta( $user_id, 'shariff3UU_ignore_notice', 'true', true );
	}
}
add_action('admin_init', 'shariff3UU_nag_ignore');

// display an info notice if flattr is set as a service, but no username is entered
function shariff3UU_flattr_notice() {
	if ( isset( $GLOBALS["shariff3UU"]["services"] ) &&  ( strpos( $GLOBALS["shariff3UU"]["services"], 'flattr' ) !== false ) && empty( $GLOBALS["shariff3UU"]["flattruser"] ) && current_user_can( 'manage_options' ) ) {
		echo "<div class='error'><p>" . __('Please check your ', 'shariff3UU') . "<a href='" . get_bloginfo('wpurl') . "/wp-admin/options-general.php?page=shariff3uu&tab=advanced'>" . __('Shariff-Settings</a> - Flattr was selected, but no username was provided! Please enter your <strong>Flattr username</strong> in the shariff options!', 'shariff3UU') . "</span></p></div>";
	}
}
add_action( 'admin_notices', 'shariff3UU_flattr_notice' );

// display an info notice if mailform is set as a service, but mail form functionality has been disabled
function shariff3UU_mail_notice() {
	if ( isset( $GLOBALS["shariff3UU_mailform"]["disable_mailform"] ) && ( strpos( $GLOBALS["shariff3UU_basic"]["services"], 'mailform' ) !== false ) && isset( $GLOBALS["shariff3UU"]["disable_mailform"] ) && $GLOBALS["shariff3UU"]["disable_mailform"] == '1' && current_user_can( 'manage_options' ) ) {
		echo "<div class='error'><p>" . __('Please check your ', 'shariff3UU') . "<a href='" . get_bloginfo('wpurl') . "/wp-admin/options-general.php?page=shariff3uu&tab=mailform'>" . __('Shariff-Settings</a> - Mailform has been selected as a service, but mail form functionality is disabled!', 'shariff3UU') . "</span></p></div>";
	}
}
add_action( 'admin_notices', 'shariff3UU_mail_notice' );

// clear cache upon deactivation
function shariff3UU_deactivate() {
// check for multisite
if ( is_multisite() ) {
	global $wpdb;
	$current_blog_id = get_current_blog_id();
	$blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
	if ( $blogs ) {
		foreach ( $blogs as $blog ) {
			// switch to each blog
			switch_to_blog( $blog['blog_id'] );
			// delete cache dir
			shariff_removecachedir();
			// switch back to main
			restore_current_blog();
		}
	}
} else {
	// delete cache dir
	shariff_removecachedir();
	}
}
register_deactivation_hook( __FILE__, 'shariff3UU_deactivate' );

// delete cache directory
function shariff_removecachedir() {
	$upload_dir = wp_upload_dir();
	$cache_dir = $upload_dir['basedir'] . '/shariff3uu_cache';
	shariff_removefiles( $cache_dir );
	// remove /shariff3uu_cache if empty
	@rmdir( $cache_dir );
}

// helper function to delete .dat files that begin with "Shariff" and empty folders that also start with "Shariff"
function shariff_removefiles( $directory ) {
	foreach( glob( "{$directory}/Shariff*" ) as $file ) {
		if ( is_dir( $file ) ) shariff_removefiles( $file );
		elseif ( substr( $file, -4 ) == '.dat' ) @unlink( $file );
	}
	@rmdir( $directory );
}
?>
