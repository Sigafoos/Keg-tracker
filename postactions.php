<?php
// for all our $.post queries!
require('functions.php');

if ($_POST['new'] == "beer") {
	$query = "INSERT INTO " . $dbprefix . "beers(beer) VALUES('" . $db->real_escape_string($_POST['beer']) . "')";
	if (!($result = $db->query($query))) echo "<p>Something's gone wrong: #" . $db->errno . ": " . $db->error . "</p>";

	echo $db->insert_id;

} else if ($_POST['new'] == "location") {
	$query = "INSERT INTO " . $dbprefix . "locations(location) VALUES('" . $db->real_escape_string($_POST['location']) . "')";
	if (!($result = $db->query($query))) echo "<p>Something's gone wrong: #" . $db->errno . ": " . $db->error . "</p>";

	echo $db->insert_id;

} else if ($_POST['new'] == "keg") {
	if (!$_POST['end']) $_POST['end'] = $_POST['id'];
	switch($_POST['status']) {
		case 1: // dirty
		case 2: // empty/clean
		case 6: // broken
			$beer = 0;
			$location = 1;
			break;

		case 3: // uncarbonated
		case 4: // carbonated
			$beer = -1;
			$location = 1;
			break;

		case 5: // in use
		case -1: // unknown
		default: // REALLY unknown (how did you even do this?)
			$beer = -1;
			$location = -1;
			break;
	}
	for ($i = $_POST['id']; $i <= $_POST['end']; $i++) new_keg($i,$_POST['size'],$_POST['status'],$beer,$location);

} else if ($_POST['new'] == "warning") {
	$query = "INSERT INTO " . $dbprefix . "keg_warnings(warning) VALUES('" . $_POST['warning'] . "')";
	if (!($result = $db->query($query))) echo "<p>Something's gone wrong: #" . $db->errno . ": " . $db->error . "</p>";

	echo $db->insert_id;
} else if ($_POST['update']) {
	//$ids = explode("+",$_POST['ids']);
	if (!$_POST['ids']) die("0 kegs processed.");
	if (!$_POST['beer']) $_POST['beer'] = -1;
	if (!$_POST['location']) $_POST['location'] = -1;

	foreach (array_unique(explode("+",$_POST['ids'])) as $info) {
		// [0] = id; [1] = size
		$info = explode("_",$info);
		$query = "SELECT id, location, status, size, beer FROM " . $dbprefix . "kegs WHERE id=" . $info[0] . " AND size=" . $info[1];
		$result = $db->query($query);

		try {
			$keg = new Keg($result->fetch_assoc());
		} catch (Exception $e) {
			$errors[] = $info[0] . "_" . $info[1] . ": " . $e->getMessage();
			continue;
		}

		switch($_POST['status']) {
			case 1:
				$keg->clean();
				break;

			case 2:
				$keg->fill($_POST['beer']);
				break;

			case 3: 
			case 4:
				$keg->deliver($_POST['location']);
				break;

			case 5:
				$keg->pickup();
				break;

			default:
				echo "I'm not sure what to do with status " . $_POST['status'];
				break;
		}

		$i++;
	}
	if (count($errors)) echo "<h2>Errors</h2>\r\n<ul>\r\n<li>" . implode("</li>\r\n<li>",$errors) . "</ul>\r";
	if (count($warnings[2]) && $warninglevel >= 2) echo "<h2>Warnings</h2>\r\n<ul>\r\n<li>" . implode("</li>\r\n<li>",$warnings[2]) . "</ul>\r";
	echo "<p>" . $i . " keg";
	if ($i > 1) echo "s";
	echo " processed.</p>\r\n";
} else if ($_POST["clean"] == "true") {
	if (!$_POST['ids']) die("0 kegs processed.");
	foreach (explode("+",$_POST['ids']) as $info) {
		// [0] = id; [1] = size
		$info = explode("_",$info);
		$query = "SELECT id, location, status, size, beer FROM " . $dbprefix . "kegs WHERE id=" . $info[0] . " AND size=" . $info[1];
		$result = $db->query($query);
		$keg = new Keg($result->fetch_assoc());

		$keg->unknown();
	}
} else if ($_POST['edit'] == "keg") {
	$keg = new Keg($_POST);
	//echo "id: " . $keg->getid() . "\nsize: " . $keg->getsize() . "\nstatus: " . $keg->getstatus() . "\nbeer: " . $keg->getbeer() . "\nlocation: " . $keg->getlocation();
	$keg->update();
	die();
} else if ($_POST['warn']) {
	// the $_GET function in custom.js is a little wonky
	$_POST['id'] = (int)$_POST['id'];
	$_POST['size'] = (int)$_POST['size'];
	$_POST['status'] = -99; // "info" status
	if ($_POST['warn'] == "clear") $_POST['warning'] = -1;

	$keg = new Keg($_POST);
	echo $keg->info();
	$keg->warn($_POST['warning']);
} else if ($_POST['activate']) {
	$query = "UPDATE " . $dbprefix;
	if (substr($_POST['activate'],0,1) == "b") $query .= "beers";
	else if (substr($_POST['activate'],0,1) == "l") $query .= "locations";
	$query .= " SET active=1 WHERE id=" . substr($_POST['activate'],1);
	$result = $db->query($query);

	echo "Reactivated!";
}
?>
