<?php if (!defined('WPINC')) die("No outside script access allowed."); ?>

<h2><?php _e('Feed Stats Troubleshooting') ?></h3>
<h3>"Feed Not Found"</h4>
<p><?php _e('This means that you probably mistyped the name of your feed.  Make sure to check its capitalization.') ?></p>
						
<h3>"This feed does not permit Awareness API access."</h4>
<p><?php _e('You haven\'t enabled the FeedBurner Awareness API. Go into your <a href="http://www.feedburner.com/fb/a/myfeeds">FeedBurner account</a>, click on your feed, click on the "Publicize" tab, click on the "Awareness API" button in the sidebar, and then click on the "Activate" button.') ?></p>

<h3>"Cannot access FeedBurner."</h4>
<p><?php _e('There are three possible explanations for this. The first is that one of FeedBurner\'s servers might be down.  If this is the case, try again later.  The second possible reason is that you\'re using this on a development server that does not have access to the internet; if this is true, connect to the internet and try again.  The third possible explanation is that your server has a configuration issue that is preventing it from communicating with the internet.') ?></p>
