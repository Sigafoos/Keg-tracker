<?php
// where's the database info located?
// if local, edit the line below
$db = new mysqli("localhost","username","password","database");
// otherwise, uncomment and add the path here
//require('../db_connect.php');

// what table prefix should you use?
$dbprefix = "kegsite_";

// using Pushover? enter your API key
$pushover = "";
?>