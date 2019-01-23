<?php
$upload_path='image_upload';
$fileId = $_POST['file_id'];
$fileName = $_POST['file_name'];
$fileType = $_POST['file_type'];
$oAuthToken = $_POST['token'];
$fileExt = "";

switch ($fileType) {
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
		$getUrl = 'https://www.googleapis.com/drive/v3/files/' . $fileId . '?alt=media';
		$authHeader = 'Authorization: Bearer ' . $oAuthToken;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $getUrl);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [$authHeader]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$result = curl_exec($ch);
		$error = curl_error($ch);

		curl_close($ch);
		$fname = date('YmdHis') . 'gd' . preg_replace('/[^a-zA-Z0-9_.]/', '', $fileName) . $fileExt;
		file_put_contents('../../../../uploaded_files/downloads/' . $fname, $result);
		
		if($error == ""){
			echo $fname;
		}
	}

?>