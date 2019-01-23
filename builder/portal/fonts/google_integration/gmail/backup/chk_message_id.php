<?php
include('../../../../theodore/formbuilder/controller/connection.php');
$result = array();
$data = array();

$profile_id = trim($_GET['profile_id']);
$profile_id = base64_decode($profile_id);
$client_id = trim($_GET['client_id']);
$client_id = base64_decode($client_id);

$qMessageId = "SELECT message_id FROM `gmail_integration` WHERE gmail_profile_id = '".$profile_id."' AND client_id = '".$client_id."' order by id desc";
//echo $qMessageId;
    $getMessageId = mysqli_query($theodore_con, $qMessageId);

    if($getMessageId){
    
        while($fecthMessageId = mysqli_fetch_array($getMessageId)){
            $arr['message_id'] = $fecthMessageId['message_id'];
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

?>