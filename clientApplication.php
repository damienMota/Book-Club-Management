<?php
$conn = mysqli_connect('localhost', 'damien', 'Oimadi*1', 'application_management');
if(!$conn)
{
	echo 'Connection ERRROR';
}
else
{
	$_SESSION["application_id"]= "";
	if(isset($_POST["action"]) && $_POST["action"] == "run_client_application")
	{
		$_SESSION["application_id"] = $_POST["application_id"];	
		$find[] = ("[application_id]");
		$repl[] = ($_SESSION["application_id"]);
		error_log($_SESSION["application_id"]);
		$return = str_replace($find,$repl,file_get_contents("client_application.html"));
		echo $return;
	}
}
?>