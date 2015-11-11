<?php

// Flattr

// fetch counts
$flattr = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://api.flattr.com/rest/v2/things/lookup/?url=' . $post_url ) ) );
echo $flattr;
$flattr = json_decode( $flattr, true );
echo $flattr;
// store results, if we have some
if ( isset( $flattr['flattrs'] ) ) {
	$share_counts['flattr'] = $flattr['flattrs'];
}
// otherwise show the error message
else {
	$share_counts['errors']['flattr'] = "Flattr Error! Message: " . $flattr;
}

?>
