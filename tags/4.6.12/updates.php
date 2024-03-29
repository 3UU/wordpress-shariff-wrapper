<?php
/**
 * Will be included by shariff3uu_update() only if needed.
 * Put all update task here. Make sure that all "older" updates are checked first.
 * At least you must set $shariff3uu['version'] = [YOUR VERSION]; to avoid includes later on.
 *
 * @package WordPress
 */

// Prevent direct calls.
if ( ! class_exists( 'WP' ) ) {
	die();
}

/**
 * Migration < 2.3
 * Split options in database according to new setting tabs.
 * Delete old cache directory.
 */
if ( isset( $shariff3uu['version'] ) && -1 === version_compare( $shariff3uu['version'], '2.2.5' ) ) {

	// Basic options.
	if ( isset( $shariff3uu['version'] ) ) {
		$shariff3uu_basic['version'] = $shariff3uu['version'];
	}
	if ( isset( $shariff3uu['services'] ) ) {
		$shariff3uu_basic['services'] = $shariff3uu['services'];
	}
	if ( isset( $shariff3uu['add_after_all_posts'] ) ) {
		$shariff3uu_basic['add_after']['posts'] = $shariff3uu['add_after_all_posts'];
	}
	if ( isset( $shariff3uu['add_after_all_overview'] ) ) {
		$shariff3uu_basic['add_after']['posts_blogpage'] = $shariff3uu['add_after_all_overview'];
	}
	if ( isset( $shariff3uu['add_after_all_pages'] ) ) {
		$shariff3uu_basic['add_after']['pages'] = $shariff3uu['add_after_all_pages'];
	}
	if ( isset( $shariff3uu['add_after_all_custom_type'] ) ) {
		$shariff3uu_basic['add_after']['custom_type'] = $shariff3uu['add_after_all_custom_type'];
	}
	if ( isset( $shariff3uu['add_before_all_posts'] ) ) {
		$shariff3uu_basic['add_before']['posts'] = $shariff3uu['add_before_all_posts'];
	}
	if ( isset( $shariff3uu['add_before_all_overview'] ) ) {
		$shariff3uu_basic['add_before']['posts_blogpage'] = $shariff3uu['add_before_all_overview'];
	}
	if ( isset( $shariff3uu['add_before_all_pages'] ) ) {
		$shariff3uu_basic['add_before']['pages'] = $shariff3uu['add_before_all_pages'];
	}
	if ( isset( $shariff3uu['disable_on_protected'] ) ) {
		$shariff3uu_basic['disable_on_protected'] = $shariff3uu['disable_on_protected'];
	}
	if ( isset( $shariff3uu['backend'] ) ) {
		$shariff3uu_basic['backend'] = $shariff3uu['backend'];
	}

	// Design options.
	if ( isset( $shariff3uu['language'] ) ) {
		$shariff3uu_design['language'] = $shariff3uu['language'];
	}
	if ( isset( $shariff3uu['theme'] ) ) {
		$shariff3uu_design['theme'] = $shariff3uu['theme'];
	}
	if ( isset( $shariff3uu['buttonsize'] ) ) {
		$shariff3uu_design['buttonsize'] = $shariff3uu['buttonsize'];
	}
	if ( isset( $shariff3uu['vertical'] ) ) {
		$shariff3uu_design['vertical'] = $shariff3uu['vertical'];
	}
	if ( isset( $shariff3uu['align'] ) ) {
		$shariff3uu_design['align'] = $shariff3uu['align'];
	}
	if ( isset( $shariff3uu['align_widget'] ) ) {
		$shariff3uu_design['align_widget'] = $shariff3uu['align_widget'];
	}
	if ( isset( $shariff3uu['style'] ) ) {
		$shariff3uu_design['style'] = $shariff3uu['style'];
	}

	// Advanced options.
	if ( isset( $shariff3uu['info_url'] ) ) {
		$shariff3uu_advanced['info_url'] = $shariff3uu['info_url'];
	}
	if ( isset( $shariff3uu['twitter_via'] ) ) {
		$shariff3uu_advanced['twitter_via'] = $shariff3uu['twitter_via'];
	}
	if ( isset( $shariff3uu['flattruser'] ) ) {
		$shariff3uu_advanced['flattruser'] = $shariff3uu['flattruser'];
	}
	if ( isset( $shariff3uu['default_pinterest'] ) ) {
		$shariff3uu_advanced['default_pinterest'] = $shariff3uu['default_pinterest'];
	}

	// Mailform options.
	if ( isset( $shariff3uu['mail_add_post_content'] ) ) {
		$GLOBALS['shariff3UU_mailform']['mail_add_post_content'] = $shariff3uu['mail_add_post_content'];
	}
	if ( isset( $shariff3uu['mail_sender_name'] ) ) {
		$GLOBALS['shariff3UU_mailform']['mail_sender_name'] = $shariff3uu['mail_sender_name'];
	}
	if ( isset( $shariff3uu['mail_sender_from'] ) ) {
		$GLOBALS['shariff3UU_mailform']['mail_sender_from'] = $shariff3uu['mail_sender_from'];
	}
	// Default options should be as save as possible, same reason the statistics are disabled by default.
	$GLOBALS['shariff3UU_mailform']['require_sender'] = 1;

	// Update global.
	$shariff3uu = array_merge( $shariff3uu_basic, $shariff3uu_design, $shariff3uu_advanced, $GLOBALS['shariff3UU_mailform'] );

	// Update version.
	$shariff3uu['version'] = '2.3.0';
}

