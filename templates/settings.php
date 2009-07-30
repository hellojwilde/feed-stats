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
*/?>

<?php if (!empty($_POST)): ?>
    <div id="message" class="updated fade">
        <p><strong><?php _e('Options saved successfully.', 'feed-stats-plugin') ?></strong></p>
    </div>
<?php endif; ?>
    

<h2><?php _e('Feed Stats Settings', 'feed-stats-plugin'); ?></h2>
<form action="" method="post" id="feed-stats">
    <?php wp_nonce_field('feed-stats-edit_options') ?>          
    <table class="form-table optiontable">
        <tr>
            <th scope="row" valign="top"><?php _e('FeedBurner Feed URL', 'feed-stats-plugin'); ?></th>
            <td>
                <script type="text/javascript">
                    var help = "<?php _e('What does this mean?', 'feed-stats-plugin') ?>";
                </script>
                <input type="text" name="feed-stats-feed" id="feed-stats-feed" class="fs-text" value="<?php echo get_option('feedburner_feed_stats_name'); ?>" />
                <input type="button" class="button" name="feed-stats-tester" id="feed-stats-tester" value="Test" onclick="testURL('<?php ajax_test_url(); ?>')" style="display: none" />
                
                <span id="feed-stats-waiting"><?php _e('Testing...', 'feed-stats-plugin')  ?></span>
                <span id="feed-stats-result-good"></span>
                <span id="feed-stats-result-bad"></span>
                
                <br /><?php _e('The entire URL of your feed (eg. <code>http://feeds.feedburner.com/speedbreeze</code>).', 'feed-stats-plugin') ?>                       
            </td>
        </tr>
        <tr>
            <th scope="row" valign="top"><?php _e('Number of Days to Show', 'feed-stats-plugin') ?></th>
            <td>
                <input type="text" name="feed-stats-entries" id="feed-stats-entries" class="fs-text" value="<?php $q = get_option('feedburner_feed_stats_entries'); echo ($q !== '' && $q !== false) ? $q : 10; ?>" />
            </td>
        </tr>
    </table>
    <p class="fs-icons-credit">
        <?php _e('Icons by', 'feed-stats-plugin') ?> <a href="http://www.famfamfam.com/">FamFamFam</a>.
    </p>
    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes', 'feed-stats-plugin') ?>" />
    </p>
    <p class="feed-stats-clear"></p>
</form>
    
<script type="text/javascript" src="<?php plugin_folder() ?>js/test.js"></script>
