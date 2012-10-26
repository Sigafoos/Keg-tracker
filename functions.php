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

		$this->status = ($data['status']) ? $data['status'] : -1;
		$this->beer = ($data['beer']) ? $data['beer'] : 0;
		$this->location = ($data['location']) ? $data['location'] : -1;
		$this->size = ($data['size']) ? $data['size'] : 1;
	}

	// DISPLAY FUNCTIONS
	// display the keg's status
	public function info() {
		global $db;
		$query = "SELECT cbw_keg_statuses.status, cbw_locations.location, cbw_keg_sizes.size, cbw_beers.beer FROM cbw_kegs INNER JOIN cbw_keg_statuses ON cbw_kegs.status=cbw_keg_statuses.id INNER JOIN cbw_locations ON cbw_kegs.location=cbw_locations.id INNER JOIN cbw_keg_sizes ON cbw_kegs.size=cbw_keg_sizes.id LEFT OUTER JOIN cbw_beers ON cbw_kegs.beer=cbw_beers.id WHERE cbw_kegs.id=" . $this->id . " AND cbw_kegs.size=" . $this->size;
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
	public function getstatus($text = 0) {
		if (!$text) {
			return $this->status;
		} else {
			global $db;
			$query = "SELECT status FROM cbw_keg_statuses WHERE id=" . $this->status;
			$result = $db->query($query);
			$row = $result->fetch_assoc();
			return $row['status'];
		}
	}

	// UNTESTED
	public function getbeer($text = 0) {
		if (!$text) {
			return $this->beer;
		} else {
			global $db;
			$query = "SELECT beer FROM cbw_beers WHERE id=" . $this->beer;
			$result = $db->query($query);
			$row = $result->fetch_assoc();
			return $row['beer'];
		}
	}

	// UNTESTED
	public function getlocation($text = 0) {
		if (!$text) {
			return $this->location;
		} else {
			global $db;
			$query = "SELECT location FROM cbw_locations WHERE id=" . $this->location;
			$result = $db->query($query);
			$row = $result->fetch_assoc();
			return $row['location'];
		}
	}

	public function getsize($text = 0) {
		if (!$text) {
			return $this->size;
		} else {
			global $db;
			$query = "SELECT size FROM cbw_keg_sizes WHERE id=" . $this->size;
			$result = $db->query($query);
			$row = $result->fetch_assoc();
			return $row['size'];
		}
	}

	// UPDATING FUNCTIONS
	// now that we've checked the validity of the data in the specific action function, 
	// update the data
	public function update() {
		global $db;

		$query = "UPDATE cbw_kegs SET status=" . $this->status . ", beer=" . $this->beeri . ", location=" . $this->location . " WHERE id=" . $this->id . " AND size=" . $this->size;

		if (!$db->query($query)) {
			echo "<p>Error updating info for keg " . $this->id . "-" . $this->size . ": #" . $db->errno . ": " . $db->error . "</p>\r";
			echo "<p>" . $query . "</p>\r";
			return FALSE;
		} else {
			return $this->log();
		}
	}

	private function log($timestamp = NULL) {
		global $db;
		if (!$timestamp) $timestamp = date("Y-m-d G:i:s");

		$query = "INSERT INTO cbw_keg_log(keg_id, size, status, date) VALUES(" . $this->id . "," . $this->size . ", " . $this->status . ", '" . $timestamp . "')";
		if (!$db->query($query)) {
			echo "<p>Error logging update for keg " . $this->id . "-" . $this->size . ": #" . $db->errno . ": " . $db->error . "</p>\r";
			echo "<p>" . $query . "</p>\r";
			return FALSE;
		} else {
			return TRUE;
		}
	}


	// someone used the keg washer
	public function clean() {
		global $warnings;

		// it's not at CBW
		if ($this->location != 1) {
			$warnings[] = "keg " . $this->id . "_" . $this->size . " was not marked as being at HQ";
			$this->location = 1;
		}
		// it had beer in it
		if ($this->beer != 0) {
			$warnings[] = "keg " . $this->id . "_" . $this->size . " was not marked as empty";
			$this->beer = 0;
		}
		// it's not dirty
		if ($this->status != 1) {
			$warnings[] = "keg " . $this->id . "_" . $this->size . " was not marked as dirty";
		}

		$this->status = 2;
		$this->update();
	}

	// embeer!
	public function fill($beer) {
		global $warnings;

		// it's not at CBW
		if ($this->location != 1) {
			$warnings[] = "keg " . $this->id . "_" . $this->size . "  was not marked as being at HQ\r";
			$this->location = 1;
		}
		// it had beer in it
		if ($this->beer != 0) {
			$warnings[] = "keg " . $this->id . "_" . $this->size . "  was not marked as empty\r";
		}
		// it wasn't clean
		if ($this->status != 2) {
			$warnings[] = "keg " . $this->id . "_" . $this->size . "  was not marked as clean\r";
		}

		$this->status = 3;
		$this->beer = $beer;
		$this->update();
	}

	// hook up to Cookiepuss
	function carbonate() {
		global $warnings;

		// we don't know the beer
		if ($this->beer == 0) {
			$warnings[] = "keg " . $this->id . "_" . $this->size . "  full of unknown beer\r";
			$this->beer = -1;
		}
		// it's not at CBW
		if ($this->location != 1) {
			$warnings[] = "keg " . $this->id . "_" . $this->size . "  was not marked as being at HQ\r";
			$this->location = 1;
		}
		// it wasn't full of uncarbed beer
		if ($this->status != 3) {
			$warnings[] = "keg " . $this->id . "_" . $this->size . "  was not marked as uncarbonated\r";
		}

		$this->status = 4;
		$this->update();
	}

	// send to a bar, or our fridge
	public function deliver($location) {
		global $warnings;

		// we don't know the beer
		if ($this->beer == 0) {
			$warnings[] = "keg " . $this->id . "_" . $this->size . "  full of unknown beer\r";
			$this->beer = -1;
		}
		// it wasn't full of carbed beer
		if ($this->status != 4) {
			$warnings[] = "keg " . $this->id . "_" . $this->size . "  was not marked as uncarbonated\r";
		}

		$this->status = 5;
		$this->location = $location;
		$this->update();
	}

	// it's been used up
	public function pickup() {
		global $warnings;

		// it wasn't out somewhere
		if ($this->status != 5) {
			$warnings[] = "keg " . $this->id . "_" . $this->size . "  was not marked as being in use\r";
		}
		// it didn't have beer in it
		if ($this->beer == 0) {
			$warnings[] = "keg " . $this->id . "_" . $this->size . "  was marked as empty\r";
		}

		$this->location = 1;
		$this->beer = 0;
		$this->status = 1;
		$this->update();
	}

}

