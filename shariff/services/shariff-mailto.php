<?php
/**
 * Will be included in the shariff.php only, when Mailto is requested as a service.
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
	$button_url = 'mailto:?body=' . $share_url . '&subject=' . rawurlencode( urldecode( $share_title ) );

	// Colors.
	$main_color      = '#999';
	$secondary_color = '#a8a8a8';
	$wcag_color      = '#595959';

	// SVG icon.
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path fill="' . $main_color . '" d="M32 12.7v14.2q0 1.2-0.8 2t-2 0.9h-26.3q-1.2 0-2-0.9t-0.8-2v-14.2q0.8 0.9 1.8 1.6 6.5 4.4 8.9 6.1 1 0.8 1.6 1.2t1.7 0.9 2 0.4h0.1q0.9 0 2-0.4t1.7-0.9 1.6-1.2q3-2.2 8.9-6.1 1-0.7 1.8-1.6zM32 7.4q0 1.4-0.9 2.7t-2.2 2.2q-6.7 4.7-8.4 5.8-0.2 0.1-0.7 0.5t-1 0.7-0.9 0.6-1.1 0.5-0.9 0.2h-0.1q-0.4 0-0.9-0.2t-1.1-0.5-0.9-0.6-1-0.7-0.7-0.5q-1.6-1.1-4.7-3.2t-3.6-2.6q-1.1-0.7-2.1-2t-1-2.5q0-1.4 0.7-2.3t2.1-0.9h26.3q1.2 0 2 0.8t0.9 2z"/></svg>';

	// Same window?
	$same_window = 1;

	// Button text label.
	$button_text_array = array(
		'bg' => 'имейл',
		'da' => 'e-mail',
		'de' => 'E-Mail',
		'en' => 'email',
		'es' => 'correo',
		'fi' => 'sähköpostitse',
		'fr' => 'courriel',
		'hr' => 'e-pošta',
		'hu' => 'e-mail',
		'it' => 'e-mail',
		'ja' => 'e-mail',
		'ko' => 'e-mail',
		'nl' => 'e-mail',
		'no' => 'e-post',
		'pl' => 'e-mail',
		'pt' => 'e-mail',
		'ro' => 'e-mail',
		'ru' => 'e-mail',
		'sk' => 'e-mail',
		'sl' => 'e-mail',
		'sr' => 'e-mail',
		'sv' => 'e-post',
		'tr' => 'e-posta',
		'zh' => '分享',
	);

	// Button alt label.
	$button_title_array = array(
		'bg' => 'Изпрати по имейл',
		'cs' => 'Poslat mailem',
		'da' => 'Sende via e-mail',
		'de' => 'Per E-Mail versenden',
		'en' => 'Send by email',
		'es' => 'Enviar por correo electrónico',
		'fi' => 'Lähetä sähköpostitse',
		'fr' => 'Envoyer par courriel',
		'hr' => 'Pošaljite emailom',
		'hu' => 'Elküldés e-mailben',
		'it' => 'Inviare via email',
		'ja' => '電子メールで送信',
		'ko' => '이메일로 보내기',
		'nl' => 'Sturen via e-mail',
		'no' => 'Send via epost',
		'pl' => 'Wyślij e-mailem',
		'pt' => 'Enviar por e-mail',
		'ro' => 'Trimite prin e-mail',
		'ru' => 'Отправить по эл. почте',
		'sk' => 'Poslať e-mailom',
		'sl' => 'Pošlji po elektronski pošti',
		'sr' => 'Pošalji putem email-a',
		'sv' => 'Skicka via e-post',
		'tr' => 'E-posta ile gönder',
		'zh' => '通过电子邮件传送',
	);
}
