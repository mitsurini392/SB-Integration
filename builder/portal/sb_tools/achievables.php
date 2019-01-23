<?php
	$ci = $_GET['ci'];
	$ui = $_GET['ui'];
	$dec_ci = base64_decode($ci);
	$dec_ui = base64_decode($ui);
	$achieve_counter = 0;
	$visitedDemoProj = 0;
	$addedContacts = 0;
	$addedLogo = 0;
	$completedBusDetails = 0;
	$addedProj = 0;
	$user_is_new = 0;
	
	//------START - Check if New User------//
		$queryFindFromDemoCheckEx = "SELECT date_registered AS date_registered_set FROM trial_registration WHERE client_id = ".$dec_ci;
		$executedQueryFindFromDemoCheckEx = mysqli_query($csportal_con, $queryFindFromDemoCheckEx);
		if(mysqli_num_rows($executedQueryFindFromDemoCheckEx) > 0){
			while($row = mysqli_fetch_array($executedQueryFindFromDemoCheckEx)) {
				extract($row);
				$date_reg_diff = strtotime($date_registered_set) - strtotime('2019-01-02');
				if($date_reg_diff >= 0){
					$user_is_new = 1;
				}
			}
		}
	//-------END - Check if New User-------//

	if($user_is_new == 1){
		//------START - Check 'Visit Sample Project'------//
		$queryFindFromDemoCheckEx = "SELECT *
									 FROM tbl_demoscript_checker 
									 WHERE client_id = '".$dec_ci."'
									 AND visit_demo_proj = '1'";
		$executedQueryFindFromDemoCheckEx = mysqli_query($theodore_con, $queryFindFromDemoCheckEx);
		if(mysqli_num_rows($executedQueryFindFromDemoCheckEx) > 0){
			$visitedDemoProj = 1;
			$achieve_counter++;
		}
		
		$getSampleProj = "	SELECT id AS sample_pid 
							FROM _submission_204 
							WHERE JClientID140400 = '".$dec_ci."'
							AND _demo_project = 'yes'";
		$execSampleProj = mysqli_query($theodore_con, $getSampleProj);

		if (mysqli_num_rows($execSampleProj) > 0) {
			while($extractSampleProj=mysqli_fetch_array($execSampleProj)){
				extract($extractSampleProj);
				$sample_pid = base64_encode($sample_pid);
			}
		}
		//-------END - Check 'Visit Sample Project'-------//
		
		
		//------START - Check 'Add Contacts'------//
			$querySearch=
				"SELECT x.* FROM (
						SELECT representative_email AS email_address
						FROM smallbui_theodore._relationship_db_subcontractors
						WHERE client_id = '".$dec_ci."'
						AND status = 'active'
					UNION
						SELECT employee_email AS email_address
						FROM smallbui_theodore._relationship_db_employee
						WHERE client_id = '".$dec_ci."'
						AND status = 'active'
					UNION
						SELECT representative_email AS email_address
						FROM smallbui_theodore._relationship_db_suppliers
						WHERE client_id = '".$dec_ci."'
						AND status = 'active'
					UNION
						SELECT customer_email AS email_address
						FROM smallbui_theodore._relationship_db_customers
						WHERE client_id = '".$dec_ci."'
						AND status = 'active'
					UNION
						SELECT email_address
						FROM smallbui_theodore._relationship_db_other_types
						WHERE client_id = '".$dec_ci."'
						AND status = 'active'
					) AS x
					WHERE x.email_address
					NOT IN('aaron.callaghan@smallbuilders.com.au','brock.boyer@smallbuilders.com.au','gab.ryan@smallbuilders.com.au', 'joshua.esson@smallbuilders.com.au','justin.gordon@smallbuilders.com.au','mason.gerstaecker@smallbuilders.com.au','rudy.griffin@smallbuilders.com.au', 'ryder.coppleson@smallbuilders.com.au','terri.bass@smallbuilders.com.au')
					GROUP BY x.email_address";
			
			$executedQuerySearchCountContacts = mysqli_query($theodore_con, $querySearch);
			if (mysqli_num_rows($executedQuerySearchCountContacts) > 1) {
				$addedContacts = 1;
				$achieve_counter++;
			}
		//-------END - Check 'Add Contacts'-------//
		
		
		//------START - Check 'Add Logo'------//
			if($_user_companylogo != '' && $_user_companylogo != 'logo.png'){
				$addedLogo = 1;
				$achieve_counter++;
			}
		//-------END - Check 'Add Logo'-------//
		
		
		//------START - Check 'Complete Business Details'------//
			$queryFindFromDemoCheckEx = "SELECT *
									 FROM tbl_demoscript_checker 
									 WHERE client_id = '".$dec_ci."'
									 AND edit_business_details = '1'";
			$executedQueryFindFromDemoCheckEx = mysqli_query($theodore_con, $queryFindFromDemoCheckEx);
			if(mysqli_num_rows($executedQueryFindFromDemoCheckEx) > 0){
				$completedBusDetails = 1;
				$achieve_counter++;
			}
		//-------END - Check 'Complete Business Details'-------//
		
		
		//------START - Check 'Added a Project'------//
		$query_count_project = "SELECT * 
								FROM _submission_204 
								WHERE JClientID140400 = '".$dec_ci."'
								AND (_demo_project = 'no' || _demo_project IS NULL)";
		$count_project = mysqli_query($theodore_con, $query_count_project);

		if (mysqli_num_rows($count_project) > 0) {
			$addedProj = 1;
			$achieve_counter++;
		}
		//-------END - Check 'Added a Project'-------//
		
		//------START - Fraction to Percentage------//
			$percentage_val = ($achieve_counter / 5) * 100;
			$percentage_val = number_format($percentage_val, 0);
		//-------END - Fraction to Percentage-------//
		
	}
	//------START - Get Business Details------//
	$getClientDetails = mysqli_query($theodore_con, "SELECT * FROM `_submission_208` WHERE JClientID21102607 = '".$dec_ci."'");
	if(mysqli_num_rows($getClientDetails) > 0){
		$fetchClientDetails = mysqli_fetch_array($getClientDetails);
			
		$ach_business_name = stripslashes($fetchClientDetails["JYourbusinessname21102039"]);
		$ach_business_tradingname = stripslashes($fetchClientDetails["JYourtradingname21102137"]);
		$ach_business_entitytype = stripslashes($fetchClientDetails["JYourbusinesstype21101228"]);
			
		$ach_business_state = stripslashes($fetchClientDetails["adrs_state"]);
		$ach_business_postcode = stripslashes($fetchClientDetails["adrs_postcode"]);
		$ach_business_gstregister = stripslashes($fetchClientDetails["GST_Registered"]);
		
		$ach_business_abn = stripslashes($fetchClientDetails["JYourABN21102149"]);
		$ach_business_address = stripslashes($fetchClientDetails["JYourregisteredbusinessadd21102218"]);

		$ach_business_phone = stripslashes($fetchClientDetails["JYourphonenumber21102512"]);
		$ach_business_fax = stripslashes($fetchClientDetails["JYourfaxnumber21102526"]);
		$ach_business_email = stripslashes($fetchClientDetails["JYouremailaddress21102543"]);

		$ach_business_contractor_license = stripslashes($fetchClientDetails["JLicensenumber21103449"]);
			
		if($fetchClientDetails["JValiduntil21103538"]!=""){
			$ach_business_contractor_validity = date("d-m-Y", strtotime($fetchClientDetails["JValiduntil21103538"]));
		}
			
		$ach_business_bank_name = stripslashes($fetchClientDetails["JBankAccountName21103617"]);
		$ach_business_bank_bsb = stripslashes($fetchClientDetails["JBSBNumber21103627"]);
		$ach_business_bank_number = stripslashes($fetchClientDetails["JBankAccountNumber21103637"]);

		$ach_business_insurer_WCI = stripslashes($fetchClientDetails["JNameofInsurer21103717"]);
		$ach_business_policy_number_WCI = stripslashes($fetchClientDetails["JPolicyNumber21103726"]);
		if($fetchClientDetails["JCoverageUntil21103756"]!=""){
			$ach_business_insurance_validity_WCI = date("d-m-Y", strtotime($fetchClientDetails["JCoverageUntil21103756"]));
		}
			
		$ach_business_insurer_PII = stripslashes($fetchClientDetails["JNameofInsurer21103858"]);
		$ach_business_policy_number_PII = stripslashes($fetchClientDetails["JPolicyNumber21103913"]);
		if($fetchClientDetails["JCoverageUntil21103927"]!=""){
			$ach_business_insurance_validity_PII = date("d-m-Y", strtotime($fetchClientDetails["JCoverageUntil21103927"]));
		}

		$ach_business_insurer_CWI = stripslashes($fetchClientDetails["JNameofInsurer21104012"]);
		$ach_business_policy_number_CWI = stripslashes($fetchClientDetails["JPolicyNumber21104022"]);
		if($fetchClientDetails["JCoverageUntil21104033"]!=""){
			$ach_business_insurance_validity_CWI = date("d-m-Y", strtotime($fetchClientDetails["JCoverageUntil21104033"]));
		}
			
		$ach_business_insurer_PPLI = stripslashes($fetchClientDetails["JNameofInsurer21104100"]);
		$ach_business_policy_number_PPLI = stripslashes($fetchClientDetails["JPolicyNumber21104108"]);
		if($fetchClientDetails["JCoverageUntil21104117"]!=""){
			$ach_business_insurance_validity_PPLI = date("d-m-Y", strtotime($fetchClientDetails["JCoverageUntil21104117"]));
		}
			
		$ach_business_insurance_type_OI = stripslashes($fetchClientDetails["JInsuranceType21104150"]);
		$ach_business_insurer_OI = stripslashes($fetchClientDetails["JNameofInsurer21104158"]);
		$ach_business_policy_number_OI = stripslashes($fetchClientDetails["JPolicyNumber21104207"]);
		if($fetchClientDetails["JCoverageUntil21104217"]!=""){
			$ach_business_insurance_validity_OI = date("d-m-Y", strtotime($fetchClientDetails["JCoverageUntil21104217"]));
		}
	}
	//-------END - Get Business Details-------//
	
