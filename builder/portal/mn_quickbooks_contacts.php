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
$pagename = "Quickbooks - Contacts";
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
	<style>
        /** Start - Search Form script */
        .ui-widget{ font-size:12px; margin:0; background-image:none;} .ui-state-focus:hover{padding-left:0; padding-right:0; text-indent:5px;}
        .ui-autocomplete { max-height: 230px;overflow-y: auto;overflow-x: hidden;}
        /** End - Search Form script */
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
		
		<!-- Start - Content -->
		<div class="c-pagecontent">
		
			<section class="row">
                
				
				
			</section>
			
		</div>
		
		<!-- End - Content -->
		<hr/>
		<div class="c-footer-details"></div>
		</div>
        <?php 
            include("sb_tools/footer.php"); 
        ?>
	</div>

</form>

</body>
</html>