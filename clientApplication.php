<?php
$conn = mysqli_connect('localhost', 'damien', 'Oimadi*1', 'application_management');
require('fpdf.php');
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
		$test = 'false';
		while($row = mysqli_fetch_assoc($result))
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
				mysqli_stmt_bind_param($signatorStmt, "i",$row["application_id"]);
				mysqli_stmt_execute($signatorStmt);
				$signatorResult = mysqli_stmt_get_result($signatorStmt);
				while($rowSig = mysqli_fetch_assoc($signatorResult))
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
					elseif($rowSig["signedDate"] == "" || $rowSig["signatorName"] == "" || $rowSig["signatorDecision"] == "")
					{
							if($rowSig["signatorDecision"] == "")
							{
								$signatorDecision = '<div style="display:inline-block;"><b>Sign Now</b><input style="margin-left:5px;"type="radio" id="signNow" name="signatorDecision"></div><div style="display:inline-block;margin-left:10px;"><b>Print & Sign Later</b><input style="margin-left:5px;" id="printSignLater" type="radio" name="signatorDecision"></div><br>';
							}
							if($rowSig["signatorDecision"] == "signNow")
							{
								$signatorDecision = '<div style="display:inline-block;"><b>Sign Now</b><input style="margin-left:5px;" type="radio" id="signNow" checked="checked" name="signatorDecision"></div><div style="display:inline-block;margin-left:10px;"><b>Print & Sign Later</b><input style="margin-left:5px;" id="printSignLater" type="radio" name="signatorDecision"></div><br>';
							}
							if($rowSig["signatorDecision"] == "printSignLater")
							{
								$signatorDecision = '<div style="display:inline-block;"><b>Sign Now</b><input style="margin-left:5px;" type="radio" id="signNow" checked="checked" name="signatorDecision"></div><div style="display:inline-block;margin-left:10px;"><b>Print & Sign Later</b><input style="margin-left:5px;" id="printSignLater" type="radio" checked="checked" name="signatorDecision"></div><br>';
							}
							$replClientAgreement[] = $row["application_id"];
							$replClientAgreement[] = $rowSig["signedDate"];
							$replClientAgreement[] = $rowSig["signatorName"];
							$replClientAgreement[] = $signatorDecision;
							$replClientAgreement[] = 'data:image/png;base64,'.$rowSig["signatorBase64"];
							
							$find = array("[application_id]","[signedDate]","[signatorName]","[signatorDecision]","[base64]");
							$return = str_replace($find,$replClientAgreement,file_get_contents("client_agreement.html"));
					}
					else
					{
						$pdf = new FPDF();
						$pdf->AddPage();
						$pdf->SetFont('Arial','B',16);
						$pdf->setXY(105,25);
						$pdf->Cell(10,5,'Bio','B',1,'C');
						$pdf->setXY(105,25);
						
						$pdf->SetFont('Arial','B',12);
						$pdf->setXY(70,35);
						$pdf->Cell(15,5,'Name:',0,1,'R');
						$pdf->SetFont('Arial','',12);
						$pdf->setXY(86,35);
						$pdf->Cell(47,5,$row["name"],1,1,'C');
						
						$pdf->SetFont('Arial','B',12);
						$pdf->setXY(70,43);
						$pdf->Cell(15,5,'Phone Number:',0,1,'R');
						$pdf->SetFont('Arial','',12);
						$pdf->setXY(86,43);
						$pdf->Cell(47,5,$row["primary_phone_number"],1,1,'C');
						
						$pdf->SetFont('Arial','B',12);
						$pdf->setXY(70,51);
						$pdf->Cell(15,5,'Email Address:',0,1,'R');
						$pdf->SetFont('Arial','',12);
						$pdf->setXY(86,51);
						$pdf->Cell(47,5,$row["primary_email"],1,1,'L');
						
						$pdf->SetFont('Arial','B',12);
						$pdf->setXY(70,59);
						$pdf->Cell(15,5,'Business Name:',0,1,'R');
						$pdf->SetFont('Arial','',12);
						$pdf->setXY(86,59);
						$pdf->Cell(47,5,$row["business_name"],1,1,'C');
						
						$pdf->SetFont('Arial','B',12);
						$pdf->setXY(70,67);
						$pdf->Cell(15,5,'Education:',0,1,'R');
						$pdf->SetFont('Arial','',12);
						$pdf->setXY(86,67);
						$pdf->Cell(47,5,$row["client_education"],1,1,'C');
						
						$pdf->SetFont('Arial','B',12);
						$pdf->setXY(105,75);
						$pdf->Cell(15,5,'About Me:',0,1,'R');
						$pdf->SetFont('Arial','',12);
						$pdf->setXY(65,80);
						$pdf->MultiCell(90,35,"",1,"L");
						$pdf->setXY(66,81);
						$pdf->MultiCell(90,5,$row["about_me"],0,"L");
						
						$pdf->SetFont('Arial','B',12);
						$pdf->setXY(105,120);
						$pdf->Cell(15,5,'Emergency Contact Info:',0,1,'C');
						$pdf->setXY(55,130);
						$pdf->Cell(15,5,'Name:',0,1,'C');
						$pdf->setXY(104,130);
						$pdf->Cell(15,5,'Email:',0,1,'C');
						$pdf->setXY(150,130);
						$pdf->Cell(15,5,'Phone Number:',0,1,'C');
						$pdf->SetFont('Arial','',12);
						
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
							$yAxis = 140;
	
							for($x = 0; $x <= $counter; $x++)
							{
								$yAxis = $yAxis+5;
								if($x == 0)
								{
									$pdf->setXY(45,140);
								}
								else
								{
									$pdf->setXY(45,$yAxis);
								}
								$pdf->Cell(35,5,$eciNameArray[$x],1,1,'C');

								if($x == 0)
								{
									$pdf->setXY(84,140);
								}
								else
								{
									$pdf->setXY(84,$yAxis);
								}
								$pdf->Cell(52,5,$eciEmailArray[$x],1,1,'C');
								
								if($x == 0)
								{
									$pdf->setXY(140,140);
								}
								else
								{
									$pdf->setXY(140,$yAxis);
								}
								$pdf->Cell(35,5,$eciPhoneArray[$x],1,1,'C');
							}
							
						}
						$pdf->AddPage();
						$pdf->SetFont('Arial','B',16);
						$pdf->setXY(83,25);
						$pdf->Cell(47,5,'Agreement Form','B',1,'C');
						$pdf->setXY(25,35);
						$pdf->SetFont('Arial','',10);
						$pdf->MultiCell(165,5,"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ut lacinia risus. Proin sed elit ipsum. Mauris et mollis augue. Cras tellus nunc, luctus vel varius sit amet, faucibus eu ligula. Nulla sollicitudin, lorem id dictum congue, libero tortor rutrum libero, ac lobortis magna ante ut tellus. Suspendisse quis efficitur lorem. Fusce fermentum faucibus nisi, elementum egestas libero varius quis. Morbi convallis quam eget interdum consectetur. Suspendisse sapien elit, pretium nec ante ac, rhoncus dictum risus. Nunc dictum quis urna quis finibus. Aliquam nec massa magna. Maecenas ut sapien porta, vehicula dui a, commodo nisl. Quisque elementum venenatis est, quis porta enim mattis at. Proin consequat sit amet purus at egestas. Suspendisse id ultrices tortor, eu aliquet mi. Praesent at mi eu metus porta tempor. Donec convallis, nunc a lacinia dignissim, enim sapien aliquet neque, ut egestas metus lacus eu sem. Etiam fringilla a libero sit amet tincidunt. Nam bibendum facilisis leo, nec pulvinar massa porta sit amet. Nulla tincidunt eros nec velit cursus, non maximus mi imperdiet. Praesent nec ligula ac leo rutrum gravida sit amet vel ligula. Duis a mollis lacus, vitae semper nisl. Proin vulputate bibendum ligula ac gravida. Morbi suscipit porta sapien vitae malesuada. Suspendisse lectus felis, hendrerit nec nulla ut, suscipit tempor nibh. In pulvinar vel tellus id interdum. Morbi gravida porta rhoncus. Phasellus non lobortis magna.	Fusce et rutrum magna. In suscipit lacus eget ante varius pellentesque. In dapibus justo vitae condimentum vehicula. Maecenas pellentesque dolor ut nisi maximus venenatis. Cras a egestas massa, ut sodales purus. Pellentesque et tristique libero. Etiam rhoncus at sapien quis vestibulum. Cras luctus ex a lacus gravida accumsan. Sed nec ultrices est, eget tincidunt ipsum. Sed pharetra viverra nisl at faucibus. Cras lobortis dignissim vulputate. Curabitur commodo commodo vulputate. Aliquam eget maximus magna. Aliquam et mattis arcu. Pellentesque semper, dui eget blandit dapibus, augue nisi mollis erat, vel imperdiet eros ipsum a tortor. Donec sagittis dapibus sem. Pellentesque nibh ante, feugiat non turpis quis, sodales fringilla purus. Ut porttitor tempus aliquam. Nullam pharetra, justo a tempor rhoncus, est arcu faucibus lectus, vestibulum sollicitudin est tortor sit amet nunc. Duis aliquet non sapien eget viverra. Duis interdum lectus urna, eget venenatis sem pharetra id. Praesent condimentum pretium blandit. Fusce dignissim efficitur posuere. Praesent maximus mauris eget felis euismod, in posuere leo pulvinar. Morbi at quam ut velit gravida lacinia ornare eget quam. Donec egestas ligula vel dapibus vehicula. Aenean ante lacus, feugiat in sapien non, congue vestibulum mi. Maecenas vehicula iaculis purus, ac blandit ante imperdiet at. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce tempor arcu ut mauris tempus euismod. In hac habitasse platea dictumst.",1,"L");
						
						$signatorSql = "SELECT * FROM signator_info where application_id =?;";
						$signatorStmt = mysqli_stmt_init($conn);
						mysqli_stmt_prepare($signatorStmt, $signatorSql);
						if(!mysqli_stmt_prepare($signatorStmt, $signatorSql))
						{
							echo "SQL ERROR";
						}
						else
						{
							mysqli_stmt_bind_param($signatorStmt, "i",$row["application_id"]);
							mysqli_stmt_execute($signatorStmt);
							$signatorResult = mysqli_stmt_get_result($signatorStmt);
							while($rowSig = mysqli_fetch_assoc($signatorResult))
							{
								$pdf->setXY(60,190);
								$pdf->SetFont('Arial','B',12);
								$pdf->Cell(26,5,'Signed Date:',0,0,'C');
								$pdf->SetFont('Arial','',12);
								$pdf->setXY(90,190);
								$pdf->Cell(47,5,$rowSig["signedDate"],1,1,'C');
								
								$pdf->setXY(59,198);
								$pdf->SetFont('Arial','B',12);
								$pdf->Cell(26,5,'Signed Name:',0,0,'C');
								$pdf->SetFont('Arial','',12);
								$pdf->setXY(90,198);
								$pdf->Cell(47,5,$rowSig["signatorName"],1,1,'C');
								
								$pdf->setXY(63,206);
								$pdf->SetFont('Arial','B',12);
								$pdf->Cell(26,5,'Signature:',0,0,'C');
								$pdf->setXY(67,230);
								
								if($rowSig["signatorDecision"] == "printSignLater")
								{
									$pdf->Cell(73,5,'',"B",0,'C');
								}
								if($rowSig["signatorDecision"] == "signNow")
								{
									$img = 'data:image/png;base64,'.$rowSig["signatorBase64"];
									$pdf->Image($img,70,210,100,0,'png');
									$pdf->Cell(73,5,'',"B",0,'C');
								}
							}
						}
						
						$pdf->Output('downloads/clientAppReview_'.$row["application_id"].'.pdf','F');
						
						$find = array("[application_id]");
						$repl = array($row["application_id"]);
						$return = str_replace($find,$repl,file_get_contents("client_review.html"));
					}
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
	if(isset($_POST["action"]) && $_POST["action"] == "submit_agreement")
	{
		$signatorName = mysqli_real_escape_string($conn, $_POST["signatorName"]);
		$signatorDate = mysqli_real_escape_string($conn, $_POST["signedDate"]);
		$signatorDecision = mysqli_real_escape_string($conn, $_POST["signatorDecision"]);
		if($_POST["signatorDecision"] == "signNow")
		{
			$updateSQL = "UPDATE signator_info SET signatorName =?,signedDate =?,signatorBase64 =?,signatorDecision =? WHERE application_id =?;";
			$updateSTMT = mysqli_stmt_init($conn);
			mysqli_stmt_prepare($updateSTMT, $updateSQL);
			if(!mysqli_stmt_prepare($updateSTMT, $updateSQL))
			{
				echo "SQL ERROR";
			}
			else
			{
				mysqli_stmt_bind_param($updateSTMT, "ssssi",$signatorName,$signatorDate,$_POST["signatorBase64"],$signatorDecision,$_POST["application_id"]);
				mysqli_stmt_execute($updateSTMT);
			}
		}
		if($_POST["signatorDecision"] == "printSignLater")
		{
			$updateSQL = "UPDATE signator_info SET signatorName =?,signedDate =?,signatorDecision =? WHERE application_id =?;";
			$updateSTMT = mysqli_stmt_init($conn);
			mysqli_stmt_prepare($updateSTMT, $updateSQL);
			if(!mysqli_stmt_prepare($updateSTMT, $updateSQL))
			{
				echo "SQL ERROR";
			}
			else
			{
				mysqli_stmt_bind_param($updateSTMT, "sssi",$signatorName,$signatorDate,$signatorDecision,$_POST["application_id"]);
				mysqli_stmt_execute($updateSTMT);
			}
		}
	}
	if(isset($_POST["action"]) && $_POST["action"] == "submit_application")
	{
		$updateSQL = "UPDATE application_main SET application_status =? WHERE application_id =?;";
		$updateSTMT = mysqli_stmt_init($conn);
		mysqli_stmt_prepare($updateSTMT, $updateSQL);
		if(!mysqli_stmt_prepare($updateSTMT, $updateSQL))
		{
			echo "SQL ERROR";
		}
		else
		{
			mysqli_stmt_bind_param($updateSTMT, "si","submitted",$_POST["application_id"]);
			mysqli_stmt_execute($updateSTMT);
			
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
				
				//Email Sent to Client After Application Has Been Submitted//
			}
		}
	}
}
?>