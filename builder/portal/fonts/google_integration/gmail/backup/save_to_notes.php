<?php

include("../../../controller/connection.php");
include("../../../../theodore/formbuilder/controller/connection.php");


$fetchdata = json_decode(file_get_contents('php://input'), true);
$ui = $_GET["ui"];
$ci = $_GET["ci"];
$dec_ui = base64_decode($ui);
$dec_ci = base64_decode($ci);
$result = array();
$status = false;

$submittedby_name = "";
$submittedby_email = "";
$getUserInfo = mysqli_query($csportal_con, "SELECT user_fname, user_lname, email_address FROM cs_users WHERE user_id = '".$dec_ui."' LIMIT 1");

if($getUserInfo && mysqli_num_rows($getUserInfo) != 0){
    $fetchUserInfo = mysqli_fetch_array($getUserInfo);
    $submittedby_name = trim($fetchUserInfo["user_fname"] . ' ' . $fetchUserInfo["user_lname"]);
    $submittedby_email = $fetchUserInfo["email_address"];
}


    foreach($fetchdata as $arrdata){
        
        $sb_message_id = mysqli_real_escape_string($theodore_con, trim($arrdata['message_id']));
        $projectname = mysqli_real_escape_string($theodore_con, trim($arrdata['projectname']));
        $status = mysqli_real_escape_string($theodore_con, trim($arrdata['status']));
        $sbsnippet = mysqli_real_escape_string($theodore_con, trim($arrdata['sbsnippet']));
        $personresponsible = mysqli_real_escape_string($theodore_con, trim($arrdata['personresponsible']));
        $duedate = mysqli_real_escape_string($theodore_con, trim($arrdata['duedate']));
        
        
        if($duedate != ''){
            $duedate = date('Y-m-d', strtotime($duedate));    
        }
        
        $user_id = 0;
        $client_id = 0;
        $profile_id = "";
        $msg_thread_id = "";
        $msg_message_id = "";
        $msg_body = "";
        $msg_subject = "";
        $msg_to = "";
        $msg_from = "";
        $msg_date = "";
        $msg_microtime = "";
        $msg_snippet = "";
    
        $getEmailData = mysqli_query($theodore_con, "SELECT * FROM `gmail_integration` WHERE id = '".$sb_message_id."' LIMIT 1");
        
        if($getEmailData){
            while($fetchEmailData = mysqli_fetch_array($getEmailData)){
                
                extract($fetchEmailData);
                    
                $user_id = mysqli_real_escape_string($theodore_con, $user_id);
                $client_id = mysqli_real_escape_string($theodore_con, $client_id);
                $profile_id = mysqli_real_escape_string($theodore_con, $gmail_profile_id);
                $msg_thread_id = mysqli_real_escape_string($theodore_con, $thread_id);
                $msg_message_id = mysqli_real_escape_string($theodore_con, $message_id);
                
                //$msg_body = mysqli_real_escape_string($theodore_con, trim(strip_tags(decodeBody($body)))); //removes html characters
                $msg_body = trim(html_entity_decode(decodeBody($body)));
                $msg_body = mysqli_real_escape_string($theodore_con, $msg_body);
                
                $msg_subject = mysqli_real_escape_string($theodore_con, $subject);
                $msg_to = mysqli_real_escape_string($theodore_con, $to);
                $msg_from_email = mysqli_real_escape_string($theodore_con, trim(get_string_between($from, '<', '>')));
                $msg_from_name = mysqli_real_escape_string($theodore_con, trim(delete_all_between('<', '>', $from)));
                
                //$msg_date = $date_utc;
                $date_mc = ($date_mc/1000);
                $date_submitted = date('Y-m-d H:i:s', $date_mc);
                $last_date_updated = date('Y-m-d H:i:s');
                $msg_snippet = mysqli_real_escape_string($theodore_con, $snippet);
                
                $projectname = mysqli_real_escape_string($theodore_con, trim($projectname));
                $status = mysqli_real_escape_string($theodore_con, trim($status));
                $personresponsible = mysqli_real_escape_string($theodore_con, trim($personresponsible));
                $duedate = mysqli_real_escape_string($theodore_con, trim($duedate));
                
                $insert_main = "INSERT INTO `tbl_dashboard_notes` (date_submitted, client_id, submitted_by_id, submitted_by, submitted_by_email, project_name, due_date, person_responsible, status, last_dateupdate, notes_type, from_tbl_submission, from_id_submission, gmail_message_id) VALUES ('".$date_submitted."',  '".$client_id."', '".$user_id."', '".$submittedby_name."', '".$submittedby_email."', '".$projectname."', '".$duedate."', '".$personresponsible."', '".$status."', '".$last_date_updated."', 'email_integration', 'No', 'No', '".$sb_message_id."')";
                
                if(mysqli_query($theodore_con, $insert_main)){
                    
                    $note_id = mysqli_insert_id($theodore_con);

                    $insert_body = "insert into tbl_notes_comment(_status, _user_id, _client_id, _notes_id, _message, _submitted_by_name, _submitted_date, gmail_message_id) VALUES ('active', '".$user_id."', '".$client_id."', '".$note_id."', '', '".$submittedby_name."', '".$date_submitted."', '".$sb_message_id."')";
                    
                    if(mysqli_query($theodore_con, $insert_body)){
                        
                        $status = true;
                        
                    } else {
                        on_error(mysqli_error($theodore_con));
                    }
                    
                } else {
                    on_error(mysqli_error($theodore_con));
                }
            }
            
        } else {
            on_error(mysqli_error($theodore_con));
        }
    }
    
    if($status){
        $out['result'] = true;
        
    } else {
        $out['result'] = false;
    }

    array_push($result, $out);
    echo json_encode($result);    

function on_error($a){
    $result = array();
    $out['result'] = false;
    $out['message'] = $a;
    array_push($result, $out);
    echo json_encode($result);
    
}

function get_string_between($string, $start, $end){
    $string = " ".$string;
    $ini = strpos($string,$start);
    if ($ini == 0) return "";
    $ini += strlen($start);   
    $len = strpos($string,$end,$ini) - $ini;
    return substr($string,$ini,$len);
}

function delete_all_between($beginning, $end, $string) {
  $beginningPos = strpos($string, $beginning);
  $endPos = strpos($string, $end);
  if ($beginningPos === false || $endPos === false) {
    return $string;
  }

  $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

  return str_replace($textToDelete, '', $string);
}

function decodeBody($body) {
    $rawData = $body;
    $sanitizedData = strtr($rawData,'-_', '+/');
    $decodedMessage = base64_decode($sanitizedData);
    if(!$decodedMessage){
        $decodedMessage = FALSE;
    }
    return $decodedMessage;
}
?>