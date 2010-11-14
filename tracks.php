<?php
/* Heard
 *
 * by Pete Prodoehl <pete@rasterweb.net>
 *
 * Released under the GPL
 *
 * We should really explain more...
 *
 */

include_once("init.php");
date_default_timezone_set('America/Chicago'); // GMT

?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Heard</title>

<style type="text/css">

body {
	margin: 30px;
	font-size: 11px;
	font-family: 'Lucida Grande', Verdana, Geneva, Lucida, Arial, Helvetica, sans-serif;
	color: #666;
}

h1 {
	float: left;
	font-size: 14px;
	margin: 4px 0;	
}

h2 {
	float: right;
	font-size: 10px;
	font-weight: normal;
	margin: 4px 0;
}

table {
	width: 100%;
	border-spacing: 0;
	border-collapse: 0;
}

table tr td {
	margin: 0;
	padding: 4px 8px;
	border-bottom: 1px #ccc solid;
}

table tr td.date {
	text-align: right;	
}

table#tracks tr:hover td { 
	background: #bfdff4;
}

/* thanks to Michael Raichelson for CSS help */

a:link, a:visited, a:active, a:hover {
	color: #333;
	text-decoration: none;
}

a:active, a:hover {
	color: #66f;
	text-decoration: underline;
}

</style>

<?php /* oh, you'll need jQuery here...  */ ?>
<script type="text/javascript" src="js/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function()
{
  $("tr:even").css("background-color", "#ebebeb");
  $("tr:odd").css("background-color", "#ffffff");

});
</script>

</head>
<body>


<?php

// how many rows to show per page
$rowsperpage = 200;

// by default we show first page
$pagenum = 1;

// if $_GET['page'] defined, use it as page number
if(isset($_GET['page']))
{
    $pagenum = $_GET['page'];
}

// counting the offset
$offset = ($pagenum - 1) * $rowsperpage;

// how many tracks
$dbcount = get_count();

if (isset($dbcount)) {
	foreach($dbcount as $row) {
		$thecount = $row['count'];
	}
}

$pagenumnext = $pagenum + 1;
$pagenumprev = $pagenum - 1;

if ($pagenumprev < 1) {
	$pagenumprev = 1;
}

?>

<div style="width: 90%; margin: 10px auto">

<h1><a href="./">Heard</a></h1>

<?php /* you may want to change this so it points to your own account */ ?>
<h2><a href="http://www.last.fm/user/rasterweb">visit rasterweb on Last.fm</a></h2>

<p style="float: left; clear: both">
<?php echo $thecount ?> tracks scrobbled.
</p>

<p style="float: right">
<a href="?page=<?php echo $pagenumprev ?>">&laquo; prev</a> | 
<a href="?page=<?php echo $pagenumnext ?>">next &raquo;</a>
</p>

<table id="tracks" style="clear: both">

<?php

$dbitems = get_tracks($offset, $rowsperpage);

if (isset($dbitems)) {
	foreach($dbitems as $row) {
		
		echo "<tr>\n";
		echo "<td><a href=\"" . $row['urlartist'] . "\">" . substr($row['artist'], 0, 80) . "</a> - ";
		echo "<a href=\"" . $row['urltrack'] . "\">" . substr($row['name'], 0, 120) . "</a></td>\n";
		echo "<td class=\"date\">" . date("F j, Y, g:i a", (int) $row['dateuts']) . "</td>\n";
		echo "</tr>\n";
	}
}
else {
	echo "<tr><td colspan=\"2\">Sorry, nothing to display...</td></tr>";
}

?>

</table>

<p style="float: left">
<?php echo $thecount ?> tracks scrobbled.
</p>

<p style="float: right">
<a href="?page=<?php echo $pagenumprev ?>">&laquo; prev</a> | 
<a href="?page=<?php echo $pagenumnext ?>">next &raquo;</a>
</p>

<p style="text-align: center; clear: both">
<?php /* feel free to change or removed this... or leave it as-is */ ?>
Heard was created by Pete Prodoehl and uses <a href="http://last.fm/">Last.fm</a> and it's associated API to get the data.
</p>

</div>

</body>
</html>
