<?php if (!defined('WPINC')) die("No outside script access allowed.");

/*
    Plugin Name: Feed Stats for WordPress
    Plugin URI: http://www.speedbreeze.com/feed-stats
    Description: A quick and easy way to view the stats for your FeedBurner feed. After activating the plugin, make sure to  <a href="./options-general.php?page=feed-stats-options">configure</a> which feed you want to track.
    Version: 1.0.6
    Author: Jonathan Wilde
    Author URI: http://www.speedbreeze.com
*/

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

// Some dependencies needed to make this plugin run
require_once(dirname(__FILE__) . '/include/class_http.php');
require_once(dirname(__FILE__) . '/lang.php');
require_once(dirname(__FILE__) . '/client.php');
require_once(dirname(__FILE__) . '/parser.php');
require_once(dirname(__FILE__) . '/render.php');    

add_action('admin_menu', 'add_feed_stats_pages');
add_action('admin_head', 'feed_stats_classes');
add_action('init', 'fs_add_textdomain');

function fs_add_textdomain () {
    // Determine our translations folder name
    $i18n = basename(dirname(__FILE__)) . "/translations";
    
    // Load the text domain from the folder
    load_plugin_textdomain('feed-stats-plugin', 'wp-content/plugins/' . $i18n, $i18n);
}

function add_feed_stats_pages() {
    add_submenu_page('index.php', 'Feed Stats', 'Feed Stats', 'manage_options', 'feed-stats-stats', 'display_feed_stats');
    add_options_page('Feed Stats', 'Feed Stats', 'manage_options', 'feed-stats-options', 'display_feed_options');
}

function feed_stats_classes() {
    load_template(dirname(__FILE__) . '/templates/header.php');
}

