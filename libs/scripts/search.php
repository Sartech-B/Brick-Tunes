<?php
	include_once('../../config/config.php');
	include_once('../includes/class.basesite.php');
	include_once('../includes/class.db.php');

	global $user, $db;

	if($user->security_check() && isset($_GET['key'])) {
		$_key = mysql_real_escape_string(strip_tags($_GET['key']));
		$words = explode(" ", $_key);
		$query = "SELECT * FROM music WHERE name='$_key' or name LIKE '%$_key%' or name LIKE '%".implode("%' or name LIKE '%", $words)."%' or artist LIKE '%$_key%' or artist LIKE '%".implode("%' or artist LIKE '%", $words)."%' LIMIT 25";
		echo "
<center><h1>Search results:</h1></center><br>
		<table id='search-content' class='main-content' style='width: 100%;'>
			<tr>";
			$mysql_query = mysql_query($query);
			$num_res = mysql_num_rows($mysql_query);
			$i = 0;
			while($row = mysql_fetch_assoc($mysql_query)) {
				$i++;
				$songName = $row['name'];
				$artist = $row['artist'];
				$musicLink = $row['link'];
				$musicId = $row['id'];
				$genre = $row['type'];
				$artistQuery = "SELECT * FROM authors WHERE name='$artist'";
				$image = $db->retrieve_obj($artistQuery, 'link');
				if(!file_exists("../.".$image)) {
					$image = "./Images/default_cover.png";
				}
				echo "<td><center><div class='music-box'>
				<div class='music' id='search-$musicId' onClick='music_click(1, this.id);' onmouseenter='overlay_hover(1, this.id)' onmouseleave='overlay_leave(1, this.id)' style='background: url($image) center center; background-size: cover;'>
				<center>
				<div class='overlay' id='search-overlay-$musicId'>
				<br>
				<div id='text-type'>$genre</div>
				<div class='now-playing' id='search-now-playing-$musicId'></div>
				</div>
				</center>
				</div>
				<span class='song-name'>$songName(<a href='./download.php?id=$musicId' target='_blank'>Download</a>)</span><br><span class='artist'>$artist</span>
				<span id='span-search-$musicId'></span>
				</div></center><br><br></td>";
				switch($i){
					case 4: echo "</tr><tr>";
					$i = 0;
				break;
				}
			}
		echo "</tr></table>";
	}
?>