<?php
require('config.inc.php');

if (!$db) die("Fatal error: no database information specified");
if (!$dbprefix) die("Fatal error: no database prefix specified");

// beer
$query = "CREATE TABLE IF NOT EXISTS " . $dbprefix . "beers (id int auto_increment primary key, beer varchar(100) not null, active int(1) default 1)";
if (!($result = $db->query($query))) die("Fatal error creating table " . $dbprefix . "beers: #" . $db->errno . ": " . $db->error);

// log
$query = "CREATE TABLE IF NOT EXISTS " . $dbprefix . "keg_log (keg_id int, size int, date datetime not null, status int default -1, location int default -1, beer int default -1, warning int default -1)";
if (!($result = $db->query($query))) die("Fatal error creating table " . $dbprefix . "keg_log: #" . $db->errno . ": " . $db->error);
$query = "ALTER TABLE " . $dbprefix . "keg_log ADD primary key(keg_id, size, date)";
if (!($result = $db->query($query))) die("Fatal error setting primary key for table " . $dbprefix . "keg_log: #" . $db->errno . ": " . $db->error);

// keg sizes
$query = "CREATE TABLE IF NOT EXISTS " . $dbprefix . "keg_sizes (id int auto_increment primary key, size varchar(25) not null)";
if (!($result = $db->query($query))) die("Fatal error creating table " . $dbprefix . "keg_sizes: #" . $db->errno . ": " . $db->error);
$query = "INSERT IGNORE INTO " . $dbprefix . "keg_sizes SET id=1, status='1/6 bbl'";
$db->query($query);
$query = "INSERT IGNORE INTO " . $dbprefix . "keg_sizes SET id=2, status='1/2 bbl'";
$db->query($query);
$query = "INSERT IGNORE INTO " . $dbprefix . "keg_sizes SET id=3, status='Cask'";
$db->query($query);
$query = "INSERT IGNORE INTO " . $dbprefix . "keg_sizes SET id=4, status='Pin'";
$db->query($query);

// statuses
$query = "CREATE TABLE IF NOT EXISTS " . $dbprefix . "keg_statuses (id int auto_increment primary key, status varchar(50) not null)";
if (!($result = $db->query($query))) die("Fatal error creating table " . $dbprefix . "keg_statuses: #" . $db->errno . ": " . $db->error);
$query = "INSERT IGNORE INTO " . $dbprefix . "keg_statuses SET id=1, status='Dirty'";
$db->query($query);
$query = "INSERT IGNORE INTO " . $dbprefix . "keg_statuses SET id=2, status='Empty'";
$db->query($query);
$query = "INSERT IGNORE INTO " . $dbprefix . "keg_statuses SET id=3, status='Filled'";
$db->query($query);
$query = "INSERT IGNORE INTO " . $dbprefix . "keg_statuses SET id=5, status='In use'";
$db->query($query);
$query = "INSERT IGNORE INTO " . $dbprefix . "keg_statuses SET id=6, status='Broken'";
$db->query($query);
$query = "INSERT IGNORE INTO " . $dbprefix . "keg_statuses SET id=-1, status='Unknown'";
$db->query($query);

// warnings
$query = "CREATE TABLE IF NOT EXISTS " . $dbprefix . "keg_warnings (id int auto_increment primary key, warning varchar(100) not null)";
if (!($result = $db->query($query))) die("Fatal error creating table " . $dbprefix . "keg_warnings: #" . $db->errno . ": " . $db->error);
$query = "INSERT IGNORE INTO " . $dbprefix . "keg_warnings SET id=-1, warning='None'";
$db->query($query);
$query = "INSERT IGNORE INTO " . $dbprefix . "keg_warnings SET id=2, warning='Duplicate?'";
$db>query($query);
$query = "INSERT IGNORE INTO " . $dbprefix . "keg_warnings SET id=3, warning='Poured foamy'";
$db>query($query);
$query = "INSERT IGNORE INTO " . $dbprefix . "keg_warnings SET id=4, warning='Infected beer'";
$db>query($query);
$query = "INSERT IGNORE INTO " . $dbprefix . "keg_warnings SET id=5, warning='Broken'";
$db->query($query);

// kegs
$query = "CREATE TABLE IF NOT EXISTS " . $dbprefix . "kegs (id int auto_increment primary key, location int default -1, status int default -1, size int not null default 1, beer int default -1, warning int default -1)";
if (!($result = $db->query($query))) die("Fatal error creating table " . $dbprefix . "kegs: #" . $db->errno . ": " . $db->error);

// locations
$query = "CREATE TABLE IF NOT EXISTS " . $dbprefix . "keg_locations (id int auto_increment primary key, location varchar(75) not null, active int(1) default 1)";
if (!($result = $db->query($query))) die("Fatal error creating table " . $dbprefix . "keg_locations: #" . $db->errno . ": " . $db->error);

require('header.inc.php'); 
require('functions.php');
?>
<div data-role="header">
<h1>Install successful</h1>
</div>

<div data-role="content">
You may go about your merry way. Delete this file and then head to the index.

<p><a href="index.php" data-role="button" data-inline="true" data-theme="b">Home</a></p>
</div>
<?php require('footer.inc.php'); ?>
