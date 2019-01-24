<?php

?>

<html lang="en-AU"><head>
<title>XERO Integration</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<style>
@charset utf-8;
body{font-family:Sans-Serif, Arial, Calibri, Helvetica;}
.formcontent{width:70%;background:#FCFCFC;border:solid 1px #FDFDFD;box-shadow:0 0 1px 1px #d5d5d5;border-radius:5px;display:block;color:#5cb85c;margin:50px auto;padding:3%;}
#backtoform{text-decoration:underline;font-size:14px;color:#1682ba;cursor:pointer;}
.btn{display:inline-block;margin-bottom:0;margin-top:20px;font-size:14px;font-weight:400;line-height:1.42857143;text-align:center;white-space:nowrap;vertical-align:middle;-ms-touch-action:manipulation;touch-action:manipulation;cursor:pointer;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;background-image:none;border:1px solid transparent;border-radius:4px;padding:10px 16px;}
.btn-default{color:#333;background-color:#fff;border-color:#ccc;}
span a:link{color:#00a2ff;text-decoration:none;}
span a:hover{text-decoration:underline;color:#00a2ff;}
span a:visited{text-decoration:none;color:#00a2ff;}
.title_view{color:#333;}
</style>
</head>
<body>

<div class="formcontent">
	<span>Success! A copy of your submission has been emailed to you.</span><br>

<br><br><table width="100%" cellpadding="5" cellspacing="0" style="font-family:calibri, arial; margin-top:1%; padding:0; border: solid 1px #ccc; font-size: 14px">
                <tbody><tr>
                    <th style="border:solid 1px #ccc; text-align:center;"><span class="title_view">Customer Name</span></th>
                    <th style="border:solid 1px #ccc; text-align:center;"><span class="title_view">Customer Address</span></th>
                    <th style="border:solid 1px #ccc; text-align:center;"><span class="title_view">Representative Name</span></th>
                    <th style="border:solid 1px #ccc; text-align:center;"><span class="title_view">Email Address</span></th>
                    <th style="border:solid 1px #ccc; text-align:center;"><span class="title_view">Contact Number</span></th>
                    <th style="border:solid 1px #ccc; text-align:center;"><span class="title_view">Action</span></th>
                </tr><tr>
                            <td style="border:solid 1px #ccc; text-align:left; font-size:12px; color: #333">Abbey Ormond</td>
                            <td style="border:solid 1px #ccc; text-align:left; font-size:12px; color: #333">59 Swanston Street LANDSBOROUGH VIC 3384</td>
                            <td style="border:solid 1px #ccc; text-align:left; font-size:12px; color: #333">Abbey Ormond</td>
                            <td style="border:solid 1px #ccc; text-align:left; font-size:12px; color: #333">abbey@ormond.com</td>
                            <td style="border:solid 1px #ccc; text-align:right; font-size:12px; color: #333">(03) 5381 3736</td>
                            <td style="border:solid 1px #ccc; text-align:center; font-size:12px; color: #333">
                                <span><a href="#" onclick="open_in_xero(&quot;eae8b0ad-48b8-4759-afac-38ebd864c4b9&quot;)">Open in XERO</a></span>
                            </td>
                        </tr></tbody></table><script>alert('Unexpected error encountered. SMTP Error: data not accepted.SMTP server error: DATA END command failed Detail: Message rejected: Sending paused for this account. For more information, please check the inbox of the email address associated with your AWS account.
 SMTP code: 554');</script>
</div>

<script type="text/javascript">
    function open_in_xero(contact_id){
        window.open('https://go.xero.com/organisationlogin/default.aspx?shortcode=!7J1hz&redirecturl=/Contacts/View/' + contact_id);
    }
    
    function return_page(){
        window.location = '../?ui=MzI3Mg==&ci=MzI0&refresh=1&subf=1';
    }
</script>


</body></html>