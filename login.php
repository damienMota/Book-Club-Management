<?php
session_start();
$_SESSION["user001auth"] = "true";
if($_POST["action"] == "login" && $_POST["username"] == "user001" && $_POST["password"] == "livedamien28")
{
	
	echo "Success";
}
else
{
	echo "error";
}
?>