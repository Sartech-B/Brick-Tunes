<?php
	include_once('class.db.php');

	if(!class_exists('Security')) {
		abstract class Security {

			protected $is_logged_in;
			public static $js_exit_code = "<script>window.close();</script>";

			public function __construct() {
				global $db;
				
				if(!$this->is_logged_in) {
					return false;
				}
				return true;
			}

			public function is_logged_in() {
				if(!$this->is_logged_in) {
					return false;
				}
				return true;
			}

			public function non_empty($string) {
				if(!preg_match('/[A-Z]/', $string) || !preg_match('/[a-z]/', $string) || !preg_match('/[0-9]/', $string)) {
					return false;
				}

				return true;
			}

			public function security_check() {
				if(!isset($_COOKIE['User'])) {
					return false;
				}
				$username = mysql_real_escape_string(strip_tags($_COOKIE['User']));
				$query = "
							SELECT * FROM users WHERE uname='$username'
						";
				$mysql_query = mysql_query($query);
				$exists = mysql_num_rows($mysql_query);
				return $exists==1 ? true : false; 
			}
		}
	}
?>