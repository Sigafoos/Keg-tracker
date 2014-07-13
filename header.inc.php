<!DOCTYPE html>
<html lang="en">
<head>
<title>Kegs!</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="apple-touch-icon" href="icon.png"/>
<meta name="apple-mobile-web-app-capable" content="yes" />
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css" />
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
<script src="custom.js"></script>
<body>
<div data-role="page">
<?php 
if (file_exists("install.php") && $_SERVER['PHP_SELF'] != "/install.php") {
	?>
		<div data-role="header">
		<h1>Error</h1>
		</div>
		<div data-role="content">
		Install file exists. If this is your first time on the site, fill out config.inc.php and then install. If you've already installed, remove install.php.

		<p><a href="install.php" data-role="button" data-theme="b">Install</a></p>
		</div>
		<?php
	require('footer.inc.php');
	die();
}
?>
