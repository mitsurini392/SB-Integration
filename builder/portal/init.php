<?php
if(empty($_GET['ui']) || !isset($_GET['ui']) || empty($_GET['ci']) || !isset($_GET['ci'])) {
	if(empty($_GET['state']) || !isset($_GET['state'])){
		echo '<script>window.location="../index.php?stat=invalidaccess&nostate=1&src=init";</script>';
	}
}

	if($_GET['ui'] != "" && $_GET['ci'] != ""){
		$ui = $_GET['ui'];
		$ci = $_GET['ci'];
		$dec_ui = base64_decode($ui);
		$dec_ci = base64_decode($ci);
	} else {
		$state = $_GET['state'];
		$state = str_replace('{','',$state);
		$state = str_replace('}','',$state);
		
		$exp_state = explode('-', $state);
		$ui = $exp_state[0];
		$ci = $exp_state[1];
		
		$dec_ui = base64_decode($ui);
		$dec_ci = base64_decode($ci);
	}

/**
	$ip = '';
	$form_id = '';
	$browser_details =  mysqli_real_escape_string($csportal_con, $_SERVER['HTTP_USER_AGENT']);
	$form_pagename = mysqli_real_escape_string($csportal_con, $_SERVER['REQUEST_URI']);

	if(!empty($_GET['form_id']) || isset($_GET['form_id'])){
		$form_id = mysqli_real_escape_string($csportal_con, $_GET['form_id']);
	}

	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}
		
	$insertValues = mysqli_query($csportal_con, "INSERT INTO `cs_userlogs_accessedforms` (user_id, ip_address, browser_details, form_id, form_page_name) VALUES ('".$dec_ui."', '".$ip."', '".$browser_details."', '".$form_id."', '".$form_pagename."')");
*/

	include('../../theodore/formbuilder/libs/mdetect/Mobile_Detect.php');
	$detect = new Mobile_Detect;

	if($detect->isMobile()){
		
		if($_user_clienttype == 'free_user'){
			if(strlen(strstr(strtolower(basename($_SERVER['PHP_SELF'])), strtolower('demo'))) == 0){
				if(basename($_SERVER['PHP_SELF'])!='start-trial.php' && basename($_SERVER['PHP_SELF'])!='trial-period-ended.php'){
					$dirLink = 'demo.m.dashboard.php?ui='.$ui.'&ci='.$ci;
					echo '<script>window.location="'.$dirLink.'";</script>';
				}
			}
				
			if(strlen(strstr(strtolower(basename($_SERVER['PHP_SELF'])), strtolower('demo.m.'))) == 0){
				if(basename($_SERVER['PHP_SELF'])!='start-trial.php' && basename($_SERVER['PHP_SELF'])!='trial-period-ended.php'){
					$dirLink = 'demo.m.dashboard.php?ui='.$ui.'&ci='.$ci;
					echo '<script>window.location="'.$dirLink.'";</script>';
				}
			}
			
		} else {
			if(strlen(strstr(strtolower(basename($_SERVER['PHP_SELF'])), strtolower('_m.'))) == 0){
				if(strlen(strstr(strtolower(basename($_SERVER['PHP_SELF'])), strtolower('demo'))) > 0){
					$dirLink = '_m.index.php?ui='.$ui.'&ci='.$ci;
					echo '<script>window.location="'.$dirLink.'";</script>';
				}
				if(basename($_SERVER['PHP_SELF'])!='profile.php'){
					$dirLink = '_m.index.php?ui='.$ui.'&ci='.$ci;
					echo '<script>window.location="'.$dirLink.'";</script>';
				}
				
				if(basename($_SERVER['PHP_SELF'])=='start-trial.php' && basename($_SERVER['PHP_SELF'])=='trial-period-ended.php'){
					$dirLink = '_m.index.php?ui='.$ui.'&ci='.$ci;
					echo '<script>window.location="'.$dirLink.'";</script>';
				}
			}
		}
		
	} else {
			if($_user_clienttype == 'free_user'){
				if(strlen(strstr(strtolower(basename($_SERVER['PHP_SELF'])), strtolower('demo'))) == 0){
					if(basename($_SERVER['PHP_SELF'])!='start-trial.php' && basename($_SERVER['PHP_SELF'])!='trial-period-ended.php'){
						//$dirLink = 'demo.dashboard.php?ui='.$ui.'&ci='.$ci;
						$dirLink = 'trial-period-ended.php?ui='.$ui.'&ci='.$ci;
						echo '<script>window.location="'.$dirLink.'";</script>';
					}
				}
				
				if(strlen(strstr(strtolower(basename($_SERVER['PHP_SELF'])), strtolower('demo.m.'))) > 0){
					if(basename($_SERVER['PHP_SELF'])!='start-trial.php' && basename($_SERVER['PHP_SELF'])!='trial-period-ended.php'){
						$dirLink = 'demo.dashboard.php?ui='.$ui.'&ci='.$ci;
						echo '<script>window.location="'.$dirLink.'";</script>';
					}
				}
			} else {
					// if(strlen(strstr(strtolower(basename($_SERVER['PHP_SELF'])), strtolower('demo'))) > 0){
					// 	$dirLink = 'index.php?ui='.$ui.'&ci='.$ci;
					// 	echo '<script>window.location="'.$dirLink.'";</script>';
					// }
				
					// if(strlen(strstr(strtolower(basename($_SERVER['PHP_SELF'])), strtolower('_m.'))) > 0){
					// 	$dirLink = 'index.php?ui='.$ui.'&ci='.$ci;
					// 	echo '<script>window.location="'.$dirLink.'";</script>';		
					// }
					
					if(basename($_SERVER['PHP_SELF'])=='start-trial.php' && basename($_SERVER['PHP_SELF'])=='trial-period-ended.php'){
						$dirLink = 'index.php?ui='.$ui.'&ci='.$ci;
						echo '<script>window.location="'.$dirLink.'";</script>';		
					}
				}
		}


?>



