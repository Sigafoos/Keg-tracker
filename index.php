<!DOCTYPE html>
<head>
<title>Kegs!</title>
</head>
<body>
<h1>Keg statuses</h1>

<table border="1">
<tr>
<th scope="col">ID</th>
<th scope="col">Status</th>
<th scope="col">Beer</th>
<th scope="col">Location</th>
<th scope="col">Size</th>
<?php 
require('functions.php');
$query = "SELECT cbw_kegs.id, cbw_locations.location, cbw_keg_statuses.status, cbw_beers.beer, cbw_keg_sizes.size FROM cbw_kegs INNER JOIN cbw_locations ON cbw_kegs.location=cbw_locations.id INNER JOIN cbw_keg_statuses ON cbw_kegs.status=cbw_keg_statuses.id INNER JOIN cbw_keg_sizes ON cbw_kegs.size=cbw_keg_sizes.id LEFT OUTER JOIN cbw_beers ON cbw_kegs.beer=cbw_beers.id ORDER BY size DESC, id";
$result = $db->query($query);
if (!$result) echo "<p>Error #" . $db->errno . ": " . $db->error . "</p>";
while ($row = $result->fetch_assoc()) {
	echo "<tr>\r";
	echo "<td>" . $row['id'] . "</td>\r";
	echo "<td>" . $row['status'] . "</td>\r";
	echo "<td>" . $row['beer'] . "</td>\r";
	echo "<td>" . $row['location'] . "</td>\r";
	echo "<td>" . $row['size'] . "</td>\r";
	echo "</tr>\r";
}
?>
</table>

</body>
</html>
