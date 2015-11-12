<?php

// Twitter

// fetch counts
$twitter = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://cdn.api.twitter.com/1/urls/count.json?url=' . $post_url ) ) );
$twitter_json = json_decode( $twitter, true );

// store results, if we have some
if ( isset( $twitter_json['count'] ) ) {
	$share_counts['twitter'] = $twitter_json['count'];
}
// otherwise store the error message
else {
	$share_counts['errors']['twitter'] = "Twitter Error! Message: " . $twitter;
}

?>
