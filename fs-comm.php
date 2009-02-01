<?php

function fetch_remote_xml($url) {
	// Load up Troy Wolf's class
	require_once 'include/class_http.php';
	
	// Create a new instance of Troy Wolf's http class
	$fetcher = new http();
	$fetcher->dir = realpath("./cache/") . "/";
	
	// Fetch the data from the URL using a GET request (that's really 
	// all we need for this plugin--we're really only fetching gdata here)
	if (!$fetcher->fetch($url))
		return false;
		
	// Let's return the informaton the caller function  assuming that we 
	//  received some
	return $fetcher->body;
}

function fs_load_feed_data ($name, $days) {
	// Calculate out the date for the number of $days ago
	$minussev = mktime(0, 0, 0, date("m"), date("d") - $days, date("Y"));
	
	// Calculate out yesterday's date
	$minusone = mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"));
	
	$end = date("Y-m-d");
	$start = date("Y-m-d", $minussev);
	$yesterday = date("Y-m-d", $minusone);

	// Generate the URL
	$url = "https://feedburner.google.com/api/awareness/1.0/GetFeedData" . 
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
	$url = "https://feedburner.google.com/api/awareness/1.0/GetItemData" . 
		    "?uri=" . $name . "&dates=" . $start . "," . $end;

	// Fetch and parse the data
	return fetch_remote_xml($url);
}

?>
