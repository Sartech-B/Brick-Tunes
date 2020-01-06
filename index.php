<?php
	include_once('./libs/includes/class.db.php');
	include_once('./libs/includes/class.basesite.php');

	global $user, $db;

	if($user->security_check()){
		header("Location: ./home.php");
		exit();
	}

	if(isset($_POST['reg']) && isset($_POST['full_name']) && isset($_POST['uname']) && isset($_POST['pass1']) && isset($_POST['pass2'])) {
		$tmpFullName = mysql_real_escape_string(strip_tags($_POST['full_name']));
		$tmpUName = mysql_real_escape_string(strip_tags($_POST['uname']));
		$tmpPass1 = mysql_real_escape_string(strip_tags($_POST['pass1']));
		$tmpPass2 = mysql_real_escape_string(strip_tags($_POST['pass2']));
		if($user->non_empty($tmpFullName) && $user->non_empty($tmpUName) && $user->non_empty($tmpPass1) && $user->non_empty($tmpPass2)) {
			if($tmpPass1==$tmpPass2){
				$tmpQuery = "SELECT * FROM users WHERE uname='$tmpUName'";
				$tmpQueryNumRows = mysql_num_rows(mysql_query($tmpQuery));
				if($tmpQueryNumRows==0){
					$md5Pass = md5($tmpPass1);
					$tmpInsertQuery = "INSERT INTO users values('','$tmpFullName','$tmpUName','$md5Pass', '')";
					//mysql_query($tmpInsertQuery) or die(mysql_error());
					$db->insert($tmpInsertQuery);
					echo "<div style='position: fixed; background-color: rgba(0, 0, 0, 0.8); width: 100%; color: lime;' id='success'><center><h2>Successfully created an account, Now login!</h2></center></div>";
				}
				else{
					echo "<div style='position: fixed; background-color: rgba(0, 0, 0, 0.8); width: 100%; color: red;' id='error'><center><h2>User already exists!</h2></center></div>";
				}
			}
			else{
				echo "<div style='position: fixed; background-color: rgba(0, 0, 0, 0.8); width: 100%; color: red;' id='error'><center><h2>Passwords do not match!</h2></center></div>";
			}
		}
		else{
			echo "<div style='position: fixed; background-color: rgba(0, 0, 0, 0.8); width: 100%; color: red;' id='error'><center><h2>Please fill in all the fields!</h2></center></div>";
		}
	}
	if(isset($_POST['login']) && isset($_POST['login_id']) && isset($_POST['login_pass'])){
		$loginUname = mysql_real_escape_string(strip_tags($_POST['login_id']));
		$loginPass = mysql_real_escape_string(strip_tags($_POST['login_pass']));
		$loginMd5Pass = md5($loginPass);
		$loginQuery = "SELECT * FROM users WHERE uname='$loginUname' and pass='$loginMd5Pass' LIMIT 1";
		$loginQueryNumRows = mysql_num_rows(mysql_query($loginQuery));
		if($loginQueryNumRows==1){
			setcookie('User', $loginUname, time()+DB::$expire_time);
			header("Location: ./home.php");
			exit();
		}
		else if($loginQueryNumRows==0){
			echo "<div style='position: fixed; background-color: rgba(0, 0, 0, 0.8); width: 100%; color: red;' id='error'><center><h2>Username or password incorrect!</h2></center></div>";
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="./style/index.css">
	<link rel="stylesheet" type="text/css" href="./style/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="./style/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="./style/bootstrap-theme.css">
	<link rel="stylesheet" type="text/css" href="./style/bootstrap-theme.min.css">
	<script src="./js/js.js"></script>
	<script src="./js/index.js"></script>
	<script src="./js/bootstrap.min.js"></script>
	<script src="./js/bootstrap.js"></script>
	<title>Brick Tunes</title>
</head>
<body>
<center>
<div id="header">
		<center><table>
			<tr>
				<td colspan="3"><img src="./Images/logo.png" style="width: 100%;" /></td>
			</tr>
			<tr style="height: 75px;">
				<?php if(isset($_GET['login'])): ?>
					<td><a href="./"><center><img src="./Images/home.png" style="width: 82%; border-radius: 4px;" /></center></a></td>
					<td><a href="./?reg=true"><center><img src="./Images/reg2.png" style="width: 82%; border-radius: 4px;" /></center></a></td>
					<td><a href="./?about_us=true"><center><img src="./Images/about.png" style="width: 82%; border-radius: 4px;" /></center></a></td>
				<?php elseif(isset($_GET['reg'])): ?>
					<td><a href="./"><center><img src="./Images/home.png" style="width: 82%; border-radius: 4px;" /></center></a></td>
					<td><a href="./?login=true"><center><img src="./Images/login.png" style="width: 82%; border-radius: 4px;" /></center></a></td>
					<td><a href="./?about_us=true"><center><img src="./Images/about.png" style="width: 82%; border-radius: 4px;" /></center></a></td>
				<?php elseif(isset($_GET['reg'])): ?>
					<td><a href="./?login=true"><center><img src="./Images/login.png" style="width: 82%; border-radius: 4px;" /></center></a></td>
					<td><a href="./?reg=true"><center><img src="./Images/reg2.png" style="width: 82%; border-radius: 4px;" /></center></a></td>
					<td><a href="./"><center><img src="./Images/home.png" style="width: 82%; border-radius: 4px;" /></center></a></td>
				<?php elseif(isset($_GET['about_us'])): ?>
					<td><a href="./"><center><img src="./Images/home.png" style="width: 82%; border-radius: 4px;" /></center></a></td>
					<td><a href="./?login=true"><center><img src="./Images/login.png" style="width: 82%; border-radius: 4px;" /></center></a></td>
					<td><a href="./?reg=true"><center><img src="./Images/reg2.png" style="width: 82%; border-radius: 4px;" /></center></a></td>
				<?php else: ?>
					<td><a href="./?login=true"><center><img src="./Images/login.png" style="width: 82%; border-radius: 4px;" /></center></a></td>
					<td><a href="./?reg=true"><center><img src="./Images/reg2.png" style="width: 82%; border-radius: 4px;" /></center></a></td>
					<td><a href="./?about_us=true"><center><img src="./Images/about.png" style="width: 82%; border-radius: 4px;" /></center></a></td>
				<?php endif; ?>
			</tr>
		</table></center>
</div></center>

<center>
<div id="main-content">
<?php if(isset($_GET['login'])): ?>
<center>
	<div id="login-box" class="box"><center>
	<center><label><h1>Login</h1></label></center>
	<form method="POST">
		<input type="text" class="form-control" name="login_id" placeholder="Username" /><br><br>
		<input type="password" class="form-control" name="login_pass" placeholder="Password" /><br>
		Don't have an account. <a href="./?reg=true">Register.</a>
		<br>
		<center><input type="submit" value="Login" name="login" class="btn btn-primary" /></center>
	</form><br>
	</center>
	</div>
</center>
<?php elseif(isset($_GET['reg'])): ?>
<center>
<div id="sign-up-box" class="box"><center>
	<center><label><h1>Register</h1></label></center>
	<form method="POST">
		<input type="text" class="form-control" name="full_name" placeholder="Full Name" /><br>
		<input type="text" class="form-control" name="uname" placeholder="Username" /><br>
		<input type="password" class="form-control" name="pass1" placeholder="Password" /><br>
		<input type="password" class="form-control" name="pass2" placeholder="Confirm Password" /><br>
		<center><input type="submit" value="Sign Up" name="reg" class="btn btn-primary" /></center>
	</form><br>
	</center>
</div>
</center>
<?php elseif(isset($_GET['about_us'])): ?>
<center>
	<div id="about-us-box" class="box">
		<center><h2>Credits</h2></center>
		<p><b>Founder: </b>Sarthak Bawal</p>
		<p><b>Co-Founder: </b>Shantanu Verma</p>
	</div>
</center>
<?php else: ?>
<center>
	<div id="info-box" class="box">
		<center><h2>Listen to Music Anywhere Anytime</h2></center>
		<p>To Be Continued...</p>
	</div>
</center>
<?php endif; ?>
</div>
</center>
<br><br>
</body>
</html>