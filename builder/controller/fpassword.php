<?php
ob_start();
//include "../theodore/formbuilder/controller/phpMailerClass.php";
include "../theodore/formbuilder/libs/errhandling/errorsender.php";
require("connection.php");
//include("class_lib/as_create.php");

$fpassword_status = "";

if(isset($_POST['btnfPassword'])){
	$fp_email = addslashes($_POST['fp_email']);
	$check_user_query = "SELECT * FROM cs_users WHERE email_address='$fp_email' && user_status='approved'";
	$check_user=mysqli_query($csportal_con, $check_user_query);
	///// start - code for error notification /////
    getdata_and_senderroremail("Check if email is existing",$csportal_con,$check_user_query,"Forgot Password Page","N/A","N/A");
    /////  end - code for error notification  /////

	if (mysqli_num_rows($check_user)==0) {
		$fpassword_status = "<span class='lbl_status_f'>Your email address is not recognised by our system.<br/>Please contact John Dela Cruz on 0414 325 080. </span><br/>";
	} else {
		$fetch_user = mysqli_fetch_array($check_user);
		$password = $fetch_user["user_password"];
		$firstname = $fetch_user["user_fname"];
		include("email/sendPassword.php");
		}
	}
ob_flush();
?>