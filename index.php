<!DOCTYPE html>
<head>
<title>Kegs!</title>
</head>
<body>
<h1>Keg statuses</h1>

<?php 
require('functions.php');
$query = "SELECT id, location, status, beer, size FROM cbw_kegs ORDER BY size ASC, id";
$result = $db->query($query);
if (!$result) echo "<p>Error #" . $db->errno . ": " . $db->error . "</p>";
while ($row = $result->fetch_assoc()) {
	$keg = new keg($row);
	$keg->info();
}
?>

</body>
</html>
