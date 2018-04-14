<?php
/**
 * Plugin Name: Shariff Wrapper
 * Plugin URI: https://wordpress.org/plugins-wp/shariff/
 * Description: The Shariff Wrapper provides share buttons that respect the privacy of your visitors and are compliant to the General Data Protection Regulation (GDPR).
 * Version: 4.4.0.RC3
 * Author: Jan-Peter Lambeck & 3UU
 * Author URI: https://wordpress.org/plugins/shariff/
 * License: MIT
 * License URI: https://opensource.org/licenses/MIT
 * Text Domain: shariff
 *
 * @package WordPress
 */

// Prevent direct calls to shariff.php.
if ( ! class_exists( 'WP' ) ) {
	die();
}

// Get options (needed for front- and backend).
$shariff3uu_basic     = (array) get_option( 'shariff3uu_basic' );
$shariff3uu_design    = (array) get_option( 'shariff3uu_design' );
$shariff3uu_advanced  = (array) get_option( 'shariff3uu_advanced' );
$shariff3uu_statistic = (array) get_option( 'shariff3uu_statistic' );

// Force the creation as a global variable in order to work with WP-CLI.
global $shariff3uu;
$shariff3uu = array_merge( $shariff3uu_basic, $shariff3uu_design, $shariff3uu_advanced, $shariff3uu_statistic );

/**
 * Update function to perform tasks _once_ after an update, based on version number to work for automatic as well as manual updates.
 */
function shariff3uu_update() {
	// Adjust code version.
	$code_version = '4.4.0.RC3';

	// Get options.
	$shariff3uu = $GLOBALS['shariff3uu'];

	// Check if the installed version is older than the code version and include updates.php if necessary.
	if ( empty( $shariff3uu['version'] ) || ( isset( $shariff3uu['version'] ) && version_compare( $shariff3uu['version'], $code_version ) === -1 ) ) {
		// Include updates.php.
		include dirname( __FILE__ ) . '/updates.php';
	}
}
add_action( 'admin_init', 'shariff3uu_update' );

/** Require Shariff Widget. */
require dirname( __FILE__ ) . '/includes/class-shariff-widget.php';

// Allowed tags for headline.
$allowed_tags = array(
	// Direct formatting e.g. <strong>.
	'strong' => array(),
	'em'     => array(),
	'b'      => array(),
	'i'      => array(),
	'br'     => array(),
	// Elements that can be formatted via CSS.
	'span'   => array(
		'class' => array(),
		'style' => array(),
		'id'    => array(),
	),
	'div'    => array(
		'class' => array(),
		'style' => array(),
		'id'    => array(),
	),
	'p'      => array(
		'class' => array(),
		'style' => array(),
		'id'    => array(),
	),
	'h1'     => array(
		'class' => array(),
		'style' => array(),
		'id'    => array(),
	),
	'h2'     => array(
		'class' => array(),
		'style' => array(),
		'id'    => array(),
	),
	'h3'     => array(
		'class' => array(),
		'style' => array(),
		'id'    => array(),
	),
	'h4'     => array(
		'class' => array(),
		'style' => array(),
		'id'    => array(),
	),
	'h5'     => array(
		'class' => array(),
		'style' => array(),
		'id'    => array(),
	),
	'h6'     => array(
		'class' => array(),
		'style' => array(),
		'id'    => array(),
	),
	'hr'     => array(
		'class' => array(),
		'style' => array(),
		'id'    => array(),
	),
);

/** Admin options */
if ( is_admin() ) {
	// Include admin_menu.php.
	include dirname( __FILE__ ) . '/admin/admin-menu.php';
	// Include admin_notices.php.
	include dirname( __FILE__ ) . '/admin/admin-notices.php';
}

/** Custom meta box */
function shariff3uu_include_metabox() {
	// Check if user is allowed to publish posts.
	if ( current_user_can( 'publish_posts' ) ) {
		// Include admin_metabox.php.
		include dirname( __FILE__ ) . '/admin/admin-metabox.php';
	}
}
// Check if meta box has been disabled in the options, if not add_action.
if ( ! isset( $shariff3uu['disable_metabox'] ) || isset( $shariff3uu['disable_metabox'] ) && 1 !== $shariff3uu['disable_metabox'] ) {
	add_action( 'init', 'shariff3uu_include_metabox' );
}

/**
 * Add meta links (settings and support forum) to our entry on the plugin page.
 *
 * @param array  $links Array of all current links.
 * @param string $file Path to plugin of the current element.
 *
 * @return array New array including our links to settings and support forum.
 */
