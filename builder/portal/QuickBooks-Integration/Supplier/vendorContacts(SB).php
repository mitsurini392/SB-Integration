
<?php

require_once('../vendor/autoload.php');
require_once "../db_connect.php";

use QuickBooksOnline\API\DataService\DataService;

$config = include('../config.php');

session_start();
if(isset($_SESSION["client_id"])) {
    //Has Session
}
else {
    header('Location:../login.php');
}

//API HISTORY
$request_uri = $_SERVER["REQUEST_URI"];
$client_id = $_SESSION["client_id"];

$sql = "INSERT INTO `_api_history` (`id`,`operation`, `client_id`, `timestamp`, `request_uri`, `request_code`, `method`, `request_body`, `error_message`) VALUES (NULL,'READ', '$client_id', CURRENT_TIMESTAMP, '$request_uri', '200', 'GET', NULL, NULL);";

if($connect->query($sql)) {
    //SUCCESS
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
    <link href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
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
                    url: "getCompanyInfo.php",
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
            <a href="#" class="btn btn-secondary active">Contacts</a>
            <a href="#" class="btn btn-secondary">Sales</a>
            <a href="../Purchase/purchase(SB).php" class="btn btn-secondary">Purchases</a>
            <a href="../TimeActivity/timeActivity.php" class="btn btn-secondary">Timesheet</a>
        </div>
        <br><br>
        
        <div id="contacts">
            <div class="btn-group">
                <a href="../Customer/customerContacts(SB).php" class="btn btn-secondary" id='btnCustomers'>Customers</a>
                <a href="../Employee/employeeContacts(SB).php" class="btn btn-secondary">Employees</a>
                <a href="../Supplier/vendorContacts(SB).php" class="btn btn-secondary active">Suppliers</a>
            </div>
        </div>
        <br>
        <br>
        <div id="table">
            <div class='alert alert-warning'>
            Below Contacts are those Suppliers that exist in your Smallbuilders account but didn't exist in your QuickBooks account.
            </div>
            <nav class='nav nav-tabs nav-justified'>
                <a class='nav-item nav-link active' href='#'>Small Builders to Quickbooks</a>
                <a class='nav-item nav-link' href='vendorContacts.php'>Quickbooks to Smallbuilders</a>
            </nav>
            <table id='QBtoSB' class='table table-striped'>
                <thead>
                    <tr>
                        <td><input type='checkbox' onclick='checkAll(this);countIntegrate();'></td>
                        <td>Supplier Name</td>
                        <td>Supplier Address</td>
                        <td>Representative Name</td>
                        <td>Email Address</td>
                        <td>Phone Number</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        //GET FIELDS THAT HAVE QUICKBOOKS UID
                        require_once "../db_connect.php";

                        $quickbooks_uids = array();
                        $sql = "SELECT * FROM _relationship_db_suppliers WHERE quickbooks_uid IS NULL AND client_id = ".$_SESSION["client_id"];
                    
                        $query = $connect->query($sql);
                    
                        while($row = mysqli_fetch_array($query)) {
                            echo "<tr>
                            <td><input type='checkbox' class='form-control integrateCheck' onclick='countIntegrate()' value='".$row["id"]."'></td>
                            <td>".$row["supplier_name"]."</td>";
                            echo "<td>". $row["supplier_address"] ."</td>";
                            echo "<td>". $row["representative_name"]." " .$row["representative_lname"]."</td>";
                            echo "<td>". $row["representative_email"]."</td>";
                            echo "<td>Phone: ".$row["representative_phone"]."<br>Mobile: ".$row["representative_mobile"]."<br>Fax: ".$row["representative_fax"]."</td>";
                            echo "</tr>"; 
                        }

                    ?>
                </tbody>
            </table>
            <button id='btnIntegrate' class='mt-2 mb-5 float-right btn btn-success btn-lg' onclick='integrateVendor()' disabled>Integrate</button>
            <script>
                $("#QBtoSB").DataTable();         
            </script>
        </div>
        <hr style='clear: both'>
        <div id="table2">
            <br>
            <h3 class='text-center'>Reconciled Suppliers</h3>
            <br>
            <table id='ReconciledCust'class='table table-striped'>
                <thead>
                    <tr>
                        <td>Supplier Name</td>
                        <td>Supplier Address</td>
                        <td>Representative Name</td>
                        <td>Email Address</td>
                        <td>Phone Number</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        //GET RECONCILED CUSTOMER
                        require_once "../db_connect.php";

                        $records = array();
                        $sql = "SELECT * FROM _relationship_db_suppliers WHERE quickbooks_uid IS NOT NULL AND client_id = ".$_SESSION["client_id"];

                        $query = $connect->query($sql);

                        while($row = mysqli_fetch_array($query)) {
                            echo "<tr>
                                <td>".$row["supplier_name"]."</td>
                                <td>".$row["supplier_address"]."</td>
                                <td>".$row["representative_name"] ." ". $row["representative_lname"] . "</td>
                                <td>".$row["representative_email"]."</td>
                                <td>Phone: ".$row["representative_phone"]."<br>Mobile: ".$row["representative_mobile"]. "<br>Fax: ".$row["representative_fax"]."</td>
                            </tr>";
                        }
                        
                    ?>
                </tbody>         
            </table>
            <script>
            $("#ReconciledCust").DataTable(); 
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
            //RETRIEVE
            //vendor();
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

        function integrateVendor() {
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
            header.innerHTML = "<th>Supplier Name</th><th>Supplier Address</th><th>Representative Name</th><th>Email Address</th><th>Phone Number</th>";

            //Integrate All Checks
            var body = tbl.createTBody();
            for (let i = 0; i < integrateCheck.length; i++) {
                //Insert A Row
                var record = tbl.insertRow(-1);

                //Get Checked Record
                var id = integrateCheck[i].value;
                var supplier_name = integrateCheck[i].parentNode.parentNode.childNodes[3].innerHTML;
                var supplier_address = integrateCheck[i].parentNode.parentNode.childNodes[4].innerHTML;
                var representative_name = integrateCheck[i].parentNode.parentNode.childNodes[5].innerHTML;
                var email_address = integrateCheck[i].parentNode.parentNode.childNodes[6].innerHTML;
                var phone_number = integrateCheck[i].parentNode.parentNode.childNodes[7].innerHTML;

                //Add Record to Table
                body.innerHTML += "<tr id='tr"+id+"'><td>"+supplier_name+"</td><td>"+supplier_address+"</td><td>"+representative_name+"</td><td>"+email_address+"</td><td>"+phone_number+"</td></tr>";
                
                //Integrate Per Record
                $.ajax({
                    method: "post",
                    url: "vendorsToQB.php",
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
                
                //Send Email After all Integration Finish
                $(document).one("ajaxStop", function() {
                    sendEmail(tbl.innerHTML);
                });
            }
        }
        
        function sendEmail(tblContent) {
            //Generate Table
            var tbl = document.createElement("table");
            tbl.innerHTML = tblContent;
            //DO NOT CONTINUE IF THERE ARE NO SUCCESSFUL INTEGRATION
            if (tbl.getElementsByTagName("tbody")[0].innerHTML == "") {
                alert("No Integration were successful.");
                window.location.href = "vendorContacts(SB).php";
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
            var subj = "Small Builders Supplier successfully added to Quickbooks Contacts";
            //Add Description
            var desc = "You have successfully automated your Small Builders Supplier details into your Quickbooks account. These suppliers are now available in your Quickbooks Contacts with the following details.";
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
                            <a href='vendorContacts(SB).php' class='btn btn-secondary' style='width: 200px;'>Back to Integration</a>
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

        var getUrlParameter = function getUrlParameter(getURL,sParam) {
            var sPageURL = getURL,
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