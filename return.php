<?php 
require('header.inc.php');
require('functions.php');
?>
<div data-role="header">
<h1>Choose kegs</h1>
</div>
<div data-role="content">
<?php select_kegs(5); ?>
</div>

<?php require('footer.inc.php'); ?>
