<link rel="stylesheet" href="../../sb_css/Gotham.css">
<link rel="stylesheet" type="text/css" href="../../sb_css/onboarding_buttons.css">
<style>
/* Trial banner CSS */
	#trialBannerMin {
		display:none;
		position: fixed;
		z-index: 999;
		right: -125px;
		-moz-transition: right 1s ease;
		-webkit-transition: right 1s ease;
		-o-transition: right 1s ease;
		transition: right 1s ease;
		cursor:pointer;
	}
	#trialBannerMin:hover{
		right: 0px;
	}

	.btnBannerGrn{
		border-radius: 8px!important;
		padding: 4px 13px!important;
		margin-left: 10px;
		font-size: 12px!important;
	}
	
	.arrow-left-one{
		width: 0;
		height: 0;
		border-top: 96px solid #00adef;
		border-right: 250px solid transparent;
		position: absolute;
		left: 0;
		top: 0;
	}
	.arrow-left{
		width: 0;
		height: 0;
		border-top: 46px solid #003d6d;
		border-right: 125px solid transparent;
		position: absolute;
		left: 0;
		top: 0;

	}
	.slantTrialText{
		position: absolute;
		left: 26px;
		top: 24px;
		transform: rotate(-45deg);
		-webkit-transform: rotate(-22deg);
		color: white;
		font-family: Gotham-Bold;
		font-size: 20px;
	}	
	#trialBannerMaxDiv{
		position:relative;
		z-index:10;
		top:unset;
		box-shadow:none;
	}
