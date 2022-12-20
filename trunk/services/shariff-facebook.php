<?php
/**
 * Will be included in the shariff.php only, when Facebook is requested as a service.
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
	$service_url = esc_url( 'https://www.facebook.com/sharer/sharer.php' );

	// Build the button URL.
	$button_url = $service_url . '?u=' . $share_url;

	// Set the Colors (Hexadecimal including the #).
	$main_color      = '#3b5998';
	$secondary_color = '#4273c8';
	$wcag_color      = '#38548F';

	// SVG icon.
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 32"><path fill="' . $main_color . '" d="M17.1 0.2v4.7h-2.8q-1.5 0-2.1 0.6t-0.5 1.9v3.4h5.2l-0.7 5.3h-4.5v13.6h-5.5v-13.6h-4.5v-5.3h4.5v-3.9q0-3.3 1.9-5.2t5-1.8q2.6 0 4.1 0.2z"/></svg>';

	// Backend available?
	$backend_available = 1;

	// Button alt label.
	$button_title_array = array(
		'bg' => 'Сподели във Facebook',
		'cs' => 'Sdílet na Facebooku',
		'da' => 'Del på Facebook',
		'de' => 'Bei Facebook teilen',
		'en' => 'Share on Facebook',
		'es' => 'Compartir en Facebook',
		'fi' => 'Jaa Facebookissa',
		'fr' => 'Partager sur Facebook',
		'hr' => 'Podijelite na Facebooku',
		'hu' => 'Megosztás Facebookon',
		'it' => 'Condividi su Facebook',
		'ja' => 'フェイスブック上で共有',
		'ko' => '페이스북에서 공유하기',
		'nl' => 'Delen op Facebook',
		'no' => 'Del på Facebook',
		'pl' => 'Udostępnij na Facebooku',
		'pt' => 'Compartilhar no Facebook',
		'ro' => 'Partajează pe Facebook',
		'ru' => 'Поделиться на Facebook',
		'sk' => 'Zdieľať na Facebooku',
		'sl' => 'Deli na Facebooku',
		'sr' => 'Podeli na Facebook-u',
		'sv' => 'Dela på Facebook',
		'tr' => 'Facebook\'ta paylaş',
		'zh' => '在Facebook上分享',
	);
} elseif ( isset( $backend ) && 1 === $backend ) {
	// Use the FB ID and Secret, if available.
	if ( isset( $shariff3uu['fb_id'] ) && isset( $shariff3uu['fb_secret'] ) ) {
		// On 32-bit PHP installations the constant is 4 and we have to disable the check because the id is too long.
		if ( ( defined( PHP_INT_SIZE ) && PHP_INT_SIZE === '4' ) || ! defined( PHP_INT_SIZE ) ) {
			$fb_app_id = $shariff3uu['fb_id'];
		} else {
			$fb_app_id = absint( $shariff3uu['fb_id'] );
		}
		$fb_app_secret = sanitize_text_field( $shariff3uu['fb_secret'] );
		// Create the FB access token.
		$fb_token = $fb_app_id . '|' . $fb_app_secret;
		// Use the token to get the share counts.
		$facebook = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://graph.facebook.com/v15.0/?access_token=' . $fb_token . '&fields=og_object%7Bengagement%7D&id=' . $post_url ) ) );
		// Decode the json response.
		$facebook_json = json_decode( $facebook, true );
		// Set nofbid in case the page has not yet been crawled by Facebook and no ID is provided.
		$nofbid = '0';
	}

	/**
	* Stores results - uses engagement (Graph API > 2.12) if it exists, otherwise uses share_count - ordered based on proximity of occurrence.
	* Records errors, if enabled (e.g. request from the status tab).
	*/
	if ( isset( $facebook_json['og_object'] ) && isset( $facebook_json['og_object']['engagement'] ) && isset( $facebook_json['og_object']['engagement']['count'] ) ) {
		$share_counts['facebook'] = intval( $facebook_json['og_object']['engagement']['count'] );
	} elseif ( isset( $facebook_json['id'] ) && ! isset( $facebook_json['error'] ) && 1 === $nofbid ) {
		$share_counts['facebook'] = '0';
	} elseif ( isset( $record_errors ) && 1 === $record_errors ) {
		$service_errors['facebook'] = $facebook;
	}
}; // End if backend.
