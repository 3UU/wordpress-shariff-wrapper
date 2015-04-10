<?php 
/* delete options upon uninstall to prevent issues with other plugins and leaving trash behind */

// if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();

$option_name = 'shariff3UU';

// delete options from options table
delete_option( $option_name );

// delete user meta entry shariff_ignore_notice
$users = get_users('role=administrator');
foreach ($users as $user) { if ( !get_user_meta($user, 'shariff_ignore_notice' )) { delete_user_meta($user->ID, 'shariff3UU_ignore_notice'); } }
?>
