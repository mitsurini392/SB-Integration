<style>
	.ui-widget-content{
		width: 100px;
	}
</style>
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
                            $sql_query5 = "SELECT form_id, parent_form_id, form_order, form_name, navigation_name, page_name, description FROM cs_all_forms_new WHERE form_id=".$form_id;
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

                    $sql_query6 = "SELECT form_id, parent_form_id, form_order, form_name, navigation_name, page_name, description FROM cs_all_forms_new WHERE parent_form_id is NOT NULL";
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

    function array_sort($array, $on, $order=SORT_ASC)
    {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) 
        {
            foreach ($array as $k => $v) 
            {
                if (is_array($v)) 
                {
                    foreach ($v as $k2 => $v2) 
                    {
                        if ($k2 == $on) 
                        {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } 
                else 
                {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) 
            {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) 
            {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }
    // print_r($current);
    // echo $query_string;
    

    $navigation_array = array_sort($navigation_array, '1', SORT_ASC);

    // START - INSERT SEARCH BAR HERE
    $list = array();
    $index = 0;
    foreach ($all_available_forms as $search_form) 
    {
        $list[$index] = '{"label":"'.$search_form[1].'", "value":"'.$search_form[2].'"}';
        $index++;
    }
    // END - INSERT SEARCH BAR HERE

    $singlenav .= '<div class="row" style="border: solid 1px #e7e7e7; background-color: rgb(250,250,250); padding-bottom: 0.5%; display:'.$navName.'" id="navName">
        <div class="col-sm-12">
            <div class="col-md-1"> </div>
            <div class="col-md-5">
                <h3 style="font-size:20px"> 
                    <a href="dashboard_projects.php?ui='.$ui.'&ci='.$ci.'&pid='.base64_encode($pid).'">'. $project_name.'</a> <span class="glyphicon glyphicon-chevron-right" style="margin-left: 2%;"></span> <span id="form_title" style="margin-left: 2%;">'. $form_name1.' </span>
                </h3> 
            </div>
            <div class="col-md-6 returnDiv" style="padding-top: 1%; padding-right: 5%;"> 
                <a class="pull-right" href="dashboard_projects.php?ui='.$ui.'&ci='.$ci.'&pid='.base64_encode($pid).'"><button type="button" class="btn btn-block btn-primary" onclick="goProject()"> RETURN TO PROJECT PAGE </button></a>
            </div>
        </div>
    </div>';
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
 <script>
 
</script>