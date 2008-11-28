<?php 
	// Set the content-type header so that the browser knows what to do
	header('Content-type: text/css');
	
	// Set variable with the WP version taken from the post header
	$ver = $_GET['v'];
?>

.feed-stats-container {
	text-align: center;
}
		
#feed-stats-waiting, #feed-stats-result-good, #feed-stats-result-bad {
	display: none; 
}
		
#feed-stats-waiting img, #feed-stats-result-good img, #feed-stats-result-bad img {
	margin: 0 5px;
	position: relative;
	top: 3px;
}
		
#feed-stats-troubleshooting {
	display: none;
	padding-bottom: 5px;
	margin-left: 20px;
}

.feed-stats-tabs {
	margin-right: 30px;
<?php if (version_compare($ver, '2.5', '>=')) : ?>
	margin-top: 20px !important;
<?php endif; ?>
}

.feed-stats-tabs ul {
	padding: 0 0 0 17px;
	margin: 0;
<?php if (strpos(' '.$ver, '2.7') == 1) : ?>
	margin-bottom: 10px;
<?php endif; ?>
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
}

.feed-stats-tab {
	border-top: 1px solid #999;
	clear:both;
	padding-top: 15px;
}

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
	width: 450px;
}

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

.layout td {
	vertical-align: top;
}

.fs-message, .fs-message p {
	font-size: 16px;
}

.fs-text {
	width: 190px
}

.fs-icons-credit {
<?php if (version_compare($ver, '2.5', '<')) : ?>
	float: left;
<?php else: ?>
	float: right;
<?php endif; ?>
<?php if (strpos(' '.$ver, '2.7') == 1) : ?>
	margin-top: 27px;
<?php else: ?>
	margin-top: 35px;
<?php endif; ?>
}
