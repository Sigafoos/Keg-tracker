<?php
require('config.inc.php');

if (!$db) die("Fatal error: no database information specified");
if (!$dbprefix) die("Fatal error: no database prefix specified");

$query = "CREATE TABLE " . $dbprefix . "beers (id int auto_increment primary key, beer varchar(100) not null, active int(1) default 1)";
if (!($result = $db->query($query))) die("Fatal error creating table " . $dbprefix . "beers: #" . $db->errno . ": " . $db->error);

$query = "CREATE TABLE " . $dbprefix . "keg_log (keg_id int primary key, size int primary key, date datetime not null primary key, status int default -1, location int default -1, beer int default -1)";
if (!($result = $db->query($query))) die("Fatal error creating table " . $dbprefix . "keg_log: #" . $db->errno . ": " . $db->error);

$query = "CREATE TABLE " . $dbprefix . "keg_sizes (id int auto_increment primary key, size varchar(25) not null)";
if (!($result = $db->query($query))) die("Fatal error creating table " . $dbprefix . "keg_sizes: #" . $db->errno . ": " . $db->error);
die("bye\n");
$query = "CREATE TABLE " . $dbprefix . "keg_statuses (id int auto_increment primary key, beer varchar(100) not null, active int(1) default 1)";
if (!($result = $db->query($query))) die("Fatal error creating table " . $dbprefix . "beers: #" . $db->errno . ": " . $db->error);

$query = "CREATE TABLE " . $dbprefix . "beers (id int auto_increment primary key, beer varchar(100) not null, active int(1) default 1)";
if (!($result = $db->query($query))) die("Fatal error creating table " . $dbprefix . "beers: #" . $db->errno . ": " . $db->error);

