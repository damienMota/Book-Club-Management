<?php
$conn = mysqli_connect('localhost', 'damien', 'Oimadi*1', 'application_management');
if(!$conn)
{
	echo 'Connection ERRROR';
}
else
{
	if($_POST["action"] == "run_client_application")
	{
		$find[] = ("[application_id]"); 
		$repl[] = $_POST["application_id"];
		$return = str_replace($find,$repl,file_get_contents("client_application.html"));
		
		echo file_get_contents("client_application.html");
		return false;
	}
}
?>