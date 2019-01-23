<?php
	if($_user_pic == 'default_user.png'){
		$_user_pic = ''.asset_host().'/builder/'.FILEDIR.'/default_icon.png';
		
		$query = 
				"SELECT user_color 
				FROM cs_users 
				WHERE user_id = ".$dec_ui." 
				AND client_id = ".$dec_ci."
				AND user_color <> ''";
		$executedQuery = mysqli_query($csportal_con, $query);
		if(mysqli_num_rows($executedQuery)!=0) {
			while($extractSelect = mysqli_fetch_array($executedQuery)) {
				extract($extractSelect);
			}
			$display_photo_bg = $user_color;
		}else{
			$display_photo_bg = 'grey';
		}
		
		$hide_initial = '';
		$hide_default_photo = 'display:none';
		
		$_user_firstname = trim($_user_firstname);
		$_user_lastname = trim($_user_lastname);
		
		if($_user_firstname != ''){
			$initial = $_user_firstname[0];
		}else if($_user_lastname != ''){
			$initial = $_user_lastname[0];
		}else{
			$hide_initial = 'display:none;';
			$hide_default_photo = 'opacity:0.7;';
		}
	}else{
		$_user_pic = ''.asset_host().'/builder/'.FILEDIR.'/'.$_user_pic;
		$display_photo_bg = '#fcfcfc';
	}
?>
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

