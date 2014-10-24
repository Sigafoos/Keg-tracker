<?php
require('functions.php');
require('header.inc.php');
?>
<div data-role="header">
<a href="/" data-type="button" data-theme="b" data-icon="home">Home</a>

<?php

//$_GET['report'] = 1; // hard coding til we have more

if (!is_numeric($_GET['report'])) {
	?>
		<h1>Choose report</h1>
		</div>
		<div data-role="content">
		<p>Um maybe add stuff here</p>
		</div>
		<?php
} else {
	if (!include('reports/' . $_GET['report'] . ".php")) {
		echo "<h1>Um</h1>\r</div>\r";
		echo "<div data-role=\"content\">\r<p>How did you get here?</p>\r</div>\r";
	}

}

require('footer.inc.php');

function print_kegs($kegs) {
	?>
		</div>

		<div data-role="content">
		<table data-role="table" class="ui-responsive table-stroke">
		<thead>
		<tr>
		<th>ID</th>
		<th>Last edited</th>
		<th>Status</th>
		<th>Location</th>
		<th>Beer</th>
		<th>Warning</th>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach ($kegs as $keg) {
			echo "<tr>\r";
			echo "<th><a data-role=\"button\" data-theme=\"e\" data-inline=\"true\" href=\"/keg.php?size=" . $keg['size_id'] . "&amp;id=" . $keg['keg_id'] . "\">" . $keg['size'] . " " . $keg['keg_id']. "</a></th>\r";
			echo "<td>" . date("m/d/y",strtotime($keg['date'])) . "</td>\r";
			echo "<td>" . $keg['status'] . "</td>\r";
			echo "<td>" . $keg['location'] . "</td>\r";
			echo "<td>" . $keg['beer'] . "</td>\r";
			echo "<td>" . $keg['warning'] . "</td>\r";
			echo "</tr>\r";
		}
	?>
		</tbody>
		</table>

		</div>
		<?php
}
?>
