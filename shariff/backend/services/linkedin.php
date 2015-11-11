<?php

// LinkedIn

// fetch counts
$linkedin = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://www.linkedin.com/countserv/count/share?url=' . $post_url . '&lang=de_DE&format=json' ) ) );
$linkedin = json_decode( $linkedin, true );

// store results, if we have some
if ( isset( $linkedin['count'] ) ) {
	$share_counts['linkedin'] = $linkedin['count'];
}
// otherwise store the error message
else {
	$share_counts['errors']['linkedin'] = "LinkedIn Error! Message: " . $linkedin;
}

?>