function display_feed_stats() {
    // Grab options from the DB
    $days = get_option('feedburner_feed_stats_entries');
    $name = get_option('feedburner_feed_stats_name');
    
    // If the number of days is false (it wasn't set), default to 10
    $days = (!$days) ? 10 : $days;

    // Load data from FeedBurner
    $feed = fs_load_feed_data($name, $days);
    $items = fs_load_item_data($name, $days);

    // Render the data into a pretty set of charts
    if ($feed['success'] == true) {
        $have_reach = fs_have_reach($feed['data']);
        $meta = fs_grab_meta($feed['data']);
?>
    <div class="wrap">
        <h2>
            <?php _e('Feed Stats:', 'feed-stats-plugin') ?> <?php fs_feed_name($meta); ?>
            <span class="feed-stats-link">
                &nbsp;(<a href="<?php fs_dashboard_url($name, $meta); ?>"><?php _e('FeedBurner Dashboard', 'feed-stats-plugin'); ?></a>)
            <span>
        </h2>
        
        <table class="layout" width="100%"><tr>
            <td width="50%" class="feed-stats-left-chart-cell">
                <h3><?php _e('Total Hits &amp; Subscribers', 'feed-stats-plugin'); ?></h3>
                <div id="total-tab" class="feed-stats-tabs">
                    <ul class="total-tab-list">
                        <li id="hits-tab" onclick="selectTab('total-tab', 'hits');">
                            <a><?php _e('Hits', 'feed-stats-plugin') ?></a></li>
                        <li id="subs-tab" onclick="selectTab('total-tab', 'subs');">
                            <a><?php _e('Subscribers', 'feed-stats-plugin') ?></a></li>
                    <?php if ($have_reach): ?>
                        <li id="reach-tab" onclick="selectTab('total-tab', 'reach');">
                            <a><?php _e('Reach', 'feed-stats-plugin') ?></a></li>
                    <?php endif; ?>
                    </ul>

                    <div id="hits" class="feed-stats-tab">
                        <?php fs_feed_chart($feed['data'], 'hits'); ?>
                    </div>
                    <div id="subs" class="feed-stats-tab">
                        <?php fs_feed_chart($feed['data'], 'subs'); ?>
                    </div>
                <?php if ($have_reach): ?>
                    <div id="reach" class="feed-stats-tab">
                        <?php fs_feed_chart($feed['data'], 'reach'); ?>
                    </div>
                <?php endif; ?>

                    <script type="text/javascript">
                        selectTab('total-tab', 'hits');
                    </script>
                </div>
            </td>
            <td width="50%" class="feed-stats-right-chart-cell">
                <h3><?php _e('Yesterday\'s Viewed &amp; Clicked Feed Items', 'feed-stats-plugin') ?></h3>
<?php
        if ($items['success'] == true) :
            $item_count = fs_count_yesterday_items($items['data']);
?>

            <?php if ($item_count > 0) : ?>
                <div id="yest-tab" class="feed-stats-tabs">
                    <ul class="total-tab-list">
                        <?php if (fs_have_stat($items['data'], 'clicks')): ?>
                        <li id="clicks-tab" onclick="selectTab('yest-tab', 'clicks');">
                            <a><?php _e('Clicks', 'feed-stats-plugin') ?></a></li>
                        <?php endif; ?>
                        
                        <?php if (fs_have_stat($items['data'], 'views')): ?>
                        <li id="views-tab" onclick="selectTab('yest-tab', 'views');">
                            <a><?php _e('Views', 'feed-stats-plugin') ?></a></li>
                        <?php endif; ?>
                    </ul>

                    <?php if (fs_have_stat($items['data'], 'clicks')): ?>
                    <div id="clicks" class="feed-stats-tab">
                        <?php fs_items_chart($items['data'], 'clicks'); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (fs_have_stat($items['data'], 'views')): ?>
                    <div id="views" class="feed-stats-tab">
                        <?php fs_items_chart($items['data'], 'views'); ?>
                    </div>
                    <?php endif; ?>

                    <?php if (fs_have_stat($items['data'], 'clicks')): ?>
                    <script type="text/javascript">
                        selectTab('yest-tab', 'clicks');
                    </script>
                    <?php else: ?>
                    <script type="text/javascript">
                        selectTab('yest-tab', 'views');
                    </script>
                    <?php endif; ?>
                </div>

            <?php else: ?>
                <div class="fs-message">
                    <p><?php _e('There weren\'t any items that were 
clicked on in your feed yesterday.  If you just turned on item stats, 
wait a day or two for information to start showing up.', 
'feed-stats-plugin') ?></p>
                </div>

            <?php endif; ?>

        <? else: ?>
                <div class="fs-message">
                    <p><?php _e('It appears that you don\'t have Item 
Stats enabled in your FeedBurner account.  If it was enabled, you would 
be able to view information about clickthroughs on individual feed items.', 
'feed-stats-plugin') ?></p>
                    <p><?php _e('To enable them, you can go to', 'feed-stats-plugin') ?> <a href="<?php fs_stats_set_url($name, $meta) ?>"><?php _e('FeedBurner Stats settings', 'feed-stats-plugin') ?></a>.</p>
                </div>

        <?php endif; ?>

            </td>
        </tr><tr>
            <td class="feed-stats-table-container feed-stats-left-table-cell">
                <?php fs_feed_table($feed['data'], $have_reach); ?>
            </td>
            <td class="feed-stats-table-container feed-stats-right-table-cell">
                <?php if ($items['success'] == true && $item_count > 0) fs_items_table($items['data']); ?>
            </td>
        </tr></table>
    </div>
<?php
    } else {
?>
    <div class="wrap">
        <h2><?php echo $feed['error']['title']; ?></h2>
        <p class="fs-message"><?php echo $feed['error']['message']; ?></p>
    </div>
<?
    }
}

function display_feed_options() {
    if (!empty($_POST)) {
        // Validate the nonce
        check_admin_referer('feed-stats-edit_options');

        // Execute the function
        update_option('feedburner_feed_stats_name', $_POST['feed-stats-feed']);
        update_option('feedburner_feed_stats_entries', $_POST['feed-stats-entries']);
    }
?>
    <div class="wrap">
    <?php 
        if ($_GET['mode'] == 'help') {
            load_template(dirname(__FILE__) . "/templates/troubleshooting.php");
        } elseif ($_GET['mode'] == 'test') {
            load_template(dirname(__FILE__) . "/templates/test.php");
        } else {
            load_template(dirname(__FILE__) . "/templates/settings.php");
        } ?>
    </div>
<?php
}

if (!function_exists("admin_url")) {
    function admin_url () {
        return get_bloginfo("wpurl") . "/wp-admin/";
    }    
}

function ajax_test_url() {
    echo admin_url() . "options-general.php?page=feed-stats-options&mode=test";
}

function plugin_folder() {
    echo get_plugin_folder();
}

function get_plugin_folder() {  
    return get_bloginfo("wpurl") . "/wp-content/plugins/" . basename(dirname(__FILE__)) . "/";
}
?>
