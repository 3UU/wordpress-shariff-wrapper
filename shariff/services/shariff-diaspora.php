<?php
/**
 * Will be included in the shariff.php only, when Diaspora is requested as a service.
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
	$service_url = esc_url( 'https://share.diasporafoundation.org/' );

	// Build button URL.
	$button_url = $service_url . '?url=' . $share_url . '&title=' . $share_title;

	// Colors.
	$main_color      = '#999';
	$secondary_color = '#b3b3b3';

	// SVG icon.
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 33 32"><path fill="' . $main_color . '" d="M20.6 28.2c-0.8-1.2-2.1-2.9-2.9-4-0.8-1.1-1.4-1.9-1.4-1.9s-1.2 1.6-2.8 3.8c-1.5 2.1-2.8 3.8-2.8 3.8 0 0-5.5-3.9-5.5-3.9 0 0 1.2-1.8 2.8-4s2.8-4 2.8-4.1c0-0.1-0.5-0.2-4.4-1.5-2.4-0.8-4.4-1.5-4.4-1.5 0 0 0.2-0.8 1-3.2 0.6-1.8 1-3.2 1.1-3.3s2.1 0.6 4.6 1.5c2.5 0.8 4.6 1.5 4.6 1.5s0.1 0 0.1-0.1c0 0 0-2.2 0-4.8s0-4.7 0.1-4.7c0 0 0.7 0 3.3 0 1.8 0 3.3 0 3.4 0 0 0 0.1 1.4 0.2 4.6 0.1 5.2 0.1 5.3 0.2 5.3 0 0 2-0.7 4.5-1.5s4.4-1.5 4.4-1.5c0 0.1 2 6.5 2 6.5 0 0-2 0.7-4.5 1.5-3.4 1.1-4.5 1.5-4.5 1.6 0 0 1.2 1.8 2.6 3.9 1.5 2.1 2.6 3.9 2.6 3.9 0 0-5.4 4-5.5 4 0 0-0.7-0.9-1.5-2.1z"/></svg>';

	// Button alt label.
	$button_title_array = array(
		'bg' => 'Сподели в Diaspora',
		'cs' => 'Sdílet na Diaspora',
		'da' => 'Del på Diaspora',
		'de' => 'Bei Diaspora teilen',
		'en' => 'Share on Diaspora',
		'es' => 'Compartir en Diaspora',
		'fi' => 'Jaa Diasporaissä',
		'fr' => 'Partager sur Diaspora',
		'hr' => 'Podijelite na Diaspora',
		'hu' => 'Megosztás Diaspora',
		'it' => 'Condividi su Diaspora',
		'ja' => 'Diaspora上で共有',
		'ko' => 'Diaspora에서 공유하기',
		'nl' => 'Delen op Diaspora',
		'no' => 'Del på Diaspora',
		'pl' => 'Udostępnij przez Diaspora',
		'pt' => 'Compartilhar no Diaspora',
		'ro' => 'Partajează pe Diaspora',
		'ru' => 'Поделиться на Diaspora',
		'sk' => 'Zdieľať na Diaspora',
		'sl' => 'Deli na Diaspora',
		'sr' => 'Podeli na Diaspora-u',
		'sv' => 'Dela på Diaspora',
		'tr' => 'Diaspora\'ta paylaş',
		'zh' => '分享至Diaspora',
	);
}
