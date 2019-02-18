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
	$main_color      = '#55acee';
	$secondary_color = '#32bbf5';
	$wcag_color      = '#115A92';

	// SVG icon.
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 32"><path fill="' . $main_color . '" d="M29.7 6.8q-1.2 1.8-3 3.1 0 0.3 0 0.8 0 2.5-0.7 4.9t-2.2 4.7-3.5 4-4.9 2.8-6.1 1q-5.1 0-9.3-2.7 0.6 0.1 1.5 0.1 4.3 0 7.6-2.6-2-0.1-3.5-1.2t-2.2-3q0.6 0.1 1.1 0.1 0.8 0 1.6-0.2-2.1-0.4-3.5-2.1t-1.4-3.9v-0.1q1.3 0.7 2.8 0.8-1.2-0.8-2-2.2t-0.7-2.9q0-1.7 0.8-3.1 2.3 2.8 5.5 4.5t7 1.9q-0.2-0.7-0.2-1.4 0-2.5 1.8-4.3t4.3-1.8q2.7 0 4.5 1.9 2.1-0.4 3.9-1.5-0.7 2.2-2.7 3.4 1.8-0.2 3.5-0.9z"/></svg>';

	// Backend available?
	$backend_available = 1;

	// Button text label.
	$button_text_array = array(
		'de' => 'twittern',
		'en' => 'tweet',
		'fr' => 'tweeter',
		'it' => 'twitta',
		'ja' => 'のつぶやき',
		'ko' => '짹짹',
		'ru' => 'твит',
		'sr' => 'твеет',
		'zh' => '鸣叫',
	);

	// Button alt label.
	$button_title_array = array(
		'bg' => 'Сподели в Twitter',
		'cs' => 'Sdílet na Twiiteru',
		'da' => 'Del på Twitter',
		'de' => 'Bei Twitter teilen',
		'en' => 'Share on Twitter',
		'es' => 'Compartir en Twitter',
		'fi' => 'Jaa Twitterissä',
		'fr' => 'Partager sur Twitter',
		'hr' => 'Podijelite na Twitteru',
		'hu' => 'Megosztás Twitteren',
		'it' => 'Condividi su Twitter',
		'ja' => 'ツイッター上で共有',
		'ko' => '트위터에서 공유하기',
		'nl' => 'Delen op Twitter',
		'no' => 'Del på Twitter',
		'pl' => 'Udostępnij na Twitterze',
		'pt' => 'Compartilhar no Twitter',
		'ro' => 'Partajează pe Twitter',
		'ru' => 'Поделиться на Twitter',
		'sk' => 'Zdieľať na Twitteri',
		'sl' => 'Deli na Twitterju',
		'sr' => 'Podeli na Twitter-u',
		'sv' => 'Dela på Twitter',
		'tr' => 'Twitter\'da paylaş',
		'zh' => '在Twitter上分享',
	);
} elseif ( isset( $backend ) && 1 === $backend ) {
	// Fetch counts.
	$twitter      = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://counts.twitcount.com/counts.php?url=' . $post_url ) ) );
	$twitter_json = json_decode( $twitter, true );

	// Store results, if we have some and record errors, if enabled (e.g. request from the status tab).
	if ( isset( $twitter_json['count'] ) && ! isset( $twitter_json['error'] ) ) {
		$share_counts['twitter'] = intval( $twitter_json['count'] );
	} elseif ( isset( $record_errors ) && 1 === $record_errors ) {
		$service_errors['twitter'] = $twitter;
	}
}