<script>
function notification_load_invite(){
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
		notification_load_invite();
		$('.button-notif-badge').text(parseFloat($('.button-notif-badge').text()) - 1);
	});
}
</script>
<?php
	$getProjectName = "SELECT id, JProjectName140430 as pname_result FROM `_submission_204` WHERE JClientID140400 = '".$dec_ci."' AND id='".$pid."' ";

	$get_project = mysqli_query($theodore_con, $getProjectName);

	if(mysqli_num_rows($get_project) != 0)
	{
		$row = mysqli_fetch_array($get_project);

		$project_name = $row["pname_result"];
	}

	$user_type_array = array();
    // echo $pid;
    if (strlen($pid)>0)
    {
        // SELECT TEAM ID FROM PROJECTS TO CONNECT WITH MEMBERS - ACCESS WITH PID AND STATUS (WITH TEAM)
        $sql_query1 = "SELECT team_id FROM cs_team_project WHERE status = 1 AND project_id = ".$pid;
        $exe_query1 = mysqli_query($csportal_con, $sql_query1);

        if ($exe_query1)
        {
            if(mysqli_num_rows($exe_query1)!=0)
            {
                while($fetch_query1 = mysqli_fetch_array($exe_query1))
                {
                    extract($fetch_query1);
                                // array_push($user_type_array, $team_id);
                    
                    // SELECT USER TYPE ID FROM MEMBERS TO CONNECT WITH USER MANAGEMENT - ACCESS WITH UID, STATUS AND TEAM ID
                    $sql_query2 = "SELECT user_type_id FROM cs_team_members WHERE status = 1 AND user_id = ".$dec_ui." AND team_id = ".$team_id;
                    $exe_query2 = mysqli_query($csportal_con, $sql_query2);

                    if ($exe_query2){
                        if (mysqli_num_rows($exe_query2)!=0)
                        {
                            while($fetch_query2 = mysqli_fetch_array($exe_query2))
                            {
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

        if($exe_query3)
        {
            if(mysqli_num_rows($exe_query3)!=0)
            {
                while($fetch_query3 = mysqli_fetch_array($exe_query3))
                {
                    extract($fetch_query3);
                    array_push($user_type_array, $user_type_id);
                }
            }
        }
    } //if (strlen($pid)>0)

    else 
    {
        
        /** Start - Update condtion - Adjustment for New Users - 20171204 0 AQS **/
        
        $sql_query_else = "SELECT user_type_id FROM cs_team_members WHERE status = 1 AND user_id = ".$dec_ui;
        $exe_query_else = mysqli_query($csportal_con, $sql_query_else);

        if($exe_query_else)
        {
            if(mysqli_num_rows($exe_query_else)!=0)
            {
                while($fetch_query_else = mysqli_fetch_array($exe_query_else))
                {
                    extract($fetch_query_else);
                    array_push($user_type_array, $user_type_id);
                }
            } 
            else 
            {
                $sql_query_else2 = "SELECT user_type_id FROM cs_team_project WHERE status = 0 AND team_id = 0 AND user_id = ".$dec_ui;
                $exe_query_else2 = mysqli_query($csportal_con, $sql_query_else2);
        
                if($exe_query_else2)
                {
                    if(mysqli_num_rows($exe_query_else2)!=0)
                    {
                        while($fetch_query_else2 = mysqli_fetch_array($exe_query_else2))
                        {
                            extract($fetch_query_else2);
                            array_push($user_type_array, $user_type_id);
                        }
                    } 
                    else 
                    {
                        
                        if($_user_is_owner == 1)
                        {
                            $sql_query_else3 = "SELECT id as user_type_id FROM cs_usertype_management WHERE client_id = '".$dec_ci."' AND name = 'manager'";
                            $exe_query_else3 = mysqli_query($csportal_con, $sql_query_else3);
                            
                            if($exe_query_else3)
                            {
                                if(mysqli_num_rows($exe_query_else3)!=0)
                                {
                                    while($fetch_query_else3 = mysqli_fetch_array($exe_query_else3))
                                    {
                                        extract($fetch_query_else3);
                                        array_push($user_type_array, $user_type_id);
                                    }
                                }
                            }    
                        }
                    }
                }
            }
        }

        
    }// if (strlen($pid)>0) else

    /* --------------------------------------------------------------------------------------------------------------------- */

    $user_type_columns = array();
    foreach($user_type_array as $user_type)
    {
        $sql_query_loop = "SELECT name FROM cs_usertype_management WHERE status=1 AND id = ".$user_type;
        $exe_query_loop = mysqli_query($csportal_con, $sql_query_loop);

        if($exe_query_loop)
        {
            if(mysqli_num_rows($exe_query_loop)!=0)
            {
                while($fetch_query_loop = mysqli_fetch_array($exe_query_loop))
                {
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

    $navigation_array = array();

    $all_available_forms = array();

    if (!empty($user_type_columns))
    {
        $columns = implode(",", $user_type_columns);
        $count = 1;
        foreach ($user_type_columns as $user_type_column)
        {
            if ($count!=count($user_type_columns))
            {
                $query_string .= $user_type_column . "=1 OR ";
            }
            else 
            {
                $query_string .= $user_type_column . "=1";
            }
            $count++;
        }
        $sql_query4 = "SELECT form_id,".$columns." FROM cs_usertagged_forms_".$dec_ci;
        $exe_query4 = mysqli_query($csportal_con, $sql_query4);

            if($exe_query4)
            {
                if(mysqli_num_rows($exe_query4)!=0)
                {
                        
                    while($fetch_query4 = mysqli_fetch_array($exe_query4))
                    {
                        // extract($fetch_query4);
                        $form_id = $fetch_query4['form_id'];
                        $flag = false;
                        foreach($user_type_columns as $user_type_column)
                        {
                            if($fetch_query4[$user_type_column]=="1")
                            {
                                $flag = true;
                            }
                        }

                        if($flag)
                        {
                            $sql_query5 = "SELECT form_id, parent_form_id, form_order, form_name, navigation_name, page_name, description FROM cs_all_forms_new WHERE form_id=".$form_id." AND nav_only=0 ";
                            $exe_query5 = mysqli_query($csportal_con, $sql_query5);
                            if($exe_query5){
                                if(mysqli_num_rows($exe_query5)!=0)
                                {
                                    while($fetch_query5 = mysqli_fetch_array($exe_query5))
                                    {
                                        extract($fetch_query5);
                                        // $counter = 1;

                                        $has_parent = $parent_form_id!=0? true:false;
                                        
                                        if ($has_parent
                                        ){
                                            array_push($all_available_forms, array($form_id,$form_name,$page_name));
                                        }

                                        if(!$has_parent)
                                        {
                                            array_push($navigation_array,array($form_id,$form_order,$form_name, $page_name, $description));
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

    }

    else
    {
        // echo 'empty';
        $sql_query_empty = "SELECT client_type FROM cs_users WHERE user_id=".$dec_ui;
        $exe_query_empty = mysqli_query($csportal_con,$sql_query_empty);
        if($exe_query_empty)
        {
            if(mysqli_num_rows($exe_query_empty)!=0)
            {
                $fetch_query_empty = mysqli_fetch_array($exe_query_empty);
                extract($fetch_query_empty);

                $employee_array = ["10112","10111","10108","10059","10055","200","100","10056","157","3000","10060","109","186","10182","10061","10052","193","110","87","11","10173","10174","10175","10176","147","199","44","107","10058","13"];
                    $contractor_array = ["10112","10111","10108","10061","10052","193","10060","11","10173","10174","10175","10176"];

                    $sql_query6 = "SELECT form_id, parent_form_id, form_order, form_name, navigation_name, page_name, description FROM cs_all_forms_new WHERE parent_form_id is NOT NULL AND nav_only=0 ";
                    $exe_query6 = mysqli_query($csportal_con, $sql_query6);
                    if($exe_query6)
                    {
                        if(mysqli_num_rows($exe_query6)!=0)
                        {
                            while($fetch_query6 = mysqli_fetch_array($exe_query6))
                            {
                                extract($fetch_query6);
                                // $counter = 1;

                                $has_parent = $parent_form_id!=0? true:false;
                                
                                if ($has_parent)
                                {
                                    if ($client_type=="owner_admin")
                                    {
                                        array_push($all_available_forms, array($form_id,$form_name,$page_name));
                                    }
                                    else if ($client_type=="owner_worker")
                                    {
                                        if (in_array($form_id, $employee_array))
                                        {
                                            array_push($all_available_forms, array($form_id,$form_name,$page_name));
                                        }
                                    }
                                    else if ($client_type=="contractor"||$client_type=="project_worker")
                                    {
                                        if (in_array($form_id, $contractor_array))
                                        {
                                            array_push($all_available_forms, array($form_id,$form_name,$page_name));
                                        }
                                    }
                                }

                                if (!$has_parent)
                                {
                                    if ($client_type=="owner_admin")
                                    {
                                        array_push($navigation_array,array($form_id,$form_order,$form_name, $page_name, $description));
                                    }
                                    else if ($client_type=="owner_worker")
                                    {
                                        if (in_array($form_id, $employee_array))
                                        {
                                            array_push($navigation_array,array($form_id,$form_order,$form_name, $page_name, $description));
                                        }
                                    }
                                    else if ($client_type=="contractor"||$client_type=="project_worker")
                                    {
                                        if (in_array($form_id, $contractor_array))
                                        {
                                            array_push($navigation_array,array($form_id,$form_order,$form_name, $page_name, $description));
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
    $list = array();
    $index = 0;
    foreach ($all_available_forms as $search_form) 
    {
        $list[$index] = '{"label":"'.$search_form[1].'", "value":"'.$search_form[2].'", "id":"'.$search_form[0].'"}';
        $index++;
    }
    // END - INSERT SEARCH BAR HERE

?>
	<script type="text/javascript">
		$(function() {
		    var names = [<?php echo implode(',',$list); ?>];
		    $( ".autofillSearch").autocomplete({
			    source: names,
			    select: function(event,ui){
					event.preventDefault();
					$(".autofillSearch").val(ui.item.label);
					window.location.href = ui.item.value + "?ui=<?php echo $ui; ?>&ci=<?php echo $ci; ?>";
		        },focus: function(event, ui){
					event.preventDefault();
					$(".autofillSearch").val(ui.item.label);
				}
		    });
		});
	</script>