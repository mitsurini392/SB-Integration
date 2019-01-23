<?php
if(session_start()) { 
	session_destroy();
}

//$val_website_hostname = substr($_SERVER['HTTP_HOST'], 0, 4);
//if($val_website_hostname != 'www.'){
//    echo '<script>window.location.assign("https://www.buildersadmin.com/builder/index.php");</script>';
//}

include "controller/connection.php";
include("controller/new_login.php");

$confirm = base64_decode($_GET["confirm"]);
$video = base64_decode($_GET["vdeo"]);

	if($confirm != "" && $video !=""){
		$commStart = "";
		$commEnd = "";
	} else {
		$commStart = "<!---////////";
		$commEnd = "////////--->";
	}

///--------Browser Checker--------////
function getBrowser() { 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //SFE Get the platform
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }

    //SFE Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    } 
    elseif(preg_match('/Firefox/i',$u_agent)) 
    { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    }
    elseif(preg_match('/OPR/i',$u_agent)) 
    { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } 
    elseif(preg_match('/Chrome/i',$u_agent)) 
    { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) 
    { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } 
    elseif(preg_match('/Netscape/i',$u_agent)) 
    { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 

    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
    // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }

    // check if we have a number
    if ($version==null || $version=="") {$version="?";}

    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
} 
$ua=getBrowser();
$browser_used = $ua['name'];

