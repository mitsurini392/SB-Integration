<?php

include('../../../../theodore/formbuilder/controller/connection.php');
$result = array();

$data = array();

$profile_id = trim($_GET['profile_id']);
$profile_id = base64_decode($profile_id);

$qMessages = "SELECT * FROM `gmail_integration` WHERE gmail_profile_id = '".$profile_id."' AND moved_to_notes = 0 order by id desc";

    $getMessages = mysqli_query($theodore_con, $qMessages);

    if($getMessages){
    
        while($fecthMessages = mysqli_fetch_array($getMessages)){
            $arr['id'] = $fecthMessages['id'];
            //$arr['refid'] = $fecthMessages['client_id'] . $fecthMessages['user_id'] . $fecthMessages['id'];
            $arr['raw_message_id'] = $fecthMessages['raw_message_id'];
            $arr['message_id'] = $fecthMessages['message_id'];
            $arr['thread_id'] = $fecthMessages['thread_id'];
            $arr['subject'] = $fecthMessages['subject'];
            $arr['to'] = $fecthMessages['to'];
            $arr['reply_to'] = trim(str_replace(',', '', delete_all_between('"', '"', $fecthMessages['reply_to'])));
            $arr['in_reply_to'] = $fecthMessages['in_reply_to'];
            $arr['from'] = trim($fecthMessages['from']);
            $arr['from_name'] = trim(delete_all_between('<', '>', $fecthMessages['from']));
            $arr['from_email'] = trim(GetStringBetween($fecthMessages['from'], '<', '>'));
            $arr['date_utc'] = $fecthMessages['date_utc'];
            $arr['date_mc'] = $fecthMessages['date_mc'];
            $arr['body'] = $fecthMessages['body'];
            $arr['snippet'] = $fecthMessages['snippet'];
            
            $attachments = array();
            $qAttachments = "SELECT id, name, size, mimetype FROM `gmail_attached_files` WHERE TRIM(message_id) = '".trim($fecthMessages['message_id'])."'";
            
                $getAttachments = mysqli_query($theodore_con, $qAttachments);
                
                if($getAttachments){
                    while($fecthAttachments = mysqli_fetch_array($getAttachments)){
                        $atch['id'] = $fecthAttachments['id'];
                        $atch['name'] = $fecthAttachments['name'];
                        $atch['size'] = $fecthAttachments['size'];
                        $atch['mimetype'] = $fecthAttachments['mimetype'];
                        array_push($attachments, $atch);
                    }
                }
                
            $arr['attachment'] = $attachments;
            array_push($data, $arr);
        }
        
        $out['result'] = true;
        $out['data'] = $data;
        
    } else {
        $out['result'] = false;
        $out['message'] = mysqli_error($theodore_con);
    }


array_push($result, $out);
echo json_encode($result);


function delete_all_between($beginning, $end, $string) {
  $beginningPos = strpos($string, $beginning);
  $endPos = strpos($string, $end);
  if ($beginningPos === false || $endPos === false) {
    return $string;
  }

  $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

  return str_replace($textToDelete, '', $string);
}

function GetStringBetween($string, $start, $finish) {
    $string = " ".$string;
    $position = strpos($string, $start);
    if ($position == 0) return "";
    $position += strlen($start);
    $length = strpos($string, $finish, $position) - $position;
    return substr($string, $position, $length);
}
?>