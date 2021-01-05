<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
require('fpdf.php');
$conn = mysqli_connect('localhost', 'damien', 'Oimadi*1', 'application_management');

if(isset($_SESSION["client_application"]) && $_SESSION["client_application"] == "true")
{
	if(!$conn)
	{
		echo 'Connection ERRROR';
	}
	else
	{
		//NEED to figure how to check if someones messing with the URL
		$tempUrl = explode("?page=",$_SERVER["REQUEST_URI"]); //url if not selected from navigation bar
		$tempUrl2 = explode(substr($tempUrl[1],0,3),$tempUrl[1]); //url selected if selected from navigation bar
		$page = substr($tempUrl[1],0,3);
		$url = '';

		if($page != "bio" && $page != "agr" && $page != "rev")
		{
			$url = $tempUrl[1];
		}
		else
		{
			$url = $tempUrl2[1];
		}
		$sql = "SELECT * FROM application_main where client_URN =?;";
		if($stmt = $conn->prepare($sql)) 
		{
			$stmt->bind_param("s",$url);
			$stmt->execute();
			$stmt->bind_result($application_id,$name,$primary_phone_number,
			$primary_email,$application_status,$business_name,$reference_number,
			$validation_code,$client_URN,$client_education,$about_me);
			
			$clientEducation = "<select id='client_education'><option id=0 value=0>Please select an option</option>";
			$stmt->fetch();
			$stmt->close();
			//Normally a while loop started here.(Main Table)///
				$signatorSql = "SELECT signedDate,signatorName,signatorDecision,signatorBase64
				FROM signator_info where application_id =?;";
				if($stmt = $conn->prepare($signatorSql)) 
				{
					$stmt->bind_param("i",$application_id);
					$stmt->execute();
					$stmt->bind_result($signedDate,$signatorName,$signatorDecision,$signatorBase64);
					$stmt->fetch();
					$stmt->close();
					//while loop removed here (Signator Info)//
						if($name == "" || $primary_email == "" || $primary_phone_number == "" || $business_name == "" || $client_education == "" || $about_me == "" || (isset($page) && $page == 'bio'))
						{
							if($client_education == "")
							{
								$clientEducation.= '<option id="hs" value="High School">High School graduate</option>';
								$clientEducation.= '<option id="ad" value="Associate degree">Associate degree</option>';
								$clientEducation.= '<option id="bd" value="Bachelor degree">Bachelor degree</option>';
								$clientEducation.= '<option id="md" value="Master degree">Master degree</option>';
								$clientEducation.= '<option id="dpd" value="Doctoral or Professional degree">Doctoral or Professional degree</option></select>';
							}
							else
							{
								$clientEducation.= '<option id="hs" value="High School" '.(($client_education == 'High School')?' selected="selected"':'').'>High School graduate</option>';
								$clientEducation.= '<option id="ad" value="Associate degree" '.(($client_education == 'Associate degree')?' selected="selected"':'').'>Associate degree</option>';
								$clientEducation.= '<option id="bd" value="Bachelor degree" '.(($client_education == 'Bachelor degree')?' selected="selected"':'').'>Bachelor degree</option>';
								$clientEducation.= '<option id="md" value="Master degree" '.(($client_education == 'Master degree')?' selected="selected"':'').'>Master degree</option>';
								$clientEducation.= '<option id="dpd" value="Doctoral or Professional degree" '.(($client_education == 'Doctoral or Professional degree')?' selected="selected"':'').'>Doctoral or Professional degree</option></select>';
							}
							//CHECKING FOR EMERGENCY CONTACT//
							$sqlECI = "SELECT eci_application_id,eci_name,eci_email,eci_phone_number
							FROM emergency_contact_info where eci_application_id =?;";
							if($stmt = $conn->prepare($sqlECI)) 
							{
								$stmt->bind_param("i",$application_id);
								$stmt->execute();
								$stmt->bind_result($eci_application_id,$eci_name,$eci_email,$eci_phone_number);
								
								$emergencyContactInfo = '';
								$eciNameArray = array();
								$eciEmailArray = array();
								$eciPhoneArray = array();
								
								while($stmt->fetch())
								{
									$eciNameArray[] = $eci_name;
									$eciEmailArray[] = $eci_email;
									$eciPhoneArray[] = $eci_phone_number;
								}
								$stmt->close();
								if(!isset($eci_application_id) || $eci_application_id == 0)
								{
									$emergencyContactInfo .= '<div class="eci_row"><input style="width:15%" placeholder="Contact Name" type="text" class="eci_name">';
									$emergencyContactInfo .= '<input placeholder="Contact Email" style="margin-left:10px;width:15%;" type="text" class="eci_email">';
									$emergencyContactInfo .= '<input placeholder="Phone Number" style="margin-left:10px;width:15%;" type="text" class="eci_phone_number"><div onclick="addECIRow();"style="margin-left:5px;display:inline-block;cursor: pointer;"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-patch-plus-fll" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01-.622-.636zM8.5 6a.5.5 0 0 0-1 0v1.5H6a.5.5 0 0 0 0 1h1.5V10a.5.5 0 0 0 1 0V8.5H10a.5.5 0 0 0 0-1H8.5V6z"/>
									</svg></div></div>';
								}
								else
								{
									$err = count($eciNameArray) - 1;
									$counter = $err ;
									for($x = 0; $x <= $counter; $x++)
									{
										$emergencyContactInfo .= '<div class="eci_row"><input style="width:15%;" placeholder="Contact Name" type="text" class="eci_name" value='.$eciNameArray[$x].'>';
										$emergencyContactInfo .= '<input placeholder="Contact Email" style="margin-left:10px;width:15%;" type="text" class="eci_email" value='.$eciEmailArray[$x].'>';
										$emergencyContactInfo .= '<input placeholder="Phone Number"style="margin-left:10px;width:15%;" type="text" class="eci_phone_number" value='.$eciPhoneArray[$x].'>';
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
								$find = array("[application_id]","[name]","[primary_phone_number]","[primary_email]","[business_name]","[education]","[about_me]","[emergency_contact_info]");
								$repl = array($application_id,$name,$primary_phone_number,$primary_email,$business_name,$clientEducation,$about_me,$emergencyContactInfo);
								$return = str_replace($find,$repl,file_get_contents("client_bio.html"));
							}
						}
						elseif($signedDate == "" || $signatorName == "" || $signatorDecision == "" || (isset($page) && $page == 'agr'))
						{
								if($signatorDecision == "")
								{
									$signatorDecision = '<div style="display:inline-block;"><b>Sign Now</b><input style="margin-left:5px;"type="radio" id="signNow" name="signatorDecision"></div><div style="display:inline-block;margin-left:10px;"><b>Print & Sign Later</b><input style="margin-left:5px;" id="printSignLater" type="radio" name="signatorDecision"></div><br>';
								}
								if($signatorDecision == "signNow")
								{
									$signatorDecision = '<div style="display:inline-block;"><b>Sign Now</b><input style="margin-left:5px;" type="radio" id="signNow" checked="checked" name="signatorDecision"></div><div style="display:inline-block;margin-left:10px;"><b>Print & Sign Later</b><input style="margin-left:5px;" id="printSignLater" type="radio" name="signatorDecision"></div><br>';
								}
								if($signatorDecision == "printSignLater")
								{
									$signatorDecision = '<div style="display:inline-block;"><b>Sign Now</b><input style="margin-left:5px;" type="radio" id="signNow" checked="checked" name="signatorDecision"></div><div style="display:inline-block;margin-left:10px;"><b>Print & Sign Later</b><input style="margin-left:5px;" id="printSignLater" type="radio" checked="checked" name="signatorDecision"></div><br>';
								}
								$replClientAgreement[] = $application_id;
								$replClientAgreement[] = $signedDate;
								$replClientAgreement[] = $signatorName;
								$replClientAgreement[] = $signatorDecision;
								if($signatorBase64 == "")
								{
									$replClientAgreement[] = "//:0";
								}
								else
								{
									$replClientAgreement[] = 'data:image/png;base64,'.$signatorBase64;
								}
								
								$find = array("[application_id]","[signedDate]","[signatorName]","[signatorDecision]","[base64]");
								$return = str_replace($find,$replClientAgreement,file_get_contents("client_agreement.html"));
						}
						elseif($application_status == "pending" || (isset($page) && $page == 'rev'))
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
							$pdf->Cell(55,5,$name,1,1,'C');
							
							$pdf->SetFont('Arial','B',12);
							$pdf->setXY(70,43);
							$pdf->Cell(15,5,'Phone Number:',0,1,'R');
							$pdf->SetFont('Arial','',12);
							$pdf->setXY(86,43);
							$pdf->Cell(55,5,$primary_phone_number,1,1,'C');
							
							$pdf->SetFont('Arial','B',12);
							$pdf->setXY(70,51);
							$pdf->Cell(15,5,'Email Address:',0,1,'R');
							$pdf->SetFont('Arial','',12);
							$pdf->setXY(86,51);
							$pdf->Cell(55,5,$primary_email,1,1,'C');
							
							$pdf->SetFont('Arial','B',12);
							$pdf->setXY(70,59);
							$pdf->Cell(15,5,'Business Name:',0,1,'R');
							$pdf->SetFont('Arial','',12);
							$pdf->setXY(86,59);
							$pdf->Cell(55,5,$business_name,1,1,'C');
							
							$pdf->SetFont('Arial','B',12);
							$pdf->setXY(70,67);
							$pdf->Cell(15,5,'Education:',0,1,'R');
							$pdf->SetFont('Arial','',12);
							$pdf->setXY(86,67);
							$pdf->Cell(55,5,$client_education,1,1,'C');
							
							$pdf->SetFont('Arial','B',12);
							$pdf->setXY(105,75);
							$pdf->Cell(15,5,'About Me:',0,1,'R');
							$pdf->SetFont('Arial','',12);
							$pdf->setXY(65,80);
							$pdf->MultiCell(90,35,"",1,"L");
							$pdf->setXY(66,81);
							$pdf->MultiCell(90,5,$about_me,0,"L");
							
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
							
							
							//Don't think I need this//
							$sqlECI = "SELECT eci_application_id,eci_name,eci_email,eci_phone_number
							FROM emergency_contact_info where eci_application_id =?;";
							if($stmt = $conn->prepare($sqlECI)) 
							{
								$stmt->bind_param("i",$application_id);
								$stmt->execute();
								$stmt->bind_result($eci_application_id,$eci_name,$eci_email,$eci_phone_number);
								
								$eciNameArray = array();
								$eciEmailArray = array();
								$eciPhoneArray = array();
								
								while($stmt->fetch())
								{
									$eciNameArray[] = $eci_name;
									$eciEmailArray[] = $eci_email;
									$eciPhoneArray[] = $eci_phone_number;
								}
								$stmt->close();
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
							$pdf->SetFont('Arial','B',10);
							$pdf->setXY(26,40);
							$pdf->Cell(20,5,"Section 1:",'B',1,"L");
							$pdf->SetFont('Arial','',10);
							$pdf->setXY(20,38);
							$pdf->MultiCell(173,175,"",1,"L");
							$pdf->setXY(25,45);
							$pdf->MultiCell(165,5,"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ut lacinia risus. Proin sed elit ipsum. Mauris et mollis augue. Cras tellus nunc, luctus vel varius sit amet, faucibus eu ligula. Nulla sollicitudin, lorem id dictum congue, libero tortor rutrum libero, ac lobortis magna ante ut tellus. Suspendisse quis efficitur lorem. Fusce fermentum faucibus nisi, elementum egestas libero varius quis. Morbi convallis quam eget interdum consectetur. Suspendisse sapien elit, pretium nec ante ac, rhoncus dictum risus. Nunc dictum quis urna quis finibus. Aliquam nec massa magna. Maecenas ut sapien porta, vehicula dui a, commodo nisl. Quisque elementum venenatis est, quis porta enim mattis at. Proin consequat sit amet purus at egestas.",0,"L");
							$pdf->SetFont('Arial','B',10);
							$pdf->setXY(26,85);
							$pdf->Cell(20,5,"Section 2:",'B',1,"L");
							$pdf->SetFont('Arial','',10);
							$pdf->setXY(25,90);
							$pdf->MultiCell(165,5,"Suspendisse id ultrices tortor, eu aliquet mi. Praesent at mi eu metus porta tempor. Donec convallis, nunc a lacinia dignissim, enim sapien aliquet neque, ut egestas metus lacus eu sem. Etiam fringilla a libero sit amet tincidunt. Nam bibendum facilisis leo, nec pulvinar massa porta sit amet. Nulla tincidunt eros nec velit cursus, non maximus mi imperdiet. Praesent nec ligula ac leo rutrum gravida sit amet vel ligula. Duis a mollis lacus, vitae semper nisl. Proin vulputate bibendum ligula ac gravida. Morbi suscipit porta sapien vitae malesuada. Suspendisse lectus felis, hendrerit nec nulla ut, suscipit tempor nibh. In pulvinar vel tellus id interdum. Morbi gravida porta rhoncus. Phasellus non lobortis magna.	Fusce et rutrum magna. In suscipit lacus eget ante varius pellentesque. In dapibus justo vitae condimentum vehicula. Maecenas pellentesque dolor ut nisi maximus venenatis. Cras a egestas massa, ut sodales purus. Pellentesque et tristique libero. Etiam rhoncus at sapien quis vestibulum.",0,"L");
							$pdf->SetFont('Arial','B',10);
							$pdf->setXY(26,140);
							$pdf->Cell(20,5,"Section 3:",'B',1,"L");
							$pdf->SetFont('Arial','',10);
							$pdf->setXY(25,145);
							$pdf->MultiCell(165,5,"Cras luctus ex a lacus gravida accumsan. Sed nec ultrices est, eget tincidunt ipsum. Sed pharetra viverra nisl at faucibus. Cras lobortis dignissim vulputate. Curabitur commodo commodo vulputate. Aliquam eget maximus magna. Aliquam et mattis arcu. Pellentesque semper, dui eget blandit dapibus, augue nisi mollis erat, vel imperdiet eros ipsum a tortor. Donec sagittis dapibus sem. Pellentesque nibh ante, feugiat non turpis quis, sodales fringilla purus. Ut porttitor tempus aliquam. Nullam pharetra, justo a tempor rhoncus, est arcu faucibus lectus, vestibulum sollicitudin est tortor sit amet nunc. Duis aliquet non sapien eget viverra. Duis interdum lectus urna, eget venenatis sem pharetra id. Praesent condimentum pretium blandit. Fusce dignissim efficitur posuere. Praesent maximus mauris eget felis euismod, in posuere leo pulvinar. Morbi at quam ut velit gravida lacinia ornare eget quam. Donec egestas ligula vel dapibus vehicula. Aenean ante lacus, feugiat in sapien non, congue vestibulum mi. Maecenas vehicula iaculis purus, ac blandit ante imperdiet at. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce tempor arcu ut mauris tempus euismod. In hac habitasse platea dictumst.",0,"L");
							
							$signatorSql = "SELECT signedDate,signatorName,signatorDecision,signatorBase64
							FROM signator_info where application_id =?;";
							if($stmt = $conn->prepare($signatorSql))
							{
								$stmt->bind_param("i",$application_id);
								$stmt->execute();
								$stmt->bind_result($signedDate,$signatorName,$signatorDecision,$signatorBase64);
								while($stmt->fetch())
								{
									$pdf->setXY(60,230);
									$pdf->SetFont('Arial','B',12);
									$pdf->Cell(26,5,'Signed Date:',0,0,'C');
									$pdf->SetFont('Arial','',12);
									$pdf->setXY(90,230);
									$pdf->Cell(47,5,$signedDate,1,1,'C');
									
									$pdf->setXY(59,238);
									$pdf->SetFont('Arial','B',12);
									$pdf->Cell(26,5,'Signed Name:',0,0,'C');
									$pdf->SetFont('Arial','',12);
									$pdf->setXY(90,238);
									$pdf->Cell(47,5,$signatorName,1,1,'C');
									
									$pdf->setXY(43,265);
									$pdf->SetFont('Arial','B',12);
									$pdf->Cell(26,5,'Signature:',0,0,'C');
									$pdf->setXY(67,265);
									
									if($signatorDecision == "printSignLater")
									{
										$pdf->Cell(73,5,'',"B",0,'C');
									}
									if($signatorDecision == "signNow")
									{
										$img = 'data:image/png;base64,'.$signatorBase64;
										$pdf->Image($img,70,245,100,0,'png');
										$pdf->Cell(73,5,'',"B",0,'C');
									}
								}
								$stmt->close();
							}
							
							$pdf->Output('downloads/clientAppReview_'.$application_id.'.pdf','F');
							
							$find = array("[application_id]");
							$repl = array($application_id);
							$return = str_replace($find,$repl,file_get_contents("client_review.html"));
						}
						else
						{
							$tempReferenceNumber = "000".$reference_number;
							$referenceNumber = "";
							$counter = 0;
							
							for($x = 0; $x <= 4; $x++)
							{
								if($x > 1)
								{
									$counter++;
									$referenceNumber = substr($tempReferenceNumber, $counter);
								}
								else
								{
									$referenceNumber = $tempReferenceNumber;
									break;
								}
							}
								$find = array("[reference_number]","[name]");
								$repl = array($referenceNumber,$name);
								$return = str_replace($find,$repl,file_get_contents("client_completion.html"));
						}
						echo $return;
				}
		}
	}
}
?>