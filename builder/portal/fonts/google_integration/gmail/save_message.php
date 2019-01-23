<?php

function on_error($a){
    $result = array();
    $out['result'] = false;
    $out['message'] = $a;
    array_push($result, $out);
    die(json_encode($result));
    
}

include("../../../../theodore/formbuilder/controller/connection.php");

$fetchdata = json_decode(file_get_contents('php://input'), true);
$ui = $_GET["ui"];
$ci = $_GET["ci"];
$dec_ui = base64_decode($ui);
$dec_ci = base64_decode($ci);
$result = array();

    foreach($fetchdata as $msg){
        
        $profile_id = mysqli_real_escape_string($theodore_con, trim($msg["profile_id"]));
        $msg_thread_id = mysqli_real_escape_string($theodore_con, trim($msg["msg_thread_id"]));
        $msg_message_id = mysqli_real_escape_string($theodore_con, trim($msg["msg_message_id"]));
        $msg_raw_message_id = mysqli_real_escape_string($theodore_con, trim($msg["msg_raw_message_id"]));
        $msg_body = trim($msg["msg_body"]);
        
        //btoa(unescape(encodeURIComponent(JSON.stringify(msg_params))))
        $msg_body = base64_encode(mysqli_real_escape_string($theodore_con, urlencode($msg_body)));
        
        
        $msg_body_mime = mysqli_real_escape_string($theodore_con, trim($msg["msg_body_mime"]));
        $msg_subject = mysqli_real_escape_string($theodore_con, trim($msg["msg_subject"]));
        $msg_to = mysqli_real_escape_string($theodore_con, trim($msg["msg_to"]));
        $msg_from = mysqli_real_escape_string($theodore_con, trim($msg["msg_from"]));
        $msg_reply_to = mysqli_real_escape_string($theodore_con, trim($msg["msg_reply_to"]));
        $msg_in_reply_to = mysqli_real_escape_string($theodore_con, trim($msg["msg_in_reply_to"]));
        $msg_date = mysqli_real_escape_string($theodore_con, trim($msg["msg_date"]));
        $msg_microtime = mysqli_real_escape_string($theodore_con, trim($msg["msg_microtime"]));
        $msg_snippet = mysqli_real_escape_string($theodore_con, trim($msg["msg_snippet"]));
        $msg_sb_snippet = mysqli_real_escape_string($theodore_con, trim($msg["sbdefined_snippet"]));
        
        $chkExist = mysqli_query($theodore_con, "SELECT id FROM `gmail_integration` WHERE message_id = '".$msg_message_id."' LIMIT 1");
        
        if(mysqli_num_rows($chkExist) == 0){
            $save = mysqli_query($theodore_con, "INSERT INTO `gmail_integration` (`client_id`, `user_id`, `gmail_profile_id`, `message_id`, `thread_id`, `raw_message_id`, `message_type`, `subject`, `to`, `from`, `reply_to`, `in_reply_to`, `date_utc`, `date_mc`, `body`, `snippet`, `msg_body_mime`, `sbdefined_snippet`, `moved_to_notes`, `moved_by`) VALUES ('".$dec_ci."', '".$dec_ui."', '" . $profile_id . "', '" . $msg_message_id . "', '" . $msg_thread_id . "', '".$msg_raw_message_id."', 'received', '" . $msg_subject . "', '" . $msg_to . "', '" . $msg_from . "', '".$msg_reply_to."', '".$msg_in_reply_to."', '" . $msg_date . "', '" . $msg_microtime . "', '" . $msg_body . "', '" . $msg_snippet . "', '".$msg_body_mime."', '".$msg_sb_snippet."', 1, '".$dec_ui."')");
        
            if(!$save){
                on_error(mysqli_error($theodore_con));
            } else {
                
                $savedmessage_id = mysqli_insert_id($theodore_con);
                
                foreach($msg["msg_attachments"] as $attch){
                    
                    $atch_message_id = $msg_message_id;
                    $atch_attachment_id = $attch["atch_id"];
                    $atch_filename = $attch["atch_filename"];
                    $atch_filesize = $attch["atch_size"];
                    $atch_mimetype = $attch["atch_mimeType"];
                    
                    $saveFiles = mysqli_query($theodore_con, "INSERT INTO `gmail_attached_files` (message_id, attachment_id, name, size, mimetype, modified_by) VALUES ('".$atch_message_id."', '".$atch_attachment_id."', '" . $atch_filename . "', '" . $atch_filesize . "', '" . $atch_mimetype . "', '" . $dec_ui . "')");
                
                    if(!$saveFiles){
                        on_error(mysqli_error($theodore_con));
                    }
                }
                
                $out['result'] = true;
                $out['savedmessage_id'] = $savedmessage_id;
                $out['message'] = "Message successfully inserted. (" .$msg_message_id . ")";
                $out['state'] = 0;
            }
            
        } else {
            $out['result'] = true;
            $out['message'] = "Message already exists. (" .$msg_message_id . ")";
            $out['state'] = 1;
        }
        
        array_push($result, $out);
        echo json_encode($result);
    }

?>
