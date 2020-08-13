<?php
	use PHPMailer\PHPMailer\PHPMailer;
	
	$conn = mysqli_connect('localhost', 'damien', 'Oimadi*1', 'application_management');
	$sql = "SELECT * FROM application_main;";
	$sqlHeaders = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'application_main';";
	$rs = mysqli_query($conn,$sql);
	$rsHeaders = mysqli_query($conn,$sqlHeaders);

	if($_POST["action"] == "pending_table")
	{
		if(mysqli_num_rows($rs) == 0)
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
		while($row = mysqli_fetch_assoc($rs))
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
	if($_POST["action"] == "submitted_table")
	{
		if(mysqli_num_rows($rs) == 0)
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
		while($row = mysqli_fetch_assoc($rs))
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
		if(mysqli_num_rows($rs) == 0)
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
		while($row = mysqli_fetch_assoc($rs))
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
		$body = file_get_contents("initiation_email.html");

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
	if($_POST["action"] == "send_validation_code")
	{
		$to = "3238069526@vtext.com";
		$from = "Damien Test";
		$message = "This is a text message\nNew line...";
		$headers = "From: $from\n";
		mail($to, '', $message, $headers);
		return;
	}
?>