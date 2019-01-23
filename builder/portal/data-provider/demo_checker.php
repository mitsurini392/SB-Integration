<?php
include("../../../theodore/formbuilder/controller/connection.php");
include("../../controller/connection.php");

$form_name = $_POST["form_name"];
$ci = base64_decode($_POST["ci"]);

// 0 - means this are the first time users after this update

if($form_name=="expense"){
	$updateClient = mysqli_query($csportal_con,"UPDATE cs_clients SET demo_checker='0' where client_id='".$ci."' ");
	$insertDemo = mysqli_query($csportal_con,"INSERT INTO demo_checker(`client_id`,`form_name`)VALUES('".$ci."','".$form_name."')");
}

if($form_name=="swms"){
	$updateClient = mysqli_query($csportal_con,"UPDATE cs_clients SET demo_checker='0' where client_id='".$ci."' ");
	$insertDemo = mysqli_query($csportal_con,"INSERT INTO demo_checker(`client_id`,`form_name`)VALUES('".$ci."','".$form_name."')");
}

if($form_name=="timesheet"){
	$updateClient = mysqli_query($csportal_con,"UPDATE cs_clients SET demo_checker='0' where client_id='".$ci."' ");
	$insertDemo = mysqli_query($csportal_con,"INSERT INTO demo_checker(`client_id`,`form_name`)VALUES('".$ci."','".$form_name."')");
}

if($form_name=="quote"){
	$updateClient = mysqli_query($csportal_con,"UPDATE cs_clients SET demo_checker='0' where client_id='".$ci."' ");
	$insertDemo = mysqli_query($csportal_con,"INSERT INTO demo_checker(`client_id`,`form_name`)VALUES('".$ci."','".$form_name."')");
}

?>