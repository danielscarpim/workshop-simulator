app.directive('scoreBoard', ['$http', 'dataService', function($http, dataService) { 

  return { 
    restrict: 'E',  
    templateUrl: 'templates/ranking.html',
    controller: function($scope){
    	$scope.users = [];

    	// Get Users
        $scope.getUsers = function(){
            dataService.getUsers()
            .then(function(data){
                $scope.users = data.users;
            })
        }
        $scope.getUsers();
    }
  } 
}]);