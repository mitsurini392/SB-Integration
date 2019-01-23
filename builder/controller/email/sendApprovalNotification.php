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
	$mail->FromName='Small Builders';	
	$mail->Sender='no-reply@contractsspecialist.com.au';
	$mail->AddAddress($user_email);
	$mail->AddBCC("cs1@contractsspecialist.com.au");
	$mail->AddBCC("adrian.silva@lophils.com");
	$mail->AddBCC("herder.caperina@lophils.com");
	$mail->AddBCC("sav.estidolajr@lophils.com");
	
	$mail->Subject = 'Successful Registration on the Small Builders Portal';
	$mail->IsHTML(true);
	$mail->Body = "<p style='font-family:Calibri, Arial, Sans Serif'>Dear ".$user_fname."<br/><br/>
	<b>This is an automated email.  Please do not reply directly to this email.</b><br/><br/>
 	Thank you for registering with the Small Builders Portal!<br/><br/><br/>

	<b>Information</b><br/><br/>

		You can access the Small Builders Portal through the following link:<br/><br/>
	
	<a href='https://www.smallbuilders.com.au/builder/'>Small Builders Portal</a><br/><br/>
	
	Your log-in details are:<br/><br/>
	
	Username: 	<b>".$user_email."</b><br/>
	Password:	<b>".$user_password."</b><br/><br/><br/>
	
	For any concerns, contact John Dela Cruz on 0414 325 080.<br/><br/><br/>
	

	Regards<br/>
 	Small Builders<br/><br/><br/>
	</p>
	";
			
	if(!$mail->Send())
	{
		echo "<script type='text/javascript'> alert('Unexpected error encountered while sending notification. Please send a personal email to ".$user_email."'); window.location = './';</script>";
	}
	
	else
	{
		if(!$exec)
			{
				echo "<script type='text/javascript'> alert('Unexpected error encountered.'); </script>";
			}
			
			else
			{
				$new_user=mysql_insert_id();
					
					if($clientType=="subcontractor")
					{
						$addUserSpecific = $as_create->add('cs_userspecific_forms', array("$new_user","7"));
						$addUserSpecific = $as_create->add('cs_userspecific_forms', array("$new_user","36"));
					}
					
					else if($clientType=="supplier")
					{
						$addUserSpecific = $as_create->add('cs_userspecific_forms', array("$new_user","9"));
						$addUserSpecific = $as_create->add('cs_userspecific_forms', array("$new_user","37"));
					}
					else
					{
						$addUserSpecific=true;
					}
					
						if(!$addUserSpecific)
						{
							echo "<script type='text/javascript'> alert('Unexpected error encountered.'); </script>";
						}
						else
						{
							echo "<script type='text/javascript'> 
							alert('User Details was successfully added.'); 
							window.location = './index.php?ui=".base64_encode($ui)."'; 
							</script>";
						}
			}		
	}
?>