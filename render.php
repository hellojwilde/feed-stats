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

function fs_feed_name ($meta) {
    echo $meta[1];
}

function fs_dashboard_domain ($url) {
    if (strpos($url, "feeds.feedburner.com") !== false or strpos($url, "http://") === false)
        return "http://www.feedburner.com/";
    else
        return "http://feedburner.google.com/";
}

function fs_dashboard_url ($feed, $meta) {
    echo fs_dashboard_domain($feed) . "fb/a/dashboard?id=" . $meta[0];
}

function fs_stats_set_url ($feed, $meta) {
    echo fs_dashboard_domain($feed) . "fb/a/analyze/totalstats?id=" . $meta[0];
}

// $type can be 'subs', 'reach', or 'hits'
function fs_feed_chart ($data, $type) {
    $entries = fs_grab_entries($data);

    $chart_data_string = "chd=t:";
    $chart_day_string = "chxl=0:";
    
    $chart_values = array();
    foreach ($entries as $entry) {
        $data = fs_parse_entry($entry);

        $chart_day_string .= "|" . fs_shorten_date($data['date'], -5);
        array_push($chart_values, $data[$type]);
    }
    
    if (count($chart_values) > 0) {
        $highest = max($chart_values) * 1.1;

        foreach ($entries as $entry) {
            $data = fs_parse_entry($entry);
            $chart_data_string .= @round((($data[$type] / $highest) * 100), 1) . ",";
        }

        $chart_data_string = substr($chart_data_string, 0, strlen($chart_data_string) - 1);
        $url = 'http://chart.apis.google.com/chart?cht=lc&chg=0,20,2,2&chls=3,6,0&chs=450x220&' . $chart_data_string . 
                '&chco=A0BAE9&chm=B,E4F2FD,0,0,0&chxt=x,y&' . $chart_day_string . "&chxr=1,0," . $highest;

        echo "<img class='feed-stats-chart' src='$url' />";
    } else {
        echo _e("It appears that this is a brand new FeedBurner 
account&mdash;please wait a day or two for your first stats to appear. 
If you know than you do have stats, then your server is probably having 
issues communicating with FeedBurner.", 'feed-stats-plugin');
    }
}

function fs_items_chart ($data, $type) {
    // A slightly ugly hack to fix FeedBurner's issues
    $entries = fs_grab_yesterday_entry($data);
    $items = fs_grab_items($entries);

    // A couple of temporary vars to store data in
    $labels = '';
    $total = 0;
    $data = array();

    foreach ($items as $item) {
        $parsed = fs_parse_item($item);

        $labels .= '|' . fs_shorten_label($parsed['title']) . ' (' . $parsed[$type] . ')';
        $total += (int)$parsed[$type];
        array_push($data, (int)$parsed[$type]);
    }

    $labels = substr($labels, 1);
    $datas = 't:';

    if ($total > 0) {
        foreach ($data as $value) {
            $datas .= round(($value / $total) * 100, 1) . ',';
        }   

        $datas = substr($datas, 0, strlen($datas) - 1);
    
        $url = 'http://chart.apis.google.com/chart?cht=p&chs=450x200&chco=E4F2FD,A0BAE9&chd=' . $datas . '&chl=' . $labels;
        echo "<img class='feed-stats-chart' src='$url' />";
    }
}

function fs_items_table ($xml) {
?>
    <table class="feed-stats-data<?php 
    if (version_compare(get_bloginfo('version'), '2.7', '>=')) :?>
        widefat
    <?endif;?>">
        <thead>
            <tr>
                <th><?php _e('Title', 'feed-stats-plugin'); ?></th>
                <th style="width: 50px;"><?php _e('Clicks', 'feed-stats-plugin') ?></th>
                <th style="width: 50px;"><?php _e('Views', 'feed-stats-plugin') ?></th>
            </tr>
        </thead>
        <tbody>
<?php
    // A slightly ugly hack to fix FeedBurner's issues
    $entries = fs_grab_yesterday_entry($xml);
    $items = fs_grab_items($entries);
    $row_num = 0;

    foreach ($items as $item) {
        $parsed = fs_parse_item($item);
        $row_num += 1;
        
        $style_data = "";
        if ( $row_num % 2 != 0 ) $style_data = "feed-stats-alt";
?>
            <tr>
                <td class="feed-stats-left <?php echo $style_data; ?>">
                    <a href="<?php echo $parsed['url']; ?>">
                        <?php echo fs_shorten_title($parsed['title']); ?>
                    </a>
                </td>
                <td class="<?php echo $style_data; ?>">
                    <?php echo $parsed['clicks']; ?>
                </td>
                <td class="<?php echo $style_data; ?>">
                    <?php echo $parsed['views']; ?>
                </td>
            </tr>
<?php
    }
?>
        </tbody>
    </table>
<?php
}

function fs_feed_table ($xml, $have_reach=false) {
?>
    <table class="feed-stats-data <?php 
    if (version_compare(get_bloginfo('version'), '2.7', '>=')) :?>
        widefat
    <?endif;?>">
        <thead>
            <tr>
                <th><?php _e('Date', 'feed-stats-plugin'); ?></th>
                <th style="width: 100px;"><?php _e('Subscribers', 'feed-stats-plugin'); ?></th>
                <th style="width: 50px;"><?php _e('Hits', 'feed-stats-plugin'); ?></th>
            <?php if ($have_reach): ?>
                <th style="width: 50px;"><?php _e('Reach', 'feed-stats-plugin'); ?></th>
            <?php endif; ?>
            </tr>
        </thead>
        <tbody>
<?php
        $entries = fs_grab_entries($xml);
        $table_entries = array_reverse($entries);
        $row_num = 0;           

        foreach ( $table_entries as $entry ) { 
            $data = fs_parse_entry($entry);
            $row_num += 1;

            $style_data = "";
            if ( $row_num % 2 != 0 ) $style_data = "feed-stats-alt";
?>
                    <tr>
                        <td class="feed-stats-left <?php echo $style_data; ?>">
                            <?php echo date(get_option('date_format'), strtotime($data['date'])); ?></td>
                        <td class="<?php echo $style_data; ?>"><?php echo $data['subs'] ?></td>
                        <td class="<?php echo $style_data; ?>"><?php echo $data['hits'] ?></td>
                    <?php if ($have_reach): ?>
                        <td class="<?php echo $style_data; ?>"><?php echo $data['reach'] ?></td>
                    <?php endif; ?>
                    </tr>
<?php
        }
?>
                </tbody>
            </table>
<?php
}

?>
