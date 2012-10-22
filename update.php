<?php
require('header.inc.php');
require('functions.php');
$warnings = array();
// things will happen!
?>
<div data-role="header">
<h1>Update</h1>
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
		case 1:
			$keg->clean();
			break;

		case 2:
			$keg->fill($_GET['beer']);
			break;

		case 3: 
			$keg->carbonate();
			break;

		case 4:
			$keg->deliver($_GET['location']);
			break;

		case 5:
			$keg->pickup();
			break;

		default:
			echo "I'm not sure what to do with status " . $_GET['status'];
			break;
	}

	$i++;
}

if (count($warnings)) echo "<h2>Warnings</h2>\r\n<ul>\r\n<li>" . implode("</li>\r\n<li>",$warnings) . "</ul>\r";
echo "<p>" . $i . " keg";
if ($i > 1) echo "s";
echo " processed.</p>\r\n";

?>
<p><a href="/" data-role="button" data-theme="b">Home</a></p>
</div>

<?php require('footer.inc.php'); ?>
