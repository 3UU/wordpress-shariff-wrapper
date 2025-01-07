<?php
/**
 *  Will be included in the shariff.php to display admin notices about missing settings.
 *
 * @package Shariff Wrapper
 * @subpackage admin
 */

// Prevent direct calls to admin_menu.php.
if ( ! class_exists( 'WP' ) ) {
	die();
}

/**
 * Display an info notice, if a service has been selected that requires a username, id, etc. and none has been provided.
 */
function shariff3uu_service_notice() {
	// Prevent php info notices.
	$services = array();
	// Check if any services are set and if user can manage options.
	if ( isset( $GLOBALS['shariff3uu']['services'] ) && current_user_can( 'manage_options' ) ) {
		// Patreon.
		if ( strpos( $GLOBALS['shariff3uu']['services'], 'patreon' ) !== false && empty( $GLOBALS['shariff3uu']['patreonid'] ) ) {
			$services[] = 'Patreon';
		}
		// PayPal.
		if ( strpos( $GLOBALS['shariff3uu']['services'], 'paypal' ) !== false && strpos( $GLOBALS['shariff3uu']['services'], 'paypalme' ) === false && empty( $GLOBALS['shariff3uu']['paypalbuttonid'] ) ) {
			$services[] = 'PayPal';
		}
		// PayPal.me.
		if ( strpos( $GLOBALS['shariff3uu']['services'], 'paypalme' ) !== false && empty( $GLOBALS['shariff3uu']['paypalmeid'] ) ) {
			$services[] = 'PayPal.Me';
		}
		// Bitcoin.
		if ( strpos( $GLOBALS['shariff3uu']['services'], 'bitcoin' ) !== false && empty( $GLOBALS['shariff3uu']['bitcoinaddress'] ) ) {
			$services[] = 'Bitcoin';
		}
		// Loop through services and display an info notice.
		foreach ( $services as $service ) {
			echo '<div class="notice notice-error"><p>';
				$settings_url = get_bloginfo( 'wpurl' ) . '/wp-admin/options-general.php?page=shariff3uu&tab=advanced">';
				// Translators: %s will be replaced with the correct URL to the local Shariff Settings page and tab.
				printf( wp_kses( __( 'Please check your <a href="%s">Shariff Settings</a>!', 'shariff' ), array( 'a' => array( 'href' => true ) ) ), esc_url( $settings_url ) );
				echo ' ';
				// Translators: %s will be replaced with a service name e.g. Twitter.
				printf( wp_kses( __( '%s has been selected as a service, but no username, ID or address has been provided! Please enter the required information on the advanced tab!', 'shariff' ), array() ), esc_html( $service ) );
			echo '</p></div>';
		}
	}
}
add_action( 'admin_notices', 'shariff3uu_service_notice' );
