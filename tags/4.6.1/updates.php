<?php
/**
 * Will be included by shariff3UU_update() only if needed.
 * Put all update task here. Make sure that all "older" updates are checked first.
 * At least you must set $GLOBALS["shariff3UU"]["version"] = [YOUR VERSION]; to avoid includes later on.
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
if ( isset( $GLOBALS['shariff3UU']['version'] ) && -1 === version_compare( $GLOBALS['shariff3UU']['version'], '2.2.5' ) ) {

	// Basic options.
	if ( isset( $GLOBALS['shariff3UU']['version'] ) ) {
		$GLOBALS['shariff3UU_basic']['version'] = $GLOBALS['shariff3UU']['version'];
	}
	if ( isset( $GLOBALS['shariff3UU']['services'] ) ) {
		$GLOBALS['shariff3UU_basic']['services'] = $GLOBALS['shariff3UU']['services'];
	}
	if ( isset( $GLOBALS['shariff3UU']['add_after_all_posts'] ) ) {
		$GLOBALS['shariff3UU_basic']['add_after']['posts'] = $GLOBALS['shariff3UU']['add_after_all_posts'];
	}
	if ( isset( $GLOBALS['shariff3UU']['add_after_all_overview'] ) ) {
		$GLOBALS['shariff3UU_basic']['add_after']['posts_blogpage'] = $GLOBALS['shariff3UU']['add_after_all_overview'];
	}
	if ( isset( $GLOBALS['shariff3UU']['add_after_all_pages'] ) ) {
		$GLOBALS['shariff3UU_basic']['add_after']['pages'] = $GLOBALS['shariff3UU']['add_after_all_pages'];
	}
	if ( isset( $GLOBALS['shariff3UU']['add_after_all_custom_type'] ) ) {
		$GLOBALS['shariff3UU_basic']['add_after']['custom_type'] = $GLOBALS['shariff3UU']['add_after_all_custom_type'];
	}
	if ( isset( $GLOBALS['shariff3UU']['add_before_all_posts'] ) ) {
		$GLOBALS['shariff3UU_basic']['add_before']['posts'] = $GLOBALS['shariff3UU']['add_before_all_posts'];
	}
	if ( isset( $GLOBALS['shariff3UU']['add_before_all_overview'] ) ) {
		$GLOBALS['shariff3UU_basic']['add_before']['posts_blogpage'] = $GLOBALS['shariff3UU']['add_before_all_overview'];
	}
	if ( isset( $GLOBALS['shariff3UU']['add_before_all_pages'] ) ) {
		$GLOBALS['shariff3UU_basic']['add_before']['pages'] = $GLOBALS['shariff3UU']['add_before_all_pages'];
	}
	if ( isset( $GLOBALS['shariff3UU']['disable_on_protected'] ) ) {
		$GLOBALS['shariff3UU_basic']['disable_on_protected'] = $GLOBALS['shariff3UU']['disable_on_protected'];
	}
	if ( isset( $GLOBALS['shariff3UU']['backend'] ) ) {
		$GLOBALS['shariff3UU_basic']['backend'] = $GLOBALS['shariff3UU']['backend'];
	}

	// Design options.
	if ( isset( $GLOBALS['shariff3UU']['language'] ) ) {
		$GLOBALS['shariff3UU_design']['language'] = $GLOBALS['shariff3UU']['language'];
	}
	if ( isset( $GLOBALS['shariff3UU']['theme'] ) ) {
		$GLOBALS['shariff3UU_design']['theme'] = $GLOBALS['shariff3UU']['theme'];
	}
	if ( isset( $GLOBALS['shariff3UU']['buttonsize'] ) ) {
		$GLOBALS['shariff3UU_design']['buttonsize'] = $GLOBALS['shariff3UU']['buttonsize'];
	}
	if ( isset( $GLOBALS['shariff3UU']['vertical'] ) ) {
		$GLOBALS['shariff3UU_design']['vertical'] = $GLOBALS['shariff3UU']['vertical'];
	}
	if ( isset( $GLOBALS['shariff3UU']['align'] ) ) {
		$GLOBALS['shariff3UU_design']['align'] = $GLOBALS['shariff3UU']['align'];
	}
	if ( isset( $GLOBALS['shariff3UU']['align_widget'] ) ) {
		$GLOBALS['shariff3UU_design']['align_widget'] = $GLOBALS['shariff3UU']['align_widget'];
	}
	if ( isset( $GLOBALS['shariff3UU']['style'] ) ) {
		$GLOBALS['shariff3UU_design']['style'] = $GLOBALS['shariff3UU']['style'];
	}

	// Advanced options.
	if ( isset( $GLOBALS['shariff3UU']['info_url'] ) ) {
		$GLOBALS['shariff3UU_advanced']['info_url'] = $GLOBALS['shariff3UU']['info_url'];
	}
	if ( isset( $GLOBALS['shariff3UU']['twitter_via'] ) ) {
		$GLOBALS['shariff3UU_advanced']['twitter_via'] = $GLOBALS['shariff3UU']['twitter_via'];
	}
	if ( isset( $GLOBALS['shariff3UU']['flattruser'] ) ) {
		$GLOBALS['shariff3UU_advanced']['flattruser'] = $GLOBALS['shariff3UU']['flattruser'];
	}
	if ( isset( $GLOBALS['shariff3UU']['default_pinterest'] ) ) {
		$GLOBALS['shariff3UU_advanced']['default_pinterest'] = $GLOBALS['shariff3UU']['default_pinterest'];
	}

	// Mailform options.
	if ( isset( $GLOBALS['shariff3UU']['mail_add_post_content'] ) ) {
		$GLOBALS['shariff3UU_mailform']['mail_add_post_content'] = $GLOBALS['shariff3UU']['mail_add_post_content'];
	}
	if ( isset( $GLOBALS['shariff3UU']['mail_sender_name'] ) ) {
		$GLOBALS['shariff3UU_mailform']['mail_sender_name'] = $GLOBALS['shariff3UU']['mail_sender_name'];
	}
	if ( isset( $GLOBALS['shariff3UU']['mail_sender_from'] ) ) {
		$GLOBALS['shariff3UU_mailform']['mail_sender_from'] = $GLOBALS['shariff3UU']['mail_sender_from'];
	}
	// Default options should be as save as possible, same reason the statistics are disabled by default.
	$GLOBALS['shariff3UU_mailform']['require_sender'] = 1;

	// Update global.
	$GLOBALS['shariff3UU'] = array_merge( $GLOBALS['shariff3UU_basic'], $GLOBALS['shariff3UU_design'], $GLOBALS['shariff3UU_advanced'], $GLOBALS['shariff3UU_mailform'] );

	// Update version.
	$GLOBALS['shariff3UU']['version'] = '2.3.0';
}

/**
 * Migration < 3.3
 * Update options that were moved.
 * Delete old cache directory and db entry.
 */
