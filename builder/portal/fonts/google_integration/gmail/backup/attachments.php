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

    foreach($fetchdata as $atch){
        
        $atch_message_id = mysqli_real_escape_string($theodore_con, trim($atch["message_id"]));
        $atch_attachment_id = mysqli_real_escape_string($theodore_con, trim($atch["attachment_id"]));
        $atch_attachment_u_id = mysqli_real_escape_string($theodore_con, trim($atch["attachment_u_id"]));
        $atch_filename = mysqli_real_escape_string($theodore_con, trim($atch["name"]));
        $atch_filesize = mysqli_real_escape_string($theodore_con, trim($atch["size"]));
        $atch_mimetype = mysqli_real_escape_string($theodore_con, trim($atch["mimetype"]));
        $atch_data = mysqli_real_escape_string($theodore_con, trim($atch["data"]));
        
        $chkExist = mysqli_query($theodore_con, "SELECT id FROM `gmail_attached_files` WHERE message_id = '".$atch_message_id."' AND attachment_u_id = '".$atch_attachment_u_id."' AND TRIM(name) = '".$atch_filename."' LIMIT 1");
        
        if(mysqli_num_rows($chkExist) == 0){
            $save = mysqli_query($theodore_con, "INSERT INTO `gmail_attached_files` (message_id, attachment_id, attachment_u_id, name, size, mimetype, data, modified_by) VALUES ('".$atch_message_id."', '".$atch_attachment_id."', '".$atch_attachment_u_id."', '" . $atch_filename . "', '" . $atch_filesize . "', '" . $atch_mimetype . "',  '" . $atch_data . "', '" . $dec_ui . "')");
        
            if(!$save){
                on_error(mysqli_error($theodore_con));
            } else {
                $out['result'] = true;
                $out['message'] = "successfully inserted: " . $atch_attachment_id;
                array_push($result, $out);
                echo json_encode($result);
            }    
        } else {
            $out['result'] = true;
            $out['message'] = "message id: " . $atch_attachment_id . " already exists";
            array_push($result, $out);
            echo json_encode($result);    
        }
        
    }


?>