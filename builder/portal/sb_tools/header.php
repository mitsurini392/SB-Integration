<?php
//config

DEFINE("FILEDIR", "files");

/**
* Code Changes: i.e.,
* copy("generated_templates/file_name.docx", "../../../../uploaded_files/downloads/file_name.docx");
* copy("generated_templates/file_name.docx", "../../../../". FILEDIR ."/file_name.docx");
*/

function app_server(){
    if(strpos($_SERVER['HTTP_HOST'],'localhost') !== false)
        return 1;
    
    else if (strpos($_SERVER['HTTP_HOST'],'developement-ze32u3c1c3342.smallbuilders.com.au') !== false)
        return 5;
    
    else if (strpos($_SERVER['HTTP_HOST'],'smallbuilders.com.au') !== false)
        return 2;
    
    else if(strpos($_SERVER['HTTP_HOST'],'staging-z2pcdnr3vc2g1n.buildersadmin.com') !== false)
        return 3;
    
    else if(strpos($_SERVER['HTTP_HOST'],'development-z3euwcec3342.buildersadmin.com') !== false)
        return 4;
    
    else //default to buildersadmin
        return 0;
}

function app_host_name(){
    if(app_server() == 1)
        return "Builders Admin (LocalHost)";
    
    else if(app_server() == 2)
        return "Small Builders";
    
    else if(app_server() == 3)
        return "Builders Admin (Staging)";
    
    else if(app_server() == 4)
        return "Builders Admin (DevSite)";
    
    else if(app_server() == 5)
        return "Small Builders (DevSite)";
    
    else
        return "Builders Admin";
}

function asset_host(){
    if(app_server() == 1)
        return "//localhost/SB-Integration";
    
    else if(app_server() == 2)
        return "//www.smallbuilders.com.au";
    
    else if(app_server() == 3)
        return "//staging-z2pcdnr3vc2g1n.buildersadmin.com";
    
    else if(app_server() == 4)
        return "//development-z3euwcec3342.buildersadmin.com";
    
    else if(app_server() == 5)
        return "//developement-ze32u3c1c3342.smallbuilders.com.au";
    
    else
        return "//www.buildersadmin.com";
}

function integration_callback(){
    return asset_uri();
}

function asset_uri(){
    if(app_server() == 1)
        return "http://localhost/SB-Integration";
    
    else if(app_server() == 2)
        return "https://www.smallbuilders.com.au";
    
    else if(app_server() == 3)
        return "https://staging-z2pcdnr3vc2g1n.buildersadmin.com";
    
    else if(app_server() == 4)
        return "https://development-z3euwcec3342.buildersadmin.com";
    
    else if(app_server() == 5)
        return "https://developement-ze32u3c1c3342.smallbuilders.com.au";
    
    else
        return "https://www.buildersadmin.com";
}

function keypay_config(){
    if(app_server() == 1){
        $app_name = "Builders Admin (LocalHost)";
        $client_id = "f6vLvpBO0IPWR6yAjcZ4ULto";
        $client_secret = "tOSwmKDy3a4nr6rpjhf4HzzP";
    } else if(app_server() == 2){
        $app_name = "Small Builders";
        $client_id = "duSjsKnVE36xUUrGnA4kNAFg";
        $client_secret = "tjwhPnjFVaYafHDcbRCzsXFs";
    } else if(app_server() == 3){
        $app_name = "Builders Admin (Staging)";
        $client_id = "mE7AxAGbevOx2UzrL8QozAem";
        $client_secret = "qs58ReoM8C6bdcLDAfMujaDv";
    } else {
        $app_name = "Builders Admin";
        $client_id = "";
        $client_secret = "";
    }
    
    $params = array(
        'app_name' => $app_name,
        'client_id' => $client_id,
        'client_secret' => $client_secret
    );
    
    return $params;

}

function google_maps_key(){
    if(app_server() == 1 || app_server() == 3 || app_server() == 4 || app_server() == 5)
        return "AIzaSyB-i0TrNwgf7ZtnOMe5WnQ4QWa-SjiXP9M";

    else
        return "AIzaSyBhmU_XTXJ7wn5uRp13fc9XDP0y-6QkWeI";
}

function office365_calender_config(){
    if(app_server() == 1){
        $app_key = "e478aa6d-23af-43f7-b4da-b8e93b3cc262";
            
    } else if(app_server() == 2){
        $app_key = "eeee9dfd-09f5-4989-9e34-25848b9b3204";
        
    } else if(app_server() == 3){
        $app_key = "733376c4-fa72-4df2-b422-a78f892c312e";
    
    } else if(app_server() == 4){
        $app_key = "b49113cd-b7eb-4ed0-96ba-156cac451240";
    } else {
        $app_key = "180e0046-f8bc-4697-a18d-befcea277639";
    }
    
    $params = array(
        'app_key' => $app_key
    );
    
    return $params;
}

function office365_onedrive_config(){
    if(app_server() == 1){
        $app_key = "5dd0e840-2376-4e8c-b126-7872b1926e9b";
            
    } else if(app_server() == 2){
        $app_key = "9e601f15-a090-451b-84d4-5910d8969f2d";
        
    } else if(app_server() == 3){
        $app_key = "8b3a2096-4bd0-4f00-b5af-fd9470f10cf3";
    
    } else if(app_server() == 4){
        $app_key = "84312128-b766-4c46-9306-e9400b33f5b9";
    } else {
        $app_key = "e910d5a8-debd-46a2-9dcf-b6f5bc325d61";
    }
    
    $params = array(
        'app_key' => $app_key
    );
    
    return $params;
}

function onedrive_config(){
    if(app_server() == 1){
        $app_key = "e0be2a94-a58a-4fd5-99e4-1063df87d53b";
            
    } else if(app_server() == 2){
        $app_key = "542d9918-af3f-40e0-884c-931f3a5e2eb5";
        
    } else if(app_server() == 3){
        $app_key = "49e7a7b0-aad8-4293-a4ba-0102fb4c1799";
    
    } else if(app_server() == 4){
        $app_key = "32b025b3-3850-4cf7-b101-92c6752c2456";
    } else {
        $app_key = "6e1c22ef-3699-4e5b-9add-1692e2562d9a";
    }
    
    $params = array(
        'app_key' => $app_key
    );
    
    return $params;

}

function db_config(){
    
    if(app_server() == 1){
        $app_host = "localhost";
        $app_user = "root";
        $app_pass = "mysql";
    } else {
        $app_host = "smallbuilders-database.ckddrfaxcra0.ap-southeast-2.rds.amazonaws.com";
        $app_user = "root";
        $app_pass = "UnVYX7Av";
    }
    
    $params = array(
        'host' => $app_host,
        'user' => $app_user,
        'password' => $app_pass
    );
    
    return $params;

}

/**
* Code Changes: i.e.,
* <link href="https://www.smallbuilders.com.au/builder/css/general.css" rel="stylesheet" type="text/css">
* <link href="<?php echo asset_host(); ?>/builder/css/general.css" rel="stylesheet" type="text/css">
* 
* use app_host_name() for Application name
* use asset_host() for CSS, Javascripts, etc.
* use integration_callback() for Integration callbacks
*/

?>