function shariff3uu_meta_links( $links, $file ) {
	$plugin = plugin_basename( __FILE__ );
	// Create link.
	if ( $file === $plugin ) {
		return array_merge(
			$links,
			array( '<a href="' . home_url() . '/wp-admin/options-general.php?page=shariff3uu">' . __( 'Settings', 'shariff' ) . '</a>', '<a href="https://wordpress.org/support/plugin/shariff" target="_blank">' . __( 'Support Forum', 'shariff' ) . '</a>' )
		);
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'shariff3uu_meta_links', 10, 2 );

/** Initialize translations. */
function shariff_init_locale() {
	if ( function_exists( 'load_plugin_textdomain' ) ) {
		load_plugin_textdomain( 'shariff' );
	}
}

/** Register the wp rest api route and sanitize the input */
function shariff3uu_sanitize_api() {
	register_rest_route( 'shariff/v1', '/share_counts', array(
		'methods'  => 'GET',
		'callback' => 'shariff3uu_share_counts',
		'args'     => array(
			'url'       => array(
				'sanitize_callback' => 'esc_url',
				'required'          => true,
				'description'       => __( 'URL of the post or page to request share counts for.', 'shariff' ),
			),
			'services'  => array(
				'sanitize_callback' => 'sanitize_text_field',
				'required'          => true,
				'description'       => __( 'A list of services separated by |. Example: twitter|facebook|xing', 'shariff' ),
			),
			'timestamp' => array(
				'sanitize_callback' => 'absint',
				'description'       => __( 'Timestamp of the last update of the post. Used for dynamic cache lifespan.', 'shariff' ),
			),
		),
	) );
}
add_action( 'rest_api_init', 'shariff3uu_sanitize_api' );

/**
 * Provide share counts via the wp rest api.
 *
 * @param WP_REST_Request $request Incoming request.
 *
 * @return string Returns the share counts for all requested services if available or an error message.
 */
function shariff3uu_share_counts( WP_REST_Request $request ) {
	// Get options.
	$shariff3uu = $GLOBALS['shariff3uu'];

	// Setup Parameters.
	$url       = urldecode( $request['url'] );
	$services  = $request['services'];
	$timestamp = $request['timestamp'];

	// Exit if no url is provided and provide an error message.
	if ( empty( $url ) || 'undefined' === $url ) {
		return new WP_Error( 'nourl', 'No URL provided!', array( 'status' => 400 ) );
	}

	// Exit if no services are provided and provide an error message.
	if ( empty( $services ) || 'undefined' === $services ) {
		return new WP_Error( 'noservices', 'No services provided!', array( 'status' => 400 ) );
	}

	// Make sure that the provided url matches the WordPress domain.
	$get_url = wp_parse_url( $url );
	$wp_url  = wp_parse_url( get_bloginfo( 'url' ) );
	// On an external backend check allowed hosts, else compare that domain is equal.
	if ( defined( 'SHARIFF_FRONTENDS' ) ) {
		$shariff_frontends = array_flip( explode( '|', SHARIFF_FRONTENDS ) );
		if ( ! isset( $get_url['host'] ) || ! array_key_exists( $get_url['host'], $shariff_frontends ) ) {
			return new WP_Error( 'externaldomainnotallowed', 'External domain not allowed by this server!', array( 'status' => 400 ) );
		}
	} elseif ( ! isset( $get_url['host'] ) || $get_url['host'] !== $wp_url['host'] ) {
		return new WP_Error( 'domainnotallowed', 'Domain not allowed by this server!', array( 'status' => 400 ) );
	}

	// Encode the shareurl.
	$post_url     = rawurlencode( esc_url( $url ) );
	$post_url_raw = $url;

	// Set transient name.
	// Transient names can only contain 40 characters, therefore we use a hash (md5 always creates a 32 character hash).
	// We need a prefix so we can clean up on uninstall and updates.
	$post_hash = 'shariff' . hash( 'md5', $post_url );

	// Check for ttl option, must be between 60 and 7200 seconds.
	if ( isset( $shariff3uu['ttl'] ) ) {
		$ttl = absint( $shariff3uu['ttl'] );
		// Make sure ttl is a reasonable number.
		if ( $ttl < '61' ) {
			$ttl = '60';
		} elseif ( $ttl > '7200' ) {
			$ttl = '7200';
		}
	} else {
		// Else set it to new default (five minutes).
		$ttl = '300';
	}

	// Adjust ttl based on the post age.
	if ( isset( $timestamp ) && ( ! isset( $shariff3uu['disable_dynamic_cache'] ) || ( isset( $shariff3uu['disable_dynamic_cache'] ) && 1 !== $shariff3uu['disable_dynamic_cache'] ) ) ) {
		// The timestamp represents the last time the post or page was modified.
		$post_time    = intval( $timestamp );
		$current_time = current_time( 'timestamp', true );
		$post_age     = round( abs( $current_time - $post_time ) );
		if ( $post_age > '0' ) {
			$post_age_days = round( $post_age / 60 / 60 / 24 );
			// Make sure ttl base is not getting too high.
			if ( $ttl > '300' ) {
				$ttl = '300';
			}
			$ttl = round( ( $ttl + $post_age_days * 3 ) * ( $post_age_days * 2 ) );
		}

		// Set minimum ttl to 60 seconds and maximum ttl to one week.
		if ( $ttl < '60' ) {
			$ttl = '60';
		} elseif ( $ttl > '604800' ) {
			$ttl = '604800';
		}

		// In case we get a timestamp older than 01.01.2000 or for example a 0, use a reasonable default value of five minutes.
		if ( $post_time < '946684800' ) {
			$ttl = '300';
		}
	}

	// Set the default value.
	$need_update = false;

	// Remove totalnumber for array.
	$real_services = str_replace( 'totalnumber|', '', $services );
	$real_services = str_replace( '|totalnumber', '', $real_services );

	// Explode services.
	$service_array = explode( '|', $real_services );

	// Remove duplicated entries.
	$service_array = array_unique( $service_array );

	// Get old share counts.
	if ( get_transient( $post_hash ) !== false ) {
		$old_share_counts = get_transient( $post_hash );
	} else {
		$old_share_counts = array();
	}

	// Check if we need to update.
	if ( get_transient( $post_hash ) !== false ) {
		// Check timestamp.
		$diff = current_time( 'timestamp', true ) - $old_share_counts['timestamp'];
		if ( $diff > $ttl ) {
			$need_update = true;
		}
		// Check if we have a different set of services than stored in the cache.
		$diff_array = array_diff_key( array_flip( $service_array ), $old_share_counts );
		if ( ! empty( $diff_array ) ) {
			$need_update = true;
			// We only need to update the missing service.
			$service_array = array_flip( $diff_array );
		}
	} else {
		$need_update = true;
	}

	// Prevent php notices if debug mode is enabled.
	$response     = '';
	$share_counts = array();

	// If we do not need an update, use stored data.
	if ( false === $need_update ) {
		$share_counts = $old_share_counts;
		// Provide update info for debugging and support.
		$share_counts['updated'] = '0';
	} elseif ( 'totalnumber' === $services ) {
		// If only totalnumber is requested we only use cached data.
		$share_counts = $old_share_counts;
	} elseif ( isset( $shariff3uu['external_host'] ) && ! empty( $shariff3uu['external_host'] ) ) {
		// Check if we want to use an external API.
		$response = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( $shariff3uu['external_host'] . '?url=' . rawurlencode( $url ) . '&services=' . $services . '&timestamp=' . $timestamp . '"' ) ) );
		// Decode response.
		$share_counts = json_decode( $response, true );
		// Save transient.
		set_transient( $post_hash, $share_counts, '604800' );
		// Offer a hook to work with the share counts.
		do_action( 'shariff_share_counts', $share_counts );
	} else {
		// Else we fetch new counts ourselves.
		$share_counts = shariff3uu_fetch_sharecounts( $service_array, $old_share_counts, $post_hash, $post_url_raw );
	}

	// Return results, if we have some or an error message if not.
	if ( isset( $share_counts ) && null !== $share_counts ) {
		return $share_counts;
	} else {
		return new WP_Error( 'nodata', 'Could not receive any data.', array( 'status' => 400 ) );
	}
}

/**
 *  Fetch share counts.
 *
 * @param array  $service_array Array with the desired services.
 * @param array  $old_share_counts Array of all already stored share counts.
 * @param string $post_hash MD5-Hash of the current post.
 * @param string $post_url_raw Raw URL of the current post.
 *
 * @return array Array of all fetched share counts.
 */
