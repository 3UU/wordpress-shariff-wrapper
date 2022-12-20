<?php
/**
 * Will be included in the shariff.php only, when LinedIn is requested as a service.
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
	$service_url = esc_url( 'https://www.linkedin.com/sharing/share-offsite/' );

	// Build button URL.
	$button_url = $service_url . '?url=' . $share_url;

	// Colors.
	$main_color      = '#0077b5';
	$secondary_color = '#1488bf';
	$wcag_color      = '#005B8A';

	// SVG icon.
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 27 32"><path fill="' . $main_color . '" d="M6.2 11.2v17.7h-5.9v-17.7h5.9zM6.6 5.7q0 1.3-0.9 2.2t-2.4 0.9h0q-1.5 0-2.4-0.9t-0.9-2.2 0.9-2.2 2.4-0.9 2.4 0.9 0.9 2.2zM27.4 18.7v10.1h-5.9v-9.5q0-1.9-0.7-2.9t-2.3-1.1q-1.1 0-1.9 0.6t-1.2 1.5q-0.2 0.5-0.2 1.4v9.9h-5.9q0-7.1 0-11.6t0-5.3l0-0.9h5.9v2.6h0q0.4-0.6 0.7-1t1-0.9 1.6-0.8 2-0.3q3 0 4.9 2t1.9 6z"/></svg>';

	// Backend available?
	$backend_available = 0;

	// Button text label.
	$button_text_array = array(
		'bg' => 'cподеляне',
		'cs' => 'sdílet',
		'da' => 'del',
		'de' => 'mitteilen',
		'en' => 'share',
		'es' => 'compartir',
		'fi' => 'Jaa',
		'fr' => 'partager',
		'hr' => 'podijelite',
		'hu' => 'megosztás',
		'it' => 'condividi',
		'ja' => 'シェア',
		'ko' => '공유하기',
		'nl' => 'delen',
		'no' => 'del',
		'pl' => 'udostępnij',
		'pt' => 'compartilhar',
		'ro' => 'distribuiți',
		'ru' => 'поделиться',
		'sk' => 'zdieľať',
		'sl' => 'deli',
		'sr' => 'podeli',
		'sv' => 'dela',
		'tr' => 'paylaş',
		'zh' => '分享',
	);
	// Button alt label.
	$button_title_array = array(
		'bg' => 'Сподели в LinkedIn',
		'cs' => 'Sdílet na LinkedIn',
		'da' => 'Del på LinkedIn',
		'de' => 'Bei LinkedIn teilen',
		'en' => 'Share on LinkedIn',
		'es' => 'Compartir en LinkedIn',
		'fi' => 'Jaa LinkedInissä',
		'fr' => 'Partager sur LinkedIn',
		'hr' => 'Podijelite na LinkedIn',
		'hu' => 'Megosztás LinkedInen',
		'it' => 'Condividi su LinkedIn',
		'ja' => 'LinkedIn上で共有',
		'ko' => 'LinkedIn에서 공유하기',
		'nl' => 'Delen op LinkedIn',
		'no' => 'Del på LinkedIn',
		'pl' => 'Udostępnij przez LinkedIn',
		'pt' => 'Compartilhar no LinkedIn',
		'ro' => 'Partajează pe LinkedIn',
		'ru' => 'Поделиться на LinkedIn',
		'sk' => 'Zdieľať na LinkedIn',
		'sl' => 'Deli na LinkedIn',
		'sr' => 'Podeli na LinkedIn-u',
		'sv' => 'Dela på LinkedIn',
		'tr' => 'LinkedIn\'ta paylaş',
		'zh' => '在LinkedIn上分享',
	);
}
