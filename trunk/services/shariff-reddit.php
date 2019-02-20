<?php
/**
 * Will be included in the shariff.php only, when Reddit is requested as a service.
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
	$service_url = esc_url( 'https://www.reddit.com/submit' );

	// Build button URL.
	$button_url = $service_url . '?url=' . $share_url;

	// Colors.
	$main_color      = '#ff4500';
	$secondary_color = '#ff5700';
	$wcag_color      = '#A32D00';

	// SVG icon.
	$svg_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="' . $main_color . '" d="M440.3 203.5c-15 0-28.2 6.2-37.9 15.9-35.7-24.7-83.8-40.6-137.1-42.3L293 52.3l88.2 19.8c0 21.6 17.6 39.2 39.2 39.2 22 0 39.7-18.1 39.7-39.7s-17.6-39.7-39.7-39.7c-15.4 0-28.7 9.3-35.3 22l-97.4-21.6c-4.9-1.3-9.7 2.2-11 7.1L246.3 177c-52.9 2.2-100.5 18.1-136.3 42.8-9.7-10.1-23.4-16.3-38.4-16.3-55.6 0-73.8 74.6-22.9 100.1-1.8 7.9-2.6 16.3-2.6 24.7 0 83.8 94.4 151.7 210.3 151.7 116.4 0 210.8-67.9 210.8-151.7 0-8.4-.9-17.2-3.1-25.1 49.9-25.6 31.5-99.7-23.8-99.7zM129.4 308.9c0-22 17.6-39.7 39.7-39.7 21.6 0 39.2 17.6 39.2 39.7 0 21.6-17.6 39.2-39.2 39.2-22 .1-39.7-17.6-39.7-39.2zm214.3 93.5c-36.4 36.4-139.1 36.4-175.5 0-4-3.5-4-9.7 0-13.7 3.5-3.5 9.7-3.5 13.2 0 27.8 28.5 120 29 149 0 3.5-3.5 9.7-3.5 13.2 0 4.1 4 4.1 10.2.1 13.7zm-.8-54.2c-21.6 0-39.2-17.6-39.2-39.2 0-22 17.6-39.7 39.2-39.7 22 0 39.7 17.6 39.7 39.7-.1 21.5-17.7 39.2-39.7 39.2z"/></svg>';

	// Backend available?
	$backend_available = 1;

	// Button alt label.
	$button_title_array = array(
		'bg' => 'Сподели в Reddit',
		'cs' => 'Sdílet na Redditu',
		'da' => 'Del på Reddit',
		'de' => 'Bei Reddit teilen',
		'en' => 'Share on Reddit',
		'es' => 'Compartir en Reddit',
		'fi' => 'Jaa Redditissä',
		'fr' => 'Partager sur Reddit',
		'hr' => 'Podijelite na Reddit',
		'hu' => 'Megosztás Redditen',
		'it' => 'Condividi su Reddit',
		'ja' => 'Reddit上で共有',
		'ko' => 'Reddit에서 공유하기',
		'nl' => 'Delen op Reddit',
		'no' => 'Del på Reddit',
		'pl' => 'Udostępnij przez Reddit',
		'pt' => 'Compartilhar no Reddit',
		'ro' => 'Partajează pe Reddit',
		'ru' => 'Поделиться на Reddit',
		'sk' => 'Zdieľať na Reddit',
		'sl' => 'Deli na Reddit',
		'sr' => 'Podeli na Reddit-u',
		'sv' => 'Dela på Reddit',
		'tr' => 'Reddit\'ta paylaş',
		'zh' => '分享至Reddit',
	);
} elseif ( isset( $backend ) && 1 === $backend ) {
	// Fetch counts.
	$reddit      = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://www.reddit.com/api/info.json?url=' . $post_url ) ) );
	$reddit_json = json_decode( $reddit, true );

	// Store results, if we have some and record errors, if enabled (e.g. request from the status tab).
	if ( isset( $reddit_json['data']['children'] ) ) {
		$count = 0;
		foreach ( $reddit_json['data']['children'] as $child ) {
			$count += intval( $child['data']['score'] );
		}
		$share_counts['reddit'] = $count;
	} elseif ( isset( $record_errors ) && 1 === $record_errors ) {
		$service_errors['reddit'] = $reddit;
	}
}
