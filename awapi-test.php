<?php

// Some dependencies needed to use the WP HTTP libraries and parse the
// output from the WP HTTP libraries
require_once('fs-comm.php');
require_once('fs-parse.php');

// Figure out the URL for the information at FeedBurner
$feed = $_GET['feed'];
$feed_url = "https://feedburner.google.com/api/awareness/1.0/GetFeedData?uri=" . $feed;

// Fetch the information from FeedBurner
$xml = fetch_remote_xml($feed_url);

// If there is XML returned, check it for errors from FeedBurner
$errors = fs_check_errors($xml);

// If there are no errors, tell the user that the feed is valid; if 
// there are errors, print them out directly.
if ($errors == false)
	echo 'The feed is valid.';
else
	echo $errors;
?>
