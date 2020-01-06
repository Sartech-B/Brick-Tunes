<?php
	include_once('class.db.php');
	include_once('class.user.php');
	include_once('class.music.php');

	if(!class_exists('BaseSite')) {
		class BaseSite extends Music {

			public function __construct() {
				global $db;
				if(!$this->security_check()) {
					parent::__construct("Fatal Error", false);
					return false;
				}
				$username = mysql_real_escape_string(strip_tags($_COOKIE['User']));
				$this->username = $username;
				parent::__construct($username, true);
			}

			public function redirect($path) {
				header("Location: $path");
				exit();
			}

		}
	}
	$user = new BaseSite();
?>