<?php 
require('header.inc.php');
require('functions.php');
?>
<div data-role="header">
<a href="#" data-rel="back" data-type="button" data-theme="b" data-icon="arrow-l">Back</a>
<h1>Choose location</h1>
<a href="#new" data-rel="popup" data-type="button" data-theme="b" data-icon="plus" data-inline="true" data-position-to="window" data-transition="slideup" title="new location">New</a>
</div>

<div data-role="content">
<?php select_location($_GET['action']); ?>
</div>

<div id="new" data-role="popup" data-overlay-theme="a" class="ui-corner-all">
<div data-role="header" class="ui-corner-top">
<h1>New location</h1>
</div>
<div data-role="content" class="ui-corner-bottom ui-content">
<form action="location.php" method="post">
<label for="location">Location:</label>
<input type="text" name="location" id="location" value="" />

<button type="submit" data-inline="true" data-theme="b">Submit</button>
</form>
</div>
</div>
<?php require('footer.inc.php'); ?>
