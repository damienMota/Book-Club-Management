<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
require('fpdf.php');
$conn = mysqli_connect('localhost', 'damien', 'Oimadi*1', 'application_management');
if(isset($_SESSION["user001auth"]) && $_SESSION["user001auth"] == "true")
{
	
	$sql = "SELECT * FROM application_main;";
	
	//Pending Table//
	$sqlPending = "SELECT application_id,name,primary_phone_number,primary_email,application_status,business_name,validation_code
	FROM application_main where application_status = 'pending';";
	$rsPending = mysqli_query($conn,$sqlPending);
	//Submitted Table//
	$sqlSubmitted = "SELECT application_id,name,primary_phone_number,primary_email,application_status,business_name,reference_number,client_URN
	FROM application_main where application_status = 'submitted';";
	$rsSubmitted = mysqli_query($conn,$sqlSubmitted);
	//Completed Table//
	$sqlCompleted = "SELECT application_id,name,primary_phone_number,primary_email,application_status,business_name,reference_number,client_URN
	FROM application_main where application_status = 'completed';";
	$rsCompleted = mysqli_query($conn,$sqlCompleted);
	
	if($_POST["action"] == "pending_table")
	{
		$header = array("application id","name","primary phone number","primary email","activity log","application status","business name","validation code");
		if(mysqli_num_rows($rsPending) == 0)
		{
			$ret = '<table style="width:100%;" id="pendingTable"><thead style="background-color:#D9DEDE;"><tr>';
			foreach($header as $fixedHeader)
			{
				$ret .= '<th>'.strtoupper($fixedHeader).'</th>';
			}
			$ret .= '</tr></thead></table>';
			echo $ret;
		}
		else
		{
			$ret = '<table style="width:100%;" id="pendingTable"><thead style="background-color:#D9DEDE;"><tr>';
	
			foreach($header as $fixedHeader)
			{
				$ret .= '<th>'.strtoupper($fixedHeader).'</th>';
			}

			$ret .= '</tr></thead>';
			while($rowPending = mysqli_fetch_assoc($rsPending))
			{
				if($rowPending["application_status"] == "pending")
				{
					$ret .= '<tr style="font-size:16px;">';
					$application_id = "";
					foreach($rowPending as $key => $item)
					{
						$ret .= '<td>'.$item.'</td>';
						if($key == 'application_id')
						{
							$application_id = $item;
						}
						if($key == "primary_email")
						{
							$ret .= '<td><button onclick="activityLog('.$application_id.');" type="button">Log</button></td>';
						}
					}
				}
			}
			$ret .= '</tr></table>';
			echo $ret;
		}
	}
	if($_POST["action"] == "submitted_table")
	{
		$header = array("application id","name","primary phone number","primary email","activity log","application status","business name","reference number","client urn");
		if(mysqli_num_rows($rsSubmitted) == 0)
		{
			$ret = '<table style="width:100%;" id="submittedTable"><thead style="background-color:#D9DEDE;"><tr>';
			foreach($header as $fixedHeader)
			{
				$ret .= '<th>'.strtoupper($fixedHeader).'</th>';
			}
			$ret .= '</tr></thead></table>';
			echo $ret;
		}
		else
		{
			$ret = '<table style="width:100%;" id="submittedTable"><thead style="background-color:#D9DEDE;"><tr>';
			foreach($header as $fixedHeader)
			{
				$ret .= '<th>'.strtoupper($fixedHeader).'</th>';
			}
			$ret .= '</tr></thead>';
			while($rowSubmitted = mysqli_fetch_assoc($rsSubmitted))
			{
				if($rowSubmitted["application_status"] == "submitted")
				{
					$ret .= '<tr style="font-family:16px;" class="editUser_S">';
					$application_id = "";
					foreach($rowSubmitted as $key => $item)
					{
						if($key == 'application_id')
						{
							$application_id = $item;
						}
						if($key == 'reference_number')
						{
							if(strlen($item) == 1)
							{
								$ret .= '<td>000'.$item.'</td>';
							}
							if(strlen($item) == 2)
							{
								$ret .= '<td>00'.$item.'</td>';
							}
							if(strlen($item) == 3)
							{
								$ret .= '<td>0'.$item.'</td>';
							}
							if(strlen($item) == 4)
							{
								$ret .= '<td>'.$item.'</td>';
							}
						}
						else
						{
							$ret .= '<td>'.$item.'</td>';
							if($key == "primary_email")
							{
								$ret .= '<td><button onclick="activityLog('.$application_id.');" type="button">Log</button></td>';
							}
						}
					}
				}
			}
			$ret .= '</tr></table>';
			echo $ret;
		}
	}
	if($_POST["action"] == "completed_table")
	{
		$header = array("application id","name","primary phone number","primary email","activity log","application status","business name","reference number","client urn");
		if(mysqli_num_rows($rsCompleted) == 0)
		{
			$ret = '<table style="width:100%;" id="completedTable"><thead style="background-color:#D9DEDE;"><tr>';
			foreach($header as $fixedHeader)
			{
				$ret .= '<th>'.strtoupper($fixedHeader).'</th>';
			}
			$ret .= '</tr></thead></table>';
			echo $ret;
		}
		else
		{
			$ret = '<table style="width:100%;" id="completedTable"><thead style="background-color:#D9DEDE;"><tr>';
			foreach($header as $fixedHeader)
			{
				$ret .= '<th>'.strtoupper($fixedHeader).'</th>';
			}
			$ret .= '</tr></thead>';
			while($rowCompleted = mysqli_fetch_assoc($rsCompleted))
			{
				if($rowCompleted["application_status"] == "completed")
				{
					$ret .= '<tr style="font-family:16px;" class="editUser_C">';
					$application_id = "";
					foreach($rowCompleted as $key => $item)
					{
						if($key == 'application_id')
						{
							$application_id = $item;
						}
						if($key == 'reference_number')
						{
							if(strlen($item) == 1)
							{
								$ret .= '<td>000'.$item.'</td>';
							}
							if(strlen($item) == 2)
							{
								$ret .= '<td>00'.$item.'</td>';
							}
							if(strlen($item) == 3)
							{
								$ret .= '<td>0'.$item.'</td>';
							}
							if(strlen($item) == 4)
							{
								$ret .= '<td>'.$item.'</td>';
							}
						}
						else
						{
							$ret .= '<td>'.$item.'</td>';
							if($key == "primary_email")
							{
								$ret .= '<td><button onclick="activityLog('.$application_id.');" type="button">Log</button></td>';
							}
						}
					}
				}
			}
			$ret .= '</tr></table>';
			echo $ret;
		}
	}
	if($_POST["action"] == "activity_log")
	{
		$sql = "SELECT action,description,time
		FROM activity_log where application_id =?;";
		
		if($stmt = $conn->prepare($sql)) 
		{
			$stmt->bind_param("i",$_POST["application_id"]);
			$stmt->execute();
			$stmt->bind_result($action,$description,$time);
			
			$ret = '<table id="activityTable"><thead><tr>';
			$headers = array("action","description","time");
			foreach($headers as $fixedHeader)
			{
				$ret .= '<th>'.strtoupper($fixedHeader).'</th>';
			}
			$ret .= '</tr></thead>';
			
			while($stmt->fetch()) 
			{
				$ret .= '<tr>';
				$row = array($action,$description,$time);
				foreach($row as $key => $item)
				{
					$ret .= '<td>'.$item.'</td>';
				}
			}
			$ret .= '</tr></table>';
			echo $ret;
		}
	}
	if($_POST["action"] == "initiate_application")
	{
		$name = $_POST["first_name"].' '.$_POST["last_name"];
		$sql = "SELECT application_id FROM application_main where name=? or primary_phone_number=? or primary_email=?";
		
		if($stmt = $conn->prepare($sql)) 
		{
			$stmt->bind_param("sss",$name,$_POST["primary_phone_number"],$_POST["primary_email"]);
			$stmt->execute();
			$stmt->bind_result($application_id);
			
			$existingApplicant = $stmt -> fetch();
			if(isset($existingApplicant))
			{
				$ret = "Error";
				echo $ret;
			}
			else
			{
				$action = "Initiated Application";
				$description = "";
					
				$email = $_POST["primary_email"];
				$subject = 'Test Subject';
				$validation_code = mt_rand(100000, 999999); 
				$client_URN = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'),1,16);
				// Building Prepared Statement for Insert into table application_main//
				$insertName = mysqli_real_escape_string($conn, $name);
				$insertPrimaryPhone = mysqli_real_escape_string($conn, $_POST["primary_phone_number"]);
				$insertPrimaryEmail = mysqli_real_escape_string($conn, $_POST["primary_email"]);
				$insertApplicationStatus = mysqli_real_escape_string($conn, "pending");
				$insertValidationCode = mysqli_real_escape_string($conn, $validation_code);
				
				$sql = "INSERT INTO application_main (name,primary_phone_number,primary_email,application_status,validation_code,client_URN)
						VALUES (?, ?, ?, ?, ?, ?);";
				$stmt = mysqli_stmt_init($conn);
				if(!mysqli_stmt_prepare($stmt, $sql))
				{
					echo "SQL ERROR";
				}
				else
				{
					mysqli_stmt_bind_param($stmt, "ssssis", $insertName, $insertPrimaryPhone, $insertPrimaryEmail, $insertApplicationStatus, $insertValidationCode, $client_URN);
					mysqli_stmt_execute($stmt);
				}
				
				// Formatting for PHP Mailer//		
				$find = array("[validation_code]","[application_id]","[client_URN]");
				$appId = mysqli_insert_id($conn);
				
				$insertSQL = "INSERT INTO activity_log (application_id,action)
				VALUES (?,?);";
				$insertSTMT = mysqli_stmt_init($conn);
				if(!mysqli_stmt_prepare($insertSTMT,$insertSQL))
				{
					echo "SQL ERROR";
				}
				else
				{
					mysqli_stmt_bind_param($insertSTMT, "is",$appId,$action);
					mysqli_stmt_execute($insertSTMT);
					// INSERT APPLICATION ID FOR SIGNATOR_INFO TABLE
					$sqlSI = "INSERT INTO signator_info (application_id)
							VALUES (?);";
					$stmtSI = mysqli_stmt_init($conn);
					if(!mysqli_stmt_prepare($stmtSI, $sqlSI))
					{
						echo "SQL ERROR";
					}
					else
					{
						mysqli_stmt_bind_param($stmtSI, "i", $appId);
						mysqli_stmt_execute($stmtSI);
					}
					$repl = array($validation_code,$appId,$client_URN);
					$fixedTemplate = str_replace($find,$repl,file_get_contents("initiation_email.html"));
					$body = $fixedTemplate;
					
					require_once "PHPMailer/PHPMailer.php";
					require_once "PHPMailer/SMTP.php";
					require_once "PHPMailer/Exception.php";

					$mail = new PHPMailer();

					// smtp settings
					$mail->isSMTP();
					$mail->Host = "smtp.gmail.com";
					$mail->SMTPAuth = true;
					$mail->Username = "mota.damien@gmail.com";
					$mail->Password = 'damienab';
					$mail->Port = 465;
					$mail->SMTPSecure = "ssl";

					// email settings
					$mail->isHTML(true);
					$mail->setFrom($email, $name);
					$mail->addAddress($email);
					$mail->Subject = ("Application Initiated for: ".$name);
					$mail->Body = $body;

					if($mail->send())
					{
						$status = "success";
						$response = "Email is sent!";
					}
					else
					{
						$status = "failed";
						$response = "Something is wrong: <br>" . $mail->ErrorInfo;
					}
					echo $status;
				}
			}
		}

	}
	if(isset($_POST["action"]) && $_POST["action"] == "getApplicationPDF")
	{
		$sql = "SELECT * FROM application_main where application_id =?;";

		if($stmt = $conn->prepare($sql))
		{
			$stmt->bind_param("i",$_POST["application_id"]);
			$stmt->execute();
			$stmt->bind_result($application_id,$name,$primary_phone_number,
			$primary_email,$application_status,$business_name,$reference_number,
			$validation_code,$client_URN,$client_education,$about_me);
			$stmt->fetch();
			$stmt->close();
			
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
			echo '<div id="applicationEmbedded"style="text-align:center;margin-top:40px;"><embed style="border:solid black 1px;" src="downloads/clientAppReview_'.$application_id.'.pdf" width="1000px" height="400px" /></div>';
		}
	}
	if(isset($_POST["action"]) && $_POST["action"] == "returnAppMessage")
	{
		$action = "Returned Application";
		$description = $_POST["returnAppMessage"];
		
		$insertSQL = "INSERT INTO activity_log (application_id,action,description)
		VALUES (?,?,?);";
		$insertSTMT = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($insertSTMT,$insertSQL))
		{
			echo "SQL ERROR";
		}
		else
		{
			mysqli_stmt_bind_param($insertSTMT, "iss",$_POST["application_id"],$action,$description);
			mysqli_stmt_execute($insertSTMT);
			
			$name = $_POST["name"];
			$email = $_POST["email"];
			$subject = 'Returning Application';
			$validation_code = mt_rand(100000, 999999); 
			$client_URN = $_POST["clientURN"];
			//Building Prepared Statement for Insert into table application_main//
			
			$applicationStatus = mysqli_real_escape_string($conn, "pending");
		
			$updateSQL = "UPDATE application_main set validation_code =?, application_status =? where application_id =?;";
			$stmt = mysqli_stmt_init($conn);
			if(!mysqli_stmt_prepare($stmt, $updateSQL))
			{
				echo "SQL ERROR";
			}
			else
			{
				mysqli_stmt_bind_param($stmt, "isi", $validation_code,$applicationStatus,$_POST["application_id"]);
				mysqli_stmt_execute($stmt);
			}
			
			//Formatting for PHP Mailer//		
			$find = array("[validation_code]","[client_URN]","[return_app_message]","[name]");
			$appId = mysqli_insert_id($conn);
			
			$repl = array($validation_code,$client_URN,$_POST["returnAppMessage"],$_POST["name"]);
			$fixedTemplate = str_replace($find,$repl,file_get_contents("return_application_email.html"));
			$body = $fixedTemplate;
			
			require_once "PHPMailer/PHPMailer.php";
			require_once "PHPMailer/SMTP.php";
			require_once "PHPMailer/Exception.php";

			$mail = new PHPMailer();

			//smtp settings
			$mail->isSMTP();
			$mail->Host = "smtp.gmail.com";
			$mail->SMTPAuth = true;
			$mail->Username = "mota.damien@gmail.com";
			$mail->Password = 'damienab';
			$mail->Port = 465;
			$mail->SMTPSecure = "ssl";

			//email settings
			$mail->isHTML(true);
			$mail->setFrom($email, $name);
			$mail->addAddress($email);
			$mail->Subject = ("Application Returned for: ".$name);
			$mail->Body = $body;

			if($mail->send()){
				$status = "success";
				$response = "Email is sent!";
			}
			else
			{
				$status = "failed";
				$response = "Something is wrong: <br>" . $mail->ErrorInfo;
			}
			echo $status;
		}
	}
	if(isset($_POST["action"]) && $_POST["action"] == "markApplicationComplete")
	{
		$status = "completed";
		$updateSQL = "UPDATE application_main set application_status =? WHERE application_id =?;";
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $updateSQL))
		{
			echo "SQL ERROR";
		}
		else
		{
			mysqli_stmt_bind_param($stmt, "si",$status,$_POST["application_id"]);
			mysqli_stmt_execute($stmt);
			
			$action = "Marked Complete";
			$insertSQL = "INSERT INTO activity_log (application_id,action)
			VALUES (?,?);";
			$insertSTMT = mysqli_stmt_init($conn);
			if(!mysqli_stmt_prepare($insertSTMT,$insertSQL))
			{
				echo "SQL ERROR";
			}
			else
			{
				mysqli_stmt_bind_param($insertSTMT, "is",$_POST["application_id"],$action);
				mysqli_stmt_execute($insertSTMT);
			}
			$name = $_POST["name"];
			$email = $_POST["email"];
			//Formatting for PHP Mailer//		
			$find = array("[name]");
			$repl = array($name);
			
			$fixedTemplate = str_replace($find,$repl,file_get_contents("application_completed_email.html"));
			$body = $fixedTemplate;
			
			require_once "PHPMailer/PHPMailer.php";
			require_once "PHPMailer/SMTP.php";
			require_once "PHPMailer/Exception.php";

			$mail = new PHPMailer();

			//smtp settings
			$mail->isSMTP();
			$mail->Host = "smtp.gmail.com";
			$mail->SMTPAuth = true;
			$mail->Username = "mota.damien@gmail.com";
			$mail->Password = 'damienab';
			$mail->Port = 465;
			$mail->SMTPSecure = "ssl";

			//email settings
			$mail->isHTML(true);
			$mail->setFrom($email, $name);
			$mail->addAddress($email);
			$mail->Subject = ("Application Marked Complete for: ".$name);
			$mail->Body = $body;
			$mail->AddEmbeddedImage('images/ubik.jpg','ubik');
			$mail->AddEmbeddedImage('images/cantHurtMe.jpg','cantHurtMe');
			$mail->AddEmbeddedImage('images/scythe.jpg','scythe');

			if($mail->send()){
				$status = "success";
				$response = "Email is sent!";
			}
			else
			{
				$status = "failed";
				$response = "Something is wrong: <br>" . $mail->ErrorInfo;
			}
			echo $status;
		}
	}
	if(isset($_POST["action"]) && $_POST["action"] == "markApplicationIncomplete")
	{
		$status = "submitted";
		$updateSQL = "UPDATE application_main set application_status =? WHERE application_id =?;";
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $updateSQL))
		{
			echo "SQL ERROR";
		}
		else
		{
			mysqli_stmt_bind_param($stmt, "si",$status,$_POST["application_id"]);
			mysqli_stmt_execute($stmt);
			
			$action = "Marked Incomplete";
			$insertSQL = "INSERT INTO activity_log (application_id,action)
			VALUES (?,?);";
			$insertSTMT = mysqli_stmt_init($conn);
			if(!mysqli_stmt_prepare($insertSTMT,$insertSQL))
			{
				echo "SQL ERROR";
			}
			else
			{
				mysqli_stmt_bind_param($insertSTMT, "is",$_POST["application_id"],$action);
				mysqli_stmt_execute($insertSTMT);
				echo 'success';
			}
		}
	}
	if(isset($_POST["action"]) && $_POST["action"] == "logout")
	{
		$_SESSION["user001auth"] = "";
		echo "success";
	}
	if(isset($_POST["action"]) && $_POST["action"] == "saveApplication")
    {
		$sql = "SELECT application_id FROM application_main where name=? or primary_phone_number=?";
		if($stmt = $conn->prepare($sql)) 
		{
			$stmt->bind_param("ss",$_POST["name"],$_POST["primary_phone_number"]);
			$stmt->bind_result($appId);
			$stmt->execute();		
			$stmt -> fetch();
			$stmt->close();
			if(isset($appId) && $appId != $_POST["application_id"])
			{
				$ret = "Error";
				echo $ret;
			}
			else
			{
				$selectSQL = "SELECT business_name,name,primary_phone_number,primary_email FROM application_main WHERE application_id =?;";
				if($stmt = $conn->prepare($selectSQL))
				{
					$stmt->bind_param("i",$_POST["application_id"]);
					$stmt->execute();
					$stmt->bind_result($business_name,$name,$primary_phone_number,$primary_email);
					$stmt->fetch();
					$stmt->close();
					
					$updateSQL = "UPDATE application_main set business_name =?, name =?, primary_phone_number =?, primary_email =? WHERE application_id =?;";
					$stmt = mysqli_stmt_init($conn);
					if(!mysqli_stmt_prepare($stmt, $updateSQL))
					{
						echo "SQL ERROR";
					}
					else
					{
						mysqli_stmt_bind_param($stmt, "ssssi",$_POST["business_name"],$_POST["name"],$_POST["primary_phone_number"],$_POST["primary_email"],$_POST["application_id"]);
						mysqli_stmt_execute($stmt);
						
						$err = array();
						if($name != $_POST["name"])
						{
							$err[] = "Name";
						}
						if($primary_phone_number != $_POST["primary_phone_number"])
						{
							$err[] = "Phone Number";
						}
						if($primary_email != $_POST["primary_email"])
						{
							$err[] = "Primary Email";
						}
						if($business_name != $_POST["business_name"])
						{
							$err[] = "Business Name";
						}
						
						$action = "Edited Application";
						if(empty($err))
						{
							echo "success";
						}
						else
						{
							$description = "The following text fields have changed: ".implode(", ",$err);
							$insertSQL = "INSERT INTO activity_log (application_id,action,description)
							VALUES (?,?,?);";
							$insertSTMT = mysqli_stmt_init($conn);
							if(!mysqli_stmt_prepare($insertSTMT,$insertSQL))
							{
								echo "SQL ERROR";
							}
							else
							{
								mysqli_stmt_bind_param($insertSTMT, "iss",$_POST["application_id"],$action,$description);
								mysqli_stmt_execute($insertSTMT);
								echo 'success';
							}
						}
					}
				}
			}
		}
    }
}
if(isset($_SESSION["client_application"]) && $_SESSION["client_application"] == "true")
{
    if(isset($_POST["action"]) && $_POST["action"] == "header_validation")
    {
    	$sql = "SELECT * FROM application_main where client_URN =?;";
    	if($stmt = $conn->prepare($sql))
    	{
    		$stmt->bind_param("s",$_POST["client_URN"]);
    		$stmt->execute();
    		$stmt->bind_result($application_id,$name,$primary_phone_number,
    		$primary_email,$application_status,$business_name,$reference_number,
    		$validation_code,$client_URN,$client_education,$about_me);
    		
    		$stmt->fetch();
    		$stmt->close();
    		
    		$signatorSql = "SELECT signedDate,signatorName,signatorDecision,signatorBase64
    		FROM signator_info where application_id =?;";
    		if($stmt = $conn->prepare($signatorSql))
    		{
    			$stmt->bind_param("i",$application_id);
    			$stmt->execute();
    			$stmt->bind_result($signedDate,$signatorName,
    			$signatorDecision,$signatorBase64);
    			$stmt->fetch();
    			$stmt->close();
                
    			if($name == "" || $primary_email == "" || $primary_phone_number == "" || $business_name == "" || $client_education == "" || $about_me == "")
    			{
    				$ret = array("agr","rev");
    			}
    			elseif($signedDate == "" || $signatorName == "" || $signatorDecision == "")
    			{
    				$ret = array("rev");
    			}
    			else
    			{
    				$ret = array("success");
    			}
    			echo json_encode($ret);
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
    	$action = "Submitted Application";
    	$description = "";
    	
    	$insertSQL = "INSERT INTO activity_log (application_id,action)
    	VALUES (?,?);";
    	if($stmt = $conn->prepare($insertSQL))
    	{
    		$stmt->bind_param("is",$_POST["application_id"],$action);
    		$stmt->execute();
    		$stmt->close();
    		
    		$status = "submitted";
    		$updateSQL = "UPDATE application_main SET application_status =?,reference_number =? WHERE application_id =?;";
    		
    		if($stmt = $conn->prepare($updateSQL))
    		{
    			$stmt->bind_param("sii",$status,$_POST["application_id"],$_POST["application_id"]);
    			$stmt->execute();
    			$stmt->close();
    			
    			$sql = "SELECT reference_number,name,primary_email FROM application_main where application_id =?;";
    			if($stmt = $conn->prepare($sql))
    			{
    				$stmt->bind_param("i",$_POST["application_id"]);
    				$stmt->execute();
    				$stmt->bind_result($reference_number,$name,$primary_email);
    				$stmt->fetch();
    				
    				require_once "PHPMailer/PHPMailer.php";
    				require_once "PHPMailer/SMTP.php";
    				require_once "PHPMailer/Exception.php";
    
    				$mail = new PHPMailer();
    
    				$mail->isSMTP();
    				$mail->Host = "smtp.gmail.com";
    				$mail->SMTPAuth = true;
    				$mail->Username = "mota.damien@gmail.com";
    				$mail->Password = "damienab";
    				$mail->Port = 465;
    				$mail->SMTPSecure = "ssl";
    
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
    				$find = array("[name]","[reference_number]");
    				$repl = array($name,$referenceNumber);
    				$body = str_replace($find,$repl,file_get_contents("submitted_email.html"));
    				
    				$mail->isHTML(true);
    				$mail->setFrom($primary_email, $name);
    				$mail->addAddress($primary_email);
    				$mail->Subject = ("Application Submitted for: ".$name);
    				$mail->Body = $body;
    				
    				$status = "";
    				if($mail->send()){
    					$status = "success";
    					$response = "Email is sent!";
    				}
    				else
    				{
    					$status = "failed";
    					$response = "Something is wrong: <br>" . $mail->ErrorInfo;
    				}
    				echo $status;
    			}
    		}
    	}
    }
}
if($_POST["action"] == "submit_verification_code")
{
	$validationCode = $_POST["code"];
	$sql = "SELECT client_URN FROM application_main where validation_code=?;";
	if($stmt = $conn->prepare($sql)) 
	{
		$stmt->bind_param("i",$validationCode);
		$stmt->execute();
		$stmt->bind_result($client_URN);
		$stmt->fetch();
		if(isset($client_URN) && $client_URN != "")
		{
			$_SESSION["client_application"] = "true";
			$return[] = $client_URN;
			$return[] = "Success";
			echo json_encode($return);
		}
		else
		{
			echo json_encode("Error");
		}
	}
}
if($_POST["action"] == "resend_verification_code")
{
	$email = mysqli_real_escape_string($conn, $_POST["email"]);
	$phone = mysqli_real_escape_string($conn, $_POST["phone"]);
	
	$sql = "SELECT application_id,name,primary_email,client_URN 
	FROM application_main where primary_email =? and primary_phone_number =?;";
	if($stmt = $conn->prepare($sql)) 
	{
		$validation_code = mt_rand(100000, 999999);
		$stmt->bind_param("ss",$email,$phone);
		$stmt->execute();
		$stmt->bind_result($application_id,$name,$primary_email,$client_URN);
		$stmt->fetch();
		$stmt->close();
		
		if(isset($application_id) && $application_id != "")
		{
			$updateSQL = "UPDATE application_main set validation_code =? where application_id =?;";
			if($stmt2 = $conn->prepare($updateSQL)) 
			{
				$stmt2->bind_param("ii",$validation_code,$application_id);
				$stmt2->execute();
				
				$email = $primary_email;
				$name = $name;
				$find = array("[validation_code]","[client_URN]");
				$repl = array($validation_code,$client_URN);
				$fixedTemplate = str_replace($find,$repl,file_get_contents("initiation_email.html"));
				$body = $fixedTemplate;
				
				require_once "PHPMailer/PHPMailer.php";
				require_once "PHPMailer/SMTP.php";
				require_once "PHPMailer/Exception.php";

				$mail = new PHPMailer();

				// smtp settings
				$mail->isSMTP();
				$mail->Host = "smtp.gmail.com";
				$mail->SMTPAuth = true;
				$mail->Username = "mota.damien@gmail.com";
				$mail->Password = 'damienab';
				$mail->Port = 465;
				$mail->SMTPSecure = "ssl";

				// email settings
				$mail->isHTML(true);
				$mail->setFrom($email, $name);
				$mail->addAddress($email);
				$mail->Subject = ("New Verification Code for: ".$name);
				$mail->Body = $body;
				$status = "";
				if($mail->send()){
					$status = "success";
					$response = "Email is sent!";
				}
				else
				{
					$status = "failed";
					$response = "Something is wrong: <br>" . $mail->ErrorInfo;
				}
				echo $status;
			}
		}
		else
		{
			echo "Error";
		}
	}
}
?>