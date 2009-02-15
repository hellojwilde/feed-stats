<h2><?php _e('Feed Stats Settings'); ?></h2>
<form action="" method="post" id="feed-stats">
	<?php wp_nonce_field('feed-stats-edit_options') ?>			
	<table class="form-table optiontable">
		<tr>
			<th scope="row" valign="top"><?php _e('FeedBurner Feed URL'); ?></th>
			<td>
				<script type="text/javascript">
					var help = "<?php _e('What does this mean?') ?>";
				</script>
				<input type="text" name="feed-stats-feed" id="feed-stats-feed" class="fs-text" value="<?php echo get_option('feedburner_feed_stats_name'); ?>" />
				<input type="button" class="button" name="feed-stats-tester" id="feed-stats-tester" value="Test" onclick="testURL('<?php ajax_test_url(); ?>')" style="display: none" />
				
				<span id="feed-stats-waiting"><img src="<?php plugin_folder(); ?>images/ajax-loader.gif" alt="testing-icon" />Testing...</span>
				<span id="feed-stats-result-good"></span>
				<span id="feed-stats-result-bad"></span>
				
				<br /><?php _e('The entire URL of your feed (eg. <code>http://feeds.feedburner.com/speedbreeze</code>).') ?>						
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top"><?php _e('Number of Days to Show') ?></th>
			<td>
				<input type="text" name="feed-stats-entries" id="feed-stats-entries" class="fs-text" value="<?php echo get_option('feedburner_feed_stats_entries'); ?>" />
			</td>
		</tr>
	</table>
	<p class="fs-icons-credit">
		<?php _e('Icons by') ?> <a href="http://www.famfamfam.com/">FamFamFam</a>.
	</p>
	<p class="submit">
		<input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	</p>
	<p class="feed-stats-clear"></p>
</form>
	
<script type="text/javascript" src="<?php plugin_folder() ?>js/test.js"></script>
