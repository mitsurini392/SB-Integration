<?php

require_once('quickbooks-integration/vendor/autoload.php');

use QuickBooksOnline\API\DataService\DataService;

session_start();
include("../../theodore/formbuilder/controller/connection.php");
include("data-provider/client_info.php");
include("../controller/connection.php");
include("data-provider/user_info.php");
include("init.php");
include("sb_tools/header.php");
include "../../theodore/formbuilder/controller/phpMailerClass.php";


$_SESSION['islogged'] = 'yes';
$getui = $_GET['ui'];
$getci = $_GET['ci'];
$dec_ui = base64_decode($getui);
$dec_ci = base64_decode($getci);
$constat = 0;

/** - Start - User Activity - AQS 20170130 */
$dec_ui = base64_decode($getui);
$dec_ci = base64_decode($getci);

$ui_ = $dec_ui;
$ci_ = $dec_ci;
$browser_details = $_SERVER['HTTP_USER_AGENT'];
$form_id = "";
$pagename = "Quickbooks - Customers";
$pageurl = $_SERVER['REQUEST_URI'];
$activity = "View Page";
$category_id = 4;

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
		}

$saveActivity = mysqli_query($csportal_con, "INSERT INTO `cs_user_activity` (user_id, client_id, form_id, category_id, page_name, page_url, ip_address, browser_details, activity) VALUES ('".$ui_."', '".$ci_."', '".$form_id."', '".$category_id."', '".$pagename."', '".$pageurl."', '".$ip."', '".$browser_details."', '".$activity."')");

if($saveActivity){
	$activity_id = mysqli_insert_id($theodore_con);
}

//Check QB Session
if (!isset($_SESSION['sessionAccessToken'])) {
	header('Location: quickbooks.php?ui='.$getui.'&ci='.$getci);
}

//QUICKBOOKS_TOKENS
$accessToken = $_SESSION['sessionAccessToken'];
$accessTokenJson = array('token_type' => 'bearer',
	'access_token' => $accessToken->getAccessToken(),
	'refresh_token' => $accessToken->getRefreshToken(),
	'x_refresh_token_expires_in' => $accessToken->getRefreshTokenExpiresAt(),
	'expires_in' => $accessToken->getAccessTokenExpiresAt()
);

$access_token = $accessTokenJson["access_token"];
$refresh_token = $accessTokenJson["refresh_token"];
$realm_id = $accessToken->getRealmID();



//send activity id to thanks page

/** - End - User Activity - AQS 20170130 */
?>

<!doctype html>
<!--[if lt IE 7]> <html class="ie6 oldie"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="">
<!--<![endif]-->
<!--[if lt IE 9]> <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script> <![endif]-->
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Small Builders Portal</title>
    <link rel="shortcut icon" href="<?php echo asset_host(); ?>/builder/images/icon.png">
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo asset_host(); ?>/builder/portal/js/custom.js"></script>
	<script type="text/javascript" src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
	<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="<?php echo asset_host(); ?>/builder/portal/css/dataTables.bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo asset_host(); ?>/builder/portal/css/custom.css" type="text/css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
	<link href="<?php echo asset_host(); ?>/theodore/formbuilder/libs/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="<?php echo asset_host(); ?>/theodore/formbuilder/libs/js/gen.js"></script>
    <script src="<?php echo asset_host(); ?>/builder/portal/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo asset_host(); ?>/builder/portal/js/dataTables.bootstrap.js"></script>
    <script src="<?php echo asset_host(); ?>/theodore/formbuilder/libs/js/apps.min.js"></script>
    
	<style>
        /** Start - Search Form script */
        .ui-widget{ font-size:12px; margin:0; background-image:none;} .ui-state-focus:hover{padding-left:0; padding-right:0; text-indent:5px;}
        .ui-autocomplete { max-height: 230px;overflow-y: auto;overflow-x: hidden;}
        /** End - Search Form script */
    
    	.active_link {cursor:pointer; margin-top: 4%; margin-bottom: 2%;}
    	.inactive_link {cursor:not-allowed; margin-top: 4%; margin-bottom: 2%;}
    	._profilepic{background-size: 96px 96px; border: none; vertical-align: top; height: 96px; width: 125px; border-radius: 5px 5px 5px 5px;}
	</style>
	
