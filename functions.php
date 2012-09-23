<?php
if (!$db) require('../db_connect.php');

function new_keg($id, $status = 1, $location = 1, $size = 1) {
	global $db;
	$query = "INSERT INTO cbw_kegs(id, status, location, size) VALUES(" . $id . "," . $status . "," . $location . "," . $size . ")";
	if (!$db->query($query)) {
		echo "<p>Error creating keg " . $id . ": #" . $db->errno . ": " . $db->error . "</p>\r";
		return FALSE;
	} else {
		return TRUE;
	}
}

// This should really never be used
function delete_keg($id,$size) {
	global $db;
	$query = "DELETE FROM cbw_kegs WHERE id=" . $id . " AND size=" . $size;
	if (!$db->query($query)) {
		echo "<p>Error deleting keg " . $id . ": #" . $db->errno . ": " . $db->error . "</p>\r";
		return FALSE;
	} else {
		return TRUE;
	}
}
?>
