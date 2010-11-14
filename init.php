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

require_once("config.php");

$db_connection = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Cannot connect to database. Check your configuration.  MySQL says: " . mysql_error());

mysql_select_db(DB_DBNAME, $db_connection) or die("Cannot select database. Check your configuration.  MySQL says: " .  mysql_error());

$DB_TABLE = DB_TABLE;

function do_query($sql, $live=0) {
	
	global $db_connection;
	
	// echo "[$sql]";
	
	if($live) {
		return mysql_query($sql, $db_connection);
	}
	else {
		$result = mysql_query($sql, $db_connection);
		if(mysql_errno()) die("Cannot query database. MySQL says: ". mysql_error() . "");
		return $result;
	}
}


function get_tracks($offset, $rowsperpage) {
	
	global $DB_TABLE;
	
	$result = do_query("SELECT id, artist, urlartist, name, urltrack, album, dateuts FROM $DB_TABLE ORDER BY dateuts DESC LIMIT $offset, $rowsperpage");
	
	$i = 0;
	
	while($row = mysql_fetch_array($result)) {
		
		$items[$i]['id'] 		= $row['id'];
		$items[$i]['artist'] 		= stripslashes($row['artist']);
		$items[$i]['urlartist'] 	= stripslashes($row['urlartist']);
		$items[$i]['name'] 		= stripslashes($row['name']);
		$items[$i]['urltrack'] 		= stripslashes($row['urltrack']);
		$items[$i]['album'] 		= stripslashes($row['album']);
		$items[$i]['dateuts'] 		= $row['dateuts'];
		
		$i++;
		
	}
	
	return $items;
}



function get_count() {
	
	global $DB_TABLE;
	
	$result = do_query("SELECT DISTINCT (id) AS count FROM $DB_TABLE");
	
	$i = 0;
	
	while($row = mysql_fetch_array($result)) {
		
		$items[$i]['count'] 		= $row['count'];
		
		$i++;
		
	}
	
	return $items;
	
}

?>
