<?php 
require('functions.php');

if ($_POST['update']) {
	$query = "SELECT max(id) AS id FROM " . $dbprefix . "kegs WHERE size=" . $_POST['size'];
	if (!($result = $db->query($query))) echo "<p>Oh my: #" . $db->errno . ": " . $db->error . "</p>\r";
	$row = $result->fetch_assoc();
	echo "<input type=\"number\" pattern=\"[0-9]*\" name=\"id\" id=\"id\" value=\"" . ($row['id']+1) . "\" class=\"ui-input-text ui-body-c ui-corner-all ui-shadow-inset\" />";
	die();
}

require('header.inc.php');

$query = "SELECT id, size FROM " . $dbprefix . "keg_sizes ORDER BY id";
if (!($result = $db->query($query))) echo "<p>Oh my: #" . $db->errno . ": " . $db->error . "</p>\r";
while ($row = $result->fetch_assoc()) $sizes[$row['id']] = $row['size'];

$query = "SELECT max(id) AS id FROM " . $dbprefix . "kegs WHERE size=1";
if (!($result = $db->query($query))) echo "<p>Oh my: #" . $db->errno . ": " . $db->error . "</p>\r";
$row = $result->fetch_assoc();
$maxid = $row['id'] + 1;

$query = "SELECT id, status FROM " . $dbprefix . "keg_statuses";
if (!($result = $db->query($query))) echo "<p>Oh my: #" . $db->errno . ": " . $db->error . "</p>\r";
while ($row = $result->fetch_assoc()) $statuses[$row['id']] = $row['status'];
?>
<div data-role="header">
<a href="/" data-type="button" data-theme="b" data-icon="home">Home</a>
<h1>New keg</h1>
</div>

<div data-role="content">
<form action="" method="post" id="newkeg">
<div data-role="fieldcontain">
<label for="size">Size</label>
<select name="size" id="size">
<?php foreach($sizes as $id=>$size) echo "<option value=\"" . $id . "\">" . $size . "</option>\r\n"; ?>
</select>
</div>

<div data-role="fieldcontain">
<label for="id">First id</label>
<input type="number" pattern="[0-9]*" name="id" id="id" value="<?php echo $maxid; ?>"/>
</div>

<div data-role="fieldcontain">
<label for="end">Second id</label>
<input type="number" pattern="[0-9]*" name="end" id="end" placeholder="Leave blank if only adding one keg" />
</div>

<div data-role="fieldcontain">
<label for="status">Status</label>
<select name="status" id="status">
<?php foreach($statuses as $id=>$status) echo "<option value=\"" . $id . "\">" . $status . "</option>\r\n"; ?>
</select>
</div>

<button type="submit" data-theme="b">Submit</button>

</form>

<!-- the success message -->
<div id="success" data-role="popup" data-overlay-theme="a" class="ui-corner-all">
<div data-role="header" class="ui-corner-top">
<h1>Success!</h1>
</div>

<div data-role="content" class="ui-corner-bottom ui-content">
<div id="message"></div>
<a href="/" data-role="button" data-theme="b">Home</a>
</div>
</div>
</div>

<?php require('footer.inc.php'); ?>

