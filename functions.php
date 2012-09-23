<?php
if (!$db) require('../db_connect.php');

class keg {
	private $id;
	private $status;
	private $beer;
	private $location;
	private $size;

	function __construct($data) {
		$this->id = $data['id'];
		if (!$this->id) {
			throw new Exception("Error: no ID specified for keg");
			return FALSE;
		}

		$this->status = ($data['status']) ? $data['status'] : 1;
		$this->beer = $data['beer']; // it's okay to be null
		$this->location = ($data['location']) ? $data['location'] : 1;
		$this->size = ($data['size']) ? $data['size'] : 1;
	}

	// display the keg's status
	public function info() {
		global $db;
		$query = "SELECT cbw_keg_statuses.status, cbw_locations.location, cbw_keg_sizes.size, cbw_beers.beer FROM cbw_kegs INNER JOIN cbw_keg_statuses ON cbw_kegs.status=cbw_keg_statuses.id INNER JOIN cbw_locations ON cbw_kegs.location=cbw_locations.id INNER JOIN cbw_keg_sizes ON cbw_kegs.size=cbw_keg_sizes.id LEFT OUTER JOIN cbw_beers ON cbw_kegs.beer=cbw_beers.id WHERE cbw_kegs.id=" . $this->id . " AND cbw_kegs.size=" . $this->size;
		$result = $db->query($query);
		if (!($result = $db->query($query))) {
			echo "<p>Error getting info for keg " . $this->id . ": #" . $db->errno . ": " . $db->error . "</p>\r";
			return FALSE;
		}
		$row = $result->fetch_assoc();

		echo "<h2>" . $row['size'] . " keg #" . $this->id . "</h2>\r";
		echo "<p><strong>Status</strong>: " . $row['status'] . "<br />\r";
		echo "<strong>Beer</strong>: ";
		echo ($row['beer']) ? $row['beer'] : "(empty)";
		echo "<br />\r<strong>Location</strong>: " . $row['location'] . "</p>\r";

		return TRUE;
	}
}

function new_keg($id, $status = 1, $beer = NULL, $location = 1, $size = 1) {
	global $db;
	$query = "INSERT INTO cbw_kegs(id, status, beer, location, size) VALUES(" . $id . "," . $status . "," . $beer . "," . $location . "," . $size . ")";
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
