<?php require('header.inc.php'); ?>
<h1>Keg statuses</h1>

<?php 
if ($_GET['action'] == "clean") {
	echo "to do";
} else if ($_GET['action'] == "fill") {
	if (!$GET['beer']) select_beer();
	else select_kegs(2);
} else if ($_GET['action'] == "carbonate") {
	echo "to do";
} else if ($_GET['action'] == "deliver") {
	echo "to do";
} else if ($_GET['action'] == "pickup") {
	echo "to do";
} else {
?>
	<p>What'll it be, mister?</p>

	<ul>
	<li><a href="?action=clean">Clean</a></li>
	<li><a href="?action=fill">Fill</a></li>
	<li><a href="?action=carbonate">Carbonate</a></li>
	<li><a href="?action=deliver">Deliver</a></li>
	<li><a href="?action=pickup">Pick Up</a></li>
	</ul>
<?php
}
function select_kegs($status) {
	global $db;

	$query = "SELECT id, size FROM cbw_kegs WHERE status=" . $status . " ORDER BY size, id";
	if (!($result = $db->query($query))) echo "<p>Something's gone wrong: #" . $db->errno . ": " . $db->error . "</p>";
	while ($row = $result->fetch_assoc()) $kegs[] = new keg($row);
?>
	<p>Fill 'er up! Here are your choices:</p>
	<div id="formwrapper">
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<fieldset>
	<?php
	$query = "SELECT id, size FROM cbw_keg_sizes";
	if (!($result = $db->query($query))) echo "<p>Something's gone wrong: #" . $db->errno . ": " . $db->error . "</p>";
	while ($row = $result->fetch_assoc()) $sizes[$row['id']] = $row['size'];

	foreach ($kegs as $keg) {
		$id = $keg->getid();
		$intsize = $keg->getsize();
		$size = $sizes[$intsize];
		//echo "<input type=\"radio\" id=\"" . $id . "_
		echo "<checkbox name=\"" . $id . "_" . $intsize . "\" id=\"keg[]\" /><label for=\"" . $id . "_" . $intsize . "\">" . $size . "  keg #" . $id . "</label><br />\r";
	}
		?>
			</fieldset>
			</form>
			</div>
			<?php
}

// I don't see this being used more than once, but if it is...
function select_beer() {
	echo "WHICH BEER?";
}
require('footer.inc.php');
?>
