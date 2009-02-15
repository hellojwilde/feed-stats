<?php

function fetch_remote_xml($url) {
	// Load up Troy Wolf's http class
	require_once 'include/class_http.php';
	
	// Create a new instance of Troy Wolf's http class
	$fetcher = new http();
	$fetcher->dir = realpath("./cache/") . "/";
	
	// Fetch the data from the URL using a GET request (that's really 
	// all we need for this plugin--we're really only fetching gdata here)
	if (!$fetcher->fetch($url))
		return false;
		
	// Let's return the informaton the caller function  assuming that we 
	// received some
	return $fetcher->body;
}

function fs_fetch_feedburner_data ($url, $action, $updatable=true, $post=null) {
	$old_awareness_url = "http://api.feedburner.com/awareness/1.0/";
	$new_awareness_url = "https://feedburner.google.com/api/awareness/1.0/";
	
	$name = '';
	$location = '';
	$nourl = false;
	
	// Detect what type of FeedBurner feed that we're using
	if (strpos($url, "feeds.feedburner.com") !== false) {
		$name = preg_replace("|(http:\/\/)?feeds\.feedburner\.com\/|", "", $url);
		$location = $old_awareness_url;
	} elseif (preg_match("|(http:\/\/)?feed(.*)\.com\/|", $url) != 0) {
		$name = preg_replace("|(http:\/\/)?feed(.*)\.com\/|", "", $url);
		$location = $new_awareness_url;
	} else {
		$name = $url;
		$location = $old_awareness_url;
		$nourl = true;
	}
	
	$req = $action . "?uri=" . $name . "&" . $post;
	
	// Try to pull in the feed data from the autodetected url
	$data = fetch_remote_xml($location . $req);
	$error = fs_check_errors($data);
	
	if ($error != false) {
		$data_tmp = fetch_remote_xml($new_awareness_url . $req);
		
		if (fs_check_errors($data_tmp) != false) {
			return array(
				'success' => false,
				'data' => $error,
			);
		} else {
			$data = $data_tmp;
			
			// If we have WordPress access, let's update the link
			if (function_exists('update_option') && $updatable == true)
				update_option('feedburner_feed_stats_name', "http://feedproxy.google.com/" . $name);
		}
	} else {
		// If we have WordPress access, let's update the link
		if (function_exists('update_option') && $updatable == true && $nourl == false)
			update_option('feedburner_feed_stats_name', "http://feeds.feedburner.com/" . $name);
	}
	
	return array(
		'success' => true,
		'data' => $data
	);
}

function fs_load_feed_data ($name, $days) {
	// Calculate out the date for the number of $days ago
	$minussev = mktime(0, 0, 0, date("m"), date("d") - $days, date("Y"));
	
	// Calculate out yesterday's date
	$minusone = mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"));
	
	$end = date("Y-m-d");
	$start = date("Y-m-d", $minussev);
	$yesterday = date("Y-m-d", $minusone);

	// Get the data from FeedBurner
	return fs_fetch_feedburner_data($name, 
		"GetFeedData",
		true,
		"&dates=" . $start . "," . $end
	);
}

function fs_load_item_data ($name, $days) {
	// Calculate out the date for the number of $days ago
	$minussev = mktime(0, 0, 0, date("m"), date("d") - $days, date("Y"));
	
	// Calculate out yesterday's date
	$minusone = mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"))
	
	$end = date("Y-m-d");
	$start = date("Y-m-d", $minussev);
	$yesterday = date("Y-m-d", $minusone);

	// Get data from FeedBurner
	return fs_fetch_feedburner_data($name, 
		"GetItemData",
		true,
		"&dates=" . $start . "," . $end
	);
}

?>
