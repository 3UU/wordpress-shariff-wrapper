<?php

// Twitter

// fetch counts
$twitter = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://cdn.api.twitter.com/1/urls/count.json?url="' . $post_url . '"' ) ) );
$twitter = json_decode( $twitter, true );

// store results, if we have some
if ( isset( $twitter['count'] ) ) {
	$share_counts['twitter'] = $twitter['count'];
}
// otherwise show the error message
else {
	echo "Twitter Error! Message: ";
	print "<pre>";
	print_r( $twitter );
	print "</pre>";
}

?>
