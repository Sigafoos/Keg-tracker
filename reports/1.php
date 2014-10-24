<?php
echo "<h1>Forgotten kegs</h1>\r";
$query = "SELECT a.keg_id, a.size AS size_id, " . $dbprefix . "keg_sizes.size, a.date, " . $dbprefix . "keg_statuses.status, " . $dbprefix . "locations.location, " . $dbprefix . "beers.beer, " . $dbprefix . "keg_warnings.warning";
$query .= " FROM " . $dbprefix . "keg_log a";
$query .= " JOIN (SELECT keg_id, size, max(date) AS date FROM " . $dbprefix . "keg_log GROUP BY keg_id, size) b"; // 6 = broken
$query .= " ON a.keg_id=b.keg_id AND a.size=b.size AND a.date=b.date";
$query .= " INNER JOIN " . $dbprefix . "keg_sizes ON a.size=" . $dbprefix . "keg_sizes.id";
$query .= " INNER JOIN " . $dbprefix . "keg_statuses on a.status=" . $dbprefix . "keg_statuses.id";
$query .= " INNER JOIN " . $dbprefix . "locations ON a.location=" . $dbprefix . "locations.id";
$query .= " INNER JOIN " . $dbprefix . "beers ON a.beer=" . $dbprefix . "beers.id";
$query .= " INNER JOIN " . $dbprefix . "keg_warnings ON a.warning=" . $dbprefix . "keg_warnings.id";
$query .= " WHERE b.date < NOW() - INTERVAL 45 DAY AND a.status != 6 ORDER BY a.date ASC";
if (!($result = $db->query($query))) {
	echo "<p>Error getting unused kegs: #" . $db->errno . ": " . $db->error . "</p>\r";
}

while ($row = $result->fetch_assoc()) $kegs[] = $row;
print_kegs($kegs);
?>
