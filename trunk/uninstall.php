<?php 
/* delete options upon uninstall to prevent issues with other plugins and leaving trash behind */

// if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();

$option_name = 'shariff3UU';

// delete options from options table
delete_option( $option_name );
?>