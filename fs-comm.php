<?php

function fetch_remote_xml($url) {
	require_once(ABSPATH . WPINC . '/classes.php');	
	require_once(ABSPATH . WPINC . '/functions.php');	
	require_once(ABSPATH . WPINC . '/http.php');	
	
	require_once(ABSPATH . WPINC . '/rss.php');
		
	$remote = _fetch_remote_file($url);

	if ( is_success($remote->status) ) { 
		return $remote->results;
	} else {
		return false;
	}
}

function fs_load_feed_data ($name, $days) {
	// Calculate out some dates
	$minussev = mktime(0, 0, 0, date("m"), date("d") - $days, date("Y"));
	$minusone = mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"));
	
	$end = date("Y-m-d");
	$start = date("Y-m-d", $minussev);
	$yesterday = date("Y-m-d", $minusone);

	// Generate the URL
	$url = "http://api.feedburner.com/awareness/1.0/GetFeedData" . 
		   "?uri=" . $name . "&dates=" . $start . "," . $end;

	// Fetch and parse the data
	return fetch_remote_xml($url);
}

function fs_load_item_data ($name, $days) {
	// Calculate out some dates
	$minussev = mktime(0, 0, 0, date("m"), date("d") - $days, date("Y"));
	$minusone = mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"));
	
	$end = date("Y-m-d");
	$start = date("Y-m-d", $minussev);
	$yesterday = date("Y-m-d", $minusone);

	// Generate the URL
	$url = "http://api.feedburner.com/awareness/1.0/GetItemData" . 
		    "?uri=" . $name . "&dates=" . $start . "," . $end;

	// Fetch and parse the data
	return fetch_remote_xml($url);
}

?>
