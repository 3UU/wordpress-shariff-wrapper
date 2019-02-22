<?php
/**
 * Will be included in the shariff.php only, when Odnoklassniki is requested as a service.
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
	$service_url = esc_url( 'https://connect.ok.ru/offer' );

	// Build button URL.
	$button_url = $service_url . '?url=' . $share_url . '&title=' . $share_title;

	// Colors.
	$main_color      = '#ee8208';
	$secondary_color = '#f3a752';
	$wcag_color      = '#874508';

	// SVG icon.
	$svg_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="20" viewBox="0 0 14 20"><path fill="' . $main_color . '"  d="M7.1 10.1q-2.1 0-3.6-1.5t-1.5-3.6q0-2.1 1.5-3.6t3.6-1.5 3.6 1.5 1.5 3.6q0 2.1-1.5 3.6t-3.6 1.5zM7.1 2.6q-1 0-1.8 0.7t-0.7 1.8q0 1 0.7 1.8t1.8 0.7 1.8-0.7 0.7-1.8q0-1-0.7-1.8t-1.8-0.7zM13 10.7q0.1 0.3 0.2 0.6t0 0.5-0.3 0.4-0.5 0.4-0.7 0.5q-1.3 0.8-3.5 1l0.8 0.8 3 3q0.3 0.3 0.3 0.8t-0.3 0.8l-0.1 0.1q-0.3 0.3-0.8 0.3t-0.8-0.3q-0.7-0.8-3-3l-3 3q-0.3 0.3-0.8 0.3t-0.8-0.3l-0.1-0.1q-0.3-0.3-0.3-0.8t0.3-0.8l3.8-3.8q-2.3-0.2-3.5-1-0.4-0.3-0.7-0.5t-0.5-0.4-0.3-0.4 0-0.5 0.2-0.6q0.1-0.2 0.3-0.4t0.5-0.2 0.6 0 0.7 0.4q0.1 0 0.2 0.1t0.5 0.3 0.8 0.3 1 0.3 1.3 0.1q1 0 1.9-0.3t1.3-0.6l0.4-0.3q0.4-0.3 0.7-0.4t0.6 0 0.5 0.2 0.3 0.4z"/></svg>';

	// Backend available?
	$backend_available = 1;

	// Button text label.
	$button_title_array = array(
		'bg' => 'Сподели във Odnoklassniki',
		'da' => 'Del på Odnoklassniki',
		'de' => 'Bei Odnoklassniki teilen',
		'en' => 'Share on Odnoklassniki',
		'es' => 'Compartir en Odnoklassniki',
		'fi' => 'Jaa Odnoklassniki',
		'fr' => 'Partager sur Odnoklassniki',
		'hr' => 'Podijelite na Odnoklassniki',
		'hu' => 'Megosztás Odnoklassniki',
		'it' => 'Condividi su Odnoklassniki',
		'ja' => 'Odnoklassnikiでシェア',
		'ko' => '오드 노 클라스 니키 공유',
		'nl' => 'Delen op Odnoklassniki',
		'no' => 'Del på Odnoklassniki',
		'pl' => 'Udostępnij na Odnoklassniki',
		'pt' => 'Compartilhar no Odnoklassniki',
		'ro' => 'Partajează pe Odnoklassniki',
		'ru' => 'Поделиться на Odnoklassniki',
		'sk' => 'Zdieľať na Odnoklassniki',
		'sl' => 'Deli na Odnoklassniki',
		'sr' => 'Podeli na Odnoklassniki',
		'sv' => 'Dela på Odnoklassniki',
		'tr' => 'Odnoklassniki\'ta paylaş',
		'zh' => '在Odnoklassniki上分享',
	);
} elseif ( isset( $backend ) && 1 === $backend ) {
	// Fetch share counts.
	$odnoklassniki = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://connect.ok.ru/dk?st.cmd=extLike&tp=json&ref=' . $post_url ) ) );

	// Decode the json response.
	$odnoklassniki_json = json_decode( $odnoklassniki, true );

	// Store results and record errors, if enabled (e.g. request from the status tab).
	if ( isset( $odnoklassniki_json ) && ! empty( $odnoklassniki_json ) ) {
		$share_counts['odnoklassniki'] = intval( $odnoklassniki_json['count'] );
	} elseif ( isset( $record_errors ) && 1 === $record_errors ) {
		$service_errors['odnoklassniki'] = $odnoklassniki;
	}
}
