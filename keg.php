<?php 
require('functions.php');
require('header.inc.php');
?>
<div data-role="header">
<a href="/" data-type="button" data-theme="b" data-icon="home">Home</a>
<h1>Choose keg</h1>
</div>

<?php
if (!$_GET['id']) {
	$query = "SELECT id, size FROM " . $dbprefix . "keg_sizes ORDER BY id";
	if (!($result = $db->query($query))) echo "<p>Oh my: #" . $db->errno . ": " . $db->error . "</p>\r";
	while ($row = $result->fetch_assoc()) $sizes[$row['id']] = $row['size'];

	$query = "SELECT id FROM " . $dbprefix . "kegs WHERE size=1";
	if (!($result = $db->query($query))) echo "<p>Oh my: #" . $db->errno . ": " . $db->error . "</p>\r";
	while ($row = $result->fetch_assoc()) $kegs[] = $row['id'];
	?>
		<div data-role="content">
		<form method="get" action="keg.php">
		<label for="size">size</label>
		<select name="size" id="size">
		<?php foreach ($sizes as $id=>$size) {
			echo "<option value=\"" . $id . "\"";
			if ($id == 1) echo " selected=\"selected\"";
			echo ">" . $size . "</option>\r\n"; 
		}?>
		</select>

			<label for="id" id="id">id</label>
			<input type="number" pattern="[0-9]*" name="id" id="id" />


		<button type="submit" data-inline="true" data-theme="b">Submit</button>
		</form>
		</div>

		<?php
} else {
	$query = "SELECT id, status, beer, location, size FROM " . $dbprefix . "kegs WHERE id=" . $_GET['id'] . "  AND size=" . $_GET['size'];
	if (!($result = $db->query($query))) echo "<p>Oh my: #" . $db->errno . ": " . $db->error . "</p>\r";
	$keg = new Keg($result->fetch_assoc());
	?>
		<div id="keginfo" data-role="content">

		<h2>Keg <?php echo $keg->getid() . "_" . $keg->getsize(); ?></h2>

		<a href="#" class="action" id="a1" data-role="button" data-inline="true" data-theme="b">Clean</a>
		<a href="#" class="action" id="a2" data-role="button" data-inline="true" data-theme="b">Fill</a>
		<a href="#" class="action" id="a4" data-role="button" data-inline="true" data-theme="b">In use</a>
		<a href="#" class="action" id="a5" data-role="button" data-inline="true" data-theme="b">Dirty</a>

		<form method="post" action="keg.php<?php echo "?id=" . $_GET['id'] . "&amp;size=" . $_GET['size']; ?>">
		<label for="status">Status</label>
		<select name="status" id="status">
		<?php 
		$query = "SELECT id, status FROM " . $dbprefix . "keg_statuses ORDER BY id";
		if (!($result = $db->query($query))) echo "<p>Oh my: #" . $db->errno . ": " . $db->error . "</p>\r";
		while ($row = $result->fetch_assoc()) {
			echo "<option value=\"" . $row['id'] . "\"";
			if ($row['id'] == $keg->getstatus()) echo " selected=\"selected\"";
			echo ">" . $row['status'] . "</option>\r\n";
		}
		?>
			</select>

		<label for="beer">Beer</label>
		<select name="beer" id="beer">
		<?php 
		$query = "SELECT id, beer FROM " . $dbprefix . "beers WHERE active=1 OR id<1 ORDER BY beer";
		if (!($result = $db->query($query))) echo "<p>Oh my: #" . $db->errno . ": " . $db->error . "</p>\r";
		while ($row = $result->fetch_assoc()) {
			echo "<option value=\"" . $row['id'] . "\"";
			if ($row['id'] == $keg->getbeer()) echo " selected=\"selected\"";
			echo ">" . $row['beer'] . "</option>\r\n";
		}
		?>
			</select>

		<label for="location">Location</label>
		<select name="location" id="location">
		<?php 
		$query = "SELECT id, location FROM " . $dbprefix . "locations WHERE active=1 OR id<1 ORDER BY location";
		if (!($result = $db->query($query))) echo "<p>Oh my: #" . $db->errno . ": " . $db->error . "</p>\r";
		while ($row = $result->fetch_assoc()) {
			echo "<option value=\"" . $row['id'] . "\"";
			if ($row['id'] == $keg->getlocation()) echo " selected=\"selected\"";
			echo ">" . $row['location'] . "</option>\r\n";
		}
		?>
			</select>

			<button type="submit" data-inline="true" data-theme="b">Submit</button>

		</form>

		<table data-role="table" class="ui-responsive table-stroke">
		<thead>
		<tr>
		<th>Date</th>
		<th>Action</th>
		<!--<th>Comments</th>-->
		</tr>
		</thead>
		<tbody>
		<?php
		$query = "SELECT date, status, " . $dbprefix . "locations.location, " . $dbprefix . "beers.beer FROM " . $dbprefix . "keg_log INNER JOIN " . $dbprefix . "locations ON " . $dbprefix . "keg_log.location=" . $dbprefix . "locations.id INNER JOIN " . $dbprefix . "beers ON " . $dbprefix . "keg_log.beer=" . $dbprefix . "beers.id WHERE keg_id=" . $_GET['id'] . " AND size=" . $_GET['size'] . " ORDER BY date DESC LIMIT 5";
		if (!($result = $db->query($query))) echo "<p>Oh my: #" . $db->errno . ": " . $db->error . "</p>\r";
		while ($row = $result->fetch_assoc()) {
			echo "<tr>\r";
			echo "<th>" . date("m/d/y",strtotime($row['date'])) . "</th>\r";
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

		<!-- the success message -->
		<div id="success" data-role="popup" data-overlay-theme="a" class="ui-corner-all">
		<div data-role="header" class="ui-corner-top">
		<h1>Success!</h1>
		</div>

		<div data-role="content" class="ui-corner-bottom ui-content">
		<a href="/" data-role="button" data-inline="true" data-theme="b">Home</a>
		<a href="keg.php" data-role="button" data-inline="true" data-theme="a">Another</a>
		</div>
		</div>

		<div data-role="content" class="ui-corner-bottom ui-content">
		<button data-inline="true" data-theme="b">Submit</button>
		</div>
		</div>


		<?php
}

require('footer.inc.php');
?>
