<?php require('header.inc.php'); ?>

<div id="home">
<div class="toolbar">
<h1>Kegs!</h1>
</div>

<div class="info"><?php echo date("h:m:s"); ?></div>

<ul class="rounded">
<li><a href="#clean" class="clean">Clean</a></li>
<li><a href="#fill">Fill</a></li>
</ul>
</div>

<div id="clean">
<div class="toolbar">
<a class="back" href="#home">Back</a>
<h1>Clean kegs</h1>
</div>
<div class="content"></div>

</div>

<?php require('footer.inc.php'); ?>
