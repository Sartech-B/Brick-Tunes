<?php
	include_once('../../config/config.php');
	include_once('../includes/class.basesite.php');
	include_once('../includes/class.db.php');

	global $user, $db;

	if(!$user->security_check()) {
		// security redirect
	}
?>