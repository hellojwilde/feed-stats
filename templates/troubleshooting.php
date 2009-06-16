<?php if (!defined('WPINC')) die("No outside script access allowed."); 

/*
	Copyright (c) 2008 - 2009 Jonathan Wilde

	This file is part of the Feed Stats Plugin for WordPress.

    The Feed Stats Plugin for WordPress is free software: you can redistribute 
    it and/or modify it under the terms of the GNU General Public License as 
    published by the Free Software Foundation, either version 2 of the License, 
    or (at your option) any later version.

    The Feed Stats Plugin for WordPress is distributed in the hope that it will 
    be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with the Feed Stats Plugin for WordPress.  If not, see 
    <http://www.gnu.org/licenses/>.
*/?>

<h2><?php _e('Feed Stats Troubleshooting') ?></h3>
<h3><?php _e('Feed not found.') ?></h4>
<p><?php _e('This means that you probably mistyped the name of your feed.  Make sure to check its capitalization.') ?></p>
						
<h3><?php _e('This feed does not permit Awareness API access.') ?></h4>
<p><?php _e('You haven\'t enabled the FeedBurner Awareness API. Go into your FeedBurner account, click on your feed, click on the "Publicize" tab, click on the "Awareness API" button in the sidebar, and then click on the "Activate" button.') ?></p>

<h3><?php _e('Cannot access FeedBurner.')</h4>
<p><?php _e('There are three possible explanations for this. The first is that one of FeedBurner\'s servers might be down.  If this is the case, try again later.  The second possible reason is that you\'re using this on a development server that does not have access to the internet; if this is true, connect to the internet and try again.  The third possible explanation is that your server has a configuration issue that is preventing it from communicating with the internet.') ?></p>
