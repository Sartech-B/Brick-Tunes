<?php
	include_once('./config/config.php');
	include_once('./libs/includes/class.db.php');
	include_once('./libs/includes/class.basesite.php');

	global $user, $db;

	if(!$user->security_check() || $user->username()!="sartech") {
		header("Location: ./home.php");
		exit();
	}

	$query = "SELECT * FROM users";
	$mysql_query = mysql_query($query);

	// Priority Algorithm
	// Users Loop
	while($row = mysql_fetch_assoc($mysql_query)) {
		$uid = $row['id'];
		$music_query = mysql_query("SELECT * FROM list");
		$mx = 0;
		$mx_type = '';
		$cal_query = mysql_query("SELECT * FROM views WHERE uid='$uid'");
		// Songs played by the user
		while($m_row = mysql_fetch_assoc($cal_query)) {
			$t = 0;
			$music_id = $m_row['id'];
			$music_genre = $db->retrieve_obj("SELECT * FROM music WHERE id='$music_id'", 'type');
			$genre_query = mysql_query("SELECT * FROM music WHERE type='$music_genre'");
			// Find all songs with that genre
			while($gen_row = mysql_fetch_assoc($genre_query)) {
				$tmp_query = mysql_query("SELECT * FROM views WHERE uid='$uid'");
				$exists = mysql_num_rows($tmp_query);
				// Check whether user played this song
				// if yes count in that genre
				if($exists!=0) {
					$t++;
				}
			}
			// Add specific genre
			if($t>$mx) {
				$mx = $t;
				$mx_type = $music_genre;
			}
		}
		// Update most played genre by the user
		mysql_query("UPDATE users SET cat='$mx_type' WHERE id='$uid'");
	}
	echo "Done!";
?>