<?php
	include_once('./config/config.php');
	include_once('./libs/includes/class.basesite.php');
	include_once('./libs/includes/class.db.php');
	
	global $user, $db;

	if(!$user->security_check()){
		header("Location: ./");
		exit();
	}

	$full_name = $user->full_name();
	$username = $user->username();
	
	if(isset($_GET['mix']) && isset($_POST['track_name']) && isset($_POST['type']) && isset($_FILES['s_file']) && isset($_POST['upload'])) {
		$track_name = mysql_real_escape_string(strip_tags($_POST['track_name']));
		$type = mysql_real_escape_string(strip_tags($_POST['type']));

		if($_FILES['s_file']['type']=="audio/mp3" || $_FILES['s_file']['type']=="audio/ogg" || $_FILES['s_file']['type']=="audio/x-m4a") {
			$tmp_query = mysql_query("SELECT * FROM inst WHERE name='$type'");
			$exists = mysql_num_rows($tmp_query);
			$temp_query = mysql_query("SELECT * FROM mix");
			$rnd_num = mysql_num_rows($temp_query);
			if($user->non_empty($track_name) && $exists!=0) {
				$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
				$chars_set_2 = "~_userchoicemix_~";
				$randDirName = "~user_song_mix_$rnd_num"."_".substr(str_shuffle($chars), 0, 25)."_userchoicemix_".substr(str_shuffle($chars_set_2), 0, 20);
				mkdir("./Music/Mix/$randDirName");
				move_uploaded_file($_FILES['s_file']['tmp_name'], "./Music/Mix/$randDirName/".$_FILES['s_file']['name']);
				$musicLink = "./Music/Mix/$randDirName/".$_FILES['s_file']['name'];
				$type_id = $db->retrieve_obj("SELECT * FROM inst WHERE name='$type'", 'id');
				mysql_query("INSERT INTO mix values('','$track_name','$full_name','$type_id','$musicLink')") or die(mysql_error());
				echo "<div style='position: fixed; background-color: rgba(0, 0, 0, 0.8); width: 100%; color: lime;' id='success'><center><h2>Uploaded!</h2></center></div>";
			}
			else{
				echo "<div style='position: fixed; background-color: rgba(0, 0, 0, 0.8); width: 100%; color: red;' id='error'><center><h2>Please fill in all the fields!</h2></center></div>";
			}
		}
		else{
			echo "<div style='position: fixed; background-color: rgba(0, 0, 0, 0.8); width: 100%; color: red;' id='error'><center><h2>File format not supported!</h2></center></div>";
		}
	}

	if(isset($_GET['account']) && isset($_POST['old_pass']) && isset($_POST['new_pass']) && isset($_POST['c_pass'])) {
		$old_pass = mysql_real_escape_string(strip_tags($_POST['old_pass']));
		$new_pass = mysql_real_escape_string(strip_tags($_POST['new_pass']));
		$c_pass = mysql_real_escape_string(strip_tags($_POST['c_pass']));

		$old_pass_md5 = md5($old_pass);

		$user_pass = $db->retrieve_obj("SELECT * FROM users WHERE uname='$username'", 'pass');
		
		if($old_pass_md5==$user_pass) {
			if($user->non_empty($new_pass)) {
				if($new_pass==$c_pass) {
					$new_pass_md5 = md5($new_pass);
					$query = "UPDATE users SET pass='$new_pass_md5' WHERE uname='$username'";
					mysql_query($query);
					echo "<div style='position: fixed; background-color: rgba(0, 0, 0, 0.8); width: 100%; color: lime;' id='success'><center><h2>Password changed successfully!</h2></center></div>";
				}
				else {
					echo "<div style='position: fixed; background-color: rgba(0, 0, 0, 0.8); width: 100%; color: red;' id='error'><center><h2>Passwords do not match!</h2></center></div>";
				}
			}
			else {
				echo "<div style='position: fixed; background-color: rgba(0, 0, 0, 0.8); width: 100%; color: red;' id='error'><center><h2>Password must contain atleast small letters!</h2></center></div>";
			}
		}
		else {
				echo "<div style='position: fixed; background-color: rgba(0, 0, 0, 0.8); width: 100%; color: red;' id='error'><center><h2>Incorrect password!</h2></center></div>";
			}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="./style/home.css">
	<link rel="stylesheet" type="text/css" href="./style/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="./style/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="./style/bootstrap-theme.css">
	<link rel="stylesheet" type="text/css" href="./style/bootstrap-theme.min.css">
	<link rel="preload" as="image" href="./Images/equi.gif">
	<link rel="preload" as="image" href="./Images/loading.gif">
	<link rel="preload" as="image" href="./Images/default_cover.png">
	<link rel="preload" as="image" href="./Images/bg.jpg">
	<?php if(isset($_GET['mix'])): ?>
		<script src="./js/mix.js"></script>
	<?php else: ?>
		<script src="./js/home.js"></script>
	<?php endif; ?>
	<script src="./js/js.js"></script>
	<script src="./js/s_home.js"></script>
	<script src="./js/bootstrap.min.js"></script>
	<script src="./js/bootstrap.js"></script>
	<title>Brick Tunes</title>
</head>
<body>
<?php if(isset($_GET['id'])): ?>
<a id="back" href="./home.php">
<img src="./Images/back.png" />
</a>
<?php endif; ?>
<center>
<div id="header"><center>
	<table>
			<tr>
				<td colspan="3"><img src="./Images/logo.png" style="width: 100%;" /></td>
			</tr>
			<tr style="height: 75px;">
			<?php if(isset($_GET['mix'])): ?>
				<td><a href="./home.php"><center><img src="./Images/home.png" style="width: 82%; border-radius: 4px;" /></center></a></td>
				<td><a href="./home.php?account=true"><center><img src="./Images/acc.png" style="width: 82%; border-radius: 4px;" /></center></a></td>
			<?php elseif(isset($_GET['account'])): ?>
				<td><a href="./home.php"><center><img src="./Images/home.png" style="width: 82%; border-radius: 4px;" /></center></a></td>
				<td><a href="./home.php?mix=true"><center><img src="./Images/mix.png" style="width: 82%; border-radius: 4px;" /></center></a></td>
			<?php else: ?>
				<td><a href="./home.php?mix=true"><center><img src="./Images/mix.png" style="width: 82%; border-radius: 4px;" /></center></a></td>
				<td><a href="./home.php?account=true"><center><img src="./Images/acc.png" style="width: 82%; border-radius: 4px;" /></center></a></td>
			<?php endif; ?>
				<td><a href="./logout.php"><center><img src="./Images/logout.png" style="width: 82%; border-radius: 4px;" /></center></a></td>
			</tr>
			<tr>
			<?php if(isset($_GET['mix'])): ?>
			<?php elseif(isset($_GET['account'])): ?>
			<?php else: ?>
				<td colspan="3"><center><input class="search-box" id="search" type="text" placeholder="Search for songs, artists, movies" onkeyup="search(this.value, this.id);" autocomplete="off" /></center></td>
			<?php endif; ?>	
			</tr>
		</table></center>
</div>
</center>
<div id="playing-music"></div>
<br>
<center>
<div id="search-container"></div>
<?php if(isset($_GET['id'])): ?>
<?php
	$list_id = mysql_real_escape_string(strip_tags($_GET['id']));
	if(!$user->list_id_exists($list_id)) {
		header("Location: ./home.php");
		exit();
	}
	$name = $db->retrieve_obj("SELECT * FROM list WHERE id='$list_id'", 'name');
?>
<div id="main-container" class="<?php echo $name; ?>">

</div>
<?php elseif(isset($_GET['mix'])): ?>
<div class="box">
<center><h2>Upload Sound Track</h2></center>
<center>
<form method="POST" enctype="multipart/form-data">
	<table border="0" style="width: 100%;">
		<tr>
			<td><div style="width: 90%;"><center><input type="text" name="track_name" class="form-control" placeholder="Name of Sound Track" /></center></div><br></td>
			<td><div><center>
				<select class="form-control" name="type">
				<option>Instrument/Type</option>
				<?php
					$typeQuery = mysql_query("SELECT * FROM inst ORDER BY name");
					while($typeRow = mysql_fetch_assoc($typeQuery)){
						$type = $typeRow['name'];
						echo "<option>$type</option>";
					}
				?>
			</select>
			</center></div><br></td>
		</tr>
		<tr>
			<td colspan="2"><div><center><input type="file" name="s_file" class="form-control" /></center></div><br></td>
		</tr>
		<tr>
			<td colspan="2"><div><center><input type="submit" name="upload" class="btn btn-primary" value="Upload Track" /></center></div></td>
		</tr>
	</table>
</form>
</center><br>
</div><br>
<div id="mix-container" class="">

</div>
<?php elseif(isset($_GET['account'])): ?>
<div id="account-container">
<center>
	<div id="change-pass-box" class="box"><center>
	<center><label><h2>Change account Password</h2></label></center>
	<form method="POST">
		<input type="password" class="form-control" name="old_pass" placeholder="Current password" /><br>
		<input type="password" class="form-control" name="new_pass" placeholder="New password" /><br>
		<input type="password" class="form-control" name="c_pass" placeholder="Confirm password" /><br>
		<center><input type="submit" value="Change password" name="c" class="btn btn-primary" /></center>
	</form><br>
	</center>
	</div>
</center>
</div>
<br>
<?php else: ?>
<div id="play-container">
<center><div id="play-list">PLAYLISTS</div></center>
<?php
	$user->load_list();
?>
</div>
<?php endif; ?>
</center>
<div id="footer-check"></div>
<div id="view-container"></div>
<br><br>
</body>
</html>