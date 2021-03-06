<?php
require('config.inc.php'); // your user keys (Pushover and Facebook)

class keg {
	private $id;
	private $status;
	private $beer;
	private $location;
	private $size;
	private $warning;

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
		$this->warning = ($data['warning']) ? $data['warning'] : -1;
	}

	// DISPLAY FUNCTIONS
	// display the keg's status
	public function info() {
		global $db,$dbprefix;
		$query = "SELECT " . $dbprefix . "keg_statuses.status, " . $dbprefix . "locations.location, " . $dbprefix . "keg_sizes.size, " . $dbprefix . "beers.beer, " . $dbprefix . "keg_warnings.warning FROM " . $dbprefix . "kegs INNER JOIN " . $dbprefix . "keg_statuses ON " . $dbprefix . "kegs.status=" . $dbprefix . "keg_statuses.id INNER JOIN " . $dbprefix . "locations ON " . $dbprefix . "kegs.location=" . $dbprefix . "locations.id INNER JOIN " . $dbprefix . "keg_sizes ON " . $dbprefix . "kegs.size=" . $dbprefix . "keg_sizes.id LEFT OUTER JOIN " . $dbprefix . "beers ON " . $dbprefix . "kegs.beer=" . $dbprefix . "beers.id LEFT OUTER JOIN " . $dbprefix . "keg_warnings ON " . $dbprefix . "kegs.warning=" . $dbprefix . "keg_warnings.id WHERE " . $dbprefix . "kegs.id=" . $this->id . " AND " . $dbprefix . "kegs.size=" . $this->size;
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
			global $db,$dbprefix;
			$query = "SELECT status FROM " . $dbprefix . "keg_statuses WHERE id=" . $this->status;
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
			global $db,$dbprefix;
			$query = "SELECT beer FROM " . $dbprefix . "beers WHERE id=" . $this->beer;
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
			global $db,$dbprefix;
			$query = "SELECT location FROM " . $dbprefix . "locations WHERE id=" . $this->location;
			$result = $db->query($query);
			$row = $result->fetch_assoc();
			return $row['location'];
		}
	}

	public function getsize($text = 0) {
		if (!$text) {
			return $this->size;
		} else {
			global $db,$dbprefix;
			$query = "SELECT size FROM " . $dbprefix . "keg_sizes WHERE id=" . $this->size;
			$result = $db->query($query);
			$row = $result->fetch_assoc();
			return $row['size'];
		}
	}

	public function getwarning($text = 0) {
		if (!$text) {
			return $this->warning;
		} else {
			global $db,$dbprefix;
			$query = "SELECT warning FROM " . $dbprefix . "keg_warnings WHERE id=" . $this->warning;
			$result = $db->query($query);
			$row = $result->fetch_assoc();
			return $row['warning'];
		}
	}

	// UPDATING FUNCTIONS
	// now that we've checked the validity of the data in the specific action function, 
	// update the data
	public function update() {
		global $db,$dbprefix;

		$query = "UPDATE " . $dbprefix . "kegs SET";
		if ($this->status != -99) $query .= " status=" . $this->status . ", beer=" . $this->beer . ", location=" . $this->location; // full update
		else $query .= " warning=" . $this->warning; // just an info update
		$query .= " WHERE id=" . $this->id . " AND size=" . $this->size;

		if (!$db->query($query)) {
			echo "<p>Error updating info for keg " . $this->id . "-" . $this->size . ": #" . $db->errno . ": " . $db->error . "</p>\r";
			echo "<p>" . $query . "</p>\r";
			return FALSE;
		} else {
			return $this->log();
		}
	}

	private function log($timestamp = NULL) {
		global $db,$dbprefix;
		date_default_timezone_set('America/New_York');
		if (!$timestamp) $timestamp = date("Y-m-d G:i:s");

		$query = "INSERT INTO " . $dbprefix . "keg_log(keg_id, size, status";
		if ($this->status != -99) $query = "INSERT INTO " . $dbprefix . "keg_log(keg_id, size, status, location, beer, date) VALUES(" . $this->id . "," . $this->size . ", " . $this->status . ", " . $this->location . ", " . $this->beer . ", '" . $timestamp . "')";
		else $query = "INSERT INTO " . $dbprefix . "keg_log(keg_id, size, status, warning, date) VALUES(" . $this->id . "," . $this->size . ", " . $this->status . ", " . $this->warning . ", '" . $timestamp . "')";

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
			$warnings[2][] = "keg " . $this->id . "_" . $this->size . " was not marked as being at HQ";
			$this->location = 1;
		}
		// it had beer in it
		if ($this->beer != 0) {
			$warnings[2][] = "keg " . $this->id . "_" . $this->size . " was not marked as empty";
			$this->beer = 0;
		}
		// it's not dirty
		if ($this->status != 1) {
			$warnings[2][] = "keg " . $this->id . "_" . $this->size . " was not marked as dirty";
		}

		$this->status = 2;
		$this->update();
	}

	// embeer!
	public function fill($beer = -1) {
		global $warnings;

		// it's not at CBW
		if ($this->location != 1) {
			$warnings[2][] = "keg " . $this->id . "_" . $this->size . "  was not marked as being at HQ\r";
			$this->location = 1;
		}
		// it had beer in it
		if ($this->beer != 0) {
			$warnings[2][] = "keg " . $this->id . "_" . $this->size . "  was not marked as empty\r";
		}
		// it wasn't clean
		if ($this->status != 2) {
			$warnings[2][] = "keg " . $this->id . "_" . $this->size . "  was not marked as clean\r";
		}

		$this->status = 3;
		$this->beer = $beer;
		$this->update();
	}

	// send to a bar, or our fridge
	public function deliver($location = -1) {
		global $warnings;

		// we don't know the beer
		if ($this->beer == 0) {
			$warnings[2][] = "keg " . $this->id . "_" . $this->size . "  full of unknown beer\r";
			$this->beer = -1;
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
			$warnings[2][] = "keg " . $this->id . "_" . $this->size . "  was not marked as being in use\r";
		}
		// it didn't have beer in it
		if ($this->beer == 0) {
			$warnings[2][] = "keg " . $this->id . "_" . $this->size . "  was marked as empty\r";
		}

		$this->location = 1;
		$this->beer = 0;
		$this->status = 1;
		$this->update();
	}

	public function warn($warning) {
		$this->warning = $warning;
		$this->update();
	}

	// we don't know anything about this keg anymore
	public function unknown() {
		$this->location = -1;
		$this->beer = -1;
		$this->status = -1;
		$this->update();
	}

}