</head>

<body>
	<div id="page-load">
		<div class="blackout-page-load"></div>
		<div class="page-load">
			<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Please wait...
		</div>
	</div>

	<div id="c-container" style="display:none">
		<div class="c-container clearfix">
			<?php 
                include("sb_tools/profile_forms.php");
                include("sb_tools/navigation.php");
            ?>
        <!--
		<script type="text/javascript">
			$('#form_10154 a').attr("onclick","callInvoicePage()").removeAttr("href");
			$('#form_10155 a').attr("onclick","callExpenseClaimsPage()").removeAttr("href");
			$('#form_10156 a').attr("onclick","callTimesheetsPage()").removeAttr("href");
		</script>
		-->
		<!-- End - Main Navigation -->
		
		<!-- Start - Content -->
		<div class="c-pagecontent">

		<section>
			
			<section class="row">			
				<section class="col-lg-12">
					<section class="pull-left text-left">
						
					</section>
				</section>
			</section>

			<section class="row"><br/><br/></section>	

			<ul class="nav nav-tabs">
				<li class="active" id="tab-sb2qb" onclick="shiftview(this)">
					<a href="#toxero">Small Builders to Quickbooks</a>
				</li>
				<li id="tab-qb2sb" onclick="shiftview(this)">
					<a href="#toSB">Quickbooks to Small Builders</a>
				</li>
			</ul>

			<section class="row"><br><br></section>

			<section id='section_sb2qb' style='display: block'>
					<section class="col-lg-12 alert alert-warning alert-dismissable">
    				    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
    				    Below Contacts are those Customers that exist in your Small Builders Account but didn't exist in your Quickbooks Account.
    				</section>

				<table id="tbl_records_customers_sbtoqb" class="table table-striped table-bordered" cellspacing="0" width="100%">

					<thead>
                    <tr>
						<td class='text-center' style="color:#FFF; width:40px;" nowrap>&nbsp;<input type="checkbox" name="chkAll" id="chkAll" value='sb2qb' onclick="checkAll(this);">&nbsp;</td>
                        <td class='text-center' nowrap>Customer Name</td>
                        <td class='text-center' nowrap>Customer Email</td>
                        <td class='text-center' nowrap>Representative Name</td>
                        <td class='text-center' nowrap>Customer Address</td>
                        <td class='text-center' nowrap>Customer Phone Number</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sql = "SELECT * FROM _relationship_db_customers WHERE quickbooks_uid IS NULL AND client_id = ".$dec_ci;
                    
                        $query = $theodore_con->query($sql);
                    
                        while($row = mysqli_fetch_array($query)) {
                            echo "<tr>
                            <td class='text-center'><input type='checkbox' class='check_sb2qb' onclick='countCheck(this)' value='".$row["id"]."'></td>
                            <td>".$row["customer_name"]."</td>";
                            echo "<td>". $row["customer_email"] ."</td>";
                            echo "<td>". $row["representative_name"]." " .$row["representative_lname"]."</td>";
                            echo "<td>". $row["customer_address"]."</td>";
                            echo "<td>Phone: ".$row["customer_phone"]."<br>Mobile: ".$row["customer_mobile"]."<br>Fax: ".$row["customer_fax"]."</td>";
                            echo "</tr>"; 
                        }

                    ?>
                </tbody>
				
				</table>
				
				
				
				<section class="row">
					<button id="sb2qb_move" class="center-block btn btn-lg btn-success" onclick='integrate(this)' disabled>Move to Quickbooks</button>
				</section>

			</section>
			
			<section id='section_qb2sb' style='display: none'>			
				
				<section class="col-lg-12 alert alert-warning alert-dismissable">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
					Below Contacts are those Customers that exist in your Quickbooks Account but didn't exist in your Small Builders.
				</section>

				<table id="tbl_records_customers_qbtosb" class="table table-striped table-bordered" cellspacing="0" width="100%">
					<thead>
						<tr>
						<td class='text-center' style="color:#FFF; width:40px;" nowrap>&nbsp;<input type="checkbox" name="chkAll" id="chkAll" value='qb2sb' onclick='checkAll(this)'>&nbsp;</td>
							<td class='text-center' nowrap>A</td>
							<td class='text-center' nowrap>B</td>
							<td class='text-center' nowrap>C</td>
							<td class='text-center' nowrap>D</td>
							<td class='text-center' nowrap>E</td>
							<td class='text-center' nowrap>F</td>
						</tr>
					</thead>
				
				</table>
				
				
				
				<section class="row">
					<input type="submit" name="sbpush_customer" id="sbpush_customer" class="center-block btn btn-lg btn-success" value="Move to Quickbooks">
				</section>
			</section>

		</section>
		</div>
		
		<!-- End - Content -->
		<hr/>
		<div class="c-footer-details"></div>
        <?php include("sb_tools/footer.php"); ?>
		</div>
	</div>