/**
 * Migration < 3.3
 * Update options that were moved.
 * Delete old cache directory and db entry.
 */
if ( isset( $shariff3uu['version'] ) && -1 === version_compare( $shariff3uu['version'], '3.3.0' ) ) {

	// Update options that were moved.
	if ( isset( $shariff3uu['backend'] ) ) {
		$shariff3uu_statistic['backend'] = $shariff3uu['backend'];
	}
	if ( isset( $shariff3uu['fb_id'] ) ) {
		$shariff3uu_statistic['fb_id'] = $shariff3uu['fb_id'];
	}
	if ( isset( $shariff3uu['fb_secret'] ) ) {
		$shariff3uu_statistic['fb_secret'] = $shariff3uu['fb_secret'];
	}
	if ( isset( $shariff3uu['ttl'] ) ) {
		$shariff3uu_statistic['ttl'] = $shariff3uu['ttl'];
	}
	if ( isset( $shariff3uu['disable'] ) ) {
		$shariff3uu_statistic['disable'] = $shariff3uu['disable'];
	}

	// Delete old cache directory and db entry.
	if ( is_multisite() ) {
		global $wpdb;
		// phpcs:ignore
		$blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
		if ( $blogs ) {
			foreach ( $blogs as $blog ) {
				// Switch to each blog.
				switch_to_blog( $blog['blog_id'] );
				// Delete cache dir.
				shariff_removeoldcachedir();
				// Delete old db entry.
				delete_option( 'shariff3UU' );
				// Switch back to main.
				restore_current_blog();
			}
		}
	} else {
		// Delete cache dir.
		shariff_removeoldcachedir();
		// Delete old db entry.
		delete_option( 'shariff3UU' );
	}

	// Disable Twitter backend due to new service OpenShareCount.com.
	$shariff3uu_statistic['disable']['twitter'] = 1;

	// Update version.
	$shariff3uu['version'] = '3.3.0';
}

/**
 * Helper function to delete old cache directory.
 */
function shariff_removeoldcachedir() {
	$upload_dir = wp_upload_dir();
	$cache_dir  = $upload_dir['basedir'] . '/1970/01';
	$cache_dir2 = $upload_dir['basedir'] . '/1970';
	shariff_removeoldfiles( $cache_dir );
	// Remove /1970/01 and /1970 if they are empty.
	rmdir( $cache_dir );
	rmdir( $cache_dir2 );
}

/**
 * Helper function to delete old .dat files that begin with 'Shariff' and empty folders that also start with 'Shariff'.
 *
 * @param string $directory Path to Shariff cache directory.
 */
function shariff_removeoldfiles( $directory ) {
	foreach ( glob( '{$directory}/Shariff* ' ) as $file ) {
		if ( is_dir( $file ) ) {
			shariff_removeoldfiles( $file );
		} elseif ( substr( $file, -4 ) === '.dat' ) {
			unlink( $file );
		}
	}
	rmdir( $directory );
}

/**
 * Migration < 4.0
 */
if ( isset( $shariff3uu['version'] ) && -1 === version_compare( $shariff3uu['version'], '4.0.0' ) ) {

	// Set new option share counts, if statistic is enabled.
	if ( isset( $shariff3uu_statistic['backend'] ) ) {
		$shariff3uu_statistic['sharecounts'] = 1;
	}

	// Disable share counts if WP version < 4.4.
	if ( version_compare( get_bloginfo( 'version' ), '4.4.0' ) < 1 ) {
		unset( $shariff3uu_statistic['backend'] );
	}

	// Change button language to WordPress language, if it is set to auto and http_negotiate_language is not available (auto will not work without it).
	if ( ! isset( $shariff3uu_design['lang'] ) && ! function_exists( 'http_negotiate_language' ) ) {
		$shariff3uu_design['lang'] = substr( get_bloginfo( 'language' ), 0, 2 );
	}

	// Update version.
	$shariff3uu['version'] = '4.0.0';
}

