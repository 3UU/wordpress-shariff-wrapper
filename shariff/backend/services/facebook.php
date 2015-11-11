<?php

// Facebook

// if we have a fb id and secret, use it
if ( isset( $shariff3UU_advanced['fb_id'] ) && isset( $shariff3UU_advanced['fb_secret'] ) ) {
	$fb_app_id = absint( $shariff3UU_advanced['fb_id'] );
	$fb_app_secret = sanitize_text_field( $shariff3UU_advanced['fb_secret'] );
	// get fb access token
	$fb_token = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://graph.facebook.com/oauth/access_token?client_id=' .  $fb_app_id . '&client_secret=' . $fb_app_secret . '&grant_type=client_credentials' ) ) );
	// use token to get share counts
	$facebookID = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://graph.facebook.com/v2.2/?id=' . $post_url . '&' . $fb_token ) ) );
	$facebookID = json_decode( $facebookID, true );
	// store results, if we have some
	if ( isset( $facebookID['share']['share_count'] ) ) {
		$share_counts['facebook'] = $facebookID['share']['share_count'];
	}
	// otherwise store the error message
	else {
		$share_counts['errors']['facebookID'] = "Facebook ID Error! Message: " . $facebookID;
	}
}
// otherwise use the normal way
else { 
	$facebook = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://graph.facebook.com/fql?q=SELECT%20share_count%20FROM%20link_stat%20WHERE%20url="' . $post_url . '"' ) ) );
	$facebook = json_decode( $facebook, true );
	// store results, if we have some
	if ( isset( $facebook['data']['0']['share_count'] ) ) {
		$share_counts['facebook'] = $facebook['data']['0']['share_count'];
	}
	// otherwise show the error message
	else {
		$share_counts['errors']['facebook'] = "Facebook Error! Message: " . $facebook;
	}
}

?>
