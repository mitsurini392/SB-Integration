<?php
    include("../../theodore/formbuilder/controller/connection.php");
    $val_ui = base64_decode($_GET[ui]);
    $val_ci = base64_decode($_GET[ci]);
    $select_query = "SELECT * FROM tbl_demoscript_checker WHERE user_id=".$val_ui." AND client_id=".$val_ci. " ORDER BY ID DESC LIMIT 1";
    $select = mysqli_query($theodore_con, $select_query);
    $fetch_select = mysqli_fetch_array($select);
    
    $feat_counter = 0;
    $columns = array('timesheet','expense','payment','site','ceo','forecast');
    foreach ($columns as $col){
        if ($fetch_select['fs_'.$col] == "1"){
            $feat_counter +=1;
        }
    }

    $pid = base64_decode($_GET['pid']);

    if ($pid != "") {
        // get if project demo
        $query_project_demo = "SELECT _demo_project as is_demo FROM _submission_204 WHERE id=".$pid;
        $exe_project_demo = mysqli_query($theodore_con, $query_project_demo);
        $fetch_project_demo = mysqli_fetch_array($exe_project_demo);
        extract($fetch_project_demo);
    }
    
    $feat_start = $_GET['feat_start'];

    $qs = explode("?", $_SERVER['REQUEST_URI']);
    $phpFileName = basename($qs[0], ".php");
//    echo $phpFileName;
    // check if user is from a feature (just finished submitting a feature)
    $is_from_ss = false;
    $text_header = "Small Builders offers you a lot more!";
    $display_sub_header = "display:none;";
    $display_sub_text1 = "display:none;";
    $display_sub_text2 = "display:block;";
    $feat_name = "";
    $font_size = "25pt";
        if(isset($_GET['feat'])&&strlen($_GET['feat'])>1){
            $is_from_ss = true;
            $text_header = "Thanks, ".$_user_firstname."!";
            $display_sub_header = "display:block;";
            $display_sub_text1 = "display:block;";
            $display_sub_text2 = "display:none;";
            $feat_name = base64_decode($_GET['feat']);
            if($feat_name=='Expense System'){
                $font_size = "21pt";   
            }else if($feat_name=='Payment Claim System'){
                $font_size = "18.5pt";
            }else if($feat_name=="Timesheet System"){
                $font_size = "20.5pt";
            }else if($feat_name=="CEO Report"){
                $font_size = "23.5pt";
            }
        }

?>
<link rel="stylesheet" href="../../sb_css/Gotham.css">
<link rel="stylesheet" href="../../sb_css/onboarding_buttons.css">
<link rel="stylesheet" type="text/css" href="../../sb_css/loading-bar.css">
<script type="text/javascript" src="../../sb_js/loading-bar.js"></script>
<style>
    .calendly-badge-content{
        display: none !important;
    }
/* The Modal (background) */

#myFeature {
 
    border-top-right-radius: 25px;
        border-top-left-radius: 25px; }
#myWidget {
 
    border-top-right-radius: 25px;
        border-top-left-radius: 25px; }
.feature {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 10px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */

}

/* Modal Content */
.feature-content {
    position: relative;
    background-color: #fefefe;
    margin: auto;
    padding: 0;
    border: 1px solid #888;
    width: 770px;
    height: 670px;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
    -webkit-animation-name: anim-open;
    -webkit-animation-duration: 0.5s;
    animation-name: anim-open;
    animation-duration: 0.5s
    border-radius: 5px;
    border: none;
}

    .feature-close{
        -webkit-animation-name: anim-close;
        -webkit-animation-duration: 0.5s;
        animation-name: anim-close;
        animation-duration: 0.5s;
        opacity: 0;
    }
    
.feature-content2 {
    position: relative;
    background-color: #fefefe;
    margin: auto;
    padding: 0;
    border: 1px solid #888;
    width: 770px;
    height: 670px;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
    -webkit-animation-name: animatetop;
    -webkit-animation-duration: 0.5s;
    animation-name: animatetop;
    animation-duration: 0.5s
    border-radius: 5px;
    border: none;
}
    
.feature-dialog
  {
    position: fixed;
    margin: auto;
    width: 770px;
    height: 649px;
    -webkit-transform: translate3d(0%, 0, 0);
        -ms-transform: translate3d(0%, 0, 0);
         -o-transform: translate3d(0%, 0, 0);
            transform: translate3d(0%, 0, 0);
  }
    
/* Add Animation */
@-webkit-keyframes animatetop {
    from {top:-300px; opacity:0} 
    to {top:0; opacity:1}
}

@keyframes animatetop {
    from {top:-300px; opacity:0}
    to {top:0; opacity:1}
}

@-webkit-keyframes anim-open {
	0% { opacity: 0; -webkit-transform: translate3d(-400px, 0, 0) scale3d(1.4, 0.5, 1); }
	100% { opacity: 1; -webkit-transform: translate3d(0, 0, 0) scale3d(1, 1, 1); }
}

@keyframes anim-open {
	0% { opacity: 0; -webkit-transform: translate3d(-400px, 0, 0) scale3d(1.4, 0, 1); transform: translate3d(-400px, 0, 0) scale3d(1.4, 0, 1); }
	100% { opacity: 1; -webkit-transform: translate3d(0, 0, 0) scale3d(1, 1, 1); transform: translate3d(0, 0, 0) scale3d(1, 1, 1); }
}

