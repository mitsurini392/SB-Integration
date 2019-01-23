<?php

require_once('../vendor/autoload.php');
use QuickBooksOnline\API\DataService\DataService;

$config = include('../config.php');

session_start();
if(isset($_SESSION["client_id"])) {
    //Has Session
}
else {
    header('Location:../login.php');
}

$dataService = DataService::Configure(array(
    'auth_mode' => 'oauth2',
    'ClientID' => $config['client_id'],
    'ClientSecret' =>  $config['client_secret'],
    'RedirectURI' => $config['oauth_redirect_uri'],
    'scope' => $config['oauth_scope'],
    'baseUrl' => "Development"
));

$OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
$authUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();


// Store the url in PHP Session Object;
$_SESSION['authUrl'] = $authUrl;

//set the access token using the auth object
if (isset($_SESSION['sessionAccessToken'])) {

    $accessToken = $_SESSION['sessionAccessToken'];
    $accessTokenJson = array('token_type' => 'bearer',
        'access_token' => $accessToken->getAccessToken(),
        'refresh_token' => $accessToken->getRefreshToken(),
        'x_refresh_token_expires_in' => $accessToken->getRefreshTokenExpiresAt(),
        'expires_in' => $accessToken->getAccessTokenExpiresAt()
    );
    $dataService->updateOAuth2Token($accessToken);
    $oauthLoginHelper = $dataService -> getOAuth2LoginHelper();
    $CompanyInfo = $dataService->getCompanyInfo();
}
else { 
    echo "<script>
        alert('Please Connect to Quickbooks');
        window.location.href = '../index.php';
    </script>";
}


?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
    <link href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script>

        var url = '<?php echo $authUrl; ?>';

        var OAuthCode = function(url) {


            //SHOW LOGIN WINDOW
            this.loginPopup = function (parameter) {
                this.loginPopupUri(parameter);
            }
            
            //CREATE LOGIN WINDOW
            this.loginPopupUri = function (parameter) {

                // Launch Popup
                var parameters = "location=1,width=800,height=650";
                parameters += ",left=" + (screen.width - 800) / 2 + ",top=" + (screen.height - 650) / 2;

                var win = window.open(url, 'connectPopup', parameters);
                var pollOAuth = window.setInterval(function () {
                    try {

                        if (win.document.URL.indexOf("code") != -1) {
                            window.clearInterval(pollOAuth);
                            win.close();
                            location.reload();
                        }
                    } catch (e) {
                        console.log(e)
                    }
                }, 100);
            }
        }

        var apiCall = function() {

            //GET COMPANY NAME
            this.getCompanyName = function() {
                $.ajax({
                    type: "GET",
                    url: "../getCompanyName.php",
                }).done(function( msg ) {
                    $( '#orgName' ).html( msg );
                });
            }
            
            //GET COMPANY INFO
            this.getCompanyInfo = function() {
                $.ajax({
                    type: "GET",
                    url: "../getCompanyInfo.php",
                }).done(function( msg ) {
                    $( '#apiCall' ).html( msg );
                });
            }
            
            //REFRESH TOKEN
            this.refreshToken = function() {
                $.ajax({
                    type: "POST",
                    url: "refreshToken.php",
                }).done(function( msg ) {

                });
            }
        }


        var oauth = new OAuthCode(url);
        var apiCall = new apiCall();
    </script>
</head>
<body>

