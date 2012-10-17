<?php 
require('header.inc.php'); 
require('functions.php');
?>
<div data-role="header">
<h1>Kegs!</h1>
</div>

<?php
$query = "SELECT count(id) AS number, status FROM cbw_kegs GROUP BY status ORDER BY status";
if (!($result = $db->query($query))) echo "<p>Oh my: #" . $db->errno . ": " . $db->error . "</p>\r";
while ($row = $result->fetch_assoc()) $stats[$row['status']] = $row['number'];
?>

<div data-role="content">
<ul data-role="listview" data-inset="true">
<li><a href="clean.php">Clean</a> <span class="ui-li-count"><?php echo (int)$stats[1]; ?></span></li>
<li><a href="beer.php?action=fill">Fill <span class="ui-li-count"><?php echo (int)$stats[2]; ?></span></a></li>
<li><a href="carbonate.php">Carbonate <span class="ui-li-count"><?php echo (int)$stats[3]; ?></span></a></li>
<li><a href="location.php?action=deliver">Deliver <span class="ui-li-count"><?php echo (int)$stats[4]; ?></span></a></li>
<li><a href="return.php">Return <span class="ui-li-count"><?php echo (int)$stats[5]; ?></span></a></li>
</ul>
</div>
<?php require('footer.inc.php'); ?>
