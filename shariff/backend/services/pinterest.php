<?php

// Pinterest

// fetch counts
$pinterest = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'http://api.pinterest.com/v1/urls/count.json?callback=x&url=' . $post_url ) ) );
$pinterest_json = rtrim( substr( $pinterest, 2 ) , ")" );
$pinterest_json = json_decode( $pinterest_json, true );

// store results, if we have some
if ( isset( $pinterest_json['count'] ) ) {
	$share_counts['pinterest'] = $pinterest_json['count'];
}
// otherwise store the error message
else {
	$share_counts['errors']['pinterest'] = "Pinterest Error! Message: " . $pinterest;
}


?>
