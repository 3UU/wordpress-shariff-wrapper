<?php
/**
 * Will be included in the shariff.php only, when an admin is logged in.
 *
 * @package Shariff Wrapper
 * @subpackage admin
 */

// Prevent direct calls to admin_menu.php.
if ( ! class_exists( 'WP' ) ) {
	die();
}

// Set services that have a share count API / backend.
$shariff3uu_services_backend = array( 'facebook', 'pinterest', 'reddit', 'tumblr', 'vk', 'addthis', 'odnoklassniki', 'buffer' );

// Adds the actions for the admin page.
add_action( 'admin_menu', 'shariff3uu_add_admin_menu' );
add_action( 'admin_init', 'shariff3uu_options_init' );
add_action( 'init', 'shariff_init_locale' );

/**
 * Scripts and styles for admin pages e.g. info notice.
 *
 * @param string $hook The calling hook.
 */
function shariff3uu_admin_style( $hook ) {
	// Scripts only needed on our plugin options page - no need to load them on _ALL_ admin pages.
	if ( 'settings_page_shariff3uu' === $hook ) {
		// Scripts for pinterest default image media uploader.
		wp_enqueue_media();
		$shariff_js_media_url = trailingslashit( plugins_url() ) . 'shariff/js/shariff-media.js';
		wp_register_script( 'shariff_mediaupload', $shariff_js_media_url, array( 'jquery' ), '1.0', true );
		$translation_array = array(
			'choose_image' => __( 'Choose image', 'shariff' ),
		);
		wp_localize_script( 'shariff_mediaupload', 'shariff_media', $translation_array );
		wp_enqueue_script( 'shariff_mediaupload' );
	}
}
add_action( 'admin_enqueue_scripts', 'shariff3uu_admin_style' );

/**
 * Adds the admin menu.
 */
function shariff3uu_add_admin_menu() {
	add_options_page( 'Shariff', 'Shariff', 'manage_options', 'shariff3uu', 'shariff3uu_options_page' );
}

/**
 * Creates the plugin options page.
 */
function shariff3uu_options_init() {

	/** First tab - basic */

	// Registers first tab (basic) settings and calls sanitize function.
	register_setting( 'shariff3uu_basic', 'shariff3uu_basic', 'shariff3uu_basic_sanitize' );

	// First tab - basic options.
	add_settings_section(
		'shariff3uu_basic_section',
		__( 'Basic options', 'shariff' ),
		'shariff3uu_basic_section_callback',
		'shariff3uu_basic'
	);

	// Services.
	add_settings_field( 'shariff3uu_text_services', '<div style="width:450px">' . __( 'Enable the following services in the provided order:', 'shariff' ) . '</div>', 'shariff3uu_text_services_render', 'shariff3uu_basic', 'shariff3uu_basic_section' );

	// Add after.
	add_settings_field(
		'shariff3uu_multiplecheckbox_add_after',
		__( 'Add the Shariff buttons <u>after</u> all:', 'shariff' ),
		'shariff3uu_multiplecheckbox_add_after_render',
		'shariff3uu_basic',
		'shariff3uu_basic_section'
	);

	// Add before.
	add_settings_field(
		'shariff3uu_checkbox_add_before',
		__( 'Add the Shariff buttons <u>before</u> all:', 'shariff' ),
		'shariff3uu_multiplecheckbox_add_before_render',
		'shariff3uu_basic',
		'shariff3uu_basic_section'
	);

	// Disable on protected posts.
	add_settings_field(
		'shariff3uu_checkbox_disable_on_protected',
		__( 'Disable the Shariff buttons on password protected posts.', 'shariff' ),
		'shariff3uu_checkbox_disable_on_protected_render',
		'shariff3uu_basic',
		'shariff3uu_basic_section'
	);

	// Disable outside of loop.
	add_settings_field(
		'shariff3uu_checkbox_disable_outside_loop',
		__( 'Disable the Shariff buttons outside of the main loop.', 'shariff' ),
		'shariff3uu_checkbox_disable_outside_loop_render',
		'shariff3uu_basic',
		'shariff3uu_basic_section'
	);

	// Add to custom WordPress hooks.
	add_settings_field( 'shariff3uu_text_custom_hooks', __( 'Add Shariff to the following custom WordPress hooks:', 'shariff' ), 'shariff3uu_text_custom_hooks_render', 'shariff3uu_basic', 'shariff3uu_basic_section' );

	// Shortcode to use for custom hook.
	add_settings_field( 'shariff3uu_text_custom_hooks_shortcode', __( 'Use the following shortcode for the custom hooks:', 'shariff' ), 'shariff3uu_text_custom_hooks_shortcode_render', 'shariff3uu_basic', 'shariff3uu_basic_section' );

	/** Second tab - design */

	// Registers second tab (design) settings and calls sanitize function.
	register_setting( 'shariff3uu_design', 'shariff3uu_design', 'shariff3uu_design_sanitize' );

	// Second tab - design options.
	add_settings_section( 'shariff3uu_design_section', __( 'Design options', 'shariff' ), 'shariff3uu_design_section_callback', 'shariff3uu_design' );

	// Button language.
	add_settings_field(
		'shariff3uu_select_language',
		'<div style="width:450px">' . esc_html__( 'Default button language:', 'shariff' ) . '</div>',
		'shariff3uu_select_language_render',
		'shariff3uu_design',
		'shariff3uu_design_section'
	);

	// Automatic button language for multilingual sites.
	add_settings_field(
		'shariff3uu_checkbox_autolang',
		__( 'Automatically set button language based on locale (e.g. set by WPML).', 'shariff' ),
		'shariff3uu_checkbox_autolang_render',
		'shariff3uu_design',
		'shariff3uu_design_section'
	);

	// Theme.
	add_settings_field(
		'shariff3uu_radio_theme',
		__( 'Shariff button design:', 'shariff' ),
		'shariff3uu_radio_theme_render',
		'shariff3uu_design',
		'shariff3uu_design_section'
	);

	// Button size.
	add_settings_field(
		'shariff3uu_checkbox_buttonsize',
		__( 'Button size:', 'shariff' ),
		'shariff3uu_checkbox_buttonsize_render',
		'shariff3uu_design',
		'shariff3uu_design_section'
	);

	// Button stretch.
	add_settings_field(
		'shariff3uu_checkbox_buttonsstretch',
		__( 'Stretch buttons horizontally to full width.', 'shariff' ),
		'shariff3uu_checkbox_buttonstretch_render',
		'shariff3uu_design',
		'shariff3uu_design_section'
	);

	// Border radius.
	add_settings_field(
		'shariff3uu_number_borderradius',
		__( 'Border radius for the round theme (1-50):', 'shariff' ),
		'shariff3uu_number_borderradius_render',
		'shariff3uu_design',
		'shariff3uu_design_section'
	);

	// Custom main color.
	add_settings_field(
		'shariff3uu_text_maincolor',
		__( 'Custom main color for <b>all</b> buttons (hexadecimal):', 'shariff' ),
		'shariff3uu_text_maincolor_render',
		'shariff3uu_design',
		'shariff3uu_design_section'
	);

	// Custom secondary color.
	add_settings_field(
		'shariff3uu_text_secondarycolor',
		__( 'Custom secondary color for <b>all</b> buttons (hexadecimal):', 'shariff' ),
		'shariff3uu_text_secondarycolor_render',
		'shariff3uu_design',
		'shariff3uu_design_section'
	);

	// Vertical.
	add_settings_field(
		'shariff3uu_checkbox_vertical',
		__( 'Shariff button orientation <b>vertical</b>.', 'shariff' ),
		'shariff3uu_checkbox_vertical_render',
		'shariff3uu_design',
		'shariff3uu_design_section'
	);

	// Alignment option.
	add_settings_field(
		'shariff3uu_radio_align',
		__( 'Alignment of the Shariff buttons:', 'shariff' ),
		'shariff3uu_radio_align_render',
		'shariff3uu_design',
		'shariff3uu_design_section'
	);

	// Alignment option for the widget.
	add_settings_field(
		'shariff3uu_radio_align_widget',
		__( 'Alignment of the Shariff buttons in the widget:', 'shariff' ),
		'shariff3uu_radio_align_widget_render',
		'shariff3uu_design',
		'shariff3uu_design_section'
	);

	// Headline.
	add_settings_field(
		'shariff3uu_text_headline',
		__( 'Headline above all Shariff buttons:', 'shariff' ),
		'shariff3uu_text_headline_render',
		'shariff3uu_design',
		'shariff3uu_design_section'
	);

	// Alternative headline if share counts are zero.
	add_settings_field(
		'shariff3uu_text_headline_zero',
		__( 'Alternative headline, if share counts are zero:', 'shariff' ),
		'shariff3uu_text_headline_zero_render',
		'shariff3uu_design',
		'shariff3uu_design_section'
	);

	// Custom css.
	add_settings_field(
		'shariff3uu_text_style',
		__( 'Custom CSS <u>attributes</u> for the container <u>around</u> Shariff:', 'shariff' ),
		'shariff3uu_text_style_render',
		'shariff3uu_design',
		'shariff3uu_design_section'
	);

	// Custom css class.
	add_settings_field(
		'shariff3uu_text_cssclass',
		__( 'Custom CSS <u>class</u> for the container <u>around</u> Shariff:', 'shariff' ),
		'shariff3uu_text_cssclass_render',
		'shariff3uu_design',
		'shariff3uu_design_section'
	);

	// Hide until css loaded.
	add_settings_field(
		'shariff3uu_checkbox_hideuntilcss',
		__( 'Hide buttons until page is fully loaded.', 'shariff' ),
		'shariff3uu_checkbox_hideuntilcss_render',
		'shariff3uu_design',
		'shariff3uu_design_section'
	);

	// Open in popup.
	add_settings_field(
		'shariff3uu_checkbox_popup',
		__( 'Open links in a popup (requires JavaScript).', 'shariff' ),
		'shariff3uu_checkbox_popup_render',
		'shariff3uu_design',
		'shariff3uu_design_section'
	);

	/** Third tab - advanced */

	// Registers third tab (advanced) settings and calls sanitize function.
	register_setting( 'shariff3uu_advanced', 'shariff3uu_advanced', 'shariff3uu_advanced_sanitize' );

	// Third tab - advanced options.
	add_settings_section(
		'shariff3uu_advanced_section',
		__( 'Advanced options', 'shariff' ),
		'shariff3uu_advanced_section_callback',
		'shariff3uu_advanced'
	);

	// Info url.
	add_settings_field(
		'shariff3uu_text_info_url',
		'<div style="width:450px">' . __( 'Custom link for the info button:', 'shariff' ) . '</div>',
		'shariff3uu_text_info_url_render',
		'shariff3uu_advanced',
		'shariff3uu_advanced_section'
	);

	// Info text.
	add_settings_field(
		'shariff3uu_text_info_text',
		__( 'Custom text for the info button:', 'shariff' ),
		'shariff3uu_text_info_text_render',
		'shariff3uu_advanced',
		'shariff3uu_advanced_section'
	);

	// Twitter via.
	add_settings_field(
		'shariff3uu_text_twittervia',
		__( 'Twitter username for the via tag:', 'shariff' ),
		'shariff3uu_text_twittervia_render',
		'shariff3uu_advanced',
		'shariff3uu_advanced_section'
	);
	
	// Mastodon via.
	add_settings_field(
		'shariff3uu_text_mastodonvia',
		__( 'Mastodon username for the via tag:', 'shariff' ),
		'shariff3uu_text_mastodonvia_render',
		'shariff3uu_advanced',
		'shariff3uu_advanced_section'
	);

	// Patreon username.
	add_settings_field(
		'shariff3uu_text_patreonid',
		__( 'Patreon username:', 'shariff' ),
		'shariff3uu_text_patreonid_render',
		'shariff3uu_advanced',
		'shariff3uu_advanced_section'
	);

	// Paypal button id.
	add_settings_field(
		'shariff3uu_text_paypalbuttonid',
		__( 'PayPal hosted button ID:', 'shariff' ),
		'shariff3uu_text_paypalbuttonid_render',
		'shariff3uu_advanced',
		'shariff3uu_advanced_section'
	);

	// Paypalme id.
	add_settings_field(
		'shariff3uu_text_paypalmeid',
		__( 'PayPal.Me ID:', 'shariff' ),
		'shariff3uu_text_paypalmeid_render',
		'shariff3uu_advanced',
		'shariff3uu_advanced_section'
	);

	// Bitcoin address.
	add_settings_field(
		'shariff3uu_text_bitcoinaddress',
		__( 'Bitcoin address:', 'shariff' ),
		'shariff3uu_text_bitcoinaddress_render',
		'shariff3uu_advanced',
		'shariff3uu_advanced_section'
	);

	// RSS feed.
	add_settings_field(
		'shariff3uu_text_rssfeed',
		__( 'RSS feed:', 'shariff' ),
		'shariff3uu_text_rssfeed_render',
		'shariff3uu_advanced',
		'shariff3uu_advanced_section'
	);

	// Default image for pinterest.
	add_settings_field(
		'shariff3uu_text_default_pinterest',
		__( 'Default image for Pinterest:', 'shariff' ),
		'shariff3uu_text_default_pinterest_render',
		'shariff3uu_advanced',
		'shariff3uu_advanced_section'
	);

	// Hide WhatsApp.
	add_settings_field(
		'shariff3uu_checkbox_hide_whatsapp',
		__( 'Hide WhatsApp on desktop devices.', 'shariff' ),
		'shariff3uu_checkbox_hide_whatsapp_render',
		'shariff3uu_advanced',
		'shariff3uu_advanced_section'
	);

	// Shortcode priority.
	add_settings_field(
		'shariff3uu_number_shortcodeprio',
		__( 'Shortcode priority:', 'shariff' ),
		'shariff3uu_number_shortcodeprio_render',
		'shariff3uu_advanced',
		'shariff3uu_advanced_section'
	);

	// Disable metabox.
	add_settings_field(
		'shariff3uu_checkbox_disable_metabox',
		__( 'Disable the metabox.', 'shariff' ),
		'shariff3uu_checkbox_disable_metabox_render',
		'shariff3uu_advanced',
		'shariff3uu_advanced_section'
	);

	/** Fifth tab - statistic */

	// Registers fifth tab (statistic) settings and calls sanitize function.
	register_setting( 'shariff3uu_statistic', 'shariff3uu_statistic', 'shariff3uu_statistic_sanitize' );

	// Fifth tab (statistic).
	add_settings_section(
		'shariff3uu_statistic_section',
		__( 'Statistic', 'shariff' ),
		'shariff3uu_statistic_section_callback',
		'shariff3uu_statistic'
	);

	// Statistic.
	add_settings_field(
		'shariff3uu_checkbox_backend',
		'<div style="width:450px">' . __( 'Enable statistic.', 'shariff' ) . '</div>',
		'shariff3uu_checkbox_backend_render',
		'shariff3uu_statistic',
		'shariff3uu_statistic_section'
	);

	// Share counts.
	add_settings_field(
		'shariff3uu_checkbox_sharecounts',
		__( 'Show share counts on buttons.', 'shariff' ),
		'shariff3uu_checkbox_sharecounts_render',
		'shariff3uu_statistic',
		'shariff3uu_statistic_section'
	);

	// Hide when zero.
	add_settings_field(
		'shariff3uu_checkbox_hidezero',
		__( 'Hide share counts when they are zero.', 'shariff' ),
		'shariff3uu_checkbox_hidezero_render',
		'shariff3uu_statistic',
		'shariff3uu_statistic_section'
	);

	// Facebook App ID.
	add_settings_field(
		'shariff3uu_text_fb_id',
		__( 'Facebook App ID:', 'shariff' ),
		'shariff3uu_text_fb_id_render',
		'shariff3uu_statistic',
		'shariff3uu_statistic_section'
	);

	// Facebook App Secret.
	add_settings_field(
		'shariff3uu_text_fb_secret',
		__( 'Facebook App Secret:', 'shariff' ),
		'shariff3uu_text_fb_secret_render',
		'shariff3uu_statistic',
		'shariff3uu_statistic_section'
	);

	// Automatic cache.
	add_settings_field(
		'shariff3uu_checkbox_automaticcache',
		__( 'Fill cache automatically.', 'shariff' ),
		'shariff3uu_checkbox_automaticcache_render',
		'shariff3uu_statistic',
		'shariff3uu_statistic_section'
	);

	// Ranking.
	add_settings_field(
		'shariff3uu_number_ranking',
		__( 'Number of posts on ranking tab:', 'shariff' ),
		'shariff3uu_number_ranking_render',
		'shariff3uu_statistic',
		'shariff3uu_statistic_section'
	);

	// TTL.
	add_settings_field(
		'shariff3uu_number_ttl',
		__( 'Cache TTL in seconds (60 - 7200):', 'shariff' ),
		'shariff3uu_number_ttl_render',
		'shariff3uu_statistic',
		'shariff3uu_statistic_section'
	);

	// Disable dynamic cache lifespan.
	add_settings_field(
		'shariff3uu_checkbox_disable_dynamic_cache',
		__( 'Disable the dynamic cache lifespan (not recommended).', 'shariff' ),
		'shariff3uu_checkbox_disable_dynamic_cache_render',
		'shariff3uu_statistic',
		'shariff3uu_statistic_section'
	);

	// Disable services.
	add_settings_field(
		'shariff3uu_multiplecheckbox_disable_services',
		__( 'Disable the following services (share counts only):', 'shariff' ),
		'shariff3uu_multiplecheckbox_disable_services_render',
		'shariff3uu_statistic',
		'shariff3uu_statistic_section'
	);

	// External hosts.
	add_settings_field(
		'shariff3uu_text_external_host',
		__( 'External API for share counts:', 'shariff' ),
		'shariff3uu_text_external_host_render',
		'shariff3uu_statistic',
		'shariff3uu_statistic_section'
	);

	// Request external api directly from js.
	add_settings_field(
		'shariff3uu_checkbox_external_direct',
		__( 'Request external API directly.', 'shariff' ),
		'shariff3uu_checkbox_external_direct_render',
		'shariff3uu_statistic',
		'shariff3uu_statistic_section'
	);

	// WP in sub folder and api only reachable there?
	add_settings_field(
		'shariff3uu_checkbox_subapi',
		__( 'Local API not reachable in root.', 'shariff' ),
		'shariff3uu_checkbox_subapi_render',
		'shariff3uu_statistic',
		'shariff3uu_statistic_section'
	);

	/** Sixth tab - help */

	// Register sixth tab (help).
	add_settings_section(
		'shariff3uu_help_section',
		__( 'Shariff Help', 'shariff' ),
		'shariff3uu_help_section_callback',
		'shariff3uu_help'
	);

	/** Seventh tab - status */

	// Register seventh tab (status).
	add_settings_section(
		'shariff3uu_status_section',
		__( 'Status', 'shariff' ),
		'shariff3uu_status_section_callback',
		'shariff3uu_status'
	);

	/** Eight tab - ranking */

	// Register eight tab (ranking).
	add_settings_section(
		'shariff3uu_ranking_section',
		__( 'Ranking', 'shariff' ),
		'shariff3uu_ranking_section_callback',
		'shariff3uu_ranking'
	);
}

