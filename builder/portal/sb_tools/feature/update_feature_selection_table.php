	
<?php
    /***** start - update the location *****/
//	include("../../../controller/connection.php");
	include("../../../../theodore/formbuilder/controller/connection.php");
	/***** end - update the location *****/

    $val_feature = $_POST['feature'];
    $val_ui = $_POST['dec_ui'];
    $val_ci = $_POST['dec_ci'];
    $column_name = "fs_".$val_feature;
//    switch ($val_feature){
//        case "timesheet": 
//            $column_name = "";
//        case "expense":
//        case "payment":
//        case "site":
//        case "ceo":
//        case "forecast":
//    }

    $update_query = "UPDATE tbl_demoscript_checker SET ".$column_name."=1 WHERE user_id=".$val_ui." AND client_id=".$val_ci;
//    $update_query = "UPDATE tbl_demoscript_checker SET ".$column_name."=1, fs_last_form='".$val_feature."' WHERE user_id=".$val_ui." AND client_id=".$val_ci;
    $update = mysqli_query($theodore_con, $update_query);
    
    echo mysqli_error($theodore_con);
    
?>