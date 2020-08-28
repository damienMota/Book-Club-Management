<?php
$conn = mysqli_connect('localhost', 'damien', 'Oimadi*1', 'application_management');
if(!$conn)
{
	echo 'Connection ERRROR';
}
else
{
	//NEED to figure how to check if someones messing with the URL
	$url = explode("?page=",$_SERVER["REQUEST_URI"]);
	
	$sql = "SELECT * FROM application_main where client_URN =?;";
	$stmt = mysqli_stmt_init($conn);
	mysqli_stmt_prepare($stmt, $sql);
	if(!mysqli_stmt_prepare($stmt, $sql))
	{
		echo "SQL ERROR";
	}
	else
	{
		mysqli_stmt_bind_param($stmt, "s",$url[1]);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$clientEducation = "<select id='client_education'><option id=0 value=0>Please select an option</option>";
		while($row = mysqli_fetch_assoc($result))
		{
			if($row["client_education"] == "")
			{
				$clientEducation.= '<option id="hs" value="High School">High School graduate</option>';
				$clientEducation.= '<option id="ad" value="Associate degree">Associate degree</option>';
				$clientEducation.= '<option id="bd" value="Bachelor degree">Bachelor degree</option>';
				$clientEducation.= '<option id="md" value="Master degree">Master degree</option>';
				$clientEducation.= '<option id="dpd" value="Doctoral or Professional degree">Doctoral or Professional degree</option></select>';
			}
			else
			{
				$clientEducation.= '<option id="hs" value="High School" '.(($row["client_education"] == 'High School')?' selected="selected"':'').'>High School graduate</option>';
				$clientEducation.= '<option id="ad" value="Associate degree" '.(($row["client_education"] == 'Associates degree')?' selected="selected"':'').'>Associate degree</option>';
				$clientEducation.= '<option id="bd" value="Bachelor degree" '.(($row["client_education"] == 'Bachelor degree')?' selected="selected"':'').'>Bachelor degree</option>';
				$clientEducation.= '<option id="md" value="Master degree" '.(($row["client_education"] == 'Master degree')?' selected="selected"':'').'>Master degree</option>';
				$clientEducation.= '<option id="dpd" value="Doctoral or Professional degree" '.(($row["client_education"] == 'Doctoral or Professional degree')?' selected="selected"':'').'>Doctoral or Professional degree</option></select>';
			}
			$find = array("[application_id]","[name]","[primary_phone_number]","[primary_email]","[business_name]","[education]");
			$repl = array($row["application_id"],$row["name"],$row["primary_phone_number"],$row["primary_email"],$row["business_name"],$clientEducation);
			$return = str_replace($find,$repl,file_get_contents("client_application.html"));
			echo $return;
		}
	}
}
?>