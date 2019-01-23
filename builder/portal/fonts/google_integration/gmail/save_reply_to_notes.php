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

    if($getUserInfo && mysqli_num_rows($getUserInfo) != 0){
        $fetchUserInfo = mysqli_fetch_array($getUserInfo);
        $submittedby_name = trim($fetchUserInfo["user_fname"] . ' ' . $fetchUserInfo["user_lname"]);
        $submittedby_email = $fetchUserInfo["email_address"];
    }

    foreach($fetchdata as $arrdata){
        
        //for email
        
        $message_id = mysqli_real_escape_string($theodore_con, trim($arrdata['message_id']));
        $thread_id = mysqli_real_escape_string($theodore_con, trim($arrdata['thread_id']));
        $subject = mysqli_real_escape_string($theodore_con, trim($arrdata['subject']));
        $sent_to = mysqli_real_escape_string($theodore_con, trim($arrdata['sent_to']));
        $sent_from = mysqli_real_escape_string($theodore_con, trim($arrdata['sent_from']));
        $profile_id = mysqli_real_escape_string($theodore_con, trim($arrdata['profile_id']));
        $message_type = mysqli_real_escape_string($theodore_con, trim($arrdata['message_type']));
        //$message_body = mysqli_real_escape_string($theodore_con, trim($arrdata['message']));
        
        $message_body = trim($arrdata["message"]);
        
        //btoa(unescape(encodeURIComponent(JSON.stringify(msg_params))))
        $message_body = base64_encode(mysqli_real_escape_string($theodore_con, urlencode($message_body)));
        
        $date_sent = date('Y-m-d H:i:s');
        $sbdefined_snippet = mysqli_real_escape_string($theodore_con, trim($arrdata['sbdefined_snippet']));
        
        $chkExist = mysqli_query($theodore_con, "SELECT id FROM `gmail_integration` WHERE message_id = '".$message_id."' LIMIT 1");
        
        if(mysqli_num_rows($chkExist) == 0){
            $save = mysqli_query($theodore_con, "INSERT INTO `gmail_integration` (`client_id`, `user_id`, `gmail_profile_id`, `message_id`, `thread_id`, `message_type`, `date_sent`, `subject`, `to`, `from`, `body`, `sbdefined_snippet`, `moved_to_notes`, `moved_by`) VALUES ('".$dec_ci."', '".$dec_ui."', '" . $profile_id . "', '" . $message_id . "', '" . $thread_id . "', '".$message_type."', '". $date_sent ."', '" . $subject . "', '" . $sent_to . "', '" . $sent_from . "', '" . $message_body . "', '".$sbdefined_snippet."', 1, '".$dec_ui."')");
        
            if(!$save){
                on_error(mysqli_error($theodore_con));
            } else {
                $sb_message_id = mysqli_insert_id($theodore_con); // for notes
                $project_name = mysqli_real_escape_string($theodore_con, trim($arrdata['project_name']));
                $status = mysqli_real_escape_string($theodore_con, trim($arrdata['status']));
                $personresponsible = mysqli_real_escape_string($theodore_con, trim($arrdata['personresponsible']));
                $duedate = mysqli_real_escape_string($theodore_con, trim($arrdata['duedate']));
                
                if($duedate != '' && $duedate != '0000-00-00' && $duedate != null){
                    $duedate = date('Y-m-d', strtotime($duedate));    
                }
                
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
        			
            		$get_project_id = mysqli_query($theodore_con, "SELECT id FROM `".$tbl_projects."` WHERE `".$col_client_id."` = '". $dec_ci ."' AND `".$col_project_name."` = '".$project_name."'");
                    
                    if($get_project_id && mysqli_num_rows($get_project_id) != 0){
                        $fetch_project_id = mysqli_fetch_array($get_project_id);
                        $p_id = $fetch_project_id['id'];
                    }
        		}
        
                $discussion = mysqli_real_escape_string($theodore_con, trim($arrdata['discussion']));
                
                if($discussion != ""){
                    $val_discussion = explode(",", $discussion);
                
                    foreach($val_discussion as $key_discussion) {
                        
                        $insert_main = "INSERT INTO `tbl_dashboard_notes` (date_submitted, client_id, submitted_by_id, submitted_by, submitted_by_email, project_id, project_name, due_date, person_responsible, status, discussion, last_dateupdate, notes_type, from_tbl_submission, from_id_submission, gmail_message_id) VALUES ('".$date_sent."',  '".$dec_ci."', '".$dec_ui."', '".$submittedby_name."', '".$submittedby_email."', '".$p_id."', '".$project_name."', '".$duedate."', '".$personresponsible."', '".$status."', '".$key_discussion."', '".$date_sent."', 'email_integration', 'No', 'No', '".$sb_message_id."')";
                        
                        if(mysqli_query($theodore_con, $insert_main)){
                            $note_id = mysqli_insert_id($theodore_con);
        
                            $insert_body = "insert into tbl_notes_comment(_status, _user_id, _client_id, _notes_id, _message, _submitted_by_name, _submitted_date, gmail_message_id, _due_date, _status_id, _show_due_date, _show_status) VALUES ('active', '".$dec_ui."', '".$dec_ci."', '".$note_id."', '', '".$submittedby_name."', '".$date_sent."', '".$sb_message_id."', '".$duedate."', '".$status."', 'Yes', 'Yes')";
                            
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
                   
                   $insert_main = "INSERT INTO `tbl_dashboard_notes` (date_submitted, client_id, submitted_by_id, submitted_by, submitted_by_email, project_id, project_name, due_date, person_responsible, status, discussion, last_dateupdate, notes_type, from_tbl_submission, from_id_submission, gmail_message_id) VALUES ('".$date_sent."',  '".$dec_ci."', '".$dec_ui."', '".$submittedby_name."', '".$submittedby_email."', '".$p_id."', '".$project_name."', '".$duedate."', '".$personresponsible."', '".$status."', '".$key_discussion."', '".$date_sent."', 'email_integration', 'No', 'No', '".$sb_message_id."')";
                        
                    if(mysqli_query($theodore_con, $insert_main)){
                        $note_id = mysqli_insert_id($theodore_con);
    
                        $insert_body = "insert into tbl_notes_comment(_status, _user_id, _client_id, _notes_id, _message, _submitted_by_name, _submitted_date, gmail_message_id, _due_date, _status_id, _show_due_date, _show_status) VALUES ('active', '".$dec_ui."', '".$dec_ci."', '".$note_id."', '', '".$submittedby_name."', '".$date_sent."', '".$sb_message_id."', '".$duedate."', '".$status."', 'Yes', 'Yes')";
                        
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