function shariff3uu_fetch_sharecounts( $service_array, $old_share_counts, $post_hash, $post_url_raw ) {
	// We only need the backend part from the service phps.
	$backend = 1;

	// Encode the shareurl.
	$post_url = rawurlencode( esc_url( $post_url_raw ) );

	// Get options.
	$shariff3uu = $GLOBALS['shariff3uu'];

	// Prevent php notices.
	$total_count  = 0;
	$share_counts = array();

	// Loop through all desired services.
	foreach ( $service_array as $service ) {
		// Only include services that are not disabled.
		if ( ! empty( $service ) && ( ! isset( $shariff3uu['disable'][ $service ] ) || ( isset( $shariff3uu['disable'][ $service ] ) && 0 === $shariff3uu['disable'][ $service ] ) ) ) {
			// Determine path.
			$path_service_file = dirname( __FILE__ ) . '/services/shariff-' . $service . '.php';
			// Include service files.
			if ( file_exists( $path_service_file ) ) {
				include $path_service_file;
			}
			// if we have an error (e.g. a timeout) and we have an old share count for this service, keep it!
			if ( array_key_exists( $service, $old_share_counts ) && ( ! array_key_exists( $service, $share_counts ) || empty( $share_counts[ $service ] ) ) ) {
				$share_counts[ $service ] = $old_share_counts[ $service ];
			}
		}
		// Calculate total share count.
		if ( isset( $share_counts[ $service ] ) ) {
			$total_count = $total_count + $share_counts[ $service ];
		}
	}

	// Add total count.
	if ( 0 !== $total_count ) {
		$share_counts['total'] = $total_count;
	}

	// Save transient, if we have counts.
	if ( isset( $share_counts ) ) {
		// Add current timestamp and url.
		$share_counts['timestamp'] = current_time( 'timestamp', true );
		$share_counts['url']       = $post_url_raw;
		// Combine different set of services.
		if ( get_transient( $post_hash ) !== false ) {
			$other_request = get_transient( $post_hash );
			$share_counts  = array_merge( $other_request, $share_counts );
		}
		// Save transient.
		set_transient( $post_hash, $share_counts, '604800' );
		// Offer a hook to work with the share counts.
		do_action( 'shariff_share_counts', $share_counts );
		// Update info.
		$share_counts['updated'] = 1;
	} elseif ( isset( $old_share_counts ) ) {
		$share_counts = $old_share_counts;
		// Update info.
		$share_counts['updated'] = '0';
	}

	// Return share counts.
	return $share_counts;
}

/**
 * Fills cache automatically.
 */
function shariff3uu_fill_cache() {
	// Amount of posts - set to 100 if not set.
	if ( isset( $GLOBALS['shariff3uu']['ranking'] ) && absint( $GLOBALS['shariff3uu']['ranking'] ) > '0' ) {
		$numberposts = absint( $GLOBALS['shariff3uu']['ranking'] );
	} else {
		$numberposts = '100';
	}

	// Avoid errors if no services are given - instead use the default set of services.
	if ( empty( $GLOBALS['shariff3uu']['services'] ) ) {
		$services = 'twitter|facebook|googleplus';
	} else {
		$services = $GLOBALS['shariff3uu']['services'];
	}

	// Explode services.
	$service_array = explode( '|', $services );

	// Arguments for wp_get_recent_posts().
	$args = array(
		'numberposts'      => $numberposts,
		'orderby'          => 'post_date',
		'order'            => 'DESC',
		'post_status'      => 'publish',
		'suppress_filters' => false,
	);

	// Catch last 100 posts or whatever number is set for it.
	$recent_posts = wp_get_recent_posts( $args );
	if ( $recent_posts ) {
		foreach ( $recent_posts as $recent ) {
			// Get the url.
			$url      = get_permalink( $recent['ID'] );
			$post_url = rawurlencode( $url );
			// Set transient name.
			$post_hash = 'shariff' . hash( 'md5', $post_url );
			// Get old share counts.
			if ( get_transient( $post_hash ) !== false ) {
				$old_share_counts = get_transient( $post_hash );
			} else {
				$old_share_counts = array();
			}
			// Fetch share counts and save them.
			shariff3uu_fetch_sharecounts( $service_array, $old_share_counts, $post_hash, $url );
		}
	}
}
add_action( 'shariff3uu_fill_cache', 'shariff3uu_fill_cache' );

/**
 * Adds schedule event in order to fill cache automatically.
 */
function shariff3uu_fill_cache_schedule() {
	// Get options manually bc of start on activation.
	$shariff3uu_statistic = (array) get_option( 'shariff3uu_statistic' );
	// Check if option is set.
	if ( isset( $shariff3uu_statistic['automaticcache'] ) && 1 === $shariff3uu_statistic['automaticcache'] ) {
		// Check if job is already scheduled.
		if ( ! wp_next_scheduled( 'shariff3uu_fill_cache' ) ) {
			// Add cron job.
			wp_schedule_event( time(), 'weekly', 'shariff3uu_fill_cache' );
		}
	} else {
		// Else option is not set therefore remove cron job if scheduled.
		if ( wp_next_scheduled( 'shariff3uu_fill_cache' ) ) {
			// Remove cron job.
			wp_clear_scheduled_hook( 'shariff3uu_fill_cache' );
		}
	}
}
add_action( 'shariff3uu_save_statistic_options', 'shariff3uu_fill_cache_schedule' );

/** Registers activation hook to start cron job after an update. */
register_activation_hook( __FILE__, 'shariff3uu_fill_cache_schedule' );

/**
 * Adds custom weekly cron recurrences.
 *
 * @param array $schedules Array of existing schedules.
 *
 * @return array Updated array including our new schedule.
 */
function shariff3uu_fill_cache_schedule_custom_recurrence( $schedules ) {
	$schedules['weekly'] = array(
		'display'  => __( 'Once weekly', 'shariff' ),
		'interval' => 804600,
	);
	return $schedules;
}
add_filter( 'cron_schedules', 'shariff3uu_fill_cache_schedule_custom_recurrence' );

/**
 * Adds short tag to posts and pages (including custom post types).
 *
 * @param string $content The current post content.
 *
 * @return string The post content including our short tag.
 */
