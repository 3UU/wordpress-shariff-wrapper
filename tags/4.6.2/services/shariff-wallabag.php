<?php
/**
 * Will be included in the shariff.php only, when Wallabag is requested as a service.
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
	$service_url = esc_url( 'https://app.wallabag.it/bookmarklet' );

	// Build button URL.
	$button_url = $service_url . '?url=' . $share_url;

	// Colors.
	$main_color      = '#26a69a';
	$secondary_color = '#2bbbad';
	$wcag_color      = '#156058';

	// SVG icon.
	$svg_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 500"><path fill="' . $main_color . '" d="M381 474l-5 7c-15 19-29 20-45 2-14-15-30-28-47-38a75 75 0 0 1-15-12c-8-8-10-19-8-30 2-8-2-12-8-16l-3 12c-8 33-31 51-64 57-36 6-73 7-109 5l-57-4c-9-1-9-1-7-9h46c28 0 55-1 82-9 45-14 67-49 62-96l-4-25 43 13q101 24 197-11c6-2 10-2 14 5l-49 53-15 15c-8 8-8 15 1 23l24 18 50 34c-8 20-26 26-43 14l-43-34c-14-10-23-25-31-39-3-4-6-6-10-5h-36c-5-1-8 1-9 6zM409 4l-6 49c-2 19-9 35-28 45l40 50c-13 9-26 11-40 12-19 0-36-6-54-11-16-4-34-9-51-11-10-2-20 0-31 1l30-38c-16-9-25-23-28-41l-1-15-3-41c21 5 38 16 51 33l11 16 22 29c10-9 19-18 24-29 12-26 32-42 60-48l4-1zm-19 187c12 9 17 20 16 35l-2 47c-3 31-26 48-57 40a114 114 0 0 1-11-3 44 44 0 0 0-28-1 110 110 0 0 1-28 5c-16 0-28-8-33-24-6-16-6-33-6-50v-14c-2-15 2-28 17-35 10 6 19 13 17 27v32c-1 14 5 25 16 33 15-12 16-28 15-45v-30c0-11 4-15 12-16 8-2 11 0 15 10 6 13 10 27 8 41-2 17 4 30 16 40 11-10 16-23 14-38v-16c0-21 1-24 19-38z"/></svg>';

	// Button text label.
	$button_text_array = array(
		'de' => 'wallabag it',
		'en' => 'wallabag it',
	);

	// Button alt label.
	$button_title_array = array(
		'de' => 'Bei wallabag speichern',
		'en' => 'Save to wallabag',
	);
}
