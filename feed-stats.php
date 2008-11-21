<?php
/*
	Plugin Name: FeedBurner Feed Stats
	Plugin URI: http://www.speedbreeze.com/2008/02/22/feed-stats-wordpress-plugin/
	Description: A quick and easy way to view the stats for your FeedBurner feed. After activating the plugin, make sure to  <a href="./options-general.php?page=feed-stats">configure</a> which feed you want to track.
	Version: 1.0.
	Author: Jonathan Wilde
	Author URI: http://www.speedbreeze.com
*/

/*
	Copyright (c) 2008 Jonathan Wilde

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_action('admin_menu', 'add_feed_stats_pages');
add_action('admin_head', 'feed_stats_classes');

function add_feed_stats_pages() {
	add_submenu_page('index.php', 'Feed Stats', 'Feed Stats', 'manage_options', 'feed-stats', 'display_feed_stats');
	add_options_page('Feed Stats', 'Feed Stats', 'manage_options', 'feed-stats', 'display_feed_options');
}

function feed_stats_classes() {
?>
	<script type="text/javascript" src="<?php plugin_folder() ?>js/tabs.js"></script>
	<link type="text/css" href="<?php plugin_folder() ?>style.css" rel="stylesheet" media="all"></script>

	<!--[if lte IE 7]>
	<style type="text/css">
		.total-tab-list {
			margin: 0 !important;
			padding: 0 !important;
		}

		#hits-tab, #clicks-tab {
			margin-left: 11px;
		}

		.feed-stats-tabs div {
			margin-top: 10px !important;
		}
	</style>
	<![endif]-->
<?php
	if (version_compare(get_bloginfo('version'), '2.5', '>=')) {
?>
	<style type="text/css">
		.feed-stats-tabs {
			margin-top: 20px !important;
		}
		
		/* Borrowed from wp-admin.css v. 2.5.1, copyright 2008 
		   Automattic, Inc. Licensed under GPL. */
		.info-box {
			background:#F8F8F8 none repeat scroll 0 0;
			border:5px solid #DDDDDD;
			display:none;
			height:300px;
			left:50%;
			margin-left:-225px;
			padding:15px 15px 10px;
			position:absolute;
			top:100px;
			width:450px;
			z-index:1000;
		}

		.info-box .submit {
			bottom:15px;
			padding:15px 0 0;
			position:absolute;
			width:450px;
		}

		.info-box-title {
			line-height:2;
			margin:0;
			padding:0 7px;
		}
	</style>
<?php
	}
}

