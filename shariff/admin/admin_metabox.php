<?php
// Will be included in the shariff.php only, when user is logged in.

// prevent direct calls to admin_menu.php
if ( ! class_exists('WP') ) { die(); }

// call setup function on the post editor screen
add_action( 'load-post.php', 'shariff3UU_metabox_setup' );
add_action( 'load-post-new.php', 'shariff3UU_metabox_setup' );

// meta box setup function
function shariff3UU_metabox_setup() {
	add_action( 'add_meta_boxes', 'shariff3UU_add_metabox' );
}

// add meta box
function shariff3UU_add_metabox() {
	foreach( get_post_types() as $posttype ) {
		add_meta_box( 'shariff_metabox', __( 'Shariff Settings', 'shariff' ), 'shariff3uu_build_metabox', $posttype, 'side', 'default' );
	}
}

// build meta box
function shariff3uu_build_metabox() {
	// scripts for pinterest image media uploader
	wp_enqueue_media();
	wp_register_script( 'shariff_mediaupload', plugins_url( '../js/shariff-media.js', __FILE__ ), array( 'jquery' ), '1.0', true  );
	$translation_array = array( 'choose_image' => __( 'Choose image', 'shariff' ) );
	wp_localize_script( 'shariff_mediaupload', 'shariff_media', $translation_array );
	wp_enqueue_script( 'shariff_mediaupload' );
		
	// make sure the form request comes from WordPress
	wp_nonce_field( basename( __FILE__ ), 'shariff_metabox_nonce' );
	
	// retrieve the current metabox disable value
	$shariff_metabox_disable = get_post_meta( get_the_ID(), 'shariff_metabox_disable', true );
	// disable checkbox
	echo '<p><strong>' . __( 'Disable Shariff', 'shariff' ) . '</strong><br>';
	echo '<input type="checkbox" name="shariff_metabox_disable"';
	if ( isset( $shariff_metabox_disable ) ) echo checked( $shariff_metabox_disable, 1, 0 );
	echo '>';
	echo '<label for="shariff_metabox_disable">' . __( 'Disable Shariff on this post or page.', 'shariff' ) . '</label></p>';
	
	// retrieve the current metabox media value (pinterest image)
	$shariff_metabox_media = get_post_meta( get_the_ID(), 'shariff_metabox_media', true );
	// metabox shortcode
	echo '<p><strong>' . __( 'Pinterest Image', 'shariff' ) . '</strong><br><label for="shariff_metabox_media">' . __( 'The complete url to your desired custom image for Pinterest.', 'shariff' ) . '</label><br>';
	echo '<input type="text" name="shariff_metabox_media" value="' . esc_html( $shariff_metabox_media ) . '" id="shariff-image-url" style="width:55%; margin-right:5px"><input type="button" name="upload-btn" id="shariff-upload-btn" class="button-secondary" value="' . __( 'Choose image', 'shariff' ) . '"></p>';
	
	// retrieve the current metabox shortcode value
	$shariff_metabox = get_post_meta( get_the_ID(), 'shariff_metabox', true );
	// metabox shortcode
	echo '<p><strong>' . __( 'Shortcode', 'shariff' ) . '</strong><br><label for="shariff_metabox">' . __( 'The settings in this shortcode field overwrite <u>all</u> global settings.', 'shariff' ) . '</label><br>';
	echo '<input type="text" name="shariff_metabox" value="' . esc_html( $shariff_metabox ) . '" placeholder="[shariff]" style="width:100%"></p>';
	
	// retrieve the current metabox ignore widget value
	$shariff_metabox_ignore_widget = get_post_meta( get_the_ID(), 'shariff_metabox_ignore_widget', true );
	// disable checkbox
	echo '<p><strong>' . __( 'Ignore Widgets', 'shariff' ) . '</strong><br>';
	echo '<input type="checkbox" name="shariff_metabox_ignore_widget"';
	if ( isset( $shariff_metabox_ignore_widget ) ) echo checked( $shariff_metabox_ignore_widget, 1, 0 );
	echo '>';
	echo '<label for="shariff_metabox_ignore_widget">' . __( 'Do not affect widgets.', 'shariff' ) . '</label></p>';
}

// save meta data
function shariff3UU_save_metabox_data( $post_id ) {
	// check nonce and if shariff_metabox is set
	if ( isset( $_REQUEST['shariff_metabox_nonce'] ) && wp_verify_nonce( $_REQUEST['shariff_metabox_nonce'], basename( __FILE__ ) ) ) {
		// save meta box disable
		if ( isset( $_POST['shariff_metabox_disable'] ) && $_POST['shariff_metabox_disable'] == 'on' ) {
			update_post_meta( $post_id, 'shariff_metabox_disable', '1' );
		}
		else {
			delete_post_meta( $post_id, 'shariff_metabox_disable', '1' );
		}
		
		// save meta box media
		if ( isset( $_POST['shariff_metabox_media'] ) && ! empty( $_POST['shariff_metabox_media'] ) ) {
			update_post_meta( $post_id, 'shariff_metabox_media', esc_url_raw( $_POST['shariff_metabox_media'] ) );
		}
		else {
			delete_post_meta( $post_id, 'shariff_metabox_media' );
		}
		
		// save meta box shortcode
		if ( isset( $_POST['shariff_metabox'] ) && ! empty( $_POST['shariff_metabox'] ) ) {
			update_post_meta( $post_id, 'shariff_metabox', wp_kses( $_POST['shariff_metabox'], $GLOBALS["allowed_tags"] ) );
		}
		else {
			delete_post_meta( $post_id, 'shariff_metabox' );
		}

		// save meta box ignore widgets
		if ( isset( $_POST['shariff_metabox_ignore_widget'] ) && $_POST['shariff_metabox_ignore_widget'] == 'on' ) {
			update_post_meta( $post_id, 'shariff_metabox_ignore_widget', '1' );
		}
		else {
			delete_post_meta( $post_id, 'shariff_metabox_ignore_widget', '1' );
		}
	}
}
add_action( 'save_post', 'shariff3UU_save_metabox_data', 10, 2 );
