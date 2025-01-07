<?php
/**
 * Will be included in the shariff.php only, when an user is logged in.
 *
 * @package Shariff Wrapper
 * @subpackage admin
 */

// Prevent direct calls to admin_menu.php.
if ( ! class_exists( 'WP' ) ) {
	die();
}

// Call setup function on the post editor screen.
add_action( 'load-post.php', 'shariff3uu_metabox_setup' );
add_action( 'load-post-new.php', 'shariff3uu_metabox_setup' );

/**
 * Meta box setup function.
 */
function shariff3uu_metabox_setup() {
	add_action( 'add_meta_boxes', 'shariff3uu_add_metabox' );
}

/**
 * Adds the meta box.
 */
function shariff3uu_add_metabox() {
	foreach ( get_post_types() as $posttype ) {
		add_meta_box( 'shariff_metabox', __( 'Shariff Settings', 'shariff' ), 'shariff3uu_build_metabox', $posttype, 'side', 'default' );
	}
}

/**
 * Builds the meta box.
 */
function shariff3uu_build_metabox() {
	// Scripts for the pinterest image media uploader.
	wp_enqueue_media();
	wp_register_script( 'shariff_mediaupload', plugins_url( '../js/shariff-media.js', __FILE__ ), array( 'jquery' ), '1.0', true );
	$translation_array = array( 'choose_image' => __( 'Choose image', 'shariff' ) );
	wp_localize_script( 'shariff_mediaupload', 'shariff_media', $translation_array );
	wp_enqueue_script( 'shariff_mediaupload' );

	// Make sure the form request comes from WordPress.
	wp_nonce_field( basename( __FILE__ ), 'shariff_metabox_nonce' );

	// Retrieve the current metabox disable value.
	$shariff_metabox_disable = get_post_meta( get_the_ID(), 'shariff_metabox_disable', true );
	// Disable checkbox.
	echo '<p><strong>' . esc_html__( 'Disable Shariff', 'shariff' ) . '</strong><br>';
	echo '<input type="checkbox" name="shariff_metabox_disable" id="shariff_metabox_disable"';
	if ( isset( $shariff_metabox_disable ) ) {
		echo checked( $shariff_metabox_disable, 1, 0 );
	}
	echo '>';
	echo '<label for="shariff_metabox_disable">' . esc_html__( 'Disable Shariff for this content.', 'shariff' ) . '</label></p>';

	// Retrieve the current metabox add before and after values.
	$shariff_metabox_before = get_post_meta( get_the_ID(), 'shariff_metabox_before', true );
	$shariff_metabox_after  = get_post_meta( get_the_ID(), 'shariff_metabox_after', true );
	// Add Shariff checkboxes.
	echo '<p><strong>' . esc_html__( 'Add Shariff', 'shariff' ) . '</strong><br>';
	// Before checkbox.
	echo '<input type="checkbox" name="shariff_metabox_before" id="shariff_metabox_before"';
	if ( isset( $shariff_metabox_before ) ) {
		echo checked( $shariff_metabox_before, 1, 0 );
	}
	echo '>';
	echo '<label for="shariff_metabox_before">' . esc_html__( 'Add buttons before this content.', 'shariff' ) . '</label><br>';
	// After checkbox.
	echo '<input type="checkbox" name="shariff_metabox_after" id="shariff_metabox_after"';
	if ( isset( $shariff_metabox_after ) ) {
		echo checked( $shariff_metabox_after, 1, 0 );
	}
	echo '>';
	echo '<label for="shariff_metabox_after">' . esc_html__( 'Add buttons after this content.', 'shariff' ) . '</label></p>';

	// Retrieve the current metabox media value (pinterest image).
	$shariff_metabox_media = get_post_meta( get_the_ID(), 'shariff_metabox_media', true );

	// Metabox shortcode.
	echo '<p><strong>' . esc_html__( 'Pinterest Image', 'shariff' ) . '</strong><br><label for="shariff_metabox_media">' . esc_html__( 'The complete url to your desired custom image for Pinterest.', 'shariff' ) . '</label><br>';
	echo '<input type="text" name="shariff_metabox_media" id="shariff-image-url" value="' . esc_html( $shariff_metabox_media ) . '" style="width:90%; margin-right:5px"><input type="button" name="upload-btn" id="shariff-upload-btn" class="button-secondary" value="' . esc_html__( 'Choose image', 'shariff' ) . '"></p>';

	// Retrieve the current metabox shortcode value.
	$shariff_metabox = get_post_meta( get_the_ID(), 'shariff_metabox', true );
	// Metabox shortcode.
	echo '<p><strong>' . esc_html__( 'Shortcode', 'shariff' ) . '</strong><br><label for="shariff_metabox">' . esc_html__( 'The settings in this shortcode field overwrite ALL global settings.', 'shariff' ) . '</label><br>';
	echo '<input type="text" name="shariff_metabox" id="shariff_metabox" value="' . esc_html( $shariff_metabox ) . '" placeholder="[shariff]" style="width:90%"></p>';

	// Retrieve the current metabox ignore widget value.
	$shariff_metabox_ignore_widget = get_post_meta( get_the_ID(), 'shariff_metabox_ignore_widget', true );
	// Disable checkbox.
	echo '<p><strong>' . esc_html__( 'Ignore Widgets', 'shariff' ) . '</strong><br>';
	echo '<input type="checkbox" name="shariff_metabox_ignore_widget" id="shariff_metabox_ignore_widget"';
	if ( isset( $shariff_metabox_ignore_widget ) ) {
		echo checked( $shariff_metabox_ignore_widget, 1, 0 );
	}
	echo '>';
	echo '<label for="shariff_metabox_ignore_widget">' . esc_html__( 'Do not affect buttons in widgets.', 'shariff' ) . '</label></p>';
}