@-webkit-keyframes anim-close {
	0% { opacity: 1; -webkit-transform: translate3d(0, 0, 0) scale3d(1, 1, 1); }
	20% { opacity: 1; -webkit-transform: translate3d(0, 0, 0) scale3d(1, 1, 1); }
	100% { opacity: 0; -webkit-transform: translate3d(-400px, 0, 0) scale3d(1.4, 0.5, 1); }
}

@keyframes anim-close {
	0% { opacity: 1; -webkit-transform: translate3d(0, 0, 0) scale3d(1, 1, 1); transform: translate3d(0, 0, 0) scale3d(1, 1, 1); }
	20% { opacity: 1; -webkit-transform: translate3d(0, 0, 0) scale3d(1, 1, 1); transform: translate3d(0, 0, 0) scale3d(1, 1, 1); }
	100% { opacity: 0; -webkit-transform: translate3d(-400px, 0, 0) scale3d(1.4, 0, 1); transform: translate3d(-400px, 0, 0) scale3d(1.4, 0, 1); }
}

/* Inner elements animations */

@-webkit-keyframes anim-elem-open {
	0% { opacity: 0; -webkit-transform: translate3d(-100px, 0, 0); }
	20% { opacity: 0; -webkit-transform: translate3d(-100px, 0, 0); }
	100% { opacity: 1; -webkit-transform: translate3d(0, 0, 0); }
}

@keyframes anim-elem-open {
	0% { opacity: 0; -webkit-transform: translate3d(-100px, 0, 0); transform: translate3d(-100px, 0, 0); }
	20% { opacity: 0; -webkit-transform: translate3d(-100px, 0, 0); transform: translate3d(-100px, 0, 0); }
	100% { opacity: 1; -webkit-transform: translate3d(0, 0, 0); transform: translate3d(0, 0, 0); }
}

@-webkit-keyframes anim-elem-close {
	0% { opacity: 1; -webkit-transform: translate3d(0, 0, 0); }
	100% { opacity: 0; -webkit-transform: translate3d(-100px, 0, 0); }
}

@keyframes anim-elem-close {
	0% { opacity: 1; -webkit-transform: translate3d(0, 0, 0); transform: translate3d(0, 0, 0); }
	100% { opacity: 0; -webkit-transform: translate3d(-100px, 0, 0); transform: translate3d(-100px, 0, 0); }
}
/* The Close Button */
.close-feature {
    color: white;
    float: right;
    font-weight: bold;
    position: absolute;
    right: 10px;
    
}

.close-feature:hover,
.close-feature:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

.feature-header {
    height: 73px;
    padding: 2px 16px;
    background-color: #003D6D;
    color: white;
        border-top-right-radius: 5px;
        border-top-left-radius: 5px; 
}

.feature-body {padding: 2px 2px;

}
    
/*Left*/
  .modaly.left-feat.fade .modal-dialog{
    left: -320px;
    -webkit-transition: opacity 0.3s linear, left 0.3s ease-out;
       -moz-transition: opacity 0.3s linear, left 0.3s ease-out;
         -o-transition: opacity 0.3s linear, left 0.3s ease-out;
            transition: opacity 0.3s linear, left 0.3s ease-out;
  }
  
  .modaly.left-feat.fade.in .modal-dialog{
    left: 0;
  }
.feature-widget .modal-dialog
  {
    padding-top: 25px;
    position: fixed;
    margin: auto;
    width: 112px;
    height: 595px;
    -webkit-transform: translate3d(0%, 0, 0);
        -ms-transform: translate3d(0%, 0, 0);
         -o-transform: translate3d(0%, 0, 0);
            transform: translate3d(0%, 0, 0);
  }

  .feature-widget .modal-content
  {
    height: 100%;
    overflow-y: hidden;
    overflow-x: hidden;


  }
  
  .feature-widget .modal-body
  {
    padding: 10px 10px;
  }
    
/* ----- MODAL STYLE ----- */
  .feature-widget {
    border-radius: 10px;
    border: none;
  }
    
