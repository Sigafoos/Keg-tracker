<?php 
require('functions.php');
?>
<div id="beer">
<div class="toolbar">
<a class="back" href="#home">Back</a>
<a class="button slideup" href="#newbeer">+</a>
<h1>Choose beer</h1>
</div>
<?php select_beer($_GET['section']); ?>
</div>
<?php require('footer.inc.php'); ?>
