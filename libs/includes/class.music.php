<?php
	include_once('class.db.php');
	include_once('class.user.php');

	if(!class_exists('Music')) {
		abstract class Music extends User {

			private $fav = "./Images/fav.png";

			public function __construct($username, $state){
				if(!parent::__construct($username, $state)) {
					return false;
				}
				return true;
			}

			public function music_id_exists($id) {
				$query = "
							SELECT * FROM music WHERE id='$id'
						";
				$mysql_query = mysql_query($query);
				$exists = mysql_num_rows($mysql_query);
				return $exists==1 ? true : false;	
			}

			public function list_id_exists($id) {
				$query = "
							SELECT * FROM list WHERE id='$id'
						";
				$mysql_query = mysql_query($query);
				$exists = mysql_num_rows($mysql_query);
				return $exists==1 ? true : false;
			}

			public function load_mix($query)
			{
				global $db;

				$mysql_query = mysql_query($query);
				$i = 0;
				echo "<table class='main-content' style='width: 100%;'><tr>";
				while($row = mysql_fetch_assoc($mysql_query)) {
					$i++;
					$songName = $row['name'];
					$artist = $row['user'];
					$musicLink = $row['link'];
					$musicId = $row['id'];
					$type_id = $row['type'];
					$type = $db->retrieve_obj("SELECT * FROM inst WHERE id='$type_id'", 'name');
					// $artistQuery = mysql_query("SELECT * FROM authors WHERE name='$artist'");
					// $artistRow = mysql_fetch_assoc($artistQuery);
					// $artistLink = $artistRow['link'];
					$image = $db->retrieve_obj("SELECT * FROM inst WHERE name='$type'", 'link');
					if(!file_exists("../.".$image)) {
						$image = "./Images/default_cover.png";
					}
					echo "<td><center><div class='music-box'>
					<div class='music' id='$musicId' onClick='music_click(this.id);' onmouseenter='overlay_hover(this.id)' onmouseleave='overlay_leave(this.id)' style='background: url($image) center center; background-size: cover;'>
					<center>
					<div class='overlay' id='overlay-$musicId'>
					<br>
					<div id='text-type'>$type</div>
					<div class='now-playing' id='now-playing-$musicId'></div>
					</div>
					</center>
					</div>
					<span class='song-name'>$songName</span><br><span class='artist'>$artist</span>
					<span id='span-$musicId'></span>
					</div></center><br><br></td>";
					switch($i){
						case 4: echo "</tr><tr>";
						$i = 0;
					break;
					}
				}
				echo "</tr></table>";
			}

			public function load_list() {
				$query = "SELECT * FROM list ORDER BY name";
				$mysql_query = mysql_query($query);
				$i = 0;
				echo "<table><tr>";
				while($row = mysql_fetch_assoc($mysql_query)) {
					$i++;
					$name = $row['name'];
					$list_id = $row['id'];
					$num_songs = $this->num_of_songs("SELECT * FROM music WHERE type='$list_id'");
					echo "<td>
						<div id='list-box'>
							<a href='./home.php?id=$list_id'>
								<b>-></b>&nbsp; $name
							</a>
						</div></td></tr><tr>
					";
				}
				echo "</tr></table>";
			}

			public function num_of_songs($query) {
				$mysql_query = mysql_query($query);
				$num = mysql_num_rows($mysql_query);
				return $num;
			}

			public function get_playlist_img($playlist) {
				return $this->{$playlist};
			}

			public function artist_id_exists($id) {
				$query = "
							SELECT * FROM authors WHERE id='$id'
						";
				$mysql_query = mysql_query($query);
				$exists = mysql_num_rows($mysql_query);
				return $exists==1 ? true : false;	
			}

			// public function get_top_ten() {
			// 	while($row = mysql_fetch_assoc(mysql_query($this->top_ten_query))) {
			// 		$name = $row['name'];
			// 		$artist = $row['artist'];
			// 		$id = $row['id'];
			// 		echo "";
			// 	}
			// }

			public function get_views($id) {
				global $db;

				$query = "
									SELECT views FROM music WHERE id='$id'
								";
				$views = $db->retrieve_obj($query, 'views');
				return $views;
			}

			public function update_views($id, $views) {
				global $db;

				$query = "
							UPDATE music SET views='$views' WHERE id='$id'
						";
				$uid = $this->user_id;
				$_query = "
							INSERT INTO views values('', '$uid', '$id')
						";
				$db->update($query);
				$db->insert($_query);
			}

			public function viewed($id) {
				$uid = $this->user_id;
				$query = "
							SELECT * FROM views WHERE uid='$uid' and music_id='$id'
						";
				$mysql_query = mysql_query($query);
				$exists = mysql_num_rows($mysql_query);
				return $exists==1 ? true : false;
			}

			public function load_music($query) {
				global $db;

				$mysql_query = mysql_query($query);
				$i = 0;
				while($row = mysql_fetch_assoc($mysql_query)) {
					$i++;
					$songName = $row['name'];
					$artist = $row['artist'];
					$musicLink = $row['link'];
					$musicId = $row['id'];
					$genre = $row['type'];
					$image = $db->retrieve_obj("SELECT * FROM authors WHERE name='$artist'", 'link');
					if(!file_exists("../.".$image)) {
						$image = "./Images/default_cover.png";
					}
					echo "<td><center><div class='music-box'>
					<div class='music' id='top-$musicId' onClick='music_click(0, this.id);' onmouseenter='overlay_hover(0, this.id)' onmouseleave='overlay_leave(0, this.id)' style='background: url($image) center center; background-size: cover;'>
					<center>
					<div class='overlay' id='top-overlay-$musicId'>
					<br>
					<div id='text-type'>$genre</div>
					<div class='now-playing' id='top-now-playing-$musicId'></div>
					</div>
					</center>
					</div>
					<span class='song-name'>$songName(<a href='./download.php?id=$musicId'>Download</a>)</span><br><span class='artist'>$artist</span>
					<span id='span-top-$musicId'></span>
					</div></center><br><br></td>";
					switch($i){
						case 4: echo "</tr><tr>";
						$i = 0;
					break;
					}
				}
			}

			public function load_music1($query) {
				global $db;

				$mysql_query = mysql_query($query);
				if(!$mysql_query) return;
				$i = 0;
				while($row = mysql_fetch_assoc($mysql_query)) {
					$i++;
					$musicId = $row['music_id'];
					$struct_query = "SELECT * FROM music WHERE id='$musicId'";
					$songName = $db->retrieve_obj($struct_query, 'name');
					$artist = $db->retrieve_obj($struct_query, 'artist');
					$musicLink = $db->retrieve_obj($struct_query, 'link');
					$genre = $db->retrieve_obj($struct_query, 'type');
					$image = $db->retrieve_obj("SELECT * FROM authors WHERE name='$artist'", 'link');
					if(!file_exists("../.".$image)) {
						$image = "./Images/default_cover.png";
					}
					echo "<td><center><div class='music-box'>
					<div class='music' id='top-$musicId' onClick='music_click(0, this.id);' onmouseenter='overlay_hover(0, this.id)' onmouseleave='overlay_leave(0, this.id)' style='background: url($image) center center; background-size: cover;'>
					<center>
					<div class='overlay' id='top-overlay-$musicId'>
					<br>
					<div id='text-type'>$genre</div>
					<div class='now-playing' id='top-now-playing-$musicId'></div>
					</div>
					</center>
					</div>
					<span class='song-name'>$songName(<a href='./download.php?id=$musicId'>Download</a>)</span><br><span class='artist'>$artist</span>
					<span id='span-top-$musicId'></span>
					</div></center><br><br></td>";
					switch($i){
						case 4: echo "</tr><tr>";
						$i = 0;
					break;
					}
				}
			}
		}
	}
?>