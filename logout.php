<?php
	setcookie('User','',time()-1);
	header("Location: ./");
	exit();
?>