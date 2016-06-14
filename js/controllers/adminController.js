app.controller('adminController', ['$scope', 'dataService', 'alertService','$location', function($scope, dataService, alertService, $location){

	$scope.users = [];
	$scope.dealers = [];
	$scope.locations = [];
	$scope.admins = [];
	$scope.inputData = [];
	$scope.isEditing = false;

	$scope.type = 'users';

	$scope.switchTab = function(type){
		$scope.type = type;
		$scope.refresh();
	}

	// Get Users
	$scope.getUsers = function(){
		dataService.getUsers()
		.then(function(data){
			$scope.users = data.users;
		})
	}

	// Get Dealers
	$scope.getDealers = function(){
		dataService.getDealers()
		.then(function(data){
			$scope.dealers = data.dealers;
		})
	}

	// Get Locations
	$scope.getLocations = function(){
		dataService.getLocations()
		.then(function(data){
			$scope.locations = data.locations;
		})
	}

	// Get Admins
	$scope.getAdmins = function(){
		dataService.getAdmins()
		.then(function(data){
			$scope.admins = data.admins;
		})
	}

	// Update Data
	$scope.updateData = function(action,item){

		var type = item.inputData.type;

		if (action == 'delete') {
			alertService.openAlert(item, type);
			item.editable = false;
			
		} else {
			dataService.updateData(action, type, item);
		}

	}

	// add new row
    $scope.addRow = function(target){

    	var newRow = {};

    	if(target === $scope.users){
    		newRow = {'user_name':'','user_stars':'','user_dealer':'','user_score':0};
    	} else if (target === $scope.dealers){
    		newRow = {'dealer_id':'','dealer_code':'','dealer_location':''};
    	} else if (target === $scope.locations){
    		newRow = {'user_name':'','user_stars':'','user_dealer':'','user_score':0};
    	}

        $scope.removeEditable();
        newRow.editable = true;
        newRow.isNew = true;
        target.unshift(newRow);
        $scope.isEditing = true;

    }

	// Edit Row
	$scope.editRow = function(target) {
		$scope.removeEditable();
        target.editable = true;  
    }

    // Close Row
    $scope.closeRow = function(target) {
    	target.editable = false;
    	$scope.isEditing = false;
    	if (target.isNew === true){
        	$scope[$scope.type].splice(target,1);
        } 
    }

    // Revert editable rows
    $scope.removeEditable = function(){
    	$scope.isEditing = false;
    	for (var i =0; i < $scope.users.length; i++) {
			$scope.users[i].editable = false;
			if ($scope.users[i].isNew === true) {
				$scope.users.splice(i,1);
			}
		}
    }

	// Refresh view
	$scope.refresh = function(){
		$scope.getDealers();
		$scope.getUsers();
		$scope.getLocations();
		$scope.getAdmins();
		$scope.isEditing = false;
		$scope.removeEditable();
	}

	// Logoff
	$scope.logoff = function(){
		dataService.admin = null;
		$location.path("/");
	}

	// Refresh view when info is updated
	$scope.$on('update', function(event){
		$scope.refresh();
	});

	// Get response from alert
	$scope.$on('alertResponse', function(event){
		if(alertService.confirm === true) {
			var type = alertService.currentItem.inputData.type;
			dataService.updateData('delete', type, alertService.currentItem);
			alertService.closeAlert();
		} else {
			alertService.closeAlert();
		}
	})

	// init

	$scope.refresh();

}]);