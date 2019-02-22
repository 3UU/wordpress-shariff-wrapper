<?php
/**
 * Will be included in the shariff.php only, when Flipboard is requested as a service.
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
	$service_url = esc_url( 'https://share.flipboard.com/bookmarklet/popout' );

	// Build the button URL.
	$button_url = $service_url . '?v=2&title=' . $share_title . '&url=' . $share_url;

	// Set the Colors (Hexadecimal including the #).
	$main_color      = '#f52828';
	$secondary_color = '#373737';
	$wcag_color      = '#B30A0A';

	// SVG icon.
	$svg_icon = '<svg width="24px" height="24px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="' . $main_color . '" d="M0 0h8v24H0V0zm9 9h7v7H9V9zm0-9h15v8H9V0z"/></svg>';

	// Backend available?
	$backend_available = 0;

	// Button alt label.
	$button_title_array = array(
		'bg' => 'Сподели в Flipboard',
		'cs' => 'Sdílet na Flipboardu',
		'da' => 'Del på Flipboard',
		'de' => 'Bei Flipboard teilen',
		'en' => 'Share on Flipboard',
		'es' => 'Compartir en Flipboard',
		'fi' => 'Jaa Flipboardissä',
		'fr' => 'Partager sur Flipboard',
		'hr' => 'Podijelite na Flipboardu',
		'hu' => 'Megosztás Flipboardon',
		'it' => 'Condividi su Flipboard',
		'ja' => 'Flipboard上で共有',
		'ko' => 'Flipboard에서 공유하기',
		'nl' => 'Delen op Flipboard',
		'no' => 'Del på Flipboard',
		'pl' => 'Udostępnij na Flipboardu',
		'pt' => 'Compartilhar no Flipboard',
		'ro' => 'Partajează pe Flipboard',
		'ru' => 'Поделиться на Flipboard',
		'sk' => 'Zdieľať na Flipboardu',
		'sl' => 'Deli na Flipboardu',
		'sr' => 'Podeli na Flipboard-u',
		'sv' => 'Dela på Flipboard',
		'tr' => 'Flipboard\'ta paylaş',
		'zh' => '在Flipboard上分享',
	);
}