function shariff3uu_posts( $content ) {
	// Get options.
	$shariff3uu = $GLOBALS['shariff3uu'];

	// Do not add Shariff to excerpts or outside the loop, if option is checked.
	if ( in_array( 'get_the_excerpt', $GLOBALS['wp_current_filter'], true ) || ( ! in_the_loop() && isset( $shariff3uu['disable_outside_loop'] ) && 1 === $shariff3uu['disable_outside_loop'] ) ) {
		return $content;
	}

	// Disable share buttons on password protected posts if configured in the admin menu.
	if ( ( 1 === post_password_required( get_the_ID() ) || ! empty( $GLOBALS['post']->post_password ) ) && isset( $shariff3uu['disable_on_protected'] ) && 1 === $shariff3uu['disable_on_protected'] ) {
		$shariff3uu['add_before']['posts']          = 0;
		$shariff3uu['add_before']['posts_blogpage'] = 0;
		$shariff3uu['add_before']['pages']          = 0;
		$shariff3uu['add_after']['posts']           = 0;
		$shariff3uu['add_after']['posts_blogpage']  = 0;
		$shariff3uu['add_after']['pages']           = 0;
		$shariff3uu['add_after']['custom_type']     = 0;
	}

	// If we want to see it as text - replace the slash.
	if ( true === strpos( $content, '/hideshariff' ) ) {
		$content = str_replace( '/hideshariff', 'hideshariff', $content );
	} elseif ( true === strpos( $content, 'hideshariff' ) ) {
		// Remove the sign.
		$content = str_replace( 'hideshariff', '', $content );
		// Return the content without adding Shariff.
		return $content;
	}

	// Type of current post.
	$current_post_type = get_post_type();
	if ( 'post' === $current_post_type ) {
		$current_post_type = 'posts';
	}

	// Prevent php warnings in debug mode.
	$add_before = 0;
	$add_after  = 0;

	// Check if shariff should be added automatically (plugin options).
	if ( ! is_singular() ) {
		// On blog page.
		if ( isset( $shariff3uu['add_before']['posts_blogpage'] ) && 1 === $shariff3uu['add_before']['posts_blogpage'] ) {
			$add_before = 1;
		}
		if ( isset( $shariff3uu['add_after']['posts_blogpage'] ) && 1 === $shariff3uu['add_after']['posts_blogpage'] ) {
			$add_after = 1;
		}
	} elseif ( is_singular( 'post' ) ) {
		// On single post.
		if ( isset( $shariff3uu['add_before'][ $current_post_type ] ) && 1 === $shariff3uu['add_before'][ $current_post_type ] ) {
			$add_before = 1;
		}
		if ( isset( $shariff3uu['add_after'][ $current_post_type ] ) && 1 === $shariff3uu['add_after'][ $current_post_type ] ) {
			$add_after = 1;
		}
	} elseif ( is_singular( 'page' ) ) {
		// On pages.
		if ( isset( $shariff3uu['add_before']['pages'] ) && 1 === $shariff3uu['add_before']['pages'] ) {
			$add_before = 1;
		}
		if ( isset( $shariff3uu['add_after']['pages'] ) && 1 === $shariff3uu['add_after']['pages'] ) {
			$add_after = 1;
		}
	} else {
		// On custom_post_types.
		$all_custom_post_types = get_post_types( array( '_builtin' => false ) );
		if ( is_array( $all_custom_post_types ) ) {
			$custom_types = array_keys( $all_custom_post_types );
			// Add shariff, if custom type and option checked in the admin menu.
			if ( isset( $shariff3uu['add_before'][ $current_post_type ] ) && 1 === $shariff3uu['add_before'][ $current_post_type ] ) {
				$add_before = 1;
			}
			if ( isset( $shariff3uu['add_after'][ $current_post_type ] ) && 1 === $shariff3uu['add_after'][ $current_post_type ] ) {
				$add_after = 1;
			}
		}
	}

	// Check if buttons are enabled on a single post or page via the meta box.
	if ( get_post_meta( get_the_ID(), 'shariff_metabox_before', true ) ) {
		$add_before = 1;
	}
	if ( get_post_meta( get_the_ID(), 'shariff_metabox_after', true ) ) {
		$add_after = 1;
	}

	// Add shariff.
	if ( 1 === $add_before ) {
		$content = '[shariff]' . $content;
	}
	if ( 1 === $add_after ) {
		$content .= '[shariff]';
	}

	// Return content.
	return $content;
}
if ( ! isset( $GLOBALS['shariff3uu']['shortcodeprio'] ) ) {
	$GLOBALS['shariff3uu']['shortcodeprio'] = '10';
}
add_filter( 'the_content', 'shariff3uu_posts', $GLOBALS['shariff3uu']['shortcodeprio'] );

/**
 * Add shorttag to excerpt.
 *
 * @param string $content The current content of the excerpt.
 *
 * @return string The new content of the post including the shorttag.
 */
function shariff3uu_excerpt( $content ) {
	// Get options.
	$shariff3uu = $GLOBALS['shariff3uu'];
	// Remove headline in post.
	if ( isset( $shariff3uu['headline'] ) ) {
		$content = str_replace( strip_tags( $shariff3uu['headline'] ), ' ', $content );
	}
	// Add shariff before the excerpt, if option checked in the admin menu.
	if ( isset( $shariff3uu['add_before']['excerpt'] ) && 1 === $shariff3uu['add_before']['excerpt'] ) {
		$content = do_shortcode( '[shariff]' ) . $content;
	}
	// Add shariff after the excerpt, if option checked in the admin menu.
	if ( isset( $shariff3uu['add_after']['excerpt'] ) && 1 === $shariff3uu['add_after']['excerpt'] ) {
		$content .= do_shortcode( '[shariff]' );
	}
	return $content;
}
add_filter( 'the_excerpt', 'shariff3uu_excerpt' );

/**
 * Removes hideshariff from content in cases of excerpts or other plain text usages.
 *
 * @param string $content The current content of the post.
 *
 * @return string The modified content without hideshariff.
 */
function shariff3uu_hideshariff( $content ) {
	if ( true === strpos( $content, 'hideshariff' ) ) {
		$content = str_replace( 'hideshariff', '', $content );
	}
	return $content;
}
add_filter( 'the_content', 'shariff3uu_hideshariff', 999 );

/**
 * Removes shariff from rss feeds.
 *
 * @param string $content The current content of the post.
 *
 * @return string The modified content without shariff.
 */
function shariff3uu_remove_from_rss( $content ) {
	$content = preg_replace( '/<div class="shariff\b[^>]*>(.*?)<\/div>/i', '', $content );
	$content = preg_replace( '/<div class="ShariffSC\b[^>]*>(.*?)<\/div>/i', '', $content );
	return $content;
}
add_filter( 'the_content_feed', 'shariff3uu_remove_from_rss', 999 );

