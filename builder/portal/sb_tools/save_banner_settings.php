<?php
	include("../../../theodore/formbuilder/controller/connection.php");
	include("../../controller/connection.php");

	$banner_stat= $_POST["banner_stat"];
	$user_id= $_POST["user_id"];

	$select = mysqli_query($theodore_con,"SELECT * FROM trial_banner_settings WHERE user_id = '".$user_id."'");
	if(mysqli_num_rows($select) == 0) {
		mysqli_query($theodore_con, "INSERT INTO trial_banner_settings (user_id, banner_stat)values('".$user_id."', '".$banner_stat."')");
	}else{
		mysqli_query($theodore_con, "UPDATE trial_banner_settings SET banner_stat = '".$banner_stat."' WHERE user_id = '". $user_id ."'");
	}
?>