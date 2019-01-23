<?php
date_default_timezone_set("Australia/Sydney"); 
$csportal_con = mysqli_connect('localhost', 'root', 'mysql');

if (!$csportal_con) {
	die('Fail to connect' . mysqli_error($csportal_con));
} else {
	$selected_db = mysqli_select_db($csportal_con, 'integration_cs_portal');
	if (!$selected_db) {
		die('Fail to use the selected database' . mysqli_error($csportal_con));
	}
}
?>