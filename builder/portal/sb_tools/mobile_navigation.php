<?php
    /**START - Checks if current company still exists. If no, sign out account.*/
	$check_query = "SELECT * 
					FROM cs_users 
					WHERE email_address='".$_user_email."' 
					AND client_id = '".$dec_ci."' 
					AND user_status = 'approved'";
		$portal_company = mysqli_query($csportal_con, $check_query);
		if(mysqli_num_rows($portal_company)==0) {
			echo "<script>
					//alert('Please try logging in again.');
					window.location = '../index.php?stat=invalidaccess';
				</script>";
		}
	/**END - Checks if current company still exists.*/
?>

<style type="text/css">
	body {
		overflow-x: hidden;
	}
	.ui-autocomplete { max-width:90%;}
	#idlogo {
		width: 200px;
	}	
	@media (max-width: 768px){
		#idlogo {
			width: 100%;
			max-height: 150px !important;
		}	
	}
	 /*TIFF UPDATE -- HIDE I WANT TRAINING BUTTON*/
	.calendly-badge-widget{
	    display: none;
	}
</style>
<meta name="viewport" content="width=device-width, initial-scale = 1.0, maximum-scale=1.0, user-scalable=no" />
<div id="page-load">
    <div class="blackout-page-load"></div>
    <div class="page-load">
        <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Please wait...
    </div>
</div>


    <div id="c-container" style="display:none">
        <div class="c-container clearfix">
            <div class="row">
                <div class="col-md-8">
                    <img src="<?php echo asset_host(); ?>/builder/<?php echo FILEDIR."/".$_user_companylogo; ?>" onClick="javascript:window.location='_m.index.php?ui=<?php echo $ui; ?>&ci=<?php echo $ci; ?>'" alt="<?php echo $_company_business_name; ?>" id="idlogo" />
                </div>
                <div class="col-md-4">
                    <div class="welcomeMessage text-right">
                        <small>
                            Hi <?php echo $_user_firstname ?>, Welcome to <?php echo app_host_name(); ?> Portal! 
                            <img src="<?php echo asset_host(); ?>/builder/<?php echo FILEDIR."/".$_user_pic; ?>" title="Profile" class="c-thumbnail" id="c-thumbnail" />
                        </small>
                        
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
                    </div>
					<br/>
					<div class="input-group add-on pull-right">
						  <input class="form-control autofillSearchForm" placeholder="Search Forms..." name="search" id="srch-term" type="text">
						  <div class="input-group-btn">
							<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
						  </div>
					</div>
                </div>
            </div><br/>
            
<!-- Start - Main Navigation -->
<?php

	$dec_ui = base64_decode($_GET['ui']);
	$dec_ci = base64_decode($_GET['ci']);
	$pid = "";
	// $pid = ;
	if (isset($_GET['pid'])){$pid = base64_decode($_GET['pid']);}
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
							$sql_query5 = "SELECT form_id, parent_form_id, form_order, form_name, navigation_name, mobile_page, description, main_category FROM cs_all_forms_new WHERE form_id=".$form_id;
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
	$array_pages = ['199', '87', '186', '100', '44', '11', '110', '200', '10094', '10108', '10053', '10108', '10055'];
	$array_swms = [
					// SWMS PAGES
					'10204',
					'10206', 
					'10175', 
					'10174'
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
	$list = array();
	    $index = 0;
	    foreach ($all_available_forms as $search_form) {
	    	if (in_array($search_form[0], $array_pages)||in_array($search_form[0], $array_reports)||in_array($search_form[0], $notes_children)){
		    	$list[$index] = '{"label":"'.$search_form[1].'", "value":"'.$search_form[2].'"}';
		    	$index++;	    		
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

	echo '<nav class="navbar navbar-default" id="main_nav">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					</button>
				</div>';

	echo '<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">';
				if(!empty($user_type_columns)){
					$siblings = getSibling('0',$csportal_con,$query_string,$dec_ci,$user_type_columns,$employee_array,$contractor_array,$client_type);
				}
				else {
					$siblings = getSibling('0',$csportal_con,$query_string,$dec_ci,$user_type_columns,$employee_array,$contractor_array,$client_type);
				}
				// print_r($siblings);
				$siblings = array_sort($siblings,'2',SORT_ASC);
				foreach ($siblings as $sibling) {
					if($sibling[0] == "10058"){ //SKIP PRE-CONSTRUCTION PHASE
						continue;
					}

					$activeness = "";
					if($current[1]=="0"){
						$activeness = $current[0]==$sibling[0]? "class='active';":"";
					}
					else{
						$activeness = $current[7]==$sibling[3]? "class='active';":"";
					}
					if (strlen($pid)>0){
						echo "<li ".$activeness." id='form_".$sibling[0]."'>
								<a href='".$sibling[5]."?ui=".$ui."&ci=".$ci."' class='c-navlinks'>".$sibling[4]." ".
												$sibling[6]."</a>
							 </li>";
					}
					else {
						echo "<li ".$activeness." id='form_".$sibling[0]."'>
								<a href='".$sibling[5]."?ui=".$ui."&ci=".$ci."' class='c-navlinks'>".$sibling[4]." ".
												$sibling[6]."</a>
							 </li>";
					}
				}
	echo "
				</ul>
			</div>
		</nav>";

	if(count($children)>0){
		foreach($children as $child){
			if (in_array($child[0], $array_pages)||
				in_array($child[0], $notes_children)||
				in_array($child[0], $array_reports)||
				in_array($child[0], $array_whs)||
				in_array($child[0], $array_swms)){
				if (basename($_SERVER['PHP_SELF'])!="_m.index.php"){
					echo "<br id='br_".$child[0]."'><button type='button' class='btn btn-default btn-lg btn-block' id='".$child[0]."' onclick=window.location='".$child[5]."?ui=".$ui."&ci=".$ci."';>".$child[4]."</button>";									
				}
			}
		}
	}
	
	
	
	
	
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

<?php

	if (isset($_GET['pid'])&&(strlen($_GET['pid'])>2)) {	
?>
	<style type="text/css">
	#back_project{
		-webkit-appearance: none;
	}
	</style>
	<div class="under_nav" style="padding: 20px 0px 0px 0px;">
        <div>
            <button class="btn btn-block" id="back_project" style="background-color:#dddddd;color:#615959;font-size: 20px;" 
            onclick="window.location = '_m.dashboard_projects.php?ui=<?php echo $ui; ?>&ci=<?php echo $ci; ?>&pid=<?php echo $_GET['pid']; ?>';" >
                Back to Project
            </button>
            <br>
        </div>
    </div>
<?php 
	}
?>