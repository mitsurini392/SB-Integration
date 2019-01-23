<style>
		.backBtn{
			color:lightgrey;
			left:0;
			top:0;
			margin-left:-20px;
			width:20px;
			cursor:pointer;
			border-radius:10px;
		}
		.backBtn:hover{
			color:grey;
		}
        .contactdiv {
            margin: 10px 10px 0 10px;
            border-radius: 4px;
            padding: 15px;
            background: #e8edef;
            line-height: 22px;
        }
		
		.integTabs{
			border-bottom:2px solid #076dc4;
			padding-top: 10px;
			padding-bottom:10px;
			cursor:pointer;
		}
		.integTabs:hover{
			background-color:lightgrey;
		}
		
		.activeIntegTab{
			font-weight:bold;
			border-bottom:#076dc4 3px solid!important;
		}
		
		.noPoint{
			pointer-events:none;
		}
		.integModalBtns{
			background-color: #00a1e5;
			border-radius:20px;
			color:white!important;
			font-weight:bold;
			height:30px;
			width:98%;
			margin-top:20px;
		}
		.integModalBtns:hover{
			background-color:#0097d6;
		}
		.inviteEmployeeBtn{
			background-image: linear-gradient(#60dc71, #48ba58)!important;
			color:white!important;
			border-radius: 7px;
			font-size: 17px;
			width:280px!important;
			padding: 7px;
		}
		.inviteEmployeeBtn:hover {
			background-image: linear-gradient(#2ed144, #389444)!important;
		}
		.laterBtn{
			background-color: white;
			color: grey;
			border-radius: 7px;
			border: 2px solid lightgrey;
			padding: 7px;
			font-size: 17px;
			margin: 20px;
			width:280px!important;
		}
		.laterBtn:hover{
			background-color:lightgrey;
		}
		.userTypeInteg{
			text-transform:capitalize;
		}
		#xeroContactsTbl{
			width:100%;
			border: 1px solid black;
		}
		
		#xeroContactsTbl thead{
			background-color: #fffac3;
			font-weight:bold;
		}
		
		#xeroContactsTbl td, #xeroContactsTbl th{
			border: 1px solid black;
			cursor:default;
			vertical-align:middle;
		}
