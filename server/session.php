<?php
session_start();
$method = $_SERVER['REQUEST_METHOD'];
if ('DELETE' === $method) {
    if(isset($_SESSION['username']))
	{
	    $_SESSION=array();
	    unset($_SESSION);
	    session_destroy();
	    setcookie("username", "", time() - 3600);
	    setcookie("admin", "", time() - 3600);
	    echo "session destroyed...";
	}
}
?>