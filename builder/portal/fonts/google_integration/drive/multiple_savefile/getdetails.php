<?php
if(isset($_POST)){
	include("../../../../../theodore/formbuilder/controller/connection.php");
	$fetchdata = json_decode(file_get_contents('php://input'), true);
	
	$gd_id = '';
	$arr_fileIDs = array();
	$aerr = '';
	
	foreach($fetchdata as $data){
		$gd_id .= "'" . trim($data) . "',";	
	}
	
	$file_id = substr($gd_id, 0, -1);
	
	$query = "SELECT id, name, size FROM `googledrive_files` WHERE id IN (".$file_id.")";
	$execQt = mysqli_query($theodore_con, $query);
		
		if(!$execQt){
			$aerr .= mysqli_error($theodore_con);
		} else {
			if(mysqli_num_rows($execQt)!=0){
				while($fetchgdfiles = mysqli_fetch_array($execQt)){
					extract($fetchgdfiles);
					$arr_gdfile['id'] = $id;
					$arr_gdfile['name'] = $name;
					$arr_gdfile['size'] = $size;
					array_push($arr_fileIDs, $arr_gdfile);
				}
			}	
		}

	if(trim($aerr)=='') 
		echo json_encode($arr_fileIDs);

}
?>
