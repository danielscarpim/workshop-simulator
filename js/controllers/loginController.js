app.controller('loginController', [ '$scope', '$http','dataService','$location', function($scope, $http, dataService, $location) {

	$scope.inputData = [];
	$scope.admin_name = "";

	$scope.carouselInit = function(){
		angular.element('.carousel').carousel({
		  wrap: false,
		  interval: false,
		});
	}

	$scope.carouselInit();

	$scope.submitForm = function(usertype) {

		var encodedString = {};
		var loginScript = {};
		$scope.admin_name = this.inputData.admin_name;

		encodedString.user = 
			'username=' +  encodeURIComponent(this.inputData.user_name) +
			'&starsid=' +  encodeURIComponent(this.inputData.stars_id);

		encodedString.admin = 
			'adminname=' 	  +  encodeURIComponent(this.inputData.admin_name) +
			'&adminpassword=' +  encodeURIComponent(this.inputData.admin_password);

		loginScript.user  = 'php/userLogin.php'
		loginScript.admin = 'php/adminLogin.php'

		$http({
			method: 'POST',
			url: loginScript[usertype],
			data: encodedString[usertype],
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		})
		.success( function(data, status, headers, config) {

			if (usertype === 'user') {

				if (data.login === true) {
				window.location.href = data.url;

				} else {
					$scope.errorMsg = $scope.interFace.common.error[data.error_msg];
				}

			} else if (usertype === 'admin') {

				if (data.login === true) {
				dataService.admin = $scope.admin_name;
				$location.path("/admin");

				} else {
					$scope.errorMsg = $scope.interFace.common.error[data.error_msg];
				}
			}

		})
		.error( function(data, status, headers, config) {
			console.log('Unable to submit form');
		})
	}

}]);