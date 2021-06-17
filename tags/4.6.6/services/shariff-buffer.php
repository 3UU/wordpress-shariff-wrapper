<?php
/**
 * Will be included in the shariff.php only, when Buffer is requested as a service.
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
	$service_url = esc_url( 'https://buffer.com/add' );

	// Build the button URL.
	$button_url = $service_url . '?url=' . $share_url . '&text=' . $share_title;

	// Set the Colors (Hexadecimal including the #).
	$main_color      = '#168eea';
	$secondary_color = '#329ced';
	$wcag_color      = '#0C5B97';

	// SVG icon.
	$svg_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><path fill="' . $main_color . '" d="M91-37.4V-52l1.8 1 24 11.2c1.7.8 3.3.7 4.9 0l24-11.2 1-.4c.9-.6 1-1.3 0-1.8l-3.5-1.7a6.2 6.2 0 0 0-6.2 0l-15.1 7.1c-1.7.8-3.4.9-5.1 0-4.7-2.3-9.5-4.3-14.2-6.7-2.7-1.4-5.3-1.7-8-.1-1.1.7-2.3 1.2-3.5 1.7v-14.3l25 11.6c2.2 1.1 4.2 1 6.3 0l23.5-11c.6-.3 1.5-.4 1.4-1.3 0-.8-.8-1-1.3-1.2l-24.5-11.5a5.2 5.2 0 0 0-4.4-.1L91-68.5v-16.2c0-1.1.2-1.3 1.3-1.3h88.9l-.1 2.3v59.6c-.1 1.1.5 1.5 1.6 1.5h9.4c1 0 1.5-.4 1.5-1.5v-4l1.3 1.2c7.9 7.7 21 6.9 27.8-1.8s7-24.2.5-33.3c-6.7-9.3-20.8-10.6-28.4-2.4-.3.3-.4.8-1.2.6V-86H422c1.2 0 1.5.3 1.5 1.5-.1 5.4 0 10.7 0 16.1-5.8-.3-10 2.7-14.2 6.6v-3.8c0-1.4-.4-2-1.8-1.9h-6.2c-4 0-4 0-4 4.1v39.1c0 1.3.4 1.8 1.6 1.8h8c2.3 0 2.3 0 2.3-2.3 0-7.8.3-15.6-.1-23.3-.2-4.1 1.6-6.1 4.9-7.5l2.6-.9c2.2-.7 4.5-.2 6.8-.4v34.6c0 1-.2 1.2-1.2 1.2h-330c-1 0-1.2-.2-1.2-1.2v-13.8l1.4.9 24.2 11.3c1.7.8 3.4.8 5-.1l13-6 11.4-5.4c.5-.2 1-.4 1-1.1 0-.7-.5-1-1-1.2l-3.4-1.6c-2-1-3.7-.9-5.6 0l-15.3 7.2c-2 .9-3.6.9-5.6 0L101-39.2c-2-.9-3.7-1.1-5.6-.1-1.2.4-2.7 1.2-4.3 1.9zm281.5-3.8h14.8c1.3 0 1.8-.5 1.8-1.8-.1-2-.1-4-.4-6A22.1 22.1 0 0 0 362-68.3a23 23 0 0 0-18.6 21.5c-.4 9.6 2.9 17.4 11.7 22.2a27.6 27.6 0 0 0 20.3 1.9c3.3-.8 6.5-2.1 9.2-4.3.6-.5 1.2-1 .5-1.9l-4.1-6c-.4-.7-1-.7-1.5-.2a17.4 17.4 0 0 1-9.7 3.7c-6.4.6-11-1.9-13.4-7.7-.7-1.8-.4-2.1 1.5-2.1zm-108 13.1c0 1.5.2 2.6 0 3.7 0 1.3.5 1.8 1.8 1.7h8.6c1.3 0 1.8-.5 1.8-1.8v-41.4c0-1.2-.5-1.7-1.7-1.7h-8c-2.3 0-2.3 0-2.3 2.4v26.1c0 .9 0 1.7-.6 2.4-2.4 2.3-5 4-8.5 4.1-6.4.2-8.8-2.1-8.8-8.5v-24.3c0-2.2 0-2.2-2.3-2.2h-7.8c-1.6-.1-2.1.6-2 2.1v10.2c0 7.2-.2 14.5.2 21.7.3 5.3 3.5 9.7 8.3 11.1s9.5 1.2 14.2-.6c2.5-1.1 4.8-2.8 7.2-5zm68-11.7v-15.1c0-2.2 0-2.2 2.4-2.2h5.4c1 0 1.5-.4 1.5-1.4v-7.6c0-1.2-.6-1.5-1.6-1.5h-6c-.6 0-1.2.1-1.4-.7a5.9 5.9 0 0 1 6.9-7.4c.7.2 1.5.4 2 .8 1.2.8 1.8.4 2.4-.6.9-1.4 1.7-2.9 2.7-4.2.9-1.2.5-1.9-.5-2.7a17.4 17.4 0 0 0-8.3-3 14.6 14.6 0 0 0-17 11.7c-.3 1.5-.4 3-.4 4.5 0 1.1-.4 1.6-1.5 1.5h-4.2c-1 0-1.5.3-1.5 1.4v7.8c0 1 .4 1.4 1.4 1.3h3.4c2.4 0 2.4 0 2.4 2.4v30c0 1.5.5 2 2 2h8.3c1.5 0 1.9-.5 1.9-2-.2-4.9-.2-10-.2-15zm-42-.3v15.4c0 1.5.6 2 2 2h7.4c2.9 0 2.9 0 2.9-2.8V-55c0-1.6.5-2.1 2-2h5.3c1 0 1.5-.4 1.5-1.5V-66c0-1.1-.4-1.6-1.5-1.5h-5.8c-.6 0-1.2.1-1.4-.7a5.7 5.7 0 0 1 5.4-7.6c1.3 0 2.5.4 3.5 1.1s1.5.4 2-.5l2.7-4.1c1-1.8 1-2-.7-3.2a17.7 17.7 0 0 0-8.1-2.7 15 15 0 0 0-15.2 7.2c-1.6 2.8-2 5.8-2 8.9.2 1.3-.3 1.8-1.6 1.7h-4.4c-1 0-1.4.4-1.4 1.3v7.8c0 1.1.5 1.4 1.5 1.4h4.2c1.4-.1 1.7.5 1.7 1.8v15zM.3 14.1L29.3.5a6 6 0 0 1 4.9.1C43.3 5 52.5 9.1 61.5 13.5c.7.3 1.4.5 1.4 1.3 0 1-.9 1.1-1.5 1.4-8.7 4.2-17.5 8.2-26.2 12.3a7.8 7.8 0 0 1-7 0L.3 15.5v-1.4zm0 34.7l5.1-2.6a7 7 0 0 1 6.3.1l16.8 8c2.1 1 4.1 1 6.2 0l17.1-8c2.1-1 4.1-1 6.2 0l3.8 1.7c.6.3 1.4.6 1.3 1.3 0 .7-.7 1-1.3 1.3l-12.7 6-14.4 6.7c-1.8.9-3.7 1-5.6 0L2 50.9c-.6-.2-1-.7-1.6-1-.2-.2-.2-.6-.2-1z"/><path fill="' . $main_color . '" d="M.3 31.5l4-1.9c3-1.8 5.7-1.4 8.8.1 5.3 2.7 10.6 5 15.9 7.5 2 .9 3.7.9 5.6 0 5.6-2.7 11.3-5.2 16.9-8a7.2 7.2 0 0 1 6.9 0c1.2.8 2.6 1.3 3.9 2 1 .6 1 1.3 0 2l-1 .4-26.9 12.5c-1.8.9-3.6.9-5.3 0L2.2 33.6c-.7-.3-1.2-.8-1.9-1v-1z"/></svg>';

	// Backend available?
	$backend_available = 1;

	// Button alt label.
	$button_title_array = array(
		'bg' => 'Сподели в buffer',
		'cs' => 'Sdílet na buffer',
		'da' => 'Del på buffer',
		'de' => 'Bei buffer teilen',
		'en' => 'Share on buffer',
		'es' => 'Compartir en buffer',
		'fi' => 'Jaa bufferissä',
		'fr' => 'Partager sur buffer',
		'hr' => 'Podijelite na buffer',
		'hu' => 'Megosztás bufferen',
		'it' => 'Condividi su buffer',
		'ja' => 'buffer上で共有',
		'ko' => 'buffer에서 공유하기',
		'nl' => 'Delen op buffer',
		'no' => 'Del på buffer',
		'pl' => 'Udostępnij przez buffer',
		'pt' => 'Compartilhar no buffer',
		'ro' => 'Partajează pe buffer',
		'ru' => 'Поделиться на buffer',
		'sk' => 'Zdieľať na buffer',
		'sl' => 'Deli na buffer',
		'sr' => 'Podeli na buffer',
		'sv' => 'Dela på buffer',
		'tr' => 'buffer\'ta paylaş',
		'zh' => '在buffer上分享',
	);
} elseif ( isset( $backend ) && 1 === $backend ) {
	// Get share counts.
	$buffer = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://api.bufferapp.com/1/links/shares.json?url=' . $post_url ) ) );
	// Decode the json response.
	$buffer_json = json_decode( $buffer, true );

	/**
	* Stores results.
	* Records errors, if enabled (e.g. request from the status tab).
	*/
	if ( isset( $buffer_json['shares'] ) ) {
		$share_counts['buffer'] = intval( $buffer_json['shares'] );
	} elseif ( isset( $record_errors ) && 1 === $record_errors ) {
		$service_errors['buffer'] = $buffer;
	}
}; // End if backend.
