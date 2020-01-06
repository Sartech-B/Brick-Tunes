<?php
	include_once('../../config/config.php');
	include_once('../includes/class.basesite.php');
	
	global $user, $db;

	if($user->security_check() && isset($_GET['id']) && isset($_GET['type']) && isset($_GET['type_num_sec'])) {
		$id = mysql_real_escape_string(strip_tags($_GET['id']));
		$id_type = mysql_real_escape_string(strip_tags($_GET['type']));
		$type_num_sec = mysql_real_escape_string(strip_tags($_GET['type_num_sec']));
		if($user->music_id_exists($id)) {
			$query = "SELECT * FROM music WHERE id='$id'";
			$mysql_query = mysql_query($query);
			$row = mysql_fetch_assoc($mysql_query);
			$name = $row['name'];
			$artist = $row['artist'];
			$type = $row['type'];
			$artist_query = "SELECT * FROM authors WHERE name='$artist'";
			$image_link = $db->retrieve_obj($artist_query, 'link');
			if(!file_exists("../.".$image_link)) {
				$image_link = "./Images/default_cover.png";
			}
			$equi = "./Images/equi.gif";
			echo "
				<table style='width: 100%; height: 100%;' id='$id_type' onClick='music_click($type_num_sec, this.id);'>
					<tr>
						<td style='width: 23%;' rowspan='2'><img src='$image_link' style='width: auto; height: 121px;' /></td>
						<td><center><h3>$name</h3></center></td>
						<td rowspan='2' style='background-image: url($equi); background-size: cover; background-position: center center; width: 25%;'><center>
							<br><br><br>
							Now Playing
						</center></td>
					</tr>
					<tr>
						<td><center>$artist</center></td>
					</tr>
				</table>
			";	
		}
	}
?>