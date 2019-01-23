<?php
include '../theodore/formbuilder/controller/phpMailerClass.php';
include '../theodore/formbuilder/libs/errhandling/errorsender.php';
include '../theodore/formbuilder/libs/mdetect/Mobile_Detect.php';

if(isset($_GET['error'])){
	$login_status = "<span class='lbl_status_f'>Invalid Username or Password.</span>";
}

if(isset($_POST['btnLogIn'])){
	$uname = mysqli_real_escape_string($csportal_con, $_POST['li_username']);
	$pword = mysqli_real_escape_string($csportal_con, $_POST['li_password']);
	$loggeduser_id = "";
	$loggeduser_name = "";
	$loggedclient_id = "";
	$loggeduser_type = "";
	$loggedclient_type = "";
	$browserDetails = $_SERVER['HTTP_USER_AGENT'];
	
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		    $ip = $_SERVER['HTTP_CLIENT_IP'];
		} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
		   		 $ip = $_SERVER['REMOTE_ADDR'];
				}	
	$logged_ip = $ip;

	$check_user_query = "SELECT * FROM `cs_users` WHERE email_address='".$uname."' AND BINARY user_password='".$pword."' LIMIT 1";
	$check_user = mysqli_query($csportal_con, $check_user_query);
    
	if (mysqli_num_rows($check_user)!=0) {
		$extract_users = mysqli_fetch_array($check_user);
		extract($extract_users);
		if(strtolower($user_status)=="pending"){
		    
		    $chkCreditCardDetails = mysqli_query($csportal_con, "SELECT id FROM trial_creditcard_details WHERE client_id = '".$client_id."'");
		    
		    if(mysqli_num_rows($chkCreditCardDetails) != 0){
		        $login_status = "<span class='lbl_status_f'>Your account has been suspended. Please settle your account to re-activate your access.</span>";
		    } else {
		        $login_status = "<span class='lbl_status_f'>Your account has not been approved. Please contact your Administrator.</span>";
		    }
		    
		} else {
			$queryForSelectUser = "
				SELECT * 
				FROM cs_users 
				WHERE email_address = '".$uname."' 
					AND LOWER(user_status) = 'approved'";
			if (isset($_POST['inputHiddenRedirect']) && isset($_POST['inputHiddenClientId']) ) {
				$checkerForMultipleUser = true;
				$queryForSelectUser = "
					SELECT *
					FROM cs_users
					WHERE email_address = '".$uname."'
						AND client_id = '".base64_decode($_POST['inputHiddenClientId'])."'
						AND LOWER(user_status) = 'approved'";
				$executedQueryForSelectUser = mysqli_query($csportal_con, $queryForSelectUser);
				if (mysqli_num_rows($executedQueryForSelectUser) == 0) {
					$checkerForMultipleUser = false;
					$queryForSelectUser = "
						SELECT * 
						FROM cs_users 
						WHERE email_address = '".$uname."' 
							AND LOWER(user_status) = 'approved'";
				}
			}

			$sel_users_contacts = mysqli_query($csportal_con, $queryForSelectUser);
			if(mysqli_num_rows($sel_users_contacts)!=0) {
				$usrcount=0;
				while($extract_userscontacts = mysqli_fetch_array($sel_users_contacts)) {
					extract($extract_userscontacts);
						$usrcount++;
				}
				if($usrcount!=1){
					$redirectChooseportal = "choose-portal.php";
					$mobileredirectChooseportal = "_m.choose-portal.php";
				}else{
					$redirectChooseportal = "index.php";
					$mobileredirectChooseportal = "_m.index.php";

					if (base64_decode($_POST['inputHiddenRedirect']) == "timesheet" && $checkerForMultipleUser) {
						$redirectChooseportal = "timesheet.php";
						$mobileredirectChooseportal = "_m.index.php";
					}
				}
			}
		
			$login_status = "<span class='lbl_status_s'>Sign in successful.</span>";
			
			session_start();
			$_SESSION['islogged'] = 'yes';
			$_SESSION['s_ui'] = $user_id;
			$_SESSION['s_ci'] = $client_id;
			$_SESSION['s_ctype'] = $client_type;
			
			$loggeduser_id = $user_id;
			$loggeduser_name = $email_address;
			$loggedclient_id = $client_id;
			$loggeduser_type = $user_type;
			$loggedclient_type = $client_type;
			
			$log_query = "INSERT INTO `cs_userlogs` (user_id, username, ip_address, browser_details) VALUES ('". $loggeduser_id ."', '". $loggeduser_name ."', '". $logged_ip."', '". $browserDetails."')";
			
			$enc_ui = base64_encode($loggeduser_id);
			$enc_ci = base64_encode($loggedclient_id);
			
			if(mysqli_query($csportal_con, $log_query)){
				$adminpanel = 'adminpanel/index.php?ui='.$enc_ui.'&ci='.$enc_ci; // admin
				$business_web = 'portal/'.$redirectChooseportal.'?ui='.$enc_ui.'&ci='.$enc_ci.'&login=1'; // business - web
				$business_mobile = 'portal/'.$mobileredirectChooseportal.'?ui='.$enc_ui.'&ci='.$enc_ci.'&login=1'; // business - mobile
				
				$free_web = 'portal/index.php?ui='.$enc_ui.'&ci='.$enc_ci.'&login=1'; // free - web
				$free_mobile = 'portal/_m.index.php?ui='.$enc_ui.'&ci='.$enc_ci.'&login=1'; // free - mobile
				
				$trial_expired = 'portal/trial-period-ended.php?ui='.$enc_ui.'&ci='.$enc_ci; // trial period expired
				$trial_expired_mobile = 'portal/_m.trial-period-ended.php?ui='.$enc_ui.'&ci='.$enc_ci; // trial period expired - mobile
				
				
				
				$card_declined = 'portal/declined_creditcard.php?ui='.$enc_ui.'&ci='.$enc_ci; // declined credit card
			
				if($loggeduser_name == "sb_admin@smallbuilders.com.au"){
					$pagelocation = $adminpanel; // admin
				} else {
					
					if($loggeduser_id == 2040){
						/** start - 20170717 AQS
						 * 
						 * Paul Kervin (MASONRITE BRICKLAYING SERVICES PTY. LTD.) Request to access for 1 more week
						 * 34th Day + 1 week = 41 Days
						 * 
						 */
						 
						$get_trial_period = mysqli_query($csportal_con, "SELECT datediff(date_registered + INTERVAL 91 DAY, date(NOW())) as trial_period FROM `trial_registration` WHERE client_id = '". $loggedclient_id . "' AND user_id = '".$loggeduser_id."' AND stage = 'Trial' HAVING trial_period < 0");
					} else {
						$get_trial_period = mysqli_query($csportal_con, "SELECT datediff(date_registered + INTERVAL 30 DAY, date(NOW())) as trial_period FROM `trial_registration` WHERE client_id = '". $loggedclient_id . "' AND user_id = '".$loggeduser_id."' AND stage = 'Trial' HAVING trial_period < 0");
					}
				
					if(mysqli_num_rows($get_trial_period)!=0){
						$detect = new Mobile_Detect;
						if ($detect->isMobile()){
							$pagelocation = $trial_expired_mobile; // trial period expired - mobile
						} else {
							$pagelocation = $trial_expired; // trial period expired - web
						}
						
					} else {
						$get_declined_accounts = mysqli_query($csportal_con, "SELECT id FROM `trial_registration` WHERE client_id = '". $loggedclient_id . "' AND user_id = '".$loggeduser_id."' AND stage = 'Card Declined'");
						
						if(mysqli_num_rows($get_declined_accounts)!=0){
							$pagelocation = $card_declined; // declined credit card
						} else {
							$detect = new Mobile_Detect;
							if ($detect->isMobile()){
								if($loggedclient_type=="free_user"){
									$pagelocation = $free_mobile; // free - mobile
								} else {
									$pagelocation = $business_mobile; // business - mobile
								}							
							} else {
								if($loggedclient_type=="free_user"){
									$pagelocation = $free_web; // free - web
								} else {
										$pagelocation = $business_web; // business - web
								}
							}
						}
					}
				}
				// $detect = new Mobile_Detect;
				// echo "<script> alert('".$pagelocation."'); </script>";
				header("Location: $pagelocation");
			}
		}
	}else{
		$login_status = "<span class='lbl_status_f'>Invalid Username or Password.</span>";
	}
}
?>