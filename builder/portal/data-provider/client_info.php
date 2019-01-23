<?php
$dec_ci = base64_decode($_GET["ci"]);
//prepopulate company details
$_company_business_logo ="";
$_company_template_logo ="";
$_company_business_name = "";
$_company_trading_name = "";
$_company_abn = "";
$_company_acn = "";
$_company_business_address = "";
$_company_phone_number = "";
$_company_fax_number = "";
$_company_email_address = "";
$_company_rep_name = "";
$_company_rep_jobtitle = "";
$_company_rep_phone_number = "";
$_company_rep_mobile_number = "";
$_company_rep_email_address = "";
$_company_license_number = "";
$_company_bank_account_name = "";
$_company_bsb_number = "";
$_company_bank_account_number = "";
$_company_valid_until = "";
$_gst_registered = "";
$_tradelicense = "";

	$getClientInfo = mysqli_query($theodore_con, "SELECT * FROM `_submission_208` where JClientID21102607 = '".$dec_ci."'");
	if(mysqli_num_rows($getClientInfo)!=0) {
		$fetchClientInfo = mysqli_fetch_array($getClientInfo);	
                $_company_business_logo = stripslashes($fetchClientInfo["JYourbusinesslogo08114745"]);
                $_company_template_logo = stripslashes($fetchClientInfo["JYourtemplatelogo12124433"]);
		$_company_business_name = stripslashes($fetchClientInfo["JYourbusinessname21102039"]);
		$_company_trading_name = stripslashes($fetchClientInfo["JYourtradingname21102137"]);
		$_company_abn = stripslashes($fetchClientInfo["JYourABN21102149"]);
		$_company_acn = stripslashes($fetchClientInfo["JYourACN21102201"]);
		$_company_business_address = stripslashes($fetchClientInfo["JYourregisteredbusinessadd21102218"]);
		$_company_phone_number = stripslashes($fetchClientInfo["JYourphonenumber21102512"]);
		$_company_fax_number = stripslashes($fetchClientInfo["JYourfaxnumber21102526"]);
		$_company_email_address = stripslashes($fetchClientInfo["JYouremailaddress21102543"]);
		$_company_rep_name = stripslashes($fetchClientInfo["JYourrepresentativesname21103235"]);
		$_company_rep_jobtitle = stripslashes($fetchClientInfo["JYourrepresentativesjobtit21103250"]);
		$_company_rep_phone_number = stripslashes($fetchClientInfo["JYourrepresentativesphonen21103334"]);
		$_company_rep_mobile_number = stripslashes($fetchClientInfo["JYourrepresentativesmobile21103410"]);
		$_company_rep_email_address = stripslashes($fetchClientInfo["JYourrepresentativesemaila21103424"]);
		$_company_license_number = stripslashes($fetchClientInfo["JLicensenumber21103449"]);
		$_company_bank_account_name = stripslashes($fetchClientInfo["JBankAccountName21103617"]);
		$_company_bsb_number = stripslashes($fetchClientInfo["JBSBNumber21103627"]);
		$_company_bank_account_number = stripslashes($fetchClientInfo["JBankAccountNumber21103637"]);
		$_workers_compensation_name = stripslashes($fetchClientInfo["JNameofInsurer21103717"]);
		$_workers_compensation_number = stripslashes($fetchClientInfo["JPolicyNumber21103726"]);
		$_workers_compensation_coverage = $fetchClientInfo["JCoverageUntil21103756"];
		$_professional_indemnity_name = stripslashes($fetchClientInfo["JNameofInsurer21103858"]);
		$_professional_indemnity_number = stripslashes($fetchClientInfo["JPolicyNumber21103913"]);
		$_professional_indemnity_coverage = $fetchClientInfo["JCoverageUntil21103927"];
		$_contract_works_name = stripslashes($fetchClientInfo["JNameofInsurer21104012"]);
		$_contract_works_number = stripslashes($fetchClientInfo["JPolicyNumber21104022"]);
		$_contract_works_coverage = $fetchClientInfo["JCoverageUntil21104033"];
		$_public_liability_name = stripslashes($fetchClientInfo["JNameofInsurer21104100"]);
		$_public_liability_number = stripslashes($fetchClientInfo["JPolicyNumber21104108"]);
		$_public_liability_coverage = $fetchClientInfo["JCoverageUntil21104117"];
		$_other_insurance_type = stripslashes($fetchClientInfo["JInsuranceType21104150"]);
		$_other_insurance_name = stripslashes($fetchClientInfo["JNameofInsurer21104158"]);
		$_other_insurance_number = stripslashes($fetchClientInfo["JPolicyNumber21104207"]);
		$_other_insurance_coverage = $fetchClientInfo["JCoverageUntil21104217"];
		
		
		$_gst_registered = stripslashes($fetchClientInfo["GST_Registered"]);
		$_tradelicense = $fetchClientInfo["q_licensestate"];

			if($fetchClientInfo["JValiduntil21103538"]!=""){ 
				$_company_valid_until = date("d-m-Y", strtotime($fetchClientInfo["JValiduntil21103538"]));
			}
			
		if($_gst_registered=="Yes"){
			$_gst_registered_y = "checked";
			$_gst_registered_n = "";
		}else{
			$_gst_registered_y = "";
			$_gst_registered_n = "checked";
		}
		
		if($_tradelicense=="Yes"){
			$_tradelicense_y = "checked";
			$_tradelicense_n = "";
		}else{
			$_tradelicense_y = "";
			$_tradelicense_n = "checked";
		}
	
		$title = $_company_business_name;
	}
?>