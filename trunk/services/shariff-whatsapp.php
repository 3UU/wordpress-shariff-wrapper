<?php
/**
 * Will be included in the shariff.php only, when WhatsApp is requested as a service.
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
	$service_url = 'https://api.whatsapp.com/send';

	// Build button URL.
	$button_url = $service_url . '?text=' . $share_url . '%20' . $share_title;

	// Colors.
	$main_color      = '#34af23';
	$secondary_color = '#5cbe4a';
	$wcag_color      = '#226411';

	// SVG icon.
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path fill="' . $main_color . '" d="M17.6 17.4q0.2 0 1.7 0.8t1.6 0.9q0 0.1 0 0.3 0 0.6-0.3 1.4-0.3 0.7-1.3 1.2t-1.8 0.5q-1 0-3.4-1.1-1.7-0.8-3-2.1t-2.6-3.3q-1.3-1.9-1.3-3.5v-0.1q0.1-1.6 1.3-2.8 0.4-0.4 0.9-0.4 0.1 0 0.3 0t0.3 0q0.3 0 0.5 0.1t0.3 0.5q0.1 0.4 0.6 1.6t0.4 1.3q0 0.4-0.6 1t-0.6 0.8q0 0.1 0.1 0.3 0.6 1.3 1.8 2.4 1 0.9 2.7 1.8 0.2 0.1 0.4 0.1 0.3 0 1-0.9t0.9-0.9zM14 26.9q2.3 0 4.3-0.9t3.6-2.4 2.4-3.6 0.9-4.3-0.9-4.3-2.4-3.6-3.6-2.4-4.3-0.9-4.3 0.9-3.6 2.4-2.4 3.6-0.9 4.3q0 3.6 2.1 6.6l-1.4 4.2 4.3-1.4q2.8 1.9 6.2 1.9zM14 2.2q2.7 0 5.2 1.1t4.3 2.9 2.9 4.3 1.1 5.2-1.1 5.2-2.9 4.3-4.3 2.9-5.2 1.1q-3.5 0-6.5-1.7l-7.4 2.4 2.4-7.2q-1.9-3.2-1.9-6.9 0-2.7 1.1-5.2t2.9-4.3 4.3-2.9 5.2-1.1z"/></svg>';

	// Mobile only?
	if ( isset( $atts['hide_whatsapp'] ) && 1 === $atts['hide_whatsapp'] ) {
		$mobile_only = 1;
	} else {
		$mobile_only = 0;
	}

	// Button alt label.
	$button_title_array = array(
		'bg' => 'Сподели в Whatsapp',
		'cs' => 'Sdílet na Whatsappu',
		'da' => 'Del på Whatsapp',
		'de' => 'Bei Whatsapp teilen',
		'en' => 'Share on Whatsapp',
		'es' => 'Compartir en Whatsapp',
		'fi' => 'Jaa WhatsAppissä',
		'fr' => 'Partager sur Whatsapp',
		'hr' => 'Podijelite na Whatsapp',
		'hu' => 'Megosztás WhatsAppen',
		'it' => 'Condividi su Whatsapp',
		'ja' => 'Whatsapp上で共有',
		'ko' => 'Whatsapp에서 공유하기',
		'nl' => 'Delen op Whatsapp',
		'no' => 'Del på Whatsapp',
		'pl' => 'Udostępnij przez WhatsApp',
		'pt' => 'Compartilhar no Whatsapp',
		'ro' => 'Partajează pe Whatsapp',
		'ru' => 'Поделиться на Whatsapp',
		'sk' => 'Zdieľať na Whatsapp',
		'sl' => 'Deli na Whatsapp',
		'sr' => 'Podeli na WhatsApp-u',
		'sv' => 'Dela på Whatsapp',
		'tr' => 'Whatsapp\'ta paylaş',
		'zh' => '在Whatsapp上分享',
	);
}
