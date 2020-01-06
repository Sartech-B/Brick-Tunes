<?php
	if (!class_exists('DB')) {
		class DB {

			public static $expire_time = 60 * 60 * 24 * 7 * 12;

			public function __construct() {
				//@mysql_connect("mysql.hostinger.co.uk", "u730781343_root", "ghib0i7g!!") or die("<div id='error'><center><h1>Could not connect to server!</h1></center><br><center><p>Retry Later.</p></center></div>");
				//@mysql_select_db("u730781343_music") or die("<div id='error'><center><h1>Could not connect to server!</h1></center><br><center><p>Retry Later.</p></center></div>");

				mysql_connect("localhost", "root", "");
				mysql_select_db("music");

				//mysqli_connect("mysql.hostinger.co.uk", "u730781343_root", "ghib0i7g!!", "u730781343_music") or die("<div id='error'><center><h1>Could not connect to server!</h1></center><br><center><p>Retry Later.</p></center></div>");
			}
			
			public function insert($query) {				
				$mysql_query = mysql_query($query);

				return $mysql_query;
			}
			
			public function update($query) {
				$mysql_query = mysql_query($query);

				return $mysql_query;
			}

			public function retrieve_obj($query, $obj) {
				$_current_row_query = mysql_query($query);
				$row = mysql_fetch_assoc($_current_row_query);
				$result = $row[$obj];
				return $result;
			}
		}
	}
	
	$db = new DB();
?>