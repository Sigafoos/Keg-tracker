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

	// DISPLAY FUNCTIONS
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

	// have at the variables
	public function getid() {
		return $this->id;
	}

	// UNTESTED
	public function getintstatus() {
		return $this->status;
	}

	// UNTESTED
	public function getstatus() {
		global $db;
		$query = "SELECT status FROM cbw_keg_statuses WHERE id=" . $this->status;
		$result = $db->query($query);
		$row = $result->fetch_assoc();
		return $row['status'];
	}

	// UNTESTED
	public function getintbeer() {
		return $this->beer;
	}

	// UNTESTED
	public function getbeer() {
		if ($this->beer == NULL) return NULL;

		global $db;
		$query = "SELECT beer FROM cbw_beers WHERE id=" . $this->beer;
		$result = $db->query($query);
		$row = $result->fetch_assoc();
		return $row['beer'];
	}

	//UNTESTED
	public function getintlocation() {
		return $this->location;
	}

	// UNTESTED
	public function getlocation() {
		global $db;
		$query = "SELECT location FROM cbw_locations WHERE id=" . $this->location;
		$result = $db->query($query);
		$row = $result->fetch_assoc();
		return $row['location'];
	}

	public function getintsize() {
		return $this->size;
	}

	public function getsize() {
		global $db;
		$query = "SELECT size FROM cbw_keg_sizes WHERE id=" . $this->size;
		$result = $db->query($query);
		$row = $result->fetch_assoc();
		return $row['size'];
	}

	// UPDATING FUNCTIONS
	// now that we've checked the validity of the data in the specific action function, 
	// update the data
	private function update() {
		global $db;

		$query = "UPDATE cbw_kegs SET status=" . $this->status . ", beer=" . $this->beer . ", location=" . $this->location . " WHERE id=" . $this->id . " AND size=" . $this->size;
		echo "<p>" . $query . "</p>";

		if (!$db->query($query)) {
			echo "<p>Error updating info for keg " . $this->id . "-" . $this->size . ": #" . $db->errno . ": " . $db->error . "</p>\r";
			echo "<p>" . $query . "</p>\r";
			return FALSE;
		} else {
			return TRUE;
		}
	}

	// someone used the keg washer
	public function clean() {
		// it's not at CBW
		if ($this->location != 1) {
			echo "Warning: keg was not marked as being at HQ\r";
			$this->location = 1;
		}
		// it had beer in it
		if ($this->beer != NULL) {
			echo "Warning: keg was not marked as empty\r";
			$this->beer = "NULL";
		}
		// it's not dirty
		if ($this->status != 1) {
			echo "Warning: keg was not marked as clean\r";
		}

		$this->status = 2;
		$this->update();
	}

	// embeer!
	public function fill($beer) {
		// it's not at CBW
		if ($this->location != 1) {
			echo "Warning: keg was not marked as being at HQ\r";
			$this->location = 1;
		}
		// it had beer in it
		if ($this->beer != NULL) {
			echo "Warning: keg was not marked as empty\r";
		}
		// it wasn't clean
		if ($this->status != 2) {
			echo "Warning: keg was not marked as clean\r";
		}

		$this->status = 3;
		$this->beer = $beer;
		$this->update();
	}

	// hook up to Cookiepuss
	function carbonate() {
		// usually I try to help out, but I need to know the beer
		if ($this->beer == NULL) {
			throw new Exception("Error: keg does not contain beer");
			return FALSE;
		}
		// it's not at CBW
		if ($this->location != 1) {
			echo "Warning: keg was not marked as being at HQ\r";
			$this->location = 1;
		}
		// it wasn't full of uncarbed beer
		if ($this->status != 3) {
			echo "Warning: keg was not marked as uncarbonated\r";
		}

		$this->status = 4;
		$this->update();
	}

	// send to a bar, or our fridge
	public function deliver($location) {
		// usually I try to help out, but I need to know the beer
		if ($this->beer == NULL) {
			throw new Exception("Error: keg does not contain beer");
			return FALSE;
		}
		// it's not at CBW
		if ($this->location != 1) {
			echo "Warning: keg was not marked as being at HQ\r";
		}
		// it wasn't full of carbed beer
		if ($this->status != 4) {
			echo "Warning: keg was not marked as uncarbonated\r";
		}

		$this->status = 5;
		$this->location = $location;
		$this->update();
	}

	// it's been used up
	public function pickup() {
		// it wasn't out somewhere
		if ($this->status != 5) {
			echo "Warning: keg was not marked as being in use\r";
		}
		// it didn't have beer in it
		if ($this->beer == NULL) {
			echo "Warning: keg was marked as empty\r";
		}

		$this->location = 1;
		$this->beer = "NULL";
		$this->status = 1;
		$this->update();
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
