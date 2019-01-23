<?php
		$dec_ui = base64_decode($_GET['ui']);
	$dec_ci = base64_decode($_GET['ci']);
	$client_type = $_SESSION['s_ctype'];
	$pid = "";
	// $pid = ;
	if (isset($_GET['pid'])){$pid = base64_decode($_GET['pid']);}
	$php_page = basename($_SERVER['PHP_SELF']);
	$user_type_array = array();

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
	// print_r($user_type_columns);
	$user_type_columns = array_filter($user_type_columns);
	$query_string="";

	$parents = array();
	$children = array();
	$siblings = array();
	$current = array();

	$all_available_forms = array();
	
	$employee_array = ["10112","10111","10108","10059","10055","200","100","10056","157","3000","10060","109","186","10182","10061","10052","193","110","87","11","10173","10174","10175","10176","147","199","44","107","10058","13"];
	$contractor_array = ["10112","10111","10108","10061","10052","193","10060","11","10173","10174","10175","10176"];
		

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
							$sql_query5 = "SELECT form_id, parent_form_id, form_order, form_name, navigation_name, page_name, description FROM cs_all_forms_new WHERE form_id=".$form_id;
							$exe_query5 = mysqli_query($csportal_con, $sql_query5);
							if($exe_query5){
								if(mysqli_num_rows($exe_query5)!=0){
									while($fetch_query5 = mysqli_fetch_array($exe_query5)){
										extract($fetch_query5);
										// $counter = 1;

										$has_parent = $parent_form_id!=0? true:false;
										
										if ($has_parent){
											array_push($all_available_forms, array($form_id,$form_name,$page_name));
										}

										if($page_name==$php_page){
											array_push($children, getChildren($form_id,$csportal_con,$query_string,$dec_ci));
											// array_push($siblings, getSibling($parent_form_id,$csportal_con));
											$current = array($form_id, $parent_form_id, $form_order, $form_name, $navigation_name, $page_name, $description);
											while($has_parent){
												$new_arr = getParent($parent_form_id,$csportal_con,$query_string,$dec_ci);
												$parent_form_id = $new_arr[0][1];
												$has_parent = $new_arr[0][1]!=0? true:false;
												array_unshift($parents, $new_arr);
											}
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
		$sql_query_empty = "SELECT client_type FROM cs_users WHERE user_id=".$dec_ui;
		$exe_query_empty = mysqli_query($csportal_con,$sql_query_empty);
		if($exe_query_empty){
			if(mysqli_num_rows($exe_query_empty)!=0){
				$fetch_query_empty = mysqli_fetch_array($exe_query_empty);
				extract($fetch_query_empty);
					
					

					$sql_query6 = "SELECT form_id, parent_form_id, form_order, form_name, navigation_name, page_name, description FROM cs_all_forms_new WHERE parent_form_id is NOT NULL";
					$exe_query6 = mysqli_query($csportal_con, $sql_query6);
					if($exe_query6){
						if(mysqli_num_rows($exe_query6)!=0){
							while($fetch_query6 = mysqli_fetch_array($exe_query6)){
								extract($fetch_query6);
								// $counter = 1;

								$has_parent = $parent_form_id!=0? true:false;
								
								if ($has_parent){
									if ($client_type=="owner_admin"){
										array_push($all_available_forms, array($form_id,$form_name,$page_name));
									}
									else if ($client_type=="owner_worker"){
										if (in_array($form_id, $employee_array)){
											array_push($all_available_forms, array($form_id,$form_name,$page_name));
										}
									}
									else if ($client_type=="contractor"||$client_type=="project_worker"){
										if (in_array($form_id, $contractor_array)){
											array_push($all_available_forms, array($form_id,$form_name,$page_name));
										}
									}
								}

								if($page_name==$php_page){
									if ($client_type=="owner_admin"){
										array_push($children, getChildrenManager($form_id,$csportal_con,"manager",$employee_array,$contractor_array));
										$current = array($form_id, $parent_form_id, $form_order, $form_name, $navigation_name, $page_name, $description);
										while($has_parent){
											$new_arr = getParentManager($parent_form_id,$csportal_con,"manager",$employee_array,$contractor_array);
											$parent_form_id = $new_arr[0][1];
											$has_parent = $new_arr[0][1]!=0? true:false;
											array_unshift($parents, $new_arr);
										}
									}
									else if ($client_type=="owner_worker"){
										if (in_array($form_id, $employee_array)){
											echo "owner_worker";
											array_push($children, getChildrenManager($form_id,$csportal_con,"employee",$employee_array,$contractor_array));
											$current = array($form_id, $parent_form_id, $form_order, $form_name, $navigation_name, $page_name, $description);
											while($has_parent){
												$new_arr = getParentManager($parent_form_id,$csportal_con,"employee",$employee_array,$contractor_array);
												$parent_form_id = $new_arr[0][1];
												$has_parent = $new_arr[0][1]!=0? true:false;
												array_unshift($parents, $new_arr);
											}
										}
									}
									else if ($client_type=="contractor"||$client_type=="project_worker"){
										if (in_array($form_id, $contractor_array)){
											array_push($children, getChildrenManager($form_id,$csportal_con,"contractor",$employee_array,$contractor_array));
											$current = array($form_id, $parent_form_id, $form_order, $form_name, $navigation_name, $page_name, $description);
											while($has_parent){
												$new_arr = getParentManager($parent_form_id,$csportal_con,"contractor",$employee_array,$contractor_array);
												$parent_form_id = $new_arr[0][1];
												$has_parent = $new_arr[0][1]!=0? true:false;
												array_unshift($parents, $new_arr);
											}
										}
									}
								}
							}
						}
					}
			}
		}
	}


	// print_r($current);
	// echo $query_string;
	

	

	// START - INSERT SEARCH BAR HERE
	// $list = array();
	//     $index = 0;
	//     foreach ($all_available_forms as $search_form) {
	//     	$list[$index] = '{"label":"'.$search_form[1].'", "value":"'.$search_form[2].'"}';
	//     	$index++;
	//     }
	print_r($all_available_forms);
	// END - INSERT SEARCH BAR HERE


	
?>