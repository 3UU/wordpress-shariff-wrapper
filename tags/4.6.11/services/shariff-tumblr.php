<?php
/**
 * Will be included in the shariff.php only, when Tumblr is requested as a service.
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
	$service_url = esc_url( 'https://www.tumblr.com/widgets/share/tool' );

	// Domain.
	$wpurl      = get_bloginfo( 'wpurl' );
	$domainname = trim( $wpurl, '/' );
	if ( ! preg_match( '#^http(s)?://#', $domainname ) ) {
		$domainname = 'http://' . $domainname;
	}
	$url_parts  = wp_parse_url( $domainname );
	$domainname = preg_replace( '/^www\./', '', $url_parts['host'] );

	// Build button URL.
	$button_url = $service_url . '?posttype=link&canonicalUrl=' . $share_url . '&tags=' . rawurlencode( $domainname );

	// Colors.
	$main_color      = '#36465d';
	$secondary_color = '#529ecc';
	$wcag_color      = '#36465d';

	// SVG icon.
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path fill="' . $main_color . '" d="M18 14l0 7.3c0 1.9 0 2.9 0.2 3.5 0.2 0.5 0.7 1.1 1.2 1.4 0.7 0.4 1.5 0.6 2.4 0.6 1.6 0 2.6-0.2 4.2-1.3v4.8c-1.4 0.6-2.6 1-3.7 1.3-1.1 0.3-2.3 0.4-3.6 0.4-1.5 0-2.3-0.2-3.4-0.6-1.1-0.4-2.1-0.9-2.9-1.6-0.8-0.7-1.3-1.4-1.7-2.2s-0.5-1.9-0.5-3.4v-11.2h-4.3v-4.5c1.3-0.4 2.7-1 3.6-1.8 0.9-0.8 1.6-1.7 2.2-2.7 0.5-1.1 0.9-2.4 1.1-4.1h5.2l0 8h8v6h-8z"/></svg>';

	// Backend available?
	$backend_available = 1;

	// Button alt label.
	$button_title_array = array(
		'bg' => 'Сподели в tumblr',
		'cs' => 'Sdílet na tumblru',
		'da' => 'Del på tumblr',
		'de' => 'Bei tumblr teilen',
		'en' => 'Share on tumblr',
		'es' => 'Compartir en tumblr',
		'fi' => 'Jaa tumblrissä',
		'fr' => 'Partager sur tumblr',
		'hr' => 'Podijelite na tumblr',
		'hu' => 'Megosztás tumblren',
		'it' => 'Condividi su tumblr',
		'ja' => 'tumblr上で共有',
		'ko' => 'tumblr에서 공유하기',
		'nl' => 'Delen op tumblr',
		'no' => 'Del på tumblr',
		'pl' => 'Udostępnij przez tumblr',
		'pt' => 'Compartilhar no tumblr',
		'ro' => 'Partajează pe tumblr',
		'ru' => 'Поделиться на tumblr',
		'sk' => 'Zdieľať na tumblr',
		'sl' => 'Deli na tumblr',
		'sr' => 'Podeli na tumblr-u',
		'sv' => 'Dela på tumblr',
		'tr' => 'tumblr\'ta paylaş',
		'zh' => '在tumblr上分享',
	);
} elseif ( isset( $backend ) && 1 === $backend ) {
	// Fetch counts.
	$tumblr      = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://api.tumblr.com/v2/share/stats?url=' . $post_url ) ) );
	$tumblr_json = json_decode( $tumblr, true );

	// Store results, if we have some and record errors, if enabled (e.g. request from the status tab).
	if ( isset( $tumblr_json['response']['note_count'] ) ) {
		$share_counts['tumblr'] = intval( $tumblr_json['response']['note_count'] );
	} elseif ( isset( $record_errors ) && 1 === $record_errors ) {
		$service_errors['tumblr'] = $tumblr;
	}
}
