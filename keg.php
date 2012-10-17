<?php 
if (isset($_GET['digit1'])) {
	$id = $_GET['digit1'] . $_GET['digit2'] . $_GET['digit3'];
	header("Location:keg.php?id=" . (int)$id . "&size=" . $_GET['size']);

}
require('header.inc.php');
require('functions.php');
?>
<div data-role="header">
<h1>Choose keg</h1>
</div>

<?php
if (!$_GET['id']) {
	$query = "SELECT id, size FROM cbw_keg_sizes ORDER BY id";
	if (!($result = $db->query($query))) echo "<p>Oh my: #" . $db->errno . ": " . $db->error . "</p>\r";
	while ($row = $result->fetch_assoc()) $sizes[$row['id']] = $row['size'];
	?>
		<div data-role="content">
		<form method="get" action="keg.php">
		<div data-role="fieldcontain">
		<fieldset data-role="controlgroup" data-type="horizontal">
		<legend>Keg id</legend>
		<label for="size" class="ui-hidden-accessible">Keg size</label>
		<select name="size" id="size">
		<?php foreach ($sizes as $id=>$size) echo "<option value=\"" . $id . "\">" . $size . "</option>\r\n"; ?>
		</select>

		<label for="digit1" class="ui-hidden-accessible">First digit of keg</label>
		<select name="digit1" id="digit1">
		<?php for ($i = 0; $i < 10; $i++) echo "<option value=\"" . $i . "\">" . $i . "</option>\r\n"; ?>
		</select>
		<label for="digit2" class="ui-hidden-accessible">Second digit of keg</label>
		<select name="digit2" id="digit2">
		<?php for ($i = 0; $i < 10; $i++) echo "<option value=\"" . $i . "\">" . $i . "</option>\r\n"; ?>
		</select>
		<label for="digit3" class="ui-hidden-accessible">Third digit of keg</label>
		<select name="digit3" id="digit3">
		<?php for ($i = 0; $i < 10; $i++) echo "<option value=\"" . $i . "\">" . $i . "</option>\r\n"; ?>
		</select>
		</fieldset>
		</div>

		<button class="submit">Submit</button>
		</form>
		</div>

		<?php
} else if (!$_POST['status']) {
	$query = "SELECT id, status, beer, location, size FROM cbw_kegs WHERE id=" . $_GET['id'] . "  AND size=" . $_GET['size'];
	if (!($result = $db->query($query))) echo "<p>Oh my: #" . $db->errno . ": " . $db->error . "</p>\r";
	$keg = new Keg($result->fetch_assoc());
	?>
		<h2>Keg <?php echo $keg->getid() . "_" . $keg->getsize(); ?></h2>
		<form method="post" action="keg.php<?php echo "?id=" . $_GET['id'] . "&amp;size=" . $_GET['size']; ?>">
		<label for="status">Status</label>
		<select name="status" id="status">
		<?php 
		$query = "SELECT id, status FROM cbw_keg_statuses ORDER BY id";
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
		$query = "SELECT id, beer FROM cbw_beers ORDER BY id";
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
		$query = "SELECT id, location FROM cbw_locations ORDER BY id";
		if (!($result = $db->query($query))) echo "<p>Oh my: #" . $db->errno . ": " . $db->error . "</p>\r";
		while ($row = $result->fetch_assoc()) {
			echo "<option value=\"" . $row['id'] . "\"";
			if ($row['id'] == $keg->getlocation()) echo " selected=\"selected\"";
			echo ">" . $row['location'] . "</option>\r\n";
		}
		?>
			</select>

			<button type="submit">Submit</button>

		</form>
		<?php
} else {
	echo "stuff";
}

require('footer.inc.php');
?>
