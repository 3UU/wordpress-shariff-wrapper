<?php
/**
 * Will be included in the shariff.php only, when Qzone is requested as a service.
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
	$service_url = esc_url( 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey' );

	// Build button URL.
	$button_url = $service_url . '?url=' . $share_url . '&title=' . $share_title;

	// Colors.
	$main_color      = '#ffce00';
	$secondary_color = '#71C5F2';
	$wcag_color      = '#6B5700';

	// SVG icon.
	$svg_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="98" height="98" viewBox="0 0 97.7 97.7"><path fill="' . $main_color . '" d="M97.6 37.8c-0.2-0.8-0.9-1.3-1.7-1.4l-31.4-2.9L50.8 3.5c-0.3-0.7-1.1-1.2-1.9-1.2 -0.8 0-1.6 0.5-1.9 1.2L33 32.8 1.8 36.4c-0.8 0.1-1.5 0.6-1.7 1.4 -0.2 0.8 0 1.6 0.6 2.1l23 21.1L18.1 93c-0.2 0.8 0.1 1.6 0.8 2.1 0.4 0.3 0.8 0.4 1.2 0.4 0.4 0 0.7-0.1 1-0.3l28.1-16.1 27.6 16.3c0.7 0.2 1.6 0.2 2.2-0.3 0.7-0.5 1-1.2 0.8-2l-4.2-23.6c1.1-0.5 4.3-1.9 6.1-3.9 -2.8 1.1-3.8 1.4-6.6 1.9v0c-18.6 3.6-47 0.5-48 0.5L58.3 45c-10.6-1.9-35.1-2.7-36.6-2.8 -0.2 0-0.2 0-0.1 0 0 0 0 0 0.1 0 1.3-0.2 33.3-5.5 51.9-0.2L42.1 64.1c0 0 24.3 2.4 32.8 1.4l-0.6-4.4 22.8-21.2C97.6 39.3 97.8 38.5 97.6 37.8z"/></svg>';

	// Button alt label.
	$button_title_array = array(
		'bg' => 'Сподели в Qzone',
		'cs' => 'Sdílet na Qzone',
		'da' => 'Del på Qzone',
		'de' => 'Bei Qzone teilen',
		'en' => 'Share on Qzone',
		'es' => 'Compartir en Qzone',
		'fi' => 'Jaa Qzoneissä',
		'fr' => 'Partager sur Qzone',
		'hr' => 'Podijelite na Qzone',
		'hu' => 'Megosztás Qzone',
		'it' => 'Condividi su Qzone',
		'ja' => 'Qzone上で共有',
		'ko' => 'Qzone에서 공유하기',
		'nl' => 'Delen op Qzone',
		'no' => 'Del på Qzone',
		'pl' => 'Udostępnij przez Qzone',
		'pt' => 'Compartilhar no Qzone',
		'ro' => 'Partajează pe Qzone',
		'ru' => 'Поделиться на Qzone',
		'sk' => 'Zdieľať na Qzone',
		'sl' => 'Deli na Qzone',
		'sr' => 'Podeli na Qzone-u',
		'sv' => 'Dela på Qzone',
		'tr' => 'Qzone\'ta paylaş',
		'zh' => '分享至QQ空间',
	);
}
