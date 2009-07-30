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

// Figure out the URL for the information at FeedBurner
$feed = urldecode($_GET['feed']);
$response = fs_fetch_feedburner_data($feed, "GetFeedData", false);
$template = "[result][%+d] %s[result]";

// If there are no errors, tell the user that the feed is valid; if 
// there are errors, print them out directly.
if ($response['success'] == true) {
    $status = fs_translatable_error("Valid");
    printf($template, 
        $status['code'], 
        $status['title']);
} else
    printf($template, 
        $response['error']['code'], 
        $response['error']['title']);
?>
