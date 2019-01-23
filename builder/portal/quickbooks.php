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
//include("/quickbooks-integration/index.php");

$_SESSION['islogged'] = 'yes';
$getui = $_GET['ui'];
$getci = $_GET['ci'];
$dec_ui = base64_decode($getui);
$dec_ci = base64_decode($getci);
$constat = 0;



$config = include('quickbooks-integration/config.php');


$dataService = DataService::Configure(array(
    'auth_mode' => 'oauth2',
    'ClientID' => $config['client_id'],
    'ClientSecret' =>  $config['client_secret'],
    'RedirectURI' => $config['oauth_redirect_uri'],
    'scope' => $config['oauth_scope'],
    'baseUrl' => "development"
));

$OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
$authUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();



// Store the url in PHP Session Object;
$_SESSION['authUrl'] = $authUrl;

//set the access token using the auth object
if (isset($_SESSION['sessionAccessToken'])) {

    $accessToken = $_SESSION['sessionAccessToken'];
    $accessTokenJson = array('token_type' => 'bearer',
        'access_token' => $accessToken->getAccessToken(),
        'refresh_token' => $accessToken->getRefreshToken(),
        'x_refresh_token_expires_in' => $accessToken->getRefreshTokenExpiresAt(),
        'expires_in' => $accessToken->getAccessTokenExpiresAt()
    );
    $dataService->updateOAuth2Token($accessToken);
    $oauthLoginHelper = $dataService -> getOAuth2LoginHelper();
    $CompanyInfo = $dataService->getCompanyInfo();
}
else {
    echo "";
}


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
    <script src="<?php echo asset_host(); ?>/builder/portal/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo asset_host(); ?>/builder/portal/js/dataTables.bootstrap.js"></script>
    <script src="<?php echo asset_host(); ?>/theodore/formbuilder/libs/js/apps.min.js"></script>
	<style>
        /** Start - Search Form script */
        .ui-widget{ font-size:12px; margin:0; background-image:none;} .ui-state-focus:hover{padding-left:0; padding-right:0; text-indent:5px;}
        .ui-autocomplete { max-height: 230px;overflow-y: auto;overflow-x: hidden;}
        /** End - Search Form script */
        
        .active_link {color:#4b6c9f;}
        .active_link:hover {cursor:pointer; text-decoration: underline}
        .inactive_link {cursor:not-allowed;}
        ._profilepic{background-size: 96px 96px; border: none; vertical-align: top; height: 96px; width: 125px; border-radius: 5px 5px 5px 5px;}
        .imgcon {margin-top:5%; cursor:pointer}
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

		<script type="text/javascript">
            $('#form_10228 a').attr("onclick","callContactsPage()").removeAttr("href");
			$('#form_10154 a').attr("onclick","callInvoicePage()").removeAttr("href");
			$('#form_10155 a').attr("onclick","callExpenseClaimsPage()").removeAttr("href");
			$('#form_10156 a').attr("onclick","callTimesheetsPage()").removeAttr("href");
		</script>
		
		<!-- End - Main Navigation -->
		
		<!-- Start - Content -->
		<div class="c-pagecontent">

			<section class="row">
				<section class="col-lg-12">
				<br/><br/>
				<p>You can connect your account to Quickbooks accounting software from here. You can also move your registered payment claims into your Quickbooks account to make a draft tax invoice from here.</p>
				<br/>
				</section>
			</section>
	
			<section class="row">			
				<section class="col-lg-12">
					<section class="pull-left text-left">

					<?php

                    $movetoqb_class = "";
                    $act_movetoqb = "";
                    $movetoqb_title = "";
                    $imglink = asset_host() . '/builder/portal/quickbooks_integration/libs/'; 

                    if(isset($accessTokenJson)) {
                        echo "Status: <p style='color: green; display: inline'>Connected</p><br>";
                        echo "Organisation: ".$CompanyInfo->CompanyName."<br>";
                        echo "<a href='quickbooks-integration/logout.php'><img src='quickbooks-integration/disconnect.png'></a>";

                        $movetoqb_class = "btn-success active_link";
                        $act_movetoqb = "enabled";
                    }
                    else {
                        echo "Status: <p style='color: red; display: inline'>Not Connected</p><br><br>";
                        echo "<a class='imgLink' href='#' onclick='oauth.loginPopup()'><img src='quickbooks-integration/views/C2QB_green_btn_lg_default.png' width='178' /></a>
                        <hr />";

                        $movetoqb_class = "btn-default inactive_link";
                        $act_movetoqb = "disabled";
                        $movetoqb_title = "Not connected to Quickbooks";
                    }
        ?>
    

					</section>
				</section>
			</section>
			
			<section class="row"><br/><br/><br/><br/><br/><br/></section>
					
		</div>
		
		<!-- End - Content -->
		<hr/>
		<div class="c-footer-details"></div>
            <?php include("sb_tools/footer.php"); ?>
		</div>
	</div>
<script type="text/javascript" id="inspectletjs">

    function callContactsPage(){
        var connection_status = '<?php echo $act_movetoqb; ?>';
        if(connection_status == 'enabled'){
            window.location = "mn_quickbooks_contacts.php?ui=<?php echo $getui; ?>&ci=<?php echo $getci; ?>";
        } else {
                alert("Please connect to Quickbooks.");
            }
            
    }

    function callInvoicePage(){
        var connection_status = '<?php echo $act_movetoqb; ?>';
        
        if(connection_status == 'enabled'){
            window.location = "mn_quickbooks_invoice.php?ui=<?php echo $getui; ?>&ci=<?php echo $getci; ?>";
        } else {
                alert("Please connect to Quickbooks.");
            }
            
    }

    function callExpenseClaimsPage(){
        var connection_status = '<?php echo $act_movetoqb; ?>';
        
        if(connection_status == 'enabled'){
            window.location = "mn_quickbooks_expenseclaims.php?ui=<?php echo $getui; ?>&ci=<?php echo $getci; ?>";
        } else {
                alert("Please connect to Quickbooks.");
            }
            
    }

    function callTimesheetsPage(){
        var connection_status = '<?php echo $act_movetoqb; ?>';
        
        if(connection_status == 'enabled'){
            window.location = "mn_quickbooks_timesheets.php?ui=<?php echo $getui; ?>&ci=<?php echo $getci; ?>";
        } else {
                alert("Please connect to Quickbooks.");
            }
            
    }

    function GetQueryStringParams(e) {
            var t = window.location.search.substring(1);
            var n = t.split("&");
            for (var r = 0; r < n.length; r++) {
                var i = n[r].split("=");
                if (i[0] == e) {
                    return i[1]
                }
            }
        }
</script>

<script id='qbScripts'>
    var url = '<?php echo $authUrl; ?>';

    var OAuthCode = function(url) {


        //SHOW LOGIN WINDOW
        this.loginPopup = function (parameter) {
            this.loginPopupUri(parameter);
        }
        
        //CREATE LOGIN WINDOW
        this.loginPopupUri = function (parameter) {

            // Launch Popup
            var parameters = "location=1,width=800,height=650";
            parameters += ",left=" + (screen.width - 800) / 2 + ",top=" + (screen.height - 650) / 2;

            var win = window.open(url, 'connectPopup', parameters);
            var pollOAuth = window.setInterval(function () {
                try {

                    if (win.document.URL.indexOf("code") != -1) {
                        window.clearInterval(pollOAuth);
                        win.close();
                        showloading();
                        location.reload();
                    }
                } catch (e) {
                    console.log(e)
                }
            }, 100);
        }
    }

    var apiCall = function() {

        //GET COMPANY NAME
        this.getCompanyName = function() {
            $.ajax({
                type: "GET",
                url: "getCompanyName.php",
            }).done(function( msg ) {
                $( '#orgName' ).html( msg );
            });
        }
        
        //GET COMPANY INFO
        this.getCompanyInfo = function() {
            $.ajax({
                type: "GET",
                url: "getCompanyInfo.php",
            }).done(function( msg ) {
                $( '#apiCall' ).html( msg );
            });
        }
        
        //REFRESH TOKEN
        this.refreshToken = function() {
            $.ajax({
                type: "POST",
                url: "refreshToken.php",
            }).done(function( msg ) {

            });
        }
    }

    var oauth = new OAuthCode(url);
    var apiCall = new apiCall();

    </script>

</body>
</html>