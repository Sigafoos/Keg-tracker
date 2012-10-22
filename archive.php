<?php
require('functions.php');

if ($_POST['beer']) $query = "UPDATE cbw_beers SET active=0 WHERE id=" . $_POST['beer'];
else if ($_POST['location']) $query = "UPDATE cbw_locations SET active=0 WHERE id=" . $_POST['location'];

if (!($result = $db->query($query))) echo "<p>Something's gone wrong: #" . $db->errno . ": " . $db->error . "</p>";
?>
