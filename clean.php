<?php require('functions.php'); ?>
<div id="clean">
<div class="info"><?php echo date("h:m:s"); ?></div>
<?php 
if (!function_exists("select_kegs")) echo "etf";
select_kegs(2); ?>
</div>
