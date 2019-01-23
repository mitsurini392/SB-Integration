<?php

include("../../../controller/connection.php");
include("../../../../theodore/formbuilder/controller/connection.php");

$fetchdata = json_decode(file_get_contents('php://input'), true);
$ui = $_GET["ui"];
$ci = $_GET["ci"];
$dec_ui = base64_decode($ui);
$dec_ci = base64_decode($ci);
$result = array();
$qstatus = false;

$submittedby_name = "";
$submittedby_email = "";
$getUserInfo = mysqli_query($csportal_con, "SELECT user_fname, user_lname, email_address FROM cs_users WHERE user_id = '".$dec_ui."' LIMIT 1");

if($getUserInfo && mysqli_num_rows($getUserInfo) != 0) {
    $fetchUserInfo = mysqli_fetch_array($getUserInfo);
    $submittedby_name = trim($fetchUserInfo["user_fname"] . ' ' . $fetchUserInfo["user_lname"]);
    $submittedby_email = $fetchUserInfo["email_address"];
}

    foreach($fetchdata as $arrdata) {
        
        $sb_message_id = mysqli_real_escape_string($theodore_con, trim($arrdata['message_id']));
        $projectname = $arrdata['projectname'];
        $status = $arrdata['status'];
        $personresponsible = $arrdata['personresponsible'];
        $duedate = $arrdata['duedate'];
        $discussion = $arrdata['discussion'];
        $newDiscussion = $arrdata['newDiscussion'];
        //$sbsnippet = mysqli_real_escape_string($theodore_con, trim($arrdata['sbsnippet']));
        
        if($duedate != '' && $duedate != '0000-00-00' && $duedate != null){
            $duedate = date('d-m-Y', strtotime($duedate));    
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
        
        // get current projects table
        $select = mysqli_query($theodore_con, "SELECT tbl_name, project_address as pr_addr, project_name as pr_name, client_id FROM current_projects_db");

        if(mysqli_num_rows($select)!=0) {
            while($extractSelect = mysqli_fetch_array($select)) {
                extract($extractSelect);
                $tbl_projects = $tbl_name;
                $col_project_addr = $pr_addr;
                $col_project_name = $pr_name;
                $col_client_id = $client_id;
            }
            
            // get project ID
            
            $get_project_id = mysqli_query($theodore_con, "SELECT id FROM `".$tbl_projects."` WHERE `".$col_client_id."` = '". $dec_ci ."' AND `".$col_project_name."` = '".$projectname."'");
            
            if($get_project_id && mysqli_num_rows($get_project_id) != 0) {
                $fetch_project_id = mysqli_fetch_array($get_project_id);
                $p_id = $fetch_project_id['id'];
            }
        }
    
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
                //$date_mc = ($date_mc/1000);
                //$date_submitted = date('Y-m-d H:i:s', $date_mc);
                $last_date_updated = date('Y-m-d H:i:s');

                $sbdefined_snippet = mysqli_real_escape_string($theodore_con, $sbdefined_snippet);
                $msg_snippet = mysqli_real_escape_string($theodore_con, $snippet);
                $projectname = mysqli_real_escape_string($theodore_con, trim($projectname));
                $status = mysqli_real_escape_string($theodore_con, trim($status));
                $personresponsible = mysqli_real_escape_string($theodore_con, trim($personresponsible));
                $duedate = mysqli_real_escape_string($theodore_con, trim($duedate));
                $discussion = mysqli_real_escape_string($theodore_con, trim($discussion));

                //start - insert new discussion
                $valNewIdDiscusions = "";
                foreach($newDiscussion as $eachNewDiscussion) {
                    $insertNewDiscussionQuery = "insert into tbl_notes_discussion (status, client_id, project_id, name, date_submitted, submitted_by, submitted_id, edited_by, edited_date) values ('active', '".$dec_ci."', '".$p_id."', '".addslashes($eachNewDiscussion['name'])."', '".$last_date_updated."', '".$submittedby_name."', '".$dec_ui."', '', '')";
                    if(mysqli_query($theodore_con, $insertNewDiscussionQuery)) {
                        $discussions_id = mysqli_insert_id($theodore_con);
                        $valNewIdDiscusions .= $discussions_id.",";
                    }
                }
                if($discussion != "") {
                    $discussion = $valNewIdDiscusions.$discussion;
                } else {
                    $discussion = substr($valNewIdDiscusions, 0, -1);
                }
                //end - insert new discussion
                
                if($discussion != ""){
                    $val_discussion = explode(",", $discussion);
                
                    foreach($val_discussion as $key_discussion) {
                        $insert_main = "INSERT INTO `tbl_dashboard_notes` (date_submitted, client_id, submitted_by_id, submitted_by, submitted_by_email, project_id, project_name, due_date, person_responsible, status, discussion, last_dateupdate, notes_type, from_tbl_submission, from_id_submission, gmail_message_id, snippet) VALUES ('".$last_date_updated."',  '".$dec_ci."', '".$dec_ui."', '".$submittedby_name."', '".$submittedby_email."', '".$p_id."', '".$projectname."', '".$duedate."', '".$personresponsible."', '".$status."', '".$key_discussion."', '".$last_date_updated."', 'email_integration', 'No', 'No', '".$sb_message_id."', '".$sbdefined_snippet."')";
                
                        if(mysqli_query($theodore_con, $insert_main)){
                            $note_id = mysqli_insert_id($theodore_con);
        
                            $insert_body = "insert into tbl_notes_comment(_status, _user_id, _client_id, _notes_id, _message, _submitted_by_name, _submitted_date, gmail_message_id, _due_date, _status_id, _show_due_date, _show_status) VALUES ('active', '".$dec_ui."', '".$dec_ci."', '".$note_id."', '', '".$submittedby_name."', '".$last_date_updated."', '".$sb_message_id."', '".$duedate."', '".$status."', 'Yes', 'Yes')";
                            
                            if(mysqli_query($theodore_con, $insert_body)){
                                $qstatus = true;
                            } else {
                                on_error(mysqli_error($theodore_con));
                            }
                            
                        } else {
                            on_error(mysqli_error($theodore_con));
                        }
                    }
                } else {
                    $insert_main = "INSERT INTO `tbl_dashboard_notes` (date_submitted, client_id, submitted_by_id, submitted_by, submitted_by_email, project_id, project_name, due_date, person_responsible, status, discussion, last_dateupdate, notes_type, from_tbl_submission, from_id_submission, gmail_message_id, snippet) VALUES ('".$last_date_updated."',  '".$dec_ci."', '".$dec_ui."', '".$submittedby_name."', '".$submittedby_email."', '".$p_id."', '".$projectname."', '".$duedate."', '".$personresponsible."', '".$status."', '".$key_discussion."', '".$last_date_updated."', 'email_integration', 'No', 'No', '".$sb_message_id."', '".$sbdefined_snippet."')";
                
                    if(mysqli_query($theodore_con, $insert_main)){
                        $note_id = mysqli_insert_id($theodore_con);
    
                        $insert_body = "insert into tbl_notes_comment(_status, _user_id, _client_id, _notes_id, _message, _submitted_by_name, _submitted_date, gmail_message_id, _due_date, _status_id, _show_due_date, _show_status) VALUES ('active', '".$dec_ui."', '".$dec_ci."', '".$note_id."', '', '".$submittedby_name."', '".$last_date_updated."', '".$sb_message_id."', '".$duedate."', '".$status."', 'Yes', 'Yes')";
                        
                        if(mysqli_query($theodore_con, $insert_body)){
                            $qstatus = true;
                        } else {
                            on_error(mysqli_error($theodore_con));
                        }
                        
                    } else {
                        on_error(mysqli_error($theodore_con));
                    }
                }
                
                
            }
            
        } else {
            on_error(mysqli_error($theodore_con));
        }
    }
    
    if($qstatus){
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