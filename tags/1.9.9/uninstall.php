<?php 
/* delete options upon uninstall to prevent issues with other plugins and leaving trash behind */

// if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();

$option_name = 'shariff3UU';
$widget_name = 'widget_shariff';

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
          delete_option( $widget_name );
          // delete user meta entry shariff3UU_ignore_notice
          $users = get_users('role=administrator');
          foreach ($users as $user) { if ( !get_user_meta($user, 'shariff3UU_ignore_notice' )) { delete_user_meta($user->ID, 'shariff3UU_ignore_notice'); } };
          // delete cache dir
          shariff_removecachedir();
          // switch back to main
          restore_current_blog();
        }
    }
} else {
    // delete options from options table
    delete_option( $option_name );
    delete_option( $version_name );
    delete_option( $widget_name );
    // delete user meta entry shariff3UU_ignore_notice
    $users = get_users('role=administrator');
    foreach ($users as $user) { if ( !get_user_meta($user, 'shariff3UU_ignore_notice' )) { delete_user_meta($user->ID, 'shariff3UU_ignore_notice'); } };
    // delete cache dir
    shariff_removecachedir();
}

/* Delete cache directory */
function shariff_removecachedir(){
  $upload_dir = wp_upload_dir();
  $cache_dir = $upload_dir['basedir'].'/1970/01';
  $cache_dir2 = $upload_dir['basedir'].'/1970';
  shariff_removefiles( $cache_dir );
  // Remove /1970/01 and /1970 if they are empty
  @rmdir($cache_dir);
  @rmdir($cache_dir2);
}

/* Helper function to delete .dat files that begin with "Shariff" and empty folders that also start with "Shariff" */
function shariff_removefiles($directory){
  foreach(glob("{$directory}/Shariff*") as $file) {
    if(is_dir($file)) shariff_removefiles($file);
    else if(substr($file, -4) == '.dat') @unlink($file);
  }
  @rmdir($directory);
}
?>
