<?php 
require('header.inc.php');
require('functions.php');
?>
<div data-role="header">
<h1>Choose beer</h1>
</div>

<div data-role="content">
<?php select_beer($_GET['action']); ?>
</div>
<?php require('footer.inc.php'); ?>
