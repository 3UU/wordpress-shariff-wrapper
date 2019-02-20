<?php
/**
 * Will be included in the shariff.php only, when Info is requested as a service.
 *
 * @package Shariff Wrapper
 */

// Prevent direct calls.
if ( ! class_exists( 'WP' ) ) {
	die();
}

// Check if we need the frontend or the backend part.
if ( isset( $frontend ) && 1 === $frontend ) {
	// Set custom info url or default one.
	if ( array_key_exists( 'info_url', $atts ) ) {
		$service_url = esc_url( $atts['info_url'] );
	} else {
		$service_url = esc_url( 'http://ct.de/-2467514' );
	}

	// Build button URL.
	$button_url = $service_url;

	// Colors.
	$main_color      = '#999';
	$secondary_color = '#a8a8a8';
	$wcag_color      = '#595959';

	// Replace $main_color with $wcag_color and $secondary_color with #000, if $wacg_theme is selected.
	if ( isset( $atts['theme'] ) && 'wcag' === $atts['theme'] && isset( $wcag_color ) && '' !== $wcag_color ) {
		$main_color      = $wcag_color;
		$secondary_color = '#000';
	}

	// SVG icon.
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11 32"><path fill="' . $main_color . '" d="M11.4 24v2.3q0 0.5-0.3 0.8t-0.8 0.4h-9.1q-0.5 0-0.8-0.4t-0.4-0.8v-2.3q0-0.5 0.4-0.8t0.8-0.4h1.1v-6.8h-1.1q-0.5 0-0.8-0.4t-0.4-0.8v-2.3q0-0.5 0.4-0.8t0.8-0.4h6.8q0.5 0 0.8 0.4t0.4 0.8v10.3h1.1q0.5 0 0.8 0.4t0.3 0.8zM9.2 3.4v3.4q0 0.5-0.4 0.8t-0.8 0.4h-4.6q-0.4 0-0.8-0.4t-0.4-0.8v-3.4q0-0.4 0.4-0.8t0.8-0.4h4.6q0.5 0 0.8 0.4t0.4 0.8z"/></svg>';

	// Button text label.
	$button_text_array = array(
		'de' => 'info',
		'en' => 'info',
	);

	// Button alt label.
	if ( array_key_exists( 'info_text', $atts ) ) {
		$button_title_array = array( 'en' => $atts['info_text'] );
	} else {
		$button_title_array = array(
			'bg' => 'Повече информация',
			'cs' => 'Více informací',
			'da' => 'Flere oplysninger',
			'de' => 'Weitere Informationen',
			'en' => 'More information',
			'es' => 'Más informaciones',
			'fi' => 'Lisätietoja',
			'fr' => 'Plus d\'informations',
			'hr' => 'Više informacija',
			'hu' => 'Több információ',
			'it' => 'Maggiori informazioni',
			'ja' => '詳しい情報',
			'ko' => '추가 정보',
			'nl' => 'Verdere informatie',
			'no' => 'Mer informasjon',
			'pl' => 'Więcej informacji',
			'pt' => 'Mais informações',
			'ro' => 'Mai multe informatii',
			'ru' => 'Больше информации',
			'sk' => 'Viac informácií',
			'sl' => 'Več informacij',
			'sr' => 'Više informacija',
			'sv' => 'Mer information',
			'tr' => 'Daha fazla bilgi',
			'zh' => '更多信息',
		);
	}
}
