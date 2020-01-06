<?php
	include_once('./config/config.php');
	include_once('./libs/includes/class.basesite.php');
	include_once('./libs/includes/class.db.php');

	global $user, $db;
	
	if(!$user->security_check() || !isset($_GET['id'])) {
		header("Location: ./?login=true");
		exit();
	}
	else {
		$id = mysql_real_escape_string(strip_tags($_GET['id']));
		$query = "SELECT * FROM music WHERE id='$id'";
		$mysql_query = mysql_query($query);
		$num = mysql_num_rows($mysql_query);
		if($num==0){
			die();
		}
		else {
			$row = mysql_fetch_assoc($mysql_query);
			$link = $row['link'];
			$name = $row['name'];
			header("Content-type: audio/*");
			header("Content-Disposition: attachment; filename='$name.mp3'");
			readfile($link);
			//header("Location: ./home.php");
			//exit();
		}
	}
?>