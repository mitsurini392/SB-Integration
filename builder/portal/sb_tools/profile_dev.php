<style>
.notif-bar {
	height: 50px;
}
.button-notif {
  color: white;
  display: inline-block; /* Inline elements with width and height. TL;DR they make the icon buttons stack from left-to-right instead of top-to-bottom */
  position: relative; /* All 'absolute'ly positioned elements are relative to this one */
  padding: 2px 5px; /* Add some padding so it looks nice */
  padding-top:10px;
}
/* Make the badge float in the top right corner of the button */
.button-notif-badge {
  background-color: #fa3e3e;
  border-radius: 2px;
  color: white;
  padding: 1px 3px;
  font-size: 10px;
  position: absolute; /* Position the badge within the relatively positioned button */
  right: 0;
}
ul::-webkit-scrollbar{
			background-color: #F5F5F5;
			width: 5px;
		}

		ul::-webkit-scrollbar-track{
			-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
			border-radius: 10px;
			background-color: #F5F5F5;
		}

		ul::-webkit-scrollbar-thumb{
			border-radius: 10px;
			-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
			background-color: rgb(95,95,95);
		}
</style>
<div class="row text-center" style="width:100vw; background-color:white; padding-top:1%">
<div class="col-md-2" id="logodiv">		
	<img src="<?php echo $_user_companylogo; ?>"  id="logo" onClick="javascript:window.location='index.php?ui=<?php echo $ui; ?>&ci=<?php echo $ci; ?>'" alt="<?php echo $_company_business_name; ?>" id="idimglogo" />
</div>
<div class="col-md-6" style="padding:0;">				
	<nav class="navbar navbar-default" style="background-color: white; border-color: white; " id="main_nav">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>                      
			</button>
		</div>
		<div class="collapse navbar-collapse navbar-expand-xl" style="background-color:white; padding:0" id="myNavbar">
			<ul class="nav navbar-nav">
				<?php
					foreach($navigation_array as $nav){
						echo "<li class='navfont'>
							<a  style='padding: 10px 10px' href='".$nav[3]."?ui=".$ui."&ci=".$ci."&form_id=".$nav[0]."'> ".$nav[2]." ".$nav[4]." </a>
							</li>";
					}
					
				if($_SESSION["s_ctype"] == "free_user"){
					echo '<li>
							<button class="btn" style="width: 100px; margin-top:5px; background: transparent;" data-toggle="modal" data-target="#myModal"> <span> <img src="images/index/upgrade.png" width="100%"> </span> </button>
						</li>';
				}
				?>

			</ul>	
		</div>
	</nav>
</div>
<div class="col-md-1" style="padding:0;">	
	<div class="pull-right">
		<div class="btn-group show-on-hover">
			<div class="button-notif btn btn-lg" id="btnNotification" style="color:black;" class="default dropdown-toggle" data-toggle="dropdown" onclick="notification_load();" >
				<i class="fa fa-bell-o"></i>
				<span class="button-notif-badge">
					<?php 
						$datenow = date('Y-m-d');
						//--------------------Notifications -----------------------//
						$select_notifications = mysqli_query($theodore_con,"SELECT * FROM _notification_invite where client_id='".$dec_ci."' and DATE(date_submitted)='".$datenow."' and viewed='no' ");
						$i=0;
						if(mysqli_num_rows($select_notifications)!=0) {
							while($extractNotifications = mysqli_fetch_array($select_notifications)) {
								extract($extractNotifications);
								$i++;
							}
						}
						//--------------------Notifications -----------------------//
						echo $i;
					?>
				</span>
			</div>
			<ul class="dropdown-menu dropdown-menu-form dropdown-menu-right" id="_notification" role="menu"  style="width:400px;height: 400px;overflow-y:auto;overflow-x:hidden;">
				
			</ul>
		</div>
	</div>
