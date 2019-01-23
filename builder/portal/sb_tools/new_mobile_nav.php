
<style type="text/css">
html, body {
    max-width: 100%;
    overflow-x: hidden !important;
}
body{
 font-family: 'Lato', sans-serif;
 overflow-x:hidden !important;
    }
 /*TIFF UPDATE -- HIDE I WANT TRAINING BUTTON*/
.calendly-badge-widget{
    display: none;
}
.outerDiv {
    display: inline-flex;
    height: auto;
    /*background-color: red;*/
    /*color: white;*/
}

.innerDiv {
    margin: auto 5px;
    /*background-color: green;   */
}
.no-top-bot {
	margin-top: 0px;
	margin-bottom: 0px;
}
.no-padding {
	padding-left: 0px;
	padding-right: 0px;
}
.no-left {
	padding-left: 0px;
}
.no-right {
	padding-right: 0px;
}
#idlogo{
	width: 80%;
	max-height: 80px;
}
.c-container {
    /*padding: 0% 5% !important;*/
}
.project_nav {
	background-color: rgb(250,250,250);
}
.under_nav {
	background-color: rgb(240,240,240);
}
.tbSearch {
    border-right: 0px;
    border-left: 0px;
    border-top: 0px;
    background: transparent;
}
.text-primary {
    color: #337ab7;
    font-size: 16px;
}
.card {
    display: block;
    /*margin-bottom: 20px;*/
    margin: 0% 5%;
    /*text-align: center;*/
    line-height: 1.42857143;
    background-color: #fff;
    border-radius: 2px;
    box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16),0 2px 10px 0 rgba(0,0,0,0.12);
    transition: box-shadow .25s;
    /*height:45vh;*/
}
.card:hover {
  box-shadow: 0 8px 17px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
}
.img-card {
  width: 100%;
  height:200px;
  border-top-left-radius:2px;
  border-top-right-radius:2px;
  display:block;
  overflow: hidden;
}
.img-card img{
  width: 100%;
  height: 200px;
  object-fit:cover;
  transition: all .25s ease;
}
.card-content {
  padding:15px;
  text-align:left;
  height:80%;
}
.card-title {
  margin-top:0px;
  font-weight: 700;
  font-size: 1.65em;
}
.card-title a {
  color: #000;
  text-decoration: none !important;
}
.card-read-more {
  border-top: 1px solid #D4D4D4;
}
.card-read-more a {
  text-decoration: none !important;
  font-weight:600;
}
.card a{
    color: black;
}
.no-right-left{
	padding-left: 0px;
	padding-right: 0px;
	margin-right: 0px;
	margin-left: 0px;
}
</style>
    <div class="row outerDiv">
        <div class="col-xs-3 innerDiv no-right" style="text-align: center;">
            <img src="<?php echo asset_host(); ?>/builder/<?php echo FILEDIR."/".$_user_companylogo; ?>" onClick="javascript:window.location='_m.index.php?ui=<?php echo $ui; ?>&ci=<?php echo $ci; ?>'" alt="<?php echo $_company_business_name; ?>" id="idlogo" />
        </div>
        <div class="col-xs-7 innerDiv no-padding">
			<div class="input-group add-on pull-right">
				  <input class="form-control autofillSearchForm" placeholder="Search Forms..." name="search" id="srch-term" type="text">
				  <div class="input-group-btn">
				  		<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
				  </div>
			</div>
        </div>
        <div class="col-xs-2 innerDiv no-left">
            <img src="<?php echo asset_host(); ?>/builder/<?php echo FILEDIR."/".$_user_pic; ?>" title="Profile" class="c-thumbnail" id="c-thumbnail" style="float: right;" />
        </div>
    </div>
    <div id="panel">
        <div class="photo_panel"> 
            <img src="<?php echo asset_host(); ?>/builder/<?php echo FILEDIR."/".$_user_pic; ?>" style="background-size: 96px 96px; border: none; vertical-align: top; height: 96px; width: 125px; border-radius: 5px 5px 5px 5px;" />
        </div>
        <div class="p_panel">
            <div class="n_panel_name"><?php echo $_user_firstname ." ".$_user_lastname; ?></div>
            <div class="e_panel_email"><?php echo $_user_email ?></div>
        </div>
        <span class="prof_link" onClick="javascript:window.location='_m.profile.php?ui=<?php echo $ui; ?>&ci=<?php echo $ci; ?>'" >Profile</span>
            <span class="logout" onClick="gologout()">Log Out</span>
    </div>

            
