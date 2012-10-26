<?php 
require('functions.php');

if ($_POST['list'] == "yes") {
	$query = "SELECT id FROM cbw_kegs WHERE size=" . $_POST['size'];
	if (!($result = $db->query($query))) echo "<p>Oh my: #" . $db->errno . ": " . $db->error . "</p>\r";
	while ($row = $result->fetch_assoc()) echo "<option value=\"" . $row['id'] . "\">" . $row['id'] . "</option>";
	die();
} else if ($_POST['edit'] == "yes") {
	$keg = new Keg($_POST);
	//echo "id: " . $keg->getid() . "\nsize: " . $keg->getsize() . "\nstatus: " . $keg->getstatus() . "\nbeer: " . $keg->getbeer() . "\nlocation: " . $keg->getlocation();
	$keg->update();
	die();
}
require('header.inc.php');
?>
<div data-role="header">
<a href="#" data-rel="back" data-type="button" data-theme="b" data-icon="arrow-l">Back</a>
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
		<option value="" selected="selected"></option>
		<?php foreach ($sizes as $id=>$size) echo "<option value=\"" . $id . "\">" . $size . "</option>\r\n"; ?>
		</select>

		<select name="id" id="id">
		<option value=""></option>
		</select>

		</fieldset>
		</div>

		<button type="submit" data-inline="true" data-theme="b">Submit</button>
		</form>
		</div>

		<?php
} else {
	$query = "SELECT id, status, beer, location, size FROM cbw_kegs WHERE id=" . $_GET['id'] . "  AND size=" . $_GET['size'];
	if (!($result = $db->query($query))) echo "<p>Oh my: #" . $db->errno . ": " . $db->error . "</p>\r";
	$keg = new Keg($result->fetch_assoc());
	?>
		<div id="keginfo">
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

			<button type="submit" data-inline="true" data-theme="b">Submit</button>

		</form>
		</div>

		<!-- the success message -->
		<div id="success" data-role="popup" data-overlay-theme="a" class="ui-corner-all">
		<div data-role="header" class="ui-corner-top">
		<h1>Success!</h1>
		</div>

		<div data-role="content" class="ui-corner-bottom ui-content">
		<a href="/" data-role="button" data-inline="true" data-theme="b">Home</a>
		<a href="#" data-role="button" data-inline="true" data-theme="a" data-rel="back">Close</a>
		</div>
		</div>
		<?php
}

require('footer.inc.php');
?>