function new_keg($id, $size = 1, $status = -1, $beer = -1, $location = -1) {
	global $db,$dbprefix;
	$query = "INSERT INTO " . $dbprefix . "kegs(id, status, beer, location, size) VALUES(" . $id . "," . $status . "," . $beer . "," . $location . "," . $size . ")";
	if (!$db->query($query)) {
		echo "<p>Error creating keg " . $id . ": #" . $db->errno . ": " . $db->error . "</p>\r";
		return FALSE;
	} else {
		return TRUE;
	}
}

// This should really never be used
function delete_keg($id,$size) {
	global $db,$dbprefix;
	$query = "DELETE FROM " . $dbprefix . "kegs WHERE id=" . $id . " AND size=" . $size;
	if (!$db->query($query)) {
		echo "<p>Error deleting keg " . $id . ": #" . $db->errno . ": " . $db->error . "</p>\r";
		return FALSE;
	} else {
		return TRUE;
	}
}

// choose your own beer adventure
function select_beer($section) {
	global $db,$dbprefix;
	
	echo "<div id=\"select\">\r\n";
	echo "<ul data-role=\"listview\">\r\n";
	$query = "SELECT id, beer FROM " . $dbprefix . "beers WHERE active=1 ORDER BY beer";
	if (!($result = $db->query($query))) echo "<p>Something's gone wrong: #" . $db->errno . ": " . $db->error . "</p>";
	while ($row = $result->fetch_assoc()) {
		echo "<li><a href=\"" . $section . ".php?beer=" . $row['id'] . "\">" . $row['beer'] . "</a><a href=\"#\" class=\"confirm\" id=\"b" . $row['id'] . "\" data-icon=\"delete\" data-theme=\"a\" title=\"Archive " . $row['beer'] . "\"></a></li>\r\n";
	}
	echo "</ul>\r\n</div>\r\n";

	// the "are you sure?" dialog
	?>
		<div id="confirm" data-role="popup" data-overlay-theme="a" class="ui-corner-all">
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
	global $db,$dbprefix;

	echo "<div id=\"select\">\r\n";
	echo "<ul data-role=\"listview\">\r\n";
	$query = "SELECT id, location FROM " . $dbprefix . "locations WHERE active=1 ORDER BY location";
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
	global $db,$dbprefix;
	// get the beers to look up 
	// but don't bother if it's anachronistic
	if ($status > 2) {
		$query = "SELECT id, beer FROM " . $dbprefix . "beers";
		if (!($result = $db->query($query))) echo "<p>Something's gone wrong: #" . $db->errno . ": " . $db->error . "</p>";
		while ($row = $result->fetch_assoc()) $beers[$row['id']] = $row['beer'];
	}

	// get the locations to look up
	// but don't bother if it's anachronistic
	if ($status == 5) {
		$query = "SELECT id, location FROM " . $dbprefix . "locations";
		if (!($result = $db->query($query))) echo "<p>Something's gone wrong: #" . $db->errno . ": " . $db->error . "</p>";
		while ($row = $result->fetch_assoc()) $locations[$row['id']] = $row['location'];
	}

	// get the keg sizes to look up
	$query = "SELECT id, size FROM " . $dbprefix . "keg_sizes";
	if (!($result = $db->query($query))) echo "<p>Something's gone wrong: #" . $db->errno . ": " . $db->error . "</p>";
	while ($row = $result->fetch_assoc()) $sizes[$row['id']] = $row['size'];

	// get the kegs themselves
	//$query = "SELECT id, size, beer, location FROM " . $dbprefix . "kegs WHERE status=" . $status . " OR status=-1 ORDER BY location, beer, size, id";
	$select = "SELECT " . $dbprefix . "kegs.id, size";
	$from = " FROM " . $dbprefix . "kegs";
	$where = " WHERE status=" . $status;
	$order = " ORDER BY " . $dbprefix . "kegs.id ASC";
	switch ($status) {
		case 1:
		case 2:
			//$order .= " status";
			break;
		case 3:
			$select .= ", " . $dbprefix . "kegs.beer";
			$from .= " INNER JOIN " . $dbprefix . "beers ON " . $dbprefix . "kegs.beer=" . $dbprefix . "beers.id";
			$where .= " OR status=4";
			//$order .= " " . $dbprefix . "beers.beer";
			break;
		case 4:
		case 5:
			$select .= ", " . $dbprefix . "kegs.beer, " . $dbprefix . "kegs.location";
			$from .= " INNER JOIN " . $dbprefix . "beers ON " . $dbprefix . "kegs.beer=" . $dbprefix . "beers.id";
			$from .= " INNER JOIN " . $dbprefix . "locations ON " . $dbprefix . "kegs.location = " . $dbprefix . "locations.id";
			//$order .= " " . $dbprefix . "locations.location, " . $dbprefix . "beers.beer";
			break;
	}
	$order .= ", size, " . $dbprefix . "kegs.id";
	$query = $select . $from . $where . $order;
	//die($query);
	if (!($result = $db->query($query))) echo "<p>Something's gone wrong: #" . $db->errno . ": " . $db->error . "</p>";
	while ($row = $result->fetch_assoc()) $kegs[] = new keg($row);
?>
	<div id="csv">
	<form method="post" action="?status=<?php
	echo $status;
	if ($_GET['beer']) echo "&beer=" . $_GET['beer'];
	else if ($_GET['location']) echo "&location=" . $_GET['location'];
	?>">
		<label for="size">Size</label>
		<select name="size" id="size">
		<?php
		$query = "SELECT id, size FROM " . $dbprefix . "keg_sizes ORDER BY id";
	$result = $db->query($query);
	while ($row = $result->fetch_assoc()) {
		echo "<option value=\"" . $row['id'] . "\"";
		if ($row['id'] == 5) echo " selected=\"selected\""; // hacky
		echo ">" . $row['size'] . "</option>";
	}
	?>
		</select>
	<label for="keglist">Kegs</label>
	<input type="text" pattern="[0-9 .]+" name="keglist" id="keglist" />
	<button type="submit" data-inline="true" data-theme="b">Submit</button>
	</form>
	<p>(period separated, do not have to be in the below list; or:</p>
	</div>

	<div id="kegs">
	<form method="post" action="update.php?status=<?php echo $status . "&" . $_SERVER['QUERY_STRING']; ?>">
	<div data-role="fieldcontain">
	<fieldset data-role="controlgroup">
	<?php
	echo "<legend>Kegs to update</legend>\r\n";

	foreach ($kegs as $keg) {
		$id = $keg->getid();
		$intsize = $keg->getsize();
		$formid = $id . "_" . $intsize;
		$size = $sizes[$intsize];
		$beer = $keg->getbeer();
		$location = $keg->getlocation();

		echo "<input type=\"checkbox\" id=\"keg" . $formid . "\" name=\"kegs[" . $formid . "]\" /><label for=\"keg" . $formid . "\">" . $size . "  #" . $id;
		if ($beers || $locations) {
			echo " (";
			if ($locations) echo $locations[$location];
			if ($locations && $beers) echo "/";
			if ($beers) echo $beers[$beer];
			echo ")";
		}
		echo "</label>\r\n";
	}
		?>
			</fieldset>
			</div>
			<button type="submit"  data-theme="b">Submit</button>
			</form>
			</div>
			<!-- the success message -->
			*<div id="success" data-role="popup" data-overlay-theme="a" class="ui-corner-all">
			<div data-role="header" class="ui-corner-top">
			<h1>Success!</h1>
			</div>

			<div data-role="content" class="ui-corner-bottom ui-content">
			<div id="message"></div>
			<a href="/" data-role="button" data-theme="b">Home</a>
			</div>
			</div>

			<?php
}

// send a Pushover alert if something important happened.
// eventually I'll let you send this to people who aren't me
// and probably support email, etc
function send_alert($title = "Keg tracker alert", $message, $priority = 0) {
	$alert['token'] = "5DmKP2l58HoqvFGda4zWcnBmLYm3MX";
	$alert['user'] = $pushover; // CHANGE THIS: just sends to Dan
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
