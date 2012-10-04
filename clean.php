<?php 
require('functions.php');
?>
<div id="clean">
<div class="toolbar">
<a class="back" href="#home">Back</a>
<h1>Choose kegs</h1>
</div>
<div class="info"><?php echo $_GET['beer']; ?></div>
<?php select_kegs(2); ?>
</div>
