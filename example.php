<?php
	abstract class test {
		static $_name;

		public function __construct($name) {
			self::$_name = $name;
		}

		abstract function return_name();
	}

	class B extends test {

		public function __construct($_name) {
			parent::__construct($_name);
		}

		public function return_name() {
			return parent::$_name;
		}
	}

	$_user = new B("Sarthak") or die("Fatal Error: "+error_log());
	echo $_user->return_name();
	echo test::$_name;
?>