/**
 * Adds shariff buttons after bbpress replies.
 */
function shariff3uu_bbp_add_shariff_after_replies() {
	// Get options.
	$shariff3uu = $GLOBALS['shariff3uu'];
	if ( isset( $shariff3uu['add_after']['bbp_reply'] ) && 1 === $shariff3uu['add_after']['bbp_reply'] ) {
		echo esc_html( shariff3uu_render( array() ) );
	}
}
add_action( 'bbp_theme_after_reply_content', 'shariff3uu_bbp_add_shariff_after_replies' );

/**
 * Function is called to include the shariff.css in the header of AMP pages.
 * Currently only called by the amp_post_template_css hook of the AMP plugin by Automatic.
 * We need to strip out all !important in order to pass AMP test.
 */
function shariff3uu_amp_css() {
	ob_start();
	include plugins_url( '/css/shariff.css', __FILE__ );
	$shariff_css = ob_get_clean();
	if ( false !== $shariff_css ) {
		echo esc_html( str_replace( '!important', '', $shariff_css ) );
	} else {
		include plugins_url( '/css/shariff.css', __FILE__ );
	}
}

// Registers the shortcode.
add_shortcode( 'shariff', 'shariff3uu_render' );

/**
 * Renders the shorttag to the HTML shorttag of Shariff.
 *
 * @param array $atts (optional) Array of shariff options provided in the shorttag.
 *
 * @return string|null The rendered HTML shorttag or null if a disable condition is met.
 */
