<?php 
require('header.inc.php');
require('functions.php');
?>
<div data-role="header">
<a href="/" data-type="button" data-theme="b" data-icon="home">Home</a>
<h1>Choose beer</h1>
<a href="#new" data-rel="popup" data-type="button" data-theme="b" data-icon="plus" data-inline="true" data-position-to="window" data-transition="slideup" title="new beer">New</a>
</div>

<div data-role="content">
<?php select_beer($_GET['action']); ?>
</div>

<div id="new" data-role="popup" data-overlay-theme="a" class="ui-corner-all">
<div data-role="header" class="ui-corner-top">
<h1>New beer</h1>
</div>
<div data-role="content" class="ui-corner-bottom ui-content">
<form action="beer.php" method="post">
<label for="beer">Beer name:</label>
<input type="text" name="beer" id="beer" value="" />

<button type="submit" data-inline="true" data-theme="b">Submit</button>
</form>
</div>
</div>
<?php require('footer.inc.php'); ?>
