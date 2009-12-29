=== Feed Stats for WordPress ===
Contributors: speedbreeze
Tags: feedburner, stats, feeds, hits, subscribers, reach
Requires at least: 2.3.3
Tested up to: 2.9
Stable tag: 1.0.6

A plugin that seamlessly integrates detailed FeedBurner stats into your
WordPress blog's dashboard.

== Description ==

**Feed Stats for WordPress** is a plugin that allows you to view your 
FeedBurner feed stats from inside of the WordPress admin interface.  
Stats for your feed can be viewed from the "Feed Stats" page in the 
"Dashboard" section of WordPress.

Currently, this plugin allows you to view the following types of 
statistics:

* Subscribers
* Hits
* Reach (see the FAQ section for more information about this)
* Item Clickthroughs
* Item Views

**Note:** This is a third-party plugin for viewing data from the 
FeedBurner web service. I am *not* in any way affiliated with Google.

= Questions? Comments? Found a Bug? =
Feel free to ask these questions, get help, find out about new releases, 
and more at this plugin's 
[mailing list](http://www.speedbreeze.com/feed-stats/product/support).

== Installation ==

Installing this plugin into your copy of WordPress and configuring it is 
a three-step process:

1. Download the plugin and extract the feed-stats folder into a 
temporary location. Copy this folder into your wp-content folder in your 
installation of WordPress.
1. In the "Plugins" page of the "Plugins" section of the admin 
interface, click on the "Activate" button next to this plugin's name.
1. Under the "Feed Stats" page of the "Settings" section of the admin 
interface, type in the full URL of your feed on FeedBurner. Click the "Save 
Settings" button.

You're done! You can now see the stats of your feed in the "Feed Stats" 
page of the "Dashboard" section of WordPress' admin interface.

== Frequently Asked Questions ==

= What are "Reach" stats? =

According to FeedBurner, "Reach" stats describe how many of your 
subscribers actively click on and view items in your feed.  For more 
information on this, you can view the 
["What is Reach?"](http://www.google.com/support/feedburner/bin/answer.py?answer=78954) 
article in the FeedBurner Help Center.

= What's the "Awareness API"? =

The Awareness API is the system that FeedBurner created to allow 
applications (like this plugin) to access your statistics.  You *will* 
have to manually enable it in the admin panel of your FeedBurner account 
in order to use this plugin (it's turned off by default).  More 
information about enabling it is below.

= How do I enable the Awareness API? =

To enable the Awareness API, login to your FeedBurner account, click on 
"My Feeds" and select the feed you want to enable the API for from the 
list. Click on the "Publicize" tab and then click on the "Awareness API" 
link under the services column. Finally, click on the "Activate" button.

= How often is information retrieved from FeedBurner? =

The data about your feeds is loaded from FeedBurner only when you open 
the "Feed Stats" page.  This plugin does **not** continuously poll 
FeedBurner.

= How are the charts displayed? =

The charts are displayed using the [Google Charts API](http://code.google.com/apis/chart/). 
Additional chart making software or graphics software (like GD) doesn't 
have to be installed on your server to run this plugin.

= How many days of stats does the plugin display? =

By default, 10 days of hits, subscribers, and reach stats are shown.  
I've found that 10 days of stats displays well in the charts in the 
dashboard.  If you wish to change this, the number of days is 
configurable from the "Feed Stats" page in the "Settings" section of the 
WordPress admin interface.

= What browsers will this plugin work in? =

As of version 1.0.4, this plugin has been tested in (and known to work
well in) the following set of browsers:

* Internet Explorer 6
* Opera 9.5
* Safari 3
* Firefox 3
* Google Chrome beta

== Screenshots ==

You can view screenshots of this plugin at the 
[official page](http://www.speedbreeze.com/feed-stats/product/screenshots) 
for the plugin on my website.

== Changelog ==

= 1.0.6 =
* Removed the scary Google FeedBurner error messages--Google fixed their 
  Awareness API issues.
* Fixed the bug (reported by Bjoern Buerstinghaus) where both the Feed 
  Stats options and Feed Stats stats pages' navigation menu items would 
  be highlighted at the same time when either of those pages were viewed.
* Made the plugin fully translatable.
* Improved code cleanliness and documentation.
* Rewrote parts of the code that loads information from FeedBurner to
  improve performance; it now makes only two data requests (1.0.5 made 
  four) to FeedBurner when the stats page is loaded.
* Set cache headers on the CSS file to improve performance.
* Improved compatibility with Windows servers.
* Formatted the dates in the tables below the charts according to the 
  date format option set in WordPress to increase readability.
* Added Bjoern Buerstinghaus's fantastic translation of the plugin into
  German.
* Improved initial user experience by improving the messages to the user.
* Improved code quality by writing tests (using the SimpleTest PHP
  toolkit) to ensure that the code works properly.

= 1.0.5 =
* A new HTTP engine (Troy Wolf's `class_http`) that should offer more 
  consistency across WordPress versions.
* FeedBurner vs. Google FeedBurner detection code to ease the transition 
  to Google.
* Improved error messages.
* Templated CSS (to improve consistency across versions of WordPress).
* CSS updates to make the plugin feel more at home in WordPress 2.7+.
* More robust detection of the availability of reach stats.
* The feed tester is now run in the domain of the WordPress admin 
  panels.  This should allow it to run under more configurations of 
  WordPress and improve security.
* External script access of internal PHP scripts is now blocked (using 
  a similar method to CodeIgniter).
* Improved internal documentation.
* Proper ChangeLog documentation.
* A proper GPL COPYING file has been added.
