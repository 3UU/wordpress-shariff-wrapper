<?php
/**
 * Will be included in the shariff.php only, when Printer is requested as a service.
 *
 * @package Shariff Wrapper
 */

// Prevent direct calls.
if ( ! class_exists( 'WP' ) ) {
	die();
}

// Check if we need the frontend or the backend part.
if ( isset( $frontend ) && 1 === $frontend ) {

	// Build button URL.
	$button_url = 'javascript:window.print()';

	// Colors.
	$main_color      = '#999';
	$secondary_color = '#a8a8a8';
	$wcag_color      = '#595959';

	// SVG icon.
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 32"><path fill="' . $main_color . '" d="M6.8 27.4h16v-4.6h-16v4.6zM6.8 16h16v-6.8h-2.8q-0.7 0-1.2-0.5t-0.5-1.2v-2.8h-11.4v11.4zM27.4 17.2q0-0.5-0.3-0.8t-0.8-0.4-0.8 0.4-0.3 0.8 0.3 0.8 0.8 0.3 0.8-0.3 0.3-0.8zM29.7 17.2v7.4q0 0.2-0.2 0.4t-0.4 0.2h-4v2.8q0 0.7-0.5 1.2t-1.2 0.5h-17.2q-0.7 0-1.2-0.5t-0.5-1.2v-2.8h-4q-0.2 0-0.4-0.2t-0.2-0.4v-7.4q0-1.4 1-2.4t2.4-1h1.2v-9.7q0-0.7 0.5-1.2t1.2-0.5h12q0.7 0 1.6 0.4t1.3 0.8l2.7 2.7q0.5 0.5 0.9 1.4t0.4 1.6v4.6h1.1q1.4 0 2.4 1t1 2.4z"/></svg>';

	// Same window?
	$same_window = 1;

	// Button text label.
	$button_text_array = array(
		'cs' => 'tlačit',
		'de' => 'drucken',
		'en' => 'print',
		'fr' => 'imprimer',
		'es' => 'imprimir',
		'it' => 'imprimere',
		'da' => 'dat trykke',
		'nl' => 'drukken',
	);

	// Button alt label.
	$button_title_array = array(
		'de' => 'drucken',
		'cs' => 'tlačit',
		'en' => 'print',
		'fr' => 'imprimer',
		'es' => 'imprimir',
		'it' => 'imprimere',
		'da' => 'dat trykke',
		'nl' => 'drukken',
	);
}
