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
		// error_log(mysqli_num_rows($result));
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
			//CHECKING FOR EMERGENCY CONTACT//
			$sqlECI = "SELECT * FROM emergency_contact_info where eci_application_id =?;";
			$stmtECI = mysqli_stmt_init($conn);
			mysqli_stmt_prepare($stmtECI, $sqlECI);
			if(!mysqli_stmt_prepare($stmtECI, $sqlECI))
			{
				echo "SQL ERROR";
			}
			else
			{
				mysqli_stmt_bind_param($stmtECI, "i",$row["application_id"]);
				mysqli_stmt_execute($stmtECI);
				$resultECI = mysqli_stmt_get_result($stmtECI);
				$emergencyContactInfo = '';
				if(mysqli_num_rows($resultECI) == 0)
				{
					$emergencyContactInfo .= '<div class="eci_row"><input placeholder="Contact Name" type="text" class="eci_name">';
					$emergencyContactInfo .= '<input placeholder="Contact Email" style="margin-left:10px;" type="text" class="eci_email">';
					$emergencyContactInfo .= '<input placeholder="Phone Number"style="margin-left:10px;" type="text" class="eci_phone_number"><div onclick="addECIRow();"style="margin-left:5px;display:inline-block;cursor: pointer;"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-patch-plus-fll" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01-.622-.636zM8.5 6a.5.5 0 0 0-1 0v1.5H6a.5.5 0 0 0 0 1h1.5V10a.5.5 0 0 0 1 0V8.5H10a.5.5 0 0 0 0-1H8.5V6z"/>
					</svg></div></div>';
				}
				else
				{
					error_log("2");
				}
			}
			
			$find = array("[application_id]","[name]","[primary_phone_number]","[primary_email]","[business_name]","[education]","[emergency_contact_info]");
			$repl = array($row["application_id"],$row["name"],$row["primary_phone_number"],$row["primary_email"],$row["business_name"],$clientEducation,$emergencyContactInfo);
			$return = str_replace($find,$repl,file_get_contents("client_application.html"));
			echo $return;
		}
	}
	if(isset($_POST["action"]) && $_POST["action"] == "submit_bio")
	{
		$updateSQL = "UPDATE application_main set name =? phone_number =? primary_email =? business_name =? client_education=? about_me=? where application_id =?;";
		$updateSTMT = mysqli_stmt_init($conn);
		mysqli_stmt_prepare($updateSTMT, $updateSQL);
		if(!mysqli_stmt_prepare($updateSTMT, $updateSQL))
		{
			echo "SQL ERROR";
		}
		else
		{
			//SET THIS UP WHEN YOU RETURN//
			mysqli_stmt_bind_param($updateSTMT, "ss",$validation_code,$row["application_id"]);
			mysqli_stmt_execute($updateSTMT);
		}
	}
}
?>