</style>
<!-------------------------- START - INTEGRATION CONTACTS MODAL ---------------------------->
<div class="modal fade" id="modalIntegrationContacts">
    <div class="modal-dialog modal-70" style="width:70%" id="modal70"> <!-- modal-70 -->
        <div class="modal-content">
            <div class="modal-header" style="display:none"></div>
            <div id="integMainMenu" class="modalsClose modal-body" style="background-color:white;min-height: 460px;overflow-y: auto;padding: 30px 40px"> <!-- 30px 40px; -->
                <button type="button" class="close" data-dismiss="modal">&times;</button>
				<div class="col-xs-12">
					
					<div style="font-size:33px;font-weight:bold">Connect <?php echo app_host_name(); ?> to tools you already use</div>
					<div style="font-size:19px;margin: 15px 0">If you use any of the software below, you can easily import your contacts like your employees to start using the timesheets here in <?php echo app_host_name(); ?>.</div>
					
				</div>
				<div class="col-xs-12" style="text-align:center;">
					<div class="col-xs-4"> <!--col-xs-6 col-sm-3-->
						<img style="height: 150px;" src="images/integration_icons/xero.png" /><br>
						<button onclick="openInteg('integConn','integXero')" class="btn btn-xs integModalBtns">Import</button>
					</div>
					<div class="col-xs-4" style="">
						<img style="height: 150px;" src="images/integration_icons/myob.png" /><br>
						<button onclick="redirectPageContact('myob')" class="btn integModalBtns">Import</button>
					</div>
					<div class="col-xs-4" style="">
						<img style="height: 150px;" src="images/integration_icons/quickbooks.png" /><br>
						<button onclick="redirectPageContact('quickbooks')" class="btn btn-xs integModalBtns">Import</button>
					</div>
					<div class="col-xs-6 col-sm-3" style="display:none">
						<img style="height: 150px;" src="images/integration_icons/excel.png" /><br>
						<button onclick="openInteg('integConn','integExcel')" class="btn btn-xs integModalBtns">Import</button>
					</div>
				</div>
				<div class="col-xs-12 text-center">
					<button data-dismiss="modal" class="btn btn-md laterBtn">I'll do this later</button>
				</div>
            </div>
			
			<div id="integConn" class="modalsClose modal-body" style="display:none;background-color:white;min-height: 550px;overflow-y: auto;padding: 30px 40px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
				<div class="col-xs-7 text-left" style="padding:0">
					<div class="col-sm-3" style="text-align:center;padding-right:0;">
						<div class="backBtn" onclick="openInteg('integMainMenu','0')">
							<i class="fa fa-arrow-left" aria-hidden="true"></i>
						</div>
						<img style="margin-top:-18px;display:none;height: 75px;" class="integXeroItems integItems" src="images/integration_icons/xero.png" />
						<img style="margin-top:-18px;height: 75px;" class="integExcelItems integItems" src="images/integration_icons/excel.png" />
					</div>
					<div class="col-sm-9" style="padding-left:0;padding-top: 15px;">
						<div style="font-size:17px;font-weight:bold;">
							<span class="integXeroItems integItems">XERO</span>
							<span class="integExcelItems integItems">EXCEL</span>
						</div>
						<div>
							<span class="integXeroItems integItems">Integrate this accounting software to <?php echo app_host_name(); ?></span>
							<span class="integExcelItems integItems">Integrate EXCEL EXCEL to <?php echo app_host_name(); ?></span>
						</div>
					</div>
					<div class="col-xs-12">
						<div style="margin:20px 0;">
							<!--
							<script src="https://fast.wistia.com/embed/medias/vfn7m8iwm9.jsonp" async></script>
							<script src="https://fast.wistia.com/assets/external/E-v1.js" async></script>
							<div class="wistia_responsive_padding" style="padding:56.25% 0 0 0;position:relative;margin-left:2.5%;">
								<div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;">
									<div class="wistia_embed wistia_async_vfn7m8iwm9 videoFoam=true" style="height:100%;position:relative;width:100%">
										<div class="wistia_swatch" style="height:100%;left:0;opacity:0;overflow:hidden;position:absolute; top:0;transition:opacity 200ms;width:100%;">
											<img src="https://fast.wistia.com/embed/medias/vfn7m8iwm9/swatch" style="filter:blur(5px);height:100%;object-fit:contain;width:100%;" alt="" onload="this.parentNode.style.opacity=1;" />
										</div>
									</div>
								</div>
							</div>
							-->
							<iframe width="490" height="315" src="https://www.youtube.com/embed/bIBRh7SbVkg">
							</iframe>
						</div>
						<ul class="integXeroItems integItems" style="margin-left:-15px;">
						  <li style="padding-left:20px">Import Customers, Suppliers, and Employees</li>
						  <li style="padding-left:20px">Integrate purchases on the Expense Systems</li>
						  <li style="padding-left:20px">Transfer payment claims made to XERO</li>
						  <li style="padding-left:20px">Integrate timesheet payroll to XERO</li>
						</ul>
						
						<ul class="integExcelItems integItems" style="margin-left:-15px;">
						  <li style="padding-left:20px">Import Customers, Suppliers, and Employees from Excel</li>
						</ul>
					</div>
				</div>				
				<div class="col-xs-5 text-center integXero integXeroItems integItems">
					<button class="btn btn-md btn-success btnCustomSuccess btnCustomSuccess" id="connectXeroContacts" onclick="validateXeroConnection();" style="padding-left:80px; min-width:286px;padding-right:80px;">
						Connect to XERO
					</button><br><br><br>
					
					<div onclick="showXeroTab('integXero', 'descTab');" class="integXeroMaindescTab col-xs-6 integTabs activeIntegTab">Description</div>
					<div onclick="showXeroTab('integXero', 'reqTab');" class="integXeroMainreqTab col-xs-6 integTabs">Requirements</div>
					
					<div class="col-xs-12">
						<div class="descTab integXeroTabs" style="text-align:justify;font-size:16px;margin-top: 20px;">
							Xero is a powerful and simple-to-use accounting software that has all the timesaving tools you need to grow your business.

							<br><br>
							You can easily integrate the following submissions from <?php echo app_host_name(); ?> to your Xero account: <br>
							<ul class="integXeroItems integItems" style="margin-left:-15px;">
							  <li style="padding-left:20px">contacts</li>
							  <li style="padding-left:20px">payment claims</li>
							  <li style="padding-left:20px">expenses</li>
							  <li style="padding-left:20px">timesheets</li>
							</ul>
						</div>
						<div class="reqTab integXeroTabs" style="text-align:justify;display:none;font-size:16px;margin-top: 20px;">
							This app will require access to:<br><br>
							<ul class="integXeroItems integItems" style="margin-left:-15px;">
							  <li style="padding-left:20px">sync contacts from Xero to <?php echo app_host_name(); ?> and vice versa</li>
							  <li style="padding-left:20px">integrate employees</li>
							  <li style="padding-left:20px">integrate employees’ timesheets</li>
							  <li style="padding-left:20px">process payroll on Xero</li>
							  <li style="padding-left:20px">integrate purchases on <?php echo app_host_name(); ?> to Xero</li>
							  <li style="padding-left:20px">integrate sales (payment claims) to Xero</li>
							</ul>
						</div>
					</div>
				</div>		
				<div class="col-xs-5 text-center integExcel integExcelItems integItems">
					<button class="btn btn-md btn-success btnCustomSuccess btnCustomSuccess" style="padding-left:80px; min-width:286px;padding-right:80px;">
						Select Excel file
					</button><br><br><br>
					
					<div onclick="showXeroTab('integExcel', 'descTab');" class="integExcelMaindescTab col-xs-6 integTabs activeIntegTab">Description</div>
					<div onclick="showXeroTab('integExcel', 'reqTab');" class="integExcelMainreqTab col-xs-6 integTabs">Requirements</div>
					
					<div class="col-xs-12">
						<div class="descTab integExcelTabs" style="text-align:justify;font-size:18px;margin-top: 20px;">
							EXCEL DESC Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce placerat dignissim pulvinar. Nullam accumsan ante dolor, non tristique eros eleifend in. Morbi tincidunt consequat faucibus. Maecenas laoreet elit a nibh auctor, ac placerat sem eleifend. Etiam bibendum lectus ac varius iaculis. Nam tempus leo lorem, at imperdiet turpis accumsan et. Etiam cursus, felis quis laoreet imperdiet, nibh lectus ultricies mi
						</div>
						<div class="reqTab integExcelTabs" style="text-align:justify;display:none;font-size:18px;margin-top: 20px;">
							EXCEL REQNon tristique eros eleifend in. Morbi tincidunt consequat faucibus. Maecenas laoreet elit a nibh auctor, ac placerat sem eleifend. Etiam bibendum lectus ac varius iaculis. Nam tempus leo lorem, at imperdiet turpis accumsan et. Etiam cursus, felis quis laoreet imperdiet, nibh lectus ultricies mi, at mollis elit nibh sed est. Phasellus nec dictum ipsum.
						</div>
					</div>
				</div>
			</div>
			<div id="integXeroSelect" class="modalsClose modal-body" style="display:none;background-color:white;min-height: 550px;overflow-y: auto;padding: 30px 40px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
				<div class="col-xs-7 text-left" style="padding:0">
					<div class="col-sm-3" style="padding-right:0;position:relative;">
						<div class="backBtn integXeroItems integItems" onclick="openInteg('integConn','integXero')">
							<i class="fa fa-arrow-left" aria-hidden="true"></i>
						</div>
						<div class="backBtn integExcelItems integItems" onclick="openInteg('integConn','integExcel')">
							<i class="fa fa-arrow-left" aria-hidden="true"></i>
						</div>
						<img style="height: 75px;" src="images/integration_icons/xero.png" />
					</div>
					<div class="col-sm-9" style="padding-left:0;padding-top: 15px;">
						<div style="font-size:17px;font-weight:bold;">XERO - Select Contacts</div>
						<div>Select the contacts you want to intgerate in <?php echo app_host_name(); ?></div>
					</div>
				</div>
				<div class="col-xs-5"></div>
				<div class="col-xs-12">
					<button onclick="checkIntegInputs('xero')" id="selectAllBtnInteg" class="btn btn-sm btn-success btnCustomSuccess" style="padding-left:30px; padding-right:30px;margin:20px 0;">
						<i class="fa fa-check-square-o" aria-hidden="true"></i> Select All Contacts
					</button><br>
					<div style="max-height:300px;overflow-y:auto;">
						<table class="table" id="xeroContactsTbl">
							<thead>
								<th>Import?</th>
								<th>Contact Type</th>
								<th>Name</th>
								<th>Address</th>
								<th>Email Address</th>
							</thead>
							<tbody id="xeroTbody">
								<tr>
									<td colspan="5" style="text-align:center">
										There are no contacts.
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<center>
						<button class="btn btn-md btn-success btnCustomSuccess" onclick="importIntegContacts()" style="padding-left:30px; padding-right:30px;margin:20px 0;">
							Import my contacts
						</button>
					</center>
				</div>
			</div>
			
			
        </div>
    </div>
