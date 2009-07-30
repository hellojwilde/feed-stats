<?php 
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

// Set the content-type header so that the browser knows what to do
header('Content-type: text/css');

// Set the expire header so that the browser knows that this should be 
// cached for two years to improve performance
header('Expires: ' . gmdate("D, d M Y H:i:s", time() + 63072000) . ' GMT');
    
// Set variable with the WP version taken from the post header
$ver = $_GET['v'];
$folder = urldecode($_GET['f']);
?>

.feed-stats-container {
    text-align: center;
}
        
#feed-stats-waiting, #feed-stats-result-good, #feed-stats-result-bad {
    display: none;
    padding-left: 20px;
    background: transparent none no-repeat center left;
}

#feed-stats-waiting {
    background-image: url(<?php echo $folder; ?>images/ajax-loader.gif);
}

#feed-stats-result-good {
    background-image: url(<?php echo $folder; ?>images/accept.gif);
}

#feed-stats-result-bad {
    background-image: url(<?php echo $folder; ?>images/exclamation.gif);
}
        
#feed-stats-troubleshooting {
    display: none;
    padding-bottom: 5px;
    margin-left: 20px;
}

#feed-stats-feed {
    width: 300px;
}

#feed-stats-entries {
    width: 50px;
}

<?php if (version_compare($ver, '2.7', '>=')) : ?>
.feed-stats-tabs {
    margin-right: 10px;
}

.feed-stats-tabs ul {
    padding: 0 0 0 17px;
    margin: 0;
}

.feed-stats-tabs ul li {
    float: left;
    list-style-type: none;
    line-height: 28px;
    padding: 0 7px;
    position: relative;
    margin: 0;
    top: 1px;
    z-index: 9999;
}


.feed-stats-tabs .active {
    border: 1px solid #DFDFDF;
    border-bottom: 1px solid #fff;
    font-weight: normal;
    background: #fff;
    padding: 0 6px;
    -moz-border-radius-bottomleft: 0;
    -moz-border-radius-bottomright: 0;
    -moz-border-radius-topleft: 4px;
    -moz-border-radius-topright: 4px;
}

.feed-stats-tabs ul li a {
    padding: 0;
    cursor: pointer;
}

.feed-stats-tabs .active a {
    color: #D54E21 !important;  
}

.feed-stats-tab {
    border: 1px solid #DFDFDF;
    clear: both;
    padding: 10px 0;
    background-color: #fff;
    text-align: center;
    min-height: 220px;
}
<?php else: ?>
.feed-stats-tabs {
    margin-right: 30px;
<?php if (version_compare($ver, '2.5', '>=')) : ?>
    margin-top: 20px !important;
<?php endif; ?>
}

.feed-stats-tabs ul {
    padding: 0 0 0 17px;
    margin: 0;
}

.feed-stats-tabs ul li {
    float: left;
    list-style-type: none;
    border: 1px solid #999;
    padding: 3px;
    margin: 0 3px 0 0;
    position: relative;
    top: 1px;
    z-index: 9999;
}


.feed-stats-tabs .active {
    border-bottom: 1px solid #fff;
    font-weight: bold;
}

.feed-stats-tabs ul li a {
    padding: 0 10px;
    cursor: pointer;
    color: #000 !important;
    border: 0;
}

.feed-stats-tab {
    border-top: 1px solid #999;
    clear: both;
    padding-top: 15px;
}
<?php endif; ?>

.feed-stats-tabs .feed-stats-clear {
    width: 100%;
}

.feed-stats-clear {
    clear: both; 
    margin: 0; 
    padding: 0;
}

.feed-stats-link {
    font-size: 10pt;
}

table.feed-stats-data {
    margin: 20px 0;
<?php if (version_compare($ver, '2.7', '<')) : ?>
    width: 450px;
<?php endif; ?>
}

<?php if (version_compare($ver, '2.7', '<')) : ?>
table.feed-stats-data th {
    text-align: left; 
    border-bottom: 2px solid #cccccc; 
    font-size: 13px;
}

table.feed-stats-data td {
    height: 22px;
}

table.feed-stats-data td.feed-stats-left {
    text-align: left; 
    padding-left: 8px;
}
        
table.feed-stats-data td.feed-stats-alt {
    background: #E6F0FF;
}
<? else: ?>
.feed-stats-table-container {
    padding-right: 10px;
}
<?php endif; ?>

.layout td {
    vertical-align: top;
}

.fs-message, .fs-message p {
    font-size: 16px;
    color: #888;
}

.fs-text {
    width: 190px
}

.feed-stats-left-chart-cell {
    padding-right: 10px;
}

.feed-stats-right-chart-cell {
    padding-left: 10px;
}

.feed-stats-left-table-cell {
    padding-right: 20px;
}

.feed-stats-right-table-cell {
    padding-left: 20px;
}

.fs-icons-credit {
<?php if (version_compare($ver, '2.5', '<')) : ?>
    float: left;
<?php else: ?>
    float: right;
<?php endif; ?>
<?php if (version_compare($ver, '2.7', '>=')) : ?>
    margin-top: 28px;
<?php else: ?>
    margin-top: 35px;
<?php endif; ?>
}
