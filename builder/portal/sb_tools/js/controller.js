/**

var app = angular.module('dashboardApp', []);
app.controller('clientInfoCtrl', function($scope, $http) {
console.log("app - called...");
	var client_id = GetQueryStringParams('ci');  
	console.log(client_id);
	
  	$scope.call_clientInfo = function(client_id){
		$http.get("http://www.smallbuilders.com.au/app/external/api/clientdetails.php?key=ea940703d15da4d81bfa6ffda9ff1a74&client_id=" + client_id).success(function(response) {
		if(response!="") {
				$scope.company_business_name = response[0].business_name;
				$scope.company_trading_name = response[0].trading_name;
				$scope.company_abn = response[0].abn;
				$scope.company_acn = response[0].acn;
				$scope.company_business_address = response[0].business_address;
				$scope.company_phone_number = response[0].phone_number;
				$scope.company_fax_number = response[0].fax_number;
				$scope.company_email_address = response[0].email_address;
				$scope.company_rep_name = response[0].rep_name;
				$scope.company_rep_jobtitle = response[0].rep_jobtitle;
				$scope.company_rep_phone_number = response[0].rep_phone_number;
				$scope.company_rep_mobile_number = response[0].rep_mobile_number;
				$scope.company_rep_email_address = response[0].rep_email_address;
				$scope.company_license_number = response[0].license_number;
				$scope.company_valid_until = response[0].valid_until;
				$scope.company_bank_account_name = response[0].bank_account_name;
				$scope.company_bsb_number = response[0].bsb_number;
				$scope.company_bank_account_number = response[0].bank_account_number;
			}
		});
	};	
});
*/