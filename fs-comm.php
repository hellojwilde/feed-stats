<?php if (!defined('WPINC')) die("No outside script access allowed.");

/*
	Copyright (c) 2008 - 2009 Jonathan Wilde

	This file is part of the Feed Stats Plugin for WordPress.

    The Feed Stats Plugin for WordPress is free software: you can redistribute 
    it and/or modify it under the terms of the GNU General Public License as 
    published by the Free Software Foundation, either version 2 of the License, 
    or (at your option) any later version.

    The Feed Stats Plugin for WordPress is distributed in the hope that it will 
    be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with the Feed Stats Plugin for WordPress.  If not, see 
    <http://www.gnu.org/licenses/>.
*/

/*  Script: fs-comm.php
    Contains helper functions to simplify communication with FeedBurner. */ 

/*  
    Function: fetch_remote_xml

    Uses Troy Wolf's HTTP class to fetch files from remote servers.  This is a 
    simple way to get content from URLs.  

    Please note that the naming of this function is a little strange--it 
    actually *can* fetch other types of content than XML.  I was trying to 
    differentiate it from other HTTP fetch functions inside of WordPress.

    Syntax:

        > $page = fetch_remote_xml($url);

    Returns:

        (object) The instance of the http class used to query the data.  To get
        the content body returned from the remote server, you would retrieve the 
        body key, as shown in the example above.

    Example:

        > $page = fetch_remote_xml("http://www.example.com/blah.xml");
        > // $page is the http object used to get the information from the server
        >
        > $body = $page->body;
        > // $body contains the HTTP body returned from the server without any 
        > // headers at the top
*/

function fetch_remote_xml($url) {
	// Load Troy Wolf's HTTP class so that we can communicate with remote 
    // servers, in our case FeedBurner
	require_once('include/class_http.php');
	
	// Create a new instance of Troy Wolf's HTTP class
	$fetcher = new http();
	$fetcher->dir = realpath("./cache/") . "/";
	
	// Fetch the data from the URL using a GET request (that's really 
	// all we need for this plugin--we're really only fetching gdata here)
	if (!$fetcher->fetch($url))
		return false;
		
	// Let's return the http class object to the caller function, assuming that we 
    // actually received some
	return $fetcher;
}

function fs_fetch_feedburner_data ($url, $action, $updatable=true, $post=null) {
    // The pre-Google Awareness API URL (used for people who haven't moved their 
    // account over to Google FeedBurner)
    $old_awareness_url = "http://api.feedburner.com/awareness/1.0/";

    // The new Google Awareness API URL (for feeds at the new Google FeedBurner
	$new_awareness_url = "https://feedburner.google.com/api/awareness/1.0/";
	
    // Let's instantiate a few variables
	$name = '';
	$location = '';
	$nourl = false;
	
	// Detect what type of FeedBurner feed that we're using
	if (strpos($url, "feeds.feedburner.com") !== false) {
        // If we're using a pre-Google FeedBurner feed, we'll use PCRE to grab
        // the feed name (stored in the $name variable) and set our $location 
        // variable to the old Awareness API URL
		$name = preg_replace("|(http:\/\/)?feeds\.feedburner\.com\/|", "", $url);
		$location = $old_awareness_url;
	} elseif (preg_match("|(http:\/\/)?feed(.*)\.com\/|", $url) != 0) {
        // If we're using a Google FeedBurner/Google FeedProxy feed, we'll use
        // PCRE to grab the feed name (again stored in the $name variable) and 
        // set our $location variable to the new Awareness API URL
		$name = preg_replace("|(http:\/\/)?feed(.*)\.com\/|", "", $url);
		$location = $new_awareness_url;
	} else {
        // If the data passed in doesn't match any of the above URLs, let's 
        // assume that a feed name, rather than a URL, was passed in; since this 
        // is a way of storing the FeedBurner feed in earlier versions of the 
        // plugin, we'll assume that the user is using the older version of the 
        // FeedBurner API.  We need to set the $nourl variable to true, which 
        // indicates that we don't have a full URL, need to try the new FeedBurner 
        // API if it doesn't work, and need to eventually update the setting 
        // containing the feed name in the database once we find a working URL.
		$name = $url;
		$location = $old_awareness_url;
		$nourl = true;
	}
	
    // We will formulate our request string, complete with GET variables
	$req = $action . "?uri=" . $name . "&" . $post;
	
	// Try to pull in the feed data from the autodetected URL
	$data = fetch_remote_xml($location . $req);
	$error = fs_check_errors($data->body, $data->status);

    // Create the base for a result array
    $result = array(
        'code' => $data->status,
        'body' => $data->body,
        'success' => true,
		'data' => $data->body,
        'url' => $data->url
    );
	
	if ($error != false && $nourl == true) {
        // Let's try the new FeedBurner servers to see if we can get a new result there.
		$data_tmp = fetch_remote_xml($new_awareness_url . $req);
		
		if (fs_check_errors($data_tmp->body, $data_tmp->status) != false) {
            // We didn't have a complete URL to start with and our check of the 
            // new Google FeedBurner servers failed, too.  We'll stop and give 
            // that result to the calling function
			$result['success'] = false;
            $result['data'] = $error;
		} else {
            // Since this second URL worked, let's update the result array
			$result['code'] = $data_tmp->status;
            $result['body'] = $data_tmp->body;
            $result['data'] = $data_tmp->body;
            $result['url'] = $data_tmp->url;
			
			// If we have WordPress access, let's update the link
			if (function_exists('update_option') && $updatable == true)
				update_option('feedburner_feed_stats_name', "http://feedproxy.google.com/" . $name);
		}
	} elseif ($error == false && $nourl == true) {
		// If we have WordPress access, let's update the link
		if (function_exists('update_option') && $updatable == true && $nourl == false)
			update_option('feedburner_feed_stats_name', "http://feeds.feedburner.com/" . $name);
	} elseif ($error != false && $nourl == false) {
        // We had a full URL to start with, but it didn't work; we don't really
        // have any recourse.  Let's tell this to the caller function
        $result['success'] = false;
        $result['data'] = $error;
	}

	return $result;
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
		"dates=" . $start . "," . $end
	);
}

function fs_load_item_data ($name, $days) {
	// Calculate out the date for the number of $days ago
	$minussev = mktime(0, 0, 0, date("m"), date("d") - $days, date("Y"));
	
	// Calculate out yesterday's date
	$minusone = mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"));
	
	$end = date("Y-m-d");
	$start = date("Y-m-d", $minussev);
	$yesterday = date("Y-m-d", $minusone);

	// Get data from FeedBurner
	return fs_fetch_feedburner_data($name, 
		"GetItemData",
		true,
		"dates=" . $start . "," . $end
	);
}

?>
