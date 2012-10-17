<?php 
require('header.inc.php'); 
require('functions.php');
?>
<div data-role="header">
<h1>Kegs!</h1>
</div>

<div data-role="content">
<h2>Actions</h2>
<ul data-role="listview" data-inset="true">
<li><a href="clean.php">Clean</a></li>
<li><a href="beer.php?action=fill">Fill</a></li>
<li><a href="carbonate.php">Carbonate</a></li>
<!--
<li><a href="?section=deliver#location">Deliver</a></li>
-->
<li><a href="return.php">Return</a></li>
</ul>
</div>
<!--
<div id="home">
<div class="toolbar">
<h1>Kegs!</h1>
</div>

<div class="info">
<?php
// number of clean kegs
/*
$query = "SELECT count(cbw_kegs.id) AS number, cbw_keg_statuses.status FROM cbw_kegs INNER JOIN cbw_keg_statuses ON cbw_kegs.status=cbw_keg_statuses.id WHERE cbw_kegs.status IN (1, 2) GROUP BY cbw_kegs.status ORDER BY cbw_kegs.status";
if (!($result = $db->query($query))) echo "<p>Oh my: #" . $db->errno . ": " . $db->error . "</p>\r";
while ($row = $result->fetch_assoc()) $stats[] = $row['status'] . ": " . $row['number'];

echo implode(" | ",$stats);
*/
?>
</div>

<h2>Actions</h2>
<ul class="rounded">
<li><a href="#clean">Clean</a></li>
<li><a href="?section=fill#beer">Fill</a></li>
<li><a href="?section=fill#carbonate">Carbonate</a></li>
<li><a href="?section=deliver#location">Deliver</a></li>
<li><a href="#return">Return</a></li>
</ul>

<h2>Reports</h2>
<ul class="rounded">
<li><a href="#">Stuff?</a></li>
</ul>
</div>

<div id="clean"></div>
<div id="fill"></div>
<div id="carbonate"></div>
<div id="deliver"></div>
<div id="return"></div>

<div id="beer"></div>
<div id="location"></div>
<div id="newbeer">hey</div>
<div id="newlocation"></div>
<div id="test"></div>

-->
<?php require('footer.inc.php'); ?>
