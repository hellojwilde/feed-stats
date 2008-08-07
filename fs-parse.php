<?php

function fs_check_errors ($xml) {
	if (preg_match('|rsp stat="fail"|', $xml)) {
		preg_match('|msg="(.*?)"|', $xml, $msg);
		return $msg[1];
	} else {
		return false;
	}
}

function fs_grab_meta ($xml) {
	preg_match('|id="(.*?)"|', $xml, $feed_id);
	preg_match('|uri="(.*?)"|', $xml, $feed_name);
	
	return array($feed_id[1], $feed_name[1]);
}

function fs_grab_entries ($xml) {
	preg_match_all('|<entry(.*?)>|', $xml, $entries, PREG_PATTERN_ORDER);

	return $entries[1];
}

function fs_parse_entry ($xml) {
	preg_match('|reach="(.*?)"|', $xml, $entry_reach);
	preg_match('|date="(.*?)"|', $xml, $entry_date);
	preg_match('|circulation="(.*?)"|', $xml, $entry_subscribers);
	preg_match('|hits="(.*?)"|', $xml, $entry_hits);
	
	return array('date' => $entry_date[1], 
		     'reach' => $entry_reach[1], 
		     'subs' => $entry_subscribers[1], 
		     'hits' => $entry_hits[1]);
}

// The function fs_grab_yesterday_entry is partially based on stripScripts
// from the MooTools JavaScript Framework

// MooTools is copyright © 2006-2008 Valerio Proietti and licensed
// under the MIT License <http://www.opensource.org/licenses/mit-license.php>

function fs_grab_yesterday_entry ($xml) {
	$y = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 1,date("Y")));

	preg_match('/<entry date="' . $y . '"[^>]*>([\s\S]*?)<\/entry>/', 
		$xml, $items);

	return $items[1];
}

function fs_shorten_date ($date) {
	$short = substr($date, 5);
	return str_replace('-', '/', $short);
}

function fs_shorten_label ($label) {
	if (strlen($label) > 17) {
		$label = str_replace(array('”', '“'), array('"', '"'), $label);
		return substr($label, 0, 17) . '...';
	} else {
		return str_replace(array('”', '“'), array('"', '"'), $label);
	}	
}

function fs_shorten_title ($title) {
	if (strlen($title) > 40) {
		return substr($title, 0, 40) . '...';
	} else {
		return $title;
	}
}

// The function fs_grab_items is partially based on stripScripts
// from the MooTools JavaScript Framework

// MooTools is copyright © 2006-2008 Valerio Proietti and licensed
// under the MIT License <http://www.opensource.org/licenses/mit-license.php>

function fs_grab_items ($xml) {
	preg_match_all('/<item([^>]*?)>/', $xml, $items, PREG_PATTERN_ORDER);
	
	return $items[1];
}

function fs_parse_item ($xml) {
	preg_match('|title="(.*?)"|', $xml, $entry_title);
	preg_match('|url="(.*?)"|', $xml, $entry_url);
	preg_match('|clickthroughs="(.*?)"|', $xml, $entry_clicks);
	preg_match('|itemviews="(.*?)"|', $xml, $entry_views);
	
	return array('title' => $entry_title[1], 
		     'url' => $entry_url[1], 
		     'clicks' => $entry_clicks[1], 
		     'views' => $entry_views[1]);
}

function fs_count_yesterday_items ($xml) {
	$entries = fs_grab_yesterday_entry($xml);
	$items = fs_grab_items($entries);

	return count($items);
}
?>
