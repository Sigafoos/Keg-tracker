<?php
// for all our $.post queries!
require('functions.php');

if ($_POST['new'] == "beer") {
	$query = "INSERT INTO cbw_beers(beer) VALUES('" . $_POST['beer'] . "')";
	if (!($result = $db->query($query))) echo "<p>Something's gone wrong: #" . $db->errno . ": " . $db->error . "</p>";

	echo $db->insert_id;
} else if ($_POST['new'] == "location") {
	$query = "INSERT INTO cbw_locations(location) VALUES('" . $_POST['location'] . "')";
	if (!($result = $db->query($query))) echo "<p>Something's gone wrong: #" . $db->errno . ": " . $db->error . "</p>";

	echo $db->insert_id;
}
?>