</div>
<!-------------------------- END - INTEGRATION CONTACTS MODAL ---------------------------->

<!-------------------------- START - INTEGRATION SUCCESS MODAL ---------------------------->
<div class="modal fade" id="modalIntegrationSuccess">
    <div class="modal-dialog" style="width:510px;margin-top:130px">
        <div class="modal-content text-center">
            <div class="modal-body" style="background-color:white;min-height: 350px;overflow-y: auto;padding: 10px 20px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
				<div class="col-xs-12">
					<img style="height: 80px;" src="images/integration_icons/check.png" /><br>
					<div style="font-size:33px;font-weight:bold">Success</div>
					<div style="font-size:15px;">Your contacts are successfully integrated. <br> Do you want to invite some of them to use <?php echo app_host_name(); ?>?</div>
				</div>
				<div class="col-xs-12" style="text-align:center;margin: 20px 10px;">
					<button class="btn btn-md inviteEmployeeBtn" data-dismiss="modal" onclick="showMainContactsModal();" style="padding-left:30px; padding-right:30px;">
						Invite my Employees
					</button><br>
					<button class="btn btn-md laterBtn" data-dismiss="modal" style="margin:10px!important;">I'll do this later</button>
				</div>
            </div>		
			
        </div>
    </div>
</div>
<!-------------------------- END - INTEGRATION SUCCESS MODAL ---------------------------->