</div>
<div class="col-md-3" id="searchform">	
	<div class="navbar-form navbar pull-right">
	
			<img src="../portal/<?php echo $_user_pic; ?>" title="Profile" class="c-thumbnail  dropdown-toggle" data-toggle="dropdown" />
			
			<div class="row dropdown-menu dropdown-menu-form dropdown-menu-right" role="menu" style="margin-right:12%;">
			
				<div class="col-sm-12">
					<div class="row" style="padding-left:5%;padding-right:5%;padding-bottom:2%;padding-top:2%;border-bottom:solid 1px lightgrey;" >
						<div class="col-sm-5"> 
							<img src="../portal/<?php echo $_user_pic; ?>" style="cursor: pointer;height: 100px;width: 100px;padding:2%;border-radius:50%;border:solid 1px lightgrey;" >
						</div>
						<div class="col-sm-7"> 
							<h5 class="text-muted"><b><?php echo $_company_business_name; ?></b><br/><br/><small><?php echo $_user_firstname ." ".$_user_lastname; ?></small><br/><small><?php echo $_user_email; ?></small><br/><br/>
							<span class="btn btn-primary btn-sm" style="width:120px;" onClick="javascript:window.location='profile.php?ui=<?php echo $ui;?>&ci=<?php echo $ci;?>'" >My Profile</span>
							</h5>
						</div>
					</div>
					
					<div class="row" style="width: 420px;height: auto; max-height:300px;overflow-x: hidden;overflow-y: auto; background-color:rgb(250,250,250);cursor:pointer;">
					<?php
						$portal_company = mysqli_query($csportal_con, "SELECT * from cs_users where email_address='".$_user_email."' && user_id <> '".$dec_ui."' ");
						if(mysqli_num_rows($portal_company)!=0) {
							while($ex_postportal_con=mysqli_fetch_array($portal_company)){
								extract($ex_postportal_con);
									echo '<a href="index.php?ui='.base64_encode($user_id).'&ci='.base64_encode($client_id).'" style="text-decoration:none;"><div class="row" style="padding-left:5%;padding-right:5%;padding-bottom:3%;padding-top:3%;border-bottom:solid 1px lightgrey;cursor:pointer;">
											<div class="col-sm-5"> 
												<img src="images/'.$company_logo.'" style="cursor: pointer;height: 40px;width: 210px;" >
											</div>
											<div class="col-sm-7"> 
												<b>'.$company.'</b>
											</div>
										</div></a>';
							}
						}
					?>
					</div>
					
					<div class="row" style="padding-top:5%;padding-bottom:5%;" >
						<div class="col-sm-12">
							<span class="btn btn-default btn-sm pull-left" onClick="javascript:window.location='../portal/choose-portal.php?ui=<?php echo $ui;?>&ci=<?php echo $ci;?>'" style="width:120px;" >My Portal</span>
							<span class="btn btn-default btn-sm pull-right" onClick="gologout()" style="width:120px;" >Sign Out</span>
						</div>
					</div>
				</div>
			
			</div>
	</div>
	<div>
		<div class="input-group add-on" style="margin-right:15px; width:18vw; margin-top: 10px;">
			<input class="form-control autofillSearchForm pull-right" style="width:100%" placeholder="Search Forms..." name="search" id="srch-term" type="text"/>
			<div class="input-group-btn">
				<button class="btn btn-default" style="height:34px;" type="submit"><i class="glyphicon glyphicon-search"></i></button>
			</div>
		</div>
	</div>
</div>
</div>
<script>
function notification_load(){
	$("#_notification").html('<br/><br/><br/><br/><br/><section><table style="width:100%; margin-top:40px;"><tbody><tr><td style="text-align:center;"><img style="width: 50px; height: 50px;" src="controller/formsource/ceo_report_project/images/loading-circle.gif"></td></tr></tbody></table></section>');
	$.post("controller/formsource/dashboard_project/controller/notifications-invite.php", {
	user_id: "<?php echo $dec_ui; ?>",
	client_id: "<?php echo $dec_ci; ?>",
	project_name: "<?php echo $JProjectName140430; ?>"
	}, function(e) {
		$("#_notification").html(e);
	});
}


function fviewed(x){
	$.post("controller/formsource/dashboard_project/controller/update_notification.php", {
	client_id: "<?php echo $dec_ci; ?>",
	id: x
	}, function(e) {
		notification_load();
	});
}
</script>