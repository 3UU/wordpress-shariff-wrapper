<?php
/**
 * Will be included in the shariff.php only, when Stumbleupon is requested as a service.
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
	$service_url = esc_url( 'https://www.stumbleupon.com/submit' );

	// Build button URL.
	$button_url = $service_url . '?url=' . $share_url;

	// Colors.
	$main_color      = '#eb4b24';
	$secondary_color = '#e1370e';

	// SVG icon.
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 37 32"><path fill="' . $main_color . '" d="M19 12.7v-2.1q0-0.7-0.5-1.3t-1.3-0.5-1.3 0.5-0.5 1.3v10.9q0 3.1-2.2 5.3t-5.4 2.2q-3.2 0-5.4-2.2t-2.2-5.4v-4.8h5.9v4.7q0 0.8 0.5 1.3t1.3 0.5 1.3-0.5 0.5-1.3v-11.1q0-3 2.2-5.2t5.4-2.1q3.1 0 5.4 2.2t2.3 5.2v2.4l-3.5 1zM28.4 16.7h5.9v4.8q0 3.2-2.2 5.4t-5.4 2.2-5.4-2.2-2.2-5.4v-4.8l2.3 1.1 3.5-1v4.8q0 0.7 0.5 1.3t1.3 0.5 1.3-0.5 0.5-1.3v-4.9z"/></svg>';

	// Backend available?
	$backend_available = 1;

	// Button alt label.
	$button_title_array = array(
		'bg' => 'Сподели в Stumbleupon',
		'cs' => 'Sdílet na Stumbleuponu',
		'da' => 'Del på Stumbleupon',
		'de' => 'Bei Stumbleupon teilen',
		'en' => 'Share on Stumbleupon',
		'es' => 'Compartir en Stumbleupon',
		'fi' => 'Jaa Stumbleuponissä',
		'fr' => 'Partager sur Stumbleupon',
		'hr' => 'Podijelite na Stumbleupon',
		'hu' => 'Megosztás Stumbleupon',
		'it' => 'Condividi su Stumbleupon',
		'ja' => 'Stumbleupon上で共有',
		'ko' => 'Stumbleupon에서 공유하기',
		'nl' => 'Delen op Stumbleupon',
		'no' => 'Del på Stumbleupon',
		'pl' => 'Udostępnij przez Stumbleupon',
		'pt' => 'Compartilhar no Stumbleupon',
		'ro' => 'Partajează pe Stumbleupon',
		'ru' => 'Поделиться на Stumbleupon',
		'sk' => 'Zdieľať na Stumbleupon',
		'sl' => 'Deli na Stumbleupon',
		'sr' => 'Podeli na Stumbleupon-u',
		'sv' => 'Dela på Stumbleupon',
		'tr' => 'Stumbleupon\'ta paylaş',
		'zh' => '分享至Stumbleupon',
	);
} elseif ( isset( $backend ) && 1 === $backend ) {
	// Fetch counts.
	$stumbleupon      = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://www.stumbleupon.com/services/1.01/badge.getinfo?url=' . $post_url ) ) );
	$stumbleupon_json = json_decode( $stumbleupon, true );

	// Store results, if we have some and record errors, if enabled (e.g. request from the status tab).
	if ( isset( $stumbleupon_json['success'] ) && true === $stumbleupon_json['success'] ) {
		if ( isset( $stumbleupon_json['result']['views'] ) ) {
			$share_counts['stumbleupon'] = intval( $stumbleupon_json['result']['views'] );
		} else {
			$share_counts['stumbleupon'] = 0;
		}
	} elseif ( isset( $record_errors ) && 1 === $record_errors ) {
		$service_errors['stumbleupon'] = $stumbleupon;
	}
}
