<?php 
require('functions.php');
?>
<div id="location">
<div class="toolbar">
<a class="back" href="#home">Back</a>
<a class="button slideup" href="#newlocation">+</a>
<h1>Choose location</h1>
</div>
<?php select_location($_GET['section']); ?>
</div>
