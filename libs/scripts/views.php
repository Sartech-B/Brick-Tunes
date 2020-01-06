<?php
	include_once('../../config/config.php');
	include_once('../includes/class.basesite.php');
	include_once('../includes/class.db.php');

	global $user, $db;

	if($user->security_check() && isset($_GET['id'])) {
		$id = mysql_real_escape_string(strip_tags($_GET['id']));
		if(!$user->viewed($id) && $user->music_id_exists($id)) {
			$views = $user->get_views($id);
			$views++;
			$user->update_views($id, $views);
		} 
	}
?>