<?php 
require('functions.php');
require('header.inc.php');
?>
<div data-role="header">
<a href="#" data-rel="back" data-type="button" data-theme="b" data-icon="arrow-l">Back</a>
<h1>Reactivate</h1>
</div>

<div data-role="content">
<p>Re-active a beer or location that's been archived.</p>
<div data-role="collapsible-set" data-collapsed-icon="arrow-d" data-expanded-icon="arrow-u">
<div data-role="collapsible">
<h2>Beers</h2>
<div data-role="controlgroup">
<?php
$query = "SELECT id, beer FROM " . $dbprefix . "beers WHERE active=0 AND id>0 ORDER BY beer";
if (!($result = $db->query($query))) echo "<p>Oh my: #" . $db->errno . ": " . $db->error . "</p>\r";
while ($row = $result->fetch_assoc()) echo "<a href=\"#\" id=\"b" . $row['id'] . "\" data-role=\"button\" data-icon=\"plus\" class=\"activate\">" . $row['beer'] . "</a>\r\n";
?>
</div>
</div>

<div data-role="collapsible">
<h2>Locations</h2>
<div data-role="controlgroup">
<?php
$query = "SELECT id, location FROM " . $dbprefix . "locations WHERE active=0 AND id>0 ORDER BY location";
if (!($result = $db->query($query))) echo "<p>Oh my: #" . $db->errno . ": " . $db->error . "</p>\r";
while ($row = $result->fetch_assoc()) echo "<a href=\"#\" id=\"l" . $row['id'] . "\" data-role=\"button\" data-icon=\"plus\" class=\"activate\">" . $row['location'] . "</a>\r\n";
?>
</div>
</div>
</div>
</div>

<?php require('footer.inc.php'); ?>
