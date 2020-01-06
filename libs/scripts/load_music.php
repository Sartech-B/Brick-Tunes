<?php
	include_once('../../config/config.php');
	include_once('../includes/class.basesite.php');
	include_once('../includes/class.db.php');

	global $user, $db;

	$username = $user->username();
	
?>
<?php if($user->security_check() && isset($_GET['list'])): ?>
<?php
	$list_name = mysql_real_escape_string(strip_tags($_GET['list']));
	$list_id = $db->retrieve_obj("SELECT * FROM list WHERE name='$list_name'", 'id');
?>
<center><h1><div id="fav-category" class="list-category"><div id="title"><?php echo $list_name; ?></div></div></h1></center>
<table id="top-ten-content" class="main-content" style="width: 100%;">
	<tr>
	<?php
		if($list_name=="FAVOURITES") {
			$current_uid = $db->retrieve_obj("SELECT * FROM users WHERE uname='$username'", 'id');
			$query = "SELECT * FROM views WHERE uid='$current_uid'";
			$n = mysql_num_rows(mysql_query($query));
			if($n==0) echo "<br><br><h3>No Favourites, =(</h3>";
			else $user->load_music1($query);
		}
		else if($list_name=="BT-RECOMMENDED") {
			$query = "SELECT * FROM music ORDER BY views DESC";
			$user->load_music($query);
		}
		else {
			$query = "SELECT * FROM music WHERE category='$list_id' ORDER BY views DESC";
			$user->load_music($query);
		}
	?>
	</tr>
</table>
<?php endif; ?>