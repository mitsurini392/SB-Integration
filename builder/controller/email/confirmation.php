<?php
require("class.phpmailer.php");

	$content = ob_get_clean(); 			
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->Host = "srv1.smallbuilders.com.au";
	$mail->SMTPAuth = true;
	$mail->Username = 'admin@smallbuilders.com.au';
	$mail->Password = '@dr1@n.$1lv@';
	$mail->From='no-reply@contractsspecialist.com.au';
	$mail->FromName='CS Admin';	
	$mail->Sender='no-reply@contractsspecialist.com.au';
	$mail->AddAddress($user_email);
	$mail->AddAddress("cs1@contractsspecialist.com.au");
	$mail->AddBCC("adrian.silva@lophils.com");
	$mail->AddBCC("herder.caperina@lophils.com");
	$mail->AddBCC("sav.estidolajr@lophils.com");
	
	$mail->Subject = 'Successful Registration';
	$mail->IsHTML(true);
	$mail->Body = "<p style='font-family:Calibri, Arial, Sans Serif'>Dear ".$user_fname."<br/><br/>
	<b>This is an automated email. Please do not reply directly to this email.</b><br/><br/>
 	Thank you for registering with the CS Admin Portal!<br/><br/><br/>

	<b>Information</b><br/><br/>

	Great news!<br/><br/>
	You can access the CS Admin Portal through the following link:<br/><br/>
	
	<a href='https://www.smallbuilders.com.au/builder/'>Small Builders Portal</a><br/><br/>
	
	Your log-in details are:<br/><br/>
	
	Username: 	<b>".$user_email."</b><br/>
	Password:	<b>".$user_password."</b><br/><br/><br/>
		
	For any concerns, contact John Dela Cruz on 0414 325 080.<br/><br/><br/>
	
	Regards<br/>
	CS Admin<br/><br/><br/>
	</p>
	";
			
	if(!$mail->Send())
	{
		echo "<script type='text/javascript'> alert('Unexpected error encountered while sending notification. Please send a personal email to ".$user_email."'); window.location = './';</script>";
	}	
	else
	{
	echo "<script type='text/javascript'> alert('Successfully Registered!'); window.location = './';</script>";
	}
?>