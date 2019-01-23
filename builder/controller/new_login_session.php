<?php  
 echo session_status();
	if(isset($_GET['stat'])) { 
	
		if($_GET['stat']!="") { 
			if(session_start()) {
			 echo session_status();
				session_destroy();
				echo session_status(); 
				//header("Location: ./"); 
				} 
			} 
	}  
?>