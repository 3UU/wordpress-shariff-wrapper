<?php
/**
 * Will be included in the shariff.php only, when AddThis is selected as a service.
 *
 * @package Shariff Wrapper
 */

// Prevents direct calls.
if ( ! class_exists( 'WP' ) ) {
	die();
}

// Check if we need the frontend or the backend part.
if ( isset( $frontend ) && 1 === $frontend ) {
	// Service URL.
	$service_url = esc_url( 'http://api.addthis.com/oexchange/0.8/offer' );

	// Build button URL.
	$button_url = $service_url . '?url=' . $share_url;

	// Colors.
	$main_color      = '#f8694d';
	$secondary_color = '#f75b44';
	$wcag_color      = '#AC2207';

	// SVG icon.
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path fill="' . $main_color . '" d="M28.2 12.8h-8.9v-8.9c0-0.4-0.4-0.8-0.8-0.8h-4.9c-0.4 0-0.8 0.4-0.8 0.8v8.9h-8.9c-0.4 0-0.8 0.4-0.8 0.8v4.9c0 0.4 0.4 0.8 0.8 0.8h8.9v8.9c0 0.4 0.4 0.8 0.8 0.8h4.9c0.4 0 0.8-0.4 0.8-0.8v-8.9h8.9c0.4 0 0.8-0.4 0.8-0.8v-4.9c0-0.4-0.4-0.8-0.8-0.8z"/></svg>';

	// Backend available?
	$backend_available = 1;

	// Button alt label.
	$button_title_array = array(
		'bg' => 'Сподели в AddThis',
		'cs' => 'Sdílet na AddThis',
		'da' => 'Del på AddThis',
		'de' => 'Bei AddThis teilen',
		'en' => 'Share on AddThis',
		'es' => 'Compartir en AddThis',
		'fi' => 'Jaa AddThisissä',
		'fr' => 'Partager sur AddThis',
		'hr' => 'Podijelite na AddThis',
		'hu' => 'Megosztás AddThisen',
		'it' => 'Condividi su AddThis',
		'ja' => 'AddThis上で共有',
		'ko' => 'AddThis에서 공유하기',
		'nl' => 'Delen op AddThis',
		'no' => 'Del på AddThis',
		'pl' => 'Udostępnij przez AddThis',
		'pt' => 'Compartilhar no AddThis',
		'ro' => 'Partajează pe AddThis',
		'ru' => 'Поделиться на AddThis',
		'sk' => 'Zdieľať na AddThis',
		'sl' => 'Deli na AddThis',
		'sr' => 'Podeli na AddThis',
		'sv' => 'Dela på AddThis',
		'tr' => 'AddThis\'ta paylaş',
		'zh' => '在AddThis上分享',
	);
} elseif ( isset( $backend ) && 1 === $backend ) {
	// Fetch share counts.
	$addthis      = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'http://api-public.addthis.com/url/shares.json?url=' . $post_url ) ) );
	$addthis_json = json_decode( $addthis, true );

	// Store results, if we have some, else record errors, if enabled (e.g. request from the status tab).
	if ( isset( $addthis_json['shares'] ) ) {
		$share_counts['addthis'] = intval( $addthis_json['shares'] );
	} elseif ( isset( $record_errors ) && 1 === $record_errors ) {
		$service_errors['addthis'] = $addthis;
	}
} // End if Frontend or Backend.
