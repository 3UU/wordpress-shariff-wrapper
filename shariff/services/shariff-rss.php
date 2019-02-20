<?php
/**
 * Will be included in the shariff.php only, when RSS is requested as a service.
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
	if ( array_key_exists( 'rssfeed', $atts ) ) {
		$service_url = esc_url( $atts['rssfeed'] );
	} else {
		$service_url = esc_url( get_bloginfo( 'rss_url' ) );
	}

	// Build button URL.
	$button_url = $service_url;

	// Colors.
	$main_color      = '#fe9312';
	$secondary_color = '#ff8c00';
	$wcag_color      = '#aa2e00';

	// SVG icon.
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path fill="' . $main_color . '" d="M4.3 23.5c-2.3 0-4.3 1.9-4.3 4.3 0 2.3 1.9 4.2 4.3 4.2 2.4 0 4.3-1.9 4.3-4.2 0-2.3-1.9-4.3-4.3-4.3zM0 10.9v6.1c4 0 7.7 1.6 10.6 4.4 2.8 2.8 4.4 6.6 4.4 10.6h6.2c0-11.7-9.5-21.1-21.1-21.1zM0 0v6.1c14.2 0 25.8 11.6 25.8 25.9h6.2c0-17.6-14.4-32-32-32z"/></svg>';

	// Button text label.
	$button_text_array = array(
		'de' => 'RSS-feed',
		'en' => 'RSS feed',
		'fr' => 'flux RSS',
	);

	// Button alt label.
	$button_title_array = array(
		'de' => 'RSS-feed',
		'en' => 'RSS feed',
		'fr' => "S'abonner au flux RSS",
	);
}
