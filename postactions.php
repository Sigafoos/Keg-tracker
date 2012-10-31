<?php
// for all our $.post queries!
require('functions.php');

if ($_POST['new'] == "beer") {
	$query = "INSERT INTO cbw_beers(beer) VALUES('" . $db->real_escape_string($_POST['beer']) . "')";
	if (!($result = $db->query($query))) echo "<p>Something's gone wrong: #" . $db->errno . ": " . $db->error . "</p>";

	echo $db->insert_id;
} else if ($_POST['new'] == "location") {
	$query = "INSERT INTO cbw_locations(location) VALUES('" . $db->real_escape_string($_POST['location']) . "')";
	if (!($result = $db->query($query))) echo "<p>Something's gone wrong: #" . $db->errno . ": " . $db->error . "</p>";

	echo $db->insert_id;
} else if ($_POST['update']) {
	//$ids = explode("+",$_POST['ids']);
	if (!$_POST['ids']) die("0 kegs processed.");

	foreach (explode("+",$_POST['ids']) as $info) {
		// [0] = id; [1] = size
		$info = explode("_",$info);
		$query = "SELECT id, location, status, size, beer FROM cbw_kegs WHERE id=" . $info[0] . " AND size=" . $info[1];
		$result = $db->query($query);
		$keg = new Keg($result->fetch_assoc());

		switch($_POST['status']) {
			case 1:
				$keg->clean();
				break;

			case 2:
				$keg->fill($_POST['beer']);
				break;

			case 3: 
				$keg->carbonate();
				break;

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
	if (count($warnings)) echo "<h2>Warnings</h2>\r\n<ul>\r\n<li>" . implode("</li>\r\n<li>",$warnings) . "</ul>\r";
	echo "<p>" . $i . " keg";
	if ($i > 1) echo "s";
	echo " processed.</p>\r\n";
}
?>
