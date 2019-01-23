<?php
date_default_timezone_set("Australia/Sydney"); 
//$csportal_con = mysql_connect('localhost', 'root', '');
$hosts = "10.138.172.158";
$csportal_con = mysqli_connect($hosts, "smallbuildersusr", "@dmint3c_@dr1@n");

if (!$csportal_con) {
	die('Fail to connect' . mysqli_error($csportal_con));
} else {
	$selected_db = mysqli_select_db($csportal_con, 'smallbui_cs_portal');
	if (!$selected_db) {
		die('Fail to use the selected database' . mysqli_error($csportal_con));
	}
}
?>