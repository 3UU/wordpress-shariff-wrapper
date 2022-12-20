<?php
/**
 * Will be included in the shariff.php only, when Telegram is requested as a service.
 * Thanks to Daniel Sturm (@dcsturm).
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
	$service_url = 'https://telegram.me/share/url';

	// Build button URL.
	$button_url = $service_url . '?url=' . $share_url . '&text=' . $share_title;

	// Colors.
	$main_color      = '#0088cc';
	$secondary_color = '#4084A6';
	$wcag_color      = '#005E8F';

	// SVG icon.
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path fill="' . $main_color . '" d="M30.8 6.5l-4.5 21.4c-.3 1.5-1.2 1.9-2.5 1.2L16.9 24l-3.3 3.2c-.4.4-.7.7-1.4.7l.5-7L25.5 9.2c.6-.5-.1-.8-.9-.3l-15.8 10L2 16.7c-1.5-.5-1.5-1.5.3-2.2L28.9 4.3c1.3-.5 2.3.3 1.9 2.2z"/></svg>';

	// Mobile only?
	$mobile_only = 0;

	// Button alt label.
	$button_title_array = array(
		'bg' => 'Сподели в Telegram',
		'cs' => 'Sdílet na Telegramu',
		'da' => 'Del på Telegram',
		'de' => 'Bei Telegram teilen',
		'en' => 'Share on Telegram',
		'es' => 'Compartir en Telegram',
		'fi' => 'Jaa Telegramissä',
		'fr' => 'Partager sur Telegram',
		'hr' => 'Podijelite na Telegram',
		'hu' => 'Megosztás Telegramen',
		'it' => 'Condividi su Telegram',
		'ja' => 'Telegram上で共有',
		'ko' => 'Telegram에서 공유하기',
		'nl' => 'Delen op Telegram',
		'no' => 'Del på Telegram',
		'pl' => 'Udostępnij przez Telegram',
		'pt' => 'Compartilhar no Telegram',
		'ro' => 'Partajează pe Telegram',
		'ru' => 'Поделиться на Telegram',
		'sk' => 'Zdieľať na Telegram',
		'sl' => 'Deli na Telegram',
		'sr' => 'Podeli na Telegram-u',
		'sv' => 'Dela på Telegram',
		'tr' => 'Telegram\'ta paylaş',
		'zh' => '在Telegram上分享',
	);
}
