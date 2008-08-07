<?php

function fs_feed_name ($meta) {
	echo $meta[1];
}

function fs_dashboard_url ($meta) {
	echo "http://www.feedburner.com/fb/a/dashboard?id=" . $meta[0];
}

function fs_stats_set_url ($meta) {
	echo "https://www.feedburner.com/fb/a/analyze/totalstats?id=" . $meta[0];
}

// $type can be 'subs', 'reach', or 'hits'
function fs_feed_chart ($data, $type) {
	$entries = fs_grab_entries($data);

	$chart_data_string = "chd=t:";
	$chart_day_string = "chxl=0:";
	
	$chart_values = array();
	foreach ($entries as $entry) {
		$data = fs_parse_entry($entry);

		$chart_day_string .= "|" . fs_shorten_date($data['date'], -5);
		array_push($chart_values, $data[$type]);
	}
	
	$highest = max($chart_values) * 1.1;

	foreach ($entries as $entry) {
		$data = fs_parse_entry($entry);
		$chart_data_string .= round((($data[$type] / $highest) * 100), 1) . ",";
	}

	$chart_data_string = substr($chart_data_string, 0, strlen($chart_data_string) - 1);
	$url = 'http://chart.apis.google.com/chart?cht=lc&chg=0,20,2,2&chls=3,6,0&chs=450x220&' . $chart_data_string . 
	            '&chco=A0BAE9&chm=B,E4F2FD,0,0,0&chxt=x,y&' . $chart_day_string . "&chxr=1,0," . $highest;

	echo "<img class='feed-stats-chart' src='$url' />";
}

function fs_items_chart ($data, $type) {
	// A slightly ugly hack to fix FeedBurner's issues
	$entries = fs_grab_yesterday_entry($data);
	$items = fs_grab_items($entries);

	// A couple of temporary vars to store data in
	$labels = '';
	$total = 0;
	$data = array();

	foreach ($items as $item) {
		$parsed = fs_parse_item($item);

		$labels .= '|' . fs_shorten_label($parsed['title']) . ' (' . $parsed[$type] . ')';
		$total += (int)$parsed[$type];
		array_push($data, (int)$parsed[$type]);
	}

	$labels = substr($labels, 1);
	$datas = 't:';

	foreach ($data as $value) {
		$datas .= round(($value / $total) * 100, 1) . ',';
	}	

	$datas = substr($datas, 0, strlen($datas) - 1);
	
	$url = 'http://chart.apis.google.com/chart?cht=p&chs=450x200&chco=E4F2FD,A0BAE9&chd=' . $datas . '&chl=' . $labels;
	echo "<img class='feed-stats-chart' src='$url' />";
}

function fs_items_table ($xml) {
?>
	<table class="feed-stats-data">
		<tr>
			<th>Title</th>
			<th style="width: 50px;">Clicks</th>
			<th style="width: 50px;">Views</th>
		</tr>
<?php
	// A slightly ugly hack to fix FeedBurner's issues
	$entries = fs_grab_yesterday_entry($xml);
	$items = fs_grab_items($entries);
	$row_num = 0;

	foreach ($items as $item) {
		$parsed = fs_parse_item($item);
		$row_num += 1;
		
		$style_data = "";
		if ( $row_num % 2 != 0 ) $style_data = "feed-stats-alt";
?>
		<tr>
			<td class="feed-stats-left <?php echo $style_data; ?>">
				<a href="<?php echo $parsed['url']; ?>">
					<?php echo fs_shorten_title($parsed['title']); ?>
				</a>
			</td>
			<td class="<?php echo $style_data; ?>">
				<?php echo $parsed['clicks']; ?>
			</td>
			<td class="<?php echo $style_data; ?>">
				<?php echo $parsed['views']; ?>
			</td>
		</tr>
<?php
	}
?>
	</table>
<?php
}

function fs_feed_table ($xml) {
?>
	<table class="feed-stats-data">
		<tr>
			<th>Date</th>
			<th style="width: 100px;">Subscribers</th>
			<th style="width: 50px;">Hits</th>
			<th style="width: 50px;">Reach</th>
		</tr>
<?php
		$entries = fs_grab_entries($xml);
		$table_entries = array_reverse($entries);
		$row_num = 0;			

		foreach ( $table_entries as $entry ) { 
			$data = fs_parse_entry($entry);
			$row_num += 1;

			$style_data = "";
			if ( $row_num % 2 != 0 ) $style_data = "feed-stats-alt";
?>
				<tr>
					<td class="feed-stats-left <?php echo $style_data; ?>"><?php echo $data['date']; ?></td>
					<td class="<?php echo $style_data; ?>"><?php echo $data['subs'] ?></td>
					<td class="<?php echo $style_data; ?>"><?php echo $data['hits'] ?></td>
					<td class="<?php echo $style_data; ?>"><?php echo $data['reach'] ?></td>
				</tr>
<?php
		}
?>
			</table>
<?php
}

?>