/**
 * Sanitizes input from the basic settings page.
 *
 * @param array $input Input from settings page to sanitize.
 *
 * @return array Sanitized array with settings.
 */
function shariff3uu_basic_sanitize( $input ) {
	// Create array.
	$valid = array();

	if ( isset( $input['version'] ) ) {
		$valid['version'] = sanitize_text_field( $input['version'] );
	}
	if ( isset( $input['services'] ) ) {
		$valid['services'] = trim( preg_replace( '/[^A-Za-z|]/', '', sanitize_text_field( $input['services'] ) ), '|' );
	}
	if ( isset( $input['add_after'] ) ) {
		$valid['add_after'] = sani_arrays( $input['add_after'] );
	}
	if ( isset( $input['add_before'] ) ) {
		$valid['add_before'] = sani_arrays( $input['add_before'] );
	}
	if ( isset( $input['disable_on_protected'] ) ) {
		$valid['disable_on_protected'] = absint( $input['disable_on_protected'] );
	}
	if ( isset( $input['disable_outside_loop'] ) ) {
		$valid['disable_outside_loop'] = absint( $input['disable_outside_loop'] );
	}
	if ( isset( $input['custom_hooks'] ) ) {
		$valid['custom_hooks'] = sanitize_text_field( $input['custom_hooks'] );
	}
	if ( isset( $input['custom_hooks_shortcode'] ) ) {
		$valid['custom_hooks_shortcode'] = sanitize_text_field( $input['custom_hooks_shortcode'] );
	}

	// Remove empty elements.
	$valid = array_filter( $valid );

	return $valid;
}

/**
 * Sanitizes input from the design settings page.
 *
 * @param array $input Input from settings page to sanitize.
 *
 * @return array Sanitized array with settings.
 */
function shariff3uu_design_sanitize( $input ) {
	// Create array.
	$valid = array();

	if ( isset( $input['lang'] ) ) {
		$valid['lang'] = sanitize_text_field( $input['lang'] );
	}
	if ( isset( $input['autolang'] ) ) {
		$valid['autolang'] = absint( $input['autolang'] );
	}
	if ( isset( $input['theme'] ) ) {
		$valid['theme'] = sanitize_text_field( $input['theme'] );
	}
	if ( isset( $input['buttonsize'] ) ) {
		$valid['buttonsize'] = sanitize_text_field( $input['buttonsize'] );
	}
	if ( isset( $input['buttonstretch'] ) ) {
		$valid['buttonstretch'] = absint( $input['buttonstretch'] );
	}
	if ( isset( $input['borderradius'] ) ) {
		$valid['borderradius'] = absint( $input['borderradius'] );
	}
	if ( isset( $input['maincolor'] ) ) {
		$valid['maincolor'] = sanitize_text_field( $input['maincolor'] );
	}
	if ( isset( $input['secondarycolor'] ) ) {
		$valid['secondarycolor'] = sanitize_text_field( $input['secondarycolor'] );
	}
	if ( isset( $input['vertical'] ) ) {
		$valid['vertical'] = absint( $input['vertical'] );
	}
	if ( isset( $input['align'] ) ) {
		$valid['align'] = sanitize_text_field( $input['align'] );
	}
	if ( isset( $input['align_widget'] ) ) {
		$valid['align_widget'] = sanitize_text_field( $input['align_widget'] );
	}
	if ( isset( $input['style'] ) ) {
		$valid['style'] = sanitize_text_field( $input['style'] ); }
	if ( isset( $input['cssclass'] ) ) {
		$valid['cssclass'] = sanitize_text_field( $input['cssclass'] );
	}
	if ( isset( $input['headline'] ) ) {
		$valid['headline'] = wp_kses( $input['headline'], $GLOBALS['allowed_tags'] );
	}
	if ( isset( $input['headline_zero'] ) ) {
		$valid['headline_zero'] = wp_kses( $input['headline_zero'], $GLOBALS['allowed_tags'] );
	}
	if ( isset( $input['hideuntilcss'] ) ) {
		$valid['hideuntilcss'] = absint( $input['hideuntilcss'] );
	}
	if ( isset( $input['popup'] ) ) {
		$valid['popup'] = absint( $input['popup'] );
	}

	// Remove empty elements.
	$valid = array_filter( $valid );

	return $valid;
}

/**
 * Sanitize input from the advanced settings page.
 *
 * @param array $input Input from settings page to sanitize.
 *
 * @return array Sanitized array with settings.
 */
function shariff3uu_advanced_sanitize( $input ) {
	// Creates array.
	$valid = array();

	if ( isset( $input['info_url'] ) ) {
		$valid['info_url'] = esc_url_raw( $input['info_url'] );
	}
	if ( isset( $input['info_text'] ) ) {
		$valid['info_text'] = sanitize_text_field( $input['info_text'] );
	}
	if ( isset( $input['twitter_via'] ) ) {
		$valid['twitter_via'] = str_replace( '@', '', sanitize_text_field( $input['twitter_via'] ) );
	}
	if ( isset( $input['mastodon_via'] ) ) {
		$valid['mastodon_via'] = ltrim( sanitize_text_field( $input['mastodon_via'] ), "@");
	}
	if ( isset( $input['flattruser'] ) ) {
		$valid['flattruser'] = sanitize_text_field( $input['flattruser'] );
	}
	if ( isset( $input['patreonid'] ) ) {
		$valid['patreonid'] = sanitize_text_field( $input['patreonid'] );
	}
	if ( isset( $input['paypalbuttonid'] ) ) {
		$valid['paypalbuttonid'] = sanitize_text_field( $input['paypalbuttonid'] );
	}
	if ( isset( $input['paypalmeid'] ) ) {
		$valid['paypalmeid'] = sanitize_text_field( $input['paypalmeid'] );
	}
	if ( isset( $input['bitcoinaddress'] ) ) {
		$valid['bitcoinaddress'] = sanitize_text_field( $input['bitcoinaddress'] );
	}
	if ( isset( $input['rssfeed'] ) ) {
		$valid['rssfeed'] = sanitize_text_field( $input['rssfeed'] );
	}
	if ( isset( $input['default_pinterest'] ) ) {
		$valid['default_pinterest'] = sanitize_text_field( $input['default_pinterest'] );
	}
	if ( isset( $input['hide_whatsapp'] ) ) {
		$valid['hide_whatsapp'] = absint( $input['hide_whatsapp'] );
	}
	if ( isset( $input['shortcodeprio'] ) ) {
		$valid['shortcodeprio'] = absint( $input['shortcodeprio'] );
	}
	if ( isset( $input['disable_metabox'] ) ) {
		$valid['disable_metabox'] = absint( $input['disable_metabox'] );
	}

	// Remove empty elements.
	$valid = array_filter( $valid );

	return $valid;
}

/**
 * Sanitizes input from the statistic settings page.
 *
 * @param array $input Input from settings page to sanitize.
 *
 * @return array Sanitized array with settings.
 */
