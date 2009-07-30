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

<script type="text/javascript" src="<?php plugin_folder() ?>js/tabs.js"></script>
<link type="text/css" href="<?php plugin_folder() ?>style.css.php?v=<?php echo get_bloginfo('version'); ?>&f=<?php echo urlencode(get_plugin_folder()); ?>" rel="stylesheet" media="all"></script>

<!--[if lte IE 7]>
<style type="text/css">
    .feed-stats-tab {
        position:relative;
        top: -20px;
        padding-top: 7px;
    }
</style>
<![endif]-->