.btn-left{
border:1px solid #40a54e;font-size:12px;padding: 10px 0px 10px 0px; text-decoration:none; display:inline-block;font-weight:bold; color: #FFFFFF;
 background-color: #A5B8DA; background-image: -webkit-gradient(linear, left top, left bottom, from(#A5B8DA), to(#7089B3));
 background-image: -webkit-linear-gradient(top, #5ddb6f, #48ba58);
 background-image: -moz-linear-gradient(top, #5ddb6f, #48ba58);
 background-image: -ms-linear-gradient(top, #5ddb6f, #48ba58);
 background-image: -o-linear-gradient(top, #5ddb6f, #48ba58);
 background-image: linear-gradient(to bottom, #5ddb6f, #48ba58);filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr=#5ddb6f, endColorstr=#48ba58);
}

.btn-left:hover{
 border:1px solid #40a54e;
 text-decoration:none; display:inline-block;font-weight:bold; color: #FFFFFF;
 background-color: #56c866; background-image: -webkit-gradient(linear, left top, left bottom, from(#819bcb), to(#40a54e));
 background-image: -webkit-linear-gradient(top, #56c866, #40a54e);
 background-image: -moz-linear-gradient(top, #56c866, #40a54e);
 background-image: -ms-linear-gradient(top, #56c866, #40a54e);
 background-image: -o-linear-gradient(top, #56c866, #40a54e);
 background-image: linear-gradient(to bottom, #56c866, #40a54e);filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr=#56c866, endColorstr=#40a54e);
}
    
    .calendly-badge-content{
        z-index: 99999999999 !important;
    }
    
    .ldBar-label {
        display: none;
    }
    
    #feat_next{
        position: absolute;
        top: 277px;
        right: 0px;
        width: 35px;
    }
</style>

<style>
/*DONNA CSS----------------*/
.dialog__overlay {
	-webkit-transition-duration: 1s;
	transition-duration: 1s;
}

.dialog.dialog--open .dialog__content,
.dialog.dialog--close .dialog__content {
	-webkit-animation-duration: 1s;
	animation-duration: 1s;
	-webkit-animation-fill-mode: forwards;
	animation-fill-mode: forwards;
	-webkit-animation-timing-function: cubic-bezier(0.7,0,0.3,1);
	animation-timing-function: cubic-bezier(0.7,0,0.3,1);
}

.dialog.dialog--open .dialog__content {
	-webkit-animation-name: anim-open;
	animation-name: anim-open;
}

.dialog.dialog--close .dialog__content {
	-webkit-animation-name: anim-close;
	animation-name: anim-close;
}

.dialog.dialog--open h2,
.dialog.dialog--open button {
	-webkit-animation: anim-elem-open 1s cubic-bezier(0.7,0,0.3,1) both;
	animation: anim-elem-open 1s cubic-bezier(0.7,0,0.3,1) both;
}

.dialog.dialog--close h2,
.dialog.dialog--close button {
	-webkit-animation: anim-elem-close 1s cubic-bezier(0.7,0,0.3,1) both;
	animation: anim-elem-close 1s cubic-bezier(0.7,0,0.3,1) both;
}



/*DONNA CSS----------------*/
</style>
  <!-- Trigger/Open The Modal -->


<!--FORECAST MODAL INTRO-->
<div class="modal fade modalDemoCustom" id="modalForecast" data-thisindex="1" data-backdrop="false" style="z-index:1999 !important;">
    <div class="modal-dialog" style="width:700px;margin-top:213px;">
        <div class="modal-content">
            <div class="modal-body" style="border-radius: 0px;">
                <button type="button" class="close" data-dismiss="modal" style="font-size: 35px; margin-top: -10px; color: unset;" onclick="triggerExitModal()">&times;</button>
                <h2 style="font-family: Gotham-Bold; color: #003d6d; text-align: center; margin-top: 30px; margin-bottom: 20px;">
<!--
                    This form contains your project's breakdown, budget and estimated amount for each of your supplier/subcontractor, and expenses. 
                    <br> <br> 
-->
                    This form is filled with sample data <br> that you can play around with.</h2>
                <div style="text-align: center; margin-top: 10px; margin-bottom: 10px;">
                    <div class="row" style="padding: 0px 20px;">
<!--
                        <div class="col-sm-8">
                            <div class="ldBar" data-value="50" style="width: 100%; margin-top: 10px;" data-preset="line" data-aspect-ratio="none" data-stroke-width="5" data-stroke="#003d6d"></div>
                        </div>
-->
                        <div class="col-sm-12">
                            <button type="button" onclick="startCountdownFeature();countdownForecast();" data-toggle="collapse" href="#collapse_1" aria-expanded="false" class="btn btnCustomSuccess btnCustomSm" data-dismiss="modal" style="font-size: 11pt; min-width: 155px; width: 100%; max-width:250px;" id="forecast_try">Let's give it a try</button>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--FORECAST MODAL INTRO-->

<!--FORECAST EXIT-->
<div class="modal fade" id="modalDemoExitForecast" data-thisindex="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" style="font-size: 35px; margin-top: -10px;" onclick="continueForecast();">&times;</button>
                <h2 style="font-family: Gotham-Bold; color: #003d6d; text-align: center; margin-top: 30px; margin-bottom: 20px;">This walkthrough is the fastest way to learn Small Builders</h2>
                <div style="text-align: center;">
                    <button type="button" onclick="continueForecast();" class="btn btnCustomSuccess btnCustomMd" style="font-size: 11pt; width: 80%" data-dismiss="modal">Okay, let's continue with the quick walkthrough</button>
                </div>
                <div style="text-align: center; margin-top: 15px; margin-bottom: 15px;">
                    <button type="button" class="btn btnCustomDefault btnCustomMd" style="font-size: 11pt; width: 80%;" data-dismiss="modal" id="btnForecastExit">Thanks, but I'd like to explore it on my own</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--FORECAST EXIT-->

<button id="myBtn" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myFeature" style="display:none;">Guide</button>
<!--<button id="myBtnn" class="btn btn-info btn-lg" onclick="openTraining();">Demo</button>-->

<div  class="ui-widget-content feature-widget" style="position:fixed; top:100px; left:0px; z-index:999999999;display:none;">
   <div class="left-feat" role="document" style="left: 0px;height:550px;border-bottom-right-radius: 10px;">
      <div class="feature-content2" id="myWidget" style="background-color:#003d6d; width: 100px;height: 550px;border-bottom-right-radius: 10px;" >

<button type="button" class="bookADemo btn-left"  style="    border-top-right-radius: 8px;width:100%; background-color:#3EDE41;font-family: Gotham-Bold !important;" onclick="openTrainingFeature();">Book a Demo</button>

        <div class="feature-body" style="text-align: center;padding-top: 10px;cursor:pointer" onclick="showFeature();">
        <span id="progress_number" color="white" style="font-size:22px; font-family: Gotham-Bold !important;margin-bottom:20px;color:white;font-weight:700;"><?php echo $feat_counter; ?>/6</span><br>
        <span color="white" style="font-size:13px; font-family: Gotham-Medium !important; display:block;color:white;margin-top:0px;">Tasks</span>
        <span color="white" style="font-size:13px; font-family: Gotham-Medium !important; display:block;color:white;margin-bottom:10px;">Completed</span>
        <img src="sb_tools/feature/<?php echo $feat_counter+1; ?>.png" id="progress_bar" style="width:106px; ">
        <img src="sb_tools/feature/arrow.png" id="feat_next" style="display:none;">
        </div>
      </div><!-- modal-content -->
    </div>
</div>


<!-- The Modal -->
<div class="dialog">
    <div id="myFeature" class="modal modaly dialog__overlay" data-backdrop="static" data-keyboard="false">

  <!-- Modal content -->
  <div class="modal-content feature-content dialog__content" id="center_feat" style="z-index:9999999999;">
    <div class="modal-header feature-header" style="margin-top:20px;">
      <button type="button" class="close close-feature" id="close" onclick="closeFeat();"><img src="sb_tools/feature/exit_white.png" style="width:19px;padding-top:10px;"></button>
      <center><h2><font style="font-size:25px; font-family: Gotham-Medium !important;">Small Builders Guide</font></h2></center>
    </div>
    <div class="modal-body feature-body">
      <center><h1><font color="00518E" style="font-size:25pt; font-family: Gotham-Bold !important;"><span id="walk_header"><?php echo $text_header; ?></span></font></h1></center>
      <center id="walk_container" style="<?php echo $display_sub_header;?>"><font color="00518E" style="font-size:<?php echo $font_size;?>; font-family: Gotham-Medium !important;">The <span id="walk_name"><?php echo $feat_name;?></span> walkthrough ends here.</font></center>
      <center><h3 style="margin-top:0px;<?php echo $display_sub_text1;?>" id="walk_sub1"><font color="00518E" style="font-size:14pt; font-family: Gotham-Medium !important;">Now, find out how you can customise Small Builders to your needs!</font></h3></center>
      <center><h3 style="margin-top:0px;<?php echo $display_sub_text2;?>" id="walk_sub2"><font color="00518E" style="font-size:15pt; font-family: Gotham-Medium !important;">Find out how you can customise Small Builders to your needs</font></h3></center>
      
	  <?php
	  	$queryFindFromDemoCheck = "SELECT * 
							  FROM tbl_demoscript_checker 
							  WHERE client_id = '".$val_ci."'
							  AND visit_achievables = '1'";
		$executedQueryFindFromDemoCheck = mysqli_query($theodore_con, $queryFindFromDemoCheck);
		if(mysqli_num_rows($executedQueryFindFromDemoCheck) > 0){
			$ach_setupBtn = "none";
			$ach_bookBtn = "block";
		}else{
			$ach_setupBtn = "block";
			$ach_bookBtn = "none";
		}
		/* $ach_setupBtn = "none";
		$ach_bookBtn = "block"; */
	  ?>
	  
	  <center><button type="button" class="bookADemo btn btnCustomSuccess" style="display:<?php echo $ach_setupBtn; ?>;width:280px; background-color:#3EDE41; font-family: Gotham-Bold !important;" data-dismiss="modal" onclick="ach_redirect()"><b>Set Up My Business Portal</b></button></center>
	  
	  
	  <center><button type="button" class="bookADemo btn btnCustomSuccess" style="display:<?php echo $ach_bookBtn; ?>;width:280px; background-color:#3EDE41; font-family: Gotham-Bold !important;" data-dismiss="modal" onclick="openTrainingFeature();"><b>Book a Demo</b></button></center>
	  
      <center><h3 style="margin-top:5px;margin-bottom:0px;"><font color="00518E" style="font-size:14pt; font-family: Gotham-Medium !important;">or</font></h3></center>
      <center><h3 style="margin-top:0px;"><font color="00518E"style="font-size:14pt; font-family: Gotham-Medium !important;">Choose a feature you want to learn next</font></h3></center>

      <center>
    
    <?php
          
//        $form_count = 1;
//        $current_image = "";
        $features = array('timesheet','expense','pcs','site diary','ceo report', 'forecast');
        $pages = array(
            'timesheet.php',
            'expenseform.php',
            'paymentclaimsystem.php',
            'sitediary.php',
            'dashboard_projects.php',
            'forecast_report.php'
        );
        foreach ($columns as $key => $col){
            
            if ($fetch_select['fs_'.$col] == "1"){ 
                //------- IF FEATURE IS CHECKED -----------
                $feature_pic = $features[$key]=='ceo report'? 'ceo':$features[$key];
                $click_function = '';
                if ($key == 0 || $key == 1 || $key == 2 || $key == 3 || $key == 5) {
                    $click_function = 'onclick="window.open('."'".asset_host().'/builder/portal/'.$pages[$key].
                        '?ui='.$_GET['ui'].
                        '&ci='.$_GET['ci'].
                        '&pid='.$_GET['pid'].
                        '&usr_stat='.base64_encode('demo').
                        "'".','."'".'_self'."'".')"';
                }else if ($key==4){
                    $click_function = 'onclick="
                        closeFeat();
                        redirectPage('."'".'ceo'."'".');
                    "';
                }
                else if($key==5){
                    $click_function = 'onclick="
                        closeFeat();
                        redirectPage('."'".'forecast'."'".');
                    "';
                }
                
                else {
                    $click_function = 'onclick="window.open('."'".asset_host().'/builder/portal/'.$pages[$key].
                        '?ui='.$_GET['ui'].
                        '&ci='.$_GET['ci'].
                        '&pid='.$_GET['pid'].
                        '&ft='.base64_encode($col).
                        "'".','."'".'_self'."'".')"';
                }
                
                echo '<img id="'.$feature_pic.'_pic" src="sb_tools/feature/'.$features[$key].'3.png"
                style="width:180px;height:129px;cursor:pointer;" '.$click_function.' hspace="20">';
            }
            else {    
                //------- IF FEATURE IS *NOT* CHECKED -----------
                if ($key == 0 || $key == 1 || $key == 2 || $key == 3) {
                    echo '<img src="sb_tools/feature/'.$features[$key].'1.png"
                    onmouseover="this.src='."'".'sb_tools/feature/'.$features[$key].'2.png'."'".'"
                    onmouseout="this.src='."'".'sb_tools/feature/'.$features[$key].'1.png'."'".'"
                    onclick="window.open('."'".asset_host().'/builder/portal/'.$pages[$key].
                        '?ui='.$_GET['ui'].
                        '&ci='.$_GET['ci'].
                        '&pid='.$_GET['pid'].
                        '&usr_stat='.base64_encode('demo').
                        "'".','."'".'_self'."'".')"
                    style="width:180px;height:129px;cursor:pointer;" hspace="20">';
                } else if ($key==4){
                    echo '<img id="ceo_pic" src="sb_tools/feature/'.$features[$key].'1.png"
                    onmouseover="this.src='."'".'sb_tools/feature/'.$features[$key].'2.png'."'".'"
                    onmouseout="this.src='."'".'sb_tools/feature/'.$features[$key].'1.png'."'".'"
                    onclick="
                        closeFeat();
                        redirectPage('."'".'ceo'."'".');
                    "
                    style="width:180px;height:129px;cursor:pointer;" hspace="20">';
                }
                else if($key==5){
                    // echo '<img id="forecast_pic" src="sb_tools/feature/'.$features[$key].'1.png"
                    // onmouseover="this.src='."'".'sb_tools/feature/'.$features[$key].'2.png'."'".'"
                    // onmouseout="this.src='."'".'sb_tools/feature/'.$features[$key].'1.png'."'".'"
                    // data-backdrop="static"
                    // data-keyboard="false"
                    // onclick="
                    //     closeFeat();
                    //     redirectPage('."'".'forecast'."'".');
                    // "
                    // style="width:180px;height:129px;cursor:pointer;" hspace="20">';
                    echo '<img id="forecast_pic" src="sb_tools/feature/'.$features[$key].'1.png"
                    onmouseover="this.src='."'".'sb_tools/feature/'.$features[$key].'2.png'."'".'"
                    onmouseout="this.src='."'".'sb_tools/feature/'.$features[$key].'1.png'."'".'"
                    onclick="window.open('."'".asset_host().'/builder/portal/'.$pages[$key].
                        '?ui='.$_GET['ui'].
                        '&ci='.$_GET['ci'].
                        '&pid='.$_GET['pid'].
                        '&usr_stat='.base64_encode('demo').
                        "'".','."'".'_self'."'".')"
                    style="width:180px;height:129px;cursor:pointer;" hspace="20">';
                }
//                if($key==4||$key==5){
//                    $current_feature = $key==4? "CEO Report":"Forecast";
//                    echo '<img src="sb_tools/feature/'.$features[$key].'1.png"
//                    onmouseover="this.src='."'".'sb_tools/feature/'.$features[$key].'2.png'."'".'"
//                    onmouseout="this.src='."'".'sb_tools/feature/'.$features[$key].'1.png'."'".'"
//                    onclick="window.open('."'".asset_host().'/builder/portal/'.$pages[$key].
//                        '?ui='.$_GET['ui'].
//                        '&ci='.$_GET['ci'].
//                        '&pid='.$_GET['pid'].
//                        '&feat='.base64_encode($current_feature).
//                        "'".','."'".'_self'."'".')"
//                    style="width:180px;height:129px;cursor:pointer;" hspace="20">';
//                }
                else {
                    echo '<img src="sb_tools/feature/'.$features[$key].'1.png"
                    onmouseover="this.src='."'".'sb_tools/feature/'.$features[$key].'2.png'."'".'"
                    onmouseout="this.src='."'".'sb_tools/feature/'.$features[$key].'1.png'."'".'"
                    onclick="window.open('."'".asset_host().'/builder/portal/'.$pages[$key].
                        '?ui='.$_GET['ui'].
                        '&ci='.$_GET['ci'].
                        '&pid='.$_GET['pid'].
                        '&ft='.base64_encode($col).
                        "'".','."'".'_self'."'".')"
                    style="width:180px;height:129px;cursor:pointer;" hspace="20">';
                }
            }
            
            if($key==2){
                echo '<br><br><br>';
            }
//            $form_count ++;
        }
    ?>
<!--
      <img src="sb_tools/feature/timesheet1.png" 
     onmouseover="this.src='sb_tools/feature/timesheet2.png'"
     onmouseout="this.src='sb_tools/feature/timesheet1.png'" 
      style="width:180px;height:129px;" hspace="20">

      <img src="sb_tools/feature/expense1.png" 
     onmouseover="this.src='sb_tools/feature/expense2.png'"
     onmouseout="this.src='sb_tools/feature/expense1.png'" 
      style="width:180px;height:129px;" hspace="20">

      <img src="sb_tools/feature/pcs1.png" 
     onmouseover="this.src='sb_tools/feature/pcs2.png'"
     onmouseout="this.src='sb_tools/feature/pcs1.png'" 
      style="width:180px;height:129px;" hspace="20">
-->

<!--
<br>
<br>
<br>
-->

<!--
      <img src="sb_tools/feature/site diary1.png" 
     onmouseover="this.src='sb_tools/feature/site diary2.png'"
     onmouseout="this.src='sb_tools/feature/site diary1.png'" 
      style="width:180px;height:129px;" hspace="20">

      <img src="sb_tools/feature/ceo report1.png" 
     onmouseover="this.src='sb_tools/feature/ceo report2.png'"
     onmouseout="this.src='sb_tools/feature/ceo report1.png'" 
      style="width:180px;height:129px;" hspace="20">

      <img src="sb_tools/feature/forecast1.png" 
     onmouseover="this.src='sb_tools/feature/forecast2.png'"
     onmouseout="this.src='sb_tools/feature/forecast1.png'" 
      style="width:180px;height:129px;" hspace="20">
-->

      </center>
        <br><br>
    </div>
  </div>



    </div>
</div>
<div class="modal fade" id="modal-sectionForecast" role="dialog">
    <div class="modal-dialog" style="width:97%; <?php echo $is_demo? "width:90%;margin-left:75px;":""; ?>"  >
        <div class="modal-content">
            <div class="modal-header">
                <div class="pull-right">
                    <a href="forecast_report.php<?php echo $param_ ?>" target="_blank" class="btn btn-primary btn-md">Open window in new tab &nbsp;<i class="fa fa-external-link" aria-hidden="true"></i></a>&emsp;
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <h4 class="modal-title">Forecast Report</h4>
            </div>
            <div class="modal-body" style="padding: 15px 15px 0px 15px;">
                <div id="" class="form-group" style="margin-bottom:10px;">
                    <table style="width:100%;">
                        <tr>
                            <td style="width:1%; vertical-align:top;" nowrap="">
                                <label style="margin: 0px 5px 0px 0px;">Project Name:</label>
                            </td>
                            <td style="width:99%;">
                                <section id="" style="font-size:14px;"><?php echo $JProjectName140430; ?></section>
                            </td>
                        </tr>
                    </table>
                </div>
                <section id="forecast_main_content">
                    <table style='width:100%;'>
                        <tbody>
                            <tr><td align='center'>Please wait...</td></tr>
                            <tr><td align='center'><img src='<?php echo asset_host(); ?>/builder/portal/controller/formsource/dashboard_graph/loadingAnimation.gif'></td></tr>
                        </tbody>
                    </table>
                    <br>
                </section>
            </div>
            <div class="modal-footer">
                <table style="width:100%;">
                    <tr>
                        <td align="left">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    };
    
    $(document).ready(function(){
<?php
    
    if ($feat_start=="1"){
        echo "$('#myFeature').modal('show');";
        echo "$('.feature-widget').attr('style','position:fixed; top:100px; left:0px; z-index:999999999;display:block;');";
    }
    
    if (isset($_GET['ft']) && !empty($_GET['ft'])){
        echo 'checkPage("'.base64_decode($_GET['ft']).'")';
    }

    if (empty($_GET['ft'])&&empty($feat_start)&&($is_demo)&&$phpFileName=="dashboard_projects"){
        echo 'showWidget();';
        echo 'showFeature();';
    }
        
    if (!empty($_GET['usr_stat'])){
        echo '$("#myWidget").hide();';
        echo '$("#myFeature").modal("hide");';
    }
?>
    });

    function continueForecast(){
        $('#modalForecast').modal('show');
        $('#modalForecast').attr('style','z-index:9999 !important; display: block; padding-left: 0px;');
    }
    
    $('#btnForecastExit').click(function(){
        demoDataCaptureFunnel("Forecast", "Aha", "Project Page", "Forecast Pop up 1", "Exit Onboarding");
    });
    
    var global_checker = false;
    var global_ft = getUrlParameter('ft')? window.atob(getUrlParameter('ft')):"";
    var global_pid = getUrlParameter('pid');
    
    function ach_redirect(){
		window.location.href = "onboarding_achievables.php?ui=<?php echo $_GET['ui']; ?>&ci=<?php echo $_GET['ci']; ?>";
	}
	
    function redirectPage(feature){
        var ft = window.btoa(feature);
        window.open("<?php 
                    echo asset_host().'/builder/portal/dashboard_projects.php'.
                    '?ui='.$_GET['ui'].
                    '&ci='.$_GET['ci'].
                    '&pid='.$_GET['pid'];?>&ft="+ft,"_self"
                   );
    }
    
    function checkPage(feature){
            if(feature=="ceo"){
                $('#modal-sectionForecast').modal('hide');
                getdetails(6, 'yes');
                // closeWidget();
                // setTimeout(function(){
                //     showDemoCEOReport('yes');
                // }, 2000);
            }
            else if(feature=="timesheet"){
                setTimeout(function(){
                    showDemoTimesheet('yes');
                }, 2000);
            }
            else if(feature=="expense"){
                setTimeout(function(){
                    showDemoExpense('yes');
                }, 2000);
            }
            else if(feature=="payment"){
                setTimeout(function(){
                    showDemoPaymentClaim('yes');
                }, 2000);
            }
            else if(feature=="site"){
                setTimeout(function(){
                    showDemoSiteDiary('yes');
                }, 2000);
            }
            else{
                getdetails(1);
                global_checker = true;
                closeWidget();
                setTimeout(function(){
                    showDemoForecast('yes');
                }, 2000);
            }
    }
    
    function startCountdownFeature(){
        setTimeout(function(){ 
            $('#myFeature').removeClass('feature-close');
            $('#myFeature').modal('show'); 
            $('#feat_next').hide();
            $('#myFeature').attr('style','z-index:9999999 !important; display: block; padding-left: 0px;');
            $('.feature-widget').attr('style',"position:fixed; top:100px; left:0px; z-index:999999999;display:block;");
            $('#myWidget').fadeIn();
        }, 5000);
    }
    function closeFeat(){
        $('#feat_next').show();
        $('#myFeature').addClass('feature-close');
        setTimeout(function(){
                    $('#myFeature').modal('hide');
                }, 400);
    }
    
    function submitCEO(){
        $.post("sb_tools/feature/update_feature_selection_table.php", {
            feature:"ceo",
            dec_ui:<?php echo base64_decode($_GET['ui']);?>,
            dec_ci:<?php echo base64_decode($_GET['ci']);?>
        });
        if($('#ceo_pic').attr("src")!="sb_tools/feature/ceo report3.png"){
            $('#ceo_pic').attr("src","sb_tools/feature/ceo report3.png");
            $('#ceo_pic').attr("onmouseout","");
            $('#ceo_pic').attr("onmouseover","");
            $('#progress_bar').attr("src","sb_tools/feature/<?php echo $feat_counter==6? 6:$feat_counter+2; ?>.png");
            $('#progress_number').html("<?php echo $feat_counter==6? 6:$feat_counter+1; ?>/6");
        }
        $('#walk_header').html("Thanks, <?php echo $_user_firstname;?>!");
        $('#walk_name').html("CEO Report");
        $('#walk_container').show();
        $('#walk_sub1').show();
        $('#walk_sub2').hide();
    }
    
    function submitForecast(){
        $.post("sb_tools/feature/update_feature_selection_table.php", {
            feature:"forecast",
            dec_ui:<?php echo base64_decode($_GET['ui']);?>,
            dec_ci:<?php echo base64_decode($_GET['ci']);?>
        });
        if($('#forecast_pic').attr("src")!="sb_tools/feature/forecast3.png"){
            $('#forecast_pic').attr("src","sb_tools/feature/forecast3.png");
            $('#forecast_pic').attr("onmouseout","");
            $('#forecast_pic').attr("onmouseover","");
            $('#progress_bar').attr("src","sb_tools/feature/<?php echo $feat_counter==6? 6:$feat_counter+2; ?>.png");
            $('#progress_number').html("<?php echo $feat_counter==6? 6:$feat_counter+1; ?>/6");
        }
        $('#walk_header').html("Thanks, <?php echo $_user_firstname;?>!");
        $('#walk_name').html("Forecast Tool");
        $('#walk_container').show();
        $('#walk_sub1').show();
        $('#walk_sub2').hide();
    }
    
    function submitExpense(){
        $.post("sb_tools/feature/update_feature_selection_table.php", {
            feature:"expense",
            dec_ui:<?php echo base64_decode($_GET['ui']);?>,
            dec_ci:<?php echo base64_decode($_GET['ci']);?>
        });
    }
    
    var isForecast = false;
    function getContent_Default_ForecastContent() {
        
        if(global_ft=="forecast"){
            if (!isForecast){
                $('#modalForecast').modal('show');
                $('#modalForecast').attr('style','z-index:9999 !important; display: block; padding-left: 0px;');
            }
            removeDashboardProject();
            
        }
        else {
            if (!isForecast&&(<?php echo ($fetch_select['fs_forecast']==0)||(is_null($fetch_select['fs_forecast']))? "false": "true"; ?>)){
                $('#modalForecast').modal('show');
                $('#modalForecast').attr('style','z-index:9999 !important; display: block; padding-left: 0px;');
            }
        }
        <?php 
            if ($is_demo){
        ?>     
            demoDataCaptureFunnel("Forecast", "Set Up", "Project Page", "Forecast Pop up 1", "Viewed Step");
            submitForecast();
        <?php
            }
        ?>
        console.log(global_checker);
//        console.log(global_ft);
        console.log(isForecast);
        
        <?php
//            if ($fetch_select['fs_forecast']!=1||base64_decode($_GET['ft'])=="forecast"){
//                echo "$('#modalForecast').modal('show');";
//                echo "$('#modalForecast').attr('style','z-index:9999 !important; display: block; padding-left: 0px;');";
//            }
        ?>
        
        $('#forecast_main_content').html("<table style='width:100%;'> <tbody> <tr><td align='center'>Please wait...</td></tr> <tr><td align='center'><img src='<?php echo asset_host(); ?>/builder/portal/controller/formsource/dashboard_graph/loadingAnimation.gif'></td></tr> </tbody> </table> <br>");
        $.post("controller/formsource/forecast_report/index_for_projectsfolder.php", {
            prClientId: "<?php echo $dec_ci; ?>",
            prUserId: "<?php echo $dec_ui; ?>",
            prProjectId: "<?php echo $projectID; ?>",
            prProjectName: "<?php echo ''; ?>",
            prUsername: "<?php echo $valUserName; ?>",
            <?php echo $is_demo? 'prIsDemo: "yes",':''; ?>
        }).done(function(response){ 
            $('#forecast_main_content').html(response);
            getExpenseForecast();
        }).fail(function(xhr, status) {
            alert("Oops, something went wrong. Try again your action or refresh the page.");
            $("#forecast_main_content").html('');
        });
        $('#walk_header').html("Thanks, <?php echo $_user_firstname;?>!");
        $('#walk_name').html("Forecast Tool");
        $('#walk_container').show();
        $('#walk_sub1').show();
        $('#walk_sub2').hide();
        
        isForecast = true;
    }

    function showForecastModal(){
        $('#modalForecast').modal('show');
        submitForecast();
    }
    
    function triggerExitModal(){
        $('#modalDemoExitForecast').modal('show');
    }
    
    function showFeature(){
        $('#myFeature').attr('style','z-index:9999999 !important; display: block; padding-left: 0px;');
        $('#myFeature').removeClass('feature-close');
        $('#myFeature').modal('show'); 
        $('#feat_next').hide();
    }
    
    function openTrainingFeature(){
        var feature = getFeature();
        var page = getCurrentPage();
        console.log(feature+" - "+page);
        demoDataCaptureFunnel(feature, "Post Aha", page, "Booked Demo", "Submit");
        $('#myFeature').modal('hide'); 
        $('#feat_next').show();
        $('.calendly-badge-content').click();
    }
    
    function getFeature(){
        <?php 
            $get_feat = base64_decode($_GET['feat'])=="Payment Claim System"? "PCS":base64_decode($_GET['feat']);
            $get_feat = $get_feat=="Site Diary"? $get_feat:explode(' ', trim($get_feat))[0]; 
            $get_ft = base64_decode($_GET['ft'])=="ceo"? "CEO":ucfirst(base64_decode($_GET['ft']));
        ?>
        var ft = "<?php echo isset($_GET['ft'])? $get_ft:$get_feat; ?>";
        return ft.length>1? ft:"Preparation Phase";
    }
    
    function getCurrentPage(){
        var page = "<?php echo $phpFileName;?>";
        switch(page){
            case 'timesheet':
                return 'Timesheet';
                break;
            case 'timesheetreport':
                return 'Timesheet Report';
                break;
            case 'expenseform':
                return 'Expense Form';
                break;
            case 'expensereport':
                return 'Expense Report';
                break;
            case 'paymentclaimsystem':
                return 'Payment Claim';
                break;
            case 'pcs_dashboard':
                return 'Payment Claim Dashboard';
                break;
            case 'sitediary':
                return 'Site Diary';
                break;
            case 'site_diary_report':
                return 'Site Diary Report';
                break;
            case 'dashboard_projects':
                return 'Project Page';
                break;
            default:
                return 'Project Page';
        }
    }
    
    function showWidget(){
        $('.feature-widget').attr('style',"position:fixed; top:100px; left:0px; z-index:999999999;display:block;");
        $('#myWidget').show();
        $('#feat_next').show();
    }
    
    function closeWidget(){
        $('#myWidget').fadeOut();
    }
    
    $('#forecast_try').click(function(){
        startCountdownFeature();
        demoDataCaptureFunnel("Forecast", "Set Up", "Forecast Modal", "Explore Forecast Report", "Viewed Step");
    });
    
    function countdownForecast(){
        setTimeout(function(){
            executeCostcentreComputations(1); 
            saveInline(1, 1);
        }, 2000);

    }
//$('#myBtn').click(function(){
//    $.post("sb_tools/feature/update_feature_selection_table.php", {
//        feature:"site",
//        dec_ui:<?php echo base64_decode($_GET['ui']);?>,
//        dec_ci:<?php echo base64_decode($_GET['ci']);?>
//    }, function(e) {
//        
//    })
//});

//    $('.calendly-badge-content').attr('style','display:none !important;');
        
</script>


