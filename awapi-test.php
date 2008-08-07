<?php

// Some dummy (or real) variables and functions to make the dependencies happy
define("ABSPATH", $_GET['abs']);
define("WPINC", $_GET['inc']);

function do_action() {
	return true;
}

// The dependencies
require_once ('fs-comm.php');
require_once ('fs-parse.php');

// The testing code
$feed = $_GET['feed'];
$feed_url = "http://api.feedburner.com/awareness/1.0/GetFeedData?uri=" . $feed;

$xml = fetch_remote_xml($feed_url);

if (isset($xml)) {
	$errors = fs_check_errors($xml);

	if ($errors == false) {
		echo 'The feed is valid.';
	} else {
		echo $errors;
	}
} else {
	echo 'The FeedBurner server is not availible';
}
?>