function display_feed_stats() {
	require_once (ABSPATH . WPINC . '/rss.php');

	require_once (dirname(__FILE__) . '/fs-comm.php');
	require_once (dirname(__FILE__) . '/fs-parse.php');
	require_once (dirname(__FILE__) . '/fs-render.php');	

	// Grab options from the DB
	$days = get_option('feedburner_feed_stats_entries');
	$name = get_option('feedburner_feed_stats_name');

	// A security fix for injection attacks
	$name = sanitize_url($name);
	$name = str_replace('http://', '', $name);

	// Load data from FeedBurner
	$feed = fs_load_feed_data($name, $days);
	$items = fs_load_item_data($name, $days);

	// Check to see if FeedBurner returned any errors
	$feed_errors = fs_check_errors($feed);
	$item_errors = fs_check_errors($items);
	
	// Render the data into a pretty set of charts
	if ($feed_errors == false) {
		$meta = fs_grab_meta($feed);
			
?>
	<div class="wrap">
		<h2>
			<?php _e('Feed Stats:') ?> <?php fs_feed_name($meta); ?>
			<span class="feed-stats-link">
				&nbsp;(<a href="<?php fs_dashboard_url($meta); ?>"><?php _e('FeedBurner Dashboard'); ?></a>)
			<span>
		</h2>
		
		<table class="layout" width="100%"><tr>
			<td width="50%">
				<h3>Total Hits &amp; Subscribers</h3>
				<div id="total-tab" class="feed-stats-tabs">
					<ul class="total-tab-list">
						<li id="hits-tab" onclick="selectTab('total-tab', 'hits');">
							<span><?php _e('Hits') ?></span></a></li>
						<li id="subs-tab" onclick="selectTab('total-tab', 'subs');">
							<span><?php _e('Subscribers') ?></span></a></li>
						<?php if ($item_errors == false): ?>
						<li id="reach-tab" onclick="selectTab('total-tab', 'reach');">
							<span><?php _e('Reach') ?></span></a></li>
						<?php endif; ?>
						<li class="fs-clr-tb" 
						    style="float: none; padding: 0;
							   clear: both; margin: -21px 0 0 0 !important;
							   border: 0;"></li>
					</ul>
					<div id="hits">
						<?php fs_feed_chart($feed, 'hits'); ?>
					</div>
					<div id="subs">
						<?php fs_feed_chart($feed, 'subs'); ?>
					</div>
					<?php if ($item_errors == false): ?>
					<div id="reach">
						<?php fs_feed_chart($feed, 'reach'); ?>
					</div>
					<?php endif; ?>

					<script type="text/javascript">
						selectTab('total-tab', 'hits');
					</script>
				</div>
			</td>
			<td width="50%">
				<h3><?php _e('Yesterday\'s Viewed &amp; Clicked Feed Items') ?></h3>
<?php
		if ($item_errors == false) {
			$item_count = fs_count_yesterday_items($items);

			if ($item_count > 0) {
?>
				<div id="yest-tab" class="feed-stats-tabs">
					<ul class="total-tab-list">
						<li id="clicks-tab" onclick="selectTab('yest-tab', 'clicks');">
							<span><?php _e('Clicks') ?></span></a></li>
						<li id="views-tab" onclick="selectTab('yest-tab', 'views');">
							<span><?php _e('Views') ?></span></a></li>
						<li class="fs-clr-tb" 
						    style="float: none; padding: 0;
							   clear: both; margin: -21px 0 0 0 !important;
							   border: 0;"></li>
					</ul>
					<div id="clicks">
						<?php fs_items_chart($items, 'clicks'); ?>
					</div>
					<div id="views">
						<?php fs_items_chart($items, 'views'); ?>
					</div>

					<script type="text/javascript">
						selectTab('yest-tab', 'clicks');
					</script>
				</div>
<?php
			} else {
?>
				<div style="font-size: 16px;">
					<p><?php _e('There weren\'t any items that were clicked on in your feed yesterday. 
					   If you just turned on item stats, wait a day or two for information to start showing up.') ?></p>
				</div>
<?php
			}
		} else {
?>
				<div style="font-size: 16px;">
					<p><?php _e('It appears that you don\'t have Item Stats enabled in your 
					   FeedBurner account.  If it was enabled, you would be able to 
					   view information about clickthroughs on individual feed items.') ?></p>
					<p><?php _e('To enable them, you can go to') ?> <a href="<?php fs_stats_set_url($meta) ?>"><?php _e('FeedBurner Stats settings') ?></a>.</p>
				</div>
<?php
		}
?>
			</td>
		</tr><tr>
			<td>
				<?php fs_feed_table($feed); ?>
			</td>
			<td>
				<?php if ($item_errors == false && $item_count > 0) fs_items_table($items); ?>
			</td>
		</tr></table>
	</div>
<?php
	} else {
?>
	<div id="message" class="error fade">
		<p><strong><?php echo $feed_errors ?>.</strong></p>
	</div>
<?php
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
	<?php if (!empty($_POST)): ?>
	<div id="message" class="updated fade">
		<p><strong><?php _e('Options saved successfully.') ?></strong></p>
	</div>
	<?php endif; ?>

	<div class="wrap">
		<h2><?php _e('Feed Stats Configuration'); ?></h2>
		<form action="" method="post" id="feed-stats">
			<?php wp_nonce_field('feed-stats-edit_options') ?>			
			<table class="form-table">
				<tr>
					<th scope="row" valign="top"><?php _e('FeedBurner Feed Name'); ?></th>
					<td>
						<script type="text/javascript">
							var help = "<?php _e('What Does This Mean?') ?>";
						</script>
						<input type="text" name="feed-stats-feed" id="feed-stats-feed" style="width: 190px" value="<?php echo get_option('feedburner_feed_stats_name'); ?>" />
						<input type="button" class="button" name="feed-stats-tester" id="feed-stats-tester" value="Test" onclick="testURL('<?php ajax_test_url(); ?>', '<?php echo ABSPATH ?>', '<?php echo WPINC ?>')" style="display: none" />
						<span id="feed-stats-waiting"><img src="<?php plugin_folder(); ?>images/ajax-loader.gif" alt="testing-icon" />Testing...</span>
						<span id="feed-stats-result-good" title="<img src='<?php plugin_folder(); ?>images/accept.gif' alt='good-icon' />"></span>
						<span id="feed-stats-result-bad" title="<img src='<?php plugin_folder(); ?>images/exclamation.gif' alt='bad-icon' />"></span>
						<br /><?php _e('The part of your feed URL that comes after "http://feeds.feedburner.com/".') ?>						

						<div id="feed-stats-ts-box" class="info-box">
							<h3 class="info-box-title"><?php _e('Troubleshooting') ?></h3>
							<h4 class="feed-stats-nmb">"Feed Not Found"</h4>
							<p class="feed-stats-nmt"><?php _e('This means that you probably mistyped the name of your feed.  Make sure to check its capitalization.') ?></p>
							
							<h4 class="feed-stats-nmb">"This feed does not permit Awareness API access."</h4>
							<p class="feed-stats-nmt"><?php _e('You haven\'t enabled the FeedBurner Awareness API. Go into your <a href="http://www.feedburner.com/fb/a/myfeeds">FeedBurner account</a>, click on your feed, click on the "Publicize" tab, click on the "Awareness API" button in the sidebar, and then click on the "Activate" button.') ?></p>

							<h4 class="feed-stats-nmb">"The FeedBurner server is not available."</h4>
							<p class="feed-stats-nmt"><?php _e('One of FeedBurner\'s servers must be down.  Try again later.  It\'s also possible that you\'re using this on a development server that does not have access to the internet.') ?></p>

							<div class="submit">
								<button class="button" onclick="hideTroubleshooting(); return false;"><?php _e('Close') ?></button>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<th scope="row" valign="top"><?php _e('Number of Days to Show') ?></th>
					<td>
						<input type="text" name="feed-stats-entries" id="feed-stats-feed" style="width: 190px" value="<?php echo get_option('feedburner_feed_stats_entries'); ?>" />
					</td>
				</tr>
			</table>
			<p style="float: right; margin-top: 35px">
				<?php _e('Icons by') ?> <a href="http://www.famfamfam.com/">FamFamFam</a>.
			</p>
			<p class="submit">
				<input type="submit" name="submit" id="submit" value="<?php _e('Save Changes') ?>" />
			</p>
			<p style="clear:both"></p>
		</form>
		
		<script type="text/javascript" src="<?php plugin_folder() ?>js/test.js"></script>
	</div>
<?php
}

function ajax_test_url() {
	echo get_plugin_folder() . "awapi-test.php";
}

function plugin_folder() {
	echo get_plugin_folder();
}

function get_plugin_folder() {
	$match = '|plugins/(.*?)feed-stats.php|';	
	preg_match($match, __FILE__, $dir);
	
	return get_bloginfo("wpurl") . "/wp-content/plugins/" . $dir[1];
}
?>
