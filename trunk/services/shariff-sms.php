<?php
/**
 * Will be included in the shariff.php only, when SMS is requested as a service.
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
	$service_url = 'sms:';

	// Build button URL.
	$button_url = $service_url . '?&body=' . $share_url;

	// Colors.
	$main_color      = '#a1e877';
	$secondary_color = '#d7d9d8';
	$wcag_color      = '#31640C';

	// SVG icon.
	$svg_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="510" height="510" viewBox="0 0 510 510"><path fill="' . $main_color . '" d="M459 0H51C23 0 0 23 0 51v459l102-102h357c28.1 0 51-22.9 51-51V51C510 23 487.1 0 459 0zM178.5 229.5h-51v-51h51V229.5zM280.5 229.5h-51v-51h51V229.5zM382.5 229.5h-51v-51h51V229.5z"/></svg>';

	// Mobile only?
	$mobile_only = 1;

	// Button alt label.
	$button_title_array = array(
		'bg' => 'Сподели в SMS',
		'cs' => 'Sdílet na SMS',
		'da' => 'Del på SMS',
		'de' => 'Per SMS teilen',
		'en' => 'Share on SMS',
		'es' => 'Compartir en SMS',
		'fi' => 'Jaa SMS',
		'fr' => 'Partager sur SMS',
		'hr' => 'Podijelite na SMS',
		'hu' => 'Megosztás SMS',
		'it' => 'Condividi su SMS',
		'ja' => 'SMS上で共有',
		'ko' => 'SMS에서 공유하기',
		'nl' => 'Delen op SMS',
		'no' => 'Del på SMS',
		'pl' => 'Udostępnij przez SMS',
		'pt' => 'Compartilhar no SMS',
		'ro' => 'Partajează pe SMS',
		'ru' => 'Поделиться на SMS',
		'sk' => 'Zdieľať na SMS',
		'sl' => 'Deli na SMS',
		'sr' => 'Podeli na SMS-u',
		'sv' => 'Dela på SMS',
		'tr' => 'SMS\'ta paylaş',
		'zh' => '在WSMS上分享',
	);
}
