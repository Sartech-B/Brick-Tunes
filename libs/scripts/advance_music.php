<?php
	include_once('../../config/config.php');
	include_once('../includes/class.basesite.php');
	include_once('../includes/class.db.php');

	global $user, $db;

	if($user->security_check() && isset($_GET['id']) && isset($_GET['type'])) {
		$id = mysql_real_escape_string(strip_tags($_GET['id']));
		$type = mysql_real_escape_string(strip_tags($_GET['type']));
		$music_id = str_replace("song-", "", $id);
		$musicLink = $db->retrieve_obj("SELECT * FROM music WHERE id='$music_id'", 'link');
		//echo $music_id;
		echo "<audio class='song' id='$type-song-$music_id' loop='true' preload><source src='$musicLink' type='audio/mp3'></audio>";
	}
?>