/**
 * Save meta data.
 *
 * @param integer $post_id ID of the current post.
 * @param WP_Post $post Current post.
 */
function shariff3uu_save_metabox_data( $post_id, $post ) {
	// Check nonce and if shariff_metabox is set.
	if ( isset( $_REQUEST['shariff_metabox_nonce'] ) && wp_verify_nonce( sanitize_key( $_REQUEST['shariff_metabox_nonce'] ), basename( __FILE__ ) ) ) {
		// Check if we are not autosaving or previewing (revision), else we are good to go and can save our meta box data.
		$post_type_object = get_post_type_object( $post->post_type );
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post ) || ( is_multisite() && ms_is_switched() ) || ! current_user_can( $post_type_object->cap->edit_post, $post_id ) ) {
			return;
		} else {
			// Save meta box disable.
			if ( isset( $_POST['shariff_metabox_disable'] ) && 'on' === $_POST['shariff_metabox_disable'] ) {
				update_post_meta( $post_id, 'shariff_metabox_disable', 1 );
			} else {
				delete_post_meta( $post_id, 'shariff_metabox_disable', 1 );
			}

			// Save meta box add before value.
			if ( isset( $_POST['shariff_metabox_before'] ) && 'on' === $_POST['shariff_metabox_before'] ) {
				update_post_meta( $post_id, 'shariff_metabox_before', 1 );
			} else {
				delete_post_meta( $post_id, 'shariff_metabox_before', 1 );
			}

			// Save meta box add after value.
			if ( isset( $_POST['shariff_metabox_after'] ) && 'on' === $_POST['shariff_metabox_after'] ) {
				update_post_meta( $post_id, 'shariff_metabox_after', 1 );
			} else {
				delete_post_meta( $post_id, 'shariff_metabox_after', 1 );
			}

			// Save meta box media.
			if ( isset( $_POST['shariff_metabox_media'] ) && ! empty( $_POST['shariff_metabox_media'] ) ) {
				update_post_meta( $post_id, 'shariff_metabox_media', esc_url_raw( wp_unslash( $_POST['shariff_metabox_media'] ) ) );
			} else {
				delete_post_meta( $post_id, 'shariff_metabox_media' );
			}

			// Save meta box shortcode.
			if ( isset( $_POST['shariff_metabox'] ) && ! empty( $_POST['shariff_metabox'] ) ) {
				update_post_meta( $post_id, 'shariff_metabox', wp_kses( wp_unslash( $_POST['shariff_metabox'] ), $GLOBALS['allowed_tags'] ) );
			} else {
				delete_post_meta( $post_id, 'shariff_metabox' );
			}

			// Save meta box ignore widgets.
			if ( isset( $_POST['shariff_metabox_ignore_widget'] ) && 'on' === $_POST['shariff_metabox_ignore_widget'] ) {
				update_post_meta( $post_id, 'shariff_metabox_ignore_widget', 1 );
			} else {
				delete_post_meta( $post_id, 'shariff_metabox_ignore_widget', 1 );
			}
		}
	}
}
add_action( 'save_post', 'shariff3uu_save_metabox_data', 10, 2 );