function shariff3uu_statistic_sanitize( $input ) {
	// Creates array.
	$valid = array();

	if ( isset( $input['backend'] ) ) {
		$valid['backend'] = absint( $input['backend'] );
	}
	if ( isset( $input['sharecounts'] ) ) {
		$valid['sharecounts'] = absint( $input['sharecounts'] );
	}
	if ( isset( $input['hidezero'] ) ) {
		$valid['hidezero'] = absint( $input['hidezero'] );
	}
	if ( isset( $input['ranking'] ) ) {
		$valid['ranking'] = absint( $input['ranking'] );
	}
	if ( isset( $input['automaticcache'] ) ) {
		$valid['automaticcache'] = absint( $input['automaticcache'] );
	}
	if ( isset( $input['fb_id'] ) ) {
		$valid['fb_id'] = sanitize_text_field( $input['fb_id'] );
	}
	if ( isset( $input['fb_secret'] ) ) {
		$valid['fb_secret'] = sanitize_text_field( $input['fb_secret'] );
	}
	if ( isset( $input['ttl'] ) ) {
		$valid['ttl'] = absint( $input['ttl'] );
	}
	if ( isset( $input['disable_dynamic_cache'] ) ) {
		$valid['disable_dynamic_cache'] = absint( $input['disable_dynamic_cache'] );
	}
	if ( isset( $input['disable'] ) ) {
		$valid['disable'] = sani_arrays( $input['disable'] );
	}
	if ( isset( $input['external_host'] ) ) {
		$valid['external_host'] = str_replace( ' ', '', rtrim( esc_url_raw( $input['external_host'], '/' ) ) );
	}
	if ( isset( $input['external_direct'] ) ) {
		$valid['external_direct'] = absint( $input['external_direct'] );
	}
	if ( isset( $input['subapi'] ) ) {
		$valid['subapi'] = absint( $input['subapi'] );
	}

	// Protect users from themselves.
	if ( isset( $valid['ttl'] ) && $valid['ttl'] < '60' ) {
		$valid['ttl'] = '';
	} elseif ( isset( $valid['ttl'] ) && $valid['ttl'] > '7200' ) {
		$valid['ttl'] = '7200';
	}

	// Remove empty elements.
	$valid = array_filter( $valid );

	return $valid;
}

/**
 * Helper function to sanitize arrays.
 *
 * @param array $data Input array.
 *
 * @return array Sanitized array.
 */
function sani_arrays( $data = array() ) {
	if ( ! is_array( $data ) || ! count( $data ) ) {
		return array();
	}
	foreach ( $data as $k => $v ) {
		if ( ! is_array( $v ) && ! is_object( $v ) ) {
			$data[ $k ] = absint( trim( $v ) );
		}
		if ( is_array( $v ) ) {
			$data[ $k ] = sani_arrays( $v );
		}
	}
	return $data;
}

/** Render admin options: use isset() to prevent errors while debug mode is on. */

/** Basic options */

/**
 * Description of basic options.
 */
function shariff3uu_basic_section_callback() {
	esc_html_e( 'Select the desired services in the order you want them to be displayed and where the Shariff buttons should be included automatically.', 'shariff' );
}

/**
 * Services.
 */
function shariff3uu_text_services_render() {
	if ( isset( $GLOBALS['shariff3uu_basic']['services'] ) ) {
		$services = $GLOBALS['shariff3uu_basic']['services'];
	} else {
		$services = '';
	}
	echo '<input type="text" name="shariff3uu_basic[services]" value="' . esc_html( $services ) . '" size="90" placeholder="mastodon|facebook|linkedin|info">';
	echo '<p><code>addthis|bitcoin|buffer|diaspora|facebook|flipboard|info|linkedin|mailto|mastodon|mewe|mix</code></p>';
	echo '<p><code>odnoklassniki|patreon|paypal|paypalme|pinterest|pocket|printer|reddit|rss|sms</code></p>';
	echo '<p><code>telegram|threema|tumblr|twitter|vk|wallabag|weibo|whatsapp|xing</code></p>';
	echo '<p>' . esc_html__( 'Use the pipe sign | (Alt Gr + &lt; or &#8997; + 7) between two or more services.', 'shariff' ) . '</p>';
}

/**
 * Add after.
 */
function shariff3uu_multiplecheckbox_add_after_render() {
	// Add after all posts.
	echo '<p><input type="checkbox" name="shariff3uu_basic[add_after][posts]" ';
	if ( isset( $GLOBALS['shariff3uu_basic']['add_after']['posts'] ) ) {
		echo checked( $GLOBALS['shariff3uu_basic']['add_after']['posts'], 1, 0 );
	}
	echo ' value="1">' . esc_html__( 'Posts', 'shariff' ) . '</p>';

	// Add after all posts (blog page).
	echo '<p><input type="checkbox" name="shariff3uu_basic[add_after][posts_blogpage]" ';
	if ( isset( $GLOBALS['shariff3uu_basic']['add_after']['posts_blogpage'] ) ) {
		echo checked( $GLOBALS['shariff3uu_basic']['add_after']['posts_blogpage'], 1, 0 );
	}
	echo ' value="1">' . esc_html__( 'Posts (blog page)', 'shariff' ) . '</p>';

	// Add after all pages.
	echo '<p><input type="checkbox" name="shariff3uu_basic[add_after][pages]" ';
	if ( isset( $GLOBALS['shariff3uu_basic']['add_after']['pages'] ) ) {
		echo checked( $GLOBALS['shariff3uu_basic']['add_after']['pages'], 1, 0 );
	}
	echo ' value="1">' . esc_html__( 'Pages', 'shariff' ) . '</p>';

	// Add after all excerpts.
	echo '<p><input type="checkbox" name="shariff3uu_basic[add_after][excerpt]" ';
	if ( isset( $GLOBALS['shariff3uu_basic']['add_after']['excerpt'] ) ) {
		echo checked( $GLOBALS['shariff3uu_basic']['add_after']['excerpt'], 1, 0 );
	}
	echo ' value="1">' . esc_html__( 'Excerpts', 'shariff' ) . '</p>';

	// Add after custom post types - choose after which to add.
	$post_types = get_post_types( array( '_builtin' => false ) );
	if ( isset( $post_types ) && is_array( $post_types ) && ! empty( $post_types ) ) {
		echo '<p>Custom Post Types:</p>';
	};

	foreach ( $post_types as $post_type ) {
		$object = get_post_type_object( $post_type );
		printf(
			'<p><input type="checkbox" name="shariff3uu_basic[add_after][%s]" %s value="1">%s</p>',
			$post_type,
			isset( $GLOBALS['shariff3uu_basic']['add_after'][ $post_type ] ) ? checked( $GLOBALS['shariff3uu_basic']['add_after'][ $post_type ], 1, 0 ) : '',
			// The following should already be localized <- not always, but there is no way to know, so we have to accept the language mix up.
			esc_html( $object->labels->singular_name )
		);
	}
}

/**
 * Add before.
 */
function shariff3uu_multiplecheckbox_add_before_render() {
	// Add before all posts.
	echo '<p><input type="checkbox" name="shariff3uu_basic[add_before][posts]" ';
	if ( isset( $GLOBALS['shariff3uu_basic']['add_before']['posts'] ) ) {
		echo checked( $GLOBALS['shariff3uu_basic']['add_before']['posts'], 1, 0 );
	}
	echo ' value="1">' . esc_html__( 'Posts', 'shariff' ) . '</p>';

	// Add before all posts (blog page).
	echo '<p><input type="checkbox" name="shariff3uu_basic[add_before][posts_blogpage]" ';
	if ( isset( $GLOBALS['shariff3uu_basic']['add_before']['posts_blogpage'] ) ) {
		echo checked( $GLOBALS['shariff3uu_basic']['add_before']['posts_blogpage'], 1, 0 );
	}
	echo ' value="1">' . esc_html__( 'Posts (blog page)', 'shariff' ) . '</p>';

	// Add before all pages.
	echo '<p><input type="checkbox" name="shariff3uu_basic[add_before][pages]" ';
	if ( isset( $GLOBALS['shariff3uu_basic']['add_before']['pages'] ) ) {
		echo checked( $GLOBALS['shariff3uu_basic']['add_before']['pages'], 1, 0 );
	}
	echo ' value="1">' . esc_html__( 'Pages', 'shariff' ) . '</p>';

	// Add before all excerpts.
	echo '<p><input type="checkbox" name="shariff3uu_basic[add_before][excerpt]" ';
	if ( isset( $GLOBALS['shariff3uu_basic']['add_before']['excerpt'] ) ) {
		echo checked( $GLOBALS['shariff3uu_basic']['add_before']['excerpt'], 1, 0 );
	}
	echo ' value="1">' . esc_html__( 'Excerpts', 'shariff' ) . '</p>';

	// Add before custom post types - choose before which to add.
	$post_types = get_post_types( array( '_builtin' => false ) );
	if ( isset( $post_types ) && is_array( $post_types ) && ! empty( $post_types ) ) {
		echo '<p>Custom Post Types:</p>';
	};

	foreach ( $post_types as $post_type ) {
		$object = get_post_type_object( $post_type );
		printf(
			'<p><input type="checkbox" name="shariff3uu_basic[add_before][%s]" %s value="1">%s</p>',
			$post_type,
			isset( $GLOBALS['shariff3uu_basic']['add_before'][ $post_type ] ) ? checked( $GLOBALS['shariff3uu_basic']['add_before'][ $post_type ], 1, 0 ) : '',
			// The following should already be localized <- not always, but there is no way to know, so we have to accept the language mix up.
			esc_html( $object->labels->singular_name )
		);
	}
}

/**
 * Disable on password protected posts.
 */
function shariff3uu_checkbox_disable_on_protected_render() {
	echo '<input type="checkbox" name="shariff3uu_basic[disable_on_protected]" ';
	if ( isset( $GLOBALS['shariff3uu_basic']['disable_on_protected'] ) ) {
		echo checked( $GLOBALS['shariff3uu_basic']['disable_on_protected'], 1, 0 );
	}
	echo ' value="1">';
}

/**
 * Disable outside loop.
 */
function shariff3uu_checkbox_disable_outside_loop_render() {
	echo '<input type="checkbox" name="shariff3uu_basic[disable_outside_loop]" ';
	if ( isset( $GLOBALS['shariff3uu_basic']['disable_outside_loop'] ) ) {
		echo checked( $GLOBALS['shariff3uu_basic']['disable_outside_loop'], 1, 0 );
	}
	echo ' value="1">';
}

/**
 * Custom hooks.
 */
function shariff3uu_text_custom_hooks_render() {
	if ( isset( $GLOBALS['shariff3uu_basic']['custom_hooks'] ) ) {
		$custom_hooks = $GLOBALS['shariff3uu_basic']['custom_hooks'];
	} else {
		$custom_hooks = '';
	}
	echo '<input type="text" name="shariff3uu_basic[custom_hooks]" value="' . esc_html( $custom_hooks ) . '" size="90" placeholder="' . esc_html__( 'some_custom_wordpress_hook|some_other_custom_wordpress_hook', 'shariff' ) . '">';
	echo '<p>' . esc_html__( 'Use the pipe sign | (Alt Gr + &lt; or &#8997; + 7) between two or more hooks.', 'shariff' ) . '</p>';
}

/**
 * Custom hooks shortcode.
 */
function shariff3uu_text_custom_hooks_shortcode_render() {
	if ( isset( $GLOBALS['shariff3uu_basic']['custom_hooks_shortcode'] ) ) {
		$custom_hooks_shortcode = $GLOBALS['shariff3uu_basic']['custom_hooks_shortcode'];
	} else {
		$custom_hooks_shortcode = '';
	}
	echo '<input type="text" name="shariff3uu_basic[custom_hooks_shortcode]" value="' . esc_html( $custom_hooks_shortcode ) . '" size="90" placeholder="[shariff]">';
}

/** Design options */

/**
 * Description of the design options.
 */
function shariff3uu_design_section_callback() {
	esc_html_e( 'This configures the default design of the Shariff buttons. Most options can be overwritten for single posts or pages with the options within the [shariff] shorttag. ', 'shariff' );
	$help_link = get_bloginfo( 'wpurl' ) . '/wp-admin/options-general.php?page=shariff3uu&tab=help';
	// Translators: %s will be replaced with the correct URL to the help section.
	printf( wp_kses( __( 'For more information please take a look at the <a href="%s">Help Section</a>. ', 'shariff' ), array( 'a' => array( 'href' => true ) ) ), esc_url( $help_link ) );
	printf(
		wp_kses(
			// Translators: %s will be replaced with the correct URL to the wordpress.org support forum.
			__( 'You should also check out the <a href="%s" target="_blank">Support Forum</a>. ', 'shariff' ),
			array(
				'a' => array(
					'href'   => true,
					'target' => true,
				),
			)
		),
		'https://wordpress.org/support/plugin/shariff/'
	);
}

/**
 * Language.
 */
