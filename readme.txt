=== FeedBurner Feed Stats ===
Contributors: speedbreeze
Tags: feedburner, stats, statistics, comments, hits, subscribers
Requires at least: 2.3.3
Tested up to: 2.6
Stable tag: 1.0

Finally, you can see your FeedBurner feed stats from inside your WordPress admin panel!

== Description ==

**FeedBurner Feed Stats** is a plugin that allows you to view your FeedBurner feed stats from 
inside of the WordPress admin interface.  Stats for your feed can be viewed from the "Feed Stats"
page in the "Dashboard" section of WordPress.

Currently, this plugin allows you to view the following types of statistics:

* Subscribers
* Hits
* Reach (see the FAQ section for more information about this)
* Item Clickthroughs
* Item Views

*Note:* This is a third-party plugin for viewing data from the FeedBurner web service. I am *not* 
in any way affiliated with FeedBurner.

== Installation ==

Installing this plugin into your copy of WordPress and configuring it is really a three 
step process:

1. Download the plugin and extract the feed-stats folder into a temporary location. Copy this 
folder into your wp-content folder in your installation of WordPress.
1. In the "Plugins" page of the "Plugins" section of your admin interface, click on the 
"Activate" button next to this plugin's name.
1. Under the "Feed Stats Settings" page of the "Settings" section of your admin interface, 
type in the name of your feed on FeedBurner. Click the "Save Settings Button".

You're done! You can now see the stats of your feed in the "Feed Stats" page of the "Dashboard" 
section of WordPress' admin interface.

== Frequently Asked Questions ==

= What are "Reach" stats? =

According to FeedBurner, "Reach" stats describe how many of your subscribers actively click on 
and view items in your feed.  For more information on this, you can view the 
["What is Reach?"](http://www.google.com/support/feedburner/bin/answer.py?answer=78954) 
article in the FeedBurner Help Center.

= How do I know what the name of my FeedBurner feed is? =

Look at the link to your FeedBurner Feed. It's that link, but without the 
http://feeds.feedburner.com/ part. For instance, the feed for SpeedBreeze is 
http://feeds.feedburner.com/Speedbreeze; the name of the feed is just the "Speedbreeze" part at 
the end.

= What's the "Awareness API"? =

The Awareness API is the system that FeedBurner created to allow applications (like this plugin)
to access your statistics.  You *will* have to manually enable it in the FeedBurner
admin panel of your account in order to use this plugin (it's turned off by default).  More 
information about enabling it is below.

= How do I enable the Awareness API? =

To enable the Awareness API, login to your FeedBurner account, click on "My Feeds" and select 
your feed from the list. Click on the "Publicize" tab and then click on the "Awareness API" link
under the services column. Finally, click on the "Activate" button.

= How are the charts displayed? =

The charts are displayed using the [Google Charts API](http://code.google.com/apis/chart/). 
Additional chart making software or graphics software (like GD) doesn't have to be 
installed on your server to run this plugin.

= How many days of stats does the plugin display? =

By default, 12 days of hits, subscribers, and reach stats are shown.  I've found that 12 days of 
stats displays well in the charts in the dashboard.  If you wish to change this, the number of 
days is configurable from the "Feed Stats" page in the "Settings" section of the WordPress
admin interface.   

== Screenshots ==

You can view screenshots of this plugin at the 
[official page](http://www.speedbreeze.com/2008/02/22/feed-stats-wordpress-plugin/) 
for the plugin on my website.