<div class="container">

    <div>
        QuickBooks
    </div>
    <br><br>

    <div id="conn_status">
        <?php
            if(isset($accessTokenJson)) {
                echo "Status: <p style='color: green; display: inline'>Connected</p><br>";
                echo "Organisation: <p id='orgName' style='display: inline'></p><br>";
                echo "<a href='../logout.php'><img src='../disconnect.png'></a>";
            }
            else {
                echo "Status: <p style='color: red; display: inline'>Not Connected</p><br><br>";
                echo "<a class='imgLink' href='#' onclick='oauth.loginPopup()'><img src='../views/C2QB_green_btn_lg_default.png' width='178' /></a>
                <hr />";
            } 
        ?>
    </div>
    <br>

        <div class="btn-group">
            <a href="../Customer/customerContacts.php" class="btn btn-secondary">Contacts</a>
            <a href="../Sales/sales.php" class="btn btn-secondary">Sales</a>
            <a href="../Purchase/purchase(SB).php" class="btn btn-secondary">Purchases</a>
            <a href="#" class="btn btn-secondary active">Timesheet</a>
        </div>
        <br><br>
        
        <div id="contacts">
            <div class="btn-group">
                <a href="#" class="btn btn-secondary active" id='btnCustomers'>Register</a>
                <a href="timeActivity(History).php" class="btn btn-secondary">History</a>
            </div>
        </div>
        <br>
        <br>
        <div id="table">
            <form method='get' action='timeActivity.php'>
            <div class="row">
                <div class="col-md-4">
                    <select class="search_employee form-control" name="employee_id">
                        <?php
                            require_once "../db_connect.php";

                            $sql = "SELECT id,employee_name,employee_lastname FROM _relationship_db_employee WHERE quickbooks_uid IS NOT NULL and client_id = ".$_SESSION["client_id"];

                            $query = $connect->query($sql);

                            while($row = mysqli_fetch_array($query)) {
                                echo "<option value='".$row["id"]."'>".$row["employee_name"]." ".$row["employee_lastname"]."</option>";
                            }                         
                        ?>
                    </select>
                </div>               
                <div class="col-md-2">
                    <button class='btn btn-success'>Submit</button> 
                </div>       
            </div>
            </form>
            <br>
            <table id='QBtoSB' class='table table-striped'>
                <thead>
                    <tr>
                        <td><input type='checkbox' onclick='checkAll(this);countIntegrate();'></td>
                        <td>Project Name</td>
                        <td>Cost Centre</td>
                        <td>Date Worked</td>
                        <td>Total Paid Hours</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if(!empty($_GET)) {

                                $employee_id = $_GET["employee_id"];
                                require_once "../db_connect.php";

                                $records = array();
                                $sql = "SELECT * FROM _relationship_db_timesheet inner JOIN _relationship_db_employee on _relationship_db_timesheet.employee_id = _relationship_db_employee.id WHERE _relationship_db_employee.quickbooks_uid IS NOT NULL AND _relationship_db_timesheet.client_id = ".$_SESSION["client_id"]. " AND _relationship_db_employee.id = $employee_id AND _relationship_db_timesheet.quickbooks_uid IS NULL";

                                $query = $connect->query($sql);

                                while($row = mysqli_fetch_array($query)) {
                                    echo "<tr>
                                        <td><input type='checkbox' class='form-control integrateCheck' onclick='countIntegrate();' value=".$row[0]."></td>
                                        <td>".$row["project_name"]."</td>
                                        <td>".$row["cost_centre"]."</td>
                                        <td>".$row["date_worked"]."</td>
                                        <td>".$row["total_paid_hours"]."</td>
                                    </tr>";
                                }
                        }
                    ?>
                </tbody> 
            </table>
            <button id='btnIntegrate' class='mt-2 mb-5 float-right btn btn-success btn-lg' onclick='integrateTime()' disabled>Integrate</button>
            <script>            
                $("#QBtoSB").DataTable();         
            </script>
        </div>
    <!-- <pre id="accessToken">
        <style="background-color:#efefef;overflow-x:scroll"><?php
    $displayString = isset($accessTokenJson) ? $accessTokenJson : "No Access Token Generated Yet";
    echo json_encode($displayString, JSON_PRETTY_PRINT); ?>
    </pre> -->

