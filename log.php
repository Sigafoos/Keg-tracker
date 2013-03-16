<?php 
require('functions.php');
require('header.inc.php');
?>
<div data-role="header">
<a href="/" data-type="button" data-theme="b" data-icon="home">Home</a>
<h1>Keg log</h1>
</div>

<div data-role="content">
<table data-role="table" class="ui-responsive table-stroke">
<thead>
<tr>
<th>ID</th>
<th>Date</th>
<th>Action</th>
<!--<th>Comments</th>-->
</tr>
</thead>
<tbody>
<?php
$query = "SELECT keg_id, " . $dbprefix . "keg_sizes.size, date, status, " . $dbprefix . "locations.location, " . $dbprefix . "beers.beer FROM " . $dbprefix . "keg_log INNER JOIN " . $dbprefix . "keg_sizes ON " . $dbprefix . "keg_log.size=" . $dbprefix . "keg_sizes.id INNER JOIN " . $dbprefix . "locations ON " . $dbprefix . "keg_log.location=" . $dbprefix . "locations.id INNER JOIN " . $dbprefix . "beers ON " . $dbprefix . "keg_log.beer=" . $dbprefix . "beers.id ORDER BY date DESC LIMIT 15";
if (!($result = $db->query($query))) echo "<p>Oh my: #" . $db->errno . ": " . $db->error . "</p>\r";
while ($row = $result->fetch_assoc()) {
	echo "<tr>\r";
	echo "<th>" . $row['size'] . " " . $row['keg_id'] . "</th>\r";
	echo "<td>" . date("m/d/y",strtotime($row['date'])) . "</td>\r";
	echo "<td>";
	switch ($row['status']) {
		case 1:
			echo "Emptied/returned";
			break;
		case 2:
			echo "Cleaned";
			break;
		case 3:
		case 4:
			echo "Filled with " . $row['beer'];
			break;
		case 5:
			echo "Delivered to " . $row['location'];
			break;
				case 6:
					echo "Broken";
					break;
				case -1:
					echo "Unknown";
					break;
				default:
					echo "???";// (" . print_r($row) . ")";

			}
			echo "</td>\r";
			//echo "<td></td>"; // comments still coming
			echo "</tr>\r";
		}
		?>
			</tbody>
			</table>
</div>

<?php require('footer.inc.php'); ?>