<!-------------------------- START - INVITATION SUCCESS MODAL ---------------------------->
<div class="modal fade" id="modalEmployeeInvitationSuccess">
    <div class="modal-dialog" style="width:510px;margin-top:130px">
        <div class="modal-content text-center">
            <div class="modal-body" style="background-color:white;min-height: 300px;overflow-y: auto;padding: 10px 20px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
				<div class="col-xs-12">
					<img style="height: 80px;" src="images/integration_icons/check.png" /><br>
					<div style="font-size:33px;font-weight:bold">Success</div>
					<div style="font-size:15px;">Your employees are invited to sign up for <?php echo app_host_name(); ?>. <br> Keep track of the status of their invitation in this page.</div>
				</div>
				<div class="col-xs-12" style="text-align:center;margin: 20px 10px;">
					<button type="button" class="btn btn-md inviteEmployeeBtn" data-dismiss="modal" style="padding-left:30px; padding-right:30px;">
						Thanks, got it!
					</button>
				</div>
            </div>		
			
        </div>
    </div>
</div>
<!-------------------------- END - INVITATION SUCCESS MODAL ---------------------------->

<!------------------------- START - EMPLOYEE INVITATION MODAL --------------------------->
<div class="modal fade" id="modalInviteEmployees">
    <div class="modal-dialog" style="width:900px">
        <div class="modal-content row text-center" style="margin-top:45px;">
            <div class="modal-body" style="background-color:white;padding: 10px 20px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button><br>
				<div style="max-height: 550px;overflow-y: auto;">
					<div id="employeeModal"></div>
				</div>
            </div>		
			
        </div>
    </div>
