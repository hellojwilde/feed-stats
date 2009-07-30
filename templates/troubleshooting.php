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
?>

<h2>
    <?php printf(__('<a href="%s">Feed Stats Settings</a> &raquo; 
Troubleshooting', 'feed-stats-plugin'), 
"options-general.php?page=feed-stats-options") ?>
</h2>

<p>
    <?php printf(__("I've compiled this page of information to help you 
get past those pesky error messages.  If you have any questions that 
aren't answered on this page, feel free to send a message to this 
plugin's <a href='%s'>mailing list</a>.", 
'feed-stats-plugin'), SUPPORT_URL); ?>
</p>

<?php
    // Compile the list of error messages that we're going to display
    $types = array(
        'This feed does not permit Awareness API access',
        'Feed Not Found',
        'Cannot access FeedBurner',
        'Feedburner issues', 
        'Unknown'
    );
    
    // Loop though all of the errors and display the title and messages
    foreach ($types as $type):
        // Get the error
        $error = fs_translatable_error($type);
?>

<h3><?php echo $error['title']; ?></h3>
<p><?php echo $error['message']; ?></p>

<?php endforeach; ?>
