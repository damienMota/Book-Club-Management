<?php
$conn = mysqli_connect('localhost', 'damien', 'password', 'application_management');
if(!$conn)
{
	echo 'Connection ERRROR';
}
else
{
	echo file_get_contents("login.html");
}
?>