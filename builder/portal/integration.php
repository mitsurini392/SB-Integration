<?php
include("../../theodore/formbuilder/controller/connection.php");
include("data-provider/client_info.php");
include("../controller/connection.php");
include("data-provider/user_info.php");
include("init.php");
include("sb_tools/header.php");
?>
<!doctype html>
<html class="">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo app_host_name(); ?> Portal</title>
    <link rel="shortcut icon" href="<?php echo asset_host(); ?>/builder/images/icon.png">
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo asset_host(); ?>/builder/portal/js/custom.js"></script>
	<script type="text/javascript" src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
	<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="<?php echo asset_host(); ?>/builder/portal/css/custom.css" type="text/css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
	<link href="<?php echo asset_host(); ?>/theodore/formbuilder/libs/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="<?php echo asset_host(); ?>/theodore/formbuilder/libs/js/gen.js"></script>
	<style>
        /** Start - Search Form script */
        .ui-widget{ font-size:12px; margin:0; background-image:none;} .ui-state-focus:hover{padding-left:0; padding-right:0; text-indent:5px;}
        .ui-autocomplete { max-height: 230px;overflow-y: auto;overflow-x: hidden;}
        /** End - Search Form script */
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
			<!-- Start - Main Navigation -->
			<?php include("sb_tools/profile_forms.php"); ?>
			<!--- End Update Profile and Search -->
			<?php include("sb_tools/navigation.php");?>
			<!-- Start - Content -->
			<div class="c-pagecontent card">
				<!-- Start - Dashboard Page -->
				<div>
					<br/><br/>
					<h4>Connect your <?php echo app_host_name(); ?> account to your accounting software.</h4>
					<br/><br/><br/><br/><br/>
				</div>
				<section class="row"><br/><br/><br/><br/><br/><br/></section>
				<!-- End - Dashboard Page -->
			</div>
			<!-- End - Content -->
			<hr/>
			<div class="c-footer-details"></div>
		</div>
	</div>
	<!--- SB 3rd Party Tools --->
    <?php include("sb_tools/footer.php");?>
	<!--- SB 3rd Party Tools --->
</body>
</html>