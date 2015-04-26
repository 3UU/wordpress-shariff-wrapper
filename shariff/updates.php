<?php
/**
 Would be included by shariff3UU_update() only if needed. 
 Put all update task here and feel free to split files per update 
 but make sure that all "older" updates are checked first.
 To enable a admin notice plz set
 $do_admin_notice=true; 
 At least you must set
 $GLOBALS["shariff3UU"]["version"] = [YOUR VERSION];
 to avoid includes later on.
**/

  // Migration < v 1.7
  if(empty($GLOBALS["shariff3UU"]["version"]) || ( isset($GLOBALS["shariff3UU"]["version"]) && version_compare($GLOBALS["shariff3UU"]["version"], '1.7') == '-1') ) {
    if(isset($GLOBALS["shariff3UU"]["add_all"])){
      if($GLOBALS["shariff3UU"]["add_all"]=='1'){ $GLOBALS["shariff3UU"]["add_after_all_posts"]='1'; $GLOBALS["shariff3UU"]["add_after_all_pages"]='1'; unset($GLOBALS["shariff3UU"]["add_all"]); }
    }
    if(isset($GLOBALS["shariff3UU"]["add_before_all"])){
      if($GLOBALS["shariff3UU"]["add_before_all"]=='1'){ $GLOBALS["shariff3UU"]["add_before_all_posts"]='1'; $GLOBALS["shariff3UU"]["add_before_all_pages"]='1'; unset($GLOBALS["shariff3UU"]["add_before_all"]); }
    }
    $GLOBALS["shariff3UU"]["version"] = '1.7';
    $do_admin_notice=true;
  }  
  
  // Migration < v 1.9.7
  if(!isset($wpdb)) { global $wpdb; }
  if( version_compare($GLOBALS["shariff3UU"]["version"], '1.9.7') == '-1') {
    // clear wrong entries from the past
    if (!is_multisite()){ $users = get_users('role=administrator'); foreach ($users as $user) { if ( !get_user_meta($user, 'shariff_ignore_notice' )) { delete_user_meta($user->ID, 'shariff_ignore_notice'); } }
    }else{
      $current_blog_id=get_current_blog_id();
      if($blogs = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A)){
        foreach($blogs as $blog) {
          // switch to each blog
          switch_to_blog($blog['blog_id']);
          // delete user meta entry shariff_ignore_notice
          $users = get_users('role=administrator'); 
          foreach ($users as $user) { if ( !get_user_meta($user, 'shariff_ignore_notice' )) { delete_user_meta($user->ID, 'shariff_ignore_notice'); } }
          // switch back to main
          restore_current_blog();
        }  
      }
    }    
    $GLOBALS["shariff3UU"]["version"] = '1.9.7';
  }

  // Migration < v 2.0
  if( version_compare($GLOBALS["shariff3UU"]["version"], '2.0') == '-1') {
    // switch service mail to mailto if mailto is not set in services too
    // services ist bei Erstinstallation leer -> isset() und strpos kann 0 zurückliefern (gefunden an nullter Stelle), was als false verstanden werden würde, daher === notwendig
    if(isset($GLOBALS["shariff3UU"]["services"]) && strpos($GLOBALS["shariff3UU"]["services"],'mail')!== FALSE && strpos($GLOBALS["shariff3UU"]["services"],'mailto') === FALSE){
      $GLOBALS["shariff3UU"]["services"]=str_replace('mail', 'mailto', $GLOBALS["shariff3UU"]["services"]);
      update_option( 'shariff3UU', $GLOBALS["shariff3UU"] );  
    }
    $GLOBALS["shariff3UU"]["version"] = '2.0.0';
  }
  
?>
