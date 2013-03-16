<?php 
require('header.inc.php');
require('functions.php');
?>
<div data-role="header">
<a href="/" data-type="button" data-theme="b" data-icon="home">Home</a>
<h1>Choose kegs</h1>
</div>

<div data-role="content">
<?php select_kegs(1); ?>
</div>

<?php require('footer.inc.php'); ?>
