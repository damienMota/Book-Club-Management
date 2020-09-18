<?php
	use PHPMailer\PHPMailer\PHPMailer;
	
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
			$ret = '<table id="pendingTable"><thead><tr>';
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
			while($rowPending = mysqli_fetch_assoc($rsPending))
			{
				if($rowPending["application_status"] == "pending")
				{
					$ret = '<table id="pendingTable">';
					for($i=0;$i<mysqli_num_fields($rs);$i++)
					{
						$fixedHeader = str_replace("_"," ",mysqli_fetch_field_direct($rs,$i)->name);
						$ret .= '<th>'.strtoupper($fixedHeader).'</th>';
					}
					$ret .= '</tr></thead>';
						$ret .= '<tr>';
					foreach($rowPending as $key => $item)
					{
						$ret .= '<td>'.$item.'</td>';
					}
					$ret .= '</tr></table>';
					echo $ret;
				}
			}
		}
	}
	if($_POST["action"] == "submitted_table")
	{
		if(mysqli_num_rows($rsSubmitted) == 0)
		{
			$ret = '<table id="submittedTable"><thead><tr>';
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
			while($rowSubmitted = mysqli_fetch_assoc($rsSubmitted))
			{
				if($rowSubmitted["application_status"] == "submitted")
				{
					$ret = '<table id="submittedTable"><tr>';
					for($i=0;$i<mysqli_num_fields($rs);$i++)
					{
						$fixedHeader = str_replace("_"," ",mysqli_fetch_field_direct($rs,$i)->name);
						$ret .= '<th>'.strtoupper($fixedHeader).'</th>';
					}
					$ret .= '</tr></thead>';
					$ret .= '<tr>';
					foreach($rowSubmitted as $key => $item)
					{
						$ret .= '<td>'.$item.'</td>';
					}
					$ret .= '</tr></table>';
					echo $ret;
				}
			}
		}
	}
	if($_POST["action"] == "completed_table")
	{
		if(mysqli_num_rows($rsCompleted) == 0)
		{
			$ret = '<table id="completedTable"><thead><tr>';
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
			while($rowCompleted = mysqli_fetch_assoc($rsCompleted))
			{
				if($rowCompleted["application_status"] == "submitted")
				{
					$ret = '<table id="submittedTable"><tr>';
					for($i=0;$i<mysqli_num_fields($rs);$i++)
					{
						$fixedHeader = str_replace("_"," ",mysqli_fetch_field_direct($rs,$i)->name);
						$ret .= '<th>'.strtoupper($fixedHeader).'</th>';
					}
					$ret .= '</tr></thead>';
					$ret .= '<tr>';
					foreach($rowCompleted as $key => $item)
					{
						$ret .= '<td>'.$item.'</td>';
					}
					$ret .= '</tr></table>';
					echo $ret;
				}
			}
		}
	}
	if($_POST["action"] == "initiate_application")
	{
		$name = $_POST["first_name"].' '.$_POST["last_name"];
		$email = $_POST["primary_email"];
		$subject = 'Test Subject';
		$validation_code = mt_rand(100000, 999999); 
		$client_URN = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz!@#$%^&*()'),1,16);
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
			while($row = mysqli_fetch_assoc($result))
			{
				if(isset($row["application_id"]) && $row["application_id"] != "")
				{
					$return[] = $row["client_URN"];
					$return[] = "Success";
					echo json_encode($return);
				}
				else
				{
					echo "Error";
				}
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
?>