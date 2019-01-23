<?php
require("class.phpmailer.php");
$user_id = base64_encode($user_id);

 	$link = "HTTPS://www.smallbuilders.com.au/builder/confirmation.php?user_id=".$user_id;
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->Host = "srv1.smallbuilders.com.au";
	$mail->SMTPAuth = true;
	$mail->Username = 'admin@smallbuilders.com.au';
	$mail->Password = '@dr1@n.$1lv@';
	$mail->From='no-reply@contractsspecialist.com.au';
	$mail->FromName='CS Admin';
	$mail->Sender='no-reply@contractsspecialist.com.au';
	//$mail->AddAddress("cs1@contractsspecialist.com.au");
	//$mail->AddBCC("adrian.silva@lophils.com");
	$mail->AddBCC("sav.estidolajr@lophils.com");
	$mail->Subject = 'Notice of Registration on CS Portal';
	$mail->IsHTML(true);
	$mail->Body = "<p style='font-family:Calibri, Arial, Sans Serif'>Dear CS<br/><br/>
	<b>To confirm, this email and all its attachments are classified as SECRET.</b><br/><br/>
	
	
	A new user has registered to access the CS Admin Portal.  Below are the details s/he has provided</b>:<br/><br/>
	
<html>
<head></head>
<body>
<table cellspacing='0' cellpadding='5' border='2' bordercolor='#A3A3A3'>
<tr>
<td>First Name</td>
<td>".$si_firstname."</td>
</tr>

<tr>
<td>Last Name</td>
<td>".$si_lastname."</td>
</tr>

<tr>
<td>Email Address</td>
<td>".$si_emailadd."</td>
</tr>

<tr>
<td>Company</td>
<td>".$si_company."</td>
</tr>

</table>


<br/>
We need you to confirm if the above user is authorised to access the CS Admin Portal.  Once we receive confirmation, we will activate the user&rsquo;s account. <br/><br/>

<a href='".$link."'>Please click this link to approve or disapprove this user&rsquo;s access.</a><br/><br/>

Regards<br/>
CS Admin Services - IT<br/><br/><br/>

	</p>
</body>
</html>
	";
			
	if(!$mail->Send())
	{
		$signup_status = "<span class='lbl_status_f'>Sorry, There's a problem on sending your email. Please send personal email at <a href='mailto:john.delacruz@contractsspecialist.com.au'>john.delacruz@contractsspecialist.com.au</a></span>";
		echo $mail->ErrorInfo;
		
	}
	else
	{
		$signup_status = "<span class='lbl_status_s'>Thank you for registering an account. We will contact you shortly once your account is verified.</span>";
	}
	
?>