/**
 * Migration < 4.2
 */
if ( isset( $shariff3uu['version'] ) && -1 === version_compare( $shariff3uu['version'], '4.2.0' ) ) {
	// Make sure we have the $wpdb class ready.
	global $wpdb;

	// Delete user meta entry shariff3UU_ignore_notice to display update message again after an update (check for multisite).
	if ( is_multisite() ) {
		// phpcs:ignore
		$blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
		if ( $blogs ) {
			foreach ( $blogs as $blog ) {
				// Switch to each blog.
				switch_to_blog( $blog['blog_id'] );
				// Delete user meta entry shariff3UU_ignore_notice.
				$users = get_users( 'role=administrator' );
				foreach ( $users as $user ) {
					if ( get_user_meta( $user->ID, 'shariff3UU_ignore_notice', true ) ) {
						delete_user_meta( $user->ID, 'shariff3UU_ignore_notice' );
					}
				}
				// Switch back to main.
				restore_current_blog();
			}
		}
	} else {
		// Delete user meta entry shariff3UU_ignore_notice.
		$users = get_users( 'role=administrator' );
		foreach ( $users as $user ) {
			if ( get_user_meta( $user->ID, 'shariff3UU_ignore_notice', true ) ) {
				delete_user_meta( $user->ID, 'shariff3UU_ignore_notice' );
			}
		}
	}
}

/**
 * Migration < 4.5
 */
if ( isset( $shariff3uu['version'] ) && -1 === version_compare( $shariff3uu['version'], '4.5.0' ) ) {
	// Update language settings.
	if ( ! isset( $shariff3uu_design['lang'] ) ) {
		$shariff3uu_design['autolang'] = 1;
		$shariff3uu_design['lang']     = substr( get_locale(), 0, 2 );
	}
	// Update version.
	$shariff3uu['version'] = '4.5.0';
}

/**
 * General tasks we do on every update, like clean up transients and so on.
 */

// Purge transients (check for multisite).
if ( is_multisite() ) {
	global $wpdb;
	// phpcs:ignore
	$blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
	if ( $blogs ) {
		foreach ( $blogs as $blog ) {
			// Switch to each blog.
			switch_to_blog( $blog['blog_id'] );
			// Purge transients.
			shariff3uu_purge_transients();
			// Switch back to main.
			restore_current_blog();
		}
	}
} else {
	// Purge transients.
	shariff3uu_purge_transients();
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
	// phpcs:disable
	$wpdb->query( 'DELETE FROM ' . $wpdb->options . ' WHERE option_name LIKE "_transient_timeout_shariff%"' );
	$wpdb->query( 'DELETE FROM ' . $wpdb->options . ' WHERE option_name LIKE "_transient_shariff%"' );
	// phpcs:enable
	// Clear object cache.
	wp_cache_flush();
}

// Set new version.
$shariff3uu['version']       = $code_version;
$shariff3uu_basic['version'] = $code_version;

/**
 * Remove empty elements and save to options table. We had a mix up with shariff3uu and shariff3UU in the past and update_option is not case senitive. Therefore we actually need to delete and recreate it.
 */
// Basic.
delete_option( 'shariff3uu_basic' );
$shariff3uu_basic = array_filter( $shariff3uu_basic );
update_option( 'shariff3uu_basic', $shariff3uu_basic );
// Design.
delete_option( 'shariff3uu_design' );
$shariff3uu_design = array_filter( $shariff3uu_design );
update_option( 'shariff3uu_design', $shariff3uu_design );
// Advanced.
delete_option( 'shariff3uu_advanced' );
$shariff3uu_advanced = array_filter( $shariff3uu_advanced );
update_option( 'shariff3uu_advanced', $shariff3uu_advanced );
// Statistic.
delete_option( 'shariff3uu_statistic' );
$shariff3uu_statistic = array_filter( $shariff3uu_statistic );
update_option( 'shariff3uu_statistic', $shariff3uu_statistic );

// Remove old settings.
delete_option( 'shariff3UU_mailform' );
delete_option( 'shariff3UU_hide_update_notice' );

// Remove Shariff cron job and add it again, if wanted.
wp_clear_scheduled_hook( 'shariff3uu_fill_cache' );
do_action( 'shariff3uu_save_statistic_options' );
