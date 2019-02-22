<?php
/**
 * Will be included in the shariff.php only, when TencentWeibo is requested as a service.
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
	$service_url = esc_url( 'http://v.t.qq.com/share/share.php' );

	// Build button URL.
	$button_url = $service_url . '?url=' . $share_url . '&title=' . $share_title;

	// Colors.
	$main_color      = '#02a8f3';
	$secondary_color = '#8ac249';
	$wcag_color      = '#025C88';

	// SVG icon.
	$svg_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="94" height="94" viewBox="0 0 94.1 94.1"><path fill="' . $main_color . '" d="M25.9 9.4C11.6 9.4 0 21 0 35.3c0 4.8 1.3 9.4 3.8 13.5l0.9 1.4 4.6-3.4L8.5 45.3c-1.7-3-2.7-6.5-2.7-10 0-11 9-20 20-20 11 0 20 9 20 20 0 11-9 20-20 20 -2 0-4-0.3-5.9-0.9l-1.7 5.6c2.5 0.8 5 1.1 7.6 1.1 14.3 0 25.9-11.6 25.9-25.9C51.8 21 40.2 9.4 25.9 9.4z"/><path d="M22.2 43.4l0.6-0.5c1 0.5 2.2 0.8 3.4 0.8 4.6 0 8.3-3.7 8.3-8.3 0-4.6-3.7-8.3-8.3-8.3s-8.3 3.7-8.3 8.3c0 1.2 0.3 2.3 0.7 3.4 0 0 0.1 0.6 0.8 1.2C4.2 53.1 3.1 69.4 3.1 70.2v18h5.9V70.2C8.9 70.1 9.3 54.1 22.2 43.4z"/><path d="M72.9 31.3c-1.9 0.1-3.8-0.3-5.6-1.2 -5.4-2.8-7.6-9.4-4.9-14.9 2.8-5.4 9.4-7.6 14.9-4.9 5.4 2.8 7.6 9.4 4.9 14.9 -0.5 1-1.1 1.9-1.9 2.7l2.3 2.2c1-1 1.8-2.2 2.5-3.5 3.6-7 0.7-15.7-6.3-19.2 -7-3.6-15.7-0.7-19.2 6.3 -3.6 7-0.7 15.7 6.3 19.2 2.4 1.2 5 1.7 7.6 1.5l0.9-0.1 -0.5-3.1L72.9 31.3z"/><path fill="' . $main_color . '" d="M85.3 37.4c-0.1 0-7.9-4.2-9.9-13.2l-0.1-0.5c0.5-0.4 0.9-0.9 1.2-1.5 1.1-2.3 0.2-5-2-6.2 -2.3-1.1-5-0.2-6.2 2 -1.1 2.3-0.2 5 2 6.2 0.6 0.3 1.2 0.5 1.8 0.5 0 0 0.3 0.1 0.8-0.1 2.7 10.8 10.4 15.4 10.8 15.6l8.8 4.5 1.5-2.9L85.3 37.4z"/></svg>';

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
