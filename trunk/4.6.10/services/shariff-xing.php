<?php
/**
 * Will be included in the shariff.php only, when Xing is requested as a service.
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
	$service_url = esc_url( 'https://www.xing.com/spi/shares/new' );

	// Build button URL.
	$button_url = $service_url . '?url=' . $share_url;

	// Colors.
	$main_color      = '#126567';
	$secondary_color = '#29888a';
	$wcag_color      = '#0F595C';

	// SVG icon.
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 32"><path fill="' . $main_color . '" d="M10.7 11.9q-0.2 0.3-4.6 8.2-0.5 0.8-1.2 0.8h-4.3q-0.4 0-0.5-0.3t0-0.6l4.5-8q0 0 0 0l-2.9-5q-0.2-0.4 0-0.7 0.2-0.3 0.5-0.3h4.3q0.7 0 1.2 0.8zM25.1 0.4q0.2 0.3 0 0.7l-9.4 16.7 6 11q0.2 0.4 0 0.6-0.2 0.3-0.6 0.3h-4.3q-0.7 0-1.2-0.8l-6-11.1q0.3-0.6 9.5-16.8 0.4-0.8 1.2-0.8h4.3q0.4 0 0.5 0.3z"/></svg>';

	// Backend available?
	$backend_available = 0;

	// Button alt label.
	$button_title_array = array(
		'bg' => 'Сподели в XING',
		'cs' => 'Sdílet na XINGu',
		'da' => 'Del på XING',
		'de' => 'Bei XING teilen',
		'en' => 'Share on XING',
		'es' => 'Compartir en XING',
		'fi' => 'Jaa XINGissä',
		'fr' => 'Partager sur XING',
		'hr' => 'Podijelite na XING',
		'hu' => 'Megosztás XINGen',
		'it' => 'Condividi su XING',
		'ja' => 'XING上で共有',
		'ko' => 'XING에서 공유하기',
		'nl' => 'Delen op XING',
		'no' => 'Del på XING',
		'pl' => 'Udostępnij przez XING',
		'pt' => 'Compartilhar no XING',
		'ro' => 'Partajează pe XING',
		'ru' => 'Поделиться на XING',
		'sk' => 'Zdieľať na XING',
		'sl' => 'Deli na XING',
		'sr' => 'Podeli na XING-u',
		'sv' => 'Dela på XING',
		'tr' => 'XING\'ta paylaş',
		'zh' => '分享至XING',
	);
}
