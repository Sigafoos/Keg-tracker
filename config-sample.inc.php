<?php
// where's the database info located?
// if local, edit the line below
$db = new mysqli("localhost","username","password","database");
// otherwise, uncomment and add the path here
//require('../db_connect.php');

// what table prefix should you use?
$dbprefix = "kegsite_";

<<<<<<< HEAD
/* what warnings do you want to see?
   0 = none
   1 = editing a keg with a warning
   2 = editing a keg with the wrong status (ie cleaning a keg marked as full, not in use)
   */
$warninglevel = 1;

// using Pushover? enter your API key
$pushover = "";
?>