function shariff3uu_select_language_render() {
	$options = $GLOBALS['shariff3uu_design'];
	if ( ! isset( $options['lang'] ) ) {
		$options['lang'] = substr( get_locale(), 0, 2 );
	}
	echo '<select name="shariff3uu_design[lang]">
	<option value="bg" ' . selected( $options['lang'], 'bg', 0 ) . '>български</option>
	<option value="cs" ' . selected( $options['lang'], 'cs', 0 ) . '>český</option>
	<option value="da" ' . selected( $options['lang'], 'da', 0 ) . '>Dansk</option>
	<option value="de" ' . selected( $options['lang'], 'de', 0 ) . '>Deutsch</option>
	<option value="en" ' . selected( $options['lang'], 'en', 0 ) . '>English</option>
	<option value="es" ' . selected( $options['lang'], 'es', 0 ) . '>Español</option>
	<option value="fi" ' . selected( $options['lang'], 'fi', 0 ) . '>Finnish</option>
	<option value="fr" ' . selected( $options['lang'], 'fr', 0 ) . '>Français</option>
	<option value="hr" ' . selected( $options['lang'], 'hr', 0 ) . '>Croatian</option>
	<option value="hu" ' . selected( $options['lang'], 'hu', 0 ) . '>Magyar</option>
	<option value="it" ' . selected( $options['lang'], 'it', 0 ) . '>Italiano</option>
	<option value="ja" ' . selected( $options['lang'], 'ja', 0 ) . '>日本語</option>
	<option value="ko" ' . selected( $options['lang'], 'ko', 0 ) . '>한국어</option>
	<option value="nl" ' . selected( $options['lang'], 'nl', 0 ) . '>Nederlands</option>
	<option value="no" ' . selected( $options['lang'], 'no', 0 ) . '>Norsk</option>
	<option value="pl" ' . selected( $options['lang'], 'pl', 0 ) . '>Polskie</option>
	<option value="pt" ' . selected( $options['lang'], 'pt', 0 ) . '>Português</option>
	<option value="ro" ' . selected( $options['lang'], 'ro', 0 ) . '>Română</option>
	<option value="ru" ' . selected( $options['lang'], 'ru', 0 ) . '>Pусский</option>
	<option value="sk" ' . selected( $options['lang'], 'sk', 0 ) . '>Slovenský</option>
	<option value="sl" ' . selected( $options['lang'], 'sl', 0 ) . '>Slovenščina</option>
	<option value="sr" ' . selected( $options['lang'], 'sr', 0 ) . '>Српски</option>
	<option value="sv" ' . selected( $options['lang'], 'sv', 0 ) . '>Svenska</option>
	<option value="tr" ' . selected( $options['lang'], 'tr', 0 ) . '>Türk</option>
	<option value="zh" ' . selected( $options['lang'], 'zh', 0 ) . '>中文</option>
	</select>';
}

/**
 * Automatic button language.
 */
function shariff3uu_checkbox_autolang_render() {
	echo '<input type="checkbox" name="shariff3uu_design[autolang]" ';
	if ( isset( $GLOBALS['shariff3uu_design']['autolang'] ) ) {
		echo checked( $GLOBALS['shariff3uu_design']['autolang'], 1, 0 );
	}
	echo ' value="1">';
}

/**
 * Theme.
 */
function shariff3uu_radio_theme_render() {
	$options = $GLOBALS['shariff3uu_design'];
	if ( ! isset( $options['theme'] ) ) {
		$options['theme'] = '';
	}
	$plugins_url = plugins_url();
	echo '<div style="display:grid;grid-template-columns:75px auto;grid-template-rows:44px 45px 45px 45px 45px 45px;grid-auto-flow: column;align-items:center">
		<div><input type="radio" name="shariff3uu_design[theme]" value="" ' . checked( $options['theme'], '', 0 ) . '>default</div>
		<div><input type="radio" name="shariff3uu_design[theme]" value="color" ' . checked( $options['theme'], 'color', 0 ) . '>color</div>
		<div><input type="radio" name="shariff3uu_design[theme]" value="grey" ' . checked( $options['theme'], 'grey', 0 ) . '>grey</div>
		<div><input type="radio" name="shariff3uu_design[theme]" value="white" ' . checked( $options['theme'], 'white', 0 ) . '>white</div>
		<div><input type="radio" name="shariff3uu_design[theme]" value="round" ' . checked( $options['theme'], 'round', 0 ) . '>round</div>
		<div><input type="radio" name="shariff3uu_design[theme]" value="wcag" ' . checked( $options['theme'], 'wcag', 0 ) . '>wcag</div>
		<img alt="Shariff Designs" src="' . esc_url( $plugins_url ) . '/shariff/images/shariff_designs.png" style="grid-row: 1 / span 6;align-self:baseline">
	</div>';
}

/**
 * Button size.
 */
function shariff3uu_checkbox_buttonsize_render() {
	$options = $GLOBALS['shariff3uu_design'];
	if ( ! isset( $options['buttonsize'] ) ) {
		$options['buttonsize'] = 'medium';
	}
	echo '<p><input type="radio" name="shariff3uu_design[buttonsize]" value="small" ' . checked( $options['buttonsize'], 'small', 0 ) . '>' . esc_html__( 'small', 'shariff' ) . '</p>';
	echo '<p><input type="radio" name="shariff3uu_design[buttonsize]" value="medium" ' . checked( $options['buttonsize'], 'medium', 0 ) . '>' . esc_html__( 'medium', 'shariff' ) . '</p>';
	echo '<p><input type="radio" name="shariff3uu_design[buttonsize]" value="large" ' . checked( $options['buttonsize'], 'large', 0 ) . '>' . esc_html__( 'large', 'shariff' ) . '</p>';
}

/**
 * Button stretch.
 */
function shariff3uu_checkbox_buttonstretch_render() {
	echo '<input type="checkbox" name="shariff3uu_design[buttonstretch]" ';
	if ( isset( $GLOBALS['shariff3uu_design']['buttonstretch'] ) ) {
		echo checked( $GLOBALS['shariff3uu_design']['buttonstretch'], 1, 0 );
	}
	echo ' value="1">';
}

/**
 * Border radius.
 */
function shariff3uu_number_borderradius_render() {
	$plugins_url = plugins_url();
	if ( isset( $GLOBALS['shariff3uu_design']['borderradius'] ) ) {
		$borderradius = $GLOBALS['shariff3uu_design']['borderradius'];
	} else {
		$borderradius = '';
	}
	echo '<input type="number" name="shariff3uu_design[borderradius]" value="' . esc_attr( $borderradius ) . '" maxlength="2" min="1" max="50" placeholder="50" style="width: 75px">';
	echo '<img src="' . esc_url( $plugins_url ) . '/shariff/images/borderradius.png" align="top">';
}

/**
 * Custom main color.
 */
function shariff3uu_text_maincolor_render() {
	if ( isset( $GLOBALS['shariff3uu_design']['maincolor'] ) ) {
		$maincolor = $GLOBALS['shariff3uu_design']['maincolor'];
	} else {
		$maincolor = '';
	}
	echo '<input type="text" name="shariff3uu_design[maincolor]" value="' . esc_html( $maincolor ) . '" size="7" placeholder="#000000">';
}

/**
 * Custom secondary color.
 */
function shariff3uu_text_secondarycolor_render() {
	if ( isset( $GLOBALS['shariff3uu_design']['secondarycolor'] ) ) {
		$secondarycolor = $GLOBALS['shariff3uu_design']['secondarycolor'];
	} else {
		$secondarycolor = '';
	}
	echo '<input type="text" name="shariff3uu_design[secondarycolor]" value="' . esc_html( $secondarycolor ) . '" size="7" placeholder="#afafaf">';
}

/**
 * Vertical.
 */
function shariff3uu_checkbox_vertical_render() {
	$plugins_url = plugins_url();
	echo '<input type="checkbox" name="shariff3uu_design[vertical]" ';
	if ( isset( $GLOBALS['shariff3uu_design']['vertical'] ) ) {
		echo checked( $GLOBALS['shariff3uu_design']['vertical'], 1, 0 );
	}
	echo ' value="1">';
}

/**
 * Alignment.
 */
function shariff3uu_radio_align_render() {
	$options = $GLOBALS['shariff3uu_design'];
	if ( ! isset( $options['align'] ) ) {
		$options['align'] = 'flex-start';
	}
	echo '<p><input type="radio" name="shariff3uu_design[align]" value="flex-start" ' . checked( $options['align'], 'flex-start', 0 ) . '>' . esc_html__( 'left', 'shariff' ) . '</p>';
	echo '<p><input type="radio" name="shariff3uu_design[align]" value="center" ' . checked( $options['align'], 'center', 0 ) . '>' . esc_html__( 'center', 'shariff' ) . '</p>';
	echo '<p><input type="radio" name="shariff3uu_design[align]" value="flex-end" ' . checked( $options['align'], 'flex-end', 0 ) . '>' . esc_html__( 'right', 'shariff' ) . '</p>';
}

/**
 * Alignment widget.
 */
function shariff3uu_radio_align_widget_render() {
	$options = $GLOBALS['shariff3uu_design'];
	if ( ! isset( $options['align_widget'] ) ) {
		$options['align_widget'] = 'flex-start';
	}
	echo '<p><input type="radio" name="shariff3uu_design[align_widget]" value="flex-start" ' . checked( $options['align_widget'], 'flex-start', 0 ) . '>' . esc_html__( 'left', 'shariff' ) . '</p>';
	echo '<p><input type="radio" name="shariff3uu_design[align_widget]" value="center" ' . checked( $options['align_widget'], 'center', 0 ) . '>' . esc_html__( 'center', 'shariff' ) . '</p>';
	echo '<p><input type="radio" name="shariff3uu_design[align_widget]" value="flex-end" ' . checked( $options['align_widget'], 'flex-end', 0 ) . '>' . esc_html__( 'right', 'shariff' ) . '</p>';
}

/**
 * Headline.
 */
function shariff3uu_text_headline_render() {
	if ( isset( $GLOBALS['shariff3uu_design']['headline'] ) ) {
		$headline = $GLOBALS['shariff3uu_design']['headline'];
	} else {
		$headline = '';
	}
	echo '<input type="text" name="shariff3uu_design[headline]" value="' . esc_html( $headline ) . '" size="50" placeholder="' . esc_html__( 'Share this post', 'shariff' ) . '">';
	echo '<p>';
	echo esc_html__( 'Basic HTML as well as style and class attributes are allowed. You can use %total to show the total amount of shares.', 'shariff' );
	echo '<br>';
	echo esc_html__( 'Example:', 'shariff' );
	echo ' <code>&lt;h3 class="shariff_headline"&gt;';
	echo esc_html__( 'Already shared %total times!', 'shariff' );
	echo '&lt;/h3&gt;</code></p>';
}

/**
 * Alternative headline.
 */
function shariff3uu_text_headline_zero_render() {
	if ( isset( $GLOBALS['shariff3uu_design']['headline_zero'] ) ) {
		$headline_zero = $GLOBALS['shariff3uu_design']['headline_zero'];
	} else {
		$headline_zero = '';
	}
	echo '<input type="text" name="shariff3uu_design[headline_zero]" value="' . esc_html( $headline_zero ) . '" size="50" placeholder="' . esc_html__( 'Be the first one to share this post!', 'shariff' ) . '">';
	echo '<p>';
	echo esc_html__( 'Same rules as for the default headline. Leave empty to keep the same headline in all cases.', 'shariff' );
	echo '</p>';
}

/**
 * Custom CSS.
 */
function shariff3uu_text_style_render() {
	if ( isset( $GLOBALS['shariff3uu_design']['style'] ) ) {
		$style = $GLOBALS['shariff3uu_design']['style'];
	} else {
		$style = '';
	}
	echo '<input type="text" name="shariff3uu_design[style]" value="' . esc_html( $style ) . '" size="50" placeholder="' . esc_attr__( 'More information in the FAQ.', 'shariff' ) . '">';
}

/**
 * Custom CSS class.
 */
function shariff3uu_text_cssclass_render() {
	if ( isset( $GLOBALS['shariff3uu_design']['cssclass'] ) ) {
		$cssclass = $GLOBALS['shariff3uu_design']['cssclass'];
	} else {
		$cssclass = '';
	}
	echo '<input type="text" name="shariff3uu_design[cssclass]" value="' . esc_html( $cssclass ) . '" size="50" placeholder="' . esc_attr__( 'More information in the FAQ.', 'shariff' ) . '">';
}

/**
 * Hide buttons until page is fully loaded.
 */
function shariff3uu_checkbox_hideuntilcss_render() {
	echo '<input type="checkbox" name="shariff3uu_design[hideuntilcss]" ';
	if ( isset( $GLOBALS['shariff3uu_design']['hideuntilcss'] ) ) {
		echo checked( $GLOBALS['shariff3uu_design']['hideuntilcss'], 1, 0 );
	}
	echo ' value="1">';
}

/**
 * Open links in a popup.
 */
function shariff3uu_checkbox_popup_render() {
	echo '<input type="checkbox" name="shariff3uu_design[popup]" ';
	if ( isset( $GLOBALS['shariff3uu_design']['popup'] ) ) {
		echo checked( $GLOBALS['shariff3uu_design']['popup'], 1, 0 );
	}
	echo ' value="1">';
}

/** Advanced options */

/**
 * Description of the advanced options.
 */
