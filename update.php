<?php
require('functions.php');
// things will happen!
?>
<div id="update">
<div class="toolbar">
<h1>Update</h1>
<a href="#home" class="button">Home</a>
</div>
<div class="info">
<?php
switch($_GET['status']) {
	// cleaning dirty kegs
	case 1:
		$i = 0;
		foreach ($_POST['kegs'] as $info=>$whatever) {
			// [0] = id; [1] = size
			$info = explode("_",$info);
			$query = "SELECT id, location, status, size, beer FROM cbw_kegs WHERE id=" . $info[0] . " AND size=" . $info[1];
			$result = $db->query($query);
			$keg = new Keg($result->fetch_assoc());

			$keg->clean();
			$i++;
		}

		echo "<div class=\"info\">" . $i . " kegs processed.</div>\r\n";

		break;
	default:
		?>
			<pre>
			<?php print_r($_POST); ?>
			</pre>
			<?php
			break;
}
?>
</div>
</div>
