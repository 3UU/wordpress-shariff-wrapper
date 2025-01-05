<?php
/**
 * Will be included in the shariff.php only, when Bluesky is requested as a service.
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
	$service_url = esc_url( 'https://bsky.app/intent/compose' );
	// Via tag.
	if ( array_key_exists( 'bluesky_via', $atts ) ) {
		$bluesky_via = ' via @' . esc_html( $atts['bluesky_via'] );
	} else {
		$bluesky_via = '';
	}
	// Build button URL.
	$button_url = $service_url . '?text=' . $share_title . ' ' . $share_url . ' ' . $bluesky_via;
	// Colors.
	$main_color      = '#0085ff';
	$secondary_color = '#84c4ff';
	$wcag_color      = '#84c4ff';
	// SVG icon.
	$svg_icon = '<svg width="20" height="20" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path class="st0" d="M4.89,3.12c2.07,1.55,4.3,4.71,5.11,6.4.82-1.69,3.04-4.84,5.11-6.4,1.49-1.12,3.91-1.99,3.91.77,0,.55-.32,4.63-.5,5.3-.64,2.3-2.99,2.89-5.08,2.54,3.65.62,4.58,2.68,2.57,4.74-3.81,3.91-5.48-.98-5.9-2.23-.08-.23-.11-.34-.12-.25,0-.09-.04.02-.12.25-.43,1.25-2.09,6.14-5.9,2.23-2.01-2.06-1.08-4.12,2.57-4.74-2.09.36-4.44-.23-5.08-2.54-.19-.66-.5-4.74-.5-5.3,0-2.76,2.42-1.89,3.91-.77h0Z"/></svg>';
	// Backend available?
	$backend_available = 0;
	// Button alt label.
	$button_title_array = array(
		'bg' => 'Сподели във Bluesky',
		'cs' => 'Sdílet na Bluesky',
		'da' => 'Del på Bluesky',
		'de' => 'Bei Bluesky teilen',
		'en' => 'Share on Bluesky',
		'es' => 'Compartir en Bluesky',
		'fi' => 'Jaa Bluesky',
		'fr' => 'Envoyer par Bluesky',
		'hr' => 'Podijelite na Bluesky',
		'hu' => 'Megosztás Bluesky',
		'it' => 'Condividi su Bluesky',
		'nl' => 'Delen op Bluesky',
		'no' => 'Del på Bluesky',
		'pl' => 'Udostępnij na Bluesky',
		'pt' => 'Compartilhar no Bluesky',
		'ro' => 'Partajează pe Bluesky',
		'ru' => 'Поделиться на Bluesky',
		'sk' => 'Zdieľať na Bluesky',
		'sl' => 'Deli na Bluesky',
		'sr' => 'Podeli na Bluesky',
		'sv' => 'Dela på Bluesky',
		'tr' => 'X\'ta paylaş',
	);
}
