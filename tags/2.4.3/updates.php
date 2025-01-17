<?php
/**
 * Would be included by shariff3UU_update() only if needed. 
 * Put all update task here and feel free to split files per update 
 * but make sure that all "older" updates are checked first.
 * To enable a admin notice plz set
 * $do_admin_notice = true; 
 * At least you must set
 * $GLOBALS["shariff3UU"]["version"] = [YOUR VERSION];
 * to avoid includes later on.
*/

// Migration < v 1.7
if ( isset( $GLOBALS["shariff3UU"]["version"] ) && version_compare( $GLOBALS["shariff3UU"]["version"], '1.7' ) == '-1' ) {
	if ( isset($GLOBALS["shariff3UU"]["add_all"] ) ) {
		if ( $GLOBALS["shariff3UU"]["add_all"] == '1') { 
			$GLOBALS["shariff3UU"]["add_after_all_posts"] = '1'; 
			$GLOBALS["shariff3UU"]["add_after_all_pages"] = '1'; 
			unset( $GLOBALS["shariff3UU"]["add_all"] ); 
		}
	}
	if ( isset( $GLOBALS["shariff3UU"]["add_before_all"] ) ) {
		if ( $GLOBALS["shariff3UU"]["add_before_all"] == '1' ) { 
			$GLOBALS["shariff3UU"]["add_before_all_posts"] = '1'; 
			$GLOBALS["shariff3UU"]["add_before_all_pages"] = '1'; 
			unset( $GLOBALS["shariff3UU"]["add_before_all"] ); 
		}
	}
	$GLOBALS["shariff3UU"]["version"] = '1.7';
}  

// Migration < v 1.9.7
if ( ! isset( $wpdb ) ) { 
	global $wpdb; 
}
if ( isset( $GLOBALS["shariff3UU"]["version"] ) && version_compare($GLOBALS["shariff3UU"]["version"], '1.9.7') == '-1' ) {
	// clear wrong entries from the past
	if ( ! is_multisite() ) { 
		$users = get_users('role=administrator'); 
		foreach ( $users as $user ) { 
			if ( ! get_user_meta($user, 'shariff_ignore_notice' ) ) { 
				delete_user_meta( $user->ID, 'shariff_ignore_notice' ); 
			} 
		}
	}
	else {
		$current_blog_id = get_current_blog_id();
		if ( $blogs = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A ) ) {
			foreach( $blogs as $blog ) {
				// switch to each blog
				switch_to_blog( $blog['blog_id'] );
				// delete user meta entry shariff_ignore_notice
				$users = get_users('role=administrator'); 
				foreach ( $users as $user ) { 
					if ( ! get_user_meta( $user, 'shariff_ignore_notice' ) ) { 
						delete_user_meta( $user->ID, 'shariff_ignore_notice' ); 
					} 
				}
				// switch back to main
				restore_current_blog();
			}  
		}
	}    
	$GLOBALS["shariff3UU"]["version"] = '1.9.7';
}

// Migration < v 2.0
if ( isset( $GLOBALS["shariff3UU"]["version"] ) && version_compare($GLOBALS["shariff3UU"]["version"], '2.0') == '-1' ) {
	// switch service mail to mailto if mailto is not set in services too
	// services ist bei Erstinstallation leer -> isset() und strpos kann 0 zurückliefern (gefunden an nullter Stelle), was als false verstanden werden würde, daher === notwendig
	if ( isset( $GLOBALS["shariff3UU"]["services"] ) && strpos( $GLOBALS["shariff3UU"]["services"],'mail' ) !== FALSE && strpos( $GLOBALS["shariff3UU"]["services"],'mailto' ) === FALSE ) {
		$GLOBALS["shariff3UU"]["services"] = str_replace( 'mail', 'mailto', $GLOBALS["shariff3UU"]["services"] ); 
	}
	$GLOBALS["shariff3UU"]["version"] = '2.0.0';
}

