<?php

// Include the wp-config.php file. We're going to do this with relative 
// paths, but to ensure that something bad doesn't happen if somebody 
// unzips the plugin directly into the plugins folder, we're going to 
// check the name of the parent directory.
if (dirname(__FILE__) == 'plugins')
	require_once '../../wp-config.php';
else
	require_once '../../../wp-config.php';

// Include the WP internationalization libraries
require_once(ABSPATH . WPINC . '/l10n.php');

// Some dependencies needed to use the WP HTTP libraries and parse the
// output from the WP HTTP libraries
require_once('fs-comm.php');
require_once('fs-parse.php');

// Figure out the URL for the information at FeedBurner
$feed = $_GET['feed'];
$feed_url = "http://api.feedburner.com/awareness/1.0/GetFeedData?uri=" . $feed;

// Fetch the information from FeedBurner
$xml = fetch_remote_xml($feed_url);

if (isset($xml)) {
	// If there is XML returned, check it for errors from FeedBurner
	$errors = fs_check_errors($xml);

	// If there are no errors, tell the user that the feed is valid; if 
	// there are errors, print them out directly.
	if ($errors == false)
		echo 'The feed is valid.';
	else
		echo $errors;
} else {
	// If we cannot get xml, we'll tell the user that
	echo 'Cannot access FeedBurner.';
}
?>
