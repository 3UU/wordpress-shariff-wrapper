<?php
/**
 * Will be included in the shariff.php only, when Mix is requested as a service.
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
	$service_url = esc_url( 'https://mix.com/add' );

	// Build the button URL.
	$button_url = $service_url . '?url=' . $share_url;

	// Set the Colors (Hexadecimal including the #).
	$main_color      = '#ff8226';
	$secondary_color = '#FFB27A';
	$wcag_color      = '#8F3C00';

	// SVG icon.
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="' . $main_color . '" d="M0 64v348.9c0 56.2 88 58.1 88 0V174.3c7.9-52.9 88-50.4 88 6.5v175.3c0 57.9 96 58 96 0V240c5.3-54.7 88-52.5 88 4.3v23.8c0 59.9 88 56.6 88 0V64H0z"/></svg>';

	// Backend available?
	$backend_available = 0;

	// Button alt label.
	$button_title_array = array(
		'bg' => 'Сподели във Mix',
		'cs' => 'Sdílet na Mix',
		'da' => 'Del på Mix',
		'de' => 'Bei Mix teilen',
		'en' => 'Share on Mix',
		'es' => 'Compartir en Mix',
		'fi' => 'Jaa Mixissa',
		'fr' => 'Partager sur Mix',
		'hr' => 'Podijelite na Mix',
		'hu' => 'Megosztás Mix',
		'it' => 'Condividi su Mix',
		'nl' => 'Delen op Mix',
		'no' => 'Del på Mix',
		'pl' => 'Udostępnij na Mix',
		'pt' => 'Compartilhar no Mix',
		'ro' => 'Partajează pe Mix',
		'ru' => 'Поделиться на Mix',
		'sk' => 'Zdieľať na Mix',
		'sl' => 'Deli na Mix',
		'sr' => 'Podeli na Mix',
		'sv' => 'Dela på Mix',
	);
}
