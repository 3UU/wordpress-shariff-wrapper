<?php
/**
 * Will be included in the shariff.php only, when Mastodon is requested as a service.
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
	$service_url = esc_html( 'web+mastodon://share' );

	// Build the button URL.
	$button_url = $service_url . '?text=' . $share_url;

	// Set the Colors (Hexadecimal including the #).
	$main_color      = '#2b90d9';
	$secondary_color = '#9baec8';

	// SVG icon.
	$svg_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="20" viewBox="0 0 200 200"><path fill="' . $main_color . '" d="M155 72q0-15-8-24c-5-6-12-9-20-9-10 0-18 4-23 12l-4 8-5-8c-5-8-13-12-23-12-8 0-15 3-20 9q-8 9-8 24v49h19V73c0-10 5-15 13-15 9 0 14 6 14 18v26h19V76c0-12 5-18 14-18 8 0 13 6 13 15v48h19V72l27 47c-2 13-22 28-46 30l-36 3a216 216 0 0 1-38-5 42 42 0 0 0 1 5c2 21 20 22 37 23a107 107 0 0 0 32-4v15s-11 6-32 7a137 137 0 0 1-43-4c-37-10-43-49-44-88V69c0-40 26-52 26-52 14-6 37-9 60-9h1c24 0 46 3 60 9 0 0 26 12 26 52 0 0 0 30-4 50"/></svg>';

	// Backend available?
	$backend_available = 0;

	// Button alt label.
	$button_title_array = array(
		'bg' => 'Сподели във Mastodon',
		'da' => 'Del på Mastodon',
		'de' => 'Bei Mastodon teilen',
		'en' => 'Share on Mastodon',
		'es' => 'Compartir en Mastodon',
		'fi' => 'Jaa Mastodon',
		'fr' => 'Partager sur Mastodon',
		'hr' => 'Podijelite na Mastodon',
		'hu' => 'Megosztás Mastodon',
		'it' => 'Condividi su Mastodon',
		'nl' => 'Delen op Mastodon',
		'no' => 'Del på Mastodon',
		'pl' => 'Udostępnij na Mastodon',
		'pt' => 'Compartilhar no Mastodon',
		'ro' => 'Partajează pe Mastodon',
		'ru' => 'Поделиться на Mastodon',
		'sk' => 'Zdieľať na Mastodon',
		'sl' => 'Deli na Mastodon',
		'sr' => 'Podeli na Mastodon',
		'sv' => 'Dela på Mastodon',
		'tr' => 'Mastodon\'ta paylaş',
		'zh' => '在Mastodon上分享',
	);
}
