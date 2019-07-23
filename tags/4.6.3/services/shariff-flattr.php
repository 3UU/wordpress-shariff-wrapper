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
	$service_url = esc_url( 'https://flattr.com/domain/' );

	// Get WP URL.
	$wp_url = wp_parse_url( get_bloginfo( 'url' ) );

	// Build button URL.
	$button_url = $service_url . preg_replace('#^www\.(.+\.)#i', '$1', $wp_url['host'] );

	// Colors.
	$main_color      = '#7ea352';
	$secondary_color = '#F67C1A';
	$wcag_color      = '#415728';

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
}
