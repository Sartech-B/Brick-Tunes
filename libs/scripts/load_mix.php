<?php
	include_once('../../config/config.php');
	include_once('../includes/class.basesite.php');
	include_once('../includes/class.db.php');

	global $user, $db;

	if($user->security_check() && isset($_GET['type'])) {
		$type = mysql_real_escape_string(strip_tags($_GET['type']));
		$user->load_mix("SELECT * FROM mix WHERE type LIKE '%$type%' ORDER BY id DESC");
	}
?>