/* Trial banner CSS */
</style>

      <!--------------------------------------------- Start Trial Banner ----------------------------------------------------->
		<?php

			$client_is_paid = 0;
			$user_is_new = 0;
			$trial_date_extended = "0000-00-00";
			$date_extended_trial = "0000-00-00";
			$date_registered = "0000-00-00";
			$date_registered_set = "0000-00-00";
			$trial_days_left = 0;
			$date_today = date("Y-m-d");
			$date_reg_diff = -1;
			$hide_banner = 1;
			/* if($scroll_on_page != 1){
				$scroll_on_page = 0;
			} */
			
			$date_registered_query_one ="SELECT date_registered, date_extended_trial, days_extension FROM trial_registration WHERE client_id = ".$dec_ci;
			$get_date_registered_query_one = mysqli_query($csportal_con, $date_registered_query_one);
			if(mysqli_num_rows($get_date_registered_query_one)!=0){
				$row = mysqli_fetch_array($get_date_registered_query_one);
				extract($row);
				
				
				if($date_extended_trial == "0000-00-00"){
					$date_registered_set = $date_registered;
					$days_extension = 6;
				}else{
					$date_registered_set = strtotime($date_extended_trial. ' - 8 days');
					$days_extension = (int)$days_extension-1;
				}
				$trial_date_extended = $date_extended_trial;
				$date_reg_diff = date('Y-m-d',strtotime($date_registered_set) - 1538661600); //October5
				

				if(isset($_GET['reg_date']) && $_GET['reg_date'] != ''){
					$date_registered_set = $_GET['reg_date'];
				}
				
				if(isset($_GET['extend_date']) && $_GET['extend_date'] != ''){
					$trial_date_extended= $_GET['extend_date'];
				}
				
				if(isset($_GET['date_today']) && $_GET['date_today'] != ''){
					$date_today= $_GET['date_today'];
				}
				
				if(isset($_GET['days_extension']) && $_GET['days_extension'] != ''){
					$days_extension= $_GET['days_extension'];
					$date_registered_set = strtotime($date_extended_trial. ' - 8 days');
					$days_extension = (int)$days_extension-1;
				}
				
			}

			if($date_reg_diff >= 0){
				$user_is_new = 1;
				$query = 
					"SELECT *  
					FROM trial_creditcard_details
					WHERE client_id = ".$dec_ci;
				$executedQuery = mysqli_query($csportal_con, $query);
				if(mysqli_num_rows($executedQuery)!=0){
					$client_is_paid = 1;
				}else{
					$query = 
						"SELECT *  
						FROM cs_clients_premium
						WHERE client_id = ".$dec_ci;
					$executedQuery = mysqli_query($csportal_con, $query);
					if(mysqli_num_rows($executedQuery)!=0){
						$client_is_paid = 1;
					}
				}
				
				if($stage == 'Upgraded' || isset($_GET['upgraded'])){ //manual parameter for QA purposes
					$client_is_paid = 1;
				}
				
				if($trial_date_extended != "0000-00-00"){
					$diffReg = strtotime($trial_date_extended) - strtotime($date_registered_set. ' + '.$days_extension.' days');
					
					if($diffReg <= 0){
						//echo "<script>console.log('#9A".$diffReg."');</script>";
						$date_registered_set = date('Y-m-d', strtotime($date_registered_set. ' + '.$days_extension.' days'));
					}else{
						$date_registered_set = date('Y-m-d', strtotime($trial_date_extended));
					}
					$get_date = $date_registered_set;
				}
				$check_date_reg = strtotime($date_registered_set);
				$check_date_today = strtotime($date_today);
				$datediff = $check_date_today - $check_date_reg;
				$nth_day_today = round($datediff / (60 * 60 * 24));
				$nth_day_today_plus_one = $nth_day_today + 1;
				$trial_days_left = (int)$days_extension-(round($datediff / (60 * 60 * 24)) -1);
				
				
				//echo "<script>console.log('#10A ".$date_registered_set." ".$date_today." ".(round($datediff / (60 * 60 * 24)) -1)."');</script>";
					
				if($date_registered_set == $date_today && $trial_date_extended == "0000-00-00"){
					$ribbonShows = 'display:block;';
					$daysLeftText = 'display:none;';
					$demoBtn = 'background: transparent!important;border: 1px white solid;';
					$demoBtnHover = 'onMouseOver="this.style.backgroundColor=\'#00294a\'" onMouseOut="this.style.backgroundColor=\'transparent\'"';
				}else{
					$ribbonShows = 'display:none;';
					$daysLeftText = 'display:inline-block;';
					$demoBtn = '';
					$demoBtnHover = '';
				}
				
				
				//-------- Check when to show trial banner ---------//
				$select = mysqli_query($theodore_con,"SELECT a.exit_onboarding
													  FROM smallbui_theodore.tbl_demoscript_checker AS a
													  LEFT JOIN smallbui_cs_portal.cs_users AS b
													  ON(b.user_id = a.user_id)
													  WHERE a.client_id = '".base64_decode($_GET['ci'])."'");
				if(mysqli_num_rows($select) > 0) {
					while($select_trial = mysqli_fetch_array($select)){
						extract($select_trial);
						if($exit_onboarding != 1){
							/* $selectFunnel = mysqli_query($theodore_con,"SELECT  * FROM onboarding_funnel WHERE user_id = '".base64_decode($_GET['ui'])."' AND action = 'Exit Onboarding'"); */
							/* if(mysqli_num_rows($selectFunnel) > 0) { //Show banner if user already exited the onboarding even once
								$hide_banner = 0;
								
								
								$saveOnboarding = mysqli_query($csportal_con, "UPDATE `tbl_demoscript_checker` SET exit_onboarding = '1' WHERE user_id = '".base64_decode($_GET['ui'])."'");
								
								echo "<script>console.log('1');</script>";
								
							}else  */
							
							if(isset($_GET['pid']) && $_GET['pid'] != ''){ //Hide banner if page has pid and if it's a project demo
								$query_project_demo = "SELECT _demo_project as is_demo FROM _submission_204 WHERE id=".base64_decode($_GET['pid']);
								$exe_project_demo = mysqli_query($theodore_con, $query_project_demo);
								$fetch_project_demo = mysqli_fetch_array($exe_project_demo);
								extract($fetch_project_demo);
								
								if($is_demo!='yes'){ 
									$hide_banner = 0;
									//echo "<script>console.log('1');</script>";
								}else{ //demo proj
									$selectFunnel = mysqli_query($theodore_con,"SELECT action,step FROM onboarding_funnel WHERE user_id = '".base64_decode($_GET['ui'])."' ORDER BY id DESC LIMIT 1"); 
									while($select_last=mysqli_fetch_array($selectFunnel)){
										extract($select_last);
									}
									
									if(($action == 'Exit Onboarding' || strpos($step, 'Completed') !== false) && !isset($_GET['ft']) && !isset($_GET['feat'])){
										$hide_banner = 0;
										//echo "<script>console.log('222');</script>";
									}
								}
								 
							}else if(!isset($_GET['fsign']) && !isset($_GET['ft']) && !isset($_GET['feat'])){ //Hide banner if it's on index and newly signed up
								//$hide_banner = 0;echo "<script>console.log('3');</script>";
							}
							
						}
					}
				}else{
					//echo "<script>console.log('1111');</script>";
					if($trial_days_left >= 1 && $trial_date_extended != "0000-00-00"){
						$hide_banner = 0;
					}
				}
				//-------- Check when to show trial banner ---------//
				
				
				
				$select = mysqli_query($theodore_con,"SELECT banner_stat FROM trial_banner_settings WHERE user_id = '".base64_decode($_GET['ui'])."'");
				
				if(mysqli_num_rows($select) > 0  && 
					(($trial_days_left >= 1 && $trial_days_left <= 8 && $trial_date_extended == "0000-00-00") || 
					($trial_days_left >= 1 && $trial_date_extended != "0000-00-00")) && 
					$client_is_paid == 0 && $user_is_new == 1 && $hide_banner == 0 && $_user_is_owner == 1) {
					while($select_trial=mysqli_fetch_array($select)){
						extract($select_trial);
						
						if($banner_stat == 'min' && !isset($_GET['login'])){
							echo "<script>
									$(document).ready(function() {
										$('#trialBannerMax').hide();
										$('#trialBannerMin').show();
										$('#bannerHolder').css({'height':'0'});
									});
								</script>";		
						}else{
							
							if($banner_stat == 'min' && isset($_GET['login'])){
								$addScript = 'maximizeBanner();';
							}else{
								$addScript = '';
							}
							
							echo "<script>
									$(document).ready(function() {
										".$addScript."
										if ($(document).height() > $(window).height()) {
											$('#trialBannerMax').css({'position':'relative', 'width':'99vw', 'z-index':'10', 'top':'unset','box-shadow':'none'});
										}else{
											$('#trialBannerMax').css({'position':'relative', 'width':'100vw', 'z-index':'10', 'top':'unset','box-shadow':'none'});
										}
									});
								</script>";	
														
						}
					}
				}else{
					$banner_stat = 'max';
					echo "<script>
							$(document).ready(function() {
								if ($(document).height() > $(window).height()) {
									$('#trialBannerMax').css({'position':'relative', 'width':'99vw', 'z-index':'10', 'top':'unset','box-shadow':'none'});
								}else{
									$('#trialBannerMax').css({'position':'relative', 'width':'100vw', 'z-index':'10', 'top':'unset','box-shadow':'none'});
								}
							});
						</script>";
				}
				
				
					
				$last_nth_num = substr($nth_day_today_plus_one, -1);
				
				switch ($last_nth_num) {
					case "1":
						$nth_day_today_word = $nth_day_today_plus_one.'st day';
						break;
					case "2":
						$nth_day_today_word = $nth_day_today_plus_one.'nd day';
						break;
					case "3":
						$nth_day_today_word = $nth_day_today_plus_one.'rd day';
						break;
					case ($last_nth_num >= 5 && $last_nth_num <= 9):
						$nth_day_today_word = $nth_day_today_plus_one.'th day';
						break;
					case "0":
						$nth_day_today_word = $nth_day_today_plus_one.'th day';
					
					default:
						$nth_day_today_word = $nth_day_today_plus_one.' day';
				}
			}
			
			$banner_width = '100vw';
		?>
		<div id="trialBannerMaxDiv">
			<div class="col-md-12" id="trialBannerMax" style="width:<?php echo $banner_width; ?>;display:none;font-family: Gotham-Light;position:relative;background-color:#003d6d;padding: 15px 2.5% 15px 1%;
			
			<?php if((($trial_days_left >= 1 && $trial_days_left <= 8 && $trial_date_extended == "0000-00-00") || 
					    ($trial_days_left >= 1 && $trial_date_extended != "0000-00-00")) && 
					    $client_is_paid == 0 && $user_is_new == 1 && 
					    ($banner_stat == 'max' || ($banner_stat == 'min' && $_GET['login'])) && $hide_banner == 0 && $_user_is_owner == 1){
							echo "display:block";
							$trial_stat = 1;
						}else{ 
							echo "display:none"; 
							$trial_stat = 0;
						} 
			?>">
				<div class="arrow-left-one" style="<?php echo $ribbonShows; ?>"></div>
				<div class="arrow-left" style="<?php echo $ribbonShows; ?>"></div>
				<div class="slantTrialText" style="<?php echo $ribbonShows; ?>">FREE TRIAL</div>
				
				<div class="col-lg-4 col-md-12 col-sm-12">
					<div style="position:relative;<?php echo $daysLeftText; ?>">
						<img style="height:65px;" src="<?php echo asset_host()."/builder/portal/sb_tools/feature/trial.png"; ?>"> 
						<span style="top:12px;<?php if($trial_days_left > 9){echo "left:20px;";}else{echo "left:25px;";} ?>position:absolute;color:white;font-size:28px;font-weight:bold;"><?php echo $trial_days_left; ?></span>
					</div>
					<div style="vertical-align: middle;font-size: 18px;font-weight: bold;color: white;padding-left: 10px;<?php echo $daysLeftText; ?>">DAY<span style="<?php if($trial_days_left == 1){echo "display:none";} ?>">S</span> LEFT IN YOUR FREE TRIAL</div>
					
				</div>
				
				<div class="col-lg-8 col-md-12 col-sm-12" style="font-size:13px;padding:0px;font-family: Gotham-Light;text-align:right;color:white;font-weight:bold;">
					<div style="display:inline-block;">
						<div style="display:inline-block;">
							Liking the Small Builders experience? Upgrade now and explore Small Builders for only $100 for 30 days. 
						</div>&nbsp; 
						<button type="button" class="upgradeBtn btn btn-success btn-sm btnCustomSuccess btnBannerGrn">
							Upgrade now
						</button>
					</div><br>
					<div style="display:inline-block;margin-top:10px;">
						Get the most out of your free trial! Book your free one-on-one walkthrough. &nbsp;
						<button type="button" onclick="openTraining()" style="<?php echo $demoBtn; ?>" class="bookADemo btn btn-success btn-sm btnCustomSuccess btnBannerGrn" <?php echo $demoBtnHover; ?>>Book a demo</button>
					</div>
				</div>
				<i class="fa fa-times" onclick="minimizeBanner()" style="cursor:pointer;position:absolute;right: 10px;top: 10px;color: #ffffff70;font-size: 20px;"></i>
			</div>
		</div>
		<div id="bannerHolder">
		</div>
		
		<div id="trialBannerMin"  style="text-align: center;border-top-left-radius: 5px;border-bottom-left-radius: 5px;background-color: #003d6d;padding: 10px;x 14px 0px #3b3b3b;box-shadow: -1px 1px 5px 1px #868686;">
			<div style="font-weight: bold;color: white;display:inline-block;position:relative;">
				<?php 
					if($date_registered_set == $date_today && $trial_date_extended == "0000-00-00"){
						echo '<div style="font-family:Gotham-Bold;font-size:20px; margin-top:20px;">FREE<br/>TRIAL</div>';
					}else{
						if($trial_days_left > 9){
							$echoBanner = "17px";
						}else{
							$echoBanner = "25px";
						}
						
						if($trial_days_left == 1){
							$echoBannerDisplay =  "display:none";
						}else{
							$echoBannerDisplay = '';
						}
						
						echo '<img style="height:65px;margin-top: 6px;" src="'.asset_host().'/builder/portal/sb_tools/feature/trial.png"> 
							  <span style="font-family:Gotham-Bold;top: 16px;left:'.$echoBanner.'; position:absolute;color:white;font-size:28px;font-weight:bold;">'.$trial_days_left.'</span>
							  <span style="font-family:Gotham-Light"><br> trial day<span style="'.$echoBannerDisplay.'">s</span><br>left!</span>';
					}
				?>

				
			</div>
			<div class="controlBtn" style="text-align:center;vertical-align: top;display: inline-block;font-size: 20px;font-weight: bold;color: white;padding-left: 15px;padding-top: 13px;padding-bottom:13px">
				<button type="button" style="font-weight:bold;margin-top:5px;cursor:pointer;border-radius:15px" class="controlBtn upgradeBtn btn btn-success btn-sm btnCustomSuccess">Upgrade now</button><br>
				<button type="button" onclick="openTraining()" style="font-weight:bold;margin-top: 15px;cursor:pointer;border-radius:15px;" class="bookADemo controlBtn demoBtn btn btn-success btn-sm btnCustomSuccess">Book a demo</button>
			</div>
		</div>
		
		<?php
				// -------- Start of 7 day trial (if trial isn't extended yet) -------- //
			if(($trial_days_left <= 8 && $trial_days_left >= 7) && $trial_date_extended == "0000-00-00"){
				$main_text_trial = "Your free trial period ends</br>in ".$trial_days_left." days";
				$left_text_trial = "Find out how to customise ".app_host_name()." to your particular needs";
				$right_text_trial = "Enjoy ".app_host_name()." for only $100 for 30 days!";
				$submain_text_trial = "";
			}else if($trial_days_left == 6 && $trial_date_extended == "0000-00-00"){
				$main_text_trial = "You're back!</br>There are ".$trial_days_left." days left in your trial.";
				$left_text_trial = "Lock in a schedule and we'll train your for free!";
				$right_text_trial = "With only $100 enjoy our premium features for 30 days!";
				$submain_text_trial = "";
			}else if($trial_days_left == 5 && $trial_date_extended == "0000-00-00"){
				$main_text_trial = "Only ".$trial_days_left." days left to enjoy</br>this free trial.";
				$left_text_trial = "Maximise your ".app_host_name()." experience and learn more by talking to us";
				$right_text_trial = "Liking the experience? Upgrade and explore now for only $100 for 30 days";
				$submain_text_trial = "";
			}else if($trial_days_left == 4 && $trial_date_extended == "0000-00-00"){
				$main_text_trial = "Your Free Trial expires in</br>".$trial_days_left." days!";
				$left_text_trial = "Need more time to explore? We can extend your trial and give you one-on-one training";
				$right_text_trial = "Don't miss this offer! Upgrade and explore further for only $100 for 30 days";
				$submain_text_trial = "";
			}else if($trial_days_left == 3 && $trial_date_extended == "0000-00-00"){
				$main_text_trial = "You have ".$trial_days_left." days left in</br>your free trial";
				$left_text_trial = "Extend your free trial and customise ".app_host_name()." to suit your needs!";
				$right_text_trial = "Upgrade now to continue exploring ".app_host_name()." for 30 days for only $100!";
				$submain_text_trial = "";
			}else if($trial_days_left == 2 && $trial_date_extended == "0000-00-00"){
				$main_text_trial = $trial_days_left." days left until your free</br>trial runs out!";
				$left_text_trial = "More amazing features at the tip of your fingers! Talk to us and we'll train you for free";
				$right_text_trial = "Upgrade now and make that $100 count with a premium experience in ".app_host_name()." for 30 days!";
				$submain_text_trial = "";
			}else if($trial_days_left == 1 && $trial_date_extended == "0000-00-00"){
				$main_text_trial = "Your free trial ends today";
				$left_text_trial = "Get more days of fuller experience of our premium features by talking to us";
				$right_text_trial = "Today's your last chance to get our premium features for only $100 for 30 days!";
				$submain_text_trial = "You won't have access to our premium </br>features tomorrow";
			}
				// -------- If user extends trial -------- //
			else if(($trial_days_left <= 15 && $trial_days_left >=8) && $trial_date_extended != "0000-00-00"){
				if($trial_date_extended == $date_today){
					$main_text_trial = "<div style='font-size:30px'>You now have additional ".$trial_days_left." days to use all</br>premium features of ".app_host_name()." for free!</div>";
					$left_text_trial = "Still need help? Continue talking to us.";
					$right_text_trial = "Explore ".app_host_name()." even longer for just $100 for 30 days!";
					$submain_text_trial = "";
				}else{
					if($trial_days_left == 14 && $trial_date_extended != "0000-00-00"){			
						$main_text_trial = "Your free trial period ends</br>in ".$trial_days_left." days";
						$left_text_trial = "Find out how to customise ".app_host_name()." to your particular needs";
						$right_text_trial = "Enjoy ".app_host_name()." for only $100 for 30 days!";
						$submain_text_trial = "";
					}else if($trial_days_left == 13 && $trial_date_extended != "0000-00-00"){
						$main_text_trial = $trial_days_left." more days until your</br>extended trial runs out";
						$left_text_trial = "Lock in a schedule and we'll train you for free!";
						$right_text_trial = "Enjoy ".app_host_name()." with just $100 for 30 days!";
						$submain_text_trial = "";
					}else if($trial_days_left == 12 && $trial_date_extended != "0000-00-00"){
						$main_text_trial = "You're back!</br>There are ".$trial_days_left." days left in your trial.";
						$left_text_trial = "Lock in a schedule and we'll train your for free!";
						$right_text_trial = "With only $100 enjoy our premium features for 30 days!";
						$submain_text_trial = "";
					}else if($trial_days_left == 11 && $trial_date_extended != "0000-00-00"){
						$main_text_trial = "Only ".$trial_days_left." days left to enjoy</br>this free trial.";
						$left_text_trial = "Maximise your ".app_host_name()." experience and learn more by talking to us";
						$right_text_trial = "Liking the experience? Upgrade and explore now for only $100 for 30 days";
						$submain_text_trial = "";	
					}else if($trial_days_left == 10 && $trial_date_extended != "0000-00-00"){
						$main_text_trial = "Your Free Trial expires in</br>".$trial_days_left." days!";
						$left_text_trial = "Need more time to explore? We can extend your trial and give you one-on-one training";
						$right_text_trial = "Don't miss this offer! Upgrade and explore further for only $100 for 30 days";
						$submain_text_trial = "";	
					}else if($trial_days_left == 9 && $trial_date_extended != "0000-00-00"){
						$main_text_trial = "You have ".$trial_days_left." days left in</br>your free trial";
						$left_text_trial = "Extend your free trial and customise ".app_host_name()." to suit your needs!";
						$right_text_trial = "Upgrade now to continue exploring ".app_host_name()." for 30 days for only $100!";
						$submain_text_trial = "";
					}else if($trial_days_left == 8 && $trial_date_extended != "0000-00-00"){
						$main_text_trial = $trial_days_left." days left until your free</br>trial runs out!";
						$left_text_trial = "More amazing features at the tip of your fingers! Talk to us and we'll train you for free";
						$right_text_trial = "Upgrade now and make that $100 count with a premium experience in ".app_host_name()." for 30 days!";
						$submain_text_trial = "";
					}
				}
			}else if($trial_days_left == 7 && $trial_date_extended != "0000-00-00"){
					$main_text_trial = $trial_days_left." days left in your free trial";
					$left_text_trial = "Find out how to customise ".app_host_name()." to fit your particular needs";
					$right_text_trial = "With only $100, get an unlimited experience of our premium features for 30 days!";
					$submain_text_trial = "";
			}else if($trial_days_left == 6 && $trial_date_extended != "0000-00-00"){
				$main_text_trial = $trial_days_left." more days until your</br>extended trial runs out";
				$left_text_trial = "Lock in a schedule and we'll train you for free!";
				$right_text_trial = "Enjoy ".app_host_name()." with just $100 for 30 days!";
				$submain_text_trial = "";
			}else if($trial_days_left == 5 && $trial_date_extended != "0000-00-00"){
				$main_text_trial = "You have ".$trial_days_left." days left to enjoy</br>our premium features.";
				$left_text_trial = "Maximise your ".app_host_name()." experience by talking to us.";
				$right_text_trial = "Liking the experience? Upgrade now and explore further for only $100 for 30 days";
				$submain_text_trial = "";
			}else if($trial_days_left == 4 && $trial_date_extended != "0000-00-00"){
				$main_text_trial = "Your free trial expires in</br>".$trial_days_left." days!";
				$left_text_trial = "Need assistance with getting around? Get a free one-on-one training from us";
				$right_text_trial = "Upgrade and explore further for only $100 for 30 days";
				$submain_text_trial = "";
			}else if($trial_days_left == 3 && $trial_date_extended != "0000-00-00"){
				$main_text_trial = "Only ".$trial_days_left." more days remaining in your extended free trial";
				$left_text_trial = "Customise ".app_host_name()." according to your needs! Learn more by talking to us";
				$right_text_trial = "Continue using our amazing features for only $100! Upgrade now!";
				$submain_text_trial = "";
			}else if($trial_days_left == 2 && $trial_date_extended != "0000-00-00"){
				$main_text_trial = "Last ".$trial_days_left." days before your</br>free trial expires";
				$left_text_trial = "Find out how to customise ".app_host_name()." to suit your needs";
				$right_text_trial = "Don't miss this offer! Upgrade and keep exploring for only $100 for 30 days";
				$submain_text_trial = "";
			}else if($trial_days_left == 1 && $trial_date_extended != "0000-00-00"){
				$main_text_trial = "Your free trial ends today";
				$left_text_trial = "Continue talking to us to get the most out of your ".app_host_name()." experience";
				$right_text_trial = "Today's your last chance to get our premium features for only $100 for 30 days";
				$submain_text_trial = "You won't have access to our premium </br>features tomorrow";
			}
				// -------- If trial is expired-------- //
			else if($trial_days_left <= 0 && $client_is_paid == 0 && $user_is_new == 1){
				//echo "<script>alert('Alert for QA: ACCOUNT IS EXPIRED. Account must be redirected to the Trial Page');//window.location='".asset_uri()."/builder/portal/trial-period-ended.php?ui=".base64_encode($dec_ui)."&ci=".base64_encode($dec_ci)."';//</script>";
				
				$a = $_user_email;
				
				echo "<script>console.log('Account expired.');</script>"; //!stristr($a,'test') || !stristr($a,'demo') || 
				
				if (!(strpos($a, 'lophils') !== false || strpos($a, 'smallbuilders') !== false)) {
					//echo "<script>window.location='".asset_uri()."/builder/portal/trial-period-ended.php?ui=".base64_encode($dec_ui)."&ci=".base64_encode($dec_ci)."';</script>";
				}
			}
			
			if((($trial_days_left >= 1 && $trial_days_left <= 8 && $trial_date_extended == "0000-00-00") || 
				($trial_days_left >= 1 && $trial_days_left <= 15 && $trial_date_extended != "0000-00-00")) && 
				$client_is_paid == 0 && $user_is_new == 1 && $_user_is_owner == 1){
				$accPrice = '100';
				echo "<style>
						.calendly-badge-content{
							display:none!important;
						}
					</style>";
			}else{
				$accPrice = '600';
			}
		?>
		<!-- Start Expiry Modal -->
		<div id="modalExpirationModal" class="modal fade" role="dialog" style="padding-right: 17px;">
		  <div class="modal-dialog" style="font-family: Gotham-Light;width:865px;margin-top:20vh;text-align:center;font-weight:bold;">
			<!-- Modal content-->
			<div class="modal-content" style="color:#003d6d;border: 1px #003d6d solid;border-radius: 15px;">
			  <div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" style="font-size: 35px; margin-top: -10px;">×</button>
				<div class="row">
					<div class="col-md-12" style="font-family: Gotham-Bold;padding-top: 10px;font-size: 40px;">
						<?php echo $main_text_trial; ?>
					</div>
					<div class="col-md-12" style="font-size:22px;letter-spacing: 1px;">
						<?php echo $submain_text_trial; ?>
					</div>
					<div class="col-md-12" style="font-size: 21px;padding: 30px 120px 15px;text-align:left;-webkit-text-stroke-width: 0.8px;">
						<div class="col-md-6" style="padding-right:30px">
							<?php echo $left_text_trial; ?>
						</div>
						<div class="col-md-6" style="padding-left:30px">
							<?php echo $right_text_trial; ?>
						</div>
					</div>
					<div class="col-md-12" style="font-size: 16px;padding: 0px 120px 50px;-webkit-text-stroke-width: 0.8px;">
						<div class="col-md-6" style="padding-right:30px">
							<button type="button" class="bookADemo btn btn-lg btnCustomSuccess" style="font-size: 20px;letter-spacing: 1px;font-family: Gotham-Light;font-weight: bold;border-radius:15px;margin-top: 22px;width: 100%;color:white;height: 49px;" onclick="openTrainingModal()">Book a Demo</button>
						</div>
						<div class="col-md-6" style="padding-left:30px">
							<button type="button" class="btn btn-lg btnCustomSuccess upgradeBtnModal" style="font-size: 20px;letter-spacing: 1px;font-family:Gotham-Light;border-radius:15px;font-weight: bold;color:white;margin-top: 22px;width: 100%;height: 49px;">Upgrade Now</button>
						</div>
					</div>
				</div>
			  </div>
			</div>
		  </div>
		</div>
		<!-- End Expiry Info Modal -->
		
		
		<?php
		
			/* 
				echo "<script>
						console.log('reg_date: ".$date_registered_set."');
						console.log('date_today: ".$date_today."');
						console.log('trial_extend: ".$trial_date_extended."');
						console.log('day: ".$nth_day_today."');
						console.log('days_left: ".$trial_days_left."');
						console.log('client_is_paid: ".$client_is_paid."');
						console.log('user_is_new: ".$user_is_new."');
						console.log('diff_reg: ".$diffReg."');
						console.log('get_date: ".$get_date."');
						console.log('date_reg_diff: ".$date_reg_diff."');
						console.log('days_extension: ".$days_extension."');
						console.log('trial_stat: ".$trial_stat."');
						console.log('_user_is_owner: ".$_user_is_owner."');
						console.log('hide_banner: ".$hide_banner."');
					</script>"; */
			// ------- Trial Banner ------- //
			if((($trial_days_left >= 1 && $trial_days_left <= 7 &&  $trial_date_extended == "0000-00-00") || 
				($trial_days_left >= 1 && $trial_days_left <= 15 && $trial_date_extended != "0000-00-00")) && 
				 $client_is_paid == 0 && $user_is_new == 1 && isset($_GET['login'])){
				echo "<script>
						$(document).ready(function() {
							$('#modalExpirationModal').modal('show');
						});
					</script>";
			}
			// ------- Trial Banner ------- //
		?>
		
        <!--------------------------------------------- End Trial Banner ----------------------------------------------------->
		
		<!--------------------------------------------- Start Upgrade Success Modal ----------------------------------------------------->
		<div id="modalUpgraded" class="modal fade" role="dialog" style="padding-right: 17px;">
		  <div class="modal-dialog" style="font-family: Gotham-Light;width:865px;margin-top:20vh;text-align:center;font-weight:bold;">
			<!-- Modal content-->
			<div class="modal-content" style="color:#003d6d;border: 1px #003d6d solid;border-radius: 15px;">
			  <div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" style="font-size: 35px; margin-top: -10px;">×</button>
				<div class="row">
					<div class="col-md-12" style="font-family: Gotham-Bold;padding-top: 10px;font-size: 40px;">
						Congratulations!<br/>
					</div>
					<div class="col-md-12" style="font-family: Gotham-Bold;font-size:28px;letter-spacing: 1px;">
						You have successfully upgraded your account! <br/> Enjoy unlimited use of our amazing features.
					</div>
					<div class="col-md-12" style="font-size: 22px;padding: 30px 120px 15px;-webkit-text-stroke-width: 0.8px;">
						A confirmation was sent to you via email.<br/><br/>
						<span style="font-size:15px;"><i>Note: If you haven't received an email within 30 mins, please contact us immediately</i></span>
					</div>
					<div class="col-md-12" style="font-size: 16px;padding: 0px 120px 40px;-webkit-text-stroke-width: 0.8px;">
						<button type="button" class="btn btn-lg btnCustomSuccess open_intercom" style="font-size: 20px;letter-spacing: 1px;font-family: Gotham-Light;font-weight: bold;border-radius:15px;margin-top: 22px;width: 300px;color:white;height: 49px;" data-dismiss="modal">Got it!</button>
					</div>
				</div>
			  </div>
			</div>
		  </div>
		</div>
		<?php
			if(isset($_GET['new_upgrade']) && $_GET['new_upgrade'] == 1){
				echo "<script>
						$(document).ready(function() {
							$('#modalUpgraded').modal('show');
						});
					</script>";
			}
		?>
	<!--------------------------------------------- End Upgrade Success Modal ----------------------------------------------------->
		
<script>
	// ------- Trial Banner ------- //
		
	setTimeout(
		function() {
			if($('#trialBannerMax').is(':visible')){
				var profileHeight = $('#profileHolder').outerHeight();
				if ($(window).scrollTop() >= profileHeight) {
					var bannerHeight = $('#trialBannerMax').outerHeight();
					$('#bannerHolder').css({'height':bannerHeight+'px'});
					
					if ($(document).height() > $(window).height()) {
						$('#trialBannerMax').css({'position':'fixed', 'width':'99vw', 'z-index':'999', 'top':'0px', 'box-shadow':'0 1px 13px 1px black'});
					}else{
						$('#trialBannerMax').css({'position':'fixed', 'width':'100vw', 'z-index':'999', 'top':'0px', 'box-shadow':'0 1px 13px 1px black'});
					}
					
				}else{
					if ($(document).height() > $(window).height()) {
						$('#trialBannerMax').css({'position':'relative', 'width':'99vw', 'z-index':'10', 'top:':'unset','box-shadow':'none'});
					}else{
						$('#trialBannerMax').css({'position':'relative', 'width':'100vw', 'z-index':'10', 'top:':'unset','box-shadow':'none'});
					}
					$('#bannerHolder').css({'height':'0'});
				}
			}
		}
	, 3000);
	
	
	$("#trialBannerMin").on('click', ':not(.controlBtn)', function (e) {
		//e.stopPropagation();
		$('#trialBannerMin').hide();
		$('#trialBannerMax').show();
		maximizeBanner();
	});
	$(".upgradeBtn").on('click', function (e) {
		demoDataCaptureFunnel('Outside', '<?php echo $nth_day_today_word; ?>', 'Trial Header', 'Upgrade Now', 'Submit');
		window.location.href = '<?php echo asset_host(); ?>/creditcard/index.php?ui=<?php echo base64_encode($dec_ui); ?>&ci=<?php echo base64_encode($dec_ci); ?>&src=b24tYm9hcmRpbmc=';
	});
	$(".upgradeBtnModal").on('click', function (e) {
		demoDataCaptureFunnel('Outside', '<?php echo $nth_day_today_word; ?>', 'Trial Reminder Pop Up', 'Upgrade Now', 'Submit');
		window.location.href = '<?php echo asset_host(); ?>/creditcard/index.php?ui=<?php echo base64_encode($dec_ui); ?>&ci=<?php echo base64_encode($dec_ci); ?>&src=b24tYm9hcmRpbmc=';
	});
	
	
	$(".demoBtn").on('click', function (e) {
		//$('#trialBannerMaxDiv').hide();
		//$('#trialBannerMin').show();
	});
	
	$(window).scroll(function() {
		if($('#trialBannerMax').is(':visible')){
			var profileHeight = $('#profileHolder').outerHeight();
			if ($(window).scrollTop() >= profileHeight) {
				var bannerHeight = $('#trialBannerMax').outerHeight();
				$('#bannerHolder').css({'height':bannerHeight+'px'});
				
				if ($(document).height() > $(window).height() || scroll_on_page == '<?php echo $scroll_on_page; ?>') {
					$('#trialBannerMax').css({'position':'fixed', 'width':'99vw', 'z-index':'999', 'top':'0px', 'box-shadow':'0 1px 13px 1px black'});
				}else{
					$('#trialBannerMax').css({'position':'fixed', 'width':'100vw', 'z-index':'999', 'top':'0px', 'box-shadow':'0 1px 13px 1px black'});
				}
				
			}else{
				if ($(document).height() > $(window).height() || scroll_on_page == '<?php echo $scroll_on_page; ?>') {
					$('#trialBannerMax').css({'position':'relative', 'width':'99vw', 'z-index':'10', 'top:':'unset','box-shadow':'none'});
				}else{
					$('#trialBannerMax').css({'position':'relative', 'width':'100vw', 'z-index':'10', 'top:':'unset','box-shadow':'none'});
				}
				$('#bannerHolder').css({'height':'0'});
			}
		}
	});
	
	function openTraining(){
		$('.calendly-badge-content').click();
		demoDataCaptureFunnel('Outside', '<?php echo $nth_day_today_word; ?>', 'Trial Header', 'Booked Demo', 'Submit');
	}
	
	function openTrainingModal(){
		$('.calendly-badge-content').click();
		demoDataCaptureFunnel('Outside', '<?php echo $nth_day_today_word; ?>', 'Trial Reminder Pop Up', 'Booked Demo', 'Submit');
	}
	
	function minimizeBanner(){
		$('#trialBannerMax').hide();
		$('#trialBannerMin').show();
		$('#bannerHolder').css({'height':'0'});
		
        $.post("sb_tools/save_banner_settings.php", {
			user_id: "<?php echo $dec_ui; ?>",
			banner_stat: "min"
        });
	}
	
	function maximizeBanner(){
        $.post("sb_tools/save_banner_settings.php", {
			user_id: "<?php echo $dec_ui; ?>",
			banner_stat: "max"
        });
	}
	
	$(document).on('keyup keypress', 'form input[type="text"]', function(e) {
	  if(e.which == 13) {
		e.preventDefault();
		return false;
	  }
	});
	// ------- Trial Banner ------- //
</script>