<script type="text/javascript" id="inspectletjs">
	//TOKENS and IDs
	var access_token = `<?php echo $access_token; ?>`;
	var refresh_token = `<?php echo $refresh_token; ?>`;
	var realm_id = `<?php echo $realm_id; ?>`;

	$('#tbl_records_customers_sbtoqb').dataTable( {
		"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
		"aoColumnDefs" : [{'bSortable' : false, 'aTargets' : [0]}],
		"aaSorting": [1, 'asc']
	});

	$('#tbl_records_customers_qbtosb').dataTable( {
		"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
		"aoColumnDefs" : [{'bSortable' : false, 'aTargets' : [0]}],
		"aaSorting": [1, 'asc']
	});

	function shiftview(elem) {

		if(elem.id == "tab-sb2qb") {
			//Change Tab Active
			$("#tab-sb2qb").attr('class', 'active');
			$("#tab-qb2sb").attr('class', '');
			//CHange Section
			$("#section_sb2qb").attr('style','display: block');
			$("#section_qb2sb").attr('style','display: none');
		}
		else if(elem.id == "tab-qb2sb") {
			$("#tab-sb2qb").attr('class', '');
			$("#tab-qb2sb").attr('class', 'active');
			//Change Section
			$("#section_sb2qb").attr('style','display: none');
			$("#section_qb2sb").attr('style','display: active');
		}
	}

	function checkAll(elem) {
		if(elem.value == "sb2qb") {
			//Collect All Integrate Checks
            var check_sb2qb = document.getElementsByClassName("check_sb2qb");
			//Check or Uncheck All
			for (let i = 0; i < check_sb2qb.length; i++) {
				if(elem.checked == false) {
					check_sb2qb[i].checked = false;
					$("#sb2qb_move").prop("disabled",true);
				}
				else if (elem.checked == true) {
					check_sb2qb[i].checked = true;
					$("#sb2qb_move").prop("disabled",false);
				}
								
			}
		}
		
		else if(elem.value == "qb2sb") {
			alert(elem.value);
		}
	}

	function countCheck(elem) {
		if(elem.className == "check_sb2qb") {
			//check_sb2qb count all checks
			var check_sb2qb = document.querySelectorAll('.check_sb2qb:checked');
			//Unlock Integration
			if(check_sb2qb.length > 0) {
				$("#sb2qb_move").prop("disabled",false);
			}
			else {
				$("#sb2qb_move").prop("disabled",true);
			}
		}
	}

	function integrate(elem) {
		if(elem.id == "sb2qb_move") {
			//Add Loading
			showloading();

            //Collect All Integrate Checks
            var check_sb2qb = document.querySelectorAll('.check_sb2qb:checked');

            //Create a Table (this table will be put on SB to QB successful Message)
            var tbl = document.createElement("table");
            var header = tbl.createTHead();
            header.innerHTML = "<th>Customer Name</th><th>Customer Email</th><th>Representative Name</th><th>Customer Adress</th><th>Customer Phone</th>";

            //Integrate All Checks
            var body = tbl.createTBody();
            for (let i = 0; i < check_sb2qb.length; i++) {
                //Insert A Row
                var record = tbl.insertRow(-1);

                //Get Checked Record
                var id = check_sb2qb[i].value;
                var customer_name = check_sb2qb[i].parentNode.parentNode.childNodes[3].innerHTML;
                var customer_email = check_sb2qb[i].parentNode.parentNode.childNodes[4].innerHTML;
                var rep_name = check_sb2qb[i].parentNode.parentNode.childNodes[5].innerHTML;
                var customer_address = check_sb2qb[i].parentNode.parentNode.childNodes[6].innerHTML;
                var customer_phone = check_sb2qb[i].parentNode.parentNode.childNodes[7].innerHTML;
                
                //Add Record to Table
                body.innerHTML += "<tr id='tr"+id+"'><td>"+customer_name+"</td><td>"+customer_email+"</td><td>"+rep_name+"</td><td>"+customer_address+"</td><td>"+customer_phone+"</td></tr>";

                $.ajax({
                    method: "post",
                    url: "quickbooks-integration/Customer/customersToQB.php",
                    data: "id=" + id + "&access_token="+ access_token + "&refresh_token=" + refresh_token + "&realm_id=" + realm_id,
                    success: function (data) {
                        if(data == "Success") {
                            //DO NOT DELETE RECORD  
                        }
                        else {
                            //DELETE RECORD IF FAILED TO INTEGRATE
                            $(tbl).find("#tr" + getUrlParameter(this.data,"id")).remove();
                        }
                    }
                });
                $(document).one("ajaxStop", function() {
                    sendEmail(tbl.innerHTML);
                });
            }
		}
	}

	function sendEmail(tblContent) {
            //Generate Table
            var tbl = document.createElement("table");
            tbl.innerHTML = tblContent;
            //DO NOT CONTINUE IF THERE ARE NO SUCCESSFUL INTEGRATION
            if (tbl.getElementsByTagName("tbody")[0].innerHTML == "") {
                //alert("No Integration were successful.");
                //location.reload();
                return;
            }
            //Add Style to every th 
            var th = tbl.getElementsByTagName("th");
            var td = tbl.getElementsByTagName("td");
            //Loop to th
            for (let i = 0; i < th.length; i++) {
                th[i].setAttribute("style","border:solid 1px #ccc; text-align:center; padding: 4px 0px 4px 7px;");  
            }
            //Loop to td
            for (let i = 0; i < td.length; i++) {
                td[i].setAttribute("style","border:solid 1px #ccc; text-align:center; padding: 4px 0px 4px 7px;");
            }
            //Add Subject
            var subj = "Small Builders Customer successfully added to Quickbooks Contacts";
            //Add Description
            var desc = "You have successfully automated your Small Builders Customer details into your Quickbooks account. These customers are now available in your Quickbooks Contacts with the following details.";
            //Send Email
            //Change Message Into
            $.ajax({
                method: "post",
                url: "quickbooks-integration/sendMail.php",
                data: "tblcontent=" + tbl.innerHTML + "&subj="+ subj + "&desc=" + desc + "&client_id=" + "<?php echo $dec_ci; ?>",
                success: function (data) {
					//Change TD and TH again
					//Loop to th
					for (let i = 0; i < th.length; i++) {
						th[i].setAttribute("style","border:solid 1px #ccc; text-align:center; color: #333333;");  
					}
					//Loop to td
					for (let i = 0; i < td.length; i++) {
						td[i].setAttribute("style","border:solid 1px #ccc; text-align:left; font-size:12px; color: #333");
					}
                    //Change Whole Body InnerHTML
					var body = document.getElementsByTagName("html")[0];
					body.className = 'container';
                    body.innerHTML = `<html lang="en-AU"><head>
						<title>Quickbooks Integration</title>
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
						<div class="formcontent text-center">
						<span>Success! A copy of your submission has been emailed to you.</span><br>
						<br><br><table width="100%" cellpadding="5" cellspacing="0" style="font-family:calibri, arial; margin-top:1%; padding:0; border: solid 1px #ccc; font-size: 14px">`+tbl.innerHTML+`</table>
						<button class='btn btn-primary' onclick='location.reload();'>Back to Integration</button>
						</div>
						</body></html>`;
                }
            });
        }



	var getUrlParameter = function getUrlParameter(getURL,sParam) {
            var sPageURL = getURL,
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                }
            }
        };

</script>

</body>
</html>