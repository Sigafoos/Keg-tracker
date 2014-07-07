<?php
require('functions.php');
require('header.inc.php');
?>
<div data-role="header">
<a href="/" data-type="button" data-theme="b" data-icon="home">Home</a>

<?php

$_GET['report'] = 1; // hard coding til we have more

if (!is_numeric($_GET['report'])) {
	?>
		<h1>Choose report</h1>
		</div>
		<div data-role="content">
		<p>Um maybe add stuff here</p>
		</div>
		<?php
} else {
	?>
		<?php
		switch($_GET['report']) {
			case 1:
				echo "<h1>Forgotten kegs</h1>\r";
				$query = "SELECT a.keg_id, a.size, " . $dbprefix . "keg_sizes.size, a.date, " . $dbprefix . "keg_statuses.status, " . $dbprefix . "locations.location, " . $dbprefix . "beers.beer, " . $dbprefix . "keg_warnings.warning";
				$query .= " FROM " . $dbprefix . "keg_log a";
				$query .= " JOIN (SELECT keg_id, size, max(date) AS date FROM " . $dbprefix . "keg_log GROUP BY keg_id, size) b"; // 6 = broken
				$query .= " ON a.keg_id=b.keg_id AND a.size=b.size AND a.date=b.date";
				$query .= " INNER JOIN " . $dbprefix . "keg_sizes ON a.size=" . $dbprefix . "keg_sizes.id";
				$query .= " INNER JOIN " . $dbprefix . "keg_statuses on a.status=" . $dbprefix . "keg_statuses.id";
				$query .= " INNER JOIN " . $dbprefix . "locations ON a.location=" . $dbprefix . "locations.id";
				$query .= " INNER JOIN " . $dbprefix . "beers ON a.beer=" . $dbprefix . "beers.id";
				$query .= " INNER JOIN " . $dbprefix . "keg_warnings ON a.warning=" . $dbprefix . "keg_warnings.id";
				$query .= " WHERE b.date < NOW() - INTERVAL 45 DAY AND a.status != 6 ORDER BY a.date ASC";
				if (!($result = $db->query($query))) {
					echo "<p>Error getting unused kegs: #" . $db->errno . ": " . $db->error . "</p>\r";
				}

				while ($row = $result->fetch_assoc()) $kegs[] = $row;
				break;

			default:
				echo "<h1>Um</h1>\r</div>\r";
				echo "<div data-role=\"content\">\r<p>How did you get here?</p>\r</div>\r";
				die();
				break; // probably extraneous
		}
	?>
		</div>

		<div data-role="content">
		<table data-role="table" class="ui-responsive table-stroke">
		<thead>
		<tr>
		<th>ID</th>
		<th>Last edited</th>
		<th>Status</th>
		<th>Location</th>
		<th>Beer</th>
		<th>Warning</th>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach ($kegs as $keg) {
			echo "<tr>\r";
			echo "<th>" . $keg['size'] . " " . $keg['keg_id']. "</td>\r";
			echo "<td>" . date("m/d/y",strtotime($keg['date'])) . "</td>\r";
			echo "<td>" . $keg['status'] . "</td>\r";
			echo "<td>" . $keg['location'] . "</td>\r";
			echo "<td>" . $keg['beer'] . "</td>\r";
			echo "<td>" . $keg['warning'] . "</td>\r";
			echo "</tr>\r";
		}
	?>
		</tbody>
		</table>

		</div>
		<?php
}

require('footer.inc.php');
?>
