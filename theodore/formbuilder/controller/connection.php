<?php
date_default_timezone_set("Australia/Sydney");
$theodore_con = mysqli_connect('localhost', 'root', 'mysql');

if (!$theodore_con) {
	die('Fail to connect' . mysqli_error($theodore_con));
} else {
	$selected_db = mysqli_select_db($theodore_con, 'integration_theodore');
	if (!$selected_db) {
		die('Fail to use the selected database' . mysqli_error($theodore_con));
	}
}
?>