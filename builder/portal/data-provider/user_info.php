<?php
session_save_path();
session_start();
//$dec_ui = base64_decode($_GET["ui"]);

if($_GET['ui'] != "" && $_GET['ci'] != ""){
	$ui = $_GET['ui'];
	$ci = $_GET['ci'];
	$dec_ui = base64_decode($ui);
	$dec_ci = base64_decode($ci);
} else {
	$state = $_GET['state'];
	$state = str_replace('{','',$state);
	$state = str_replace('}','',$state);
	$exp_state = explode('-', $state);
	$ui = $exp_state[0];
	$ci = $exp_state[1];
	$dec_ui = base64_decode($ui);
	$dec_ci = base64_decode($ci);
}

$_user_companylogo = "/logo.png";
$_user_pic = "/default_user.png";
$_user_firstname = "";
$_user_lastname = "";
$_user_email = "";
$_contact_number = "";
$_user_clienttype = "";
$_user_is_owner = "";

$getUserInfo = mysqli_query($csportal_con, "SELECT user_fname, user_lname, email_address, contact_number, client_type, is_owner, user_photo, client_id, company_logo FROM `cs_users` WHERE user_id = '".$dec_ui."' and user_status='approved'");


if(mysqli_num_rows($getUserInfo)==0) {
    header('Location: ../index.php?stat=invalidaccess&nonregistered=1&src=user_info');
} else if($_SESSION['islogged'] != "yes"){
    //header('Location: ../index.php?stat=invalidaccess&nosession=1&src=user_info'); 
} else {
        $fetchUserInfo = mysqli_fetch_array($getUserInfo);
        $_user_companylogo = $fetchUserInfo["company_logo"];
        $_user_pic = $fetchUserInfo["user_photo"];
        $_user_firstname = stripslashes($fetchUserInfo["user_fname"]);
        $_user_lastname = stripslashes($fetchUserInfo["user_lname"]);
        $_user_email = stripslashes($fetchUserInfo["email_address"]);
        $_user_clienttype = stripslashes($fetchUserInfo["client_type"]);
        $_user_is_owner = stripslashes($fetchUserInfo["is_owner"]);
        $_contact_number = stripslashes($fetchUserInfo["contact_number"]);

    }

?>

