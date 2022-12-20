<?php
/**
 * Will be included in the shariff.php only, when Mastodon is requested as a service.
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
	$service_url = esc_url( 'https://s2f.kytta.dev/' );

	// Via tag.
	if ( array_key_exists( 'mastodon_via', $atts ) ) {
		$mastodon_via = ' via @' . esc_html( $atts['mastodon_via'] );
	} else {
		$mastodon_via = '';
	}

	// Build button URL.
	$button_url = $service_url . '?text=' . $share_title . ' ' . $share_url . $mastodon_via;

	// Colors.
	$main_color      = '#6364FF';
	$secondary_color = '#563ACC';
	$wcag_color      = '#17063B';

	// SVG icon.
	$svg_icon = '<svg width="75" height="79" viewBox="0 0 75 79" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M37.813-.025C32.462-.058 27.114.13 21.79.598c-8.544.621-17.214 5.58-20.203 13.931C-1.12 23.318.408 32.622.465 41.65c.375 7.316.943 14.78 3.392 21.73 4.365 9.465 14.781 14.537 24.782 15.385 7.64.698 15.761-.213 22.517-4.026a54.1 54.1 0 0 0 .01-6.232c-6.855 1.316-14.101 2.609-21.049 1.074-3.883-.88-6.876-4.237-7.25-8.215-1.53-3.988 3.78-.43 5.584-.883 9.048 1.224 18.282.776 27.303-.462 7.044-.837 14.26-4.788 16.65-11.833 2.263-6.135 1.215-12.79 1.698-19.177.06-3.84.09-7.692-.262-11.52C72.596 7.844 63.223.981 53.834.684a219.453 219.453 0 0 0-16.022-.71zm11.294 12.882c5.5-.067 10.801 4.143 11.67 9.653.338 1.48.471 3 .471 4.515v21.088h-8.357c-.07-7.588.153-15.182-.131-22.765-.587-4.368-7.04-5.747-9.672-2.397-2.422 3.04-1.47 7.155-1.67 10.735v6.392h-8.307c-.146-4.996.359-10.045-.404-15.002-1.108-4.218-7.809-5.565-10.094-1.666-1.685 3.046-.712 6.634-.976 9.936v14.767h-8.354c.109-8.165-.238-16.344.215-24.5.674-5.346 5.095-10.389 10.676-10.627 4.902-.739 10.103 2.038 12.053 6.631.375 1.435 1.76 1.932 1.994.084 1.844-3.704 5.501-6.739 9.785-6.771.367-.044.735-.068 1.101-.073z"/><defs><linearGradient id="paint0_linear_549_34" x1="37.0692" y1="0" x2="37.0692" y2="79" gradientUnits="userSpaceOnUse"><stop stop-color="' . $main_color . '"/><stop offset="1" stop-color="' . $secondary_color . '"/></linearGradient></defs></svg>';

	// Backend available?
	$backend_available = 0;

	// Button alt label.
	$button_title_array = array(
		'bg' => 'Сподели във Mastodon',
		'cs' => 'Sdílet na Mastodonu',
		'da' => 'Del på Mastodon',
		'de' => 'Bei Mastodon teilen',
		'en' => 'Share on Mastodon',
		'es' => 'Compartir en Mastodon',
		'fi' => 'Jaa Mastodonissa',
		'fr' => 'Partager sur Mastodon',
		'hr' => 'Podijelite na Mastodonu',
		'hu' => 'Megosztás Mastodonon',
		'it' => 'Condividi su Mastodon',
		'ja' => 'フェイスブック上で共有',
		'ko' => '페이스북에서 공유하기',
		'nl' => 'Delen op Mastodon',
		'no' => 'Del på Mastodon',
		'pl' => 'Udostępnij na Mastodonu',
		'pt' => 'Compartilhar no Mastodon',
		'ro' => 'Partajează pe Mastodon',
		'ru' => 'Поделиться на Mastodon',
		'sk' => 'Zdieľať na Mastodonu',
		'sl' => 'Deli na Mastodonu',
		'sr' => 'Podeli na Mastodon-u',
		'sv' => 'Dela på Mastodon',
		'tr' => 'Mastodon\'ta paylaş',
		'zh' => '在Mastodon上分享',
	);
}
