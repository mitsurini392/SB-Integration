<?php

include("../../../../../theodore/formbuilder/controller/connection.php");
	$note_id = $_GET['id'];
	$result = array();
	$persons = array();
	
	$getNoteDetails = mysqli_query($theodore_con, "SELECT submitted_by, project_name, notes, due_date, start_time, end_time, person_responsible, location FROM `tbl_dashboard_notes` where id = '".$note_id."'");
	if(mysqli_num_rows($getNoteDetails)!=0){
			
		$fetchNoteDetails = mysqli_fetch_array($getNoteDetails);
		$message = "";
		$getMessages= mysqli_query($theodore_con, "SELECT _message FROM `tbl_notes_comment` WHERE _notes_id = '".$note_id."'");

		if(mysqli_num_rows($getMessages) != 0){
			while($fetchMessages = mysqli_fetch_array($getMessages)){
			$message .= $fetchMessages["_message"];
			}
		}
		
		$row_array['notes'] = stripslashes($message);
		$row_array['submitted_by'] = stripslashes($fetchNoteDetails["submitted_by"]);
		$row_array['project_name'] = stripslashes($fetchNoteDetails["project_name"]);
		$row_array['location'] = stripslashes($fetchNoteDetails["location"]);
		$row_array['due_date'] = date("Y-m-d", strtotime($fetchNoteDetails["due_date"]));
		$row_array['start_time'] = date("H:i:s", strtotime($fetchNoteDetails["start_time"]));
		$row_array['end_time'] = date("H:i:s", strtotime($fetchNoteDetails["end_time"]));;
		$person_responsible = explode(",", stripslashes($fetchNoteDetails["person_responsible"]));

		foreach($person_responsible as $val){
			$row_res["email"] = extract_email_address($val);
			array_push($persons, $row_res);
		}
		
		$row_array['person_responsible'] = $persons;
		array_push($result, $row_array);
		echo json_encode($result);
	}

function get_string_between($string, $start, $end){
	$string = ' ' . $string;
	$ini = strpos($string, $start);
	if ($ini == 0) return '';
	$ini += strlen($start);
	$len = strpos($string, $end, $ini) - $ini;
	return substr($string, $ini, $len);
}

function extract_email_address ($string) {
    foreach(preg_split('/\s/', $string) as $token) {
        $email = filter_var(filter_var($token, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
        if ($email !== false) {
            $emails[] = $email;
        }
    }
    return $emails;
}
?>