function shariff3uu_advanced_section_callback() {
	esc_html_e( 'This configures the advanced options of Shariff regarding specific services. ', 'shariff' );
	$help_link = get_bloginfo( 'wpurl' ) . '/wp-admin/options-general.php?page=shariff3uu&tab=help';
	// Translators: %s will be replaced with the correct URL to the help section.
	printf( wp_kses( __( 'For more information please take a look at the <a href="%s">Help Section</a>. ', 'shariff' ), array( 'a' => array( 'href' => true ) ) ), esc_url( $help_link ) );
	printf(
		wp_kses(
			// Translators: %s will be replaced with the correct URL to the wordpress.org support forum.
			__( 'You should also check out the <a href="%s" target="_blank">Support Forum</a>. ', 'shariff' ),
			array(
				'a' => array(
					'href'   => true,
					'target' => true,
				),
			)
		),
		'https://wordpress.org/support/plugin/shariff/'
	);
}

/**
 * Info URL.
 */
function shariff3uu_text_info_url_render() {
	if ( isset( $GLOBALS['shariff3uu_advanced']['info_url'] ) ) {
		$info_url = $GLOBALS['shariff3uu_advanced']['info_url'];
	} else {
		$info_url = '';
	}
	echo '<input type="text" name="shariff3uu_advanced[info_url]" value="' . esc_html( $info_url ) . '" size="50" placeholder="https://ct.de/-2467514">';
}

/**
 * Info Text.
 */
function shariff3uu_text_info_text_render() {
	if ( isset( $GLOBALS['shariff3uu_advanced']['info_text'] ) ) {
		$info_text = $GLOBALS['shariff3uu_advanced']['info_text'];
	} else {
		$info_text = '';
	}
	echo '<input type="text" name="shariff3uu_advanced[info_text]" value="' . esc_html( $info_text ) . '" size="50" placeholder="' . esc_html__( 'More information about these buttons.', 'shariff' ) . '">';
}

/**
 * Twitter via attribute.
 */
function shariff3uu_text_twittervia_render() {
	if ( isset( $GLOBALS['shariff3uu_advanced']['twitter_via'] ) ) {
		$twitter_via = $GLOBALS['shariff3uu_advanced']['twitter_via'];
	} else {
		$twitter_via = '';
	}
	echo '<input type="text" name="shariff3uu_advanced[twitter_via]" value="' . esc_html( $twitter_via ) . '" size="50" placeholder="' . esc_html__( 'username', 'shariff' ) . '">';
}

/**
 * Mastodon via attribute.
 */
function shariff3uu_text_mastodonvia_render() {
	if ( isset( $GLOBALS['shariff3uu_advanced']['mastodon_via'] ) ) {
		$mastodon_via = $GLOBALS['shariff3uu_advanced']['mastodon_via'];
	} else {
		$mastodon_via = '';
	}
	echo '<input type="text" name="shariff3uu_advanced[mastodon_via]" value="' . esc_html( $mastodon_via ) . '" size="50" placeholder="' . esc_html__( 'username', 'shariff' ) . '">';
}

/**
 * Patreon username.
 */
function shariff3uu_text_patreonid_render() {
	if ( isset( $GLOBALS['shariff3uu_advanced']['patreonid'] ) ) {
		$patreonid = $GLOBALS['shariff3uu_advanced']['patreonid'];
	} else {
		$patreonid = '';
	}
	echo '<input type="text" name="shariff3uu_advanced[patreonid]" value="' . esc_html( $patreonid ) . '" size="50" placeholder="' . esc_html__( 'username', 'shariff' ) . '">';
}

/**
 * PayPal Button ID.
 */
function shariff3uu_text_paypalbuttonid_render() {
	if ( isset( $GLOBALS['shariff3uu_advanced']['paypalbuttonid'] ) ) {
		$paypalbuttonid = $GLOBALS['shariff3uu_advanced']['paypalbuttonid'];
	} else {
		$paypalbuttonid = '';
	}
	echo '<input type="text" name="shariff3uu_advanced[paypalbuttonid]" value="' . esc_html( $paypalbuttonid ) . '" size="50" placeholder="1ABCDEF23GH4I">';
}

/**
 * PayPal.Me ID.
 */
function shariff3uu_text_paypalmeid_render() {
	if ( isset( $GLOBALS['shariff3uu_advanced']['paypalmeid'] ) ) {
		$paypalmeid = $GLOBALS['shariff3uu_advanced']['paypalmeid'];
	} else {
		$paypalmeid = '';
	}
	echo '<input type="text" name="shariff3uu_advanced[paypalmeid]" value="' . esc_html( $paypalmeid ) . '" size="50" placeholder="' . esc_html__( 'name', 'shariff' ) . '">';
}

/**
 * Bitcoin address.
 */
function shariff3uu_text_bitcoinaddress_render() {
	if ( isset( $GLOBALS['shariff3uu_advanced']['bitcoinaddress'] ) ) {
		$bitcoinaddress = $GLOBALS['shariff3uu_advanced']['bitcoinaddress'];
	} else {
		$bitcoinaddress = '';
	}
	echo '<input type="text" name="shariff3uu_advanced[bitcoinaddress]" value="' . esc_html( $bitcoinaddress ) . '" size="50" placeholder="1Ab2CdEfGhijKL34mnoPQRSTu5VwXYzaBcD">';
}

/**
 * RSS feed.
 */
function shariff3uu_text_rssfeed_render() {
	if ( isset( $GLOBALS['shariff3uu_advanced']['rssfeed'] ) ) {
		$rssfeed = $GLOBALS['shariff3uu_advanced']['rssfeed'];
	} else {
		$rssfeed = '';
	}
	$rssdefault = get_bloginfo( 'rss_url' );
	echo '<input type="text" name="shariff3uu_advanced[rssfeed]" value="' . esc_url( $rssfeed ) . '" size="50" placeholder="' . esc_url( $rssdefault ) . '">';
}

/**
 * Pinterest Default Image.
 */
function shariff3uu_text_default_pinterest_render() {
	$options = $GLOBALS['shariff3uu_advanced'];
	if ( ! isset( $options['default_pinterest'] ) ) {
		$options['default_pinterest'] = '';
	}
	echo '<div><input type="text" name="shariff3uu_advanced[default_pinterest]" value="' . esc_url( $options['default_pinterest'] ) . '" id="shariff-image-url" class="regular-text"><input type="button" name="upload-btn" id="shariff-upload-btn" class="button-secondary" value="' . esc_html__( 'Choose image', 'shariff' ) . '"></div>';
}

/**
 * Hide WhatsApp on Desktop.
 */
function shariff3uu_checkbox_hide_whatsapp_render() {
	echo '<input type="checkbox" name="shariff3uu_advanced[hide_whatsapp]" ';
	if ( isset( $GLOBALS['shariff3uu_advanced']['hide_whatsapp'] ) ) {
		echo checked( $GLOBALS['shariff3uu_advanced']['hide_whatsapp'], 1, 0 );
	}
	echo ' value="1">';
}

/**
 * Shortcode Priority.
 */
function shariff3uu_number_shortcodeprio_render() {
	if ( isset( $GLOBALS['shariff3uu_advanced']['shortcodeprio'] ) ) {
		$prio = $GLOBALS['shariff3uu_advanced']['shortcodeprio'];
	} else {
		$prio = '';
	}
	echo '<input type="number" name="shariff3uu_advanced[shortcodeprio]" value="' . esc_html( $prio ) . '" maxlength="2" min="0" max="20" placeholder="10" style="width: 75px">';
	echo '<p>' . esc_html__( 'Warning: DO NOT change this unless you know what you are doing or have been told so by the plugin author!', 'shariff' ) . '</p>';
}

/**
 * Disable metabox.
 */
function shariff3uu_checkbox_disable_metabox_render() {
	echo '<input type="checkbox" name="shariff3uu_advanced[disable_metabox]" ';
	if ( isset( $GLOBALS['shariff3uu_advanced']['disable_metabox'] ) ) {
		echo checked( $GLOBALS['shariff3uu_advanced']['disable_metabox'], 1, 0 );
	}
	echo ' value="1">';
}

/** Statistic section */

/**
 * Description statistic options.
 */
function shariff3uu_statistic_section_callback() {
	echo esc_html__( 'This determines how share counts are handled by Shariff.', 'shariff' );
	echo '<br>';
	if ( isset( $GLOBALS['shariff3uu_statistic']['external_direct'] ) ) {
		echo '<br><span style="color:red;font-weight:bold;">';
			echo esc_html__( 'Warning: ', 'shariff' );
		echo '</span>';
		echo esc_html__( 'You entered an external API and chose to call it directly! Therefore, all options and features (e.g. the ranking tab) regarding the statistic have no effect. You need to configure them on the external server. Remember: This feature is still experimental!', 'shariff' );
	}
	// Hook to add or remove cron job.
	do_action( 'shariff3uu_save_statistic_options' );
}

/**
 * Enable Statistic.
 */
function shariff3uu_checkbox_backend_render() {
	// Check WP version.
	if ( version_compare( get_bloginfo( 'version' ), '4.4.0' ) < 1 ) {
		echo esc_html__( 'WordPress-Version 4.4 or better is required to enable the statistic / share count functionality.', 'shariff' );
	} else {
		echo '<input type="checkbox" name="shariff3uu_statistic[backend]" ';
		if ( isset( $GLOBALS['shariff3uu_statistic']['backend'] ) ) {
			echo checked( $GLOBALS['shariff3uu_statistic']['backend'], 1, 0 );
		}
		echo ' value="1">';
	}
}

/**
 * Share Counts On Buttons.
 */
function shariff3uu_checkbox_sharecounts_render() {
	echo '<input type="checkbox" name="shariff3uu_statistic[sharecounts]" ';
	if ( isset( $GLOBALS['shariff3uu_statistic']['sharecounts'] ) ) {
		echo checked( $GLOBALS['shariff3uu_statistic']['sharecounts'], 1, 0 );
	}
	echo ' value="1">';
	if ( ! isset( $GLOBALS['shariff3uu_statistic']['backend'] ) && isset( $GLOBALS['shariff3uu_statistic']['sharecounts'] ) ) {
		echo ' ';
		echo esc_html__( 'Warning: The statistic functionality must be enabled in order for the share counts to be shown.', 'shariff' );
	}
}

/**
 * Hide when zero.
 */
function shariff3uu_checkbox_hidezero_render() {
	echo '<input type="checkbox" name="shariff3uu_statistic[hidezero]" ';
	if ( isset( $GLOBALS['shariff3uu_statistic']['hidezero'] ) ) {
		echo checked( $GLOBALS['shariff3uu_statistic']['hidezero'], 1, 0 );
	}
	echo ' value="1">';
}

/**
 * Ranking.
 */
function shariff3uu_number_ranking_render() {
	if ( isset( $GLOBALS['shariff3uu_statistic']['ranking'] ) ) {
		$numberposts = $GLOBALS['shariff3uu_statistic']['ranking'];
	} else {
		$numberposts = '';
	}
	echo '<input type="number" name="shariff3uu_statistic[ranking]" value="' . esc_html( $numberposts ) . '" maxlength="4" min="0" max="10000" placeholder="100" style="width: 75px">';
}

/**
 * Automatic cache.
 */
function shariff3uu_checkbox_automaticcache_render() {
	echo '<input type="checkbox" name="shariff3uu_statistic[automaticcache]" ';
	if ( isset( $GLOBALS['shariff3uu_statistic']['automaticcache'] ) ) {
		echo checked( $GLOBALS['shariff3uu_statistic']['automaticcache'], 1, 0 );
	}
	echo ' value="1">';
}

/**
 * Facebook App ID.
 */
function shariff3uu_text_fb_id_render() {
	if ( isset( $GLOBALS['shariff3uu_statistic']['fb_id'] ) ) {
		$fb_id = $GLOBALS['shariff3uu_statistic']['fb_id'];
	} else {
		$fb_id = '';
	}
	echo '<input type="text" name="shariff3uu_statistic[fb_id]" value="' . esc_html( $fb_id ) . '" size="50" placeholder="1234567891234567">';
}

/**
 * Facebook App Secret.
 */
function shariff3uu_text_fb_secret_render() {
	if ( isset( $GLOBALS['shariff3uu_statistic']['fb_secret'] ) ) {
		$fb_secret = $GLOBALS['shariff3uu_statistic']['fb_secret'];
	} else {
		$fb_secret = '';
	}
	echo '<input type="text" name="shariff3uu_statistic[fb_secret]" value="' . esc_html( $fb_secret ) . '" size="50" placeholder="123abc456def789123456789ghi12345">';
}

/**
 * TTL.
 */
function shariff3uu_number_ttl_render() {
	if ( isset( $GLOBALS['shariff3uu_statistic']['ttl'] ) ) {
		$ttl = $GLOBALS['shariff3uu_statistic']['ttl'];
	} else {
		$ttl = '';
	}
	echo '<input type="number" name="shariff3uu_statistic[ttl]" value="' . esc_html( $ttl ) . '" maxlength="4" min="60" max="7200" placeholder="60" style="width: 75px">';
}

/**
 * Disable dynamic cache lifespan.
 */
function shariff3uu_checkbox_disable_dynamic_cache_render() {
	echo '<input type="checkbox" name="shariff3uu_statistic[disable_dynamic_cache]" ';
	if ( isset( $GLOBALS['shariff3uu_statistic']['disable_dynamic_cache'] ) ) {
		echo checked( $GLOBALS['shariff3uu_statistic']['disable_dynamic_cache'], 1, 0 );
	}
	echo ' value="1">';
}

