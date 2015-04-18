<?php 
/* delete options upon uninstall to prevent issues with other plugins and leaving trash behind */

// if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();

$option_name = 'shariff3UU';
$version_name = 'shariff3UUversion';
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
			delete_option( $version_name );
			delete_option( $widget_name );
			// delete user meta entry shariff3UU_ignore_notice
			$users = get_users('role=administrator');
			foreach ($users as $user) { if ( !get_user_meta($user, 'shariff3UU_ignore_notice' )) { delete_user_meta($user->ID, 'shariff3UU_ignore_notice'); } }
			// delete cache dir
  			$upload_dir = wp_upload_dir();
  			$cache_dir = $upload_dir['basedir'].'/1970';
 			shariff_removecachedir( $cache_dir );
        }
        // switch back to main
        restore_current_blog();
    }
} else {
    // delete options from options table
	delete_option( $option_name );
	delete_option( $version_name );
	delete_option( $widget_name );
	// delete user meta entry shariff3UU_ignore_notice
	$users = get_users('role=administrator');
	foreach ($users as $user) { if ( !get_user_meta($user, 'shariff3UU_ignore_notice' )) { delete_user_meta($user->ID, 'shariff3UU_ignore_notice'); } }
  	// delete cache dir
  	$upload_dir = wp_upload_dir();
  	$cache_dir = $upload_dir['basedir'].'/1970';
 	shariff_removecachedir( $cache_dir );
}

/* Helper function to delete cache directory */
function shariff_removecachedir($directory){
# ritze: Das is mir zu gefaehrlich. Wenn jemand nen Archiv aufgebaut 
# hat, loeschen wir ihm damit vielleicht wichtige Files. Erstmal 
# unschaedlich gemacht, bis mir nen besseres Verzeichnis einfaellt.
return;
  foreach(glob("{$directory}/*") as $file) {
    if(is_dir($file)) shariff_removecachedir($file);
    else @unlink($file);
  }
  @rmdir($directory);
}
?>
