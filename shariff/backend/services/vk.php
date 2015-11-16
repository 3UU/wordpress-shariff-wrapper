<?php

// Twitter

// fetch counts
$vk = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'http://vk.com/share.php?act=count&url=' . $post_url ) ) );
if (!$vk){
	$share_counts['errors']['vk'] = "VK Error! Message: " . $vk;
}else{
	preg_match('/^VK.Share.count\((\d+),\s+(\d+)\);$/i', $vk, $matches);
	$vk_count = $matches[2];
}

// store results, if we have some
if ( isset( $vk_count ) ) {
	$share_counts['vk'] = intval($vk_count);
}
// otherwise store the error message
else {
	$share_counts['errors']['vk'] = "VK Error! Message: " . $vk;
}
?>