function new_keg($id, $size = 1, $status = -1, $beer = -1, $location = -1) {
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

// choose your own beer adventure
function select_beer($section) {
	global $db;
	
	echo "<div id=\"select\">\r\n";
	echo "<ul data-role=\"listview\">\r\n";
	$query = "SELECT id, beer FROM cbw_beers WHERE active=1 ORDER BY beer";
	if (!($result = $db->query($query))) echo "<p>Something's gone wrong: #" . $db->errno . ": " . $db->error . "</p>";
	while ($row = $result->fetch_assoc()) {
		echo "<li><a href=\"" . $section . ".php?beer=" . $row['id'] . "\">" . $row['beer'] . "</a><a href=\"#\" class=\"confirm\" id=\"b" . $row['id'] . "\" data-icon=\"delete\" data-theme=\"a\" title=\"Archive " . $row['beer'] . "\"></a></li>\r\n";
	}
	echo "</ul>\r\n</div>\r\n";

	// the "are you sure?" dialog
	?>
		<div id="confirm" data-role="popup" data-role="popup" data-overlay-theme="a" class="ui-corner-all">
		<div data-role="header" class="ui-corner-top">
		<h1>Really?</h1>
		</div>

		<div data-role="content" class="ui-corner-bottom ui-content">
		<p>Are you sure you want to do this?</p>
		<a href="#" class="archive" data-role="button" data-inline="true" data-theme="b">Yes</a>
		<a href="#" data-role="button" data-inline="true" data-theme="a" data-rel="back">No</a>
		</div>
		</div>
		<?php
}

function select_location($section) {
	global $db;

	echo "<div id=\"select\">\r\n";
	echo "<ul data-role=\"listview\">\r\n";
	$query = "SELECT id, location FROM cbw_locations WHERE active=1 ORDER BY location";
	if (!($result = $db->query($query))) echo "<p>Something's gone wrong: #" . $db->errno . ": " . $db->error . "</p>";
	while ($row = $result->fetch_assoc()) echo "<li><a href=\"" . $section . ".php?location=" . $row['id'] . "\">" . $row['location'] . "</a><a href=\"#\" class=\"confirm\" id=\"l" . $row['id'] . "\" data-icon=\"delete\" data-theme=\"a\" data-position-to=\"window\" title=\"Archive " . $row['location'] . "\"></a></li>\r\n";
	echo "</ul>\r\n</div>\r\n";

	// the "are you sure?" dialog
	?>
		<div id="confirm" data-role="popup" data-role="popup" data-overlay-theme="a" class="ui-corner-all">
		<div data-role="header" class="ui-corner-top">
		<h1>Really?</h1>
		</div>

		<div data-role="content" class="ui-corner-bottom ui-content">
		<p>Are you sure you want to do this?</p>
		<a href="#" class="archive" data-role="button" data-inline="true" data-theme="b">Yes</a>
		<a href="#" data-role="button" data-inline="true" data-theme="a" data-rel="back">No</a>
		</div>
		</div>
		<?php
}

// pass the status, get the kegs
function select_kegs($status) {
	global $db;

	// get the beers to look up 
	// but don't bother if it's anachronistic
	if ($status > 2) {
		$query = "SELECT id, beer FROM cbw_beers";
		if (!($result = $db->query($query))) echo "<p>Something's gone wrong: #" . $db->errno . ": " . $db->error . "</p>";
		while ($row = $result->fetch_assoc()) $beers[$row['id']] = $row['beer'];
	}

	// get the locations to look up
	// but don't bother if it's anachronistic
	if ($status == 5) {
		$query = "SELECT id, location FROM cbw_locations";
		if (!($result = $db->query($query))) echo "<p>Something's gone wrong: #" . $db->errno . ": " . $db->error . "</p>";
		while ($row = $result->fetch_assoc()) $locations[$row['id']] = $row['location'];
	}

	// get the keg sizes to look up
	$query = "SELECT id, size FROM cbw_keg_sizes";
	if (!($result = $db->query($query))) echo "<p>Something's gone wrong: #" . $db->errno . ": " . $db->error . "</p>";
	while ($row = $result->fetch_assoc()) $sizes[$row['id']] = $row['size'];

	// get the kegs themselves
	//$query = "SELECT id, size, beer, location FROM cbw_kegs WHERE status=" . $status . " OR status=-1 ORDER BY location, beer, size, id";
	$query = "SELECT id, size, beer, location FROM cbw_kegs WHERE status=" . $status;
	if ($status == 1) $query .= " OR status = -1";
	$query .= " ORDER BY location DESC, beer, size, id";
	if (!($result = $db->query($query))) echo "<p>Something's gone wrong: #" . $db->errno . ": " . $db->error . "</p>";
	while ($row = $result->fetch_assoc()) $kegs[] = new keg($row);
?>
	<form method="post" action="update.php?status=<?php echo $status . "&" . $_SERVER['QUERY_STRING']; ?>">
	<div data-role="fieldcontain">
	<fieldset data-role="controlgroup">
	<?php
	if ($status == 5) {
		$currlocation = $kegs[0]->getlocation();
		echo "<legend>" . $locations[$currlocation] . "</legend>\r\n";
	} else {
		echo "<legend>Kegs to update</legend>\r\n";
	}

	foreach ($kegs as $keg) {
		$id = $keg->getid();
		$intsize = $keg->getsize();
		$formid = $id . "_" . $intsize;
		$size = $sizes[$intsize];
		$beer = $keg->getbeer();
		$location = $keg->getlocation();

		// if we're returning kegs, separate by location
		if ($status == 5 && $currlocation != $location) {
			echo "</fieldset>\r\n</div>\r\n<div data-role=\"fieldcontain\">\r\n<fieldset data-role=\"controlgroup\">\r\n<legend>" . $locations[$location] . "</legend>\r\n";
			$currlocation = $location;
		}

		echo "<input type=\"checkbox\" id=\"keg" . $formid . "\" name=\"kegs[" . $formid . "]\" /><label for=\"keg" . $formid . "\">" . $size . "  keg #" . $id;
		if ($beer) echo " (" . $beers[$beer] . ")";
		echo "</label>\r\n";
	}
		?>
			</fieldset>
			</div>
			<button type="submit"  data-theme="b">Submit</button>
			</form>
			<?php
}

// send a Pushover alert if something important happened.
// eventually I'll let you send this to people who aren't me
// and probably support email, etc
function send_alert($title = "Keg tracker alert", $message, $priority = 0) {
	require('pushover.inc.php'); // my user key

	$alert['token'] = "5DmKP2l58HoqvFGda4zWcnBmLYm3MX";
	$alert['user'] = $user; // CHANGE THIS: just sends to Dan
	$request = "token=" . $alert['token'] . "&user=" . $alert['user'] . "&title=" . $title . "&message=" . $message;
	if ($priority == 1) $request .= "&priority=1";
	$result = urlencode($request);

	$ch = curl_init("https://api.pushover.net/1/messages.json");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // don't echo the status response, man
	curl_setopt($ch, CURLOPT_POSTFIELDS, $request);

	return curl_exec($ch);
}
?>