/**
 * Disable services.
 */
function shariff3uu_multiplecheckbox_disable_services_render() {
	// Loop through all services with a backend.
	foreach ( $GLOBALS['shariff3uu_services_backend'] as $service ) {
		// Capitalize service name.
		$service_name = ucfirst( $service );
		// Add option.
		echo '<p><input type="checkbox" name="shariff3uu_statistic[disable][' . esc_html( $service ) . ']" ';
		if ( isset( $GLOBALS['shariff3uu_statistic']['disable'][ $service ] ) ) {
			echo checked( $GLOBALS['shariff3uu_statistic']['disable'][ $service ], 1, 0 );
		}
		echo ' value="1">' . esc_html( $service_name ) . '</p>';
	}
}

/**
 * External host.
 */
function shariff3uu_text_external_host_render() {
	if ( isset( $GLOBALS['shariff3uu_statistic']['external_host'] ) ) {
		$external_host = $GLOBALS['shariff3uu_statistic']['external_host'];
	} else {
		$external_host = '';
	}
	echo '<input type="text" name="shariff3uu_statistic[external_host]" value="' . esc_html( $external_host ) . '" size="50" placeholder="' . esc_url( get_bloginfo( 'url' ) ) . '/wp-json/shariff/v1/share_counts">';
	echo '<p>' . wp_kses(
		__( 'Warning: This is an experimental feature. Please read the <a href="https://wordpress.org/plugins/shariff/faq/" target="_blank">Frequently Asked Questions (FAQ)</a>.', 'shariff' ),
		array(
			'a' => array(
				'href'   => true,
				'target' => true,
			),
		)
	) . '</p>';
	echo '<p>' . esc_html__( 'Please check, if you have to add this domain to the array $SHARIFF_FRONTENDS on the external server.', 'shariff' ) . '</p>';
}

/**
 * Direct external api call from JS.
 */
function shariff3uu_checkbox_external_direct_render() {
	echo '<input type="checkbox" name="shariff3uu_statistic[external_direct]" ';
	if ( isset( $GLOBALS['shariff3uu_statistic']['external_direct'] ) ) {
		echo checked( $GLOBALS['shariff3uu_statistic']['external_direct'], 1, 0 );
	}
	echo ' value="1">';
	echo '<p>' . esc_html__( 'Please check, if you have correctly set the Access-Control-Allow-Origin header!', 'shariff' ) . '</p>';
}

/**
 * Local API only reachable in subfolder.
 */
function shariff3uu_checkbox_subapi_render() {
	echo '<input type="checkbox" name="shariff3uu_statistic[subapi]" ';
	if ( isset( $GLOBALS['shariff3uu_statistic']['subapi'] ) ) {
		echo checked( $GLOBALS['shariff3uu_statistic']['subapi'], 1, 0 );
	}
	echo ' value="1">';
}

/**
 * Help section.
 */
function shariff3uu_help_section_callback() {
	echo '<p>';
		printf(
			wp_kses(
				__( 'The WordPress plugin "Shariff Wrapper" has been developed by Jan-Peter Lambeck and <a href="%2$s" target="_blank">3UU</a> in order to help protect the privacy of your visitors. ', 'shariff' ),
				array(
					'a' => array(
						'href'   => true,
						'target' => true,
					),
				)
			),
			'https://www.jplambeck.de',
			'https://datenverwurstungszentrale.com'
		);
		echo ' ';
		printf(
			wp_kses(
				// Translators: %s will be replaced with the correct URL to the German computer magazine.
				__( 'It is based on the original Shariff buttons developed by the German computer magazine <a href="%s" target="_blank">c\'t</a> that are compliant to the General Data Protection Regulation (GDPR) (Regulation (EU) 2016/679).', 'shariff' ),
				array(
					'a' => array(
						'href'   => true,
						'target' => true,
					),
				)
			),
			'https://ct.de/-2467514'
		);
		echo ' ';
		printf(
			wp_kses(
				// Translators: %1$s and %2$s will be replaced with the correct URLs to FAQ and the forum on wordpress.org.
				__( 'If you need any help with the plugin, take a look at the <a href="%1$s" target="_blank">Frequently Asked Questions (FAQ)</a> and the <a href="%2$s" target="_blank">Support Forum</a> on wordpress.org. ', 'shariff' ),
				array(
					'a' => array(
						'href'   => true,
						'target' => true,
					),
				)
			),
			'https://wordpress.org/plugins/shariff/#faq',
			'https://wordpress.org/support/plugin/shariff'
		);
	echo '</p>';
	echo '<p>';
		// Translators: %s will be replaced with the correct URL to the wordpress.org review page.
		printf(
			wp_kses(
				// Translators: %s will be replaced with the correct URL to the wordpress.org review page.
				__( 'If you enjoy our plugin, please consider writing a review about it on <a href="%s" target="_blank">wordpress.org</a>.', 'shariff' ),
				array(
					'a' => array(
						'href'   => true,
						'target' => true,
					),
				)
			),
			'https://wordpress.org/support/view/plugin-reviews/shariff'
		);
	echo '</p>';
	echo '<p>';
		echo wp_kses( __( 'This is a list of all available options for the <code>[shariff]</code> shortcode:', 'shariff' ), array( 'code' => array() ) );
	echo '</p>';
	// Shortcode table.
	echo '<div style="display:table;background-color:#fff">';
		// Head.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px;font-weight:bold">' . esc_html__( 'Name', 'shariff' ) . '</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px;font-weight:bold">' . esc_html__( 'Options', 'shariff' ) . '</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px;font-weight:bold">' . esc_html__( 'Default', 'shariff' ) . '</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px;font-weight:bold">' . esc_html__( 'Example', 'shariff' ) . '</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px;font-weight:bold">' . esc_html__( 'Description', 'shariff' ) . '</div>';
		echo '</div>';
		// Services.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">services</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">addthis<br>bitcoin<br>buffer<br>diaspora<br>facebook<br>flipboard<br>info<br>linkedin<br>mailto<br>mastodon<br>mewe<br>mix<br>odnoklassniki<br>patreon<br>paypal<br>paypalme<br>pinterest<br>pocket<br>printer<br>reddit<br>rss<br>sms<br>telegram<br>threema<br>tumblr<br>twitter<br>vk<br>wallabag<br>weibo<br>whatsapp<br>xing</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">mastodon|facebook|linkedin|info</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff services="facebook|mastodon|mailto"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Determines which buttons to show and in which order.', 'shariff' ) . '</div>';
		echo '</div>';
		// Backend.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">backend</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">on<br>off</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">off</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff backend="on"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Enables share counts on the buttons.', 'shariff' ) . '</div>';
		echo '</div>';
		// Theme.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">theme</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">default<br>color<br>grey<br>white<br>round<br>wcag</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">default</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff theme="round"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Determines the main design of the buttons.', 'shariff' ) . '</div>';
		echo '</div>';
		// Buttonsize.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">buttonsize</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">small<br>medium<br>large</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">medium</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff buttonsize="small"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Determines the button size regardless of theme choice.', 'shariff' ) . '</div>';
		echo '</div>';
		// Buttonstretch.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">buttonstretch</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">0<br>1</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">0</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff buttonstretch="1"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Stretch buttons horizontally to full width.', 'shariff' ) . '</div>';
		echo '</div>';
		// Borderradius.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">borderradius</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">1-50</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">50</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff borderradius="1"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Sets the border radius for the round theme. 1 essentially equals a square.', 'shariff' ) . '</div>';
		echo '</div>';
		// Maincolor.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">maincolor</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff maincolor="#000"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Sets a custom main color for all buttons (hexadecimal).', 'shariff' ) . '</div>';
		echo '</div>';
		// Secondarycolor.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">secondarycolor</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff secondarycolor="#afafaf"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Sets a custom secondary color for all buttons (hexadecimal). The secondary color is, depending on theme, used for hover effects.', 'shariff' ) . '</div>';
		echo '</div>';
		// Orientation.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">orientation</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">horizontal<br>vertical</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">horizontal</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff orientation="vertical"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Changes the orientation of the buttons.', 'shariff' ) . '</div>';
		echo '</div>';
		// Alignment.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">align</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">flex-start<br>center<br>flex-end</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">flex-start</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff align="center"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Changes the horizontal alignment of the buttons. flex-start means left, center is obvious and flex-end means right.', 'shariff' ) . '</div>';
		echo '</div>';
		// Language.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">language</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">bg, cs, da, de, en, es, fi, fr, hr, hu, it, ja, ko, nl, no, pl, pt, ro, ru, sk, sl, sr, sv, tr, zh</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Automatically selected by browser.', 'shariff' ) . '</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff lang="de"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Changes the language of the share buttons.', 'shariff' ) . '</div>';
		echo '</div>';
		// Headline.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">headline</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff headline="&lt;hr style=\'margin:20px 0\'&gt;&lt;p&gt;' . esc_html__( 'Please share this post', 'shariff' ) . '&lt;/p&gt;"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Adds a headline above the Shariff buttons. Basic HTML as well as style and class attributes can be used. To remove a headline set on the plugins options page use headline="".', 'shariff' ) . '</div>';
		echo '</div>';
		// Style.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">style</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff style="margin:20px;"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Adds custom style attributes to the container around Shariff.', 'shariff' ) . '</div>';
		echo '</div>';
		// CSS class.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">cssclass</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff class="classname"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Adds a custom class to the container around Shariff.', 'shariff' ) . '</div>';
		echo '</div>';
		// Twitter_via.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">twitter_via</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff twitter_via="your_twittername"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Sets the Twitter via tag.', 'shariff' ) . '</div>';
		echo '</div>';
		// Mastodon_via.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">mastodon_via</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff mastodon_via="your_mastodonname"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Sets the Mastodon via tag.', 'shariff' ) . '</div>';
		echo '</div>';
		// Patreonid.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">patreonid</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff patreonid="your_username"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Sets the Patreon username.', 'shariff' ) . '</div>';
		echo '</div>';
		// Paypalbuttonid.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">paypalbuttonid</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff paypalbuttonid="hosted_button_id"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Sets the PayPal hosted button ID.', 'shariff' ) . '</div>';
		echo '</div>';
		// Paypalmeid.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">paypalmeid</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff paypalmeid="name"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Sets the PayPal.Me ID. Default amount can be added with a / e.g. name/25.', 'shariff' ) . '</div>';
		echo '</div>';
		// Bitcoinaddress.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">bitcoinaddress</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff bitcoinaddress="bitcoin_address"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Sets the bitcoin address.', 'shariff' ) . '</div>';
		echo '</div>';
		// Media.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">media</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'The post featured image or the first image of the post.', 'shariff' ) . '</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff media="http://www.mydomain.com/image.jpg"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Determines the default image to share for Pinterest, if no other usable image is found.', 'shariff' ) . '</div>';
		echo '</div>';
		// Info_URL.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">info_url</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">http://ct.de/-2467514</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff info_url="http://www.mydomain.com/shariff-buttons"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Sets a custom link for the info button.', 'shariff' ) . '</div>';
		echo '</div>';
		// Info_Text.
		echo '<div style="display:table-row">';
		echo '<div style="display:table-cell;border:1px solid;padding:10px">info_text</div>';
		echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
		echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'More information about these buttons.', 'shariff' ) . '</div>';
		echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff info_text="' . esc_html__( 'My custom text.', 'shariff' ) . '"]</div>';
		echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Sets a custom text for the info button.', 'shariff' ) . '</div>';
		echo '</div>';
		// URL.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">url</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'The url of the current post or page.', 'shariff' ) . '</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff url="http://www.mydomain.com/somepost"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Changes the url to share. Only for special use cases.', 'shariff' ) . '</div>';
		echo '</div>';
		// Title.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">title</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'The title of the current post or page.', 'shariff' ) . '</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff title="' . esc_html__( 'My Post Title', 'shariff' ) . '"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Changes the title to share. Only for special use cases.', 'shariff' ) . '</div>';
		echo '</div>';
		// Timestamp.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">timestamp</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'The timestamp of the last modification of the current post or page.', 'shariff' ) . '</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff timestamp="1473240010"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Provides the time the current post or page was last modified as a timestamp. Used for determining the dynamic cache lifespan. Only for special use cases.', 'shariff' ) . '</div>';
		echo '</div>';
		// RSS feed.
		echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">rssfeed</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">http://www.mydomain.com/feed/rss/</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">[shariff rssfeed="http://www.mydomain.com/feed/rss2/"]</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html__( 'Changes the RSS feed url to another feed.', 'shariff' ) . '</div>';
		echo '</div>';

	echo '</div>';
}

/**
 * Status section.
 */
