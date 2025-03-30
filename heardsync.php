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

date_default_timezone_set('GMT');

$data		= '';
$xml		= '';
$changed	= 0;
$newtracks	= 0;


// we pull the most recent 200 tracks from Last.fm and then loop through them... we should maybe do more? 
// for now, we'll just run this 2 times per day, so if you listen to more than 200 songs in 12 hours, we will miss some...
// at one point I knew the right way to do it but I didn't implement it, so feel free to figure it out...

$from_xml_source = 'http://ws.audioscrobbler.com/2.0/?method=user.getRecentTracks&api_key='.API_KEY.'&user='.LASTFM_USER.'&limit=100&page=1';


$from_remote_fp = fopen($from_xml_source, "rb");
$from_remote_data = '';

while (!feof($from_remote_fp)) {
	$data .= fread($from_remote_fp, 1024);
}
fclose($from_remote_fp);  

$xml = new SimpleXMLElement($data);

for($i=0;$i<sizeof($xml->recenttracks->track);$i++){
		
	$artist 		= $xml->recenttracks->track[$i]->artist;
	$artistmbid		= $xml->recenttracks->track[$i]->artist["mbid"];
	$mbid 			= $xml->recenttracks->track[$i]->mbid;
	$name 			= $xml->recenttracks->track[$i]->name;
	$streamable		= $xml->recenttracks->track[$i]->streamable;
	$album 			= $xml->recenttracks->track[$i]->album;
	$albummbid		= $xml->recenttracks->track[$i]->album["mbid"];
	$urltrack		= $xml->recenttracks->track[$i]->url;
	
	list($urlartist) 	= explode("/_/", $xml->recenttracks->track[$i]->url);
	$urlartist 		= $urlartist;
	
	$imagesmall		= $xml->recenttracks->track[$i]->image[0];
	$imagemedium		= $xml->recenttracks->track[$i]->image[1];
	$imagelarge		= $xml->recenttracks->track[$i]->image[2];
	$imageextralarge	= $xml->recenttracks->track[$i]->image[3];
	$date 			= $xml->recenttracks->track[$i]->date[0];
	$dateuts		= $xml->recenttracks->track[$i]->date["uts"];
	
	
	$query = sprintf( " INSERT IGNORE INTO `tracks` "
		."(	
			`artist`, `artistmbid`, `mbid`, 
			`name`, `streamable`, `album`, 
			`albummbid`, `urltrack`, `urlartist`, 
			`imagesmall`, `imagemedium`, `imagelarge`, 
			`imageextralarge`, `datestring`, `dateuts`
		)"
		." VALUES "
		."(	
			'%s', '%s', '%s', 
			'%s', '%s', '%s', 
			'%s', '%s', '%s', 
			'%s', '%s', '%s', 
			'%s', '%s', '%s'
		); ", 
			mysql_real_escape_string($artist), mysql_real_escape_string($artistmbid), mysql_real_escape_string($mbid),
			mysql_real_escape_string($name), mysql_real_escape_string($streamable), mysql_real_escape_string($album),
			mysql_real_escape_string($albummbid), mysql_real_escape_string($urltrack), mysql_real_escape_string($urlartist),
			mysql_real_escape_string($imagesmall), mysql_real_escape_string($imagemedium), mysql_real_escape_string($imagelarge),
			mysql_real_escape_string($imageextralarge), mysql_real_escape_string($date), mysql_real_escape_string($dateuts)
		
	);
	
	do_query($query) or die();

	if (mysql_affected_rows() == 1) {
		$changed = 1;
		$newtracks++;
	}
	
}

// below is useful for debugging
if ($changed > 0) {
	echo $newtracks++ . " tracks were added\n";
}
else {
	echo "no new tracks were added\n"; 
}

?> 