if($browser_used == "Google Chrome"){
	$display_warning = "display:none;";
}
///--------Browser Checker--------////
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
<meta name="description" content="Small Builders Home Building Software Index">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Small Builders | Log In</title>
<link href="css/boilerplate.css" rel="stylesheet" type="text/css">
<link href="css/general.css" rel="stylesheet" type="text/css">
<link rel="icon" href="images/favicon.ico">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style>
.label{font-family: "Calibri", Arial, Helvetica, sans-serif;color: #000;}
.spreg{font-family: "Calibri", Arial, Helvetica, sans-serif;color: #000; color: #427fed; font-size: 100%;cursor: pointer;float: left;}
.spret{font-family: "Calibri", Arial, Helvetica, sans-serif;color: #000; color: #427fed; font-size: 100%;cursor: pointer;float: right;}
.spreg:hover {text-decoration: underline;color: #427fed;}
.spret:hover {text-decoration: underline;color: #427fed;}
@import "compass/css3";
.page {padding: 15px 0px 0px;}
.modalButton {display: block;margin: 15px auto;padding: 5px 15px;}
.modal-dialog {
    .close-button {overflow: hidden;
        button.close {font-size: 30px;line-height: 30px;padding: 7px 4px 7px 13px;@include text-shadow(none);@include opacity(.7);color:#fff;
            span {display: block;}
            &:hover, &:focus { @include opacity(1);outline: none;}
        }
    }
  
    .modal-content {box-shadow: none; background-color: transparent; border: 0;
    iframe {display: block;margin: 0 auto;}
    }
}
</style>
</head>
<body>
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/879268856/?guid=ON&amp;script=0">
<link rel="stylesheet" href="css/magnific-popup.css"></link>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="js/jquery.magnific-popup.js"></script>
<?php echo $commStart; ?>
<script>
 $(window).load(function () {
    $.magnificPopup.open({
		items: {src: ''},
        disableOn: 700,
        type: 'iframe',
        mainClass: 'mfp-fade',
        removalDelay: 160,
        preloader: false,
        fixedContentPos: false,
		iframe: {
            markup: '<style>.mfp-iframe-holder .mfp-content {max-width: 900px;height:500px}</style>'+ 
                    '<div class="mfp-iframe-scaler" >'+
                    '<div class="mfp-close"></div>'+
                    '<iframe src="<?php echo $video; ?>" frameborder="0" ></iframe>'+
                    '</div></div>'
				}
    });
});
</script>
<?php echo $commEnd; ?>

<?php
    $detect = new Mobile_Detect;
    if ($detect->isMobile()){
        echo '<script>console.log("mobile");</script>';
    }
    else {
        echo '<script>console.log("not mobile");</script>';
    }
?>
<div class="gridContainer clearfix">
 <form method="POST" autocomplete="on">   
	<div class="alert alert-warning" style="margin-bottom: 0px;display:none;">
		You are currently using <?php echo $browser_used; ?> as your browser. Please use Google Chrome to avoid glitches on the portal. If you have not installed it yet,  <a href="https://www.google.com/chrome/browser/desktop/index.html" title="Download Google Chrome" target="_blank"/>download it here</a>.
	</div>
    <div class="fluid loginSignupContainer">		
        <div class="header" align="center">
        	<a href="../"><img src="img/SBLogo.png" alt="Small Builders"></a>
        </div>
        <div id="divLogIn" class="login">
            <table cellpadding="3" cellspacing="3" width="100%">
                <tr>
                    <td align="center">
					<div style="margin-bottom: 25px" class="input-group">
							<h1><label>Log in</label></h1>
                    </div>
					</td>
                </tr>
                <tr>
                    <td>						
						<div style="margin-bottom: 25px" class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
							<input type="text" class="txtField form-control" name="li_username" id="li_username" autocorrect="on" autocomplete="off" required="required" placeholder="Email Address" autofocus value="<?php echo isset($_GET['email']) ? $_GET['email'] : ''; ?>" />
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
						<div style="margin-bottom: 25px" class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
							<input type="password" class="txtField form-control" name="li_password" id="li_password" autocomplete="off" required="required" placeholder="Password" />
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                    	<span class="spret modal-title" onClick="window.location='forgotpassword.php';">Forgot Password?</span>
                    	<span class="spreg modal-title" onClick="window.location='signup/';">Register an Account</span>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <input type="hidden" name="inputHiddenRedirect" id="inputHiddenRedirect" value="<?php echo $_GET['c3RhdHVz']; ?>">
                        <input type="hidden" name="inputHiddenClientId" id="inputHiddenClientId" value="<?php echo $_GET['Y2xpZW50X2lk']; ?>">
                    	<input type="submit" class="btnSubmit" name="btnLogIn" id="btnLogIn" value="SIGN IN" /><br/><br/>
						<?php echo $login_status; ?>
                    </td>
                </tr>
				<tr>
					<td style='text-align:center;padding-bottom:10px;'>
						<span>For any concerns, contact John Dela Cruz on 0414 325 080.</span>
					</td>
                </tr>
                <tr>
					<td style='text-align:center;display:none;'>
						<script type="text/javascript" src="https://seal.geotrust.com/getgeotrustsslseal?host_name=<?php echo asset_host();?>&amp;size=M&amp;lang=en"></script><br />
<a href="http://www.geotrust.com/ssl/" target="_blank"  style="color:#000000; text-decoration:none; font:bold 7px verdana,sans-serif; letter-spacing:.5px; text-align:center; margin:0px; padding:0px;"></a>
					</td>
                </tr>
            </table>
           </div>
    </div>
</form>
</div>
<script>
( function($) {
function iframeModalOpen(){

		$('.modalButton').on('click', function(e) {
			var src = $(this).attr('data-src');
			var width = $(this).attr('data-width') || 640;
			var height = $(this).attr('data-height') || 360;

			var allowfullscreen = $(this).attr('data-video-fullscreen');
		
			$("#myModal iframe").attr({
				'src': src,
				'height': height,
				'width': width,
				'allowfullscreen':''
			});
		});

		$('#myModal').on('hidden.bs.modal', function(){
			$(this).find('iframe').html("");
			$(this).find('iframe').attr("src", "");
		});
	}
  
  $(document).ready(function(){
		iframeModalOpen();
  });
  
  } ) ( jQuery );
</script>

<script type="text/javascript" src="js/respond.min.js"></script>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-63213932-1', 'auto');
ga('send', 'pageview');
</script>

<!-- Start of Async HubSpot Analytics Code -->
<script type="text/javascript">
(function(d,s,i,r) {
if (d.getElementById(i)){return;}
var n=d.createElement(s),e=d.getElementsByTagName(s)[0];
n.id=i;n.src='//js.hs-analytics.net/analytics/'+(Math.ceil(new Date()/r)*r)+'/1652500.js';
e.parentNode.insertBefore(n, e);
})(document,"script","hs-analytics",300000);
</script>
<!-- End of Async HubSpot Analytics Code -->


<!-- Google Code for Remarketing Tag -->
<!--------------------------------------------------
Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. See more information and instructions on how to setup the tag on: http://google.com/ads/remarketingsetup
--------------------------------------------------->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 879268856;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/879268856/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

</body>
</html>