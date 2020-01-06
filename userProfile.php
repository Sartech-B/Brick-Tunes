<?php
	include_once('./libs/includes/class.db.php');
	include_once('./libs/includes/class.basesite.php');
	
	global $user, $db;

	$username = $user->username();

	if(!$user->security_check() || $username!="sartech"){
		header("Location: ./index.php");
		exit();
	}

	if(isset($_FILES['song_file'])){
		$songName = mysql_real_escape_string($_POST['song_name']);
		$artist = mysql_real_escape_string($_POST['artist']);
		$type = mysql_real_escape_string($_POST['type']);
		$cat = mysql_real_escape_string($_POST['cat']);
		$temp_query = mysql_query("SELECT * FROM music");
		$num = mysql_num_rows($temp_query);
		if($_FILES['song_file']['type']=="audio/mp3" || $_FILES['song_file']['type']=="audio/ogg" || $_FILES['song_file']['type']=="audio/x-m4a") {
			if($songName != "" && $artist!="Artist" || $type!="Genre"){
				$chars = "~_ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890_~";
				$chars_set2 = "~_btofficialsongsonly_~";
				$randDirName = "~songfile_$num"."_".substr(str_shuffle($chars), 0, 25)."_btofficialsongsonly_".substr(str_shuffle($chars), 0, 10);
				mkdir("./Music/$randDirName");
				move_uploaded_file($_FILES['song_file']['tmp_name'], "./Music/$randDirName/".$_FILES['song_file']['name']);
				$musicLink = "./Music/$randDirName/".$_FILES['song_file']['name'];
				mysql_query("INSERT INTO music values('','$songName','$artist','$type','$musicLink','0','$cat')") or die(mysql_error());
				echo "<div style='position: fixed; background-color: rgba(0, 0, 0, 0.8); width: 100%; color: lime;'><center><h2>Uploaded!</h2></center></div>";
			}
			else{
				echo "<div style='position: fixed; background-color: rgba(0, 0, 0, 0.8); width: 100%; color: red;'><center><h2>Please fill in all the fields!</h2></center></div>";
			}
		}
		else{
			echo "<div style='position: fixed; background-color: rgba(0, 0, 0, 0.8); width: 100%; color: red;'><center><h2>File format not supported!</h2></center></div>";
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="./style/userProfile.css">
	<link rel="stylesheet" type="text/css" href="./style/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="./style/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="./style/bootstrap-theme.css">
	<link rel="stylesheet" type="text/css" href="./style/bootstrap-theme.min.css">
	<script src="./js/js.js"></script>
	<script src="./js/bootstrap.min.js"></script>
	<script src="./js/bootstrap.js"></script>
	<title>Brick Tunes</title>
</head>
<body>
<div id="header"><div id="title">Brick Tunes</div>
<table id="menu" style="margin-left: 35%; height: 100%">
<tr>
	<td><a href="./home.php">Home</a></td>
	<td><a href="./logout.php">Logout</a></td>
</tr>
</table>
</div>
<center>
<div id="upload-music">
	<center><h1>Upload Music</h1></center><br>
	<center>
	<form method="POST" enctype="multipart/form-data">
		<input type="file" name="song_file" /><br>
		<input type="text" name="song_name" placeholder="Name of song" /><br><br>
		<!-- <div id="form-control"> -->
			<select class="form-control" name="artist">
			<option>Artist</option>
			<?php
				$authorsQuery = mysql_query("SELECT * FROM authors ORDER BY name");
				while($authorsRow = mysql_fetch_assoc($authorsQuery)){
					$authorsName = $authorsRow['name'];
					echo "<option>$authorsName</option>";
				}
			?>
			</select><br>
			<select class="form-control" name="cat">
			<option>Category</option>
			<?php
				$listQuery = mysql_query("SELECT * FROM list ORDER BY name");
				while($listRow = mysql_fetch_assoc($listQuery)){
					$listName = $listRow['name'];
					$list_id = $listRow['id'];
					echo "<option value='$list_id'>$listName</option>";
				}
			?>
			</select>
		<!-- </div> --><br>
		<!-- <div id="form-control"> -->
			<select class="form-control" name="type">
			<option>Genre</option>
			<option>Pop</option>
			<option>Jazz</option>
			<option>Emotional</option>
			<option>Rock</option>
			<option>Rap/Hip-Hop</option>
			<option>Country</option>
			<option>Classical</option>
			<option>Metal</option>
			<option>Electronic</option>
			<option>Alternative</option>
			<option>Love</option>
			<option>Indian Pop</option>
			<option>Soft</option>
			<option>Other</option>
			</select>
		<!-- </div> --><br>
		<input type="submit" name="upload" class="btn btn-primary" value="Upload Music" />
	</form>
	</center>
</div>
</center>
</body>
</html>