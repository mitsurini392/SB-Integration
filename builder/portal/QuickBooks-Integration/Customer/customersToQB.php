<?php
require "../vendor/autoload.php";
include("../../../controller/connection.php");
include("../../../../theodore/formbuilder/controller/connection.php");

use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Customer;

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

    // //Get Token
    // $accessTokenKey = "eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..D-dRn7ITHUxmGAtmz5llhA._d3XLBw6jM4xMv3JHHhVAvBg_gQ4HiouCRCZaR5w5lZa7VGAdTQmyUZz5B6WXoZoBDcqpPt3i1rU-DTGxIklMbOoKTIKRwOj1lCbkeCS7ajy6xh2QPM93hZIPCKjK_SlruF5b9FmYCnXekdNZVaOsWzWyRJVw8FgJJEe8WapEsznIGK4i4tVniCfc5poU1JBymYQ6IVS2uyFFnRl7phaoTxx81G0Z26RPyPdPFzNkc1nS9cRPUmuulcX97ZUHrI4TFZUW3AYco5DY9arMp60dob9b5tJi0TRYbytZv6H-3-xLnA4h2UQGzhzMeo-if3y4EOvMWy0tmPvI_Cr_Fucn8N_92UJklP_3FuOqQUTbrmKhv4riUaLdKrkwBKWsxjO-C9leoSFfIpAyUKsUqdQ_QyQkbRRwIC3hbw1G4u7Tr8ulyuJ95I3J-B33niyMVVFhCQ38mBTwXtsTAY5lhqW10pPEEfiWnMoKwi8yDZ9_p879kvXacDUnC8dbTqIzdSiAlzD04GsDYkexp0md8QxeHHzKH_mMr7LJGXATzlJE8z4ZDNe9OYgsCD9kTJz7UwwBA016uWBurGMWXeqbIq5lY7dJqyWibdKTYngH6vk7VyDAEQktnbJ4erH0M5VPm_xS7STW95aBLl1G1uKPmJRcSzpSchkklzl89TkIkkLoIoPnvaD59QrV65lDAW2e3dC_pvruDrqW60zT0DCFL1Ojw8W2Nf0dxIqh1TM12drau93gvtNdF1Xhb0U3rm58hDQyMdU02d_cxpF1axWul5-bBOH48-bMiVC_Tuzr3K9wBIM1v14DDU8JYWStV9KPc1jtalzuiy0KfID4MHtnTIOmw.x9Lfi5OyznNuvi2QbzOglg";
    // $refreshTokenKey = "L011553415956HHjd3wx8W3ShDxjSKD8zkd66MluyOHiQZM1M3";
    // $realmId = "123146201844524";

    $id = $_POST["id"];

    $sql = "SELECT * FROM _relationship_db_customers WHERE id=$id";

    $query = $theodore_con->query($sql);

    while($row = mysqli_fetch_array($query)) {
        
            //POST
            $customer_name = $row["customer_name"];
            $customer_email = $row["customer_email"];
            $customer_address = $row["customer_address"];
            $customer_phone = $row["customer_phone"];
            $customer_mobile = $row["customer_mobile"];
            $customer_fax = $row["customer_fax"];
            $representative_name = $row["representative_name"];
            $representative_lname = $row["representative_lname"];

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
            $theResourceObj = Customer::create([
                "BillAddr" => [
                    "Line1" => "$customer_address",
                ],
                "GivenName" => "$representative_name",
                "FamilyName" => "$representative_lname",
                "Suffix" => "",
                "FullyQualifiedName" => "$customer_name",
                "CompanyName" => "$customer_name",
                "DisplayName" => "$customer_name",
                "PrimaryPhone" => [
                    "FreeFormNumber" => "$customer_phone"
                ],
                "Mobile" => [
                    "FreeFormNumber" => "$customer_mobile"
                ],
                "Fax" => [
                    "FreeFormNumber" => "$customer_fax"
                ],
                "PrimaryEmailAddr" => [
                    "Address" => "$customer_email"
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
                
                $sql = "UPDATE `_relationship_db_customers` SET `quickbooks_uid` = '$quickbooks_uid' WHERE `_relationship_db_customers`.`id` = $id;";

                
                if($theodore_con->query($sql)) {
                    echo "Success";

                    $sql = "INSERT INTO `_api_history` (`id`,`operation`, `client_id`, `timestamp`, `request_uri`, `request_code`, `method`, `request_body`, `error_message`) VALUES (NULL,'INSERT', '$client_id', CURRENT_TIMESTAMP, '$request_uri', '200', 'POST', '$request_body', NULL);";

                    if($theodore_con->query($sql)) {
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

    echo $error_msg;

    $sql = "INSERT INTO `_api_history` (`id`,`operation`, `client_id`, `timestamp`, `request_uri`, `request_code`, `method`, `request_body`, `error_message`) VALUES (NULL,'INSERT', '$client_id', CURRENT_TIMESTAMP, '$request_uri', '400', 'POST', '$request_body', '$error_msg');";

    
    if($connect->query($sql)) {
        echo $e->getMessage();
    }
    else {
        echo mysqli_error($connect);
    }
}