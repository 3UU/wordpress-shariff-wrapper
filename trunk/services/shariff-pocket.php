<?php
/**
 * Will be included in the shariff.php only, when Pocket is requested as a service.
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
	$service_url = esc_url( 'https://getpocket.com/save' );

	// Build button URL.
	$button_url = $service_url . '?url=' . $share_url . '&title=' . $share_title;

	// Colors.
	$main_color      = '#ff0000';
	$secondary_color = '#444';
	$wcag_color      = '#B30000';

	// SVG icon.
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 27 28"><path fill="' . $main_color . '" d="M24.5 2q1 0 1.7 0.7t0.7 1.7v8.1q0 2.8-1.1 5.3t-2.9 4.3-4.3 2.9-5.2 1.1q-2.7 0-5.2-1.1t-4.3-2.9-2.9-4.3-1.1-5.2v-8.1q0-1 0.7-1.7t1.7-0.7h22zM13.5 18.6q0.7 0 1.3-0.5l6.3-6.1q0.6-0.5 0.6-1.3 0-0.8-0.5-1.3t-1.3-0.5q-0.7 0-1.3 0.5l-5 4.8-5-4.8q-0.5-0.5-1.3-0.5-0.8 0-1.3 0.5t-0.5 1.3q0 0.8 0.6 1.3l6.3 6.1q0.5 0.5 1.3 0.5z"/></svg>';

	// Backend available?
	$backend_available = 0;

	// Button text label.
	$button_text_array = array(
		'de' => 'Pocket',
		'en' => 'pocket',
	);

	// Button alt label.
	$button_title_array = array(
		'bg' => 'Запазване в Pocket',
		'cs' => 'Uložit do Pocket',
		'da' => 'Gem i Pocket',
		'de' => 'Bei Pocket speichern',
		'en' => 'Save to Pocket',
		'es' => 'Guardar en Pocket',
		'fi' => 'Tallenna kohtaan Pocket',
		'fr' => 'Enregistrer dans Pocket',
		'hr' => 'Spremi u Pocket',
		'hu' => 'Mentés "Pocket"-be',
		'it' => 'Salva in Pocket',
		'ja' => '「ポケット」に保存',
		'ko' => 'Pocket에 저장',
		'nl' => 'Opslaan in Pocket',
		'no' => 'Lagre i Pocket',
		'pl' => 'Zapisz w Pocket',
		'pt' => 'Salvar em Pocket',
		'ro' => 'Salvați în Pocket',
		'ru' => 'Сохранить в Pocket',
		'sk' => 'Uložiť do priečinka Pocket',
		'sl' => 'Shrani v Pocket',
		'sr' => 'Sačuvaj u Pocket',
		'sv' => 'Spara till Pocket',
		'tr' => 'Pocket e kaydet',
		'zh' => '保存到Pocket',
	);
}
