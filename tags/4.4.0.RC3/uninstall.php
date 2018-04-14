<?php
/**
 * Delete options upon uninstall to prevent issues with other plugins and leaving trash behind.
 *
 * @package WordPress
 */

// Exit, if uninstall.php was not called from WordPress.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die();
}

// Delete options (even old ones), remove cron job and clear cache.
if ( is_multisite() ) {
	global $wpdb;
	$current_blog_id = get_current_blog_id();
	// @codingStandardsIgnoreStart
	$blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
	// @codingStandardsIgnoreEnd
	if ( $blogs ) {
		foreach ( $blogs as $blog ) {
			// Switch to each blog.
			switch_to_blog( $blog['blog_id'] );
			// Delete options from options table.
			delete_option( 'shariff3UU' );
			delete_option( 'shariff3UU_basic' );
			delete_option( 'shariff3UU_design' );
			delete_option( 'shariff3UU_advanced' );
			delete_option( 'shariff3UU_mailform' );
			delete_option( 'shariff3UU_statistic' );
			delete_option( 'widget_shariff' );
			delete_option( 'shariff3UU_hide_update_notice' );
			delete_option( 'shariff3uu_basic' );
			delete_option( 'shariff3uu_design' );
			delete_option( 'shariff3uu_advanced' );
			delete_option( 'shariff3uu_mailform' );
			delete_option( 'shariff3uu_statistic' );
			// Purge transients.
			shariff3uu_purge_transients();
			// Remove cron job.
			wp_clear_scheduled_hook( 'shariff3UU_fill_cache' );
			// Switch back to main.
			restore_current_blog();
		}
	}
} else {
	// Delete options from options table.
	delete_option( 'shariff3UU' );
	delete_option( 'shariff3UU_basic' );
	delete_option( 'shariff3UU_design' );
	delete_option( 'shariff3UU_advanced' );
	delete_option( 'shariff3UU_mailform' );
	delete_option( 'shariff3UU_statistic' );
	delete_option( 'widget_shariff' );
	delete_option( 'shariff3UU_hide_update_notice' );
	delete_option( 'shariff3uu_basic' );
	delete_option( 'shariff3uu_design' );
	delete_option( 'shariff3uu_advanced' );
	delete_option( 'shariff3uu_mailform' );
	delete_option( 'shariff3uu_statistic' );
	// Purge transients.
	shariff3uu_purge_transients();
	// Remove cron job.
	wp_clear_scheduled_hook( 'shariff3UU_fill_cache' );
}

/**
 * Helper function to purge all the transients associated with our plugin.
 */
function shariff3uu_purge_transients() {
	// Make sure we have the $wpdb class ready.
	if ( ! isset( $wpdb ) ) {
		global $wpdb;
	}

	// Delete transients.
	// @codingStandardsIgnoreStart
	$sql = 'DELETE FROM ' . $wpdb->options . ' WHERE option_name LIKE "_transient_timeout_shariff%"';
	$wpdb->query($sql);
	$sql = 'DELETE FROM ' . $wpdb->options . ' WHERE option_name LIKE "_transient_shariff%"';
	$wpdb->query($sql);
	// @codingStandardsIgnoreEnd

	// Clear object cache.
	wp_cache_flush();
}