</div>
<!------------------------- END - EMPLOYEE INVITATION MODAL --------------------------->
<script>
	function redirectPageContact(type){
		if(type == 'myob'){
			window.location="myob.php?ui=<?php echo $_GET['ui']; ?>&ci=<?php echo $_GET['ci']; ?>";
		}else if(type == 'quickbooks'){
			window.location="quickbooks.php?ui=<?php echo $_GET['ui']; ?>&ci=<?php echo $_GET['ci']; ?>";
		}
	}
	function returnEmpColor(x){
		$("#emp_email_"+x).css("border","1px solid #ccc");
	}
	function checkEmailAddressChange(emp_id){
		var emp_email = $('#emp_email_'+emp_id).val();
		if(emp_email != ''){
			$.post("controller/formsource/dashboard_project/controller/checkEmailAddressChange.php", {
				client_id: "<?php echo $dec_ci; ?>",
				emp_email: emp_email,
				emp_id: emp_id
			}, function(e) {
				$('#emp_email_check_'+emp_id).val(e);
			});
		}else{
			$('#emp_email_check_'+emp_id).val('');
		}
	}
	var blank_count = 0;
	var existing_count = 0;
	function giveEmployeeAccess(){
		blank_count = 0;
		existing_count = 0;
		if($(".employeeInviteeRow").length > 0){
			$('.emp_email_check').each(function(){
				if($(this).val() != ''){
					var thisID = $(this).attr("id").replace('emp_email_check_', '');
                    $('#emp_email_'+thisID).css("border","1px solid red");
					existing_count++;
				}
			});
			$('.emp_email').each(function(){
				if($(this).val() == ''){
                    $(this).css("border","1px solid red");
					blank_count++;
				}
			});
			
			if(existing_count > 0){
				alert('Email address already exists in the database.');
				e.preventDefault();
			}else if(blank_count > 0){
				alert('Email address is required.');
				e.preventDefault();
			}else{
				$('#modalInviteEmployees').modal('hide');
				$('.contacts_check').hide();
				contactCount = 0;
				$("#div-contact-list").html('<br/><br/><br/><br/><br/><section><table style="width:100%; margin-top:30px;"><tbody><tr><td style="text-align:center;"><img style="width: 50px; height: 50px;" src="controller/formsource/ceo_report_project/images/loading-circle.gif"></td></tr></tbody></table></section>');
				existing_count = 0;
				blank_count = 0;
				var employeeInviteeRow,
					emp_fnames = '',
					emp_lnames = '',
					emp_emails = '',
					emp_positions = '',
					emp_projects = '',
					emp_ids = '';
				$('.employeeInviteeRow').each(function(){
					employeeInviteeRow = $(this).attr("id").replace('employeeInviteeRow_', '');
					emp_ids += employeeInviteeRow + '*';
					emp_fnames += $('#emp_fname_'+employeeInviteeRow).val() + '*';
					emp_lnames += $('#emp_lname_'+employeeInviteeRow).val() + '*';
					emp_emails += $('#emp_email_'+employeeInviteeRow).val() + '*';
					emp_positions += $('#emp_position_'+employeeInviteeRow).val() + '*';
					emp_projects += $('#emp_project_'+employeeInviteeRow).val() + '*';
				});
				$.post("controller/formsource/dashboard_project/controller/giveEmployeeAccess.php", {
					_user_firstname: "<?php echo $_user_firstname; ?>",
					_user_lastname: "<?php echo $_user_lastname; ?>",
					_company_business_name: "<?php echo $_company_business_name; ?>",
					_company_business_logo: "<?php echo $_company_business_logo; ?>",
					_user_email: "<?php echo $_user_email; ?>",
					client_id: "<?php echo $dec_ci; ?>",
					user_id: "<?php echo $dec_ui; ?>",
					emp_ids: emp_ids,
					emp_fnames: emp_fnames,
					emp_lnames: emp_lnames,
					emp_emails: emp_emails,
					emp_positions: emp_positions,
					emp_projects: emp_projects
				}, function(e) {
					//alert(e + " " + emp_emails);
					contactCountInv = 0;
					getformdata(2);
					$('.left-nav').removeClass('active');
					$('#inviteNav').addClass('active');

					$('.right-panel').hide();
					$('#panel-invite').show();
					$('#modalEmployeeInvitationSuccess').modal('show');
				});
			}
		}
	}
	
	function inviteSelEmployees(){
		if($(".contacts_check:checked").length == 0){
			alert('Please select a contact.');
		}else{
			var id_contact, id_contacts_checked = '';
			$('.contacts_check:checked').each(function(){
				id_contact = $(this).attr("id").replace('contacts_check_', '');
				id_contacts_checked += id_contact + '-';
			});
			$.post("controller/formsource/dashboard_project/controller/getSelectedEmployeeDetails.php", {
				user_id: "<?php echo $dec_ui; ?>",
				client_id: "<?php echo $dec_ci; ?>",
				id_contacts_checked: id_contacts_checked
			}, function(e) {
				$('#modalInviteEmployees').modal('show');
				$('#employeeModal').html(e);
				$('.classSelectedEmployees').multipleSelect({
					width: '160px',
					selectAllText: 'ALL PROJECTS',
					allSelected: 'ALL PROJECTS',
					placeholder: 'Select Projects',
				}).multipleSelect("checkAll");
			});
		}
	}
	var show_contacts_check = 0;
	$(document).ready(function() {
		<?php 
			if(isset($_GET['xeroAuthenticated']) && $_GET['xeroAuthenticated'] == '1'){
				echo '$("#modalIntegrationContacts").modal("show");';
				echo "openInteg('integConn','integXero');";
				echo "getXeroContacts();";
			}
		?>
	});
	function showMainContactsModal(){
		$('#modalIntegrationContacts').modal('hide');
		$('#selectSort').val('name');
		$('#selectRole').val('Employee');
		$('#selectAccess').val('no');
		refreshContacts();
		contactCount = 0;
		$('.contacts_check').show();
	}
	function openInteg(modalID, integID){
		
		
		$('.modalsClose ').hide();
		$('.integItems').hide();
		$('.'+integID+'Items').show();
		$('#'+modalID).show();
	}
	
	function showXeroTab(container, tab){
		$('.'+container+'Tabs').hide();
		$('.'+container+' .'+tab).show();
		$('.'+container+' .integTabs').removeClass('activeIntegTab');
		$('.'+container+'Main'+tab).addClass('activeIntegTab');
	}
	
	function importIntegContacts(){
		if($(".xeroCheck:checked").length == 0){
			alert('Please select a contact.');
		}else{
			var id_num = 0;
			$('.xeroCheck:checked').each(function(){
				var address = '',
					bank_account_no = '',
					email_address = '',
					fax = '',
					mobile = '',
					name = '',
					phone = '',
					representative_firstname = '',
					representative_lastname = '',
					firstname = '',
					lastname = '',
					startdate = '',
					birthday = '';
				
				id_num = $(this).attr("id").replace('xeroCheck_', '');
				
				var type = $('#xeroType_'+id_num).val();
				if(type == "supplier" || type == "customer"){
					address = $('#xeroAddress_'+id_num).val(),
					bank_account_no = $('#xeroBankNo_'+id_num).val(),
					email_address = $('#xeroEmail_'+id_num).val(),
					fax = $('#xeroFax_'+id_num).val(),
					mobile = $('#xeroMobile_'+id_num).val(),
					name = $('#xeroName_'+id_num).val(),
					phone = $('#xeroPhone_'+id_num).val(),
					representative_firstname = $('#xeroRfn_'+id_num).val(),
					representative_lastname = $('#xeroRln_'+id_num).val();
				}else if(type == "employee"){
					firstname = $('#xeroFn_'+id_num).val(),
					lastname = $('#xeroLn_'+id_num).val(),
					email_address = $('#xeroEmail_'+id_num).val(),
					phone = $('#xeroPhone_'+id_num).val(),
					mobile = $('#xeroMobile_'+id_num).val(),
					startdate = $('#xeroStartDate_'+id_num).val(),
					birthday = $('#xeroBirthday_'+id_num).val();
				}
				$.post("controller/formsource/dashboard_project/controller/saveNewContact.php", {
					user_id: "<?php echo $dec_ui; ?>",
					client_id: "<?php echo $dec_ci; ?>",
					address: address,
					bank_account_no: bank_account_no,
					email_address: email_address,
					fax: fax,
					mobile: mobile,
					name: name,
					phone: phone,
					representative_firstname: representative_firstname,
					representative_lastname: representative_lastname,
					type: type,
					firstname: firstname,
					lastname: lastname,
					startdate: startdate,
					birthday: birthday
				}, function(e) {
					//alert(e);
					
					$('#business_contacts').modal('show');
					$('#modalIntegrationContacts').modal('hide');
					showSomeContacts = 1;
					contactCount = 0;
					getformdata(1);
				});
				id_num++;
			});
		}
	}
	
	// START - Function Get Contacts - AQS 20181031
	function getXeroContacts(){
		var listOfContacts = [],
			counter,
			tableContent = '';
		$('#connectXeroContacts').html('Connecting...').addClass('noPoint');
		var xeroContactsUrl = "xero_api.php";
			xeroContactsUrl += "?key=d49d74b6e65ef6d271d1055fb159c7c4";
			xeroContactsUrl += "&client_id=" + GetQueryStringParams("ci");
			xeroContactsUrl += "&module=Y29udGFjdHM=";

		$.get(xeroContactsUrl).success(function(contacts) {
			//console.log(contacts);
			var contacts = JSON.parse(contacts)[0];
				//console.log(contacts);
				$('#connectXeroContacts').html('Connect to XERO').removeClass('noPoint');
				if(contacts.result){
					listOfContacts = contacts.contacts;
					listOfContactsEmployee = contacts.employees;
					for(counter = 0; counter < listOfContactsEmployee.length; counter++){
						var firstname = listOfContactsEmployee[counter].firstname,
							lastname = listOfContactsEmployee[counter].lastname,
							email_address = listOfContactsEmployee[counter].email_address,
							phone = listOfContactsEmployee[counter].phone,
							mobile = listOfContactsEmployee[counter].mobile,
							start_date = listOfContactsEmployee[counter].start_date,
							birthday = listOfContactsEmployee[counter].birthday,
							type = 'employee';

						tableContent += 
						'<tr>'+
							'<td style="text-align:center">'+
								'<input id="xeroCheck_'+counter+'" class="xeroCheck" type="checkbox"/>'+
							'</td>'+
							'<td class="userTypeInteg">'+
								'Employee'+
							'</td>'+
							'<td>'+
								firstname+ ' ' +lastname+
							'</td>'+
							'<td>'+
								''+
							'</td>'+
							'<td>'+
								email_address+
								'<input type="hidden" id="xeroFn_'+counter+'" value="'+firstname+'" />'+
								'<input type="hidden" id="xeroLn_'+counter+'" value="'+lastname+'" />'+
								'<input type="hidden" id="xeroEmail_'+counter+'" value="'+email_address+'" />'+
								'<input type="hidden" id="xeroPhone_'+counter+'" value="'+phone+'" />'+
								'<input type="hidden" id="xeroMobile_'+counter+'" value="'+mobile+'" />'+
								'<input type="hidden" id="xeroStartDate_'+counter+'" value="'+start_date+'" />'+
								'<input type="hidden" id="xeroBirthday_'+counter+'" value="'+birthday+'" />'+
								'<input type="hidden" id="xeroType_'+counter+'" value="'+type+'" />'+
							'</td>'+
						'</tr>';
					}
					var counterTwo = counter;
					for(counter = 0; counter < listOfContacts.length; counter++){
						var address = listOfContacts[counter].address,
							bank_account_no = listOfContacts[counter].bank_account_no,
							email_address = listOfContacts[counter].email_address,
							fax = listOfContacts[counter].fax,
							mobile = listOfContacts[counter].mobile,
							name = listOfContacts[counter].name,
							phone = listOfContacts[counter].phone,
							representative_firstname = listOfContacts[counter].representative_firstname,
							representative_lastname = listOfContacts[counter].representative_lastname,
							type = listOfContacts[counter].type;

						tableContent += 
						'<tr>'+
							'<td style="text-align:center">'+
								'<input id="xeroCheck_'+counterTwo+'" class="xeroCheck" type="checkbox"/>'+
							'</td>'+
							'<td class="userTypeInteg">'+
								type+
							'</td>'+
							'<td>'+
								name+
							'</td>'+
							'<td>'+
								address+
							'</td>'+
							'<td>'+
								email_address+
								'<input type="hidden" id="xeroAddress_'+counterTwo+'" value="'+address+'" />'+
								'<input type="hidden" id="xeroBankNo_'+counterTwo+'" value="'+bank_account_no+'" />'+
								'<input type="hidden" id="xeroEmail_'+counterTwo+'" value="'+email_address+'" />'+
								'<input type="hidden" id="xeroFax_'+counterTwo+'" value="'+fax+'" />'+
								'<input type="hidden" id="xeroMobile_'+counterTwo+'" value="'+mobile+'" />'+
								'<input type="hidden" id="xeroName_'+counterTwo+'" value="'+name+'" />'+
								'<input type="hidden" id="xeroPhone_'+counterTwo+'" value="'+phone+'" />'+
								'<input type="hidden" id="xeroRfn_'+counterTwo+'" value="'+representative_firstname+'" />'+
								'<input type="hidden" id="xeroRln_'+counterTwo+'" value="'+representative_lastname+'" />'+
								'<input type="hidden" id="xeroType_'+counterTwo+'" value="'+type+'" />'+
							'</td>'+
						'</tr>';
						
						counterTwo++;
					}
					
					if(tableContent != ''){
						$('#xeroTbody').html(tableContent);
					}else{
						$('#xeroTbody').html('<tr><td colspan="5" style="text-align:center">There are no contacts.</td></tr>');
						$('#selectAllBtnInteg').hide();
					}
					<?php 
						if($page_loc != 'main_index'){
							echo 'window.location="index.php?ui='.$_GET['ui'].'&ci='.$_GET['ci'].'&xeroAuthenticated=1";';
						}else{
							echo 'openInteg("integXeroSelect", "integXero");';
						}
					?>
				} else {
					// Start - authenticate if token is expired or not connected - AQS 20181115
                    if(contacts.error_code == 2){
                        // authentication needed
                        window.location = 'xero_authentication.php?ci=' + GetQueryStringParams('ci') + '&ui=' + GetQueryStringParams('ui') + '&authenticate=2';
                    } else {
                        alert("Unexpected integration error encountered. " + contacts.message);  
                    }
                    // End - authenticate if token is expired or not connected - AQS 20181115
				}
		});
	}
	// END - Function Get Contacts - AQS 20181031
	
	function checkIntegInputs(type){
		$('.'+type+'Check').prop('checked', true);
	}
	
	// START - Function Validate XERO token - AQS 20181031
	function validateXeroConnection (){
		var xeroConnectionUrl = "../../app/external/api/xero_connection.php";
			xeroConnectionUrl += "?key=d49d74b6e65ef6d271d1055fb159c7c4";
			xeroConnectionUrl += "&client_id=" + GetQueryStringParams("ci");

		$.get(xeroConnectionUrl).success(function(xero_token) {
			var xero_token = JSON.parse(xero_token)[0];

			if(xero_token['result']){
				getXeroContacts();
			} else {
				//console.log(xero_token);
                window.location = 'xero_authentication.php?ci=' + GetQueryStringParams('ci') + '&ui=' + GetQueryStringParams('ui') + '&authenticate=2';
				// XERO authentication needed
			}
		});
	}
	// END - Call Validate XERO token - AQS 20181031
</script>