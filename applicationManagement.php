<?php
	use PHPMailer\PHPMailer\PHPMailer;
	
	$conn = mysqli_connect('localhost', 'damien', 'Oimadi*1', 'application_management');
	$sql = "SELECT * FROM application_main;";
	$sqlHeaders = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'application_main';";
	//Pending Table//
	$sqlPending = "SELECT * FROM application_main where application_status = 'pending';";
	$rsPending = mysqli_query($conn,$sqlPending);
	$rowPending = mysqli_fetch_assoc($rsPending);
	//Submitted Table//
	$sqlSubmitted = "SELECT * FROM application_main where application_status = 'submitted';";
	$rsSubmitted = mysqli_query($conn,$sqlSubmitted);
	$rowSubmitted = mysqli_fetch_assoc($rsSubmitted);
	//Completed Table//
	$sqlCompleted = "SELECT * FROM application_main where application_status = 'completed';";
	$rsCompleted = mysqli_query($conn,$sqlSubmitted);
	$rowCompleted = mysqli_fetch_assoc($rsSubmitted);
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
				if($rowPending["application_status"] == "pending")
				{
					$ret = '<table id="pendingTable"><thead><tr>';
					while($row = mysqli_fetch_assoc($rs))
					{
						foreach($row as $key => $item)
						{
							$fixedHeader = str_replace("_"," ",$key);
							$ret .= '<th>'.strtoupper($fixedHeader).'</th>';
						}
						$ret .= '</tr>';
						$ret .= '<tr>';
						foreach($row as $key => $item)
						{
							$ret .= '<td>'.$item.'</td>';
						}
					}
					$ret .= '</tr></thead></table>';
					echo $ret;
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
				if($row["application_status"] == "submitted")
				{
					$ret = '<table><tr>';
					foreach($row as $key => $item)
					{
						$ret .= '<th>'.strtoupper($key).'</th>';
					}
					$ret .= '</tr>';
					$ret .= '<tr>';
					foreach($row as $key => $item)
					{
						$ret .= '<td>'.$item.'</td>';
					}
					$ret .= '</tr></table>';
					echo $ret;
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
				if($row["application_status"] == "completed")
				{
					$ret = '<table><tr>';
					foreach($row as $key => $item)
					{
						$ret .= '<th>'.strtoupper($key).'</th>';
					}
					$ret .= '</tr>';
					$ret .= '<tr>';
					foreach($row as $key => $item)
					{
						$ret .= '<td>'.$item.'</td>';
					}
					$ret .= '</tr></table>';
					echo $ret;
				}
		}
	if($_POST["action"] == "initiate_application")
	{
		$name = $_POST["first_name"].' '.$_POST["last_name"];
		$email = $_POST["primary_email"];
		$subject = 'Test Subject';
		$verification_code = mt_rand(100000, 999999);
		$appId = mysqli_insert_id($conn);
		//Building Prepared Statement for Insert into table application_main//
		$insertName = mysqli_real_escape_string($conn, $name);
		$insertPrimaryPhone = mysqli_real_escape_string($conn, $_POST["primary_phone_number"]);
		$insertPrimaryEmail = mysqli_real_escape_string($conn, $_POST["primary_email"]);
		$insertApplicationStatus = mysqli_real_escape_string($conn, "pending");
		$insertValidationCode = mysqli_real_escape_string($conn, $verification_code);
		
		$sql = "INSERT INTO application_main (name,primary_phone_number,primary_email,application_status,validation_code)
				VALUES (?, ?, ?, ?, ?);";
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $sql))
		{
			echo "SQL ERROR";
		}
		else
		{
			mysqli_stmt_bind_param($stmt, "ssssi", $insertName, $insertPrimaryPhone, $insertPrimaryEmail, $insertApplicationStatus, $insertValidationCode);
			mysqli_stmt_execute($stmt);
		}
		
		//Formatting for PHP Mailer//
		error_log($appId);
		$find = array("[verification_code]","[application_id]");
		$repl = array($verification_code,$appId);
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
			mysqli_stmt_store_result($stmt);
			if(mysqli_stmt_num_rows($stmt) == 1)
			{
				echo "Success";
			}
			else
			{
				echo "Error";
			}
		}
	}
	if($_POST["action"] = "resend_verification_code")
	{
		$sql = "SELECT primary_email FROM application_main where validation_code=?;";
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
			mysqli_stmt_store_result($stmt);
			if(mysqli_stmt_num_rows($stmt) == 1)
			{
				echo "Success";
			}
			else
			{
				echo "Error";
			}
		}
	}
?>