?>
<style>
	#selectionProjDashboard .progress{
		height:10px;
		border-radius: 50px;
		width:120px;
		margin:5px 5px 0px 5px;
	}
	#selectionProjDashboard .progress-bar{
		background-color: #ffffff;
	}
	#selectionProjDashboard .progress{
		background-color:#366285;
	}
	#selectionProjDashboard .popover{
		width:unset!important;
		min-width:140px!important;
		color:#003d6d;
		border: 1px solid #003d6d;
		text-align:center!important;
	}
	#selectionProjDashboard .popover.right>.arrow{
		border-right-color: rgb(0, 61, 109);
	}
	#feat_next:hover{
		opacity:0.7
	}
	.rotated {
		transform: rotate(180deg);
		-ms-transform: rotate(180deg);
		-moz-transform: rotate(180deg);
		-webkit-transform: rotate(180deg);
		-o-transform: rotate(180deg);
	}
    .feat_control{
        position: absolute;
        top: 277px!important;
        right: 0px;
        width: 35px;
    }
	.achieve_icons{
		height: 45px;
		width: 45px;
		margin-top: 20px;
		border: 1px solid white;
		border-radius: 50%;
		cursor:pointer;
		margin-bottom:10px;
	}
	.achieve_icons:hover{
		background-color:white;
	}
	.achieve_btns{
		background-color:transparent;
		color:white;
		border-radius: 10px;
		border: 2px solid white;
		width:280px;
		font-weight:bold;
		text-align:left;
		padding:0;
		padding-left:20px;
		display:none;
		margin-bottom:25px;
		height:54px;
	}
	.achieve_btns:hover{
		background-color:white;
		color:rgb(0, 61, 109);
	}
	.achieve_btns:focus{
		background-color:transparent;
		color:white;
	}
	.achieve_btns_txt{
		margin-top:10px;
		display:inline-block;
	}
	.achieve_btns .achieve_icons{
		height:35px;
		width:35px;
		margin:1px 0 3px 0;
		border:0;
	}
	#ach_businessDetailsModal .tablinks{
		background-color: white;
		color: rgb(0,112,192);
		font-weight: bold;
		font-size: 16px;
		height: 40px;
		cursor: pointer;
		padding: 10px;
		text-align: center;
		border: 1px solid rgb(0,112,192);
		width: 195px;
	}
	#ach_businessDetailsModal .tablinks:hover{
		background-color:#067fd6;
		color:white;
		display:inline;
	}
	.proj_tabs_title_active{
		background-color:rgb(0,112,192)!important;
		color:white!important;
	}
	.ach_business_fields{
		margin-bottom: 35px;
	}
	.ach_business_fields_insurance{
		margin-bottom: 18px;
	}
	.ach_insuranceBtns{
		border-radius: 0%;
		width: 100%;
		height: 70px;
		background-color: #003d6d;
		border: 1px solid grey;
		border-bottom: 0;
		font-size: 15px;
		color: #ffffff;
		font-family: Gotham-Bold;
		text-align: left;
		padding-left: 32px;
		outline:0;
	}
	.ach_insuranceBtns:hover{
		color:white;
		background-color:#f1f1f1;
		color: #003d6d;
	}
	.ach_insuranceBtns_active{
		color:white;
		background-color:#f1f1f1;
		color: #003d6d;
	}
	#save_successful{
		position:fixed;
		bottom:0;
		right:0;
		width:100%;
		z-index:10000;
	}
</style>

