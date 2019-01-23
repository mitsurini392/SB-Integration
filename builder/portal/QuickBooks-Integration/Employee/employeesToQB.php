<?php
require "../vendor/autoload.php";


use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Employee;

session_start();
$client_id = $_SESSION["client_id"];
$request_body = json_encode($_REQUEST);
$request_uri = $_SERVER["REQUEST_URI"];


try {
            // Prep Data Services
            $config = include('../config.php');
            //Get Token
            $accessTokenKey = $_POST["access_token"];
            $refreshTokenKey = $_POST["refresh_token"];
            $realmId = $_POST["realm_id"];

            require_once "../db_connect.php";
            //POST
            $id = $_POST["id"];

            $sql = "SELECT * FROM _relationship_db_employee WHERE id=$id";

            $query = $connect->query($sql);

            while($row = mysqli_fetch_array($query)) {
                        
                    $employee_name = $row["employee_name"];
                    $employee_lname = $row["employee_lastname"];
                    $employee_address_line1 = $row["employee_address_line1"];
                    $employee_address_suburb = $row["employee_address_suburb"];
                    $employee_address_country = $row["employee_address_country"];
                    $employee_address_postcode = $row["employee_address_postcode"];
                    $employee_birthday = $row["employee_birthday"];
                    $employee_startdate = $row["employee_startdate"];
                    $employee_email = $row["employee_email"];
                    $employee_phone = $row["employee_phone"];
                    $employee_mobile = $row["employee_mobile"];
                    $employee_fax = $row["employee_fax"];
                    $employee_number = $row["employee_number"];
                    $employee_rate = $row["employee_rate"];



                    $dataService = DataService::Configure(array(
                        'auth_mode' => 'oauth2',
                        'ClientID' => $config['client_id'],
                        'ClientSecret' =>  $config['client_secret'],
                        'RedirectURI' => $config['oauth_redirect_uri'],
                        'accessTokenKey' => $accessTokenKey,
                        'refreshTokenKey' => $refreshTokenKey,
                        'QBORealmID' => $realmId,
                        'baseUrl' => "Development"
                    ));


                    $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");
                    $dataService->throwExceptionOnError(true);
                    //Add a new Vendor
                    $theResourceObj = Employee::create([
                        "EmployeeNumber" => "$employee_number",
                        "PrimaryAddr" => [
                        "Line1" => "$employee_address_line1",
                        "City" => "$employee_address_suburb",
                        "Country" => "$employee_address_country",
                        "PostalCode" => "$employee_address_postcode",
                        ],
                        "BirthDate" => "$employee_birthday",
                        "HiredDate" => "$employee_startdate",
                        "GivenName" => "$employee_name",
                        "FamilyName" => "$employee_name",
                        "DisplayName" => "$employee_name $employee_lname",
                        "PrimaryPhone" => [
                        "FreeFormNumber" => "$employee_phone",
                        ],
                        "Mobile" => [
                        "FreeFormNumber" => "$employee_mobile",
                        ]
                    ]);

                    $resultingObj = $dataService->Add($theResourceObj);
                    $error = $dataService->getLastError();
                    if ($error) {
                        echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
                        echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                        echo "The Response message is: " . $error->getResponseBody() . "\n";
                    }

                    else {
                        // UPDATE QUICKBOOKS_UID IN DATABASE   
                        $quickbooks_uid = $resultingObj->Id;
                        
                        $sql = "UPDATE `_relationship_db_employee` SET `quickbooks_uid` = '$quickbooks_uid' WHERE `_relationship_db_employee`.`id` = $id;";

                        
                        if($connect->query($sql)) {
                            echo "Success";
                            //INSERT INT API HISTORY

                            $sql = "INSERT INTO `_api_history` (`id`,`operation`, `client_id`, `timestamp`, `request_uri`, `request_code`, `method`, `request_body`, `error_message`) VALUES (NULL,'INSERT', '$client_id', CURRENT_TIMESTAMP, '$request_uri', '200', 'POST', '$request_body', NULL);";

                            if($connect->query($sql)) {
                                //SUCCESS
                            }
                            else {
                                //
                            }
                        }
                        else {
                            //
                        }
                    }
            }
} 

catch (Exception $e) {
    //Log Error
    $error_msg = $e->getMessage();

    $sql = "INSERT INTO `_api_history` (`id`,`operation`, `client_id`, `timestamp`, `request_uri`, `request_code`, `method`, `request_body`, `error_message`) VALUES (NULL,'INSERT', '$client_id', CURRENT_TIMESTAMP, '$request_uri', '400', 'POST', '$request_body', '$error_msg');";

    
    if($connect->query($sql)) {
        echo $e->getMessage();
    }
    else {
        echo mysqli_error($connect);
    }
}