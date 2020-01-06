<?php
	include_once('../../config/config.php');
	include_once('../includes/class.basesite.php');
	include_once('../includes/class.db.php');

	global $user, $db;

	if($user->security_check() && isset($_GET['id'])) {
		$id = mysql_real_escape_string(strip_tags($_GET['id']));
		$musicLink = $db->retrieve_obj("SELECT * FROM mix WHERE id='$id'", 'link');
		
		echo "<audio class='song' id='song-$id' loop='true' preload><source src='$musicLink' type='audio/mp3'></audio>";
	}
?>