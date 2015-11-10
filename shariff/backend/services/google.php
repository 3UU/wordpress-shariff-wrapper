<?php

// GooglePlus

// set google options
$google_json = array(
	'method' => 'pos.plusones.get',
	'id'     => 'p',
	'params' => array(
		'nolog'   => 'true',
		'id'      => $post_url2,
		'source'  => 'widget',
		'userId'  => '@viewer',
		'groupId' => '@self'
	),
	'jsonrpc'    => '2.0',
	'key'        => 'p',
	'apiVersion' => 'v1'
);

// set post options
$google_post_options = array(
	'method' => 'POST',
	'timeout' => 45,
	'redirection' => 5,
	'httpversion' => '1.0',
	'blocking' => true,
	'headers' => array( 'content-type' => 'application/json' ),
	'body' => json_encode( $google_json )
);

// fetch counts
$google = sanitize_text_field( wp_remote_retrieve_body( wp_remote_post( 'https://clients6.google.com/rpc?key=AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ', $google_post_options ) ) );
$google = json_decode( $google, true );

// store results, if we have some
if ( isset( $google['result']['metadata']['globalCounts']['count'] ) ) {
	$share_counts['googleplus'] = $google['result']['metadata']['globalCounts']['count'];
}
// otherwise show the error message
else {
	echo "Google error! Message: ";
	print "<pre>";
	print_r( $google );
	print "</pre>";
}

?>
