<?php
require('functions.php');
// things will happen!
?>
<div id="update">
<div class="toolbar">
<h1>Update</h1>
<a href="#home" class="button">Home</a>
</div>
<?php
$i = 0;
foreach ($_POST['kegs'] as $info=>$whatever) {
	// [0] = id; [1] = size
	$info = explode("_",$info);
	$query = "SELECT id, location, status, size, beer FROM cbw_kegs WHERE id=" . $info[0] . " AND size=" . $info[1];
	$result = $db->query($query);
	$keg = new Keg($result->fetch_assoc());

	switch($_GET['status']) {
		case 1: // cleaning dirty kegs
			$keg->clean();
			break;

		case 2: // fill clean kegs
			$keg->fill($_GET['beer']);
			break;

		default:
			echo "I'm not sure what to do with status " . $_GET['status'];
			break;
	}

	$i++;
}

echo "<div class=\"info\">" . $i . " keg";
if ($i > 1) echo "s";
echo " processed.</div>\r\n";

?>
</div>
