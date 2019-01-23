<?php
include("../../../theodore/formbuilder/controller/connection.php");
include("../../controller/connection.php");

$ipaddress = "";
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
	$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
	$ipaddress = $_SERVER['REMOTE_ADDR'];
}

$userid = base64_decode($_REQUEST['userid']);
$onboarding = $_REQUEST['onboarding'];
$phase = $_REQUEST['phase'];
$page = $_REQUEST['page'];
$step = $_REQUEST['step'];
$action = $_REQUEST['action'];

$query = "
	INSERT INTO onboarding_funnel (user_id, onboarding, phase, page, step, action, ip_address)
	VALUES ('".$userid."', '".$onboarding."', '".$phase."', '".$page."', '".$step."', '".$action."', '".$ipaddress."')";
mysqli_query($theodore_con, $query);

echo json_encode(true);
?>