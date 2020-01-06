<?php
	include_once('class.db.php');
	include_once('class.security.php');

	if(!class_exists('User')) {
		abstract class User extends Security {

			protected $username;
			protected $full_name;
			protected $user_id;

			public function __construct($username, $is_logged_in) {
				global $db;
				
				if(!$is_logged_in) {
					$this->is_logged_in = false;
					return false;
				}
				$this->full_name = $db->retrieve_obj("SELECT * FROM users WHERE uname='$username'", 'full_name');
				$this->user_id = $db->retrieve_obj("SELECT * FROM users WHERE uname='$username'", 'id');
				$this->is_logged_in = true;
				return true;
			}

			public function non_empty($string) {
				if(!preg_match('/[A-Z]/', $string) && !preg_match('/[a-z]/', $string) && !preg_match('/[0-9]/', $string)) {
					return false;
				}

				return true;
			}

			public function security_check() {
				if(!isset($_COOKIE['User'])) {
					return false;
				}
				$username = mysql_real_escape_string($_COOKIE['User']);
				$query = "
							SELECT * FROM users WHERE uname='$username'
						";
				$mysql_query = mysql_query($query);
				$exists = mysql_num_rows($mysql_query);
				return $exists==1 ? true : false; 
			}

			public function get_var($_var) {
				return $this->{$_var};
			}

			public function username() {
				return $this->username;
			}

			public function full_name() {
				return $this->full_name;
			}

			public function user_id() {
				return $this->user_id;
			}
		}
	}
?>