function shariff3uu_status_section_callback() {
	// Get options.
	$shariff3uu = $GLOBALS['shariff3uu'];

	// Begin status table.
	echo '<div style="display:table;border-spacing:10px;margin:-10px 0 0 -10px">';

	// Begin statistic row.
	echo '<div style="display:table-row">';
	echo '<div style="display:table-cell;width:125px">' . esc_html__( 'Statistic:', 'shariff' ) . '</div>';

	// Check if statistic is enabled.
	if ( ! isset( $shariff3uu['backend'] ) ) {
		// Statistic disabled message.
		echo '<div style="display:table">';
		echo '<div style="display:table-row"><div style="display:table-cell;font-weight:bold">' . esc_html__( 'Disabled', 'shariff' ) . '</div></div>';
		echo '</div>';
		// End statistic row, if statistic is disabled.
		echo '</div>';
	} else {
		// Encode shareurl.
		$post_url     = rawurlencode( esc_url( get_bloginfo( 'url' ) ) );
		$post_url_raw = esc_url( get_bloginfo( 'url' ) );

		// We only need the backend part.
		$backend = 1;

		// But we also want error messages.
		$record_errors = 1;

		// Avoid debug messages.
		$service_errors = array();

		// Loop through all desired services.
		foreach ( $GLOBALS['shariff3uu_services_backend'] as $service ) {
			// Include service parameters.
			if ( ! isset( $shariff3uu['disable'][ $service ] ) || ( isset( $shariff3uu['disable'][ $service ] ) && 0 === $shariff3uu['disable'][ $service ] ) ) {
				include plugin_dir_path( __FILE__ ) . '../services/shariff-' . $service . '.php';
			}
		}

		// General statistic status.
		echo '<div style="display:table-cell">';
			echo '<div style="display:table">';
		if ( empty( $service_errors ) ) {
			echo '<div style="display:table-row"><div style="display:table-cell;font-weight:bold;color:green">' . esc_html__( 'OK', 'shariff' ) . '</div></div>';
			echo '<div style="display:table-row"><div style="display:table-cell">' . esc_html__( 'No error messages.', 'shariff' ) . '</div></div>';
		} elseif ( array_filter( $service_errors ) ) {
			echo '<div style="display:table-row"><div style="display:table-cell;font-weight:bold;color:red">' . esc_html__( 'Error', 'shariff' ) . '</div></div>';
			echo '<div style="display:table-row"><div style="display:table-cell">' . esc_html__( 'One or more services reported an error.', 'shariff' ) . '</div></div>';
		} else {
			echo '<div style="display:table-row"><div style="display:table-cell;font-weight:bold;color:orange">' . esc_html__( 'Timeout', 'shariff' ) . '</div></div>';
			echo '<div style="display:table-row"><div style="display:table-cell">' . esc_html__( 'One or more services didn\'t respond in less than five seconds.', 'shariff' ) . '</div></div>';
		}
			echo '<div style="display:table-row"><div style="display:table-cell"></div></div>';
			echo '</div>';
		echo '</div>';

		// End statistic row.
		echo '</div>';

		// Output all services.
		foreach ( $GLOBALS['shariff3uu_services_backend'] as $service ) {
			// Begin service row.
			echo '<div style="display:table-row">';
				echo '<div class="shariff_status-first-cell">' . esc_html( ucfirst( $service ) ) . ':</div>';
				echo '<div style="display:table-cell">';
					echo '<div class="shariff_status-table">';
			if ( isset( $shariff3uu['disable'][ $service ] ) && 1 === $shariff3uu['disable'][ $service ] ) {
				echo '<div style="display:table-row"><div style="display:table-cell;font-weight:bold">' . esc_html__( 'Disabled', 'shariff' ) . '</div></div>';
			} elseif ( 'facebook' === $service && ( ! isset( $shariff3uu['fb_id'] ) || ! isset( $shariff3uu['fb_secret'] ) ) ) {
				echo '<div style="display:table-row"><div style="display:table-cell;font-weight:bold;color:#ff0000">' . esc_html__( 'Facebook shut down the possibility to request share counts without an APP ID and Secret. Therefore, you need to create a Facebook APP ID and Secret and enter it in the settings on the Statistic tab. Google will provide you with many tutorials. Simple search for “facebook app id secret” and you will find one in your language.', 'shariff' ) . '</div></div>';
			} elseif ( ! array_key_exists( $service, $service_errors ) ) {
				echo '<div style="display:table-row"><div style="display:table-cell;font-weight:bold;color:green">' . esc_html__( 'OK', 'shariff' ) . '</div></div>';
				echo '<div style="display:table-row"><div style="display:table-cell">' . esc_html__( 'Share Count:', 'shariff' ) . ' ' . absint( $share_counts[ $service ] ) . '</div></div>';
			} elseif ( empty( $service_errors[ $service ] ) ) {
				echo '<div style="display:table-row"><div style="display:table-cell;font-weight:bold;color:orange">' . esc_html__( 'Timeout', 'shariff' ) . '</div></div>';
				echo '<div style="display:table-row"><div style="display:table-cell">';
				echo esc_html__( 'Service didn\'t respond in less than five seconds.', 'shariff' );
				echo '</div></div>';
			} else {
				echo '<div style="display:table-row"><div style="display:table-cell;font-weight:bold;color:#ff0000">' . esc_html__( 'Error', 'shariff' ) . '</span></div></div>';
				echo '<div style="display:table-row"><div style="display:table-cell">';
				echo esc_html( $service_errors[ $service ] );
				echo '</div></div>';
			}
					echo '<div style="display:table-row"><div style="display:table-cell"></div></div>';
					echo '</div>';
				echo '</div>';
			echo '</div>';
		}
	}// End if.

	// GD needed for QR codes of the Bitcoin links.
	echo '<div style="display:table-row">';
	echo '<div style="display:table-cell">' . esc_html__( 'GD Library:', 'shariff' ) . '</div>';
	// Working message.
	if ( function_exists( 'gd_info' ) ) {
		$gd_info = gd_info();
		echo '<div style="display:table-cell">';
			echo '<div style="display: table">';
				echo '<div style="display:table-row"><div style="display:table-cell;font-weight:bold;color:green">' . esc_html__( 'OK', 'shariff' ) . '</div></div>';
				echo '<div style="display:table-row"><div style="display:table-cell">Version: ' . esc_html( $gd_info['GD Version'] ) . '</div></div>';
			echo '</div>';
		echo '</div>';
	} else {
		echo '<div style="display:table-cell">';
			echo '<div style="display: table">';
				echo '<div style="display:table-row"><div style="display:table-cell;font-weight:bold;color:red">' . esc_html__( 'Error', 'shariff' ) . '</div></div>';
				echo '<div style="display:table-row"><div style="display:table-cell">' . esc_html__( 'The GD Library is not installed on this server. This is only needed for the QR codes, if your are using the bitcoin button.', 'shariff' ) . '</div></div>';
			echo '</div>';
		echo '</div>';
	}
	echo '</div>';

	// End status table.
	echo '</div>';
}

/**
 * Ranking section.
 */
