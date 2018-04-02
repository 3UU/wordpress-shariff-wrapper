<?php
/**
 * Will be included in the shariff.php only, when GooglePlus is requested as a service.
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
	$service_url = esc_url( 'https://plus.google.com/share' );

	// Build the button URL.
	$button_url = $service_url . '?url=' . $share_url;

	// Set the Colors (Hexadecimal including the #).
	$main_color      = '#d34836';
	$secondary_color = '#f75b44';

	// SVG icon.
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path fill="' . $main_color . '" d="M31.6 14.7h-3.3v-3.3h-2.6v3.3h-3.3v2.6h3.3v3.3h2.6v-3.3h3.3zM10.8 14v4.1h5.7c-0.4 2.4-2.6 4.2-5.7 4.2-3.4 0-6.2-2.9-6.2-6.3s2.8-6.3 6.2-6.3c1.5 0 2.9 0.5 4 1.6v0l2.9-2.9c-1.8-1.7-4.2-2.7-7-2.7-5.8 0-10.4 4.7-10.4 10.4s4.7 10.4 10.4 10.4c6 0 10-4.2 10-10.2 0-0.8-0.1-1.5-0.2-2.2 0 0-9.8 0-9.8 0z"/></svg>';

	// Backend available?
	$backend_available = 0;

	// Button alt label.
	$button_title_array = array(
		'bg' => 'Сподели в Google+',
		'cs' => 'Sdílet na Google+',
		'da' => 'Del på Google+',
		'de' => 'Bei Google+ teilen',
		'en' => 'Share on Google+',
		'es' => 'Compartir en Google+',
		'fi' => 'Jaa Google+ =>ssa',
		'fr' => 'Partager sur Goolge+',
		'hr' => 'Podijelite na Google+',
		'hu' => 'Megosztás Google+on',
		'it' => 'Condividi su Google+',
		'ja' => 'Google+上で共有',
		'ko' => 'Google+에서 공유하기',
		'nl' => 'Delen op Google+',
		'no' => 'Del på Google+',
		'pl' => 'Udostępnij na Google+',
		'pt' => 'Compartilhar no Google+',
		'ro' => 'Partajează pe Google+',
		'ru' => 'Поделиться на Google+',
		'sk' => 'Zdieľať na Google+',
		'sl' => 'Deli na Google+',
		'sr' => 'Podeli na Google+',
		'sv' => 'Dela på Google+',
		'tr' => 'Google+\'da paylaş',
		'zh' => '在Google+上分享',
	);
};