<div id="selectionProjDashboard" class="ui-widget-content feature-widget" style="display:none;position:fixed; top:180px; left:0px; z-index:999999999;">
	<div class="left-feat" role="document" style="left: 0px;height:520px;border-bottom-right-radius: 10px;">
		<div class="feature-content2 myWidgetProjDashboard" id="myWidget" style="background-color:#003d6d; width: 100px;height: 520px;border-bottom-right-radius: 10px;" >
			<button type="button" class="bookADemo btn-left" style="border-top-right-radius: 8px;width:100%; background-color:#3EDE41;font-family: Gotham-Bold !important;" onclick="openTrainingFeature();">Book a Demo</button>
			<div class="feature-body" onclick="expandSelection()" style="text-align: center;padding-top: 10px;cursor:pointer">
				<div color="white" style="display:inline-block;font-size:13px; font-family: Gotham-Medium !important; color:white;margin-top:0px;">Set Up Progress</div>
				<div id="progress_number" color="white" style="display:inline-block;font-size:15px; font-family: Gotham-Bold !important;margin:5px 0;color:white;font-weight:700;">
					<div style="display:inline-block">
						<div class="progress"id="progressFeat" style="display:none;">
							<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $percentage_val;?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $percentage_val;?>%">
								<span class="sr-only"></span>
							</div>
						</div>
					</div>
					<div id="ach_progress_count" style="display:inline-block">
						<input type="hidden" value="<?php echo $percentage_val; ?>" id="ach_progress_count_text"/>
						<?php echo $percentage_val; ?>%
					</div>
				</div>
				
				
				<img src="sb_tools/feature/arrow.png" class="next_pd feat_control" style="z-index:9999;display:block;top: 256px;">
				<img src="sb_tools/feature/arrow.png" class="back_pd feat_control rotated" style="z-index:9999;display:none;">
				
					<div class="">
						<?php
							if($visitedDemoProj == 1){
								echo '<img onmouseout="this.src=\'sb_tools/feature/done-check.png\'" onmouseover="this.src=\'sb_tools/feature/sample-project-0.png\'" src="sb_tools/feature/done-check.png" class=" achieve_icons achieve_icons_min ach_visitSampPoj" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="Visit Sample Project">';
							}else{
								echo '<img onmouseout="this.src=\'sb_tools/feature/sample-project-1.png\'" onmouseover="this.src=\'sb_tools/feature/sample-project-0.png\'" src="sb_tools/feature/sample-project-1.png" class="achieve_icons achieve_icons_min ach_visitSampPoj" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="Visit Sample Project">';
							}
						?>
					</div>
					<div class="">
						<?php
							if($addedContacts == 1){
								echo '<img onmouseout="this.src=\'sb_tools/feature/done-check.png\'" onmouseover="this.src=\'sb_tools/feature/contacts-0.png\'" src="sb_tools/feature/done-check.png" class="achieve_icons achieve_icons_min ach_viewContactsModal" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="Add Contacts">';
							}else{
								echo '<img onmouseout="this.src=\'sb_tools/feature/contacts-1.png\'" onmouseover="this.src=\'sb_tools/feature/contacts-0.png\'" src="sb_tools/feature/contacts-1.png" class="achieve_icons achieve_icons_min ach_viewContactsModal" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="Add Contacts">';
							}
						?>
					</div>
					<div class="">
						<?php
							if($addedLogo == 1){
								echo '<img onmouseout="this.src=\'sb_tools/feature/done-check.png\'" onmouseover="this.src=\'sb_tools/feature/logo-0.png\'" src="sb_tools/feature/done-check.png" class="achieve_icons achieve_icons_min ach_addLogo" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="Add Your Logo">';
							}else{
								echo '<img onmouseout="this.src=\'sb_tools/feature/logo-1.png\'" onmouseover="this.src=\'sb_tools/feature/logo-0.png\'" src="sb_tools/feature/logo-1.png" class="achieve_icons achieve_icons_min ach_addLogo" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="Add Your Logo">';
							}
						?>
					</div>
					<div class="">
						<?php
							if($completedBusDetails == 1){
								echo '<img onmouseout="this.src=\'sb_tools/feature/done-check.png\'" onmouseover="this.src=\'sb_tools/feature/business-details-0.png\'" src="sb_tools/feature/done-check.png" class="achieve_icons achieve_icons_min ach_completeBusiness" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="Complete Business Details">';
							}else{
								echo '<img onmouseout="this.src=\'sb_tools/feature/business-details-1.png\'" onmouseover="this.src=\'sb_tools/feature/business-details-0.png\'" src="sb_tools/feature/business-details-1.png" class="achieve_icons achieve_icons_min ach_completeBusiness" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="Complete Business Details">';
							}
						?>
					</div>
					<div class="">
						<?php
							if($addedProj == 1){
								echo '<img onmouseout="this.src=\'sb_tools/feature/done-check.png\'" onmouseover="this.src=\'sb_tools/feature/new-project-0.png\'" src="sb_tools/feature/done-check.png" class="achieve_icons achieve_icons_min ach_createNewProj" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="Create New Project">';
							}else{
								echo '<img onmouseout="this.src=\'sb_tools/feature/new-project-1.png\'" onmouseover="this.src=\'sb_tools/feature/new-project-0.png\'" src="sb_tools/feature/new-project-1.png" class="achieve_icons achieve_icons_min ach_createNewProj" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="Create New Project">';
							}
						?>
					</div>
					
					<?php
						if($visitedDemoProj == 1){
							echo '
								<button onmouseover="changeImageAchieve(\'1\',\'in\',\'sample-project\')" onmouseout="changeImageAchieve(\'1\',\'out\',\'done\')" class="achieve_btns btn ach_visitSampPoj" style="margin-top:20px">
									<img id="achieve_icon_1" src="sb_tools/feature/done-check.png" class="achieve_icons"> &nbsp;
									<div class="achieve_btns_txt">Visit Sample Project</div>
								</button>';
						}else{
							echo '
								<button onmouseover="changeImageAchieve(\'1\',\'in\',\'sample-project\')" onmouseout="changeImageAchieve(\'1\',\'out\',\'sample-project\')" class="achieve_btns btn ach_visitSampPoj" style="margin-top:20px">
									<img id="achieve_icon_1" src="sb_tools/feature/sample-project-1.png" class="achieve_icons"> &nbsp;
									<div class="achieve_btns_txt">Visit Sample Project</div>
								</button>';
						}
						
						if($addedContacts == 1){
							echo '
								<button onmouseover="changeImageAchieve(\'2\',\'in\',\'contacts\')" onmouseout="changeImageAchieve(\'2\',\'out\',\'done\')" class="achieve_btns btn ach_viewContactsModal">
									<img id="achieve_icon_2" src="sb_tools/feature/done-check.png" class="achieve_icons"> &nbsp;
									<div class="achieve_btns_txt">Add Contacts</div>
								</button>';
						}else{
							echo '
								<button onmouseover="changeImageAchieve(\'2\',\'in\',\'contacts\')" onmouseout="changeImageAchieve(\'2\',\'out\',\'contacts\')" class="achieve_btns btn ach_viewContactsModal">
									<img id="achieve_icon_2" src="sb_tools/feature/contacts-1.png" class="achieve_icons"> &nbsp;
									<div class="achieve_btns_txt">Add Contacts</div>
								</button>';
						}
						
						if($addedLogo == 1){
							echo '
								<button onmouseover="changeImageAchieve(\'3\',\'in\',\'logo\')" onmouseout="changeImageAchieve(\'3\',\'out\',\'done\')" class="achieve_btns btn ach_addLogo">
									<img id="achieve_icon_3" src="sb_tools/feature/done-check.png" class="achieve_icons"> &nbsp;
									<div class="achieve_btns_txt">Add Your Logo</div>
								</button>';
						}else{
							echo '
								<button onmouseover="changeImageAchieve(\'3\',\'in\',\'logo\')" onmouseout="changeImageAchieve(\'3\',\'out\',\'logo\')" class="achieve_btns btn ach_addLogo">
									<img id="achieve_icon_3" src="sb_tools/feature/logo-1.png" class="achieve_icons"> &nbsp;
									<div class="achieve_btns_txt">Add Your Logo</div>
								</button>';
						}
						
						if($completedBusDetails == 1){
							echo '
								<button onmouseover="changeImageAchieve(\'4\',\'in\',\'business-details\')" onmouseout="changeImageAchieve(\'4\',\'out\',\'done\')" class="achieve_btns btn ach_completeBusiness">
									<img id="achieve_icon_4" src="sb_tools/feature/done-check.png" class="achieve_icons"> &nbsp;
									<div class="achieve_btns_txt">Complete Business Details</div>
								</button>';
						}else{
							echo '
								<button onmouseover="changeImageAchieve(\'4\',\'in\',\'business-details\')" onmouseout="changeImageAchieve(\'4\',\'out\',\'business-details\')" class="achieve_btns btn ach_completeBusiness">
									<img id="achieve_icon_4" src="sb_tools/feature/business-details-1.png" class="achieve_icons"> &nbsp;
									<div class="achieve_btns_txt">Complete Business Details</div>
								</button>';
						}
						
						if($addedProj == 1){
							echo '
								<button onmouseover="changeImageAchieve(\'5\',\'in\',\'new-project\')" onmouseout="changeImageAchieve(\'5\',\'out\',\'done\')" class="achieve_btns btn">
									<img id="achieve_icon_5" src="sb_tools/feature/done-check.png" class="achieve_icons"> &nbsp;
									<div class="achieve_btns_txt">Create New Project</div>
								</button>';
						}else{
							echo '
								<button onmouseover="changeImageAchieve(\'5\',\'in\',\'new-project\')" onmouseout="changeImageAchieve(\'5\',\'out\',\'new-project\')" class="achieve_btns btn ach_createNewProj">
									<img id="achieve_icon_5" src="sb_tools/feature/new-project-1.png" class="achieve_icons"> &nbsp;
									<div class="achieve_btns_txt">Create New Project</div>
								</button>';
						}
					?>
			</div>
		</div>
    </div>
</div>

