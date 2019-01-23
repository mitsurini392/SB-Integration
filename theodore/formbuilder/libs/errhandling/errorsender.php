<?php
function sendErrorReport($form_id, $form_title, $errmess, $user_name, $client_name){


if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

$browser_details =  $_SERVER['HTTP_USER_AGENT'];
$page = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];     
$email->From = 'it@smallbuilders.com.au';
$email -> Subject = "Error Reporting - ".$form_title;
$email -> Body = "<html>
            <body style='font-family:calibri, arial; color:#000'>
<p style='font-size:14px'>Hi Team,</p>
<p style='font-size:14px'>Please see below report of error encountered during form submission. Please attend immediately.</p><br/><br/>
            <table cellpadding='0' cellspacing='0' style='width:100%; border-left:solid 1px #ccc; border-right:solid 1px #ccc; border-bottom:solid 1px #ccc; padding:0; font-family:calibri, arial;'>
                <tr>
                    <td style='border-top:solid 1px #ccc; width:20%; padding:3px 0px 3px 7px;' valign='top'><b>Theodore Form Id:</b></td>
                    <td style='border-top:solid 1px #ccc; width:80%; padding:3px 0px 3px 7px'>".$form_id."</td>
                </tr>
                <tr>
                    <td style='border-top:solid 1px #ccc; width:20%; padding:3px 0px 3px 7px' valign='top'><b>Form Name:</b></td>
                    <td style='border-top:solid 1px #ccc; width:80%; padding:3px 0px 3px 7px'>".$form_title."</td>
                </tr>
                <tr>
                    <td style='border-top:solid 1px #ccc; width:20%; padding:3px 0px 3px 7px' valign='top'><b>Error Message:</b></td>
                    <td style='border-top:solid 1px #ccc; width:80%; padding:3px 0px 3px 7px;'>".$errmess."</td>
                </tr>
                <tr>
                    <td style='border-top:solid 1px #ccc; width:20%; padding:3px 0px 3px 7px' valign='top'><b>Current User:</b></td>
                    <td style='border-top:solid 1px #ccc; width:80%; padding:3px 0px 3px 7px'>".$user_name."</td>
                </tr>
                <tr>
                    <td style='border-top:solid 1px #ccc; width:20%; padding:3px 0px 3px 7px' valign='top'><b>Client Name:</b></td>
                    <td style='border-top:solid 1px #ccc; width:80%; padding:3px 0px 3px 7px'>".$client_name."</td>
                </tr>
                <tr>
                    <td style='border-top:solid 1px #ccc; width:20%; padding:3px 0px 3px 7px' valign='top'><b>Current Page:</b></td>
                    <td style='border-top:solid 1px #ccc; width:80%; padding:3px 0px 3px 7px'>".$page."</td>
                </tr>
                <tr>
                    <td style='border-top:solid 1px #ccc; width:20%; padding:3px 0px 3px 7px' valign='top'><b>User IP:</b></td>
                    <td style='border-top:solid 1px #ccc; width:80%; padding:3px 0px 3px 7px'>".$ip."</td>
                </tr>
                <tr>
                    <td style='border-top:solid 1px #ccc; width:20%; padding:3px 0px 3px 7px' valign='top'><b>Browser Details:</b></td>
                    <td style='border-top:solid 1px #ccc; width:80%; padding:3px 0px 3px 7px'>".$browser_details."</td>
                </tr>
                
                
            </table>
<br/><br/><p style='font-size:14px;'>Regards, <br/> Theodore<br/><br/><br/></p>
            </body>
        </html>";

$email -> AddAddress('adrian.silva@lophils.com','Adrian Silva Mabangis');
$email -> AddCC('sav.estidolajr@lophils.com', 'Sav Panda Estidola');
$email -> AddCC('franklin.porciuncula@lophils.com', 'Bondigo RRRRRrrrrwr'); 

    if(!$email -> Send()) {
        echo $email -> ErrorInfo;
    }
}

function getdata_and_senderroremail($value_from,$value_connection,$value_query,$value_title,$username,$clientname)
{
    $query = mysqli_query($value_connection,$value_query);
    if (!$query) {
        $email_error_msg = "";
        $email_error_msg .= "<label style='color: red;'>Error query for '".$value_from."'</label>";
        $email_error_msg .= "<label style='color: red;'><br><br>Error details: ".mysqli_error($value_connection)."</label>";
        $email_error_msg .= "<label style='color: red;'><br><br>Query: </label><label style='color: blue;'>".$value_query."</label>";
        $form_id = "N/A"; if (isset($_GET['form_id']) || !empty($_GET["form_id"]) || $_GET["form_id"]!="") {$form_id = $_GET['form_id'];}
        $form_title = $value_title;
        // $rcpnt_name = $_user_firstname ." ".$_user_lastname;
        // $title = $_company_business_name;
        sendErrorReport($form_id, $form_title, $email_error_msg, $username, $clientname);
    }
}

function getdata_and_senderroremail_forsqlcommand($value_from,$value_connection,$value_query,$value_title,$username,$clientname)
{
    $email_error_msg = "";
    $email_error_msg .= "<label style='color: red;'>Error query for '".$value_from."'</label>";
    $email_error_msg .= "<label style='color: red;'><br><br>Error details: ".mysqli_error($value_connection)."</label>";
    $email_error_msg .= "<label style='color: red;'><br><br>Query: </label><label style='color: blue;'>".$value_query."</label>";
    $form_id = "N/A"; if (isset($_GET['form_id']) || !empty($_GET["form_id"]) || $_GET["form_id"]!="") {$form_id = $_GET['form_id'];}
    $form_title = $value_title;
    sendErrorReport($form_id, $form_title, $email_error_msg, $username, $clientname);
}

?>