<?php
/**
 * Will be included in the shariff.php only, when MeWe is requested as a service.
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
	$service_url = esc_url( 'https://mewe.com/share' );

	// Build the button URL.
	$button_url = $service_url . '?link=' . $share_url;

	// Set the Colors (Hexadecimal including the #).
	$main_color      = '#16387D';
	$secondary_color = '#187ca5';
	$wcag_color      = '#16387D';

	// SVG icon.
	$svg_icon = '<svg width="32px" height="32px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path fill="' . $main_color . '" d="M7.48,9.21a1.46,1.46,0,1,1-2.92,0h0a1.46,1.46,0,1,1,2.92,0Zm7.3,0a1.52,1.52,0,1,1-1.52-1.52A1.52,1.52,0,0,1,14.78,9.21Zm8.52,0a1.52,1.52,0,1,1-1.52-1.52A1.52,1.52,0,0,1,23.3,9.21Zm8.53,0a1.47,1.47,0,0,1-2.93,0h0a1.47,1.47,0,0,1,2.93,0ZM.17,13.35a1.09,1.09,0,0,1,1.1-1.09h.12a1,1,0,0,1,1,.61L6,18.46l3.65-5.59a.8.8,0,0,1,.85-.61h.25a1,1,0,0,1,1,1.09v9.74a1,1,0,0,1-.7,1.19,1.24,1.24,0,0,1-.27,0,1.11,1.11,0,0,1-1.11-1.09s0-.09,0-.13V16.15l-2.8,4.26a1.08,1.08,0,0,1-1,.61c-.37,0-.61-.24-.86-.61l-2.8-4.26v7.06a1,1,0,0,1-1.09,1,1,1,0,0,1-1-1Zm13.15.37a1.35,1.35,0,0,1,0-.49,1,1,0,0,1,1-1,1.05,1.05,0,0,1,1,.73l2.8,8.15L20.75,13a.92.92,0,0,1,1-.73h.12c.61,0,1,.24,1.1.73l2.68,8.15L28.3,13a1,1,0,0,1,1-.73,1.06,1.06,0,0,1,1.09,1,1,1,0,0,1,0,.49l-3.65,9.74a1,1,0,0,1-1,.85H25.5a1.31,1.31,0,0,1-1.1-.85l-2.56-7.67-2.67,7.67a1.32,1.32,0,0,1-1.1.85h-.24a1,1,0,0,1-1-.85Z"/></svg>';

	// Backend available?
	$backend_available = 0;

	// Button alt label.
	$button_title_array = array(
		'bg' => 'Сподели във MeWe',
		'cs' => 'Sdílet na MeWeu',
		'da' => 'Del på MeWe',
		'de' => 'Bei MeWe teilen',
		'en' => 'Share on MeWe',
		'es' => 'Compartir en MeWe',
		'fi' => 'Jaa MeWeissa',
		'fr' => 'Partager sur MeWe',
		'hr' => 'Podijelite na MeWeu',
		'hu' => 'Megosztás MeWeon',
		'it' => 'Condividi su MeWe',
		'ja' => 'フェイスブック上で共有',
		'ko' => '페이스북에서 공유하기',
		'nl' => 'Delen op MeWe',
		'no' => 'Del på MeWe',
		'pl' => 'Udostępnij na MeWeu',
		'pt' => 'Compartilhar no MeWe',
		'ro' => 'Partajează pe MeWe',
		'ru' => 'Поделиться на MeWe',
		'sk' => 'Zdieľať na MeWeu',
		'sl' => 'Deli na MeWeu',
		'sr' => 'Podeli na MeWe-u',
		'sv' => 'Dela på MeWe',
		'tr' => 'MeWe\'ta paylaş',
	);
}
