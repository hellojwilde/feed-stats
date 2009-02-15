<?php

// Some dependencies needed to use the WP HTTP libraries and parse the
// output from the WP HTTP libraries
require_once('fs-comm.php');
require_once('fs-parse.php');

// Figure out the URL for the information at FeedBurner
$feed = urldecode($_GET['feed']);
$data = fs_fetch_feedburner_data($feed, "GetFeedData", false);

// If there are no errors, tell the user that the feed is valid; if 
// there are errors, print them out directly.
if ($data['success'] == true)
	echo 'The feed is valid.';
else
	echo $data['data'];
?>