function shariff3uu_render( $atts ) {
	// Get options.
	$shariff3uu = $GLOBALS['shariff3uu'];

	// Stops all further actions if we are on an admin page.
	if ( is_admin() ) {
		return null;
	}

	// Avoids errors if no attributes are given - instead uses the old set of services to make it backward compatible.
	if ( empty( $shariff3uu['services'] ) ) {
		$shariff3uu['services'] = 'twitter|facebook|googleplus|info';
	}

	// Uses the backend option for every option that is not set in the shorttag.
	$backend_options = $shariff3uu;
	if ( isset( $shariff3uu['vertical'] ) && 1 === $shariff3uu['vertical'] ) {
		$backend_options['orientation'] = 'vertical';
	}
	if ( isset( $shariff3uu['backend'] ) && 1 === $shariff3uu['backend'] ) {
		$backend_options['backend'] = 'on';
	}
	if ( isset( $shariff3uu['buttonsize'] ) && 1 === $shariff3uu['buttonsize'] ) {
		$backend_options['buttonsize'] = 'small';
	}
	if ( empty( $atts ) ) {
		$atts = $backend_options;
	} else {
		$atts = array_merge( $backend_options, $atts );
	}

	// Gets the metabox ignore widget value.
	$shariff_metabox_ignore_widget = get_post_meta( get_the_ID(), 'shariff_metabox_ignore_widget', true );

	// Adds the meta box settings if it is not a widget or if it is a widget and not being set to be ignored.
	if ( ( ! isset( $atts['widget'] ) || ( isset( $atts['widget'] ) && 1 === $atts['widget'] && 1 !== $shariff_metabox_ignore_widget ) ) && 'total' !== $atts['services'] && 'totalnumber' !== $atts['services'] ) {
		// Gets the meta box disable value.
		$shariff3uu_metabox_disable = get_post_meta( get_the_ID(), 'shariff_metabox_disable', true );

		// Stops all further actions if the meta box setting is set to disabled.
		if ( '1' === $shariff3uu_metabox_disable ) {
			return null;
		}

		// Gets the meta box shortcode.
		$shariff3uu_metabox = get_post_meta( get_the_ID(), 'shariff_metabox', true );

		// Replaces shariff with shariffmeta.
		$shariff3uu_metabox = str_replace( '[shariff ', '[shariffmeta ', $shariff3uu_metabox );

		// Gets the meta box attributes.
		if ( '[shariff]' !== $shariff3uu_metabox ) {
			do_shortcode( $shariff3uu_metabox );
		}

		if ( isset( $GLOBALS['shariff3uu']['metabox'] ) && ! empty( $GLOBALS['shariff3uu']['metabox'] ) ) {
			$metabox = $GLOBALS['shariff3uu']['metabox'];
		} else {
			$metabox = array();
		}

		// Gets the meta box media attribute.
		$shariff3uu_metabox_media = get_post_meta( get_the_ID(), 'shariff_metabox_media', true );
		if ( ! empty( $shariff3uu_metabox_media ) ) {
			$metabox['media'] = $shariff3uu_metabox_media;
		}

		// Merges the meta box atts array with the atts array (meta box shortcode overrides all others).
		if ( ! empty( $metabox ) ) {
			$atts = array_merge( $atts, $metabox );
		}

		// Clears the metabox global.
		$GLOBALS['shariff3uu']['metabox'] = '';
	} // End meta box if.

	// Ov3rfly: Makes the attributes configurable from outside, e.g. for language etc.
	$atts = apply_filters( 'shariff3uu_render_atts', $atts );

	// Removes empty elements.
	$atts = array_filter( $atts );

	// Cleans up services (remove leading or trailing |, spaces, etc.).
	$atts['services'] = trim( preg_replace( '/[^A-Za-z|]/', '', $atts['services'] ), '|' );

	// Cleans up the headline in case it was used in a shorttag.
	if ( array_key_exists( 'headline', $atts ) ) {
		$atts['headline'] = wp_kses( $atts['headline'], $GLOBALS['allowed_tags'] );
	}

	// Enqueues the stylesheet (loading it here makes sure that it is only loaded on pages that actually contain shariff buttons).
	// If SCRIPT_DEBUG is set to true, the non minified version will be loaded.
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG === true ) {
		wp_enqueue_style( 'shariffcss', plugins_url( '/css/shariff.min.css', __FILE__ ), '', $shariff3uu['version'] );
	} else {
		wp_enqueue_style( 'shariffcss', plugins_url( '/css/shariff.min.css', __FILE__ ), '', $shariff3uu['version'] );
	}

	// Adds the shariff.css to the header of AMP pages while using the AMP plugin by Automatic.
	add_action( 'amp_post_template_css', 'shariff3uu_amp_css' );

	// Enqueues the share count script (the JS should be loaded at the footer - make sure that wp_footer() is present in your theme!)
	// If SCRIPT_DEBUG is set to true, the non minified version will be loaded.
	if ( array_key_exists( 'backend', $atts ) && 'on' === $atts['backend'] ) {
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG === true ) {
			wp_enqueue_script( 'shariffjs', plugins_url( '/js/shariff.js', __FILE__ ), '', $shariff3uu['version'], true );
		} else {
			wp_enqueue_script( 'shariffjs', plugins_url( '/js/shariff.min.js', __FILE__ ), '', $shariff3uu['version'], true );
		}
	}

	// Enqueues the popup script (the JS should be loaded at the footer - make sure that wp_footer() is present in your theme!).
	// If SCRIPT_DEBUG is set to true, the non minified version will be loaded.
	if ( array_key_exists( 'popup', $atts ) && 1 === $atts['popup'] ) {
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG === true ) {
			wp_enqueue_script( 'shariff_popup', plugins_url( '/js/shariff-popup.js', __FILE__ ), '', $shariff3uu['version'], true );
		} else {
			wp_enqueue_script( 'shariff_popup', plugins_url( '/js/shariff-popup.min.js', __FILE__ ), '', $shariff3uu['version'], true );
		}
	}

	// Sets the share url.
	if ( array_key_exists( 'url', $atts ) ) {
		$share_url = rawurlencode( $atts['url'] );
	} else {
		$share_url = rawurlencode( get_permalink() );
	}

	// Sets the share title.
	if ( array_key_exists( 'title', $atts ) ) {
		$share_title = rawurlencode( $atts['title'] );
	} else {
		$share_title = rawurlencode( html_entity_decode( get_the_title(), ENT_COMPAT, 'UTF-8' ) );
	}

	// Sets the transient name.
	$post_hash = 'shariff' . hash( 'md5', $share_url );

	// Prevents php notices.
	$share_counts = array();
	$output       = '';

	// Gets the cached share counts.
	if ( array_key_exists( 'backend', $atts ) && 'on' === $atts['backend'] && get_transient( $post_hash ) !== false ) {
		$share_counts = get_transient( $post_hash );
	}

	// Adds ShariffSC container including custom styles if a custom style attribute or class exists.
	if ( array_key_exists( 'style', $atts ) || array_key_exists( 'cssclass', $atts ) ) {
		$output .= '<div class="ShariffSC';
		if ( array_key_exists( 'cssclass', $atts ) ) {
			$output .= ' ' . esc_html( $atts['cssclass'] ) . '"';
		} else {
			$output .= '"';
		}
		if ( array_key_exists( 'style', $atts ) ) {
			$output .= ' style="' . esc_html( $atts['style'] ) . '"';
		}
		$output .= '>';
	}

	// Tries http_negotiate_language if no language is set.
	if ( ! array_key_exists( 'lang', $atts ) && function_exists( 'http_negotiate_language' ) ) {
		$available_lang = array( 'en', 'de', 'fr', 'es', 'zh', 'hr', 'da', 'nl', 'fi', 'it', 'ja', 'ko', 'no', 'pl', 'pt', 'ro', 'ru', 'sk', 'sl', 'sr', 'sv', 'tr', 'zh' );
		$lang           = http_negotiate_language( $available_lang );
		$atts['lang']   = substr( $lang, 0, 2 );
	}

	// Sets the default button share text.
	$default_button_text_array = array(
		'bg' => 'cподеляне',
		'cs' => 'sdílet',
		'da' => 'del',
		'de' => 'teilen',
		'en' => 'share',
		'es' => 'compartir',
		'fi' => 'Jaa',
		'fr' => 'partager',
		'hr' => 'podijelite',
		'hu' => 'megosztás',
		'it' => 'condividi',
		'ja' => '共有',
		'ko' => '공유하기',
		'nl' => 'delen',
		'no' => 'del',
		'pl' => 'udostępnij',
		'pt' => 'compartilhar',
		'ro' => 'partajează',
		'ru' => 'поделиться',
		'sk' => 'zdieľať',
		'sl' => 'deli',
		'sr' => 'podeli',
		'sv' => 'dela',
		'tr' => 'paylaş',
		'zh' => '分享',
	);

	// Adds the timestamp for the cache.
	if ( array_key_exists( 'timestamp', $atts ) ) {
		$post_timestamp = $atts['timestamp'];
	} else {
		$post_timestamp = absint( get_the_modified_date( 'U' ) );
	}

	// Starts the output of the actual Shariff buttons.
	$output .= '<div class="shariff';
	// Alignment.
	if ( array_key_exists( 'align', $atts ) && 'none' !== $atts['align'] ) {
		$output .= ' shariff-align-' . $atts['align'];
	}
	// Alignment widget.
	if ( array_key_exists( 'align_widget', $atts ) && 'none' !== $atts['align_widget'] ) {
		$output .= ' shariff-widget-align-' . $atts['align_widget'];
	}
	// Button Stretch.
	if ( array_key_exists( 'buttonstretch', $atts ) && 1 === $atts['buttonstretch'] ) {
		$output .= ' shariff-buttonstretch';
	}
	$output .= '"';
	// Hides buttons until css is loaded.
	if ( array_key_exists( 'hideuntilcss', $atts ) && 1 === $atts['hideuntilcss'] ) {
		$output .= ' style="display:none"';
	}
	// Adds information for share count request.
	if ( array_key_exists( 'backend', $atts ) && 'on' === $atts['backend'] ) {
		// Share url.
		$output .= ' data-url="' . esc_html( $share_url ) . '"';
		// Timestamp for cache.
		$output .= ' data-timestamp="' . $post_timestamp . '"';
		// Hides share counts when they are zero.
		if ( isset( $atts['hidezero'] ) && 1 === $atts['hidezero'] ) {
			$output .= ' data-hidezero="1"';
		}
		// Adds external api if entered.
		if ( isset( $shariff3uu['external_host'] ) && ! empty( $shariff3uu['external_host'] ) && isset( $shariff3uu['external_direct'] ) ) {
			$output .= ' data-backendurl="' . $shariff3uu['external_host'] . '"';
		} // Elseif test the subapi setting.
		elseif ( isset( $shariff3uu['subapi'] ) && 1 === $shariff3uu['subapi'] ) {
			$output .= ' data-backendurl="' . get_bloginfo( 'wpurl' ) . '/wp-json/shariff/v1/share_counts?"';
		} // Elseif pretty permalinks are not activated fall back to manual rest route.
		elseif ( ! get_option( 'permalink_structure' ) ) {
			$output .= ' data-backendurl="?rest_route=/shariff/v1/share_counts&"';
		} // Else use the home url.
		else {
			$output .= ' data-backendurl="' . rtrim( home_url(), '/' ) . '/wp-json/shariff/v1/share_counts?"';
		}
	}
	$output .= '>';

	// Adds the headline.
	if ( array_key_exists( 'headline', $atts ) ) {
		if ( ! array_key_exists( 'total', $share_counts ) ) {
			$share_counts['total'] = '0';
		}
		$atts['headline'] = str_replace( '%total', '<span class="shariff-total">' . absint( $share_counts['total'] ) . '</span>', $atts['headline'] );
		$output          .= '<div class="ShariffHeadline">' . $atts['headline'] . '</div>';
	}

	// Start the ul list with design classes.
	$output .= '<ul class="shariff-buttons ';
	// Theme.
	if ( array_key_exists( 'theme', $atts ) ) {
		$output .= 'theme-' . esc_html( $atts['theme'] ) . ' ';
	} else {
		$output .= 'theme-default ';
	}
	// Orientation.
	if ( array_key_exists( 'orientation', $atts ) ) {
		$output .= 'orientation-' . esc_html( $atts['orientation'] ) . ' ';
	} else {
		$output .= 'orientation-horizontal ';
	}
	// Size.
	if ( array_key_exists( 'buttonsize', $atts ) ) {
		$output .= 'buttonsize-' . esc_html( $atts['buttonsize'] );
	} else {
		$output .= 'buttonsize-medium';
	}
	$output .= '">';

	// Prevents warnings while debug mode is on.
	$flattr_error      = '';
	$paypal_error      = '';
	$paypalme_error    = '';
	$bitcoin_error     = '';
	$patreon_error     = '';
	$button_text_array = '';
	$backend_available = '';
	$mobile_only       = '';
	$button_url        = '';
	$no_custom_color   = '';

	// Explodes services.
	$service_array = explode( '|', $atts['services'] );

	// Migrates mail to mailto.
	$service_array = preg_replace( '/\bmail\b/', 'mailto', $service_array );

	// Migrates mailform to mailto.
	$service_array = preg_replace( '/\bmailform\b/', 'mailto', $service_array );

	// Remove duplicated services.
	$service_array = array_unique( $service_array );

	// Loops through all desired services.
	foreach ( $service_array as $service ) {
		// Check if necessary usernames are set and display warning to admins, if needed.
		if ( 'flattr' === $service && ! array_key_exists( 'flattruser', $atts ) ) {
			$flattr_error = 1;
		} elseif ( 'paypal' === $service && ! array_key_exists( 'paypalbuttonid', $atts ) ) {
			$paypal_error = 1;
		} elseif ( 'paypalme' === $service && ! array_key_exists( 'paypalmeid', $atts ) ) {
			$paypalme_error = 1;
		} elseif ( 'bitcoin' === $service && ! array_key_exists( 'bitcoinaddress', $atts ) ) {
			$bitcoin_error = 1;
		} elseif ( 'patreon' === $service && ! array_key_exists( 'patreonid', $atts ) ) {
			$patreon_error = 1;
		} elseif ( 'total' !== $service && 'totalnumber' !== $service ) {

			// Only the frontend part is needed.
			$frontend = 1;

			// Determines the path to the service phps.
			$path_service_file = dirname( __FILE__ ) . '/services/shariff-' . $service . '.php';

			// Checks if service file exists.
			if ( file_exists( $path_service_file ) ) {

				// Includes service file.
				include $path_service_file;

				// Overwrites service specific colors, if custom colors are set.
				if ( array_key_exists( 'maincolor', $atts ) ) {
					$main_color = $atts['maincolor'];
				} else {
					$no_custom_color = 'shariff-nocustomcolor ';
				}
				if ( array_key_exists( 'secondarycolor', $atts ) ) {
					$secondary_color = $atts['secondarycolor'];
				}

				// Sets the border radius for the round theme.
				if ( array_key_exists( 'borderradius', $atts ) && array_key_exists( 'theme', $atts ) && 'round' === $atts['theme'] ) {
					$border_radius = '; border-radius:' . $atts['borderradius'] . '%';
				} else {
					$border_radius = '';
				}

				// Info button for default theme.
				if ( ! array_key_exists( 'maincolor', $atts ) && 'info' === $service && ( ( array_key_exists( 'theme', $atts ) && 'default' === $atts['theme'] || ( array_key_exists( 'theme', $atts ) && 'round' === $atts['theme'] ) ) || ! array_key_exists( 'theme', $atts ) ) ) {
					$main_color      = '#fff';
					$secondary_color = '#eee';
				}

				// Start li.
				$output .= '<li class="shariff-button ' . $no_custom_color . $service;
				// Mobile only.
				if ( 1 === $mobile_only ) {
					$output .= ' shariff-mobile';
				}
				$output .= '" style="background-color:' . $secondary_color . $border_radius . '">';

				// Uses default button share text, if $button_text_array is empty.
				if ( empty( $button_text_array ) ) {
					$button_text_array = $default_button_text_array;
				}

				// Sets button text in desired language; fallback is English.
				if ( array_key_exists( 'lang', $atts ) && array_key_exists( $atts['lang'], $button_text_array ) ) {
					$button_text = $button_text_array[ $atts['lang'] ];
				} else {
					$button_text = $button_text_array['en'];
				}

				// Sets the button title / label in desired language; fallback is English.
				if ( array_key_exists( 'lang', $atts ) && array_key_exists( $atts['lang'], $button_title_array ) ) {
					$button_title = $button_title_array[ $atts['lang'] ];
				} else {
					$button_title = $button_title_array['en'];
				}

				// Resets $button_text_array.
				$button_text_array = '';

				// Build the actual button.
				$output .= '<a href="' . $button_url . '" title="' . $button_title . '" aria-label="' . $button_title . '" role="button" rel="';
				if ( 'facebook' !== $service ) {
					$output .= 'noopener ';
				}
				$output .= 'nofollow" class="shariff-link" ';
				// Same window?
				if ( ! isset( $same_window ) || isset( $same_window ) && 1 !== $same_window ) {
					$output .= 'target="_blank" ';
				}
				$output .= 'style="background-color:' . $main_color . $border_radius;
				// Theme white?
				if ( isset( $atts['theme'] ) && 'white' === $atts['theme'] ) {
					$output .= '; color:' . $main_color;
				} else {
					$output .= '; color:#fff';
				}
				$output .= '">';
				$output .= '<span class="shariff-icon"';
				// Theme white?
				if ( isset( $atts['theme'] ) && 'white' === $atts['theme'] ) {
					$output .= ' style="fill:' . $main_color . '"';
				}
				$output .= '>' . $svg_icon . '</span>';
				$output .= '<span class="shariff-text"';
				if ( isset( $atts['theme'] ) && 'white' === $atts['theme'] ) {
					$output .= ' style="color:' . $main_color;
				}
				$output .= '">' . $button_text . '</span>&nbsp;';
				// Share counts?
				if ( array_key_exists( 'sharecounts', $atts ) && 1 === $atts['sharecounts'] && 1 === $backend_available && ! isset( $shariff3uu['disable'][ $service ] ) ) {
					$output .= '<span class="shariff-count" data-service="' . $service . '" style="color:' . $main_color;
					if ( true === array_key_exists( $service, $share_counts ) && null !== $share_counts[ $service ] && '-1' !== $share_counts[ $service ] && ( ! isset( $atts['hidezero'] ) || ( isset( $atts['hidezero'] ) && '-1' !== $atts['hidezero'] ) || ( isset( $atts['hidezero'] ) && 1 === $atts['hidezero'] && $share_counts[ $service ] > 0 ) ) ) {
						$output .= '"> ' . $share_counts[ $service ];
					} else {
						$output .= ';opacity:0">';
					}
					$output .= '</span>&nbsp;';
				}
				$output .= '</a>';
				$output .= '</li>';
				// Adds service to backend service, if available.
				if ( 1 === $backend_available && ! isset( $shariff3uu['disable'][ $service ] ) ) {
					$backend_service_array[] = $service;
				}
				// Resets the $backend, $mobile_only and $same_window variables.
				$backend_available = '';
				$mobile_only       = '';
				$same_window       = '';
			} // End if service file exists.
		} // End if is real and fully setup service.
	} // End foreach() loop.

	// Adds the list of backend services.
	if ( ! empty( $backend_service_array ) ) {
		$backend_services = implode( '|', $backend_service_array );
		$output           = str_replace( 'data-url=', 'data-services="' . esc_html( rawurlencode( $backend_services ) ) . '" data-url=', $output );
	}

	// Closes ul and the main shariff div.
	$output .= '</ul></div>';

	// Closes the style attribute, if there was one.
	if ( array_key_exists( 'style', $atts ) || array_key_exists( 'cssclass', $atts ) ) {
		$output .= '</div>';
	}

	// Displays a warning to admins if flattr is set, but no flattr username was provided.
	if ( 1 === $flattr_error && current_user_can( 'manage_options' ) ) {
		$output .= '<div class="shariff-warning">' . __( 'Username for Flattr is missing!', 'shariff' ) . '</div>';
	}
	// Displays a warning to admins if patreon is set, but no patreon username was provided.
	if ( 1 === $patreon_error && current_user_can( 'manage_options' ) ) {
		$output .= '<div class="shariff-warning">' . __( 'Username for patreon is missing!', 'shariff' ) . '</div>';
	}
	// Displays a warning to admins if paypal is set, but no paypal button id was provided.
	if ( 1 === $paypal_error && current_user_can( 'manage_options' ) ) {
		$output .= '<div class="shariff-warning">' . __( 'Button ID for PayPal is missing!', 'shariff' ) . '</div>';
	}
	// Displays a warning to admins if paypalme is set, but no paypalme id was provided.
	if ( 1 === $paypalme_error && current_user_can( 'manage_options' ) ) {
		$output .= '<div class="shariff-warning">' . __( 'PayPal.Me ID is missing!', 'shariff' ) . '</div>';
	}
	// Displays a warning to admins if bitcoin is set, but no bitcoin address was provided.
	if ( 1 === $bitcoin_error && current_user_can( 'manage_options' ) ) {
		$output .= '<div class="shariff-warning">' . __( 'Address for Bitcoin is missing!', 'shariff' ) . '</div>';
	}

	// If the service totalnumber is set, just output the total share count.
	if ( array_key_exists( '0', $service_array ) && 'totalnumber' === $service_array['0'] ) {
		$output = '<span class="shariff" data-services="totalnumber" data-url="' . $share_url . '"';
		// Adds the external api.
		if ( isset( $shariff3uu['external_host'] ) && ! empty( $shariff3uu['external_host'] ) && isset( $shariff3uu['external_direct'] ) ) {
			$output .= ' data-backendurl="' . $shariff3uu['external_host'] . '"';
		}
		$output .= '><span class="shariff-totalnumber">' . absint( $share_counts['total'] ) . '</span></span>';
	}

	return $output;
}