<div id="modalAchievableContact" class="modal fade animated fadeIn" role="dialog" style="font-family: 'Gotham-Bold';color:#003d6d">
	<div class="modal-content animated fadeInDown" style="width:70%;margin:50px auto;border-radius:20px;padding:20px">
		<button type="button" class="close" data-dismiss="modal" style="font-size: 30px;opacity: 0.7;color: black;" title="Close" >&times;</button>
		
		<div style="padding:0 100px;margin-bottom:45px">
			<button  type="button" style="width:100%; margin-top: 30px;" class="btnCustomSuccess btn btn-lg" data-dismiss="modal" onclick="ach_openContacts();getformdata(1);openContactForm();">Add Contacts through Small Builders</button><br>
			<label style="margin-top: 20px;">or</label><br>
			<label style="font-size: 20px;">Save time and integrate your contacts from of theses software</label>
			<br><br><br>
			
			<div class="row">
				<div class="col-xs-4">
					<div style="background-color:#e6f2f5;">
						<img src="images/integration_icons/xero.png" alt="xero" style="height:115px;margin-top:30px;">
						<button type="button" style="border-radius:10px;width:180px;margin:30px 0" data-dismiss="modal" onclick="ach_openXeroModal()" class="btn btn-md btn-primary" >Import</button>
					</div>
				</div>

				<div class="col-xs-4">
					<div style="background-color:#e6f2f5;">
						<img src="images/integration_icons/myob.png" alt="myob" style="height: 115px;margin-top:30px;">
						<button type="button" style="border-radius:10px;width:180px;margin:30px 0" class="btn btn-md btn-primary" onclick="redirectPageContact('myob')">Import</button>
					</div>
				</div>
				<div class="col-xs-4">
					<div style="background-color:#e6f2f5;">
						<img src="images/integration_icons/quickbooks.png" alt="qb" style="height:115px;margin-top:30px;">
						<button type="button" style="border-radius:10px;width:180px;margin:30px 0" class="btn btn-md btn-primary" onclick="redirectPageContact('quickbooks')">Import</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="ach_createNewProjModal" role="dialog" style="font-family:Gotham-Bold">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #003d6d;color: white;font-weight: bold;">
                <button type="button" class="close" style="color: white;opacity: 1;font-size: 28px;font-weight: normal;" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center" style="font-size:15px;">New Project</h4>
            </div>
            <div class="modal-body" style="padding:30px 50px">
                <label>Project Name</label>
                <input type="text" id="ach_projectnameForm" onkeyup="ach_checkProjectName()" class="form-control"/><br>
                <label>Project Address</label>
                <input type="text" id="ach_projectaddressForm" class="form-control" /><br>
                <label>Project Type</label>
				<select class="form-control" id="ach_projecttypeForm" style="font-size:12px;" >
                    <option value="Payment Claim System - Milestone, reference date, and stage work contracts" >Milestone / Lump Sum / Reference Date</option>
					<option value="Cost Plus Contract" >Cost Plus / Schedule of Rates</option>
                    <option value="General Payment Claim - One-off claim" >Do and Charge</option>
                </select><br><br>
				<center>
					<button type="button" class="btn btn-md btnCustomSuccess" data-dismiss="modal" style="width:100px" onclick="ach_save_submission();" >Save</button>
				</center>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ach_businessDetailsModal" data-backdrop="static" data-keyboard="false"  role="dialog">
    <div class="modal-dialog" style="width:80%;">
		<button style="z-index:999;position: absolute;right: -17px;top: -12px;background-color: red;color: white;opacity: 1;border-radius: 50%;width: 40px;height: 40px;padding-bottom: 9px;border: 3px #ffffff solid;font-size: 30px;box-shadow: -1px 1px 13px black;" type="button" class="close" onclick="ach_findNewChanges()">×</button>
		<div style="position: absolute;right: -15px;top: -27px;color: white;font-size: 11px;font-weight: bold;">CLOSE</div>
		<div class="modal-content" style="min-height:700px">
			<div class="col-xs-12 text-center" style="margin-bottom:50px;">
				<div style= "color: #237bc2; font-weight:bold; color:rgb(1,60,109); font-size:20px; font-family:Gotham-Bold;margin:40px 20px 20px 20px;">Business Profile</div>
				<button class="tablinks proj_tabs_title_active ach_bdt" onclick="ach_openTab('ach_bdt', 'ach_insurancetab_business')">Business Details</button>
				<button class="tablinks ach_cdt" onclick="ach_openTab('ach_cdt', 'ach_insurancetab_contact')">Contact Details</button>
				<button class="tablinks ach_clt" onclick="ach_openTab('ach_clt', 'ach_insurancetab_contractor')">Contractor License</button>
				<button class="tablinks ach_badt" onclick="ach_openTab('ach_badt', 'ach_insurancetab_bank')">Bank Details</button>
				<button class="tablinks ach_idt" onclick="ach_openTab('ach_idt', 'ach_insurancetab_insurance')">Bank Details</button>
				
			</div>
			
			<div id="ach_insurancetab_business" class="tabcontent" style="padding: 20px 40px;">
				<div class="col-md-6 ach_business_fields">
					<label>Business ABN</label>
					<input id="ach_business_abn" value="<?php echo $ach_business_abn; ?>" type="text" class="form-control" style="display: inline-block;padding-right: 100px;"/>
					<input id="ach_business_abn_og" value="<?php echo $ach_business_abn; ?>" type="hidden" class="form-control" style="display: none;padding-right: 100px;"/>
					<button class="btn btn-md btn-primary" type="button" style="height: 34px;border-radius: 0;border-bottom-right-radius: 7px;border-top-right-radius: 7px;position: absolute;right: 13px;top: 24px;width:100px" onclick="ach_verifybtn()">Verify</button>
				</div>
				<div class="col-md-6 ach_business_fields">
					<label>Entity Type</label>
					<input id="ach_business_entitytype" value="<?php echo $ach_business_entitytype; ?>" type="text" class="form-control"/>
					<input id="ach_business_entitytype_og" value="<?php echo $ach_business_entitytype; ?>" type="hidden" class="form-control"/>
				</div>
				<div class="col-md-6 ach_business_fields">
					<label>Business Name*</label>
					<input id="ach_business_name" value="<?php echo $ach_business_name; ?>" type="text" class="form-control" />
					<input id="ach_business_name_og" value="<?php echo $ach_business_name; ?>" type="hidden" class="form-control" />
				</div>
				<div class="col-md-6 ach_business_fields">
					<label>State</label>
					<input id="ach_business_state" value="<?php echo $ach_business_state; ?>" type="text" class="form-control" />
					<input id="ach_business_state_og" value="<?php echo $ach_business_state; ?>" type="hidden" class="form-control" />
				</div>
				<div class="col-md-6 ach_business_fields">
					<label>Address</label>
					<input type="text" id="ach_business_address" value="<?php echo $ach_business_address; ?>" class="form-control" />
					<input type="hidden" id="ach_business_address_og" value="<?php echo $ach_business_address; ?>" class="form-control" />
				</div>
				<div class="col-md-6 ach_business_fields">
					<label>Postcode</label>
					<input id="ach_business_postcode" value="<?php echo $ach_business_postcode; ?>" type="text" class="form-control" />
					<input id="ach_business_postcode_og" value="<?php echo $ach_business_postcode; ?>" type="hidden" class="form-control" />
				</div>
				<div class="col-md-6 ach_business_fields">
					<label>Trading Name</label>
					<input id="ach_business_tradingname" value="<?php echo $ach_business_tradingname; ?>" type="text" class="form-control" />
					<input id="ach_business_tradingname_og" value="<?php echo $ach_business_tradingname; ?>" type="hidden" class="form-control" />
				</div>
				<div class="col-md-6 ach_business_fields">
					<label>GST Registered Since</label>
					<input id="ach_business_gstregister" value="<?php echo $ach_business_gstregister; ?>" type="text" class="form-control date date_picker" />
					<input id="ach_business_gstregister_og" value="<?php echo $ach_business_gstregister; ?>" type="hidden" class="form-control date date_picker" />
				</div>
				<center>
					<button type="button" class="btn btn-md btnCustomSuccess" onclick="ach_save_business_details('ach_cdt', 'ach_insurancetab_contact', 1)" style="margin-top: 30px;width:100px;">Save</button>
				</center>
			</div>

			<div id="ach_insurancetab_contact" class="tabcontent" style="padding: 20px 40px;display:none">
				<div class="col-md-12 ach_business_fields">
					<label>Business Phone Number</label>
					<input id="ach_business_phone" value="<?php echo $ach_business_phone; ?>" type="text" class="form-control" />
					<input id="ach_business_phone_og" value="<?php echo $ach_business_phone; ?>" type="hidden" class="form-control" />
				</div>
				<div class="col-md-12 ach_business_fields">
					<label>Business Fax Number</label>
					<input id="ach_business_fax" value="<?php echo $ach_business_fax; ?>" type="text" class="form-control" />
					<input id="ach_business_fax_og" value="<?php echo $ach_business_fax; ?>" type="hidden" class="form-control" />
				</div>
				<div class="col-md-12 ach_business_fields">
					<label>Business Email Address</label>
					<input id="ach_business_email" value="<?php echo $ach_business_email; ?>" type="text" class="form-control" />
					<input id="ach_business_email_og" value="<?php echo $ach_business_email; ?>" type="hidden" class="form-control" />
				</div>
				<center>
					<button type="button" class="btn btn-md btnCustomSuccess" onclick="ach_save_business_details('ach_clt', 'ach_insurancetab_contractor', 2)" style="margin-top: 30px;width:100px;">Save</button>
				</center>
			</div>

			<div id="ach_insurancetab_contractor" class="tabcontent" style="padding: 20px 40px; display:none">
				<div class="col-md-12 ach_business_fields">
					<label>Contractor License</label>
					<input id="ach_business_contractor_license" value="<?php echo $ach_business_contractor_license; ?>" type="text" class="form-control" />
					<input id="ach_business_contractor_license_og" value="<?php echo $ach_business_contractor_license; ?>" type="hidden" class="form-control" />
				</div>
				<div class="col-md-12 ach_business_fields">
					<label>Valid Until</label>
					<input id="ach_business_contractor_validity" value="<?php echo $ach_business_contractor_validity; ?>" type="text" class="form-control date date_picker" />
					<input id="ach_business_contractor_validity_og" value="<?php echo $ach_business_contractor_validity; ?>" type="hidden" class="form-control date date_picker" />
				</div>
				<center>
					<button type="button" class="btn btn-md btnCustomSuccess" onclick="ach_save_business_details('ach_badt', 'ach_insurancetab_bank', 3)" style="margin-top: 30px;width:100px;">Save</button>
				</center>
			</div>

			<div id="ach_insurancetab_bank" class="tabcontent" style="padding: 20px 40px; display:none">
				<div class="col-md-12 ach_business_fields">
					<label>Specify the bank details that you want on your Invoices and Payment Claims</label>
				</div>
				<div class="col-md-12 ach_business_fields">
					<label>Bank Account Name</label>
					<input id="ach_business_bank_name" value="<?php echo $ach_business_bank_name; ?>" type="text" class="form-control" />
					<input id="ach_business_bank_name_og" value="<?php echo $ach_business_bank_name; ?>" type="hidden" class="form-control" />
				</div>
				<div class="col-md-12 ach_business_fields">
					<label>Bank Account Number</label>
					<input id="ach_business_bank_number" value="<?php echo $ach_business_bank_number; ?>" type="text" class="form-control" />
					<input id="ach_business_bank_number_og" value="<?php echo $ach_business_bank_number; ?>" type="hidden" class="form-control" />
				</div>
				<div class="col-md-12 ach_business_fields">
					<label>BSB Number</label>
					<input id="ach_business_bank_bsb" value="<?php echo $ach_business_bank_bsb; ?>" type="text" class="form-control" />
					<input id="ach_business_bank_bsb_og" value="<?php echo $ach_business_bank_bsb; ?>" type="hidden" class="form-control" />
				</div>
				<center>
					<button type="button" class="btn btn-md btnCustomSuccess" onclick="ach_save_business_details('ach_idt', 'ach_insurancetab_insurance', 4)" style="margin-top: 30px;width:100px;">Save</button>
				</center>
			</div>
			
			<div id="ach_insurancetab_insurance" class="tabcontent" style="padding: 20px 45px 10px 30px;display:none">
				<div class="col-xs-5" style="font-family: 'Gotham-Regular';padding-right:0;">
					<button type="button" id="ach_insur_btn1" onclick="ttlChange(this.id)" class="btn ach_insuranceBtns ach_insuranceBtns_active">Worker's Compensation Insurance</button>
					<button type="button" id="ach_insur_btn2" onclick="ttlChange(this.id)" class="btn ach_insuranceBtns">Professional Idemnity Insurance</button>
					<button type="button" id="ach_insur_btn3" onclick="ttlChange(this.id)" class="btn ach_insuranceBtns">Contract Works Insurance</button>
					<button type="button" id="ach_insur_btn4" onclick="ttlChange(this.id)" class="btn ach_insuranceBtns">Public and Products Liability Insurance</button>
					<button type="button" id="ach_insur_btn5" onclick="ttlChange(this.id)" class="btn ach_insuranceBtns" style="border: 1px solid grey;">Other Insurance</button>
				</div>
				<div class="col-xs-7" style="height: 350px;background-color: #ffffff;border: 1px solid grey;border-left: 0;">
					<div id="ach_insuranceTitle" style="margin: 20px;text-align:center;color:#003d6d; font-family: 'Gotham-Bold';font-size:20px;font-size: 19px;">
						Worker`s Compensation Insurance
					</div>
					<div class="col-xs-12 ach_insuranceWCItab" id="ach_insuranceWCItab_1">
						<div class="col-md-12 ach_business_fields_insurance">
							<label>Name of Insurer</label>
							<input id="ach_business_insurer_WCI" value="<?php echo $ach_business_insurer_WCI; ?>" type="text" class="form-control" />
							<input id="ach_business_insurer_WCI_og" value="<?php echo $ach_business_insurer_WCI; ?>" type="hidden" class="form-control" />
						</div>
						<div class="col-md-12 ach_business_fields_insurance">
							<label>Policy Number</label>
							<input id="ach_business_policy_number_WCI" value="<?php echo $ach_business_policy_number_WCI; ?>" type="text" class="form-control" />
							<input id="ach_business_policy_number_WCI_og" value="<?php echo $ach_business_policy_number_WCI; ?>" type="hidden" class="form-control" />
						</div>
						<div class="col-md-12 ach_business_fields_insurance">
							<label>Valid Until</label>
							<input id="ach_business_insurance_validity_WCI" value="<?php echo $ach_business_insurance_validity_WCI; ?>" type="text" class="form-control date date_picker" />
							<input id="ach_business_insurance_validity_WCI_og" value="<?php echo $ach_business_insurance_validity_WCI; ?>" type="hidden" class="form-control date date_picker" />
						</div>
						<center>
							<button type="button" onclick="ach_save_business_details('ach_idt', 'ach_insur_btn2', 5)" class="btn btn-md btnCustomSuccess" style="width:100px;">Save</button>
						</center>
					</div>
					<div class="col-xs-12 ach_insuranceWCItab" style="display:none" id="ach_insuranceWCItab_2">
						<div class="col-md-12 ach_business_fields_insurance">
							<label>Name of Insurer</label>
							<input id="ach_business_insurer_PII" type="text" value="<?php echo $ach_business_insurer_PII; ?>" class="form-control" />
							<input id="ach_business_insurer_PII_og" type="hidden" value="<?php echo $ach_business_insurer_PII; ?>" class="form-control" />
						</div>
						<div class="col-md-12 ach_business_fields_insurance">
							<label>Policy Number</label>
							<input id="ach_business_policy_number_PII" type="text" value="<?php echo $ach_business_policy_number_PII; ?>" class="form-control" />
							<input id="ach_business_policy_number_PII_og" type="hidden" value="<?php echo $ach_business_policy_number_PII; ?>" class="form-control" />
						</div>
						<div class="col-md-12 ach_business_fields_insurance">
							<label>Valid Until</label>
							<input id="ach_business_insurance_validity_PII" type="text" value="<?php echo $ach_business_insurance_validity_PII; ?>" class="form-control date date_picker" />
							<input id="ach_business_insurance_validity_PII_og" type="hidden" value="<?php echo $ach_business_insurance_validity_PII; ?>" class="form-control date date_picker" />
						</div>
						<center>
							<button type="button" onclick="ach_save_business_details('ach_idt', 'ach_insur_btn3', 5)" class="btn btn-md btnCustomSuccess" style="width:100px;">Save</button>
						</center>
					</div>
					<div class="col-xs-12 ach_insuranceWCItab" style="display:none" id="ach_insuranceWCItab_3">
						<div class="col-md-12 ach_business_fields_insurance">
							<label>Name of Insurer</label>
							<input id="ach_business_insurer_CWI" value="<?php echo $ach_business_insurer_CWI; ?>" type="text" class="form-control" />
							<input id="ach_business_insurer_CWI_og" value="<?php echo $ach_business_insurer_CWI; ?>" type="hidden" class="form-control" />
						</div>
						<div class="col-md-12 ach_business_fields_insurance">
							<label>Policy Number</label>
							<input id="ach_business_policy_number_CWI" value="<?php echo $ach_business_policy_number_CWI; ?>" type="text" class="form-control" />
							<input id="ach_business_policy_number_CWI_og" value="<?php echo $ach_business_policy_number_CWI; ?>" type="hidden" class="form-control" />
						</div>
						<div class="col-md-12 ach_business_fields_insurance">
							<label>Valid Until</label>
							<input id="ach_business_insurance_validity_CWI" value="<?php echo $ach_business_insurance_validity_CWI; ?>" type="text" class="form-control date date_picker" />
							<input id="ach_business_insurance_validity_CWI_og" value="<?php echo $ach_business_insurance_validity_CWI; ?>" type="hidden" class="form-control date date_picker" />
						</div>
						<center>
							<button type="button" onclick="ach_save_business_details('ach_idt', 'ach_insur_btn4', 5)" class="btn btn-md btnCustomSuccess" style="width:100px;">Save</button>
						</center>
					</div>
					<div class="col-xs-12 ach_insuranceWCItab" style="display:none" id="ach_insuranceWCItab_4">
						<div class="col-md-12 ach_business_fields_insurance">
							<label>Name of Insurer</label>
							<input id="ach_business_insurer_PPLI" value="<?php echo $ach_business_insurer_PPLI; ?>" type="text" class="form-control" />
							<input id="ach_business_insurer_PPLI_og" value="<?php echo $ach_business_insurer_PPLI; ?>" type="hidden" class="form-control" />
						</div>
						<div class="col-md-12 ach_business_fields_insurance">
							<label>Policy Number</label>
							<input id="ach_business_policy_number_PPLI" value="<?php echo $ach_business_policy_number_PPLI; ?>" type="text" class="form-control" />
							<input id="ach_business_policy_number_PPLI_og" value="<?php echo $ach_business_policy_number_PPLI; ?>" type="hidden" class="form-control" />
						</div>
						<div class="col-md-12 ach_business_fields_insurance">
							<label>Valid Until</label>
							<input id="ach_business_insurance_validity_PPLI" value="<?php echo $ach_business_insurance_validity_PPLI; ?>" type="text" class="form-control date date_picker" />
							<input id="ach_business_insurance_validity_PPLI_og" value="<?php echo $ach_business_insurance_validity_PPLI; ?>" type="hidden" class="form-control date date_picker" />
						</div>
						<center>
							<button type="button" onclick="ach_save_business_details('ach_idt', 'ach_insur_btn5', 5)" class="btn btn-md btnCustomSuccess" style="width:100px;">Save</button>
						</center>
					</div>
					<div class="col-xs-12 ach_insuranceWCItab" style="display:none" id="ach_insuranceWCItab_5">
						<div class="col-md-6 ach_business_fields_insurance">
							<label>Insurance Type</label>
							<input id="ach_business_insurance_type_OI" value="<?php echo $ach_business_insurance_type_OI; ?>" type="text" class="form-control" />
							<input id="ach_business_insurance_type_OI_og" value="<?php echo $ach_business_insurance_type_OI; ?>" type="hidden" class="form-control" />
						</div>
						<div class="col-md-6 ach_business_fields_insurance">
							<label>Name of Insurer</label>
							<input id="ach_business_insurer_OI" value="<?php echo $ach_business_insurer_OI; ?>" type="text" class="form-control" />
							<input id="ach_business_insurer_OI_og" value="<?php echo $ach_business_insurer_OI; ?>" type="hidden" class="form-control" />
						</div>
						<div class="col-md-6 ach_business_fields_insurance">
							<label>Policy Number</label>
							<input id="ach_business_policy_number_OI" value="<?php echo $ach_business_policy_number_OI; ?>" type="text" class="form-control" />
							<input id="ach_business_policy_number_OI_og" value="<?php echo $ach_business_policy_number_OI; ?>" type="hidden" class="form-control" />
						</div>
						<div class="col-md-6 ach_business_fields_insurance">
							<label>Coverage Until</label>
							<input id="ach_business_insurance_validity_OI" value="<?php echo $ach_business_insurance_validity_OI; ?>" type="text" class="form-control date date_picker" />
							<input id="ach_business_insurance_validity_OI_og" value="<?php echo $ach_business_insurance_validity_OI; ?>" type="hidden" class="form-control date date_picker" />
						</div>
						<center>
							<button type="button" onclick="ach_save_business_details('ach_idt', 'ach_insur_btn6', 5)" class="btn btn-md btnCustomSuccess" style="width:100px;">Save</button>
						</center>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>

<div id="save_successful" style="display:none;">
    <div>
        <div id="inner-message" class="alert alert-success alert-fixed" style="width:300px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fa fa-check"></i> Saved..
        </div>
    </div>
</div>

<div class="modal fade" id="ach_modalExitBusinessModal">
    <div class="modal-dialog" style="padding-top:200px">
        <div class="modal-content">
            <div class="modal-body" style="border-radius: 5px;">
                <button type="button" class="close" data-dismiss="modal" style="font-size: 35px; margin-top: -10px;">&times;</button>
                <h2 style="font-family: Gotham-Bold; color: #003d6d; text-align: center; margin-top: 30px; margin-bottom: 40px;">Changes you made may not be saved. Would you like to save your changes?</h2>
				<div class="row">
					<div style="text-align: right;" class="col-xs-6">
						<button type="button" onclick="ach_save_business_details('ach_idt', 'ach_insur_btn6', 5)" class="btn btn-success btn-md btnCustomSuccess" style="font-size: 11pt; width: 85%" data-dismiss="modal">Save</button>
					</div>
					<div style="text-align: left; margin-bottom: 30px;" class="col-xs-6">
						<button onclick="ach_closeBusinessModal();" type="button" class="btn btn-md btnCustomDefault" style="font-size: 11pt; width: 85%;" data-dismiss="modal">No, thanks</button>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>

<script>
	$(document).ready(function(){
		<?php
			//echo "alert('".$date_reg_diff." ".$date_registered_set."');";
			if($achieve_counter < 5 && $user_is_new == 1 && $_user_is_owner == 1 && !isset($_GET['usr_stat'])){
				echo "$('#selectionProjDashboard').show();"; 
			}
		?>
		$('[data-toggle="popover"]').popover();
		newProjectAddress = new google.maps.places.Autocomplete(document.getElementById('ach_projectaddressForm'));
		newProjectAddress.setComponentRestrictions({'country': ['au']});
		newVerifyABNProjectAddress = new google.maps.places.Autocomplete(document.getElementById('ach_business_address'));
		newVerifyABNProjectAddress.setComponentRestrictions({'country': ['au']});
		if($(".date_picker").length !== 0){
			$('.date_picker').datetimepicker({timepicker: false, format: 'd-m-Y', scrollMonth:false, scrollInput:false, validateOnBlur:false});
		}
		ach_openTab('ach_bdt', 'ach_insurancetab_business');
	});
	
	function ach_openXeroModal(){
		$('#modalIntegrationContacts').modal('show');
		openInteg('integConn','integXero');
	}
	
	var changesCounter = 0;
	function ach_findNewChanges(){
		changesCounter = 0;
		if($('#ach_business_abn').val() != $('#ach_business_abn_og').val()){ changesCounter++; }
		if($('#ach_business_address').val() != $('#ach_business_address_og').val()){ changesCounter++;}
		if($('#ach_business_name').val() != $('#ach_business_name_og').val()){ changesCounter++; }
		if($('#ach_business_state').val() != $('#ach_business_state_og').val()){ changesCounter++; }
		if($('#ach_business_tradingname').val() != $('#ach_business_tradingname_og').val()){ changesCounter++; }
		if($('#ach_business_entitytype').val() != $('#ach_business_entitytype_og').val()){ changesCounter++; }
		if($('#ach_business_postcode').val() != $('#ach_business_postcode_og').val()){ changesCounter++; }
		if($('#ach_business_gstregister').val() != $('#ach_business_gstregister_og').val()){ changesCounter++; }
		if($('#ach_business_phone').val() != $('#ach_business_phone_og').val()){ changesCounter++; }
		if($('#ach_business_email').val() != $('#ach_business_email_og').val()){ changesCounter++; }
		if($('#ach_business_fax').val() != $('#ach_business_fax_og').val()){ changesCounter++; }
		if($('#ach_business_contractor_license').val() != $('#ach_business_contractor_license_og').val()){ changesCounter++; }
		if($('#ach_business_contractor_validity').val() != $('#ach_business_contractor_validity_og').val()){ changesCounter++; }
		if($('#ach_business_bank_name').val() != $('#ach_business_bank_name_og').val()){ changesCounter++; }
		if($('#ach_business_bank_number').val() != $('#ach_business_bank_number_og').val()){ changesCounter++; }
		if($('#ach_business_bank_bsb').val() != $('#ach_business_bank_bsb_og').val()){ changesCounter++; }
		if($('#ach_business_insurer_WCI').val() != $('#ach_business_insurer_WCI_og').val()){ changesCounter++; }
		if($('#ach_business_insurer_PII').val() != $('#ach_business_insurer_PII_og').val()){ changesCounter++; }
		if($('#ach_business_insurer_CWI').val() != $('#ach_business_insurer_CWI_og').val()){ changesCounter++; }
		if($('#ach_business_insurer_PPLI').val() != $('#ach_business_insurer_PPLI_og').val()){ changesCounter++; }
		if($('#ach_business_insurer_OI').val() != $('#ach_business_insurer_OI_og').val()){ changesCounter++; }
		if($('#ach_business_policy_number_WCI').val() != $('#ach_business_policy_number_WCI_og').val()){ changesCounter++;}
		if($('#ach_business_policy_number_PII').val() != $('#ach_business_policy_number_PII_og').val()){ changesCounter++; }
		if($('#ach_business_policy_number_CWI').val() != $('#ach_business_policy_number_CWI_og').val()){ changesCounter++; }
		if($('#ach_business_policy_number_PPLI').val() != $('#ach_business_policy_number_PPLI_og').val()){ changesCounter++; }
		if($('#ach_business_policy_number_OI').val() != $('#ach_business_policy_number_OI_og').val()){ changesCounter++; }	
		if($('#ach_business_insurance_validity_WCI').val() != $('#ach_business_insurance_validity_WCI_og').val()){ changesCounter++; }
		if($('#ach_business_insurance_validity_PII').val() != $('#ach_business_insurance_validity_PII_og').val()){ changesCounter++; }
		if($('#ach_business_insurance_validity_CWI').val() != $('#ach_business_insurance_validity_CWI_og').val()){ changesCounter++; }
		if($('#ach_business_insurance_validity_PPLI').val() != $('#ach_business_insurance_validity_PPLI_og').val()){ changesCounter++;}
		if($('#ach_business_insurance_validity_OI').val() != $('#ach_business_insurance_validity_OI_og').val()){ changesCounter++;}
		
		if(changesCounter > 0){
			$('#ach_modalExitBusinessModal').modal('show');
		}else{
			$('#ach_businessDetailsModal').modal('hide');
		}
	}
	
	$(".ach_visitSampPoj").on('click', function (e) {
		e.stopPropagation();
		$("#modalAchievableContact").modal("hide");
		$("#modalDemo2").removeClass("modalDemoCustom").removeAttr("data-thisindex").modal("hide");
		$("#ach_businessDetailsModal").modal("hide");
		$("#ach_createNewProjModal").modal("hide");
		$("#business_contacts").modal("hide");
		<?php
			if ($sample_pid) {
				echo "window.location='dashboard_projects.php?ui=".$ui."&ci=".$ci."&pid=".$sample_pid."'";
			}
		?>
	});
	
	$(".ach_viewContactsModal").on('click', function (e) {
		e.stopPropagation();
		$("#modalDemo2").removeClass("modalDemoCustom").removeAttr("data-thisindex").modal("hide");
		$("#ach_businessDetailsModal").modal("hide");
		$("#ach_createNewProjModal").modal("hide");
		$("#business_contacts").modal("hide");
		$("#modalAchievableContact").modal("show");
	});
	
	$(".ach_addLogo").on('click', function (e) {
		e.stopPropagation();
		$("#modalAchievableContact").modal("hide");
		$("#ach_businessDetailsModal").modal("hide");
		$("#ach_createNewProjModal").modal("hide");
		$("#modalDemo2").removeClass("modalDemoCustom").removeAttr("data-thisindex").modal("show");
		changeModalText();
		$("#business_contacts").modal("hide");
	});
	
	$(".ach_completeBusiness").on('click', function (e) {
		e.stopPropagation();
		$("#modalAchievableContact").modal("hide");
		$("#modalDemo2").removeClass("modalDemoCustom").removeAttr("data-thisindex").modal("hide");
		$("#ach_createNewProjModal").modal("hide");
		$("#ach_businessDetailsModal").modal("show");
		$("#business_contacts").modal("hide");
	});
	
	$(".ach_createNewProj").on('click', function (e) {
		e.stopPropagation();
		$("#modalAchievableContact").modal("hide");
		$("#modalDemo2").removeClass("modalDemoCustom").removeAttr("data-thisindex").modal("hide");
		$("#ach_businessDetailsModal").modal("hide");
		$("#ach_createNewProjModal").modal("show");
		$("#business_contacts").modal("hide");
	});
	
	function ach_checkProjectName(){
        var str = $.trim($('#ach_projectnameForm').val());
        var regex = /[`~'"]/gi;

        if(regex.test(str) == true) {
            alert('Please remove apostrophe or quotation marks from the Project Name.');
            $('#ach_projectnameForm').focus();
            $('#ach_projectnameForm').val("");
        }
        
        if(str!=""){
            $.post("controller/formsource/new_project/check_projectname.php", {
                projectname:str,
                client_id:"<?php echo $dec_ci; ?>"
            }, function(e) {
                if(e!=0){
                    alert("Project name already exist. Please go to Management > Control Panel > My Projects to check your existing projects.");
                    $('#ach_projectnameForm').val("");
                }
            });
        }
	}
	
	function ach_openContacts(){
		$("#business_contacts").modal('show');
	}
	
    function ach_save_submission(){
        var projectname = $("#ach_projectnameForm").val().trim();
        var projectaddress = $("#ach_projectaddressForm").val().trim();
        var typeprojects = $("#ach_projecttypeForm").val();
        if(projectname!=="" && projectaddress!==""){
            $('.saveProjBtn').text('Saving...');
            $('.saveProjBtn' ).css( "pointer-events", "none" );
            
            $.post("controller/formsource/dashboard_project/controller/saveproject.php", {
                projectname: projectname,
                projectaddress: projectaddress,
                typeprojects: typeprojects,
                ci:'<?php echo $dec_ci ?>',
                submitted_by: '<?php echo $dec_ui ?>',
				email: '<?php echo $_user_email ?>',
				user_fname: '<?php echo $_user_firstname ?>',
				user_lname: '<?php echo $_user_lastname ?>'
            }, function(e) {
				$('#project_saved').show();
				$('.saveProjBtn').text('Saved!');
                setTimeout(function(){
					$('#project_saved').fadeOut();
					
					<?php
						$query_count_project = "SELECT * FROM _submission_204 WHERE JClientID140400 = ".$dec_ci."";
						$count_project = mysqli_query($theodore_con, $query_count_project);

						if ($count_project) {
							if (mysqli_num_rows($count_project) == 1) {
							   $npid = '&npid=1';
							}
						}
							
						$hi = mysqli_num_rows($count_project);
					?>
					var ebase = window.btoa(e);
						var npid = '<?php echo $npid; ?>';
					window.location = 'dashboard_projects.php?ui=<?php echo $ui;?>&ci=<?php echo $ci;?>&pid='+ebase+''+npid+'';
                }, 1000);
			});
        }else{
            alert("Please complete the details.");
        }
    }
	
	function expandSelection(){
		if($(".next_pd").is(":visible")){
			$(".myWidgetProjDashboard").delay(200).animate({
				width:'350px'
			});
			$(".next_pd").delay(500).fadeOut();
			$(".back_pd").delay(500).fadeIn();
			$("#progressFeat").delay(500).fadeIn();
			$(".achieve_btns").delay(600).fadeIn();
			$(".achieve_icons_min").hide();
		}else{
			$(".myWidgetProjDashboard").delay(200).animate({
				width:'100px'
			});
			$(".next_pd").delay(500).fadeIn();
			$(".back_pd").delay(500).fadeOut();
			$("#progressFeat").fadeOut();
			$(".achieve_btns").hide();
			$(".achieve_icons_min").delay(600).fadeIn();
		}
	}
	function changeImageAchieve(idVal, type, srcFile){
		if(type=="in"){
			$("#achieve_icon_"+idVal).attr("src", "sb_tools/feature/"+srcFile+"-0.png");
		}else{
			if(srcFile != 'done'){
				$("#achieve_icon_"+idVal).attr("src", "sb_tools/feature/"+srcFile+"-1.png");
			}else{
				$("#achieve_icon_"+idVal).attr("src", "sb_tools/feature/done-check.png");
			}
		}
	}
	function changeModalText(){
		$("#modalDemo2text").text("Let\'s upload your logo!");
		$("#modalDemo2SubText").show();
		$("#modalDemo2").removeClass("modalDemoCustom").removeAttr("data-thisindex");
	}
	function ttlChange(clicked_ID){
		console.log(clicked_ID);
		var html = "";

		$(".ach_insuranceBtns").removeClass("ach_insuranceBtns_active");
		$("#"+clicked_ID).addClass("ach_insuranceBtns_active");
		$(".ach_insuranceWCItab").hide();
		
		if(clicked_ID == "ach_insur_btn1"){
			$("#ach_insuranceWCItab_1").show();
			html = 'Worker`s Compensation Insurance';
		} else if (clicked_ID == "ach_insur_btn2"){
			$("#ach_insuranceWCItab_2").show();
			html = 'Professional Idemnity Insurance';
		} else if (clicked_ID == "ach_insur_btn3"){
			$("#ach_insuranceWCItab_3").show();
			html = 'Contract Works Insurance';
		} else if (clicked_ID == "ach_insur_btn4"){
			$("#ach_insuranceWCItab_4").show();
			html = 'Public and Products Liability Insurance';
		} else if (clicked_ID == "ach_insur_btn5"){
			$("#ach_insuranceWCItab_5").show();
			html = 'Other Insurance';
		} else {
			console.log("Error")
		}

		$("#ach_insuranceTitle").text(html);
	}
	function ach_openTab(evt, cityName) {
		var i, tabcontent, tablinks;
		tabcontent = document.getElementsByClassName("tabcontent");
		for (i = 0; i < tabcontent.length; i++) {
			tabcontent[i].style.display = "none";
		}
		tablinks = document.getElementsByClassName("tablinks");
		for (i = 0; i < tablinks.length; i++) {
			tablinks[i].className = tablinks[i].className.replace(" proj_tabs_title_active", "");
		}
		document.getElementById(cityName).style.display = "block";
		$('.'+evt).addClass('proj_tabs_title_active');
	}
	
	function ach_verifybtn() {
		$('#modalABNBusinessNameTitle').text("Business List");
		$('#tableABNBusinessName').parents('div.dataTables_wrapper').first().hide();
		$('#divVerifyABNBusinessName').hide();
		$('#loadingVerifyABNBusinessName').show();

		if ($('#ach_business_abn').val() == "" && $('#ach_business_name').val() == "") {
			alert("INVALID ABN / BUSINESS NAME");
		} else {
			$('#modalVerifyABNBusinessName').modal('show');

			var formdata = {
				type: "SEARCH",
				abn: $('#ach_business_abn').val(),
				businessname: $('#ach_business_name').val(),
			};

			$.ajax({
				type: "POST",
				url: "controller/formsource/abn_source/ajaxSearchABNBusinessName.php",
				data: formdata,
				dataType: "JSON",
				success: function(data) {
					if (data.status) {
						tableABNBusinessName.clear().draw();
						$.each(data.abnbusinessname, function(index, value) {
							var row =
								"<tr data-thisabn='"+value.abn+"'>"+
									"<td style='text-align:center;'>"+value.abn+"</td>"+
									"<td>"+value.businessname+"</td>"+
									"<td style='text-align:center;'>"+value.state+"</td>"+
									"<td style='text-align:right;'>"+
										"<button type='button' class='btn btn-success btn-sm classBtnSelectABNBusinessName'>Select</button>"+
									"</td>"+
								"</tr>";
							$('#ach_business_name').val(value.businessname);
							$('#ach_business_state').val(value.state);
							$('#ach_business_tradingname').val(value.tradingname);
							$('#ach_business_entitytype').val(value.entitytype);
							$('#ach_business_postcode').val(value.postcode);
							$('#ach_business_gstregister').val(gstregister);
							
							tableABNBusinessName.row.add($(row)[0]).draw();
						});
					}
				},
				error: function(data) {
					console.log(data);
				},
				complete: function(data) {
					$('#tableABNBusinessName').parents('div.dataTables_wrapper').first().show();
					$('#loadingVerifyABNBusinessName').hide();
				}
			});
		}
	}
	var completeBusiness = 0;
	function ach_save_business_details(next, curr, type){
		if ($('#ach_business_name').val() != "") {
			var ach_business_abn = $('#ach_business_abn').val();
			var ach_business_address = $('#ach_business_address').val();
			var ach_business_name = $('#ach_business_name').val();
			
			var ach_business_state = $('#ach_business_state').val();
			var ach_business_tradingname = $('#ach_business_tradingname').val();
			var ach_business_entitytype = $('#ach_business_entitytype').val();
			var ach_business_postcode = $('#ach_business_postcode').val();
			var ach_business_gstregister = $('#ach_business_gstregister').val();
			
			var ach_business_phone = $('#ach_business_phone').val();
			var ach_business_email = $('#ach_business_email').val();
			var ach_business_fax = $('#ach_business_fax').val();
			
			var ach_business_contractor_license = $('#ach_business_contractor_license').val();
			var ach_business_contractor_validity = $('#ach_business_contractor_validity').val();
			
			var ach_business_bank_name = $('#ach_business_bank_name').val();
			var ach_business_bank_number = $('#ach_business_bank_number').val();
			var ach_business_bank_bsb = $('#ach_business_bank_bsb').val();
			
			var ach_business_insurer_WCI = $('#ach_business_insurer_WCI').val();
			var ach_business_insurer_PII = $('#ach_business_insurer_PII').val();
			var ach_business_insurer_CWI = $('#ach_business_insurer_CWI').val();
			var ach_business_insurer_PPLI = $('#ach_business_insurer_PPLI').val();
			var ach_business_insurer_OI = $('#ach_business_insurer_OI').val();
			
			var ach_business_policy_number_WCI = $('#ach_business_policy_number_WCI').val();
			var ach_business_policy_number_PII = $('#ach_business_policy_number_PII').val();
			var ach_business_policy_number_CWI = $('#ach_business_policy_number_CWI').val();
			var ach_business_policy_number_PPLI = $('#ach_business_policy_number_PPLI').val();
			var ach_business_policy_number_OI = $('#ach_business_policy_number_OI').val();
			
			var ach_business_insurance_validity_WCI = $('#ach_business_insurance_validity_WCI').val();
			var ach_business_insurance_validity_PII = $('#ach_business_insurance_validity_PII').val();
			var ach_business_insurance_validity_CWI = $('#ach_business_insurance_validity_CWI').val();
			var ach_business_insurance_validity_PPLI = $('#ach_business_insurance_validity_PPLI').val();
			var ach_business_insurance_validity_OI = $('#ach_business_insurance_validity_OI').val();
			
			var ach_business_insurance_type_OI = $('#ach_business_insurance_type_OI').val();
			
			$.post("controller/formsource/dashboard_project/controller/achieve_save_business_details.php", {
				ci: "<?php echo $ci; ?>",
				ui: "<?php echo $ui; ?>",
				
				_user_email: "<?php echo $_user_email; ?>",
				_user_lastname: "<?php echo $_user_lastname; ?>",
				_user_firstname: "<?php echo $_user_firstname; ?>",
				
				ach_business_abn: ach_business_abn,
				ach_business_address: ach_business_address,
				ach_business_name: ach_business_name,
				
				ach_business_state: ach_business_state,
				ach_business_tradingname: ach_business_tradingname,
				ach_business_entitytype: ach_business_entitytype,
				ach_business_postcode: ach_business_postcode,
				ach_business_gstregister: ach_business_gstregister,
				
				ach_business_phone: ach_business_phone,
				ach_business_email: ach_business_email,
				ach_business_fax: ach_business_fax,
				
				ach_business_contractor_license: ach_business_contractor_license,
				ach_business_contractor_validity: ach_business_contractor_validity,
				
				ach_business_bank_name: ach_business_bank_name,
				ach_business_bank_number: ach_business_bank_number,
				ach_business_bank_bsb: ach_business_bank_bsb,
				
				ach_business_insurer_WCI: ach_business_insurer_WCI,
				ach_business_insurer_PII: ach_business_insurer_PII,
				ach_business_insurer_CWI: ach_business_insurer_CWI,
				ach_business_insurer_PPLI: ach_business_insurer_PPLI,
				ach_business_insurer_OI: ach_business_insurer_OI,
				
				ach_business_policy_number_WCI: ach_business_policy_number_WCI,
				ach_business_policy_number_PII: ach_business_policy_number_PII,
				ach_business_policy_number_CWI: ach_business_policy_number_CWI,
				ach_business_policy_number_PPLI: ach_business_policy_number_PPLI,
				ach_business_policy_number_OI: ach_business_policy_number_OI,
				
				ach_business_insurance_validity_WCI: ach_business_insurance_validity_WCI,
				ach_business_insurance_validity_PII: ach_business_insurance_validity_PII,
				ach_business_insurance_validity_CWI: ach_business_insurance_validity_CWI,
				ach_business_insurance_validity_PPLI: ach_business_insurance_validity_PPLI,
				ach_business_insurance_validity_OI: ach_business_insurance_validity_OI,
				
				ach_business_insurance_type_OI: ach_business_insurance_type_OI
				
			}, function(e) {
				$('#save_successful').show();
					setTimeout(function(){
						$('#save_successful').fadeOut();
						if(type == 5){
							if(curr != 'ach_insur_btn6'){
								ttlChange(curr);
							}
						}else{
							ach_openTab(next, curr);
						}
				}, 1000);
				//alert('Business details are successfully saved!');
				$('#ach_business_abn_og').val(ach_business_abn);
				$('#ach_business_address_og').val(ach_business_address);
				$('#ach_business_name_og').val(ach_business_name);
				$('#ach_business_state_og').val(ach_business_state);
				$('#ach_business_tradingname_og').val(ach_business_tradingname)
				$('#ach_business_entitytype_og').val(ach_business_entitytype);
				$('#ach_business_postcode_og').val(ach_business_postcode);
				$('#ach_business_gstregister_og').val(ach_business_gstregister);
				$('#ach_business_phone_og').val(ach_business_phone);
				$('#ach_business_email_og').val(ach_business_email);
				$('#ach_business_fax_og').val(ach_business_fax);
				$('#ach_business_contractor_license_og').val(ach_business_contractor_license);
				$('#ach_business_contractor_validity_og').val(ach_business_contractor_validity);
				$('#ach_business_bank_name_og').val(ach_business_bank_name);
				$('#ach_business_bank_number_og').val(ach_business_bank_number);
				$('#ach_business_bank_bsb_og').val(ach_business_bank_bsb);
				$('#ach_business_insurer_WCI_og').val(ach_business_insurer_WCI);
				$('#ach_business_insurer_PII_og').val(ach_business_insurer_PII);
				$('#ach_business_insurer_CWI_og').val(ach_business_insurer_CWI);
				$('#ach_business_insurer_PPLI_og').val(ach_business_insurer_PPLI);
				$('#ach_business_insurer_OI_og').val(ach_business_insurer_OI);
				$('#ach_business_policy_number_WCI_og').val(ach_business_policy_number_WCI);
				$('#ach_business_policy_number_PII_og').val(ach_business_policy_number_PII);
				$('#ach_business_policy_number_CWI_og').val(ach_business_policy_number_CWI);
				$('#ach_business_policy_number_PPLI_og').val(ach_business_policy_number_PPLI);
				$('#ach_business_policy_number_OI_og').val(ach_business_policy_number_OI);
				$('#ach_business_insurance_validity_WCI_og').val(ach_business_insurance_validity_WCI);
				$('#ach_business_insurance_validity_PII_og').val(ach_business_insurance_validity_PII);
				$('#ach_business_insurance_validity_CWI_og').val(ach_business_insurance_validity_CWI);
				$('#ach_business_insurance_validity_PPLI_og').val(ach_business_insurance_validity_PPLI);
				$('#ach_business_insurance_validity_OI_og').val(ach_business_insurance_validity_OI);
				changesCounter = 0;
				if($('.ach_completeBusiness').length){
					$("img.ach_completeBusiness").attr('src','sb_tools/feature/done-check.png').attr('onmouseout',"this.src='sb_tools/feature/done-check.png'");
					$("button.ach_completeBusiness").attr('onmouseout','changeImageAchieve("4","out","done")');
					$("#achieve_icon_4").attr('src','sb_tools/feature/done-check.png');
					var progress_count = $("#ach_progress_count_text").val();
					if(progress_count < 100 && completeBusiness == 0 && '<?php echo $completedBusDetails; ?>' == 0){
						$("#ach_progress_count_text").val(progress_count);
						var percentage_val = parseInt(progress_count) + 20;
						$("#progressFeat .progress-bar").css('width',percentage_val+'%');
						completeBusiness = 1;
					}
				}
			}); 
		} else {
			ach_openTab('ach_bdt', 'ach_insurancetab_business');
			alert("Business name is required.");
		}
	}
	
	function ach_closeBusinessModal(){
		$('#ach_businessDetailsModal').modal('hide');
	}
</script>