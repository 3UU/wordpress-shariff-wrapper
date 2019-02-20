<?php
/**
 * Will be included in the shariff.php only, when Weibo is requested as a service.
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
	$service_url = esc_url( 'http://service.weibo.com/share/share.php' );

	// Build button URL.
	$button_url = $service_url . '?url=' . $share_url . '&title=' . $share_title;

	// Colors.
	$main_color      = '#e6162d';
	$secondary_color = '#ff9933';
	$wcag_color      = '#AB1221';

	// SVG icon.
	$svg_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 96.7 96.7" width="97" height="97"><path fill="' . $main_color . '" d="M72.6 46.9c-1.4-0.4-2.3-0.7-1.6-2.5 1.5-3.9 1.7-7.2 0-9.6 -3.1-4.5-11.7-4.2-21.6-0.1 0 0-3.1 1.4-2.3-1.1 1.5-4.9 1.3-8.9-1.1-11.3 -5.3-5.3-19.5 0.2-31.7 12.4C5.3 43.8 0 53.4 0 61.8c0 16 20.5 25.7 40.6 25.7 26.3 0 43.8-15.3 43.8-27.4C84.4 52.8 78.2 48.6 72.6 46.9ZM40.6 81.8c-16 1.6-29.8-5.7-30.9-16.2 -1-10.5 11.1-20.3 27.1-21.9 16-1.6 29.8 5.7 30.9 16.2C68.8 70.4 56.6 80.2 40.6 81.8Z"/><path d="M90.1 17.6L90.1 17.6c-6.3-7-15.7-9.7-24.4-7.9h0c-2 0.4-3.3 2.4-2.8 4.4 0.4 2 2.4 3.3 4.4 2.8 6.2-1.3 12.8 0.6 17.3 5.6 4.5 5 5.7 11.8 3.8 17.8l0 0c-0.6 1.9 0.4 4 2.4 4.7 1.9 0.6 4-0.4 4.7-2.4 0 0 0 0 0 0C98.2 34.3 96.5 24.7 90.1 17.6Z"/><path d="M68.5 22.6c-1.7 0.4-2.8 2.1-2.4 3.8 0.4 1.7 2.1 2.8 3.8 2.4v0c2.1-0.4 4.3 0.2 5.8 1.9 1.5 1.7 1.9 4 1.3 6h0c-0.5 1.7 0.4 3.5 2.1 4 1.7 0.5 3.5-0.4 4-2.1 1.3-4.1 0.5-8.8-2.6-12.2C77.3 23 72.7 21.7 68.5 22.6Z"/><polygon points="80.4 26.4 80.4 26.4 80.4 26.4"/><path fill="' . $main_color . '" d="M42.2 51.8c-7.6-2-16.2 1.8-19.5 8.5 -3.4 6.8-0.1 14.4 7.6 16.9 8 2.6 17.4-1.4 20.6-8.8C54.1 61.3 50.1 53.9 42.2 51.8ZM36.4 69.3c-1.5 2.5-4.9 3.6-7.4 2.4 -2.5-1.1-3.2-4-1.6-6.4 1.5-2.4 4.7-3.5 7.2-2.4C37.1 64 37.9 66.8 36.4 69.3ZM41.5 62.8c-0.6 1-1.8 1.4-2.8 1 -1-0.4-1.3-1.5-0.7-2.4 0.6-0.9 1.7-1.4 2.7-1C41.7 60.7 42 61.8 41.5 62.8Z"/></svg>';

	// Button alt label.
	$button_title_array = array(
		'bg' => 'Сподели в tencent weibo',
		'cs' => 'Sdílet na tencent weibo',
		'da' => 'Del på tencent weibo',
		'de' => 'Bei tencent weibo teilen',
		'en' => 'Share on tencent weibo',
		'es' => 'Compartir en tencent weibo',
		'fi' => 'Jaa tencent weiboissä',
		'fr' => 'Partager sur tencent weibo',
		'hr' => 'Podijelite na tencent weibo',
		'hu' => 'Megosztás tencent weiboen',
		'it' => 'Condividi su tencent weibo',
		'ja' => 'Tencent weibo上で共有',
		'ko' => 'Tencent weibo에서 공유하기',
		'nl' => 'Delen op tencent weibo',
		'no' => 'Del på tencent weibo',
		'pl' => 'Udostępnij przez tencent weibo',
		'pt' => 'Compartilhar no tencent weibo',
		'ro' => 'Partajează pe tencent weibo',
		'ru' => 'Поделиться на tencent weibo',
		'sk' => 'Zdieľať na tencent weibo',
		'sl' => 'Deli na tencent weibo',
		'sr' => 'Podeli na tencent weibo-u',
		'sv' => 'Dela på tencent weibo',
		'tr' => 'Tencent weibo\'ta paylaş',
		'zh' => '分享至腾讯微博',
	);
}