function shariff3uu_ranking_section_callback() {
	// Post and service array.
	$posts    = array();
	$services = array();

	// Amount of posts - set to 100 if not set.
	if ( isset( $GLOBALS['shariff3uu']['ranking'] ) && absint( $GLOBALS['shariff3uu']['ranking'] ) > '0' ) {
		$numberposts = absint( $GLOBALS['shariff3uu']['ranking'] );
	} else {
		$numberposts = '100';
	}

	// Set arguments for wp_get_recent_posts().
	$args = array(
		'numberposts' => $numberposts,
		'orderby'     => 'post_date',
		'order'       => 'DESC',
		'post_status' => 'publish',
	);

	// Catch last 100 posts or whatever number is set for it.
	$recent_posts = wp_get_recent_posts( $args );
	if ( $recent_posts ) {
		foreach ( $recent_posts as $recent ) {
			// Get URL.
			$url      = get_permalink( $recent['ID'] );
			$post_url = rawurlencode( $url );
			// Set transient name.
			$post_hash = 'shariff' . hash( 'md5', $post_url );
			// Get share counts from cache.
			if ( get_transient( $post_hash ) !== false ) {
				$share_counts = get_transient( $post_hash );
				$services     = array_merge( $services, $share_counts );
				if ( isset( $share_counts['total'] ) ) {
					$total = $share_counts['total'];
				} else {
					$total = '0';
				}
			} else {
				$share_counts = array();
				$total        = '';
			}
			// Add to array.
			$posts[ $post_hash ] = array(
				'url'                => $url,
				'title'              => $recent['post_title'],
				'post_date'          => $recent['post_date'],
				'share_counts'       => $share_counts,
				'total_share_counts' => $total,
			);
		}
	}

	// Clean up services.
	unset( $services['total'] );
	unset( $services['timestamp'] );
	unset( $services['url'] );
	ksort( $services );

	// Sort array: first descending using total share counts then descending using post date.
	$tmp  = array();
	$tmp2 = array();
	foreach ( $posts as &$ma ) {
		$tmp[] = &$ma['total_share_counts'];
	}
	foreach ( $posts as &$ma2 ) {
		$tmp2[] = &$ma2['post_date'];
	}
	array_multisort( $tmp, SORT_DESC, $tmp2, SORT_DESC, $posts );

	// Intro text.
	echo '<p>';
	echo esc_html__( 'The following tables shows the ranking of your last 100 posts, pages and, if applicable, products in descending order by total share counts. To prevent slow loading times only cached data is being used. Therefore, you may see blank entries for posts that have not been visited by anyone since the last update or activation of Shariff Wrapper. You can simply visit the respective post yourself in order to have the share counts fetched.', 'shariff' );
	echo '</p>';

	// Warning if statistic has been disabled.
	if ( ! isset( $GLOBALS['shariff3uu']['backend'] ) ) {
		echo '<p>';
		echo '<span style="color: red; font-weight: bold;">';
		echo esc_html__( 'Warning:', 'shariff' );
		echo '</span> ';
		echo esc_html__( 'The statistic option has been disabled on the statistic tab. Share counts will not get updated!', 'shariff' );
		echo '</p>';
	}

	// Begin ranking table.
	echo '<div style="display:table;background-color:#fff">';
	// Head.
	echo '<div style="display:table-row">';
	echo '<div style="display:table-cell;font-weight:bold;border:1px solid;padding:10px">' . esc_html__( 'Rank', 'shariff' ) . '</div>';
	echo '<div style="display:table-cell;font-weight:bold;border:1px solid;padding:10px">' . esc_html__( 'Post', 'shariff' ) . '</div>';
	echo '<div style="display:table-cell;font-weight:bold;border:1px solid;padding:10px;text-align:center">' . esc_html__( 'Date', 'shariff' ) . '</div>';
	echo '<div style="display:table-cell;font-weight:bold;border:1px solid;padding:10px;text-align:center">' . esc_html__( 'Time', 'shariff' ) . '</div>';
	foreach ( $services as $service => $nothing ) {
		echo '<div style="display:table-cell;font-weight:bold;border:1px solid;padding:10px;text-align:center;">' . esc_html( ucfirst( $service ) ) . '</div>';
	}
	echo '<div style="display:table-cell;border:1px solid;padding:10px;font-weight:bold">' . esc_html__( 'Total', 'shariff' ) . '</div>';
	echo '</div>';
	// Posts.
	$rank = '0';
	foreach ( $posts as $post => $value ) {
		$rank ++;
		echo '<div style="display:table-row">';
		echo '<div style="display:table-cell;border:1px solid;padding:10px;text-align:center">' . absint( $rank ) . '</div>';
		echo '<div style="display:table-cell;border:1px solid;padding:10px"><a href="' . esc_url( $value['url'] ) . '" target="_blank">' . esc_html( wp_strip_all_tags( $value['title'] ) ) . '</a></div>';
		echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html( mysql2date( 'd.m.Y', $value['post_date'] ) ) . '</div>';
		echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html( mysql2date( 'H:i', $value['post_date'] ) ) . '</div>';
		// Share counts.
		foreach ( $services as $service => $nothing ) {
			echo '<div style="display:table-cell;border:1px solid;padding:10px;text-align:center">';
			if ( isset( $value['share_counts'][ $service ] ) ) {
				echo absint( $value['share_counts'][ $service ] );
			}
			echo '</div>';
		}
		echo '<div style="display:table-cell;border:1px solid;padding:10px;text-align:center">';
		if ( isset( $value['share_counts']['total'] ) ) {
			echo absint( $value['share_counts']['total'] );
		}
		echo '</div>';
		echo '</div>';
	}
	echo '</div>';

	// Clear  arrays.
	$posts    = array();
	$services = array();

	// Set arguments for wp_get_recent_posts().
	$args = array(
		'numberposts' => $numberposts,
		'orderby'     => 'post_date',
		'order'       => 'DESC',
		'post_status' => 'publish',
		'post_type'   => 'page',
	);

	// Catch last 100 pages or whatever number is set for it.
	$recent_posts = wp_get_recent_posts( $args );
	if ( $recent_posts ) {
		foreach ( $recent_posts as $recent ) {
			// Get URL.
			$url      = get_permalink( $recent['ID'] );
			$post_url = rawurlencode( $url );
			// Set transient name.
			$post_hash = 'shariff' . hash( 'md5', $post_url );
			// Get share counts from cache.
			if ( get_transient( $post_hash ) !== false ) {
				$share_counts = get_transient( $post_hash );
				$services     = array_merge( $services, $share_counts );
				if ( isset( $share_counts['total'] ) ) {
					$total = $share_counts['total'];
				} else {
					$total = '0';
				}
			} else {
				$share_counts = array();
				$total        = '';
			}
			// Add to array.
			$posts[ $post_hash ] = array(
				'url'                => $url,
				'title'              => $recent['post_title'],
				'post_date'          => $recent['post_date'],
				'share_counts'       => $share_counts,
				'total_share_counts' => $total,
			);
		}
	}

	// Clean up services.
	unset( $services['total'] );
	unset( $services['timestamp'] );
	unset( $services['url'] );
	ksort( $services );

	// Sort array: first descending using total share counts then descending using post date.
	$tmp  = array();
	$tmp2 = array();
	foreach ( $posts as &$ma ) {
		$tmp[] = &$ma['total_share_counts'];
	}
	foreach ( $posts as &$ma2 ) {
		$tmp2[] = &$ma2['post_date'];
	}
	array_multisort( $tmp, SORT_DESC, $tmp2, SORT_DESC, $posts );

	echo '<p></p>';

	// Warning if statistic has been disabled.
	if ( ! isset( $GLOBALS['shariff3uu']['backend'] ) ) {
		echo '<p>';
		echo '<span style="color: red; font-weight: bold;">';
		echo esc_html__( 'Warning:', 'shariff' );
		echo '</span> ';
		echo esc_html__( 'The statistic option has been disabled on the statistic tab. Share counts will not get updated!', 'shariff' );
		echo '</p>';
	}

	// Begin ranking table.
	echo '<div style="display:table;background-color:#fff">';
	// Head.
	echo '<div style="display:table-row">';
	echo '<div style="display:table-cell;font-weight:bold;border:1px solid;padding:10px">' . esc_html__( 'Rank', 'shariff' ) . '</div>';
	echo '<div style="display:table-cell;font-weight:bold;border:1px solid;padding:10px">' . esc_html__( 'Page', 'shariff' ) . '</div>';
	echo '<div style="display:table-cell;font-weight:bold;border:1px solid;padding:10px;text-align:center">' . esc_html__( 'Date', 'shariff' ) . '</div>';
	echo '<div style="display:table-cell;font-weight:bold;border:1px solid;padding:10px;text-align:center">' . esc_html__( 'Time', 'shariff' ) . '</div>';
	foreach ( $services as $service => $nothing ) {
		echo '<div style="display:table-cell;font-weight:bold;border:1px solid;padding:10px;text-align:center;">' . esc_html( ucfirst( $service ) ) . '</div>';
	}
	echo '<div style="display:table-cell;border:1px solid;padding:10px;font-weight:bold">' . esc_html__( 'Total', 'shariff' ) . '</div>';
	echo '</div>';
	// Pages.
	$rank = '0';
	foreach ( $posts as $post => $value ) {
		$rank ++;
		echo '<div style="display:table-row">';
		echo '<div style="display:table-cell;border:1px solid;padding:10px;text-align:center">' . absint( $rank ) . '</div>';
		echo '<div style="display:table-cell;border:1px solid;padding:10px"><a href="' . esc_url( $value['url'] ) . '" target="_blank">' . esc_html( $value['title'] ) . '</a></div>';
		echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html( mysql2date( 'd.m.Y', $value['post_date'] ) ) . '</div>';
		echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html( mysql2date( 'H:i', $value['post_date'] ) ) . '</div>';
		// Share counts.
		foreach ( $services as $service => $nothing ) {
			echo '<div style="display:table-cell;border:1px solid;padding:10px;text-align:center">';
			if ( isset( $value['share_counts'][ $service ] ) ) {
				echo absint( $value['share_counts'][ $service ] );
			}
			echo '</div>';
		}
		echo '<div style="display:table-cell;border:1px solid;padding:10px;text-align:center">';
		if ( isset( $value['share_counts']['total'] ) ) {
			echo absint( $value['share_counts']['total'] );
		}
		echo '</div>';
		echo '</div>';
	}
	echo '</div>';

	// Clear  arrays.
	$posts    = array();
	$services = array();

	// Set arguments for wp_get_recent_posts().
	$args = array(
		'numberposts' => $numberposts,
		'orderby'     => 'post_date',
		'order'       => 'DESC',
		'post_status' => 'publish',
		'post_type'   => 'product',
	);

	// Catch last 100 pages or whatever number is set for it.
	$recent_posts = wp_get_recent_posts( $args );
	if ( $recent_posts ) {
		foreach ( $recent_posts as $recent ) {
			// Get URL.
			$url      = get_permalink( $recent['ID'] );
			$post_url = rawurlencode( $url );
			// Set transient name.
			$post_hash = 'shariff' . hash( 'md5', $post_url );
			// Get share counts from cache.
			if ( get_transient( $post_hash ) !== false ) {
				$share_counts = get_transient( $post_hash );
				$services     = array_merge( $services, $share_counts );
				if ( isset( $share_counts['total'] ) ) {
					$total = $share_counts['total'];
				} else {
					$total = '0';
				}
			} else {
				$share_counts = array();
				$total        = '';
			}
			// Add to array.
			$posts[ $post_hash ] = array(
				'url'                => $url,
				'title'              => $recent['post_title'],
				'post_date'          => $recent['post_date'],
				'share_counts'       => $share_counts,
				'total_share_counts' => $total,
			);
		}
	}

	// Clean up services.
	unset( $services['total'] );
	unset( $services['timestamp'] );
	unset( $services['url'] );
	ksort( $services );

	// Sort array: first descending using total share counts then descending using post date.
	$tmp  = array();
	$tmp2 = array();
	foreach ( $posts as &$ma ) {
		$tmp[] = &$ma['total_share_counts'];
	}
	foreach ( $posts as &$ma2 ) {
		$tmp2[] = &$ma2['post_date'];
	}
	array_multisort( $tmp, SORT_DESC, $tmp2, SORT_DESC, $posts );

	echo '<p></p>';

	// Warning if statistic has been disabled.
	if ( ! isset( $GLOBALS['shariff3uu']['backend'] ) ) {
		echo '<p>';
		echo '<span style="color: red; font-weight: bold;">';
		echo esc_html__( 'Warning:', 'shariff' );
		echo '</span> ';
		echo esc_html__( 'The statistic option has been disabled on the statistic tab. Share counts will not get updated!', 'shariff' );
		echo '</p>';
	}

	if ( ! empty( $services ) ) {
		// Begin ranking table.
		echo '<div style="display:table;background-color:#fff">';
		// Head.
		echo '<div style="display:table-row">';
		echo '<div style="display:table-cell;font-weight:bold;border:1px solid;padding:10px">' . esc_html__( 'Rank', 'shariff' ) . '</div>';
		echo '<div style="display:table-cell;font-weight:bold;border:1px solid;padding:10px">' . esc_html__( 'Product', 'shariff' ) . '</div>';
		echo '<div style="display:table-cell;font-weight:bold;border:1px solid;padding:10px;text-align:center">' . esc_html__( 'Date', 'shariff' ) . '</div>';
		echo '<div style="display:table-cell;font-weight:bold;border:1px solid;padding:10px;text-align:center">' . esc_html__( 'Time', 'shariff' ) . '</div>';
		foreach ( $services as $service => $nothing ) {
			echo '<div style="display:table-cell;font-weight:bold;border:1px solid;padding:10px;text-align:center;">' . esc_html( ucfirst( $service ) ) . '</div>';
		}
		echo '<div style="display:table-cell;border:1px solid;padding:10px;font-weight:bold">' . esc_html__( 'Total', 'shariff' ) . '</div>';
		echo '</div>';
		// Pages.
		$rank = '0';
		foreach ( $posts as $post => $value ) {
			$rank ++;
			echo '<div style="display:table-row">';
			echo '<div style="display:table-cell;border:1px solid;padding:10px;text-align:center">' . absint( $rank ) . '</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px"><a href="' . esc_url( $value['url'] ) . '" target="_blank">' . esc_html( $value['title'] ) . '</a></div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html( mysql2date( 'd.m.Y', $value['post_date'] ) ) . '</div>';
			echo '<div style="display:table-cell;border:1px solid;padding:10px">' . esc_html( mysql2date( 'H:i', $value['post_date'] ) ) . '</div>';
			// Share counts.
			foreach ( $services as $service => $nothing ) {
				echo '<div style="display:table-cell;border:1px solid;padding:10px;text-align:center">';
				if ( isset( $value['share_counts'][ $service ] ) ) {
					echo absint( $value['share_counts'][ $service ] );
				}
				echo '</div>';
			}
			echo '<div style="display:table-cell;border:1px solid;padding:10px;text-align:center">';
			if ( isset( $value['share_counts']['total'] ) ) {
				echo absint( $value['share_counts']['total'] );
			}
			echo '</div>';
			echo '</div>';
		}
		echo '</div>';
	}
}

/**
 * Renders the plugin option page.
 */
function shariff3uu_options_page() {
	// The <div> with the class "wrap" makes sure that admin messages are displayed below the title and not above.
	echo '<div class="wrap">';

	// Title.
	echo '<h2>Shariff ' . esc_html( $GLOBALS['shariff3uu_basic']['version'] ) . '</h2>';

	// Start the form.
	echo '<form class="shariff" action="options.php" method="post">';

	// Hidden version entry, so it will get saved upon updating the options.
	echo '<input type="hidden" name="shariff3uu_basic[version]" value="' . esc_html( $GLOBALS['shariff3uu_basic']['version'] ) . '">';

	// Determine active tab.
	// phpcs:ignore
	if ( isset( $_GET['tab'] ) ) {
		// phpcs:ignore
		$active_tab = $_GET['tab'];
	} else {
		$active_tab = 'shariff3uu_basic';
	}

	// Tabs.
	echo '<h2 class="nav-tab-wrapper">';
		// Basic.
		echo '<a href="?page=shariff3uu&tab=shariff3uu_basic" class="nav-tab ';
	if ( 'shariff3uu_basic' === $active_tab ) {
		echo 'nav-tab-active';
	}
		echo '">' . esc_html__( 'Basic', 'shariff' ) . '</a>';
		// Design.
		echo '<a href="?page=shariff3uu&tab=shariff3uu_design" class="nav-tab ';
	if ( 'shariff3uu_design' === $active_tab ) {
		echo 'nav-tab-active';
	}
		echo '">' . esc_html__( 'Design', 'shariff' ) . '</a>';
		// Advanced.
		echo '<a href="?page=shariff3uu&tab=shariff3uu_advanced" class="nav-tab ';
	if ( 'shariff3uu_advanced' === $active_tab ) {
		echo 'nav-tab-active';
	}
		echo '">' . esc_html__( 'Advanced', 'shariff' ) . '</a>';
		// Statistic.
		echo '<a href="?page=shariff3uu&tab=shariff3uu_statistic" class="nav-tab ';
	if ( 'shariff3uu_statistic' === $active_tab ) {
		echo 'nav-tab-active';
	}
		echo '">' . esc_html__( 'Statistic', 'shariff' ) . '</a>';
		// Help.
		echo '<a href="?page=shariff3uu&tab=shariff3uu_help" class="nav-tab ';
	if ( 'shariff3uu_help' === $active_tab ) {
		echo 'nav-tab-active';
	}
		echo '">' . esc_html__( 'Help', 'shariff' ) . '</a>';
		// Status.
		echo '<a href="?page=shariff3uu&tab=shariff3uu_status" class="nav-tab ';
	if ( 'shariff3uu_status' === $active_tab ) {
		echo 'nav-tab-active';
	}
		echo '">' . esc_html__( 'Status', 'shariff' ) . '</a>';
		// Ranking.
		echo '<a href="?page=shariff3uu&tab=shariff3uu_ranking" class="nav-tab ';
	if ( 'shariff3uu_ranking' === $active_tab ) {
		echo 'nav-tab-active';
	}
		echo '">' . esc_html__( 'Ranking', 'shariff' ) . '</a>';
	echo '</h2>';

	// Content of tabs.
	if ( 'shariff3uu_basic' === $active_tab ) {
		settings_fields( 'shariff3uu_basic' );
		do_settings_sections( 'shariff3uu_basic' );
		submit_button();
	} elseif ( 'shariff3uu_design' === $active_tab ) {
		settings_fields( 'shariff3uu_design' );
		do_settings_sections( 'shariff3uu_design' );
		submit_button();
	} elseif ( 'shariff3uu_advanced' === $active_tab ) {
		settings_fields( 'shariff3uu_advanced' );
		do_settings_sections( 'shariff3uu_advanced' );
		submit_button();
	} elseif ( 'shariff3uu_statistic' === $active_tab ) {
		settings_fields( 'shariff3uu_statistic' );
		do_settings_sections( 'shariff3uu_statistic' );
		submit_button();
	} elseif ( 'shariff3uu_help' === $active_tab ) {
		settings_fields( 'shariff3uu_help' );
		do_settings_sections( 'shariff3uu_help' );
	} elseif ( 'shariff3uu_status' === $active_tab ) {
		settings_fields( 'shariff3uu_status' );
		do_settings_sections( 'shariff3uu_status' );
	} elseif ( 'shariff3uu_ranking' === $active_tab ) {
		settings_fields( 'shariff3uu_ranking' );
		do_settings_sections( 'shariff3uu_ranking' );
	}

	// End of form.
	echo '</form>';
}
