<?php
require('functions.php');
$query = "SELECT id FROM cbw_kegs WHERE size=" . $_POST['size'];
if (!($result = $db->query($query))) echo "<p>Oh my: #" . $db->errno . ": " . $db->error . "</p>\r";
while ($row = $result->fetch_assoc()) echo "<option value=\"" . $row['id'] . "\">" . $row['id'] . "</option>";
?>
