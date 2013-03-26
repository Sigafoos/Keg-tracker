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

<a href="javascript:void(0)" id="done" data-role="button" data-inline="true" data-theme="b">I'm all done, there are no more kegs</a>
</div>

<?php require('footer.inc.php'); ?>
