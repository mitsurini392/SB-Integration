<?php

include('../../../../theodore/formbuilder/controller/connection.php');
$result = array();

$data = array();

$ui = $_GET["ui"];
$ci = $_GET["ci"];
$dec_ui = base64_decode($ui);
$dec_ci = base64_decode($ci);

$qMessages = "SELECT a.id as email_id, b.id as notes_id, a.client_id, a.user_id, a.message_id, a.subject, a.from, a.sbdefined_snippet, a.date_mc, b.project_name, b.person_responsible, b.due_date FROM `gmail_integration` as a INNER JOIN `tbl_dashboard_notes` as b ON (a.id = b.gmail_message_id) WHERE a.client_id = '".$dec_ci."' AND moved_to_notes <> 0";

    $getMessages = mysqli_query($theodore_con, $qMessages);

    if($getMessages){
    
        while($fecthMessages = mysqli_fetch_array($getMessages)){
            $arr['id'] = $fecthMessages['email_id'];
            $arr['refid'] = $fecthMessages['client_id'] . $fecthMessages['user_id'] . $fecthMessages['notes_id'] . $fecthMessages['email_id'] ;
            $arr['subject'] = trim($fecthMessages['subject']);
            $arr['from'] = trim($fecthMessages['from']);
            $arr['date_mc'] = trim($fecthMessages['date_mc']);
            $arr['snippet'] = trim($fecthMessages['sbdefined_snippet']);
            $arr['project_name'] = trim($fecthMessages['project_name']);
            $arr['person_responsible'] = trim(substr($fecthMessages['person_responsible'], 0, strpos($fecthMessages['person_responsible'], "(")));
            $arr['due_date'] = $fecthMessages['due_date'];
            
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
?>