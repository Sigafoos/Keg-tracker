<!DOCYTPE html>
<html>
<head>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</head>
<body>
<?php
	require("functions.php");
if (!$_GET['submit'] && !$_GET['size'] && !$_POST['barcode']) {
	$query = "SELECT id, size FROM " . $dbprefix . "keg_sizes ORDER BY id";
	$result = $db->query($query);
	while ($row = $result->fetch_assoc()) $sizes[$row['id']] = $row['size'];
	?>
		<div class="container">
		<h1>Barcode fun</h1>
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		<div class="panel panel-default">
		<div class="panel-heading" role="tab" id="headingOne">
		<h4 class="panel-title">
		<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
		Print barcodes
		</a>
		</h4>
		</div>
		<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
		<div class="panel-body">
		<ul class="list-group">
		<?php foreach ($sizes as $id=>$size) echo "<li class=\"list-group-item\"><a href=\"?size=" . $id . "\">All " . $size . "</a></li>"; ?>
		</ul>

		<p>or a subset:</p>

		<form action="" method="get">
		<div class="form-group">
		<label for="size">Size</label>
		<select name="size" id="size" class="form-control">
		<?php foreach ($sizes as $id=>$size) echo "<option value=\"" . $id . "\">" . $size . "</option>"; ?>
		</select>
		</div>

		<div class="form-group">
		<label for="kegs">Kegs to print</label>
		<input type="text" name="kegs" id="kegs" class="form-control" />
		<p>(period or comma separated)</p>
		</div>

		<div class="form-group btn-group" data-toggle="buttons">
		<label class="btn btn-default active">
		<input type="radio" name="type" id="id" value="id" checked>id (old)
		</label>
		<label class="btn btn-default">
		<input type="radio" name="type" id="barcode" value="barcode">barcode (new)
		</label>
		</div>

		<div class="form-group">
		<button type="submit" id="submit" class="btn btn-primary">Submit</button>
		</div>
		</form>
		</div>
		</div>
		</div>
		<div class="panel panel-default">
		<div class="panel-heading" role="tab" id="headingTwo">
		<h4 class="panel-title">
		<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
		Fix barcodes
		</a>
		</h4>
		</div>
		<div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
		<div class="panel-body">
		<form action="" method="post" class="form-inline">
		<div class="form-group">
		<label for="barcode" class="sr-only">Barcode:</label>
		<textarea class="form-control" id="barcode" name="barcode" placeholder="Barcode(s)"></textarea>
		</div>
		<div class="form-group">
		<label for="id" class="sr-only">id:</label>
		<textarea class="form-control" id="id" name="id" placeholder="id(s)"></textarea>
		</div>
		<div class="form-group">
		<button type="submit" id="submit" class="btn btn-primary">Submit</button>
		</div>
		</form>
		</div>
		</div>
		</div>
		</div>




		</div>
		<?php
} else if ($_GET['submit'] || $_GET['size']) {
	/*
	$query = "SELECT size FROM " . $dbprefix . "keg_sizes WHERE id=" . $_GET['size'];
	$result = $db->query($query);
	$row = $result->fetch_assoc();
	$size = $row['size'];
	*/

	require('lib/barcode/BarcodeBase.php');
	require('lib/barcode/Code128.php');
	$bcode = array('name' => 'Code128', 'obj' => new emberlabs\Barcode\Code128());
	if ($_GET['kegs']) {
		$_GET['kegs'] = preg_replace("/\./",",",$_GET['kegs']);
		$_GET['kegs'] = preg_replace("/\s+/","",$_GET['kegs']);
		$list = array_unique(explode(",",$_GET['kegs']));
		$query = "SELECT barcode FROM " . $dbprefix . "kegs WHERE " . $_GET['type'] . " IN (" . implode(",",$list) . ")";
		$result = $db->query($query);
		while ($row = $result->fetch_assoc()) $kegs[] = $row['barcode'];
	} else {
		$query = "SELECT barcode FROM " . $dbprefix . "kegs WHERE size=" . $_GET['size'];
		$result = $db->query($query);
		while ($row = $result->fetch_assoc()) $kegs[] = $row['barcode'];
	}

	foreach ($kegs as $keg) {
		try {
			$bcode['obj']->setData($keg);
			$bcode['obj']->setDimensions(300, 150);
			$bcode['obj']->draw();
			$b64 = $bcode['obj']->base64();
			// print 4 times
			for ($i = 0; $i < 4; $i++) echo "<div style=\"text-align:center; float:left; padding: .5em; border:1px solid black\"><img src='data:image/png;base64," . $b64. "' /><br />" . $keg . "</div>";
		} catch (Exception $e) {
			echo "<p>Error with keg " . $keg . ": " . $e->getMessage() . "</p>";
		}
	}
} else if ($_POST['barcode']) { // fixing the id/barcode
	?>

		<div class="container">
		<h1>Barcode fun</h1>
		<?php
	$barcodes = explode("\n",$_POST['barcode']);
	$ids = explode("\n",$_POST['id']);
	if (count($barcodes) !== count($ids)) {
		?>
			<div class="alert alert-danger" role="alert">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			<span class="sr-only">Error:</span>
			You entered <?php echo count($barcodes); ?> barcodes and <?php echo count($ids); ?> ids
			</div>
		<?php
	} else {

	for ($i = 0; $i < count($barcodes); $i++) {
		$query = "UPDATE " . $dbprefix  . "kegs SET barcode=" . $barcodes[$i] . " WHERE id=" . $ids[$i];
		$db->query($query);
	}
	?>
		<div class="alert alert-success" role="alert">
		<span class="glyphicon glyphicon-exclamation-okay" aria-hidden="true"></span>
		<span class="sr-only">Success!</span>
		<?php echo count($barcodes); ?> kegs updated.
		</div>
		<?php
	}
	?>
		<a class="btn btn-default" href="barcode.php" role="button">Back</a>
		</div>
		<?php
}

?>
</body>
</html>
