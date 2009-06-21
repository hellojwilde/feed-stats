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

/*  
    Script: fs-comm.php

    Contains helper functions to simplify communication with FeedBurner. 
*/ 

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

define('SUPPORT_URL', 'http://www.speedbreeze.com/feed-stats/product/support');

function fs_translatable_error ($original) {
    $translatable = array(
        'Valid' => array(
            'code' => -5,
            'title' => __('This feed is valid.', 'feed-stats-plugin'),
            'message' => null
        ),
        'Unknown' => array(
            'code' => -4,
            'title' => __('Something Didn\'t Work Right...', 'feed-stats-plugin'),
            'message' => sprintf(__('This means that an error occurred,  
but there\'s no specific problem that can be easily determined. If you 
have questions, feel free to send a message to this plugin\'s 
<a href="%s">mailing list</a>.', 'feed-stats-plugin'), SUPPORT_URL)
        ),
        'Feedburner issues' => array(
            'code' => -3,
            'title' => __('FeedBurner\'s servers are having problems.', 'feed-stats-plugin'),
            'message' => sprintf(__('FeedBurner\'s Awareness API servers 
are currently having issues right now.  Try again later.  If this 
problem persists, feel free to send a message to this plugin\'s 
<a href="%s">mailing list</a>.', 'feed-stats-plugin'), SUPPORT_URL)
        ),
        'Cannot access FeedBurner' => array(
            'code' => -2,
            'title' => __('Unable to connect to FeedBurner', 'feed-stats-plugin'),
            'message' => sprintf(__('For some reason, this plugin cannot 
connect to the FeedBurner Awareness API servers.  This is usually due to 
a configuration issue on your server.  If you have questions, feel free 
to send a message to this plugin\'s <a href="%s">mailing list</a>.', 'feed-stats-plugin'), SUPPORT_URL)
        ),
        'Configuration needed' => array(
            'code' => -1,
            'title' => __('Please configure me.', 'feed-stats-plugin'),
            'message' => __('This plugin doesn\'t have a FeedBurner feed 
URL on record to display.  Please go to the settings page for this 
plugin and type in a feed URL.', 'feed-stats-plugin')
        ),
        'Feed Not Found' => array(
            'code' => 0,
            'title' => __('This feed doesn\'t exist.', 'feed-stats-plugin'),
            'message' => __('For some reason, FeedBurner can\'t find the 
feed URL that you wanted this plugin to track.  Did you move your feed 
over to Google FeedProxy? Did you delete the feed?  Is the URL correct?  
You might need to update the URL on the settings page.', 'feed-stats-plugin')
        ),
        'This feed does not permit Awareness API access' => array(
            'code' => 1,
            'title' => __('The Awareness API is not enabled.', 'feed-stats-plugin'),
            'message' => __('The Awareness API, which gives this plugin 
access to your stats, is not enabled for this feed.  Go into your 
FeedBurner account, click on your feed, click on the "Publicize" tab, 
click on the "Awareness API" button in the sidebar, and then click on 
the "Activate" button.', 'feed-stats-plugin')
        )
    );
    
    return $translatable[$original];
}

/*
 * Function: fs_fetch_feedburner_data
 * 
 * Parameters:
 * 
 *      $url    - The URL of the Google FeedBurner feed to fetch data 
 *                from.
 *      $action - The Awareness API action (eg. GetFeedData) to execute.
 *      $get    - Additional GET variables to append to the URL string.
 *      $update - Whether the function should update the database with 
 *                the URL (if the user entered the feed name instead of 
 *                the URL).
 */

function fs_fetch_feedburner_data ($url, $action, $get='', $update=true) {
    // Let's instantiate our result variable ahead of time, assuming 
    // that the result will not be a success
    $result = array( 'success' => false );
    $name = '';
    
    if ($url == '')
        // If no URL was passed in, then return an error message
        $result['error'] = fs_translatable_error('Configuration needed');
    elseif (preg_match("|(http:\/\/)?feed(.*)\.com\/|", $url) != 0) {
        // If we're using a Google FeedBurner/Google FeedProxy feed, 
        // we'll use PCRE to grab the feed name (temporarily stored in  
        // the $name variable)
		$name = preg_replace("|(http:\/\/)?feed(.*)\.com\/|", "", $url);
    } else 
        // For backwards compatibility reasons, if the feed URL isn't 
        // a URL, we'll assume that it's the feed name
        $name = $url;

    // Generate our request string
    $format = "https://feedburner.google.com/api/awareness/1.0/%s?uri=%s&%s";
    $request = sprintf($format, $action, $name, $get);
    
    // Try to pull down the data
    $response = fetch_remote_xml($request);
    
    // Search through the feed for errors
    if (preg_match('|rsp stat="fail"|', $response->body)) {
        // If there's an error embedded in the feed response, make it
        // grammatically correct and translatable
        preg_match('|msg="(.*?)"|', $response->body, $msg);
        $result['error'] = fs_translatable_error($msg[1]);
    } elseif ($response->status == "401") {
        // If the feed does not permit AwAPI access, return that;
        // sometimes
        $result['error'] = fs_translatable_error(
            'This feed does not permit Awareness API access');
    } elseif ($response->status == "500") {
        // If FeedBurner is giving us an internal server error, 
        // return the boilerplate message
        $result['error'] = fs_translatable_error('Feedburner issues');
    } elseif (strlen($response->body) == 0) {
        // If there was no data returned, then there was some 
        // problem with trying to contact FeedBurner
        $result['error'] = fs_translatable_error(
            'Cannot access FeedBurner');
    } elseif ($response->status == "200") {
        // Everything went well, so return the response from 
        // FeedBurner back to the user
        $result['success'] = true;
        $result['status'] = $response->status;
        $result['data'] = $response->body;
        $result['url'] = $data->url;
    } else {
        // We have no idea what went wrong; tell that to the user
        $result['error'] = fs_translatable_error('Unknown error');
    }
    
    // Return our result array to the user
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
		"dates=" . $start . "," . $end,
        true
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
		"dates=" . $start . "," . $end,
        true
	);
}

?>