if ( isset( $GLOBALS['shariff3UU']['version'] ) && -1 === version_compare( $GLOBALS['shariff3UU']['version'], '3.3.0' ) ) {

	// Update options that were moved.
	if ( isset( $GLOBALS['shariff3UU']['backend'] ) ) {
		$GLOBALS['shariff3UU_statistic']['backend'] = $GLOBALS['shariff3UU']['backend'];
	}
	if ( isset( $GLOBALS['shariff3UU']['fb_id'] ) ) {
		$GLOBALS['shariff3UU_statistic']['fb_id'] = $GLOBALS['shariff3UU']['fb_id'];
	}
	if ( isset( $GLOBALS['shariff3UU']['fb_secret'] ) ) {
		$GLOBALS['shariff3UU_statistic']['fb_secret'] = $GLOBALS['shariff3UU']['fb_secret'];
	}
	if ( isset( $GLOBALS['shariff3UU']['ttl'] ) ) {
		$GLOBALS['shariff3UU_statistic']['ttl'] = $GLOBALS['shariff3UU']['ttl'];
	}
	if ( isset( $GLOBALS['shariff3UU']['disable'] ) ) {
		$GLOBALS['shariff3UU_statistic']['disable'] = $GLOBALS['shariff3UU']['disable'];
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
	$GLOBALS['shariff3UU_statistic']['disable']['twitter'] = 1;

	// Update version.
	$GLOBALS['shariff3UU']['version'] = '3.3.0';
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
if ( isset( $GLOBALS['shariff3UU']['version'] ) && -1 === version_compare( $GLOBALS['shariff3UU']['version'], '4.0.0' ) ) {

	// Set new option share counts, if statistic is enabled.
	if ( isset( $GLOBALS['shariff3UU_statistic']['backend'] ) ) {
		$GLOBALS['shariff3UU_statistic']['sharecounts'] = 1;
	}

	// Disable share counts if WP version < 4.4.
	if ( version_compare( get_bloginfo( 'version' ), '4.4.0' ) < 1 ) {
		unset( $GLOBALS['shariff3UU_statistic']['backend'] );
	}

	// Change button language to WordPress language, if it is set to auto and http_negotiate_language is not available (auto will not work without it).
	if ( ! isset( $GLOBALS['shariff3UU_design']['lang'] ) && ! function_exists( 'http_negotiate_language' ) ) {
		$GLOBALS['shariff3UU_design']['lang'] = substr( get_bloginfo( 'language' ), 0, 2 );
	}

	// Update version.
	$GLOBALS['shariff3UU']['version'] = '4.0.0';
}

/**
 * Migration < 4.2
 */
if ( isset( $GLOBALS['shariff3UU']['version'] ) && -1 === version_compare( $GLOBALS['shariff3UU']['version'], '4.2.0' ) ) {
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
if ( isset( $GLOBALS['shariff3UU']['version'] ) && -1 === version_compare( $GLOBALS['shariff3UU']['version'], '4.5.0' ) ) {
	// Update language settings.
	if ( ! isset( $GLOBALS['shariff3UU_design']['lang'] ) ) {
		$GLOBALS['shariff3UU_design']['autolang'] = 1;
		$GLOBALS['shariff3UU_design']['lang']     = substr( get_locale(), 0, 2 );
	}
	// Update version.
	$GLOBALS['shariff3UU']['version'] = '4.5.0';
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
$GLOBALS['shariff3uu']['version']       = $code_version;
$GLOBALS['shariff3uu_basic']['version'] = $code_version;

/**
 * Remove empty elements and save to options table.
 */
// Basic.
delete_option( 'shariff3uu_basic' );
$shariff3uu_basic = array_filter( $GLOBALS['shariff3uu_basic'] );
update_option( 'shariff3uu_basic', $shariff3uu_basic );
// Design.
delete_option( 'shariff3uu_design' );
$shariff3uu_design = array_filter( $GLOBALS['shariff3uu_design'] );
update_option( 'shariff3uu_design', $shariff3uu_design );
// Advanced.
delete_option( 'shariff3uu_advanced' );
$shariff3uu_advanced = array_filter( $GLOBALS['shariff3uu_advanced'] );
update_option( 'shariff3uu_advanced', $shariff3uu_advanced );
// Statistic.
delete_option( 'shariff3uu_statistic' );
$shariff3uu_statistic = array_filter( $GLOBALS['shariff3uu_statistic'] );
update_option( 'shariff3uu_statistic', $shariff3uu_statistic );

// Remove old settings.
delete_option( 'shariff3UU_mailform' );
delete_option( 'shariff3UU_hide_update_notice' );

// Remove Shariff cron job and add it again, if wanted.
wp_clear_scheduled_hook( 'shariff3uu_fill_cache' );
do_action( 'shariff3uu_save_statistic_options' );