// Migration < v 2.3
if ( isset( $GLOBALS["shariff3UU"]["version"] ) && version_compare( $GLOBALS["shariff3UU"]["version"], '2.2.5' ) == '-1' ) {

	// switch mail to mailform
	if ( isset( $GLOBALS["shariff3UU"]["services"] ) && strpos( $GLOBALS["shariff3UU"]["services"], 'mail' ) !== FALSE ) {
		$GLOBALS["shariff3UU"]["services"] = str_replace( 'mail', 'mailform', $GLOBALS["shariff3UU"]["services"] );
		if ( str_replace( 'mailformto', 'mailto', $GLOBALS["shariff3UU"]["services"] ) !== FALSE ) {
			$GLOBALS["shariff3UU"]["services"] = str_replace( 'mailformto', 'mailto', $GLOBALS["shariff3UU"]["services"] );
		}

	}

	// split options in the db according to new tabs

	// basic options
	if ( isset( $GLOBALS["shariff3UU"]["version"] ) )                       $GLOBALS["shariff3UU_basic"]["version"]                      = $GLOBALS["shariff3UU"]["version"];
	if ( isset( $GLOBALS["shariff3UU"]["services"] ) )                      $GLOBALS["shariff3UU_basic"]["services"]                     = $GLOBALS["shariff3UU"]["services"];
	if ( isset( $GLOBALS["shariff3UU"]["add_after_all_posts"] ) )           $GLOBALS["shariff3UU_basic"]["add_after"]["posts"]           = $GLOBALS["shariff3UU"]["add_after_all_posts"];
	if ( isset( $GLOBALS["shariff3UU"]["add_after_all_overview"] ) )        $GLOBALS["shariff3UU_basic"]["add_after"]["posts_blogpage"]  = $GLOBALS["shariff3UU"]["add_after_all_overview"];
	if ( isset( $GLOBALS["shariff3UU"]["add_after_all_pages"] ) )           $GLOBALS["shariff3UU_basic"]["add_after"]["pages"]           = $GLOBALS["shariff3UU"]["add_after_all_pages"];
	if ( isset( $GLOBALS["shariff3UU"]["add_after_all_custom_type"] ) )     $GLOBALS["shariff3UU_basic"]["add_after"]["custom_type"]     = $GLOBALS["shariff3UU"]["add_after_all_custom_type"];
	if ( isset( $GLOBALS["shariff3UU"]["add_before_all_posts"] ) )          $GLOBALS["shariff3UU_basic"]["add_before"]["posts"]          = $GLOBALS["shariff3UU"]["add_before_all_posts"];
	if ( isset( $GLOBALS["shariff3UU"]["add_before_all_overview"] ) )       $GLOBALS["shariff3UU_basic"]["add_before"]["posts_blogpage"] = $GLOBALS["shariff3UU"]["add_before_all_overview"];
	if ( isset( $GLOBALS["shariff3UU"]["add_before_all_pages"] ) )          $GLOBALS["shariff3UU_basic"]["add_before"]["pages"]          = $GLOBALS["shariff3UU"]["add_before_all_pages"];
	if ( isset( $GLOBALS["shariff3UU"]["disable_on_protected"] ) )          $GLOBALS["shariff3UU_basic"]["disable_on_protected"]         = $GLOBALS["shariff3UU"]["disable_on_protected"];
	if ( isset( $GLOBALS["shariff3UU"]["backend"] ) )                       $GLOBALS["shariff3UU_basic"]["backend"]                      = $GLOBALS["shariff3UU"]["backend"];

	// design options
	if ( isset( $GLOBALS["shariff3UU"]["language"] ) )                      $GLOBALS["shariff3UU_design"]["language"]                    = $GLOBALS["shariff3UU"]["language"];
	if ( isset( $GLOBALS["shariff3UU"]["theme"] ) )                         $GLOBALS["shariff3UU_design"]["theme"]                       = $GLOBALS["shariff3UU"]["theme"];
	if ( isset( $GLOBALS["shariff3UU"]["buttonsize"] ) )                    $GLOBALS["shariff3UU_design"]["buttonsize"]                  = $GLOBALS["shariff3UU"]["buttonsize"];
	if ( isset( $GLOBALS["shariff3UU"]["vertical"] ) )                      $GLOBALS["shariff3UU_design"]["vertical"]                    = $GLOBALS["shariff3UU"]["vertical"];
	if ( isset( $GLOBALS["shariff3UU"]["align"] ) )                         $GLOBALS["shariff3UU_design"]["align"]                       = $GLOBALS["shariff3UU"]["align"];
	if ( isset( $GLOBALS["shariff3UU"]["align_widget"] ) )                  $GLOBALS["shariff3UU_design"]["align_widget"]                = $GLOBALS["shariff3UU"]["align_widget"];
	if ( isset( $GLOBALS["shariff3UU"]["style"] ) )                         $GLOBALS["shariff3UU_design"]["style"]                       = $GLOBALS["shariff3UU"]["style"];

	// advanced options
	if ( isset( $GLOBALS["shariff3UU"]["info_url"] ) )                      $GLOBALS["shariff3UU_advanced"]["info_url"]                  = $GLOBALS["shariff3UU"]["info_url"];
	if ( isset( $GLOBALS["shariff3UU"]["twitter_via"] ) )                   $GLOBALS["shariff3UU_advanced"]["twitter_via"]               = $GLOBALS["shariff3UU"]["twitter_via"];
	if ( isset( $GLOBALS["shariff3UU"]["flattruser"] ) )                    $GLOBALS["shariff3UU_advanced"]["flattruser"]                = $GLOBALS["shariff3UU"]["flattruser"];
	if ( isset( $GLOBALS["shariff3UU"]["default_pinterest"] ) )             $GLOBALS["shariff3UU_advanced"]["default_pinterest"]         = $GLOBALS["shariff3UU"]["default_pinterest"];

	// mailform options
	$GLOBALS["shariff3UU_mailform"]["require_sender"] = '1'; // default options should be as save as possible, same reason the statistics are disabled by default
	if ( isset( $GLOBALS["shariff3UU"]["mail_add_post_content"] ) )         $GLOBALS["shariff3UU_mailform"]["mail_add_post_content"]     = $GLOBALS["shariff3UU"]["mail_add_post_content"];
	if ( isset( $GLOBALS["shariff3UU"]["mail_sender_name"] ) )              $GLOBALS["shariff3UU_mailform"]["mail_sender_name"]          = $GLOBALS["shariff3UU"]["mail_sender_name"];
	if ( isset( $GLOBALS["shariff3UU"]["mail_sender_from"] ) )              $GLOBALS["shariff3UU_mailform"]["mail_sender_from"]          = $GLOBALS["shariff3UU"]["mail_sender_from"];

	// update global
	$GLOBALS["shariff3UU"] = array_merge( $GLOBALS["shariff3UU_basic"], $GLOBALS["shariff3UU_design"], $GLOBALS["shariff3UU_advanced"], $GLOBALS["shariff3UU_mailform"] );

	// delete old cache directory and db entry
	
	// check for multisite
	if ( is_multisite() ) {
		global $wpdb;
		$current_blog_id = get_current_blog_id();
		$blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
		if ( $blogs ) {
			foreach( $blogs as $blog ) {
				// switch to each blog
				switch_to_blog( $blog['blog_id'] );
				// delete cache dir
				shariff_removeoldcachedir();
				// delete old db entry
				delete_option( 'shariff3UU' );
				// switch back to main
				restore_current_blog();
			}
		}
	} else {
		// delete cache dir
		shariff_removeoldcachedir();
		// delete old db entry
		delete_option( 'shariff3UU' );
	}

	// display update notice
	$do_admin_notice = true;

	// update version
	$GLOBALS["shariff3UU"]["version"] = '2.3.0';
}

// helper function to delete old cache directory
function shariff_removeoldcachedir() {
	$upload_dir = wp_upload_dir();
	$cache_dir = $upload_dir['basedir'] . '/1970/01';
	$cache_dir2 = $upload_dir['basedir'] . '/1970';
	shariff_removeoldfiles( $cache_dir );
	// Remove /1970/01 and /1970 if they are empty
	@rmdir( $cache_dir );
	@rmdir( $cache_dir2 );
}

// helper function to delete old .dat files that begin with "Shariff" and empty folders that also start with "Shariff"
function shariff_removeoldfiles( $directory ) {
	foreach( glob("{$directory}/Shariff*" ) as $file ) {
		if ( is_dir( $file ) ) shariff_removeoldfiles( $file );
		elseif ( substr( $file, -4 ) == '.dat' ) @unlink($file);
	}
	@rmdir( $directory );
}

?>
