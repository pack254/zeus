<?php
	ob_start();
	session_start();
	$_SESSION["LOGIN_OK"] = false;
	$_SESSION["LOGIN_ID"] = "";
	$_SESSION["LOGIN_NAME"] = "";
	$_SESSION["LOGIN_LEVEL"] = "";

	unset($_COOKIE['LOGIN_ID']);
    unset($_COOKIE['LOGIN_LEVEL']);
    setcookie('LOGIN_ID', null, time()-3600);
    setcookie('LOGIN_LEVEL', null, time()-3600);
	
	session_write_close();
	@session_destroy();

	header("location: index.php?logout=true");
?>