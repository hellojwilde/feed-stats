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

?>
