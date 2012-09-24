<?php require('header.inc.php'); ?>
<h1>Keg statuses</h1>

<?php 
if ($_GET['action'] == "clean") {
	echo "to do";
} else if ($_GET['action'] == "fill") {
	$query = "SELECT id, size FROM cbw_kegs WHERE status=2 ORDER BY size, id";
	if (!($result = $db->query($query))) echo "<p>Something's gone wrong: #" . $db->errno . ": " . $db->error . "</p>";
	while ($row = $result->fetch_assoc()) $kegs[] = new keg($row);
?>
	<p>Fill 'er up! Here are your choices:</p>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<fieldset>
	<?php
	foreach ($kegs as $keg) {
		$id = $keg->getid();
		$intsize = $keg->getintsize();
		$size = $keg->getsize();
		echo "<label for=\"" . $id . "-" . $intsize . "\">" . $size . "  keg #" . $id . "</label><checkbox name=\"" . $id . "-" . $intsize . "\" id=\"keg[]\" /><br />\r";
	}
		?>
			</fieldset>
			</form>
			<?php
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
require('footer.inc.php');
?>
