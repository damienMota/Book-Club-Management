<?php
	use PHPMailer\PHPMailer\PHPMailer;
	require('fpdf.php');
	$conn = mysqli_connect('localhost', 'damien', 'Oimadi*1', 'application_management');
	$sql = "SELECT * FROM application_main;";
	$sqlHeaders = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'application_main';";
	//Pending Table//
	$sqlPending = "SELECT * FROM application_main where application_status = 'pending';";
	$rsPending = mysqli_query($conn,$sqlPending);
	//Submitted Table//
	$sqlSubmitted = "SELECT * FROM application_main where application_status = 'submitted';";
	$rsSubmitted = mysqli_query($conn,$sqlSubmitted);
	//Completed Table//
	$sqlCompleted = "SELECT * FROM application_main where application_status = 'completed';";
	$rsCompleted = mysqli_query($conn,$sqlCompleted);
	$rs = mysqli_query($conn,$sql);
	$rsHeaders = mysqli_query($conn,$sqlHeaders);
	if($_POST["action"] == "pending_table")
	{
		if(mysqli_num_rows($rsPending) == 0)
		{
			$ret = '<table style="width:100%;" id="pendingTable"><thead style="background-color:#D9DEDE;"><tr>';
			while($rowNum0 = mysqli_fetch_assoc($rsHeaders))
			{
				foreach($rowNum0 as $header)
				{
					$fixedHeader = str_replace("_"," ",$header);
					$ret .= '<th>'.strtoupper($fixedHeader).'</th>';
				}
			}
			$ret .= '</tr></thead></table>';
			echo $ret;
		}
		else
		{
			$ret = '<table style="width:100%;" id="pendingTable"><thead style="background-color:#D9DEDE;"><tr>';
			for($i=0;$i<mysqli_num_fields($rs);$i++)
			{
				$fixedHeader = str_replace("_"," ",mysqli_fetch_field_direct($rs,$i)->name);
				$ret .= '<th>'.strtoupper($fixedHeader).'</th>';
				if($fixedHeader == "primary email")
				{
					$ret .= '<th>Activity Log</th>';
				}
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
		if(mysqli_num_rows($rsSubmitted) == 0)
		{
			$ret = '<table style="width:100%;" id="submittedTable"><thead style="background-color:#D9DEDE;"><tr>';
			while($rowNum0 = mysqli_fetch_assoc($rsHeaders))
			{
				foreach($rowNum0 as $header)
				{
					$fixedHeader = str_replace("_"," ",$header);
					$ret .= '<th>'.strtoupper($fixedHeader).'</th>';
					if($fixedHeader == "primary email")
					{
						$ret .= '<th>Activity Log</th>';
					}
				}
			}
			$ret .= '</tr></thead></table>';
			echo $ret;
		}
		else
		{
			$ret = '<table style="width:100%;" id="submittedTable"><thead style="background-color:#D9DEDE;"><tr>';
			for($i=0;$i<mysqli_num_fields($rs);$i++)
			{
				$fixedHeader = str_replace("_"," ",mysqli_fetch_field_direct($rs,$i)->name);
				$ret .= '<th>'.strtoupper($fixedHeader).'</th>';
				if($fixedHeader == "primary email")
				{
					$ret .= '<th>Activity Log</th>';
				}
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
		if(mysqli_num_rows($rsCompleted) == 0)
		{
			$ret = '<table style="width:100%;" id="completedTable"><thead style="background-color:#D9DEDE;"><tr>';
			while($rowNum0 = mysqli_fetch_assoc($rsHeaders))
			{
				foreach($rowNum0 as $header)
				{
					$fixedHeader = str_replace("_"," ",$header);
					$ret .= '<th>'.strtoupper($fixedHeader).'</th>';
					if($fixedHeader == "primary email")
					{
						$ret .= '<th>Activity Log</th>';
					}
				}
			}
			$ret .= '</tr></thead></table>';
			echo $ret;
		}
		else
		{
			$ret = '<table style="width:100%;" id="completedTable"><thead style="background-color:#D9DEDE;"><tr>';
			for($i=0;$i<mysqli_num_fields($rs);$i++)
			{
				$fixedHeader = str_replace("_"," ",mysqli_fetch_field_direct($rs,$i)->name);
				$ret .= '<th>'.strtoupper($fixedHeader).'</th>';
				if($fixedHeader == "primary email")
				{
					$ret .= '<th>Activity Log</th>';
				}
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
		$sql = "SELECT * FROM activity_log where application_id =?;";
		$alSQL = "SELECT * FROM activity_log;";
		$rs = mysqli_query($conn,$alSQL);
		$stmt = mysqli_stmt_init($conn);
		mysqli_stmt_prepare($stmt, $sql);
		if(!mysqli_stmt_prepare($stmt, $sql))
		{
			echo "SQL ERROR";
		}
		else
		{
			mysqli_stmt_bind_param($stmt, "i",$_POST["application_id"]);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			
			$ret = '<table style="width:100%;" id="activityTable" style="width:95%;"><thead><tr>';
			for($i=0;$i<mysqli_num_fields($rs);$i++)
			{
				if(mysqli_fetch_field_direct($rs,$i)->name == "application_id")
				{
					continue;
				}
				else
				{
					$fixedHeader = str_replace("_"," ",mysqli_fetch_field_direct($rs,$i)->name);
					$ret .= '<th>'.strtoupper($fixedHeader).'</th>';
				}
			}
			$ret .= '</tr></thead>';
			while($row = mysqli_fetch_assoc($result))
			{
				$ret .= '<tr>';
				foreach($row as $key => $item)
				{
					if($key == "application_id")
					{
						continue;
					}
					else
					{
						$ret .= '<td>'.$item.'</td>';
					}
				}
			}
			$ret .= '</tr></table>';
			echo $ret;
		}
	}
	if($_POST["action"] == "initiate_application")
	{
		$action = "Initiated Application";
		$description = "";
		
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
			
			$name = $_POST["first_name"].' '.$_POST["last_name"];
			$email = $_POST["primary_email"];
			$subject = 'Test Subject';
			$validation_code = mt_rand(100000, 999999); 
			$client_URN = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'),1,16);
			//Building Prepared Statement for Insert into table application_main//
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
			
			//Formatting for PHP Mailer//		
			$find = array("[validation_code]","[application_id]","[client_URN]");
			$appId = mysqli_insert_id($conn);
			//INSERT APPLICATION ID FOR SIGNATOR_INFO TABLE
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
			$mail->Subject = ("Application Initiated for: ".$name);
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
	if($_POST["action"] == "submit_verification_code")
	{
		
		$validationCode = mysqli_real_escape_string($conn, $_POST["code"]);
		
		$sql = "SELECT * FROM application_main where validation_code=?;";
		$stmt = mysqli_stmt_init($conn);
		mysqli_stmt_prepare($stmt, $sql);
		if(!mysqli_stmt_prepare($stmt, $sql))
		{
			echo "SQL ERROR";
		}
		else
		{
			mysqli_stmt_bind_param($stmt, "i",$validationCode);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			$numRows = mysqli_num_rows($result);
			$clientURN = "";
			while($row = mysqli_fetch_assoc($result))
			{
				$clientURN = $row["client_URN"];
			}
				if($numRows != 0)
				{
					$return[] = $clientURN;
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
		
		$sql = "SELECT * FROM application_main where primary_email =? and primary_phone_number =?;";
		$stmt = mysqli_stmt_init($conn);
		mysqli_stmt_prepare($stmt, $sql);
		if(!mysqli_stmt_prepare($stmt, $sql))
		{
			echo "SQL ERROR";
		}
		else
		{
			$validation_code = mt_rand(100000, 999999);
			mysqli_stmt_bind_param($stmt, "ss",$email,$phone);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			while($row = mysqli_fetch_assoc($result))
			{
				// mysqli_stmt_store_result($stmt); //This originally was after the execute//This is used to use num rows
				if(isset($row["application_id"]) && $row["application_id"] != "")
				{
					$updateSQL = "UPDATE application_main set validation_code =? where application_id =?;";
					$updateSTMT = mysqli_stmt_init($conn);
					mysqli_stmt_prepare($updateSTMT, $updateSQL);
					if(!mysqli_stmt_prepare($updateSTMT, $updateSQL))
					{
						echo "SQL ERROR";
					}
					else
					{
						mysqli_stmt_bind_param($updateSTMT, "ii",$validation_code,$row["application_id"]);
						mysqli_stmt_execute($updateSTMT);
						
						$email = $row["primary_email"];
						$name = $row["name"];
						$find = array("[validation_code]");
						$repl = array($validation_code);
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
		$insertSTMT = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($insertSTMT,$insertSQL))
		{
			echo "SQL ERROR";
		}
		else
		{
			mysqli_stmt_bind_param($insertSTMT, "is",$_POST["application_id"],$action);
			mysqli_stmt_execute($insertSTMT);
			
			$status = "submitted";
			$updateSQL = "UPDATE application_main SET application_status =?,reference_number =? WHERE application_id =?;";
			$updateSTMT = mysqli_stmt_init($conn);
			mysqli_stmt_prepare($updateSTMT, $updateSQL);
			if(!mysqli_stmt_prepare($updateSTMT, $updateSQL))
			{
				echo "SQL ERROR";
			}
			else
			{
				mysqli_stmt_bind_param($updateSTMT, "sii",$status,$_POST["application_id"],$_POST["application_id"]);
				mysqli_stmt_execute($updateSTMT);
				
				$sql = "SELECT * FROM application_main where application_id =?;";
				$stmt = mysqli_stmt_init($conn);
				mysqli_stmt_prepare($stmt, $sql);
				if(!mysqli_stmt_prepare($stmt, $sql))
				{
					echo "SQL ERROR";
				}
				else
				{
					mysqli_stmt_bind_param($stmt, "i",$_POST["application_id"]);
					mysqli_stmt_execute($stmt);
					$result = mysqli_stmt_get_result($stmt);
					
					require_once "PHPMailer/PHPMailer.php";
					require_once "PHPMailer/SMTP.php";
					require_once "PHPMailer/Exception.php";

					$mail = new PHPMailer();

					$mail->isSMTP();
					$mail->Host = "smtp.gmail.com";
					$mail->SMTPAuth = true;
					$mail->Username = "mota.damien@gmail.com";
					$mail->Password = 'damienab';
					$mail->Port = 465;
					$mail->SMTPSecure = "ssl";
					
					while($rowEmail = mysqli_fetch_assoc($result))
					{
						$tempReferenceNumber = "000".$rowEmail["reference_number"];
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
						$repl = array($rowEmail["name"],$referenceNumber);
						$body = str_replace($find,$repl,file_get_contents("submitted_email.html"));
						
						$mail->isHTML(true);
						$mail->setFrom($rowEmail["primary_email"], $rowEmail["name"]);
						$mail->addAddress($rowEmail["primary_email"]);
						$mail->Subject = ("Application Submitted for: ".$rowEmail["name"]);
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
	if(isset($_POST["action"]) && $_POST["action"] == "getApplicationPDF")
	{
		$sql = "SELECT * FROM application_main where application_id =?;";
		$stmt = mysqli_stmt_init($conn);
		mysqli_stmt_prepare($stmt, $sql);
		if(!mysqli_stmt_prepare($stmt, $sql))
		{
			echo "SQL ERROR";
		}
		else
		{
			mysqli_stmt_bind_param($stmt, "i",$_POST["application_id"]);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			while($row = mysqli_fetch_assoc($result))
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
						$pdf->setXY(60,230);
						$pdf->SetFont('Arial','B',12);
						$pdf->Cell(26,5,'Signed Date:',0,0,'C');
						$pdf->SetFont('Arial','',12);
						$pdf->setXY(90,230);
						$pdf->Cell(47,5,$rowSig["signedDate"],1,1,'C');
						
						$pdf->setXY(59,238);
						$pdf->SetFont('Arial','B',12);
						$pdf->Cell(26,5,'Signed Name:',0,0,'C');
						$pdf->SetFont('Arial','',12);
						$pdf->setXY(90,238);
						$pdf->Cell(47,5,$rowSig["signatorName"],1,1,'C');
						
						$pdf->setXY(43,265);
						$pdf->SetFont('Arial','B',12);
						$pdf->Cell(26,5,'Signature:',0,0,'C');
						$pdf->setXY(67,265);
						
						if($rowSig["signatorDecision"] == "printSignLater")
						{
							$pdf->Cell(73,5,'',"B",0,'C');
						}
						if($rowSig["signatorDecision"] == "signNow")
						{
							$img = 'data:image/png;base64,'.$rowSig["signatorBase64"];
							$pdf->Image($img,70,245,100,0,'png');
							$pdf->Cell(73,5,'',"B",0,'C');
						}
					}
				}
				
				$pdf->Output('downloads/clientAppReview_'.$row["application_id"].'.pdf','F');
				echo '<div id="applicationEmbedded"style="text-align:center;margin-top:40px;"><embed style="border:solid black 1px;" src="downloads/clientAppReview_'.$row["application_id"].'.pdf" width="1000px" height="400px" /></div>';
			}
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
	if(isset($_POST["action"]) && $_POST["action"] == "saveApplication")
	{
		$selectSQL = "SELECT business_name,name,primary_phone_number,primary_email FROM application_main WHERE application_id =?;";
		$selectStmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($selectStmt, $selectSQL))
		{
			echo "SQL ERROR";
		}
		else
		{
			mysqli_stmt_bind_param($selectStmt, "i",$_POST["application_id"]);
			mysqli_stmt_execute($selectStmt);
			$result = mysqli_stmt_get_result($selectStmt);
			while($row = mysqli_fetch_assoc($result))
			{
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
					if($row["name"] != $_POST["name"])
					{
						$err[] = "Name";
					}
					if($row["primary_phone_number"] != $_POST["primary_phone_number"])
					{
						$err[] = "Phone Number";
					}
					if($row["primary_email"] != $_POST["primary_email"])
					{
						$err[] = "Primary Email";
					}
					if($row["business_name"] != $_POST["business_name"])
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
				echo 'success';
			}
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
?>