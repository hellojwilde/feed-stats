<?php if (!defined('WPINC')) die("No outside script access allowed.");

/*
    Copyright (c) 2008 - 2009 Jonathan Wilde

    This file is part of Feed Stats for WordPress.

    Feed Stats for WordPress is free software: you can redistribute 
    it and/or modify it under the terms of the GNU General Public License as 
    published by the Free Software Foundation, either version 2 of the License, 
    or (at your option) any later version.

    Feed Stats for WordPress is distributed in the hope that it will 
    be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Feed Stats for WordPress.  If not, see 
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

function fetch_remote_xml($url, $fetcher=false) {
    // Create a new instance of Troy Wolf's HTTP class (if a mock one 
    // wasn't created during testing)
    if (!is_object($fetcher))
        $fetcher = new http();
        
    // Set the path of the cache
    $fetcher->dir = dirname(__FILE__) . "/cache/";
    
    // Fetch the data from the URL using a GET request (that's really 
    // all we need for this plugin--we're really only fetching gdata here)
    if (!$fetcher->fetch($url, 43200))
        return false;
        
    // Let's return the http class object to the caller function, assuming that we 
    // actually received some
    return $fetcher;
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
 * 
 */

function fs_fetch_feedburner_data ($url, $action, $get='', $fetcher=false) {
    // Let's instantiate our result variable ahead of time, assuming 
    // that the result will not be a success
    $result = array( 'success' => false );
    $name = '';
    
    if ($url == '') {
        // If no URL was passed in, stop now and return an error message
        $result['error'] = fs_translatable_error('Configuration needed');
        return $result;
    } elseif (preg_match("|(http:\/\/)?feed(.*)\.com\/|", $url) != 0) {
        // If we're using a Google FeedBurner/Google FeedProxy feed, 
        // we'll use PCRE to grab the feed name (temporarily stored in  
        // the $name variable)
        $name = preg_replace("|(http:\/\/)?feed(.*)\.com\/|", "", $url);
    } else {
        // For backwards compatibility reasons, if the feed URL isn't 
        // a URL, we'll assume that it's the feed name
        $name = $url;
    }

    // Generate our request string
    $format = "https://feedburner.google.com/api/awareness/1.0/%s?uri=%s&%s";
    $request = sprintf($format, $action, $name, $get);
    
    // Try to pull down the data
    $response = fetch_remote_xml($request, $fetcher);
    
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
    $end = date("Y-m-d");
    $start = date("Y-m-d", $minussev);

    // Get the data from FeedBurner
    return fs_fetch_feedburner_data($name, 
        "GetFeedData",
        "dates=" . $start . "," . $end
    );
}

function fs_load_item_data ($name, $days) {
    // Calculate out the dates
    $minussev = mktime(0, 0, 0, date("m"), date("d") - $days, date("Y"));
    $end = date("Y-m-d");
    $start = date("Y-m-d", $minussev);

    // Get data from FeedBurner
    return fs_fetch_feedburner_data($name, 
        "GetItemData",
        "dates=" . $start . "," . $end
    );
}

?>
