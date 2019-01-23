<?php
if(isset($_POST)){
	include("../../../../../theodore/formbuilder/controller/connection.php");
	$fetchdata = json_decode(file_get_contents('php://input'), true);
	
	$arr_fileIDs = array();
	$ui = $_GET['ui'];
	$ui = base64_decode($ui);
	$aerr = '';
	
	foreach($fetchdata as $data){
		$f_id = mysqli_real_escape_string($theodore_con, $data['f_id']);
		$f_name = mysqli_real_escape_string($theodore_con, $data['f_name']);
		$fileName = date('ymdHis') . rand() .'gd' . preg_replace('/[^a-zA-Z0-9_.]/', '', $f_name);
		$f_type = mysqli_real_escape_string($theodore_con, $data['f_type']);
		$f_size = mysqli_real_escape_string($theodore_con, $data['f_size']);
		$f_url = mysqli_real_escape_string($theodore_con, $data['f_url']);
		$f_token = mysqli_real_escape_string($theodore_con, $data['f_token']);
		
		$query = "INSERT INTO `googledrive_files` (name, size, type, token, file_id, url, modified_by) VALUES ('".$f_name."', '".$f_size."', '".$f_type."', '".$f_token."', '".$f_id."', '".$f_url."', '".$ui."')";
		
		$execQt = mysqli_query($theodore_con, $query);
		
		if(!$execQt){
			$aerr .= mysqli_error($theodore_con);
		} else {
			array_push($arr_fileIDs, mysqli_insert_id($theodore_con));
			}
		
		/**
		switch ($f_type) {
			case "text/plain": $fileExt = '.txt'; break;
			case "text/csv": $fileExt = '.csv'; break;
			case "application/pdf": $fileExt = '.pdf'; break;
			case "application/vnd.openxmlformats-officedocument.wordprocessingml.document": $fileExt .= '.docx'; break;
			case "application/msword": $fileExt = '.doc'; break;    
			case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet": $fileExt .= '.xlsx'; break;
			case "application/vnd.ms-excel": $fileExt = '.xls'; break;
			case "application/vnd.ms-powerpoint": $fileExt = '.ppt'; break;
			case "application/vnd.openxmlformats-officedocument.presentationml.presentation": $fileExt = '.pptx'; break;
			case "image/jpeg": $fileExt = '.jpg'; break;
			case "image/png": $fileExt = '.png'; break;
			default: $fileExt = ''; break;
		}

		if(trim($fileExt)!=""){
			$getUrl = 'https://www.googleapis.com/drive/v3/files/' . $f_id . '?alt=media';
			$authHeader = 'Authorization: Bearer ' . $f_token;
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $getUrl);
			curl_setopt($ch, CURLOPT_HTTPHEADER, [$authHeader]);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
			$result = curl_exec($ch);
			$error = curl_error($ch);

			curl_close($ch);
			
			file_put_contents('../../../../uploaded_files/downloads/' . $fileName, $result);
			
			if($error != "")
				$aerr .= $error;
		}
		*/
	}

	if(trim($aerr)=='') 
		echo json_encode($arr_fileIDs);
		
}
?>
