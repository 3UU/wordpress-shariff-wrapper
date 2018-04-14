<?php
/**
 * Will be included in the shariff.php only, when Flattr is requested as a service.
 *
 * @package Shariff Wrapper
 */

// Prevent direct calls.
if ( ! class_exists( 'WP' ) ) {
	die();
}

// Check if we need the frontend or the backend part.
if ( isset( $frontend ) && 1 === $frontend ) {
	// Service URL.
	$service_url = esc_url( 'https://flattr.com/submit/auto' );

	// Lang tag.
	if ( isset( $atts['lang'] ) ) {
		$lang = $atts['lang'] . '_' . strtoupper( $atts['lang'] );
	} else {
		$lang = 'en_US';
	}

	// Flattr user.
	if ( array_key_exists( 'flattruser', $atts ) ) {
		$flattruser = esc_html( $atts['flattruser'] );
	} else {
		$flattruser = '';
	}

	// Build button URL.
	$button_url = $service_url . '?url=' . $share_url . '&title=' . $share_title . '&language=' . $lang . '&fid=' . $flattruser;

	// Colors.
	$main_color      = '#7ea352';
	$secondary_color = '#F67C1A';

	// SVG icon.
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 31 32"><path fill="' . $main_color . '" d="M0 28.4v-16.4q0-5.7 2.7-8.9t8.3-3.2h17.5q-0.2 0.2-1.7 1.7t-3.2 3.2-3.5 3.5-3 3-1.3 1.2q-0.5 0-0.5-0.5v-5h-1.5q-1.9 0-3 0.2t-2 0.8-1.2 1.8-0.4 3.1v8.4zM2.1 32.1q0.2-0.2 1.7-1.7t3.2-3.2 3.5-3.5 3-3 1.3-1.2q0.5 0 0.5 0.5v5h1.5q3.7 0 5.2-1.2t1.4-4.8v-8.4l7.2-7.1v16.4q0 5.7-2.7 8.9t-8.3 3.2h-17.5z"/></svg>';

	// Backend available?
	$backend_available = 1;

	// Button text label.
	$button_text_array = array(
		'de' => 'flattr',
		'en' => 'flattr',
	);

	// Button alt label.
	$button_title_array = array(
		'de' => 'Beitrag flattrn!',
		'en' => 'Flattr this!',
		'fr' => 'Flattré!',
		'es' => 'Flattr!',
	);
} elseif ( isset( $backend ) && 1 === $backend ) {
	// Fetch counts.
	$flattr      = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://api.flattr.com/rest/v2/things/lookup/?url=' . $post_url ) ) );
	$flattr_json = json_decode( $flattr, true );

	// Store results, if we have some. If no thing was found, set it to 0. Record errors, if enabled (e.g. request from the status tab).
	if ( isset( $flattr_json['flattrs'] ) ) {
		$share_counts['flattr'] = intval( $flattr_json['flattrs'] );
	} elseif ( isset( $flattr_json['description'] ) && 'No thing was found' === $flattr_json['description'] ) {
		$share_counts['flattr'] = 0;
	} elseif ( isset( $record_errors ) && 1 === $record_errors ) {
		$service_errors['flattr'] = $flattr;
	}
}
