<?php
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
$pagename = "Quickbooks - Suppliers";
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
<form method="POST">
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
			
			<table id="tbl_records_suppliers" class="table table-striped table-bordered" cellspacing="0" width="100%">
				<thead>
					<tr>
						<td class='text-center' style="color:#FFF; width:40px;" nowrap>&nbsp;<input type="checkbox" name="chkAll" id="chkAll">&nbsp;</td>
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
                <input type="submit" name="sbpush_supplier" id="sbpush_supplier" class="center-block btn btn-lg btn-success" value="Move to Quickbooks">
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


$('#tbl_records_suppliers').dataTable( {
    "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
    "aoColumnDefs" : [{'bSortable' : false, 'aTargets' : [0]}],
    "aaSorting": [1, 'asc']
});

</script>
</form>

</body>
</html>