<!-- Start - Main Navigation -->
<?php

	$dec_ui = base64_decode($_GET['ui']);
	$dec_ci = base64_decode($_GET['ci']);
	$pid = "";
	$project_name = "";
	// $pid = ;
	if (isset($_GET['pid'])){
		$pid = base64_decode($_GET['pid']);
		$query_proj_name = "SELECT JProjectName140430 FROM _submission_204 WHERE id=".$pid;
		$exe_proj_name = mysqli_query($theodore_con, $query_proj_name);

		if ($exe_proj_name){
			if(mysqli_num_rows($exe_proj_name)!=0){
				while($fetch_query_name = mysqli_fetch_array($exe_proj_name)){
					extract($fetch_query_name);
					$project_name = $JProjectName140430;
				}
			}
		}
	}
	// echo '<script>alert("'.$project_name.'");</script>';
	$ui = strlen($dec_ui)>0? base64_encode($dec_ui):"";
	$ci = strlen($dec_ci)>0? base64_encode($dec_ci):"";
	$php_page = basename($_SERVER['PHP_SELF']);
	$user_type_array = array();
	// print_r($php_page);

	if (strlen($pid)>0){
		// SELECT TEAM ID FROM PROJECTS TO CONNECT WITH MEMBERS - ACCESS WITH PID AND STATUS (WITH TEAM)
		$sql_query1 = "SELECT team_id FROM cs_team_project WHERE status = 1 AND project_id = ".$pid;
		$exe_query1 = mysqli_query($csportal_con, $sql_query1);

		if ($exe_query1){
			if(mysqli_num_rows($exe_query1)!=0){
				while($fetch_query1 = mysqli_fetch_array($exe_query1)){
					extract($fetch_query1);
								// array_push($user_type_array, $team_id);
					
					// SELECT USER TYPE ID FROM MEMBERS TO CONNECT WITH USER MANAGEMENT - ACCESS WITH UID, STATUS AND TEAM ID
					$sql_query2 = "SELECT user_type_id FROM cs_team_members WHERE status = 1 AND user_id = ".$dec_ui." AND team_id = ".$team_id;
					$exe_query2 = mysqli_query($csportal_con, $sql_query2);

					if ($exe_query2){
						if (mysqli_num_rows($exe_query2)!=0){
							while($fetch_query2 = mysqli_fetch_array($exe_query2)){
								extract($fetch_query2);
								// PUSH USET TYPE VALUES TO ARRAY
								array_push($user_type_array, $user_type_id);
							}
						}
					}

				}
			}
		}

		// SELECT USER TYPE ID FROM MEMBERS TO PUSH TO ARRAY - ACCESS WITH UID, STATUS AND TEAM ID (WITHOUT TEAM) 
		$sql_query3 = "SELECT user_type_id FROM cs_team_project WHERE status = 0 AND team_id = 0 AND user_id = ".$dec_ui." AND project_id = ".$pid;
		// print_r($sql_query3);
		$exe_query3 = mysqli_query($csportal_con, $sql_query3);

		if($exe_query3){
			if(mysqli_num_rows($exe_query3)!=0){
				while($fetch_query3 = mysqli_fetch_array($exe_query3)){
					extract($fetch_query3);
					array_push($user_type_array, $user_type_id);
				}
			}
		}
	}

	else {
		$sql_query_else = "SELECT user_type_id FROM cs_team_members WHERE status = 1 AND user_id = ".$dec_ui;
		$exe_query_else = mysqli_query($csportal_con, $sql_query_else);

		if($exe_query_else){
			if(mysqli_num_rows($exe_query_else)!=0){
				while($fetch_query_else = mysqli_fetch_array($exe_query_else)){
					extract($fetch_query_else);
					array_push($user_type_array, $user_type_id);
				}
			}
		}

		$sql_query_else2 = "SELECT user_type_id FROM cs_team_project WHERE status = 0 AND team_id = 0 AND user_id = ".$dec_ui;
		$exe_query_else2 = mysqli_query($csportal_con, $sql_query_else2);

		if($exe_query_else2){
			if(mysqli_num_rows($exe_query_else2)!=0){
				while($fetch_query_else2 = mysqli_fetch_array($exe_query_else2)){
					extract($fetch_query_else2);
					array_push($user_type_array, $user_type_id);
				}
			}
		}
	}
	// print_r($user_type_array);

	$user_type_columns = array();
	foreach($user_type_array as $user_type){
		$sql_query_loop = "SELECT name FROM cs_usertype_management WHERE status=1 AND id = ".$user_type;
		$exe_query_loop = mysqli_query($csportal_con, $sql_query_loop);

		if($exe_query_loop){
			if(mysqli_num_rows($exe_query_loop)!=0){
				while($fetch_query_loop = mysqli_fetch_array($exe_query_loop)){
					extract($fetch_query_loop);
					array_push($user_type_columns, $name);
				}
			}
		}
	}
	$user_type_columns = array_unique($user_type_columns);
	$user_type_columns = array_filter($user_type_columns);
	$query_string="";
	$client_type = "";

	$parents = array();
	$children = array();
	$siblings = array();
	$current = array();

	$all_available_forms = array();

    $employee_array = ["10112","10108","10059","10055","200","100","10056","157","3000","10060","109","186","10182","10061","10052","193","110","87","11","10173","10174","10175","10176","147","199","44","107","10058","13"];
	$contractor_array = ["10112","10108","10061","10052","193","10060","11","10173","10174","10175","10176"];
					
	if (!empty($user_type_columns)){
		$columns = implode(",", $user_type_columns);
		$count = 1;
		foreach ($user_type_columns as $user_type_column){
			if ($count!=count($user_type_columns)){
				$query_string .= $user_type_column . "=1 OR ";
			}
			else {
				$query_string .= $user_type_column . "=1";
			}
			$count++;
		}
		$sql_query4 = "SELECT form_id,".$columns." FROM cs_usertagged_forms_".$dec_ci;
		$exe_query4 = mysqli_query($csportal_con, $sql_query4);

			if($exe_query4){
				if(mysqli_num_rows($exe_query4)!=0){
						
					while($fetch_query4 = mysqli_fetch_array($exe_query4)){
						// extract($fetch_query4);
						$form_id = $fetch_query4['form_id'];
						$flag = false;
						foreach($user_type_columns as $user_type_column){
							if($fetch_query4[$user_type_column]=="1"){
								$flag = true;
							}
						}

						if($flag){
							$sql_query5 = "SELECT form_id, parent_form_id, form_order, form_name, navigation_name, mobile_page, description, main_category FROM cs_all_forms_new WHERE form_id=".$form_id." ORDER BY FIELD (form_id, 10108), form_id";
							$exe_query5 = mysqli_query($csportal_con, $sql_query5);
							if($exe_query5){
								if(mysqli_num_rows($exe_query5)!=0){
									while($fetch_query5 = mysqli_fetch_array($exe_query5)){
										extract($fetch_query5);
										// $counter = 1;

										$has_parent = $parent_form_id!=0? true:false;
										
										if ($has_parent){
											array_push($all_available_forms, array($form_id,$form_name,$mobile_page));
										}

										if($mobile_page==$php_page){
											array_push($children, getChildren($form_id,$csportal_con,$query_string,$dec_ci));
											// array_push($siblings, getSibling($parent_form_id,$csportal_con));
											$current = array($form_id, $parent_form_id, $form_order, $form_name, $navigation_name, $mobile_page, $description,$main_category);
											// while($has_parent){
											// 	$new_arr = getParent($parent_form_id,$csportal_con,$query_string,$dec_ci);
											// 	$parent_form_id = $new_arr[0][1];
											// 	$has_parent = $new_arr[0][1]!=0? true:false;
											// 	array_unshift($parents, $new_arr);
											// }
										}
									}
								}
							}
						}
					}
				}
			}

	}

	else{
		// echo 'empty';
		$sql_query_empty = "SELECT client_type as c_t FROM cs_users WHERE user_id=".$dec_ui;
		$exe_query_empty = mysqli_query($csportal_con,$sql_query_empty);
		if($exe_query_empty){
			if(mysqli_num_rows($exe_query_empty)!=0){
				$fetch_query_empty = mysqli_fetch_array($exe_query_empty);
				extract($fetch_query_empty);
				$client_type = $c_t;
					// 10111 -- Projects Folder
					

					$sql_query6 = "SELECT form_id, parent_form_id, form_order, form_name, navigation_name, mobile_page, description, main_category FROM cs_all_forms_new WHERE parent_form_id is NOT NULL";
							$exe_query6 = mysqli_query($csportal_con, $sql_query6);

					if($exe_query6){
						if(mysqli_num_rows($exe_query6)!=0){
							while($fetch_query6 = mysqli_fetch_array($exe_query6)){
								extract($fetch_query6);

								$has_parent = $parent_form_id!=0? true:false;

								if ($has_parent){
									if ($client_type=="owner_admin"){
										array_push($all_available_forms, array($form_id,$form_name,$mobile_page));
									}
									else if ($client_type=="owner_worker"){
										if (in_array($form_id, $employee_array)){
											array_push($all_available_forms, array($form_id,$form_name,$mobile_page));
										}
									}
									else if ($client_type=="contractor"||$client_type=="project_worker"){
										if (in_array($form_id, $contractor_array)){
											array_push($all_available_forms, array($form_id,$form_name,$mobile_page));
										}
									}
								}

								if($mobile_page==$php_page){
									if ($client_type=="owner_admin"){
										array_push($children, getChildrenManager($form_id,$csportal_con,"manager",$employee_array,$contractor_array));
										$current = array($form_id, $parent_form_id, $form_order, $form_name, $navigation_name, $mobile_page, $description,$main_category);
									}
									else if ($client_type=="owner_worker"){
										if (in_array($form_id, $employee_array)){
											array_push($children, getChildrenManager($form_id,$csportal_con,"employee",$employee_array,$contractor_array));
											$current = array($form_id, $parent_form_id, $form_order, $form_name, $navigation_name, $mobile_page, $description,$main_category);
										}
									}
									else if ($client_type=="contractor"||$client_type=="project_worker"){
										if (in_array($form_id, $contractor_array)){
											array_push($children, getChildrenManager($form_id,$csportal_con,"contractor",$employee_array,$contractor_array));
											$current = array($form_id, $parent_form_id, $form_order, $form_name, $navigation_name, $mobile_page, $description,$main_category);
										}
									}
								}
							}
						}
					}
			}
		}
	}


	// START - INSERT SEARCH BAR HERE
	$array_pages = ['199', '87', '186', '100', '44', '10206', '110', '200', '10094', '10108', '10125'
					// '10053'  -- REPORTS PAGE
				   ];
	$array_whs = [
					// WHS INDUCTION PAGES
                 	'10136','10137','10182'
				 ];
	$array_reports = [
					// REPORT PAGES 
					'10004', '10211', '10105', '10032', '10038', '10014'
					];

	$notes_children = ['10143', '10144', '10187'];
	$array_features = array();
	$list = array();
	    $index = 0;
	    foreach ($all_available_forms as $search_form) {
	    	if (in_array($search_form[0], $array_pages)||in_array($search_form[0], $array_reports)||in_array($search_form[0], $notes_children)||in_array($search_form[0], $array_whs)){
		    	$list[$index] = '{"label":"'.$search_form[1].'", "value":"'.$search_form[2].'"}';
		    	$index++;	    		
	    	}
	    }
	    foreach ($all_available_forms as $form_) {
	    	if(in_array($form_[0], $array_pages)){
	    		$time_sub = '0';
	    		$connection = $theodore_con;
	    		$query_last_sub = "SELECT date_submitted FROM _notification_actions WHERE client_id=".$dec_ci." AND form_id=".$form_[0]." AND project_name='".$project_name."' ORDER BY id DESC LIMIT 1";
	    		if ($form_[0]=="10108"){ //NOTES
		    		$query_last_sub = "SELECT date_submitted FROM _notification_actions WHERE client_id=".$dec_ci." AND table_name='tbl_dashboard_notes' AND project_name='".$project_name."' ORDER BY id DESC LIMIT 1";
		    		echo '<script>console.log("'.$query_last_sub.'");</script>';
	    		}
	    		else if ($form_[0]=="10206"){ //SWMS
		    		$query_last_sub = "SELECT date_submitted FROM _notification_actions WHERE client_id=".$dec_ci." AND table_name='_submission_212' AND project_name='".$project_name."' ORDER BY id DESC LIMIT 1";
	    		}
	    		else if ($form_[0]=="10094"){ //CUSTOMER NOTICES
	    			$query_last_sub = "SELECT date_submitted FROM _submission_260 WHERE JClientID29104004=".$dec_ci." AND JProjectName29105237='".$project_name."' ORDER BY id DESC LIMIT 1";
	    		}
	    		else if ($form_[0]=="200"){ //EQUIPMENT HIRE
	    			$query_last_sub = "SELECT date_submitted FROM tbl_equipment_report WHERE client_id=".$dec_ci." AND project_name='".$project_name."' ORDER BY id DESC LIMIT 1";
	    			$connection = $csportal_con;
	    		}
	    		else if ($form_[0]=="199"){ //TIMESHEET
	    			$query_last_sub = "SELECT date_submitted FROM _notification_actions WHERE client_id=".$dec_ci." AND table_name='_timesheet_details' AND project_name='".$project_name."' ORDER BY id DESC LIMIT 1";
	    		}
	    		else if ($form_[0]=="186"){ //WHS INDUCTION
	    			$query_last_sub = "SELECT date_submitted FROM _notification_actions WHERE client_id=".$dec_ci." AND table_name='_submission_91' AND project_name='".$project_name."' ORDER BY id DESC LIMIT 1";
	    		}
	    		else if ($form_[0]=="110"){ //SAFETY INSPECTION
	    			$query_last_sub = "SELECT date_submitted FROM _notification_actions WHERE client_id=".$dec_ci." AND table_name='_submission_39' AND project_name='".$project_name."' ORDER BY id DESC LIMIT 1";
	    		}
	    		else if ($form_[0]=="100"){ //EXPENSE FORM
	    			$query_last_sub = "SELECT editedon as date_submitted FROM tbl_expensesheet WHERE client_id=".$dec_ci." AND project_name='".$project_name."' ORDER BY id DESC LIMIT 1";
	    			$connection = $csportal_con;
	    		}
	    		else if ($form_[0]=="87"){ //SITE DIARY
	    			$query_last_sub = "SELECT date_submitted FROM _notification_actions WHERE client_id=".$dec_ci." AND table_name='_submission_82' AND project_name='".$project_name."' ORDER BY id DESC LIMIT 1";
	    		}
	    		else if ($form_[0]=="44"){ //TOOLBOX TALK
	    			$query_last_sub = "SELECT date_submitted FROM _notification_actions WHERE client_id=".$dec_ci." AND table_name='_submission_41' AND project_name='".$project_name."' ORDER BY id DESC LIMIT 1";
	    		}

	    		else if ($form_[0]=="10125"){ //SUBCONTRACT PURCHASE ORDER - QUICK PO
	    			$query_last_sub = "SELECT date_submitted FROM _submission_257 WHERE JClientIT05114954C160652C144552=".$dec_ci." AND JProjectname05115337C160652C144552='".$project_name."' ORDER BY id DESC LIMIT 1";
	    		}

	    		// echo '<script>console.log("'.$query_last_sub.'");</script>';
	    		$exe_query_last = mysqli_query($connection,$query_last_sub);
					if($exe_query_last){
						if(mysqli_num_rows($exe_query_last)!=0){
							$fetch_query_last = mysqli_fetch_array($exe_query_last);
							extract($fetch_query_last);
							$time_sub = humanTiming(strtotime($date_submitted));
						}
					}
	    		array_push($array_features, array('form_id'=>$form_[0],'form_name'=>$form_[1],'form_page'=>$form_[2],'time_sub'=>$time_sub));
	    	}
	    }
            function humanTiming ($time){
            	// minus 2 hours from Australian Time
                $time = (time()- 2*60*60)- $time; // to get the time since that moment
                $time = ($time<1)? 1 : $time;
                $tokens = array (
                    31536000 => 'year',
                    2592000 => 'month',
                    604800 => 'week',
                    86400 => 'day',
                    3600 => 'hour',
                    60 => 'minute',
                    1 => 'second'
                );

                foreach ($tokens as $unit => $text) {
                    if ($time < $unit) continue;
                    $numberOfUnits = floor($time / $unit);
                    return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
                }
            }
	// END - INSERT SEARCH BAR HERE

	$children = array_sort($children[0], '2', SORT_ASC);


	if($current[1]=="0"){
		$current_parent = $current;
	}
	else {
		$current_parent = getMainParent($current[7], $csportal_con, $query_string, $dec_ci);
	}

	// echo '<nav class="navbar navbar-default" id="main_nav">
	// 			<div class="navbar-header">
	// 				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
	// 				<span class="icon-bar"></span>
	// 				<span class="icon-bar"></span>
	// 				<span class="icon-bar"></span>
	// 				</button>
	// 			</div>';

	// echo '<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	// 				<ul class="nav navbar-nav">';
	// 			if(!empty($user_type_columns)){
	// 				$siblings = getSibling('0',$csportal_con,$query_string,$dec_ci,$user_type_columns,$employee_array,$contractor_array,$client_type);
	// 			}
	// 			else {
	// 				$siblings = getSibling('0',$csportal_con,$query_string,$dec_ci,$user_type_columns,$employee_array,$contractor_array,$client_type);
	// 			}
	// 			// print_r($siblings);
	// 			$siblings = array_sort($siblings,'2',SORT_ASC);
	// 			foreach ($siblings as $sibling) {
	// 				if($sibling[0] == "10058"){ //SKIP PRE-CONSTRUCTION PHASE
	// 					continue;
	// 				}

	// 				$activeness = "";
	// 				if($current[1]=="0"){
	// 					$activeness = $current[0]==$sibling[0]? "class='active';":"";
	// 				}
	// 				else{
	// 					$activeness = $current[7]==$sibling[3]? "class='active';":"";
	// 				}
	// 				if (strlen($pid)>0){
	// 					echo "<li ".$activeness." id='form_".$sibling[0]."'>
	// 							<a href='".$sibling[5]."?ui=".$ui."&ci=".$ci."' class='c-navlinks'>".$sibling[4]." ".
	// 											$sibling[6]."</a>
	// 						 </li>";
	// 				}
	// 				else {
	// 					echo "<li ".$activeness." id='form_".$sibling[0]."'>
	// 							<a href='".$sibling[5]."?ui=".$ui."&ci=".$ci."' class='c-navlinks'>".$sibling[4]." ".
	// 											$sibling[6]."</a>
	// 						 </li>";
	// 				}
	// 			}
	// echo "
	// 			</ul>
	// 		</div>
	// 	</nav>";

	// if(count($children)>0){
	// 	foreach($children as $child){
	// 		if (in_array($child[0], $array_pages)||in_array($child[0], $notes_children)||in_array($child[0], $array_reports)||in_array($child[0], $array_whs)){
	// 			if (basename($_SERVER['PHP_SELF'])!="_m.index.php"){
	// 				echo "<br id='br_".$child[0]."'><button type='button' class='btn btn-default btn-lg btn-block' id='".$child[0]."' onclick=window.location='".$child[5]."?ui=".$ui."&ci=".$ci."';>".$child[4]."</button>";									
	// 			}
	// 		}
	// 	}
	// }
	
	
	
	
	
	// print_r($current_parent);

	function getParentManager($parent_form_id,$cs,$str,$arr_emp,$arr_con){
		$arr = array();
		$sql_get_parent = "SELECT form_id, parent_form_id, form_order, form_name, navigation_name, mobile_page, description 
						   FROM cs_all_forms_new WHERE form_id=".$parent_form_id;
						   // echo $sql_get_parent;
		$exe_get_parent = mysqli_query($cs, $sql_get_parent);

		if($exe_get_parent){
			if(mysqli_num_rows($exe_get_parent)!=0){
				while($fetch_get_parent = mysqli_fetch_array($exe_get_parent)){
					extract($fetch_get_parent);
					if ($str=="manager"){
						array_push($arr,array($form_id, $parent_form_id, $form_order, $form_name, $navigation_name, $mobile_page, $description));
					}
					else if ($str=="employee"){
						if(in_array($form_id, $arr_emp)){
							array_push($arr,array($form_id, $parent_form_id, $form_order, $form_name, $navigation_name, $mobile_page, $description));
						}
					}
					else if ($str=="contractor"){
						if(in_array($form_id, $arr_con)){
							array_push($arr,array($form_id, $parent_form_id, $form_order, $form_name, $navigation_name, $mobile_page, $description));
						}	
					}
				}
			}
		}

		return $arr;
	}

	function getChildrenManager($parent_form_id,$cs,$str,$arr_emp,$arr_con){
		$arr = array();
		$sql_get_parent = "SELECT form_id, parent_form_id, form_order, form_name, navigation_name, mobile_page, description 
						   FROM cs_all_forms_new WHERE parent_form_id=".$parent_form_id;
						   // echo $sql_get_parent;
		$exe_get_parent = mysqli_query($cs, $sql_get_parent);

		if($exe_get_parent){
			if(mysqli_num_rows($exe_get_parent)!=0){
				while($fetch_get_parent = mysqli_fetch_array($exe_get_parent)){
					extract($fetch_get_parent);
					if ($str=="manager"){
						array_push($arr,array($form_id, $parent_form_id, $form_order, $form_name, $navigation_name, $mobile_page, $description));
					}
					else if ($str=="employee"){
						if(in_array($form_id, $arr_emp)){
							array_push($arr,array($form_id, $parent_form_id, $form_order, $form_name, $navigation_name, $mobile_page, $description));
						}
					}
					else if ($str=="contractor"){
						if(in_array($form_id, $arr_con)){
							array_push($arr,array($form_id, $parent_form_id, $form_order, $form_name, $navigation_name, $mobile_page, $description));
						}	
					}
				}
			}
		}

		return $arr;
	}

	function getMainNav($parent_name, $cs, $string,$dec_ci){
		$arr = array();
		$sql_get_parent = "SELECT a.form_id, a.parent_form_id, a.form_order, a.form_name, a.navigation_name, a.mobile_page, a.description 
						   FROM cs_all_forms_new as a 
						   INNER JOIN cs_usertagged_forms_".$dec_ci." as b
						   ON a.form_id=b.form_id
						   WHERE a.form_name='".$parent_name."'
						   AND a.parent_form_id=0  
						   AND (".$string.")";
						   // echo $sql_get_parent;
		$exe_get_parent = mysqli_query($cs, $sql_get_parent);

		if($exe_get_parent){
			if(mysqli_num_rows($exe_get_parent)!=0){
				while($fetch_get_parent = mysqli_fetch_array($exe_get_parent)){
					extract($fetch_get_parent);
					array_push($arr,array($form_id, $parent_form_id, $form_order, $form_name, $navigation_name, $mobile_page, $description));
				}
			}
		}

		return $arr;
	}

	function getMainParent($parent_name, $cs, $string,$dec_ci){
		$arr = array();
		$sql_get_parent = "SELECT a.form_id, a.parent_form_id, a.form_order, a.form_name, a.navigation_name, a.mobile_page, a.description 
						   FROM cs_all_forms_new as a 
						   INNER JOIN cs_usertagged_forms_".$dec_ci." as b
						   ON a.form_id=b.form_id
						   WHERE a.form_name='".$parent_name."'
						   AND a.parent_form_id=0  
						   AND (".$string.")";
						   // echo $sql_get_parent;
		$exe_get_parent = mysqli_query($cs, $sql_get_parent);

		if($exe_get_parent){
			if(mysqli_num_rows($exe_get_parent)!=0){
				while($fetch_get_parent = mysqli_fetch_array($exe_get_parent)){
					extract($fetch_get_parent);
					array_push($arr,array($form_id, $parent_form_id, $form_order, $form_name, $navigation_name, $mobile_page, $description));
				}
			}
		}

		return $arr;
	}
	// echo mysql_error($csportal_con);
	// echo mysqli_error($csportal_con);
	function getChildren($child_form_id, $cs, $string,$dec_ci){
		$arr = array();
		$sql_get_children = "SELECT a.form_id, a.parent_form_id, a.form_order, a.form_name, a.navigation_name, a.mobile_page, a.description 
						   FROM cs_all_forms_new as a 
						   INNER JOIN cs_usertagged_forms_".$dec_ci." as b
						   ON a.form_id=b.form_id
						   WHERE a.parent_form_id=".$child_form_id."
						   AND (".$string.")";
		$exe_get_children = mysqli_query($cs, $sql_get_children);

		if($exe_get_children){
			if(mysqli_num_rows($exe_get_children)!=0){
				while($fetch_get_children = mysqli_fetch_array($exe_get_children)){
					extract($fetch_get_children);
					array_push($arr,array($form_id, $parent_form_id, $form_order, $form_name, $navigation_name, $mobile_page, $description));
				}
			}
			else {
				return;
			}
		}

		return $arr;
	}

	function getSibling($child_form_id, $cs,$string,$dec_ci,$user_type_columns,$arr_emp,$arr_con,$str){
		$arr = array();
		if(!empty($user_type_columns)){
			$sql_get_sibling = "SELECT a.form_id, a.parent_form_id, a.form_order, a.form_name, a.navigation_name, a.mobile_page, a.description
						   FROM cs_all_forms_new as a 
						   INNER JOIN cs_usertagged_forms_".$dec_ci." as b
						   ON a.form_id=b.form_id
						   WHERE a.parent_form_id=".$child_form_id."
						   AND (".$string.")";
		}
		else {
			$sql_get_sibling = "SELECT form_id, parent_form_id, form_order, form_name, navigation_name, mobile_page, description 
						   FROM cs_all_forms_new WHERE parent_form_id=".$child_form_id;
		}
		// echo $sql_get_sibling;
		// echo $sql_get_sibling;
		$exe_get_sibling = mysqli_query($cs, $sql_get_sibling);
		// echo mysqli_error($cs);
		// echo $str;
		if($exe_get_sibling){
			if(mysqli_num_rows($exe_get_sibling)!=0){
				while($fetch_get_sibling = mysqli_fetch_array($exe_get_sibling)){
					extract($fetch_get_sibling);
					if(!empty($user_type_columns)){
						array_push($arr,array($form_id, $parent_form_id, $form_order, $form_name, $navigation_name, $mobile_page, $description));
					}
					else {
						if ($str=="owner_admin"){
							array_push($arr,array($form_id, $parent_form_id, $form_order, $form_name, $navigation_name, $mobile_page, $description));
						}
						else if ($str=="owner_worker"){
							if(in_array($form_id, $arr_emp)){
								array_push($arr,array($form_id, $parent_form_id, $form_order, $form_name, $navigation_name, $mobile_page, $description));
							}
						}
						else if ($str=="contractor"||$str=="project_worker"){
							if(in_array($form_id, $arr_con)){
								array_push($arr,array($form_id, $parent_form_id, $form_order, $form_name, $navigation_name, $mobile_page, $description));
							}	
						}
					}
					
				}
			}
			else {
				return;
			}
		}


		return $arr;
	}
		// print_r($current);
		

	function array_sort($array, $on, $order=SORT_ASC){
	    $new_array = array();
	    $sortable_array = array();

	    if (count($array) > 0) {
	        foreach ($array as $k => $v) {
	            if (is_array($v)) {
	                foreach ($v as $k2 => $v2) {
	                    if ($k2 == $on) {
	                        $sortable_array[$k] = $v2;
	                    }
	                }
	            } else {
	                $sortable_array[$k] = $v;
	            }
	        }

	        switch ($order) {
	            case SORT_ASC:
	                asort($sortable_array);
	                break;
	            case SORT_DESC:
	                arsort($sortable_array);
	                break;
	        }

	        foreach ($sortable_array as $k => $v) {
	            $new_array[$k] = $array[$k];
	        }
	    }

	    return $new_array;
	}
// print_r($list);

?>

<script type="text/javascript">
	$(function() {
	    var names = [<?php echo implode(',',$list); ?>];
	    $( ".autofillSearchForm").autocomplete({
		    source: names,
		    select: function(event,ui){
    	    event.preventDefault();
	        $(".autofillSearchForm").val(ui.item.label);
	        window.location.href = ui.item.value + "?ui=<?php echo $ui; ?>&ci=<?php echo $ci; ?>";
	        }
	    });
	});
</script>