// Registers the helper shortcode.
add_shortcode( 'shariffmeta', 'shariff3uu_meta' );

/**
 * Meta box helper function. Creates a global variable with the current meta box attributes.
 *
 * @param array $atts List of shariff shortcode attributes.
 */
function shariff3uu_meta( $atts ) {
	if ( ! empty( $atts ) ) {
		$GLOBALS['shariff3uu']['metabox'] = $atts;
	} else {
		$GLOBALS['shariff3uu']['metabox'] = array();
	}
}

/**
 * Clears transients and removes cron job upon deactivation.
 */
function shariff3uu_deactivate() {
	global $wpdb;
	// Checks for multisite.
	if ( is_multisite() && function_exists( 'get_sites' ) && class_exists( 'WP_Site_Query' ) ) {
		$sites = get_sites();
		foreach ( $sites as $site ) {
			switch_to_blog( $site->blog_id );
			// Purges transients.
			shariff3uu_purge_transients_deactivation();
			// Removes the cron job.
			wp_clear_scheduled_hook( 'shariff3uu_fill_cache' );
			// Switches back to main blog.
			restore_current_blog();
		}
	} else {
		// Purges transients.
		shariff3uu_purge_transients_deactivation();
		// Removes cron job.
		wp_clear_scheduled_hook( 'shariff3uu_fill_cache' );
	}
}
register_deactivation_hook( __FILE__, 'shariff3uu_deactivate' );

/**
 * Purges all the transients associated with our plugin.
 */
function shariff3uu_purge_transients_deactivation() {
	// Makes sure the $wpdb class is ready.
	if ( ! isset( $wpdb ) ) {
		global $wpdb;
	}
	// Deletes transients.
	// @codingStandardsIgnoreStart
	$sql = 'DELETE FROM ' . $wpdb->options . ' WHERE option_name LIKE "_transient_timeout_shariff%"';
	$wpdb->query( $sql );
	$sql = 'DELETE FROM ' . $wpdb->options . ' WHERE option_name LIKE "_transient_shariff%"';
	$wpdb->query( $sql );
	// @codingStandardsIgnoreEnd
	// Clears object cache.
	wp_cache_flush();
}
