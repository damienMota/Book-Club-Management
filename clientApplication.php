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
			if($row["name"] == "" || $row["primary_email"] == "" || $row["primary_phone_number"] == "" || $row["business_name"] == "" || $row["client_education"] == "" || $row["about_me"] == "")
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
						$eciNameArray = array();
						$eciEmailArray = array();
						$eciPhoneArray = array();
						while($rowECI = mysqli_fetch_assoc($resultECI))
						{
							$eciNameArray[] = $rowECI["eci_name"];
							$eciEmailArray[] = $rowECI["eci_email"];
							$eciPhoneArray[] = $rowECI["eci_phone_number"];
						}
						$err = count($eciNameArray) - 1;
						$counter = $err ;
						for($x = 0; $x <= $counter; $x++)
						{
							$emergencyContactInfo .= '<div class="eci_row"><input placeholder="Contact Name" type="text" class="eci_name" value='.$eciNameArray[$x].'>';
							$emergencyContactInfo .= '<input placeholder="Contact Email" style="margin-left:10px;" type="text" class="eci_email" value='.$eciEmailArray[$x].'>';
							$emergencyContactInfo .= '<input placeholder="Phone Number"style="margin-left:10px;" type="text" class="eci_phone_number" value='.$eciPhoneArray[$x].'>';
							if($x == 0)
							{
								$emergencyContactInfo .= '<div onclick="addECIRow();"style="margin-left:5px;display:inline-block;cursor: pointer;"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-patch-plus-fll" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01-.622-.636zM8.5 6a.5.5 0 0 0-1 0v1.5H6a.5.5 0 0 0 0 1h1.5V10a.5.5 0 0 0 1 0V8.5H10a.5.5 0 0 0 0-1H8.5V6z"/>
								</svg></div></div>';
							}
							else
							{
								$emergencyContactInfo .= '<div class="removeRow" style="margin-left:5px;display:inline-block;cursor: pointer;">';
								$emergencyContactInfo .= '<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-patch-minus" fill="currentColor" xmlns="http://www.w3.org/2000/svg">';
								$emergencyContactInfo .= '<path fill-rule="evenodd" d="M5.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H6a.5.5 0 0 1-.5-.5z"/>';
								$emergencyContactInfo .= '<path fill-rule="evenodd" d="M10.273 2.513l-.921-.944.715-.698.622.637.89-.011a2.89 2.89 0 0 1 2.924 2.924l-.01.89.636.622a2.89 2.89 0 0 1 0 4.134l-.637.622.011.89a2.89 2.89 0 0 1-2.924 2.924l-.89-.01-.622.636a2.89 2.89 0 0 1-4.134 0l-.622-.637-.89.011a2.89 2.89 0 0 1-2.924-2.924l.01-.89-.636-.622a2.89 2.89 0 0 1 0-4.134l.637-.622-.011-.89a2.89 2.89 0 0 1 2.924-2.924l.89.01.622-.636a2.89 2.89 0 0 1 4.134 0l-.715.698a1.89 1.89 0 0 0-2.704 0l-.92.944-1.32-.016a1.89 1.89 0 0 0-1.911 1.912l.016 1.318-.944.921a1.89 1.89 0 0 0 0 2.704l.944.92-.016 1.32a1.89 1.89 0 0 0 1.912 1.911l1.318-.016.921.944a1.89 1.89 0 0 0 2.704 0l.92-.944 1.32.016a1.89 1.89 0 0 0 1.911-1.912l-.016-1.318.944-.921a1.89 1.89 0 0 0 0-2.704l-.944-.92.016-1.32a1.89 1.89 0 0 0-1.912-1.911l-1.318.016z"/></svg>';
								$emergencyContactInfo .= '</div></div>';
							}
						}
					}
				}
				$find = array("[application_id]","[name]","[primary_phone_number]","[primary_email]","[business_name]","[education]","[about_me]","[emergency_contact_info]");
				$repl = array($row["application_id"],$row["name"],$row["primary_phone_number"],$row["primary_email"],$row["business_name"],$clientEducation,$row["about_me"],$emergencyContactInfo);
				$return = str_replace($find,$repl,file_get_contents("client_bio.html"));
			}
			else
			{
				$signatorSql = "SELECT * FROM signator_info where application_id =?;";
				$signatorStmt = mysqli_stmt_init($conn);
				mysqli_stmt_prepare($signatorStmt, $signatorSql);
				if(!mysqli_stmt_prepare($signatorStmt, $signatorSql))
				{
					echo "SQL ERROR";
				}
				else
				{
					$replClientAgreement = array();
					
					mysqli_stmt_bind_param($signatorStmt, "i",$row["application_id"]);
					mysqli_stmt_execute($signatorStmt);
					$signatorResult = mysqli_stmt_get_result($signatorStmt);
					if(mysqli_num_rows($signatorResult) == 0)
					{
						$signatorDecision = '<div style="display:inline-block;"><b>Sign Now</b><input style="margin-left:5px;"type="radio" id="signNow" name="signatorDecision"></div><div style="display:inline-block;margin-left:10px;"><b>Print & Sign Later</b><input style="margin-left:5px;" id="printSignLater" type="radio" name="signatorDecision"></div><br>';
						$replClientAgreement[] = $row["application_id"];
						$replClientAgreement[] = $signatorDecision;
					}
					else
					{
						while($rowSig = mysqli_fetch_assoc($signatorResult))
						{
							if($rowSig["signatorDecision"] == "")
							{
								$signatorDecision .= '<div style="display:inline-block;"><b>Sign Now</b><input style="margin-left:5px;"type="radio" id="signatorDecision" name="signatorDecision"></div><div style="display:inline-block;margin-left:10px;"><b>Print & Sign Later</b><input style="margin-left:5px;" id="signatorDecision" type="radio" name="signatorDecision"></div><br>';
							}	
							// $replClientAgreement[] = $row["application_id"];
							// $replClientAgreement[] = $rowSig["signatorName"];
							// $replClientAgreement[] = $rowSig["signedDate"];
							// $replClientAgreement[] = $signatorDecision;
							$replClientAgreement = array($row["application_id"],$rowSig["signatorName"],$rowSig["signedDate"],$signatorDecision);
						}
					}
					$find = array("[application_id]","[signatorDecision]");
					$return = str_replace($find,$replClientAgreement,file_get_contents("client_agreement.html"));
					echo $return;
				}
			}
		}
	}
	if(isset($_POST["action"]) && $_POST["action"] == "submit_bio")
	{
		$name = mysqli_real_escape_string($conn, $_POST["name"]);
		$phone = mysqli_real_escape_string($conn, $_POST["primary_phone_number"]);
		$email = mysqli_real_escape_string($conn, $_POST["primary_email"]);
		$businessName = mysqli_real_escape_string($conn, $_POST["busines_name"]);
		$education = mysqli_real_escape_string($conn, $_POST["client_education"]);
		$aboutMe = mysqli_real_escape_string($conn, $_POST["about_me"]);
		//UPDATE//
		$updateSQL = "UPDATE application_main SET name =?,primary_phone_number =?,primary_email =?,business_name =?,client_education =?,about_me =? WHERE application_id =?;";
		$updateSTMT = mysqli_stmt_init($conn);
		mysqli_stmt_prepare($updateSTMT, $updateSQL);
		if(!mysqli_stmt_prepare($updateSTMT, $updateSQL))
		{
			echo "SQL ERROR";
		}
		else
		{
			mysqli_stmt_bind_param($updateSTMT, "ssssssi",$name,$phone,$email,$businessName,$education,$aboutMe,$_POST["application_id"]);
			mysqli_stmt_execute($updateSTMT);
		}
		
		$deleteSQL = "DELETE FROM emergency_contact_info WHERE eci_application_id = ?;";
		$deleteSTMT = mysqli_stmt_init($conn);
		mysqli_stmt_prepare($deleteSTMT, $deleteSQL);
		if(!mysqli_stmt_prepare($deleteSTMT, $deleteSQL))
		{
			echo "SQL ERROR";
		}
		else
		{
			mysqli_stmt_bind_param($deleteSTMT, "i",$_POST["application_id"]);
			mysqli_stmt_execute($deleteSTMT);
			$err = count($_POST["eci_name"]);	
			$counter =  $err - 1;

			for($x = 0; $x <= $counter; $x++)
			{			
				$insertSQL = "INSERT INTO emergency_contact_info (eci_name,eci_phone_number,eci_email,eci_application_id)
				VALUES (?, ?, ?, ?);";
				$insertSTMT = mysqli_stmt_init($conn);
				mysqli_stmt_prepare($insertSTMT, $insertSQL);
				
				
				
				if(!mysqli_stmt_prepare($insertSTMT, $insertSQL))
				{
					echo "SQL ERROR";
				}
				else
				{
					mysqli_stmt_bind_param($insertSTMT, "sssi",$_POST["eci_name"][$x],$_POST["eci_phone_number"][$x],$_POST["eci_email"][$x],$_POST["application_id"]);
					mysqli_stmt_execute($insertSTMT);
					echo 'succes';
				}
			}
			
		}
	}
}
?>