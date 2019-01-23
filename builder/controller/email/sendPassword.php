<?php
include("../theodore/formbuilder/controller/phpMailerClass.php");

	$content = ob_get_clean(); 			
    //$email = new PHPMailer();
	$email->IsHTML(true);
	$email ->From='no-reply@smallbuilders.com.au';
	$email ->FromName='Small Builders';	
	$email ->Sender='no-reply@smallbuilders.com.au';
	$email ->AddAddress("$fp_email");
	$email->AddReplyTo('cs1@contractsspecialist.com.au');
	$email ->AddBCC("cs1@contractsspecialist.com.au");
	$email ->AddBCC("adrian.silva@lophils.com");
	$email ->AddBCC("sav.estidolajr@lophils.com");
	
	$email ->Subject = 'Small Builders Portal Password Recovery';
	$email ->IsHTML(true);
	$email ->Body = "
	        <b><i>Please do not reply. This is an automated email.</i></b><br/><br/>
	        <p style='font-family:Calibri, Arial, Sans Serif'>Hi ".$firstname."<br/><br/><br/>
		<b>Reference</b><br/><br/>
		You informed us that you have forgotten your password.<br/><br/><br/>
		<b>Information</b><br/>
		Your log-in details for the Small Builders Portal are as follows:<br/><br/>
		Username: <b>".$fp_email."</b><br/>
		Password: <b>".$password."</b><br/><br/><br/>
		<b>Next Steps</b><br/><br/>
		For any concerns, contact John Dela Cruz on 0414 325 080.<br/><br/><br/><br/>
		Regards<br/>
		<b>Small Builders | Building Software</b><br/>
	    	<a href='http://www.smallbuilders.com.au/'>http://www.smallbuilders.com.au/</a>
		<br/>
		</p>";
			
	if(!$email->Send()){
		$fpassword_status = "<span class='lbl_status_f'>Sorry, There is a problem on retrieving your password. Please send personal email at <a href='mailto:john.delacruz@contractsspecialist.com.au'>john.delacruz@contractsspecialist.com.au</a></span>";
	} else {
		$fpassword_status = "<span class='lbl_status_s'>Thank you. You will receive your password through your email shortly. </span>";	
		}			
?>