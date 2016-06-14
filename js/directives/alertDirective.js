app.directive('alert', [ 'alertService', function(alertService) { 

  return { 
    restrict: 'E',
    templateUrl: 'templates/alert.html',
    controller: function($scope){

    	var confirmModal = $('#confirmModal');

    	$scope.active = false;
    	$scope.currentItem = {};

    	$scope.$on('openAlert', function(event){
    		$scope.active = true;
            $scope.currentItem = alertService.currentItem;
            console.log($scope.type);
    		confirmModal.modal({
    			show: true
    		});
    	})

    	$scope.ok = function(){
    		alertService.alertResponse(true);
    		confirmModal.modal('toggle');
    	}

    	$scope.cancel = function(){
    		alertService.alertResponse(false);
    		console.log('cancel');
    		confirmModal.modal('toggle');
    	}
    }
  } 
}]);