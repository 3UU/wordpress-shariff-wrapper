<?php
/**
 * Will be included in the shariff.php only, when Twitter is requested as a service.
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
	$service_url = esc_url( 'https://twitter.com/share' );

	// Via tag.
	if ( array_key_exists( 'twitter_via', $atts ) ) {
		$twitter_via = '&via=' . esc_html( $atts['twitter_via'] );
	} else {
		$twitter_via = '';
	}

	// Build button URL.
	$button_url = $service_url . '?url=' . $share_url . '&text=' . $share_title . $twitter_via;

	// Colors.
	$main_color      = '#000';
	$secondary_color = '#595959';
	$wcag_color      = '#595959';

	// SVG icon.
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="' . $main_color . '" d="M14.258 10.152L23.176 0h-2.113l-7.747 8.813L7.133 0H0l9.352 13.328L0 23.973h2.113l8.176-9.309 6.531 9.309h7.133zm-2.895 3.293l-.949-1.328L2.875 1.56h3.246l6.086 8.523.945 1.328 7.91 11.078h-3.246zm0 0"/></svg>';

	// Backend available?
	$backend_available = 0;

	// Button alt label.
	$button_title_array = array(
		'bg' => 'Сподели във X',
		'cs' => 'Sdílet na X',
		'da' => 'Del på X',
		'de' => 'Bei X teilen',
		'en' => 'Share on X',
		'es' => 'Compartir en X',
		'fi' => 'Jaa X',
		'fr' => 'Envoyer par X',
		'hr' => 'Podijelite na X',
		'hu' => 'Megosztás X',
		'it' => 'Condividi su X',
		'nl' => 'Delen op X',
		'no' => 'Del på X',
		'pl' => 'Udostępnij na X',
		'pt' => 'Compartilhar no X',
		'ro' => 'Partajează pe X',
		'ru' => 'Поделиться на X',
		'sk' => 'Zdieľať na X',
		'sl' => 'Deli na X',
		'sr' => 'Podeli na X',
		'sv' => 'Dela på X',
		'tr' => 'X\'ta paylaş',
	);
}