</div>
</body>
    <script>
        //TOKENS and IDs
        var access_token = "<?php $json = json_encode($accessTokenJson, JSON_PRETTY_PRINT);; 
                                    $json = json_decode($json, true);
                                    echo $json["access_token"];?>";
        var refresh_token = "<?php $json = json_encode($accessTokenJson, JSON_PRETTY_PRINT);; 
                                    $json = json_decode($json, true);
                                    echo $json["refresh_token"];?>";
        var realm_id = "<?php echo $accessToken->getRealmID(); ?>";


        window.onload = function () {
            //GET COMPANY NAME
            apiCall.getCompanyName();

            $(".search_employee").val(getUrlParameter('employee_id'));
            //MAKE SELECT2
            $('.search_employee').select2();
            //CHANGE SELECT VALUE ACCORDING TO SELECTED      
        }

        function countIntegrate() {
            var integrateCheck = document.getElementsByClassName("integrateCheck");
            var checks = 0;
            for (let i = 0; i < integrateCheck.length; i++) {
                if(integrateCheck[i].checked == true) {
                    checks++;
                }
            }
            if(checks == 0) {
                document.getElementById("btnIntegrate").disabled = true;
            }
            else {
                document.getElementById("btnIntegrate").disabled = false;
            }
        }

        function checkAll(elem) {
            var integrateCheck = document.getElementsByClassName("integrateCheck");
            if(elem.checked == true) {
                for (let i = 0; i < integrateCheck.length; i++) {
                    integrateCheck[i].checked = true;
                }
            }
            else {
                for (let i = 0; i < integrateCheck.length; i++) {
                    integrateCheck[i].checked = false;
                }
            }
        }

        function integrateTime() {
            //Add Loading
            $.confirm({
                onOpenBefore: function () {
                    this.showLoading()
                }
            });

            //Collect All Integrate Checks
            var integrateCheck = document.querySelectorAll('.integrateCheck:checked');

            //Create a Table (this table will be put on SB to QB successful Message)
            var tbl = document.createElement("table");
            var header = tbl.createTHead();
            header.innerHTML = "<th>Project Name</th><th>Cost Centre</th><th>Date Worked</th><th>Total Paid Hours</th>";

            //Integrate All Checks
            var body = tbl.createTBody();
            for (let i = 0; i < integrateCheck.length; i++) {
                //Insert A Row
                var record = tbl.insertRow(-1);

                //Get Checked Record
                var id = integrateCheck[i].value;
                var project_name = integrateCheck[i].parentNode.parentNode.childNodes[3].innerHTML;
                var cost_centre = integrateCheck[i].parentNode.parentNode.childNodes[5].innerHTML;
                var date_worked = integrateCheck[i].parentNode.parentNode.childNodes[7].innerHTML;
                var total_paid_hours = integrateCheck[i].parentNode.parentNode.childNodes[9].innerHTML;
                
                //Add Record to Table
                body.innerHTML += "<tr id='tr"+id+"'><td>"+project_name+"</td><td>"+cost_centre+"</td><td>"+date_worked+"</td><td>"+total_paid_hours+"</td></tr>";

                $.ajax({
                    method: "post",
                    url: "timeActivity(Integrate).php",
                    data: "id=" + id + "&access_token="+ access_token + "&refresh_token=" + refresh_token + "&realm_id=" + realm_id,
                    success: function (data) {
                        if(data == "Success") {
                            //DO NOT DELETE RECORD  
                        }
                        else {
                            //DELETE RECORD IF FAILED TO INTEGRATE
                            $(tbl).find("#tr" + getUrlParameter(this.data,"id")).remove();
                        }
                    }
                });
                $(document).one("ajaxStop", function() {
                    sendEmail(tbl.innerHTML);
                });
            }
            // $.confirm({
            //     title: "Smallbuilders to Quickbooks",
            //     columnClass: "large",
            //     theme: "modern",
            //     content: "<table class='table'><tr><th>Project Name</th><th>Cost Centre</th><th>Date Worked</th><th>Total Paid Hours</th></tr></table>",
            //     onOpenBefore: function () {                    
            //         //PUT THIS TO VARIABLE
            //         var confirmJS = this;
            //         confirmJS.buttons.ok.disable();

            //         //Collect all QuickBooks ids na nacheckan
            //         var integrateCheck = document.querySelectorAll('.integrateCheck:checked');

            //         //Retrieve Customer Info
            //         for (let i = 0; i < integrateCheck.length; i++) {
            //             var id = integrateCheck[i].value;
            //             var project_name = integrateCheck[i].parentNode.parentNode.childNodes[3].innerHTML;
            //             var cost_centre = integrateCheck[i].parentNode.parentNode.childNodes[5].innerHTML;
            //             var date_worked = integrateCheck[i].parentNode.parentNode.childNodes[7].innerHTML;
            //             var total_paid_hours = integrateCheck[i].parentNode.parentNode.childNodes[9].innerHTML;

            //             //Add Integrate Status
            //                 //GET QUICKBOOKS RECORD USING ID
            //                 $.ajax({
            //                     method: "post",
            //                     url: "timeActivity(Integrate).php",
            //                     data: "access_token="+ access_token + "&refresh_token=" + refresh_token + "&realm_id=" + realm_id + "&id=" + id,
            //                     success: function (data) {    
            //                         if(data == "Success") {
            //                             confirmJS.$content.find('table').append("<tr><td>"+project_name+"</td><td>"+cost_centre+"</td><td>"+date_worked+"</td><td>"+total_paid_hours+"</td></tr>");    
            //                         }
            //                         else {
            //                             confirmJS.$content.find('table').append("<tr><td>"+project_name+"</td><td>"+cost_centre+"</td><td>"+date_worked+"</td><td>"+total_paid_hours+"</td></tr>");
            //                         }

            //                         //Check if All Request is Done
            //                         if(i == integrateCheck.length - 1) {
            //                             $( document ).ajaxStop(function(){
            //                                 confirmJS.buttons.ok.enable();
            //                             });
            //                         }
            //                     }
            //                 });
            //         }
            //     },
            //     buttons: {
            //         ok: {
            //             action: function () {
            //                 window.location.href = "timeActivity.php";
            //             }
            //         }
            //     }
            // });
        }

        function sendEmail(tblContent) {
            //Generate Table
            var tbl = document.createElement("table");
            tbl.innerHTML = tblContent;
            //DO NOT CONTINUE IF THERE ARE NO SUCCESSFUL INTEGRATION
            if (tbl.getElementsByTagName("tbody")[0].innerHTML == "") {
                alert("No Integration were successful.");
                window.location.href = "timeActivity.php";
                return;
            }
            //Add Style to every th 
            var th = tbl.getElementsByTagName("th");
            var td = tbl.getElementsByTagName("td");
            //Loop to th
            for (let i = 0; i < th.length; i++) {
                th[i].setAttribute("style","border:solid 1px #ccc; text-align:center; padding: 4px 0px 4px 7px;");  
            }
            //Loop to td
            for (let i = 0; i < td.length; i++) {
                td[i].setAttribute("style","border:solid 1px #ccc; text-align:center; padding: 4px 0px 4px 7px;");
            }
            //Add Subject
            var subj = "Small Builders Timesheet successfully added to Quickbooks";
            //Add Description
            var desc = "You have successfully automated your Small Builders Timesheet details into your Quickbooks account. These customers are now available as a draft in your Quickbooks Timesheet with the following details.";
            //Send Email
            //Change Message Into
            $.ajax({
                method: "post",
                url: "../sendMail.php",
                data: "tblcontent=" + tbl.innerHTML + "&subj="+ subj + "&desc=" + desc,
                success: function (data) {
                    //Change Whole Body InnerHTML
                    var body = document.getElementsByTagName("body")[0];
                    body.innerHTML = `<div class="mt-5 card col-md-8 offset-2" style='background: #FCFCFC; padding: 20px 20px 20px 20px;'>
                        <p style='color: green'>Success! A copy of your submission has been emailed to you.</p>
                        
                        <table class='table table-striped'>`+tblContent+`</table>
                        
                        <br>
                        <div class='text-center'>
                            <a href='timeActivity.php' class='btn btn-secondary' style='width: 200px;'>Back to Integration</a>
                        </div>
                    </div>`;
                }
            });
        }


        function convertNulltoEmpty(str) {
            try {
                if(str == null ){
                    return "";
                }
                else {
                    return str;
                }
            } catch (error) {
                return "";
            }
        }

        var getUrlParameter = function getUrlParameter(sParam) {
            var sPageURL = window.location.search.substring(1),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                }
            }
        };
    </script>
</html>